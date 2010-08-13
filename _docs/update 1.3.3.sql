-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure update for table `pages`
--
ALTER TABLE `pages` ADD `keywords` VARCHAR( 255 ) NOT NULL AFTER `description`;
ALTER TABLE `pages` CHANGE `variant` `variant` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ccms';