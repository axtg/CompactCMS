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
-- Table structure for table `ccms_cfgnews`
--

CREATE TABLE IF NOT EXISTS `ccms_cfgnews` (
  `showMessage` int(5) NOT NULL DEFAULT '3',
  `showAuthor` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `showDate` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `showTeaser` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Configuration variables for modNews';

--
-- Dumping data for table `ccms_cfgnews`
--

INSERT INTO `ccms_cfgnews` (`showMessage`, `showAuthor`, `showDate`, `showTeaser`) VALUES
(3, '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgpermissions`
--

CREATE TABLE IF NOT EXISTS `ccms_cfgpermissions` (
  `manageUsers` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users manage user accounts (add, modify, delete)',
  `manageOwners` int(1) NOT NULL DEFAULT '3' COMMENT 'To allow to appoint certain users to a specific page',
  `managePages` int(1) NOT NULL DEFAULT '1' COMMENT 'From what user level on can users manage pages',
  `manageMenu` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage menu preferences',
  `manageTemplate` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users manage all of the available templates',
  `manageModules` int(1) NOT NULL DEFAULT '5' COMMENT 'From what user level on can users manage modules',
  `manageActivity` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage the activeness of pages',
  `manageVarCoding` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users set the coding variable for pages',
  `manageModBackup` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users manage the back-up module ',
  `manageModNews` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage news items through the news module',
  `manageModLightbox` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage albums throught the lightbox module'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccms_cfgpermissions`
--

INSERT INTO `ccms_cfgpermissions` (`manageUsers`, `manageOwners`, `managePages`, `manageMenu`, `manageTemplate`, `manageModules`, `manageActivity`, `manageVarCoding`, `manageModBackup`, `manageModNews`, `manageModLightbox`) VALUES
(3, 0, 2, 2, 4, 4, 1, 4, 3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ccms_modnews`
--

CREATE TABLE IF NOT EXISTS `ccms_modnews` (
  `newsID` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `userID` int(5) unsigned zerofill NOT NULL,
  `newsTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `newsTeaser` text COLLATE utf8_unicode_ci NOT NULL,
  `newsContent` text COLLATE utf8_unicode_ci NOT NULL,
  `newsModified` datetime NOT NULL,
  `newsPublished` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`newsID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ccms_modnews`
--

INSERT INTO `ccms_modnews` (`newsID`, `userID`, `newsTitle`, `newsTeaser`, `newsContent`, `newsModified`, `newsPublished`) VALUES
(00001, 00001, 'News module live?', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '2010-08-20 16:15:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_modules`
--

CREATE TABLE IF NOT EXISTS `ccms_modules` (
  `modID` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `modName` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `modLocation` text COLLATE utf8_unicode_ci NOT NULL,
  `modVersion` decimal(5,2) NOT NULL,
  `modPermissionName` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `modActive` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`modID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table with the installed modules, their version and activene' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ccms_modules`
--

INSERT INTO `ccms_modules` (`modID`, `modName`, `modLocation`, `modVersion`, `modPermissionName`, `modActive`) VALUES
(00001, 'News', './lib/modules/news/news.Manage.php', 1.00, 'manageModNews', '1'),
(00002, 'Lightbox', './lib/modules/lightbox/lightbox.Manage.php', 1.00, 'manageModLightbox', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_pages`
--

CREATE TABLE IF NOT EXISTS `ccms_pages` (
  `page_id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_ids` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Separated by commas',
  `urlpage` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'editor',
  `toplevel` tinyint(5) DEFAULT NULL,
  `sublevel` tinyint(5) DEFAULT NULL,
  `menu_id` int(5) DEFAULT '1',
  `variant` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ccms',
  `pagetitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `srcfile` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `printable` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `islink` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `iscoding` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `published` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `urlpage` (`urlpage`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='Table with details for included pages' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ccms_pages`
--

INSERT INTO `ccms_pages` (`page_id`, `user_ids`, `urlpage`, `module`, `toplevel`, `sublevel`, `menu_id`, `variant`, `pagetitle`, `subheader`, `description`, `keywords`, `srcfile`, `printable`, `islink`, `iscoding`, `published`) VALUES
(00001, '0', 'home', 'editor', 1, 0, 1, 'ccms', 'Home', 'The CompactCMS demo homepage', 'The CompactCMS demo homepage', 'compactcms, light-weight cms', 'home.php', 'Y', 'Y', 'N', 'Y'),
(00002, '0', 'installation', 'editor', 2, 0, 1, 'html5', 'Installation', 'Get help installing CompactCMS', 'Get help installing CompactCMS', 'compactcms, light-weight cms', 'installation.php', 'Y', 'Y', 'N', 'Y'),
(00003, '0', 'contact', 'editor', 3, 0, 1, 'sweatbee', 'Contact form', 'A basic contact form using Ajax', 'This is an example of a basic contact form based using Ajax', 'compactcms, light-weight cms', 'contact.php', 'Y', 'Y', 'Y', 'Y'),
(00004, '0', 'lightbox', 'lightbox', 4, 0, 1, 'fireworks', 'Lightbox', 'View pictures in the lightbox', 'All pictures regarding the CCMS project in one convenient lightbox', '', 'lightbox.php', 'Y', 'Y', 'N', 'Y'),
(00005, '0', 'news', 'news', 5, 0, 1, 'reckoning', 'Recent news', 'All the latest news on CCMS', 'Read all about the latest changes to the CompactCMS project.', '', 'news.php', 'Y', 'Y', 'N', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_users`
--

CREATE TABLE IF NOT EXISTS `ccms_users` (
  `userID` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userPass` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userFirst` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userLast` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `userEmail` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `userActive` tinyint(1) NOT NULL,
  `userLevel` tinyint(1) NOT NULL,
  `userToken` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userLastlog` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table with users for CompactCMS administration' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ccms_users`
--

INSERT INTO `ccms_users` (`userID`, `userName`, `userPass`, `userFirst`, `userLast`, `userEmail`, `userActive`, `userLevel`, `userToken`, `userLastlog`, `userTimestamp`) VALUES
(00001, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'Xander', 'CCMS', 'xander@compactcms.nl', 1, 4, '5168774687486', '2010-08-20 12:05:04', '2010-08-20 14:05:04'),
(00002, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'John', 'Doe', 'john@compactcms.nl', 1, 1, '5590832043058', '2010-08-19 12:36:26', '2010-08-19 14:36:26');
