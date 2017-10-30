/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : crm

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-10-30 18:11:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL COMMENT '招商部ID',
  `enterprise_code` varchar(50) NOT NULL COMMENT '企业代码',
  `name` varchar(50) NOT NULL COMMENT '企业名称',
  `legal_person` varchar(50) NOT NULL DEFAULT '' COMMENT '法人',
  `address` varchar(120) NOT NULL COMMENT '企业地址',
  `area` char(15) NOT NULL DEFAULT '' COMMENT '区县',
  `place` varchar(50) NOT NULL DEFAULT '' COMMENT '所别',
  `industry` varchar(50) NOT NULL DEFAULT '' COMMENT '行业分类',
  `telephone` varchar(15) NOT NULL COMMENT '联系电话',
  `establish_day` date NOT NULL COMMENT '成立日期',
  `postal_code` bigint(20) NOT NULL COMMENT '邮编',
  `phone` bigint(11) NOT NULL COMMENT '手机',
  `credit_code` varchar(50) NOT NULL COMMENT '信用代码',
  `capital` decimal(11,2) NOT NULL COMMENT '注册资金',
  `tax_name` varchar(50) NOT NULL DEFAULT '' COMMENT '财务姓名',
  `tax_telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `tax_phone` bigint(11) NOT NULL COMMENT '联系手机',
  `increment` decimal(11,2) NOT NULL COMMENT '增值税比例',
  `business` decimal(11,2) NOT NULL COMMENT '营业税比例',
  `income` decimal(11,2) NOT NULL COMMENT '企业所得税比例',
  `personal` decimal(11,2) NOT NULL COMMENT '个人所得税比例',
  `manage` decimal(11,2) NOT NULL COMMENT '管理费',
  `settlement` char(10) NOT NULL DEFAULT '' COMMENT '结算比例',
  `bank_name` varchar(100) NOT NULL DEFAULT '',
  `bank_number` varchar(50) NOT NULL,
  `is_cognizance` enum('N','Y') NOT NULL COMMENT '是否认定',
  `is_ratepaying` enum('N','Y') NOT NULL COMMENT '是否纳税',
  `is_close` enum('N','Y') NOT NULL COMMENT '是否歇业',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `certificates` text COMMENT '证件资料',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of company
-- ----------------------------
INSERT INTO `company` VALUES ('1', '1', '433022', '上海逗比网络科技股份有限公司', '', '延安西路2000', '黄浦区', '', '保险业', '456456456', '2017-10-20', '433200', '18816978523', '4564561215645X', '50.00', '', '', '18816954121', '50.00', '40.00', '30.00', '20.00', '100.00', '', '上海工商银行', '123456456456', 'Y', 'Y', 'Y', '的撒多撒多叫撒大花洒', '[\"\\/upload\\/temp\\/5e86545041e84039b4e5d89484a7c256.jpg\",\"\\/upload\\/temp\\/5b357794566f42b1886d13dcf27e577b.jpg\"]');

-- ----------------------------
-- Table structure for company_copy
-- ----------------------------
DROP TABLE IF EXISTS `company_copy`;
CREATE TABLE `company_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL COMMENT '招商部ID',
  `enterprise_code` varchar(50) NOT NULL COMMENT '企业代码',
  `name` varchar(50) NOT NULL COMMENT '企业名称',
  `address` varchar(120) NOT NULL COMMENT '企业地址',
  `area` char(15) NOT NULL DEFAULT '' COMMENT '区县',
  `place` varchar(50) NOT NULL DEFAULT '' COMMENT '所别',
  `industry` varchar(50) NOT NULL DEFAULT '' COMMENT '行业分类',
  `telephone` varchar(15) NOT NULL COMMENT '联系电话',
  `establish_day` date NOT NULL COMMENT '成立日期',
  `postal_code` smallint(10) NOT NULL COMMENT '邮编',
  `phone` bigint(11) NOT NULL COMMENT '手机',
  `credit_code` varchar(50) NOT NULL COMMENT '信用代码',
  `capital` decimal(11,2) NOT NULL COMMENT '注册资金',
  `tax_name` varchar(50) NOT NULL DEFAULT '' COMMENT '财务姓名',
  `tax_telephone` varchar(15) NOT NULL COMMENT '固定电话',
  `tax_phone` bigint(11) NOT NULL COMMENT '联系手机',
  `increment` decimal(11,2) NOT NULL COMMENT '增值税比例',
  `business` decimal(11,2) NOT NULL COMMENT '营业税比例',
  `income` decimal(11,2) NOT NULL COMMENT '企业所得税比例',
  `personal` decimal(11,2) NOT NULL COMMENT '个人所得税比例',
  `manage` decimal(11,2) NOT NULL COMMENT '管理费',
  `settlement` char(10) NOT NULL DEFAULT '' COMMENT '结算比例',
  `bank_name` varchar(100) NOT NULL DEFAULT '',
  `bank_number` varchar(50) NOT NULL,
  `is_cognizance` enum('N','Y') NOT NULL COMMENT '是否认定',
  `is_ratepaying` enum('N','Y') NOT NULL COMMENT '是否纳税',
  `is_close` enum('N','Y') NOT NULL COMMENT '是否歇业',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `certificates` text NOT NULL COMMENT '证件资料',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of company_copy
-- ----------------------------

-- ----------------------------
-- Table structure for investment
-- ----------------------------
DROP TABLE IF EXISTS `investment`;
CREATE TABLE `investment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL COMMENT '招商部名称',
  `area` varchar(50) NOT NULL COMMENT '所属区县',
  `address` varchar(150) NOT NULL COMMENT '地址',
  `telephone` char(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `phone` bigint(11) NOT NULL COMMENT '手机号',
  `the_name` char(15) NOT NULL DEFAULT '' COMMENT '联系人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='招商部表';

-- ----------------------------
-- Records of investment
-- ----------------------------
INSERT INTO `investment` VALUES ('1', '0', '崇明招商部', '崇明区', '上海市崇明岛', '', '15985214785', '王三三');
INSERT INTO `investment` VALUES ('2', '0', '区县部', '浦东新区', '上海市哇哈哈哈', '', '18816978523', '');
