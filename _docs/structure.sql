-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `compactcms`
--

-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgcomment`
--

DROP TABLE IF EXISTS `ccms_cfgcomment`;
CREATE TABLE IF NOT EXISTS `ccms_cfgcomment` (
  `cfgID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `pageID` varchar(100) CHARACTER SET latin1 NOT NULL,
  `showLocale` varchar(5) CHARACTER SET latin1 NOT NULL DEFAULT 'eng',
  `showMessage` int(5) NOT NULL,
  PRIMARY KEY (`cfgID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ccms_cfgcomment`
--


-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgnews`
--

DROP TABLE IF EXISTS `ccms_cfgnews`;
CREATE TABLE IF NOT EXISTS `ccms_cfgnews` (
  `cfgID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `pageID` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `showLocale` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'eng',
  `showMessage` int(5) NOT NULL DEFAULT '3',
  `showAuthor` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `showDate` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `showTeaser` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`cfgID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Configuration variables for modNews' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ccms_cfgnews`
--


-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgpermissions`
--

DROP TABLE IF EXISTS `ccms_cfgpermissions`;
CREATE TABLE IF NOT EXISTS `ccms_cfgpermissions` (
  `manageUsers` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users manage user accounts (add, modify, delete)',
  `manageOwners` int(1) NOT NULL DEFAULT '3' COMMENT 'To allow to appoint certain users to a specific page',
  `managePages` int(1) NOT NULL DEFAULT '1' COMMENT 'From what user level on can users manage pages (add, delete)',
  `manageMenu` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage menu preferences',
  `manageTemplate` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users manage all of the available templates',
  `manageModules` int(1) NOT NULL DEFAULT '5' COMMENT 'From what user level on can users manage modules',
  `manageActivity` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage the activeness of pages',
  `manageVarCoding` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users set whether a page contains coding (wysiwyg vs code editor)',
  `manageModBackup` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users delete current back-up files',
  `manageModNews` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage news items through the news module (add, modify, delete)',
  `manageModLightbox` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage albums throught the lightbox module (add, modify, delete)',
  `manageModComment` int(1) NOT NULL DEFAULT '2' COMMENT 'The level of a user that is allowed to manage comments'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccms_cfgpermissions`
--

INSERT INTO `ccms_cfgpermissions` (`manageUsers`, `manageOwners`, `managePages`, `manageMenu`, `manageTemplate`, `manageModules`, `manageActivity`, `manageVarCoding`, `manageModBackup`, `manageModNews`, `manageModLightbox`, `manageModComment`) VALUES
(3, 0, 2, 2, 4, 4, 2, 4, 3, 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ccms_modcomment`
--

DROP TABLE IF EXISTS `ccms_modcomment`;
CREATE TABLE IF NOT EXISTS `ccms_modcomment` (
  `commentID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `pageID` varchar(100) NOT NULL,
  `commentName` varchar(100) NOT NULL,
  `commentEmail` varchar(100) NOT NULL,
  `commentUrl` varchar(100) DEFAULT NULL,
  `commentContent` text NOT NULL,
  `commentRate` enum('1','2','3','4','5') NOT NULL,
  `commentTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentHost` varchar(20) NOT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Table containing comment posts for CompactCMS guestbook mo' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ccms_modcomment`
--


-- --------------------------------------------------------

--
-- Table structure for table `ccms_modnews`
--

DROP TABLE IF EXISTS `ccms_modnews`;
CREATE TABLE IF NOT EXISTS `ccms_modnews` (
  `newsID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(5) unsigned NOT NULL,
  `pageID` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `newsTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `newsTeaser` text COLLATE utf8_unicode_ci NOT NULL,
  `newsContent` text COLLATE utf8_unicode_ci NOT NULL,
  `newsModified` datetime NOT NULL,
  `newsPublished` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`newsID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ccms_modnews`
--


-- --------------------------------------------------------

--
-- Table structure for table `ccms_modules`
--

DROP TABLE IF EXISTS `ccms_modules`;
CREATE TABLE IF NOT EXISTS `ccms_modules` (
  `modID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `modName` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'File name',
  `modTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Friendly name',
  `modLocation` text COLLATE utf8_unicode_ci NOT NULL,
  `modVersion` decimal(5,2) NOT NULL,
  `modPermissionName` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `modActive` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`modID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table with the installed modules, their version and activene' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ccms_modules`
--

INSERT INTO `ccms_modules` (`modID`, `modName`, `modTitle`, `modLocation`, `modVersion`, `modPermissionName`, `modActive`) VALUES
(00001, 'News', 'News', './lib/modules/news/news.Manage.php', 1.00, 'manageModNews', '1'),
(00002, 'Lightbox', 'Lightbox', './lib/modules/lightbox/lightbox.Manage.php', 1.00, 'manageModLightbox', '1'),
(00003, 'Comment', 'Comments', './lib/modules/comment/comment.Manage.php', 1.10, 'manageModComment', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_pages`
--

DROP TABLE IF EXISTS `ccms_pages`;
CREATE TABLE IF NOT EXISTS `ccms_pages` (
  `page_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_ids` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Separated by ||',
  `urlpage` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The in-site part of the URL, without the .html at the end',
  `module` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'editor',
  `toplevel` smallint(5) DEFAULT NULL,
  `sublevel` smallint(5) DEFAULT NULL,
  `menu_id` smallint(5) DEFAULT '1' COMMENT 'The menu this will appear in; one of define(MENU_TARGET_COUNT)',
  `variant` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ccms' COMMENT 'The template ID which will be used in conjuction with this page when rendering',
  `pagetitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description showing as tooltip in page OR as direct link to other place when starting with FQDN/URL',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `srcfile` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `printable` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `islink` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y' COMMENT 'Y when the item should show up in the menu',
  `iscoding` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y when the WYSIWYG HTML editor should not be used, e.g. when page contains PHP code',
  `published` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y' COMMENT 'N will not show the page to visitors and give them a 403 page instead',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `urlpage` (`urlpage`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='Table with details for included pages' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ccms_pages`
--

INSERT INTO `ccms_pages` (`page_id`, `user_ids`, `urlpage`, `module`, `toplevel`, `sublevel`, `menu_id`, `variant`, `pagetitle`, `subheader`, `description`, `keywords`, `srcfile`, `printable`, `islink`, `iscoding`, `published`) VALUES
(00001, '0', 'home', 'editor', 1, 0, 1, 'ccms', 'Home', 'The CompactCMS demo homepage', 'The CompactCMS demo homepage', 'compactcms, light-weight cms', 'home.php', 'Y', 'Y', 'N', 'Y'),
(00002, '0', 'contact', 'editor', 2, 0, 1, 'sweatbee', 'Contact form', 'A basic contact form using Ajax', 'This is an example of a basic contact form based using Ajax', 'compactcms, light-weight cms', 'contact.php', 'Y', 'Y', 'Y', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_users`
--

DROP TABLE IF EXISTS `ccms_users`;
CREATE TABLE IF NOT EXISTS `ccms_users` (
  `userID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userPass` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userFirst` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userLast` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `userEmail` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `userActive` smallint(1) NOT NULL,
  `userLevel` smallint(1) NOT NULL,
  `userToken` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userLastlog` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table with users for CompactCMS administration' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ccms_users`
--

INSERT INTO `ccms_users` (`userID`, `userName`, `userPass`, `userFirst`, `userLast`, `userEmail`, `userActive`, `userLevel`, `userToken`, `userLastlog`, `userTimestamp`) VALUES
(00001, 'admin', '52dcb810931e20f7aa2f49b3510d3805', 'Xander', 'G.', 'xander@compactcms.nl', 1, 4, '5168774687486', '2010-08-30 06:44:57', '2010-08-30 08:44:57');
