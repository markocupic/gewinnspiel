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
class ModuleGewinnspielTeilnehmer extends ModuleGewinnspiel
{
       /**
        * default template
        * @var string
        */
       protected $strTemplate = 'mod_gewinnspiel_teilnehmer';

       /**
        * Parse the template
        * eval SESSION and url params then route
        * @return string
        */
       public function generate()
       {
              // crypt the codes in tl_gewinnspiel_codes whre it isn't already done
              if ($this->crypt_code === true) {
                     $objDb = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE crypt = ?')->execute('');
                     while ($objDb->next()) {
                            $set = array(
                                   'code' => $this->cryptCode($objDb->code),
                                   'crypt' => '1'
                            );
                            $this->Database->prepare('UPDATE  tl_gewinnspiel_codes %s WHERE id = ?')->set($set)->executeUncached($objDb->id);
                     }
              }
              if (TL_MODE == 'BE') {
                     $objTemplate = new BackendTemplate('be_wildcard');
                     $objTemplate->wildcard = '### GEWINNSPIEL TEILNEHMER MODUL ###';
                     $objTemplate->title = $this->headline;
                     $objTemplate->id = $this->id;
                     $objTemplate->link = $this->name;
                     $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
                     return $objTemplate->parse();
              }
              if (strlen($this->Input->get('token'))) {
                     $objCode = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE token = ?')->execute($this->Input->get('token'));
                     if (!$objCode->next()) {
                            // abort script if token doesn't exist
                            die(utf8_decode("Abbruch durch ungültiges oder veraltetes Token!!!"));
                     }
                     if (strlen($this->Input->post('FORM_SUBMIT'))) {
                            $objCode = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE token = ? AND code = ?')->execute($this->Input->get('token'), $this->cryptCode($this->Input->post('code')));
                            if ($objCode->next()) {
                                   if ($objCode->hasBeenPaidOn != "") {
                                          $_SESSION['gewinnspiel_get_certificate']['error'] = 'token_gewinn_bereits_ausbezahlt';
                                          $this->reload();
                                   }
                                   // send the pdf certificate to browser
                                   sleep(2);
                                   $this->certificateDownload();
                                   exit();
                            } else {
                                   // reload page and display the form again if the code isn't valid
                                   $_SESSION['gewinnspiel_get_certificate']['error'] = 'token_invalid_code';
                                   $this->reload();
                            }
                     } else {
                            $this->status = 'show_certificate_form';
                            $this->strTemplate = 'mod_gewinnspiel_teilnehmer';
                     }
              }
              // store the Session in the class var status and then destroy it
              if (isset($_SESSION['gewinnspiel'])) {
                     $this->status = $_SESSION['gewinnspiel']['status'];
                     $this->userData = $_SESSION['gewinnspiel']['userData'];
                     unset($_SESSION['gewinnspiel']);
              }
              return parent::generate();
       }

       /**
        * Generate module
        */
       protected function compile()
       {
              $this->loadLanguageFile('gewinnspiel');
              $this->Template->status = $this->status;
              $this->Template->userData = $this->userData ? $this->userData : array();
              switch ($this->status) {
                     case 'gewonnen':
                            $this->registerUser();
                            $this->lockCode();
                            $this->sendConfirmationEmail();
                            $this->sendAdminEmailNotification();
                            $prizeSrc = $this->prizeImagesFolder . '/preis_' . $this->userData['prizeGroup'] . '.jpg';
                            $this->Template->srcPrizeImage = file_exists(TL_ROOT . '/' . $prizeSrc) ? $prizeSrc : '';
                            break;
                     case 'show_certificate_form':
                            $this->Template->formId = 'gewinnspiel_get_certificate';

                            // display error messages
                            if ($_SESSION['gewinnspiel_get_certificate']['error']) {
                                   $errorType = $_SESSION['gewinnspiel_get_certificate']['error'];
                                   $this->Template->errorMessage = $GLOBALS['TL_LANG']['gewinnspiel'][$errorType];
                                   unset($_SESSION['gewinnspiel_get_certificate']);
                            }

                            $userData = array();

                            // tl_gewinnspiel_codes
                            $objCode = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE token = ?')->execute($this->Input->get('token'));
                            while ($objCode->next()) {
                                   $userData['codeId'] = $objCode->prizeGroup;
                                   $userData['prizeId'] = $objCode->prizeGroup;
                                   $userData['memberId'] = $objCode->memberId;
                                   $userData['prizePhoto'] = $this->prizeImagesFolder . '/' . sprintf('preis_%s.jpg', $objCode->prizeGroup);
                                   $userData['validUntil'] = $objCode->validUntil;
                            }

                            // tl_gewinnspiel_preise
                            $objPrize = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_preise WHERE id = ?')->execute($objCode->prizeGroup);
                            while ($objPrize->next()) {
                                   $userData['prizeName'] = $objPrize->name;
                                   $userData['prizeDescription'] = $objPrize->description;
                            }

                            //tl_member
                            $objMember = $this->Database->prepare('SELECT * FROM tl_member WHERE id = ?')->execute($objCode->memberId);
                            while ($objMember->next()) {
                                   $userData['firstname'] = $objMember->firstname;
                                   $userData['lastname'] = $objMember->lastname;
                                   $userData['email'] = $objMember->email;
                            }

                            // assign information to template var
                            $this->Template->userData = $userData;

                            $this->Template->action = $this->Environment->base . $this->Environment->request;
                            $this->loadDataContainer('gewinnspiel');
                            $dca = $GLOBALS['TL_DCA']['gewinnspiel'];
                            $arrFieldsFromDca = array('code');
                            $this->Template->tableless = $this->tableless;
                            $this->Template->arrFields = $this->generateFields($dca, $arrFieldsFromDca, 'gewinnspiel', '');
                            break;
                     // show form
                     case 'code_bereits_eingeloest':
                     case 'kein_gewinn':
                     default:
                            // if the key isn't valid
                            if ($this->status == 'kein_gewinn') {
                                   $this->registerUser();
                                   $this->sendAdminEmailNotification();
                                   $this->Template->errorMessage = $GLOBALS['TL_LANG']['gewinnspiel']['kein_gewinn'];
                            }
                            if ($this->status == 'code_bereits_eingeloest') {
                                   $this->Template->errorMessage = $GLOBALS['TL_LANG']['gewinnspiel']['code_bereits_eingeloest'];
                            }
                            // generate form
                            $this->Template->status = 'show_form';
                            $this->Template->formId = 'gewinnspiel';
                            $this->Template->action = $this->Environment->request;
                            $this->loadDataContainer('gewinnspiel');
                            $dca = $GLOBALS['TL_DCA']['gewinnspiel'];
                            $arrFieldsFromDca = array('gender', 'firstname', 'lastname', 'email', 'code', 'captcha', 'agb');
                            $this->Template->tableless = $this->tableless;
                            $this->Template->arrFields = $this->generateFields($dca, $arrFieldsFromDca, 'gewinnspiel', '');
              }
       }

