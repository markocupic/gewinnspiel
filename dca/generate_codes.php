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
$GLOBALS['TL_DCA']['generate_codes'] = array(
       // Config
       'fields' => array(
              'insert_mode' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['generate_codes']['insert_mode'][0],
                     'explanation' => &$GLOBALS['TL_LANG']['generate_codes']['insert_mode'][1],
                     'reference' => &$GLOBALS['TL_LANG']['generate_codes'],
                     'inputType' => 'selectMenu',
                     'options' => array('append_rows', 'delete_table'),
                     'eval' => array('class' => 'block', 'mandatory' => true, 'required' => true)
              ),
              'items' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['generate_codes']['items'][0],
                     'explanation' => &$GLOBALS['TL_LANG']['generate_codes']['items'][1],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block', 'rgxp' => 'digit', 'required' => true)
              ),
              'praefix' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['generate_codes']['praefix'],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block', 'rgxp' => 'alnum')
              ),
              'length' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['generate_codes']['length'][0],
                     'explanation' => &$GLOBALS['TL_LANG']['generate_codes']['length'][1],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block', 'required' => true, 'rgxp' => 'digit')
              ),
              'confirm' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['generate_codes']['confirm'],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block', 'required' => true, 'rgxp' => 'alpha', 'maxlength' => 3)
              ),
       )
);