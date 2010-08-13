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

INSERT INTO `ccms_modnews` (`newsID`, `userID`, `newsTitle`, `newsIcon`, `newsTeaser`, `newsContent`, `newsCreated`, `newsModified`, `newsPublished`) VALUES
(00001, 00001, 'News module live', './lib/modules/news/default-news.png', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2010-12-31 23:00:00', '1');
	
--
-- Modify `ccms_cfgPermissions`
--	
ALTER TABLE `ccms_cfgpermissions` ADD `manageModNews` INT( 1 ) NOT NULL DEFAULT '2' AFTER `manageVarCoding` 
