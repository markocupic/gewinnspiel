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
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['gewinnspiel_teilnehmer'] = '{title_legend},name,headline,type;{form_layout},tableless;{config_legend},addUserToAvisotaRecipientList,senderEmail,adminNotificationEmail,prizeImagesFolder,validUntil;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['gewinnspiel_verkaufspersonal'] = '{title_legend},name,headline,type;{form_layout},tableless;{config_legend},prizeImagesFolder;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */



$GLOBALS['TL_DCA']['tl_module']['fields']['addUserToAvisotaRecipientList'] = array(
       'label' => &$GLOBALS['TL_LANG']['tl_module']['addUserToAvisotaRecipientList'],
       'inputType' => 'select',
       'legend' => 'tl_avisota_recipient_list.title',
       'foreignKey' => 'tl_avisota_recipient_list.id',
       'options_callback' => array('mod_gewinnspiel', 'getAvisotaVerteiler'),
       'eval' => array('includeBlankOption' => true, 'mandatory' => false, 'class' => 'long clr')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['senderEmail'] = array(
       'label' => &$GLOBALS['TL_LANG']['tl_module']['senderEmail'],
       'inputType' => 'text',
       'eval' => array('mandatory' => true, 'rgxp' => 'email', 'class' => 'long clr')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['adminNotificationEmail'] = array(
       'label' => &$GLOBALS['TL_LANG']['tl_module']['adminNotificationEmail'],
       'inputType' => 'text',
       'eval' => array('mandatory' => false, 'class' => 'alpha')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['prizeImagesFolder'] = array(
       'label' => &$GLOBALS['TL_LANG']['tl_module']['prizeImagesFolder'],
       'inputType' => 'fileTree',
       'eval' => array('fieldType' => 'radio', 'mandatory' => true, 'files' => false, 'filesOnly' => false, 'class' => 'long clr')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['validUntil'] = array(
       'label' => &$GLOBALS['TL_LANG']['tl_module']['validUntil'],
       'inputType' => 'text',
       'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'class' => 'long clr')
);

/**
 * Class mod_gallery_creator
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 */
class mod_gewinnspiel extends Backend
{
       /**
        * Return all gallery_creator frontent-templates as array
        * @param object
        * @return array
        */
       public function getTemplates(DataContainer $dc)
       {
              $intPid = $dc->activeRecord->pid;
              return $this->getTemplateGroup('ce_gc_', $intPid);
       }

       /**
        * @return array
        */
       public function getAvisotaVerteiler()
       {
              $options = array();
              if (in_array('tl_avisota_recipient_list', $this->Database->listTables()))
              {
                     $objDb = $this->Database->execute('SELECT * FROM tl_avisota_recipient_list ORDER BY id');
                     while ($objDb->next())
                     {
                            $options[$objDb->id] = $objDb->title;
                     }
              }
              return $options;
       }

}

?>