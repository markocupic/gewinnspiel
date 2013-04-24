-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

--
-- Table `tl_gewinnspiel_codes`
--
CREATE TABLE `tl_gewinnspiel_codes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` varchar(10) NOT NULL default '',
  `code` varchar(128) NOT NULL default '',
  `crypt` char(1) NOT NULL default '',
  `token` varchar(128) NOT NULL default '',
  `prizeGroup` int(10) unsigned NOT NULL default '0',
  `locked` char(1) NOT NULL default '',
  `memberId` int(10) unsigned NOT NULL default '0',
  `enteredCodeOn` varchar(10) NOT NULL default '',
  `validUntil` varchar(10) NOT NULL default '',
  `hasBeenPaid` char(1) NOT NULL default '',
  `hasBeenPaidOn` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `prizeGroup` (`prizeGroup`),
  KEY `memberId` (`memberId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Table `tl_gewinnspiel_preise`
--
CREATE TABLE `tl_gewinnspiel_preise` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` text NULL,
  `description` text NULL,
  `sorting` int(10) NOT NULL default '0',
  `tstamp` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 
-- Table `tl_module`
-- 
CREATE TABLE `tl_module` (
  `addUserToAvisotaRecipientList` varchar(128) NOT NULL default '',
  `senderEmail` text NULL,
  `prizeImagesFolder` varchar(256) NOT NULL default '',
  `validUntil` int(10) NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_member`
-- 
CREATE TABLE `tl_member` (
  `participantGewinnspiel` varchar(512) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;








