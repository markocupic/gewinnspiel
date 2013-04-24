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
$GLOBALS['TL_LANG']['gewinnspiel']['gender'] = 'Anrede';
$GLOBALS['TL_LANG']['gewinnspiel']['firstname'] = 'Ihr Vorname:';
$GLOBALS['TL_LANG']['gewinnspiel']['lastname'] = 'Ihr Nachname:';
$GLOBALS['TL_LANG']['gewinnspiel']['email'] = 'E-Mail:';
$GLOBALS['TL_LANG']['gewinnspiel']['code'] = 'Ihr Gewinncode:';
$GLOBALS['TL_LANG']['gewinnspiel']['code_2'] = 'Gewinncode:';
$GLOBALS['TL_LANG']['gewinnspiel']['captcha'] = 'Sicherheitsfrage';
$GLOBALS['TL_LANG']['gewinnspiel']['agb'] = 'Ich bin mit den Teilnahmebedingungen einverstanden.';
$GLOBALS['TL_LANG']['gewinnspiel']['yes'] = 'ja';

// add errors
$GLOBALS['TL_LANG']['gewinnspiel']['error_agb'] = 'Bitte bestätigen Sie die Teilnahmebedingungen.';
$GLOBALS['TL_LANG']['gewinnspiel']['error_gender'] = 'Bitte geben Sie Ihre Anrede an.';

//references
$GLOBALS['TL_LANG']['gewinnspiel']['male'] = 'Herr';
$GLOBALS['TL_LANG']['gewinnspiel']['female'] = 'Frau';
$GLOBALS['TL_LANG']['gewinnspiel']['yes'] = 'ja';

//messages for ModuleGewinnspielAbbuchen
$GLOBALS['TL_LANG']['gewinnspiel']['benutzer_nicht_registriert'] = 'Achtung Benutzer hat sich noch nicht registriert evtl. ungültiges oder gefälschtes Zertifikat!';
$GLOBALS['TL_LANG']['gewinnspiel']['ungueltiger_code'] = 'Der eingegebene Code "%s" ist kein Gewinncode!';
$GLOBALS['TL_LANG']['gewinnspiel']['einloesefrist_abgelaufen'] = 'Die Einlösefrist für diesen Code ist am %s abgelaufen.';
$GLOBALS['TL_LANG']['gewinnspiel']['gutschein_bereits_eingeloest'] = 'Dem Kunden wurde der Preis bereits am %s ausbezahlt!';
$GLOBALS['TL_LANG']['gewinnspiel']['kunden_auszahlen'] = 'Sie können den Kunden auszahlen, der Code ist gültig.';
$GLOBALS['TL_LANG']['gewinnspiel']['form'] = 'Geben Sie den Teilnehmercode ein. Den Code finden Sie auf dem Gewinnzertifikat des Kunden.';

//email
$GLOBALS['TL_LANG']['gewinnspiel']['email_subject_user'] =  'Ihre Gewinnbestätigung';
$GLOBALS['TL_LANG']['gewinnspiel']['email_subject_admin'] ='Neuer registrierter Teilnehmer beim Gewinnspiel';