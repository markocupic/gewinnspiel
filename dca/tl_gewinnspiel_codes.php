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
 * Table tl_gewinnspiel_codes
 */
$GLOBALS['TL_DCA']['tl_gewinnspiel_codes'] = array
(
       // Config
       'config' => array
       (
              'dataContainer' => 'Table',
              'enableVersioning' => true,
              'onload_callback' => array
              ( //
              ),
              'ondelete_callback' => array
              ( //
              ),
              'onsubmit_callback' => array
              ( //
              )
       ),
       // List
       'list' => array
       (
              'sorting' => array
              (
                     'mode' => 2,
                     'flag' => 1,
                     'panelLayout' => 'filter;sort,search,limit',
                     'fields' => array('id ASC')
              ),
              'label' => array
              (
                     'fields' => array('preisAbgeholt', 'id', 'code'),
                     'showColumns' => true,
                     //'format' => '<span style="#color#">[ID: %s] code: %s; locked: #locked#; #hasBeenPaidOn#</span>',
                     'label_callback' => array('tl_gewinnspiel_codes', 'labelCallback')
              ),
              'global_operations' => array
              (
                     'all' => array
                     (
                            'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                            'href' => 'act=select',
                            'class' => 'header_edit_all',
                            'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
                     )
              ),
              'operations' => array
              (
                     'edit' => array
                     (
                            'label' => &$GLOBALS['TL_LANG']['MSC']['edit'],
                            'href' => 'act=edit',
                            'icon' => 'edit.gif'
                     ),
                     'delete' => array
                     (
                            'label' => &$GLOBALS['TL_LANG']['MSC']['delete'],
                            'href' => 'act=delete',
                            'icon' => 'delete.gif',
                            'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
                     ),
                     'show' => array
                     (
                            'label' => &$GLOBALS['TL_LANG']['MSC']['show'],
                            'href' => 'act=show',
                            'icon' => 'show.gif'
                     )
              )
       ),
       // Palettes
       'palettes' => array
       (
              '__selector__' => array('hasBeenPaid', 'locked'),
              'default' => 'id,code,prizeGroup,locked,hasBeenPaid'
       ),
       // Subpalettes
       'subpalettes' => array(
              'hasBeenPaid' => 'hasBeenPaidOn',
              'locked' => 'token,validUntil,memberId,enteredCodeOn',
       ),
       // Fields
       'fields' => array
       (
              'id' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['id'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'inputType' => 'text',
                     'eval' => array('unique' => true, 'mandatory' => true, 'tl_class' => '', 'rgxp' => 'digit', 'readonly' => true),
              ),
              'code' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['code'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'inputType' => 'text',
                     'eval' => array('unique' => true, 'mandatory' => true, 'tl_class' => '', 'rgxp' => 'alnum'),
              ),
              'locked' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['locked'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'checkbox',
                     'isBoolean' => true,
                     'eval' => array('submitOnChange' => true, 'tl_class' => ''),
              ),
              'token' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['token'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'text',
                     'eval' => array('tl_class' => '', 'rgxp' => 'alnum', 'readonly' => true),
              ),
              'prizeGroup' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['prizeGroup'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'select',
                     'foreignKey' => 'tl_gewinnspiel_preise.CONCAT("[ID: ", id, "] " , name)',
                     'eval' => array('tl_class' => ''),
              ),
              'memberId' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['memberId'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'select',
                     'foreignKey' => 'tl_member.CONCAT("[ID: ", id, "] ", firstname ," ", lastname)',
                     'eval' => array('includeBlankOption' => true, 'tl_class' => ''),
              ),
              'enteredCodeOn' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['enteredCodeOn'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'text',
                     'eval' => array('tl_class' => '', 'rgxp' => 'datim'),
              ),
              'validUntil' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['validUntil'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'text',
                     'eval' => array('tl_class' => '', 'rgxp' => 'datim'),
              ),
              'hasBeenPaidOn' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['hasBeenPaidOn'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'text',
                     'eval' => array('tl_class' => '', 'rgxp' => 'datim'),
              ),
              'hasBeenPaid' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['hasBeenPaid'],
                     'exclude' => true,
                     'search' => true,
                     'sorting' => true,
                     'filter' => true,
                     'inputType' => 'checkbox',
                     'isBoolean' => true,
                     'eval' => array('submitOnChange' => true, 'tl_class' => ''),
              )
       )
);
/**
 * Class tl_gewinnspiel_codes
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 */
class tl_gewinnspiel_codes extends Backend
{
       /**
        * @param array
        * @param string
        * @param DataContainer
        * @param array
        * @return string
        */
       public function labelCallback($row, $label, DataContainer $dc, $args)
       {
              $args[0] = $row['hasBeenPaid'] ? '<span style="color:red">Preis abgeholt: true</span>' : '<span>Preis abgeholt: false</span>';
              return $args;
       }
}