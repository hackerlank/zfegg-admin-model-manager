SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `model_manager_data_source_config`
-- ----------------------------
DROP TABLE IF EXISTS `model_manager_data_source_config`;
CREATE TABLE `model_manager_data_source_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `adapter` varchar(20) NOT NULL,
  `adapter_options` text NOT NULL,
  `fields` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `model_manager_ui_config`
-- ----------------------------
DROP TABLE IF EXISTS `model_manager_ui_config`;
CREATE TABLE `model_manager_ui_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `source` varchar(20) NOT NULL,
  `columns` text,
  `detail_template` text,
  `toolbar` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
