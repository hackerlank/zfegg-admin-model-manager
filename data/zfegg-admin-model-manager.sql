/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : zfegg-admin

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-06-24 11:16:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `model_manager_data_source_config`
-- ----------------------------
DROP TABLE IF EXISTS `model_manager_data_source_config`;
CREATE TABLE `model_manager_data_source_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `adapter` varchar(20) NOT NULL,
  `adapter_options` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of model_manager_data_source_config
-- ----------------------------

-- ----------------------------
-- Table structure for `model_manager_ui_config`
-- ----------------------------
DROP TABLE IF EXISTS `model_manager_ui_config`;
CREATE TABLE `model_manager_ui_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `source` varchar(20) NOT NULL,
  `source_config` text,
  `ui_hidden` text,
  `ui_title` text,
  `ui_template` text,
  `ui_width` text,
  `ui_index` text,
  `ui_sortable` text,
  `ui_filterable` text,
  `ui_type` text,
  `detail_enable` tinyint(4) NOT NULL DEFAULT '0',
  `detail_template` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of model_manager_ui_config
-- ----------------------------
