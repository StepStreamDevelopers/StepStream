-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Host: mysql.stephealth.us
-- Generation Time: Feb 17, 2013 at 08:54 AM
-- Server version: 5.1.39
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stephealth1`
--

-- --------------------------------------------------------

--
-- Table structure for table `game_token`
--

CREATE TABLE IF NOT EXISTS `game_token` (
  `profile_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game_token`
--

INSERT INTO `game_token` (`profile_id`, `date`, `token`) VALUES
(1, '2013-02-15 16:31:59', 100),
(2, '2013-02-15 16:32:32', 100);