       /**
        * Call the FPDF Class
        */
       protected function certificateDownload()
       {
              $objCode = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE token = ?')->execute(trim($this->Input->get('token')));
              if ($objCode->next()) {
                     $objMember = $this->Database->prepare('SELECT * FROM tl_member WHERE id = ?')->execute($objCode->memberId);
                     if ($objMember->next()) {
                            $objPrize = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_preise WHERE id = ?')->execute($objCode->prizeGroup);
                            if ($objPrize->next()) {
                                   $objFpdf = new GenerateCertificate();
                                   $objFpdf->generateCertificate($this->Input->post('code'), $objCode, $objPrize, $objMember, $this);
                                   exit();
                            }
                     }
              }
       }

       /**
        * Send admin Email Notification
        */
       protected function sendAdminEmailNotification()
       {
              if ($this->adminNotificationEmail == '') return;
              if (null !== is_array(explode(',', $this->adminNotificationEmail))) {
                     $arrTo = explode(',', $this->adminNotificationEmail);
                     $email = new Email;
                     $email->priority = 'high';
                     $email->charset = 'UTF-8';
                     $email->from = $this->senderEmail;
                     $email->subject = $GLOBALS['TL_LANG']['gewinnspiel']['email_subject_admin'];
                     $objTemplate = new FrontendTemplate('mod_gewinnspiel_admin_email_notification');
                     $objTemplate->userData = $this->userData;
                     $email->html = $objTemplate->parse();
                     $email->sendTo($arrTo);
              }
       }

       /**
        * Send the confirmation Email
        */
       protected function sendConfirmationEmail()
       {
              $email = new Email;
              $email->priority = 'high';
              $email->charset = 'UTF-8';
              $email->from = $this->senderEmail;
              $email->subject = $GLOBALS['TL_LANG']['gewinnspiel']['email_subject_user'];
              $objTemplate = new FrontendTemplate('mod_gewinnspiel_confirmation_email');
              $objTemplate->href = $this->Environment->base . $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&' : '?') . 'token=' . $this->userData['token'];
              $objTemplate->userData = $this->userData;
              $email->html = $objTemplate->parse();
              $email->sendTo(trim($this->userData['email']));
       }

