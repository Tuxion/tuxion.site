-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 12, 2012 at 09:16 PM
-- Server version: 5.1.48
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sevendays_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `tx__account_user_info`
--

DROP TABLE IF EXISTS `tx__account_user_info`;
CREATE TABLE IF NOT EXISTS `tx__account_user_info` (
  `user_id` int(11) NOT NULL COMMENT 'cms_users::id',
  `avatar_image_id` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `preposition` varchar(255) DEFAULT NULL,
  `family_name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `claim_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tx__account_user_info`
--

INSERT INTO `tx__account_user_info` (`user_id`, `avatar_image_id`, `username`, `name`, `preposition`, `family_name`, `status`, `claim_key`) VALUES
(78, NULL, 'test6', NULL, NULL, NULL, 0, ''),
(77, NULL, 'test5', NULL, NULL, NULL, 0, ''),
(76, NULL, 'test4', NULL, NULL, NULL, 0, ''),
(75, NULL, 'test3', NULL, NULL, NULL, 0, ''),
(74, NULL, 'test2', NULL, NULL, NULL, 0, ''),
(73, NULL, 'test', NULL, NULL, NULL, 0, ''),
(72, NULL, 'test', NULL, NULL, NULL, 0, ''),
(71, NULL, 'test', NULL, NULL, NULL, 0, ''),
(70, 215, 'BWR', 'Bart', '', 'Roorda', 1, ''),
(79, NULL, '', NULL, NULL, NULL, 1, ''),
(80, NULL, '', NULL, NULL, NULL, 0, ''),
(81, NULL, '', NULL, NULL, NULL, 1, ''),
(82, NULL, '', NULL, NULL, NULL, 1, ''),
(84, NULL, NULL, NULL, NULL, NULL, 4, '0bd428c0ff'),
(85, NULL, NULL, NULL, NULL, NULL, 1, 'f103816afe'),
(86, NULL, 'test', NULL, NULL, NULL, 0, NULL),
(87, NULL, 'Test', NULL, NULL, NULL, 0, NULL),
(88, NULL, NULL, NULL, NULL, NULL, 4, '03cccc42ba');
