<?php if (!defined('TL_ROOT'))
       die('You can not access this file directly!');
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 * Formerly known as TYPOlight Open Source CMS.
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 * PHP version 5
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 * @filesource
 */
/**
 * Class ModuleGewinnspielTeilnehmer
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 */
abstract class ModuleGewinnspiel extends Module
{
       /**
        * Salt
        * @var string
        */
       protected $salt = '9rweWERE678!è3#§443sfd';
       /**
        * save the encoded value of the code in the db
        * @var bool
        */
       protected $crypt_code = false;
       /**
        * Status
        * @var string
        */
       protected $status;
       /**
        * This array contains all the user data
        * @var array
        */
       protected $userData;

       /**
        * @param string $strCode
        * @return string
        */
       protected function cryptCode($strCode)
       {
              return $this->crypt_code ? sha1($this->salt . $strCode) : $strCode;
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
              foreach ($arrSelectedFields as $fieldname) {
                     $hasError = false;
                     //tableless or not
                     if ($this->tableless) {
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
                     if ($this->Input->post('FORM_SUBMIT') == $formId) {
                            $objWidget->validate();
                            if ($objWidget->hasErrors()) {
                                   $objWidget->value = '';
                                   $hasError = true;
                            }
                     }

                     // add value to the fields
                     if ($fieldname != 'code') {
                            $objWidget->value = isset($this->userData[$fieldname]) ? $this->userData[$fieldname] : '';
                     } else {
                            $objWidget->value = '';
                     }
                     $arrFields[$fieldname] = $objWidget->parse();
                     $row++;
              }
              // if all forms are filled correctly check the code and reload the page
              // the code status will be found in the session
              if (!$hasError && $this->Input->post('FORM_SUBMIT') == $formId) {
                     $this->validateCode($this->Input->post('code'));
                     $this->reload();
              }
              return $arrFields;
       }
}

