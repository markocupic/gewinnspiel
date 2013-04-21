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
                     'panelLayout' => 'filter;sort,search,limit',
                     'fields' => array('id ASC')
              ),
              'label' => array
(
       'fields' => array('id', 'code', 'locked', 'hasBeenPaidOn'),
       'format' => '[ID: %s] code: %s locked: %s Preis abgeholt am: %s'
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
       'default' => 'code,locked,token,prizeGroup,validUntil,memberId,enteredCodeOn,hasBeenPaidOn'
),
       // Subpalettes
       'subpalettes' => array
(),
       // Fields
       'fields' => array
(
       'code' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['code'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('unique' => true, 'mandatory' => true, 'tl_class' => ''),
       ),
       'locked' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['locked'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'filter' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('tl_class' => ''),
       ),
       'token' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['token'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'filter' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('tl_class' => ''),
       ),
       'prizeGroup' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['prizeGroup'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'filter' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('tl_class' => ''),
       ),
       'validUntil' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['validUntil'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'filter' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('tl_class' => ''),
       ),
       'memberId' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['memberId'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'filter' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('tl_class' => ''),
       ),
       'enteredCodeOn' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['enteredCodeOn'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'filter' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('tl_class' => ''),
       ),
       'hasBeenPaidOn' => array
       (
              'label' => &$GLOBALS['TL_LANG']['tl_gewinnspiel_codes']['hasBeenPaidOn'],
              'exclude' => true,
              'search' => true,
              'sorting' => true,
              'filter' => true,
              'flag' => 1,
              'inputType' => 'text',
              'eval' => array('tl_class' => ''),
       )
)
);