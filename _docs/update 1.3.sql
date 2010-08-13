-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure update for table `pages`
--
	
ALTER TABLE `pages` ADD `variant` VARCHAR( 100 ) NOT NULL DEFAULT 'ccms' AFTER `sublevel` ;