       /**
        * register User in tl_member, tl_avisota_recipient
        */
       protected function registerUser()
       {
              // add member to tl_member
              $set = array(
                     'tstamp' => time(),
                     'firstname' => $this->userData['firstname'],
                     'lastname' => $this->userData['lastname'],
                     'gender' => $this->userData['gender'],
                     'email' => $this->userData['email'],
                     'participantGewinnspiel' => serialize(array(date('Y'))),
              );
              // mehrmaliges Teilnehmen mit identischer mail-Adresse ermöglichen
              $objMember = $this->Database->prepare('SELECT * FROM tl_member WHERE email LIKE ?')->execute($this->userData['email']);
              if (!$objMember->next()) {
                     $set['dateAdded'] = time();
                     $objInsert = $this->Database->prepare('INSERT INTO tl_member %s')->set($set)->execute();
                     $this->userData['memberId'] = $objInsert->insertId;
                     $this->log('Ein neues Mitglied (ID ' . $objInsert->insertId . ') hat sich auf der Webseite registriert.', 'ModuleGewinnspielTeilnehmer registerUser()', TL_ACCESS);
              } else {
                     $this->Database->prepare('UPDATE tl_member %s WHERE id =  ?')->set($set)->execute($objMember->id);
                     $this->userData['memberId'] = $objMember->id;
                     $this->log('Ein Mitglied (ID ' . $objMember->id . ') hat seine Kontoangaben geändert.', 'ModuleGewinnspielTeilnehmer registerUser()', TL_ACCESS);
              }
              // add member to tl_avisota_recipient, but only if avisota is installed
              if (in_array('tl_avisota_recipient_list', $this->Database->listTables()) && $this->addUserToAvisotaRecipientList) {
                     $objDb = $this->Database->prepare('SELECT * FROM tl_avisota_recipient_list WHERE id = ?')->execute($this->addUserToAvisotaRecipientList);
                     if ($objDb->first()) {
                            $set = array(
                                   'pid' => $this->addUserToAvisotaRecipientList,
                                   'tstamp' => time(),
                                   'salutation' => $this->userData['gender'] == 'male' ? 'Sehr geehrter Herr {fullname}' : 'Sehr geehrte Frau {fullname}',
                                   'firstname' => $this->userData['firstname'],
                                   'lastname' => $this->userData['lastname'],
                                   'confirmed' => '1',
                                   'gender' => $this->userData['gender'],
                                   'email' => $this->userData['email'],
                                   'addedNotice' => 'Online Gewinnspiel ' . date('Y')
                            );
                            //Vorhandene Abonnenten im selben Verteiler werden überschrieben
                            $objDb = $this->Database->prepare('SELECT * FROM tl_avisota_recipient WHERE pid = ? AND email LIKE ?')->execute($this->addUserToAvisotaRecipientList, $this->userData['email']);
                            if (!$objDb->next()) {
                                   $set['addedOn'] = time();
                                   $objInsert = $this->Database->prepare('INSERT INTO tl_avisota_recipient %s')->set($set)->execute();
                                   $this->userData['avisotaRecipientId'] = $objInsert->insertId;
                            } else {
                                   $this->Database->prepare('UPDATE tl_avisota_recipient %s WHERE id = ?')->set($set)->execute($objDb->id);
                                   $this->userData['avisotaRecipientListId'] = $objDb->id;
                            }
                            $this->userData['avisotaRecipientListId'] = $this->addUserToAvisotaRecipientList;
                     }
              }
       }

       /**
        * Lock the code, update tl_gewinnspiel_codes
        */
       protected function lockCode()
       {
              // lock row in tl_gewinnspiel_codes and insert a security token for certificate download
              $set = array(
                     'token' => sha1(md5(microtime()) . md5($this->userData['email'])) . md5(sha1(microtime() . 'fsdfsdfs?#@dsdf872423*')),
                     'locked' => 1,
                     'validUntil' => time() + $this->validUntil * 24 * 60 * 60,
                     'memberId' => $this->userData['memberId'],
                     'enteredCodeOn' => time()
              );
              $this->Database->prepare('UPDATE tl_gewinnspiel_codes %s WHERE code = ?')->set($set)->execute($this->cryptCode($this->userData['code']));
              $this->userData['token'] = $set['token'];
              $this->userData['avisotaRecipientListId'] = $this->add_user_to_avisota_recipient_list;
              // store the prize Group in $this->userData
              $objCode = $this->Database->prepare('SELECT * FROM  tl_gewinnspiel_codes WHERE code = ?')->execute($this->cryptCode($this->userData['code']));
              $this->userData['codeId'] = $objCode->id;
              $this->userData['prizeGroup'] = $objCode->prizeGroup;
              // store the prize Property in $this->userData
              $objPrize = $this->Database->prepare('SELECT * FROM  tl_gewinnspiel_preise WHERE id = ?')->execute($objCode->prizeGroup);
              $this->userData['prizeId'] = $objPrize->id;
              $this->userData['prizeName'] = $objPrize->name;
              $this->userData['prizeDescription'] = $objPrize->description;
       }

       /**
        * validate code
        * save the status in the session
        * save input values in the session
        */
       protected function validateCode()
       {
              $objCode = $this->Database->prepare('SELECT * FROM tl_gewinnspiel_codes WHERE code = ?')->execute($this->cryptCode($this->Input->post('code')));
              if ($objCode->next()) {
                     if ($objCode->locked) {
                            $_SESSION['gewinnspiel']['status'] = 'code_bereits_eingeloest';
                     } else {
                            $_SESSION['gewinnspiel']['status'] = 'gewonnen';
                     }
              } else {
                     $_SESSION['gewinnspiel']['status'] = 'kein_gewinn';
              }
              // store the inputs in the session too
              $_SESSION['gewinnspiel']['userData'] = $_POST;
              $_SESSION['gewinnspiel']['userData']['email'] = strtolower($_SESSION['gewinnspiel']['userData']['email']);
       }
}

