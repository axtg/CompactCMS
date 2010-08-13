-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Rename table `pages` to `ccms_pages`
--

RENAME TABLE `pages`  TO `ccms_pages`;

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
  `userTimestamp` datetime NOT NULL,
  `userLastlog` datetime NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table with users for CompactCMS administration' AUTO_INCREMENT=3;

--
-- Dumping data for table `ccms_users`
--

INSERT INTO `ccms_users` (`userID`, `userName`, `userPass`, `userFirst`, `userLast`, `userEmail`, `userActive`, `userLevel`, `userToken`, `userLastlog`, `userTimestamp`) VALUES
(1, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'Xander', 'Groesbeek', 'xander@compactcms.nl', 1, 5, '5168774687486', '2010-12-31 00:00:00', '2010-12-31 00:00:00'),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'John', 'Doe', 'john@compactcms.nl', 1, 1, '5590832043058', '2010-12-31 00:00:00', '2010-12-31 00:00:00');
