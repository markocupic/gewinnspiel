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

$GLOBALS['TL_DCA']['tl_member']['fields']['participantGewinnspiel'] = array(
       'label' => &$GLOBALS['TL_DCA']['tl_module']['fields']['participantGewinnspiel'],
       'inputType' => 'checkbox',
       'sorting' => true,
       'filter' => true,
       'search' => true,
       'options' => range(2013, 2016, 1),
       'eval' => array('readonly' => true, 'multiple' => true, 'tl_class' => '')
);

// add fields to palette
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('{personal_legend}','{gewinnspiel_legend},avisota_registration_lists,participantGewinnspiel;{personal_legend}', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
