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

// fields
$GLOBALS['TL_LANG']['generate_codes']['insert_mode'] = array('Modus:', 'Entscheiden Sie, ob die Tabelle vor dem Anlegen der neuen keys gelöscht werden soll.');
$GLOBALS['TL_LANG']['generate_codes']['items'] = array('Anzahl:', 'Wie viele keys sollen angelegt werden?');
$GLOBALS['TL_LANG']['generate_codes']['praefix'] = 'Code-Präfix:';
$GLOBALS['TL_LANG']['generate_codes']['length'] = array('Key-Länge:', 'Maximale Länge ohne Präfix (40 Zeichen)');
$GLOBALS['TL_LANG']['generate_codes']['confirm'] = 'Bestätigen Sie den Vorgang mit "yes"';

//references
$GLOBALS['TL_LANG']['generate_codes']['append_rows'] = 'Codes an die bestehenden Einträge anhängen';
$GLOBALS['TL_LANG']['generate_codes']['delete_table'] = 'Bestehende Tabelle vorher löschen';