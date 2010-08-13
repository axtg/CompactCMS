-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure update for table `pages`
--
	
ALTER TABLE `pages` ADD `toplevel` TINYINT( 5 ) NULL AFTER `urlpage`, ADD `sublevel` TINYINT( 5 ) NULL AFTER `toplevel`, ADD `variant` VARCHAR( 10 ) NOT NULL DEFAULT 'nsx' AFTER `sublevel` ;