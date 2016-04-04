-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2016 at 05:19 
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ssp`
--

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `public_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `index` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`user_id`, `name`, `phone`, `public_email`, `gravatar_email`, `gravatar_id`, `location`, `website`, `bio`, `index`, `street`) VALUES
(25, 'Иванов', '4352345235', '', NULL, NULL, 'томск', '', '', '', ''),
(26, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(27, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(28, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `ssp_transaction`
--

CREATE TABLE IF NOT EXISTS `ssp_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sum` int(11) NOT NULL,
  `plan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=128 ;

--
-- Dumping data for table `ssp_transaction`
--

INSERT INTO `ssp_transaction` (`id`, `date_create`, `date_update`, `category_id`, `user_id`, `sum`, `plan`) VALUES
(118, '2016-04-03 17:04:01', '2016-04-03 17:04:01', 13, 26, 2899, 0),
(119, '2016-04-03 17:06:21', '2016-04-03 17:06:21', 13, 26, 122, 0),
(120, '2016-04-03 17:06:32', '2016-04-03 17:06:32', 13, 26, 322, 0),
(121, '2016-04-03 17:24:02', '2016-04-03 17:24:02', 13, 26, 4344, 0),
(122, '2016-04-03 17:24:16', '2016-04-03 17:24:16', 16, 26, 3444, 0),
(123, '2016-04-04 05:50:38', '2016-04-04 05:50:38', 12, 26, 233, 0),
(124, '2016-04-04 05:50:57', '2016-04-04 05:50:57', 12, 26, 433, 0),
(125, '2016-04-04 07:18:07', '2016-04-04 07:18:07', 12, 26, 23, 0),
(126, '2016-04-04 07:18:22', '2016-04-04 07:18:22', 15, 26, 453, 0),
(127, '2016-04-04 07:18:32', '2016-04-04 07:18:32', 17, 26, 2555, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ssp_transaction_category`
--

CREATE TABLE IF NOT EXISTS `ssp_transaction_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `ssp_transaction_category`
--

INSERT INTO `ssp_transaction_category` (`id`, `name`, `type_id`) VALUES
(12, 'Премия', 7),
(13, 'Зарплата', 7),
(14, 'Аренда недвижимости', 7),
(15, 'Проценты по вкладам', 7),
(16, 'Покупка продуктов', 8),
(17, 'Ремонт', 8),
(18, 'Покупка мебели', 8),
(19, 'Покупка книг', 8);

-- --------------------------------------------------------

--
-- Table structure for table `ssp_transaction_type`
--

CREATE TABLE IF NOT EXISTS `ssp_transaction_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ssp_transaction_type`
--

INSERT INTO `ssp_transaction_type` (`id`, `name`, `type`) VALUES
(7, 'Приход', 'plus'),
(8, 'Расход', 'minus');

-- --------------------------------------------------------

--
-- Table structure for table `ssp_user`
--

CREATE TABLE IF NOT EXISTS `ssp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE KEY `token_unique` (`user_id`,`code`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`, `username`) VALUES
(26, 'm@m.ru', '$2y$10$VF3NydP1hdLY681qoWuuvegNrd1WnFHaGNyM9VxdbI81fdI0NGK46', 'p9xK_LQek0BnOmHmp9RyL5pIRiTw60W5', 1459394930, NULL, NULL, '::1', 1459394930, 1459394930, 0, 'Иванов'),
(27, 'test@test1.ru', '$2y$10$b8WkZn/WJv.dJeSwLWUrSOIULQTwNpngnFqFUGpWXSCr2KaF7SlHi', 'fm3vU8K0CzLIU0YkUSZXL0uxEwYi0b-4', 1459689148, NULL, NULL, '::1', 1459689148, 1459689161, 0, 'Петров'),
(28, 'test@test2.ru', '$2y$10$6mvxn35BssBWWIFTzf09len1Mro.bpPVv/65Kzo0CkM6Q7KLvR7/y', 'lNxAc8zQd8jXiyg5_fbFGnDE4G2ItTTI', 1459689491, NULL, NULL, '::1', 1459689491, 1459689491, 0, 'Алексей');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ssp_transaction`
--
ALTER TABLE `ssp_transaction`
  ADD CONSTRAINT `ssp_transaction_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `ssp_transaction_category` (`id`);

--
-- Constraints for table `ssp_transaction_category`
--
ALTER TABLE `ssp_transaction_category`
  ADD CONSTRAINT `ssp_transaction_category_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `ssp_transaction_type` (`id`);
