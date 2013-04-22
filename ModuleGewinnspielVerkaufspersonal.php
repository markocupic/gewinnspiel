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
 * Class ModuleGewinnspielVerkaufspersonal
 *
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 */

class ModuleGewinnspielVerkaufspersonal extends ModuleGewinnspiel
{
       /**
        * default template
        * @var string
        */
       protected $strTemplate = 'mod_gewinnspiel_verkaufspersonal';

       /**
        * Parse the template
        * eval SESSION and url params then route
        * @return string
        */
       public function generate()
       {
              $this->status = 'form';
              if (TL_MODE == 'BE')
              {
                     $objTemplate = new BackendTemplate('be_wildcard');

                     $objTemplate->wildcard = '### GEWINNSPIEL PREIS EINLOESEN ###';
                     $objTemplate->title = $this->headline;
                     $objTemplate->id = $this->id;
                     $objTemplate->link = $this->name;
                     $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

                     return $objTemplate->parse();
              }

              // store the Session in the class var status and then destroy it
              if (isset($_SESSION['gewinnspiel_preis_einloesen']))
              {
                     $this->status = $_SESSION['gewinnspiel_preis_einloesen']['status'];
                     $this->userData = $_SESSION['gewinnspiel_preis_einloesen']['userData'];
                     unset($_SESSION['gewinnspiel_preis_einloesen']);
              }
              else
              {
                     $this->userData = array();
                     $this->status = 'form';

              }

              return parent::generate();
       }

       /**
        * Generate module
        */
       protected function compile()
       {
              // load language file
              $this->loadLanguageFile('gewinnspiel');

              // switch by status
              $this->Template->status = $this->status;

              // all the data are stored in the array $this->Template->userData
              $arrUserData = is_array($this->userData) ? $this->userData : array();
              $this->Template->userData = $arrUserData;

              switch ($this->status)
              {
                     case 'benutzer_nicht_registriert':
                            break;
                     case 'ungueltiger_code':
                            break;
                     case 'einloesefrist_abgelaufen':
                            break;
                     case 'gutschein_bereits_eingeloest':
                            break;
                     case 'kunden_auszahlen':
                            $prizeSrc = $this->prizeImagesFolder . '/preis_' . $this->userData['prizeGroup'] . '.jpg';
                            $this->Template->srcPrizeImage = file_exists(TL_ROOT . '/' . $prizeSrc) ? $prizeSrc : '';
                            break;
                     case 'form':
                            $this->Template->formId = 'gewinnspiel_preis_einloesen';
                            $this->Template->action = $this->Environment->request;
                            $this->loadDataContainer('gewinnspiel');
                            // adapt the label
                            $GLOBALS['TL_DCA']['gewinnspiel']['fields']['code']['label'] = &$GLOBALS['TL_LANG']['gewinnspiel']['code_2'];
                            $dca = $GLOBALS['TL_DCA']['gewinnspiel'];
                            $arrFieldsFromDca = array('code', 'submit');
                            $this->Template->tableless = $this->tableless;
                            $this->Template->arrFields = $this->generateFields($dca, $arrFieldsFromDca, 'gewinnspiel_preis_einloesen');
                            break;
              }
       }

       /**
        * validate the code
        * @param string $strCode
        *
        */
       protected function validateCode($strCode)
       {
              $objCode = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE code = ?')->execute($this->cryptCode($strCode));
              if ($objCode->next())
              {
                     $this->setArrUserData($strCode);
                     if ($objCode->locked == '')
                     {
                            $_SESSION['gewinnspiel_preis_einloesen']['status'] = 'benutzer_nicht_registriert';
                     }
                     elseif ($objCode->hasBeenPaidOn == '')
                     {
                            if ($objCode->validUntil < time())
                            {
                                   $_SESSION['gewinnspiel_preis_einloesen']['status'] = 'einloesefrist_abgelaufen';
                            }
                            else
                            {
                                   $set = array(
                                          'hasBeenPaidOn' => time(),
                                          'locked' => 1
                                   );
                                   $this->Database->prepare('UPDATE tl_gewinnspiel_codes %s WHERE id =?')->set($set)->executeUncached($objCode->id);
                                   $this->userData['hasBeenPaidOn'] = $set['hasBeenPaidOn'];
                                   $this->userData['locked'] = 1;
                                   $_SESSION['gewinnspiel_preis_einloesen']['status'] = 'kunden_auszahlen';
                            }
                     }
                     else
                     {
                            $_SESSION['gewinnspiel_preis_einloesen']['status'] = 'gutschein_bereits_eingeloest';
                     }
              }
              else
              {
                     $_SESSION['gewinnspiel_preis_einloesen']['status'] = 'ungueltiger_code';
                     $this->userData['code'] = $strCode;
              }
              $_SESSION['gewinnspiel_preis_einloesen']['userData'] = $this->userData;

       }

       /**
        * set the class array $this->userData
        * @param string $strCode
        */
       protected function setArrUserData($strCode)
       {

              $objCode = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE code = ?')->execute($this->cryptCode($strCode));
              if ($objCode->next())
              {
                     // get data from tl_gewinnspiel_codes
                     $this->userData['codeId'] = $objCode->id;
                     $this->userData['prizeGroup'] = $objCode->prizeGroup;
                     $this->userData['memberId'] = $objCode->memberId;
                     $this->userData['code'] = $strCode;
                     $this->userData['locked'] = $objCode->locked;
                     $this->userData['token'] = $objCode->token;
                     $this->userData['enteredCodeOn'] = $objCode->enteredCodeOn;
                     $this->userData['hasBeenPaidOn'] = $objCode->hasBeenPaidOn;
                     $this->userData['validUntil'] = $objCode->validUntil;

                     // get data from tl_gewinnspiel_preise
                     $objPrize = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_preise WHERE id = ?')->execute($objCode->prizeGroup);
                     if ($objPrize->next())
                     {
                            $this->userData['prizeName'] = $objPrize->name;
                            $this->userData['prizeDescription'] = $objPrize->description;
                     }

                     // get data from tl_member
                     $objMember = $this->Database->prepare('SELECT * FROM tl_member WHERE id = ?')->execute($objCode->memberId);
                     if ($objMember->next())
                     {
                            $this->userData['gender'] = $objMember->gender;
                            $this->userData['firstname'] = $objMember->firstname;
                            $this->userData['lastname'] = $objMember->lastname;
                            $this->userData['email'] = $objMember->email;
                     }
              }
       }

}

