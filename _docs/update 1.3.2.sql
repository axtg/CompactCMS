-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure update for table `pages`
--
	
ALTER TABLE `pages` ADD `islink` enum('Y','N') NOT NULL DEFAULT 'Y' AFTER `printable`,ADD `iscoding` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N' AFTER `islink`, ADD `menu_id` int(5) DEFAULT '1' AFTER `sublevel`, ADD `module` varchar(20) NOT NULL DEFAULT 'editor' AFTER `urlpage`;