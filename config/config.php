<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Back end modules
 */
$GLOBALS['BE_MOD']['gewinnspiel']['tl_gewinnspiel_preise'] = array(
       'icon' => 'system/modules/gewinnspiel/assets/images/trophy.png',
       'tables' => array(
              'tl_gewinnspiel_preise'
       )
);
$GLOBALS['BE_MOD']['gewinnspiel']['tl_gewinnspiel_codes'] = array(
       'icon' => 'system/modules/gewinnspiel/assets/images/key.png',
       'tables' => array(
              'tl_gewinnspiel_codes'
       )
);
$GLOBALS['BE_MOD']['gewinnspiel']['generate_codes'] = array
(
       'icon' => 'system/modules/gewinnspiel/assets/images/award_star_gold.png',
       'callback' => 'GenerateCodes'
);

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 0, array
(
       'gewinnspiele' => array
       (
              'gewinnspiel_teilnehmer' => 'ModuleGewinnspielTeilnehmer',
              'gewinnspiel_verkaufspersonal' => 'ModuleGewinnspielVerkaufspersonal'
       )
 ));

