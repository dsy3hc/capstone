-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 07, 2014 at 03:54 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `slp_jaunt`
--
CREATE DATABASE IF NOT EXISTS `slp_jaunt` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
GRANT ALL ON slp_jaunt.* TO 'jaunt' IDENTIFIED BY '';
GRANT ALL ON slp_jaunt.* TO 'jaunt'@'localhost' IDENTIFIED BY '';
USE `slp_jaunt`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `roles` (`id`, `name`) VALUES (1, 'admin');
INSERT INTO `roles` (`id`, `name`) VALUES (2, 'client');
INSERT INTO `roles` (`id`, `name`) VALUES (3, 'hourly');
INSERT INTO `roles` (`id`, `name`) VALUES (4, 'driver');
INSERT INTO `roles` (`id`, `name`) VALUES (5, 'scheduler');


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(128) NOT NULL,
  `clientID` int(64) DEFAULT NULL,
  `role_id` int(10) unsigned NOT NULL DEFAULT '2',
  `cat_disability_num` int(11) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `email_confirm_key` varchar(32) DEFAULT NULL,
  `email_confirm_date` datetime DEFAULT NULL,
  `email_confirm_ip` varchar(39) DEFAULT NULL,
  `password_reset_key` varchar(32) DEFAULT NULL,
  `language` varchar(256) DEFAULT 'English',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientID` (`clientID`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO users (id, first_name, last_name, email, password, role_id, email_confirm_date, created, modified) VALUES (1, 'Admin', 'Employee', 'admin@ridejaunt.org', '$2y$10$mdVVSySIHN/S4UzVH5yl6uHbUyvQHo9mnxfZi1/7FR5ZgFtGyF0cm', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO users (id, first_name, last_name, email, password, role_id, email_confirm_date, created, modified) VALUES (2, 'Jaunt', 'Client', 'client@ridejaunt.org', '$2y$10$mdVVSySIHN/S4UzVH5yl6uHbUyvQHo9mnxfZi1/7FR5ZgFtGyF0cm', 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO users (id, first_name, last_name, email, password, role_id, email_confirm_date, created, modified) VALUES (3, 'Hourly', 'Employee', 'hourly@ridejaunt.org', '$2y$10$mdVVSySIHN/S4UzVH5yl6uHbUyvQHo9mnxfZi1/7FR5ZgFtGyF0cm', 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO users (id, first_name, last_name, email, password, role_id, email_confirm_date, created, modified) VALUES (4, 'Driver', 'Employee', 'driver@ridejaunt.org', '$2y$10$mdVVSySIHN/S4UzVH5yl6uHbUyvQHo9mnxfZi1/7FR5ZgFtGyF0cm', 4, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO users (id, first_name, last_name, email, password, role_id, email_confirm_date, created, modified) VALUES (5, 'Scheduler', 'Employee', 'scheduler@ridejaunt.org', '$2y$10$mdVVSySIHN/S4UzVH5yl6uHbUyvQHo9mnxfZi1/7FR5ZgFtGyF0cm', 5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE IF NOT EXISTS `reservations` (
  `created_time` datetime NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `clientID` int(11) NOT NULL,
  `disability` varchar(50) NOT NULL,
  `doctors_appointment` tinyint(1) NOT NULL,
  `pick_up_day` date NOT NULL,
  `pick_up_time` time NOT NULL,
  `pick_up_address` text NOT NULL,
  `pick_up_unit` int(11) DEFAULT NULL,
  `pick_up_city` text NOT NULL,
  `pick_up_zip` int(11) NOT NULL,
  `drop_off_address` text NOT NULL,
  `drop_off_unit` int(11) DEFAULT NULL,
  `drop_off_city` text NOT NULL,
  `drop_off_zip` int(11) NOT NULL,
  `return_time` time DEFAULT NULL,
  `bookingID` int(11) NOT NULL,
  `bookingNum` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(2) DEFAULT '0',
  `comments` text,
  `physicians` tinyint(1) NOT NULL,
  `children` tinyint(1) NOT NULL,
  `one_way` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`bookingNum`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `timeoff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `start_date_1` datetime NOT NULL,
  `end_date_1` datetime NOT NULL,
  `start_date_2` datetime DEFAULT NULL,
  `end_date_2` datetime DEFAULT NULL,
  `start_date_3` datetime DEFAULT NULL,
  `end_date_3` datetime DEFAULT NULL,
  `status` tinyint(2) DEFAULT 0,
  `request_type` enum('annual', 'sick', 'bonus') NOT NULL,
  `time_selected` tinyint(2) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `value` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`id`,`name`,`value`) VALUES  (1,'send_email','no');
INSERT INTO `settings` (`id`,`name`,`value`) VALUES  (2,'active_time','2 months');
INSERT INTO `settings` (`id`,`name`,`value`) VALUES  (3,'request_time','1 day');
INSERT INTO `settings` (`id`,`name`,`value`) VALUES  (4,'email_template','default');
INSERT INTO `settings` (`id`,`name`,`value`) VALUES  (5,'time_off_request_notification','yes');
