/*
MySQL Data Transfer
Source Host: 10.6.10.135
Source Database: picweb
Target Host: 10.6.10.135
Target Database: picweb
Date: 1/8/2010 3:37:02 PM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for chat
-- ----------------------------
DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `picid` int(255) DEFAULT NULL,
  `uid` int(255) DEFAULT NULL,
  `date` date NOT NULL,
  `comment` varchar(255) NOT NULL,
  `leftBy` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_leftby_user_uid` (`leftBy`),
  KEY `fk_picid_image_id` (`picid`),
  KEY `fk_uid_user_uid` (`uid`),
  CONSTRAINT `fk_leftby_user_uid` FOREIGN KEY (`leftBy`) REFERENCES `user` (`uid`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_picid_image_id` FOREIGN KEY (`picid`) REFERENCES `image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_uid_user_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for image
-- ----------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  `albumid` int(255) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(255) NOT NULL,
  `image` longblob NOT NULL,
  `thumb` longblob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_uid` (`uid`),
  KEY `fk_albumid_useralbum` (`albumid`),
  CONSTRAINT `fk_albumid_useralbum` FOREIGN KEY (`albumid`) REFERENCES `user_album` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `user` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `page` varchar(255) NOT NULL,
  `browser` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  `story` varchar(255) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_uid_to_user` (`uid`),
  CONSTRAINT `FK_uid_to_user` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `join_date` date NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user_album
-- ----------------------------
DROP TABLE IF EXISTS `user_album`;
CREATE TABLE `user_album` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `public` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_image_uid` (`uid`),
  CONSTRAINT `fk_image_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for whos_online
-- ----------------------------
DROP TABLE IF EXISTS `whos_online`;
CREATE TABLE `whos_online` (
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Procedure structure for clearLog
-- ----------------------------
DROP PROCEDURE IF EXISTS `clearLog`;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `clearLog`()
BEGIN
     	DROP TABLE IF EXISTS picweb.log;
	CREATE TABLE picweb.log (
		id BIGINT NOT NULL auto_increment,
		PRIMARY KEY (id),
		date date NOT NULL,
		user varchar(255) NOT NULL,
		ip varchar(255) NOT NULL,
		host varchar(255) NOT NULL,
		page varchar(255) NOT NULL,
		browser varchar(255) NOT NULL
	);
	ALTER TABLE picweb.log ENGINE = InnoDB;
END;;
DELIMITER ;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `user` VALUES ('0', 'system', 'System', 'System', '6c370670912a6a5ba12788f1ae8df266', '2009-07-21', 'user', 'root@dev-unixtrack.menards.net');
INSERT INTO `user` VALUES ('2', 'alandsve', 'Andrew', 'Landsverk', '09c48aa66f8a287b2a4dae622307529f', '2009-07-21', 'admin', 'alandsve@menards.net');
INSERT INTO `user` VALUES ('3', 'test', 'Test', 'Tester', '098f6bcd4621d373cade4e832627b4f6', '2010-01-08', 'user', 'test@test.com');
