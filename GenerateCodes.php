<?php if (!defined('TL_ROOT'))
       die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 * @filesource
 */

/**
 * Class GenerateCodes
 *
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 */
class GenerateCodes extends BackendModule
{
       /**
        * tableless layout
        * @var bool
        */
       protected $tableless = false;

       /**
        * default template
        * @var string
        */
       protected $strTemplate = 'be_generate_codes';

       public function compile()
       {
              $this->Template->mode = 'show_form';
              if ($_SESSION['gewinnspiel']['generate_codes']) {
                     // generate codes
                     $post = $_SESSION['gewinnspiel']['post'];
                     $items = $post['items'];
                     $prefix = $post['praefix'];

                     unset($_SESSION['gewinnspiel']);
                     $arrCodes = array();

                     // append new entries or truncate table before inserting new entries
                     if ($post['insert_mode'] == 'delete_table') {
                            $this->Database->execute('TRUNCATE TABLE tl_gewinnspiel_codes');
                     } else {
                            $objDb = $this->Database->execute('SELECT code FROM tl_gewinnspiel_codes');
                            while ($objDb->next())
                            {
                                    $arrCodes[] = $objDb->code;
                            }

                     }

                     // code length
                     $len = $post['length'];
                     if ($len < strlen((string)$items) + 2) {
                            $length = strlen($items) + 2;
                     } else {
                            $length = $len;
                     }
                     $m = 0;
                     for ($i = 1; $i <= $items; $i++) {
                            $m++;
                            do {
                                   $code = $prefix . substr(sha1(rand(100000000, 9999999999) . md5(microtime())), 0, $length);
                            } while (in_array($code, $arrCodes));
                            $arrCodes[] = $code;
                            $set = array(
                                   'code' => $code,
                                   'prizeGroup' => $m
                            );
                            $this->Database->prepare('INSERT INTO tl_gewinnspiel_codes %s')->set($set)->executeUncached();
                            $m = ($m == 10 ? $m = 0 : $m);
                     }
                     $this->Template->mode = 'generated_codes';
              } else {
                     // display the form
                     $this->Template->formId = 'generate_codes';
                     $this->Template->action = $this->Environment->request;
                     $this->loadLanguageFile('generate_codes');
                     $this->loadDataContainer('generate_codes');
                     $dca = $GLOBALS['TL_DCA']['generate_codes'];
                     $arrFieldsFromDca = array('insert_mode', 'items', 'praefix', 'length', 'submit');
                     $this->Template->arrFields = $this->generateFields($dca, $arrFieldsFromDca, 'generate_codes');
              }
       }

       protected function generateFields($dca, $arrSelectedFields, $formId)
       {
              $row = 0;
              $arrFields = array();
              foreach ($arrSelectedFields as $fieldname) {
                     //tableless or not
                     if ($this->tableless) {
                            $dca['fields'][$fieldname]['eval']['tableless'] = true;
                     }
                     $arrField = $dca['fields'][$fieldname];
                     // get the widget class from the dca
                     $widgetClass = 'Form' . ucfirst($arrField['inputType']);
                     $objWidget = new $widgetClass($this->prepareForWidget($arrField, $fieldname));
                     if ($fieldname == 'submit') {
                            $objWidget->slabel = $arrField['label'];
                     }
                     $objWidget->rowClass = sprintf('row_%s  %s', $row, ($row % 2 == 0 ? 'even' : 'odd'));
                     // Validate widget
                     if ($this->Input->post('FORM_SUBMIT') == $formId) {
                            $objWidget->validate();
                            if ($objWidget->hasErrors()) {
                                   $objWidget->value = '';
                                   $objWidget->addError($GLOBALS['TL_LANG']['ERR']['invalidPass']);
                                   $hasError = true;
                            }
                     }
                     $arrFields[$fieldname] = $objWidget->parse();
                     $row++;
              }
              // if all forms are filled correctly check the code and reload the page
              // the code status will be found in the session
              if (!$hasError && $this->Input->post('FORM_SUBMIT') == $formId) {
                     $_SESSION['gewinnspiel']['generate_codes'] = true;
                     $_SESSION['gewinnspiel']['post'] = $_POST;
                     $this->reload();
              }
              return $arrFields;
       }
}