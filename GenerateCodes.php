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
       protected $tableless = true;

       /**
        * default template
        * @var string
        */
       protected $strTemplate = 'be_generate_codes';

       /**
        * Generate Module
        */
       public function compile()
       {
              $GLOBALS['TL_CSS'][] = 'system/modules/gewinnspiel/assets/css/code_generator.css';

              $this->Template->mode = 'show_form';
              if ($_SESSION['gewinnspiel']['generate_codes'] && $_SESSION['gewinnspiel']['post']['confirm'] == 'yes')
              {
                     // generate codes
                     $post = $_SESSION['gewinnspiel']['post'];
                     $items = $post['items'];
                     $prefix = $post['praefix'];

                     unset($_SESSION['gewinnspiel']);
                     $arrCodes = array();

                     // append new entries or truncate table before inserting new entries
                     if ($post['insert_mode'] == 'delete_table')
                     {
                            $this->Database->execute('TRUNCATE TABLE tl_gewinnspiel_codes');
                     }
                     else
                     {
                            $objDb = $this->Database->execute('SELECT code FROM tl_gewinnspiel_codes');
                            while ($objDb->next())
                            {
                                   $arrCodes[] = $objDb->code;
                            }

                     }

                     // code length
                     $len = $post['length'];
                     if ($len < strlen((string)$items) + 2)
                     {
                            $length = strlen($items) + 2;
                     }
                     else
                     {
                            $length = $len;
                     }
                     $m = 0;
                     for ($i = 1; $i <= $items; $i++)
                     {
                            $m++;
                            do
                            {
                                   $arrLetters = range('f', 'z');
                                   $code = $prefix . substr(sha1(rand(100000000, 9999999999) . md5(microtime())), 0, $length);
                                   // replace 'e' and 'E' with a random letter -> avoid scientific notation in ms excel
                                   $code = str_replace(array('e','E'), $arrLetters[rand(0,15)], $code);
                                   // make sure that the code contains at least 1 letter
                                   $code = preg_replace('/[0-9]{1}$/', $arrLetters[rand(0,15)], $code);
                            } while (in_array($code, $arrCodes));
                            $arrCodes[] = $code;
                            $set = array(
                                   'code' => (string)$code,
                                   'prizeGroup' => $m
                            );
                            $this->Database->prepare('INSERT INTO tl_gewinnspiel_codes %s')->set($set)->executeUncached();
                            $m = ($m == 10 ? $m = 0 : $m);
                     }

                     $this->Template->mode = 'generated_codes';
                     $this->Template->keys = $arrCodes;
              }
              else
              {
                     // display the form
                     $this->Template->tableless = $this->tableless;
                     $this->Template->formId = 'generate_codes';
                     $this->Template->action = $this->Environment->request;
                     $this->loadLanguageFile('generate_codes');
                     $this->loadDataContainer('generate_codes');
                     $dca = $GLOBALS['TL_DCA']['generate_codes'];
                     $arrFieldsFromDca = array('insert_mode', 'items', 'praefix', 'length', 'confirm');
                     $this->Template->submittedFields = implode(',', $arrFieldsFromDca);
                     $this->Template->arrFields = $this->generateFields($dca, $arrFieldsFromDca, 'generate_codes', 'be_widget_gewinnspiel');
              }
       }

       /**
        * Parse Form Fields using the FormWidget classes
        * @param array $dca
        * @param array $arrSelectedField
        * @param string $formId
        * @param string $strTemplate
        * @return array
        */
       protected function generateFields($dca, $arrSelectedFields, $formId, $strTemplate = '')
       {
              $row = 0;
              $arrFields = array();
              foreach ($arrSelectedFields as $fieldname)
              {
                     //tableless or not
                     if ($this->tableless)
                     {
                            $dca['fields'][$fieldname]['eval']['tableless'] = true;
                     }
                     // get the field array
                     $arrField = $dca['fields'][$fieldname];

                     // get the widget class from the dca and create widget object
                     $widgetClass = 'Form' . ucfirst($arrField['inputType']);
                     $objWidget = new $widgetClass($this->prepareForWidget($arrField, $fieldname));

                     // set widget template
                     $objWidget->template = $strTemplate == '' ? $objWidget->template : $strTemplate;

                     // set explanation
                     $objWidget->explanation = $arrField['explanation'] ? $arrField['explanation'] : '';

                     // set the class property
                     $objWidget->class = $arrField['eval']['class'];

                     //set row class
                     $objWidget->rowClass = sprintf('row_%s  %s', $row, ($row % 2 == 0 ? 'even' : 'odd'));

                     // Validate widget
                     if ($this->Input->post('FORM_SUBMIT') == $formId)
                     {
                            $objWidget->validate();
                            if ($objWidget->hasErrors())
                            {
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
              if (!$hasError && $this->Input->post('FORM_SUBMIT') == $formId)
              {
                     $_SESSION['gewinnspiel']['generate_codes'] = true;
                     $_SESSION['gewinnspiel']['post'] = $_POST;
                     $this->reload();
              }
              return $arrFields;
       }
}