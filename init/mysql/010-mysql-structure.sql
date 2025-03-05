/*
 Navicat Premium Dump SQL

 Source Server         : strom-php-app@docker_localhost
 Source Server Type    : MySQL
 Source Server Version : 80404 (8.4.4)
 Source Host           : localhost:3310
 Source Schema         : strom-php-app

 Target Server Type    : MySQL
 Target Server Version : 80404 (8.4.4)
 File Encoding         : 65001

 Date: 05/03/2025 14:21:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for customer
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `clientKey` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `authToken` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isActive` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `clientKey`(`clientKey` ASC) USING BTREE,
  UNIQUE INDEX `authToken`(`authToken` ASC) USING BTREE,
  CONSTRAINT `customer_clientKey` CHECK (length(`clientKey`) = 50),
  CONSTRAINT `customer_name` CHECK (length(`name`) > 0),
  CONSTRAINT `customer_authToken` CHECK (length(`authToken`) = 50),
  CONSTRAINT `customer_isActive` CHECK (`isActive` in (0,1))
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `thread_id` int UNSIGNED NOT NULL,
  `customer_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `hash` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `customer_id`(`customer_id` ASC, `thread_id` ASC) USING BTREE,
  INDEX `message_ibfk_2`(`thread_id` ASC, `customer_id` ASC) USING BTREE,
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`thread_id`, `customer_id`) REFERENCES `thread` (`id`, `customer_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `message_hash` CHECK (length(`hash`) = 10)
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for thread
-- ----------------------------
DROP TABLE IF EXISTS `thread`;
CREATE TABLE `thread`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `hash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `hash`(`hash` ASC) USING BTREE,
  UNIQUE INDEX `customer_id`(`customer_id` ASC, `code` ASC) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC, `customer_id` ASC) USING BTREE,
  CONSTRAINT `thread_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `thread_hash` CHECK (length(`hash`) = 100)
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int UNSIGNED NOT NULL,
  `hash` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emailAddress` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `avatarURL` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `customer_id`(`customer_id` ASC, `code` ASC) USING BTREE,
  UNIQUE INDEX `customer_id_2`(`customer_id` ASC, `hash` ASC) USING BTREE,
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_hash` CHECK (length(`hash`) = 10)
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Function structure for RANDOM_STRING
-- ----------------------------
DROP FUNCTION IF EXISTS `RANDOM_STRING`;
delimiter ;;
CREATE FUNCTION `RANDOM_STRING`(`I_LENGTH` smallint(3) unsigned)
 RETURNS varchar(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
  NO SQL 
  SQL SECURITY INVOKER
BEGIN
  DECLARE $str VARCHAR(100)  DEFAULT '';
  DECLARE $allowedChars CHAR(62) DEFAULT 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  DECLARE $i SMALLINT(3) UNSIGNED DEFAULT 0;

  WHILE ($i < I_LENGTH) DO
    SET $str = CONCAT($str, substring($allowedChars, FLOOR(RAND() * LENGTH($allowedChars) + 1), 1));
    SET $i = $i + 1;
  END WHILE;

  RETURN $str;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table customer
-- ----------------------------
DROP TRIGGER IF EXISTS `customer_bi`;
delimiter ;;
CREATE TRIGGER `customer_bi` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN
  SET new.clientKey = (SELECT RANDOM_STRING(50));
  SET new.authToken = (SELECT RANDOM_STRING(50));
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table customer
-- ----------------------------
DROP TRIGGER IF EXISTS `customer_bu`;
delimiter ;;
CREATE TRIGGER `customer_bu` BEFORE UPDATE ON `customer` FOR EACH ROW BEGIN
  IF(new.clientKey = '') THEN
    SET new.clientKey = (SELECT RANDOM_STRING(50));
  END IF;
  IF(new.authToken = '') THEN
    SET new.authToken= (SELECT RANDOM_STRING(50));
  END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table message
-- ----------------------------
DROP TRIGGER IF EXISTS `message_bi`;
delimiter ;;
CREATE TRIGGER `message_bi` BEFORE INSERT ON `message` FOR EACH ROW BEGIN
  SET new.hash = (SELECT RANDOM_STRING(10));
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table thread
-- ----------------------------
DROP TRIGGER IF EXISTS `thread_bi`;
delimiter ;;
CREATE TRIGGER `thread_bi` BEFORE INSERT ON `thread` FOR EACH ROW BEGIN
  SET new.hash = (SELECT RANDOM_STRING(100));
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table user
-- ----------------------------
DROP TRIGGER IF EXISTS `user_bi`;
delimiter ;;
CREATE TRIGGER `user_bi` BEFORE INSERT ON `user` FOR EACH ROW BEGIN
  SET new.hash = (SELECT RANDOM_STRING(10));
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table user
-- ----------------------------
DROP TRIGGER IF EXISTS `user_bu`;
delimiter ;;
CREATE TRIGGER `user_bu` BEFORE UPDATE ON `user` FOR EACH ROW BEGIN
  IF(new.hash = '') THEN 
    SET new.hash = (SELECT RANDOM_STRING(10));
  END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
