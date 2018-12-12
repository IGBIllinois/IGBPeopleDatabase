CREATE TABLE `address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `address1` varchar(50) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` varchar(5) DEFAULT NULL,
  `forward` tinyint(1) DEFAULT '0',
  `country` varchar(50) DEFAULT 'United States',
  `address_lastUpdateUser` varchar(20) DEFAULT NULL,
  `address_lastUpdateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`address_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `department` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_code` varchar(4) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `key_info` (
  `keyinfo_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `key_id` varchar(10) DEFAULT NULL,
  `date_issued` date DEFAULT NULL,
  `date_returned` date DEFAULT NULL,
  `return_condition` tinytext,
  `paid` tinyint(1) DEFAULT '0',
  `payment_returned` tinyint(1) DEFAULT '0',
  `key_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`keyinfo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `key_list` (
  `key_id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(20) DEFAULT NULL,
  `key_room` varchar(10) DEFAULT NULL,
  `key_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `permissions` (
  `user_id` int(11) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `permissions_lastUpdateUser` varchar(20) DEFAULT NULL,
  `permissions_lastUpdateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `phone` (
  `phone_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `igb` varchar(14) DEFAULT NULL,
  `dept` varchar(14) DEFAULT NULL,
  `cell` varchar(14) DEFAULT NULL,
  `fax` varchar(14) DEFAULT NULL,
  `other` varchar(14) DEFAULT NULL,
  `phone_lastUpdateUser` varchar(20) DEFAULT NULL,
  `phone_lastUpdateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`phone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `themes` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `short_name` varchar(8) DEFAULT NULL,
  `leader_id` int(11) DEFAULT NULL,
  `theme_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `type_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1

CREATE TABLE `user_theme` (
  `user_theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`user_theme_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `netid` varchar(8) DEFAULT NULL,
  `uin` varchar(9) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `default_address` varchar(4) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `key_deposit` tinyint(1) DEFAULT '0',
  `prox_card` tinyint(1) DEFAULT '0',
  `safety_training` tinyint(1) DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `user_time_created` timestamp NULL DEFAULT NULL,
  `user_enabled` tinyint(1) DEFAULT '1',
  `admin` tinyint(1) DEFAULT '0',
  `superadmin` tinyint(1) DEFAULT '0',
  `reason_leaving` tinytext,
  `end_date` date DEFAULT NULL,
  `expected_grad` varchar(15) DEFAULT NULL,
  `image_location` char(50) DEFAULT NULL,
  `users_lastUpdateUser` varchar(20) DEFAULT NULL,
  `users_lastUpdateTime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1




