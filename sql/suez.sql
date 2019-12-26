-- MySQL dump 10.16  Distrib 10.1.34-MariaDB, for Win32 (AMD64)
--
-- Host: 172.16.0.224    Database: suez
-- ------------------------------------------------------
-- Server version	10.3.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `suez`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `suez` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `suez`;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `account` varchar(30) NOT NULL COMMENT '管理员账号',
  `password` varchar(32) NOT NULL COMMENT '管理员密码',
  `enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT '账号是否启用',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_account_index` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员账号密码';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','VMhJni9b',1),(3,'mingzhanghui','VMhJni9b',1),(4,'root','EZ8J3itc3IA=',1);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `c_name` varchar(16) NOT NULL DEFAULT '' COMMENT '配置项',
  `c_value` varchar(128) NOT NULL DEFAULT '' COMMENT '配置值',
  `c_comment` varchar(128) NOT NULL DEFAULT '' COMMENT '说明',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建日期',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '修改日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `c_name` (`c_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'email_server','imap-mail.outlook.com:993/imap/ssl','邮件服务器','2019-12-18 16:29:09','2019-12-18 16:29:09'),(2,'email_account','wts.suez@outlook.com','邮箱账号','2019-12-18 16:29:09','2019-12-18 16:29:09'),(3,'email_password','suezitPa55w@rd','邮箱密码','2019-12-18 16:29:09','2019-12-18 16:29:09'),(4,'sender_email','212333966@mail.ad.ge.com','发件人','2019-12-18 16:29:09','2019-12-18 16:29:09'),(5,'qr_prefix','http://192.168.4.157:8058/qr_sign/','二维码前缀','2019-12-18 16:29:09','2019-12-18 16:37:17');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_number` char(10) NOT NULL DEFAULT '0000000000' COMMENT '批号',
  `mm6` char(7) NOT NULL DEFAULT '0000000' COMMENT '6字原代码',
  `mm7` char(7) NOT NULL DEFAULT '0000000' COMMENT '7字原代码',
  `language` varchar(16) NOT NULL DEFAULT '''Chinese''' COMMENT '语言',
  `weight` int(11) NOT NULL DEFAULT 0 COMMENT '重量',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建日期',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='产品';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'2561935006','6115776','7005313','Chinese',192,'2019-12-24 15:25:09','2019-12-24 15:25:09'),(3,'2561935569','6091229','7145660','Chinese',1000,'2019-12-24 17:48:27','2019-12-24 17:48:27');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms`
--

DROP TABLE IF EXISTS `sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` char(11) NOT NULL DEFAULT '00000000000' COMMENT '手机号',
  `code` char(6) NOT NULL DEFAULT '000000' COMMENT '验证码',
  `address` varchar(16) NOT NULL DEFAULT '' COMMENT '手机号归属地',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '查询时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发送短信历史记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms`
--

LOCK TABLES `sms` WRITE;
/*!40000 ALTER TABLE `sms` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-24 18:28:48
