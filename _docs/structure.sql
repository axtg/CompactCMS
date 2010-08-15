-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net

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
  `showAuthor` enum('0','1') NOT NULL DEFAULT '1',
  `showDate` enum('0','1') NOT NULL DEFAULT '1',
  `showTeaser` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Configuration variables for modNews';

--
-- Dumping data for table `ccms_cfgnews`
--

INSERT INTO `ccms_cfgnews` (`showMessage`, `showAuthor`, `showDate`, `showTeaser`) VALUES
(3, '1', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgpermissions`
--

CREATE TABLE IF NOT EXISTS `ccms_cfgpermissions` (
  `manageUsers` int(1) NOT NULL COMMENT 'From what user level on can users manage user accounts (add, modify, delete)',
  `managePages` int(1) NOT NULL COMMENT 'From what user level on can users manage pages',
  `manageMenu` int(1) NOT NULL COMMENT 'From what user level on can users manage menu preferences',
  `manageTemplate` int(1) NOT NULL COMMENT 'From what user level on can users manage all of the available templates',
  `manageModules` int(1) NOT NULL COMMENT 'From what user level on can users manage modules',
  `manageActivity` int(1) NOT NULL COMMENT 'From what user level on can users manage the activeness of pages',
  `manageVarCoding` int(1) NOT NULL COMMENT 'From what user level on can users set the coding variable for pages',
  `manageModBackup` int(1) NOT NULL DEFAULT '3' COMMENT 'From what user level on can users manage the back-up module ',
  `manageModNews` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage news items through the news module',
  `manageModLightbox` int(1) NOT NULL DEFAULT '2' COMMENT 'From what user level on can users manage albums throught the lightbox module'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ccms_cfgpermissions`
--

INSERT INTO `ccms_cfgpermissions` (`manageUsers`, `managePages`, `manageMenu`, `manageTemplate`, `manageModules`, `manageActivity`, `manageVarCoding`, `manageModBackup`, `manageModNews`, `manageModLightbox`) VALUES
(3, 2, 2, 5, 5, 1, 5, 3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ccms_modnews`
--

CREATE TABLE IF NOT EXISTS `ccms_modnews` (
  `newsID` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `userID` int(5) unsigned zerofill NOT NULL,
  `newsTitle` varchar(200) NOT NULL,
  `newsIcon` varchar(200) NOT NULL DEFAULT './lib/modules/news/default-news.png',
  `newsTeaser` text NOT NULL,
  `newsContent` text NOT NULL,
  `newsModified` date NOT NULL,
  `newsPublished` enum('0','1') NOT NULL,
  PRIMARY KEY (`newsID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ccms_modnews`
--

INSERT INTO `ccms_modnews` (`newsID`, `userID`, `newsTitle`, `newsIcon`, `newsTeaser`, `newsContent`, `newsModified`, `newsPublished`) VALUES
(00001, 00001, 'News module live', './lib/modules/news/default-news.png', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '2010-08-03', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_pages`
--

CREATE TABLE IF NOT EXISTS `ccms_pages` (
  `page_id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `urlpage` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `module` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT 'editor',
  `toplevel` tinyint(5) DEFAULT NULL,
  `sublevel` tinyint(5) DEFAULT NULL,
  `menu_id` int(5) DEFAULT '1',
  `variant` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT 'ccms',
  `pagetitle` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `subheader` varchar(200) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `description` varchar(250) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `keywords` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `srcfile` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `printable` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'Y',
  `islink` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'Y',
  `iscoding` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `published` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `urlpage` (`urlpage`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Table with details for included pages' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ccms_pages`
--

INSERT INTO `ccms_pages` (`page_id`, `urlpage`, `module`, `toplevel`, `sublevel`, `menu_id`, `variant`, `pagetitle`, `subheader`, `description`, `keywords`, `srcfile`, `printable`, `islink`, `iscoding`, `published`) VALUES
(00001, 'home', 'editor', 1, 0, 1, 'ccms', 'Home', 'The CompactCMS demo homepage', 'The CompactCMS demo homepage', 'compactcms, light-weight cms', 'home.php', 'Y', 'Y', 'N', 'Y'),
(00002, 'installation', 'editor', 2, 0, 1, 'nightlights', 'Installation', 'Get help installing CompactCMS', 'Get help installing CompactCMS', 'compactcms, light-weight cms', 'installation.php', 'Y', 'Y', 'N', 'Y'),
(00003, 'contact', 'editor', 3, 0, 1, 'sweatbee', 'Contact form', 'A basic contact form using Ajax', 'This is an example of a basic contact form based using Ajax', 'compactcms, light-weight cms', 'contact.php', 'Y', 'Y', 'Y', 'Y'),
(00004, 'lightbox', 'lightbox', 4, 0, 1, 'fireworks', 'Lightbox', 'View pictures in the lightbox', 'All pictures regarding the CCMS project in one convenient lightbox', '', 'lightbox.php', 'Y', 'Y', 'N', 'Y'),
(00005, 'news', 'news', 5, 0, 1, 'reckoning', 'Recent news', 'All the latest news on CCMS', 'Read all about the latest changes to the CompactCMS project.', '', 'news.php', 'Y', 'Y', 'N', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `ccms_users`
--

CREATE TABLE IF NOT EXISTS `ccms_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) NOT NULL,
  `userPass` varchar(100) NOT NULL,
  `userFirst` varchar(50) NOT NULL,
  `userLast` varchar(25) NOT NULL,
  `userEmail` varchar(75) NOT NULL,
  `userActive` tinyint(1) NOT NULL,
  `userLevel` tinyint(1) NOT NULL,
  `userToken` varchar(100) NOT NULL,
  `userLastlog` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table with users for CompactCMS administration' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ccms_users`
--

INSERT INTO `ccms_users` (`userID`, `userName`, `userPass`, `userFirst`, `userLast`, `userEmail`, `userActive`, `userLevel`, `userToken`, `userLastlog`, `userTimestamp`) VALUES
(1, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'Xander', 'Groesbeek', 'xander@compactcms.nl', 1, 5, '5168774687486', '2010-08-15 10:48:11', '2010-08-15 12:48:11'),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'John', 'Doe', 'john@compactcms.nl', 1, 1, '5590832043058', '2010-08-15 11:26:13', '2010-08-15 13:31:21');
