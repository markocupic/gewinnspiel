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
$GLOBALS['TL_DCA']['gewinnspiel'] = array(
       // Config
       'fields' => array(
              'gender' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['gewinnspiel']['gender'],
                     'reference' => &$GLOBALS['TL_LANG']['gewinnspiel'],
                     'inputType' => 'radioButton',
                     'options' => array('male', 'female'),
                     'eval' => array('class' => 'block input_gender', 'mandatory' => true, 'required' => true)
              ),
              'firstname' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['gewinnspiel']['firstname'],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block input_firstname', 'required' => true, 'rgxp' => 'alpha')
              ),
              'lastname' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['gewinnspiel']['lastname'],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block input_lastname', 'required' => true, 'rgxp' => 'alpha')
              ),
              'email' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['gewinnspiel']['email'],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block input_email', 'required' => true, 'rgxp' => 'email')
              ),
              'code' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['gewinnspiel']['code'],
                     'inputType' => 'textField',
                     'eval' => array('class' => 'block input_code', 'required' => true, 'rgxp' => 'alnum')
              ),
              'agb' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['gewinnspiel']['agb'],
                     'inputType' => 'checkBox',
                     'reference' => &$GLOBALS['TL_LANG']['gewinnspiel'],
                     'value' => 'yes',
                     'options' => array('yes'),
                     'eval' => array('class' => 'block input_agb', 'mandatory' => true, 'required' => true)
              ),
              'captcha' => array
              (
                     'label' => &$GLOBALS['TL_LANG']['gewinnspiel']['captcha'],
                     'inputType' => 'captcha',
                     'eval' => array('class input_captcha' => 'block')
              ),
       )
);
