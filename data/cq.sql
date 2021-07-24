-- MariaDB dump 10.19  Distrib 10.4.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: cq
-- ------------------------------------------------------
-- Server version	10.4.18-MariaDB-1:10.4.18+maria~bionic

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `cq`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `cq` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `cq`;

--
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `gold` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '金币',
  `items` varchar(100) NOT NULL DEFAULT '' COMMENT '道具奖励',
  PRIMARY KEY (`id`),
  KEY `activity_name_IDX` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='活动奖励表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity`
--

LOCK TABLES `activity` WRITE;
/*!40000 ALTER TABLE `activity` DISABLE KEYS */;
INSERT INTO `activity` VALUES (1,'qiandao_1',500,'112|1'),(2,'qiandao_2',1000,'112|1'),(3,'qiandao_3',1500,'112|1'),(4,'qiandao_4',2000,'112|1'),(5,'qiandao_5',2500,'112|1'),(6,'qiandao_6',3000,'112|1'),(7,'qiandao_7',3500,'112|1'),(8,'RC01_NEWBIE_GIFTS',10000,'16|50,109|50');
/*!40000 ALTER TABLE `activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area_monster`
--

DROP TABLE IF EXISTS `area_monster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `area_monster` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `area_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '区域编号',
  `monster_ids` varchar(100) NOT NULL DEFAULT '' COMMENT '怪物编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='区域怪物';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area_monster`
--

LOCK TABLES `area_monster` WRITE;
/*!40000 ALTER TABLE `area_monster` DISABLE KEYS */;
INSERT INTO `area_monster` VALUES (1,33,'129,130,131,132,133'),(2,35,'134,135,136,137'),(3,34,'140,141,142,143'),(4,36,'132,133,146,147,148'),(5,37,'146,147,148,149|3'),(6,38,'142,143,144');
/*!40000 ALTER TABLE `area_monster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `club`
--

DROP TABLE IF EXISTS `club`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `club` (
  `clubname` varchar(255) NOT NULL,
  `clubinfo` varchar(255) NOT NULL,
  `clublv` varchar(255) NOT NULL,
  `clubid` int(11) NOT NULL AUTO_INCREMENT,
  `clubno1` int(11) NOT NULL,
  `clubexp` int(11) NOT NULL DEFAULT 0,
  `clubyxb` int(11) NOT NULL DEFAULT 0,
  `clubczb` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`clubid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `club`
--

LOCK TABLES `club` WRITE;
/*!40000 ALTER TABLE `club` DISABLE KEYS */;
/*!40000 ALTER TABLE `club` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clubplayer`
--

DROP TABLE IF EXISTS `clubplayer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clubplayer` (
  `clubid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `uclv` int(11) NOT NULL,
  PRIMARY KEY (`clubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clubplayer`
--

LOCK TABLES `clubplayer` WRITE;
/*!40000 ALTER TABLE `clubplayer` DISABLE KEYS */;
/*!40000 ALTER TABLE `clubplayer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cmd_history`
--

DROP TABLE IF EXISTS `cmd_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmd_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '角色编号',
  `cmd` varchar(255) NOT NULL COMMENT '命令参数',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '执行时间',
  PRIMARY KEY (`id`),
  KEY `cmd_history_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='执行命令历史';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cmd_history`
--

LOCK TABLES `cmd_history` WRITE;
/*!40000 ALTER TABLE `cmd_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `cmd_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combat`
--

DROP TABLE IF EXISTS `combat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '战斗编号',
  `attacker_id` int(11) NOT NULL DEFAULT 0 COMMENT '发起者编号',
  `defender_id` int(11) NOT NULL DEFAULT 0 COMMENT '防御者编号',
  `data` text DEFAULT NULL COMMENT '战斗状态，包括攻防双方的所有属性和状态',
  `logs` text DEFAULT NULL COMMENT '单回合战斗日志',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '战斗类型，1pve 2pvp',
  `is_end` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否结束',
  `result_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '战斗结束类型，0战斗中１攻方胜利２防方胜利３攻方逃跑４防方逃跑',
  `last_turn_timestamp` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COMMENT='战斗数据';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combat`
--

LOCK TABLES `combat` WRITE;
/*!40000 ALTER TABLE `combat` DISABLE KEYS */;
/*!40000 ALTER TABLE `combat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condition`
--

DROP TABLE IF EXISTS `condition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `matchers` varchar(100) NOT NULL DEFAULT '' COMMENT '匹配条件',
  `notes` varchar(100) NOT NULL DEFAULT '' COMMENT '备注说明',
  `success_info` varchar(100) NOT NULL DEFAULT '' COMMENT '成功文本',
  `failure_info` varchar(100) NOT NULL DEFAULT '' COMMENT '失败文本',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='各种限制条件';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condition`
--

LOCK TABLES `condition` WRITE;
/*!40000 ALTER TABLE `condition` DISABLE KEYS */;
INSERT INTO `condition` VALUES (1,'return has_completed_task(6)','村郊外-小道拦截','','还是先去村里比较好。'),(2,'return count_killed_private_monster(100) >= 3','村郊外-空地拦截','','青羽残魂拦住了去路'),(3,'return not has_completed_task(7)','村郊外-操作-检查遗骸','',''),(4,'return has_completed_task(7) and not  has_completed_task(8)','村郊外-操作-查看藤甲','',''),(5,'return false','村郊外-湖水拦截','','这湖水似乎有些奇怪，还是找其他方式更安全。'),(6,'return has_completed_task(8)','村郊外-操作-上山','',''),(7,'return  count_operation(\'副本_巨阙帮_吊桥机关_左\',  23) <= 0','巨阙帮-吊桥左机关','',''),(8,'return  count_operation(\'副本_巨阙帮_吊桥机关_右\',  23) <= 0','巨阙帮-吊桥右机关','',''),(9,'return  count_operation(\'副本_巨阙帮_吊桥机关_右\',  23) > 0  and count_operation(\'副本_巨阙帮_吊桥机关_左\', 23) > 0','巨阙帮-吊桥拦截','','吊桥悬在对面崖壁，无法通过。'),(10,'return has_item(11, 1)','巨阙帮-打开铜箱拦截','',''),(11,'return has_item(10, 1)','巨阙帮-打开铁箱拦截','',''),(12,'return player_attr_num(\'nowmid\') == 231','村郊外-操作-撑筏去岛','',''),(13,'return player_attr_num(\'nowmid\') == 233','村郊外-操作-撑筏回村','',''),(14,'return math.random(1, 100) <= 33','比奇矿区-随机矿石','','');
/*!40000 ALTER TABLE `condition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `type` tinyint(4) NOT NULL DEFAULT 3 COMMENT '对话类型，1问题，2答案，3打听，4吆喝',
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '答案所属的问题编号',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `npc_id` int(11) NOT NULL COMMENT 'NPC 编号',
  `npc_override_id` int(11) DEFAULT 0 COMMENT 'NPC 状态编号',
  PRIMARY KEY (`id`),
  KEY `conversations_npc_id_IDX` (`npc_id`) USING BTREE,
  KEY `conversations_npc_override_id_IDX` (`npc_override_id`) USING BTREE,
  KEY `conversations_parent_id_IDX` (`parent_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
INSERT INTO `conversations` VALUES (1,1,0,'村子里发生了什么事？',11,0),(2,3,0,'村里来了一些练武的异乡人，如果他们愿意帮忙就太好了。',11,0),(3,2,1,'也不知怎么了，村子周围的动物都不对劲，老是咬人。村里组织了一些人力去驱赶这些野兽，但是不怎么管用，唉。',11,0),(6,4,0,'外出要小心村外的野兽。',11,0);
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equip_info`
--

DROP TABLE IF EXISTS `equip_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equip_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `item_id` int(11) NOT NULL DEFAULT 0 COMMENT '物品表编号',
  `level` int(11) NOT NULL DEFAULT 1 COMMENT '等级',
  `hp` int(11) NOT NULL DEFAULT 0 COMMENT '气血',
  `mp` int(11) NOT NULL DEFAULT 0 COMMENT '灵气',
  `baqi` int(11) NOT NULL DEFAULT 0 COMMENT '霸气',
  `wugong` int(11) NOT NULL DEFAULT 0 COMMENT '物理攻击',
  `fagong` int(11) NOT NULL DEFAULT 0 COMMENT '法术攻击',
  `wufang` int(11) NOT NULL DEFAULT 0 COMMENT '物理防御',
  `fafang` int(11) NOT NULL DEFAULT 0 COMMENT '法术防御',
  `shanbi` int(11) NOT NULL DEFAULT 0 COMMENT '闪避',
  `mingzhong` int(11) NOT NULL DEFAULT 0 COMMENT '命中',
  `baoji` int(11) NOT NULL DEFAULT 0 COMMENT '暴击',
  `shenming` int(11) NOT NULL DEFAULT 0 COMMENT '神明',
  `equip_type` int(11) NOT NULL COMMENT '部位1武器2衣服3头盔4项链5手镯6戒指7腰带8鞋子9宝石10勋章',
  `manual_id` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '职业限制，0通用，6战士7法师8道士',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '性别0通用1男2女',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COMMENT='装备信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equip_info`
--

LOCK TABLES `equip_info` WRITE;
/*!40000 ALTER TABLE `equip_info` DISABLE KEYS */;
INSERT INTO `equip_info` VALUES (1,2,1,0,0,0,2,0,0,0,0,0,0,0,1,0,0),(2,8,1,0,0,0,5,0,0,0,0,0,0,0,7,0,0),(3,12,13,0,0,0,31,0,0,0,0,0,0,0,1,0,0),(4,13,15,0,0,0,0,0,36,15,0,0,0,0,3,0,0),(5,17,1,0,0,0,10,0,0,0,0,0,0,0,1,0,0),(6,33,1,0,0,0,0,10,0,0,0,0,0,0,1,0,0),(7,34,1,0,0,0,0,0,5,5,0,0,0,0,2,0,1),(8,35,1,0,0,0,0,0,5,5,0,0,0,0,2,0,2),(9,36,10,0,0,4,0,19,0,0,0,1,1,0,1,7,0),(10,37,10,0,0,3,0,16,0,0,0,1,1,0,1,0,0),(11,38,10,0,0,2,13,0,0,0,0,1,1,0,1,6,0),(12,39,15,0,0,6,0,30,0,0,0,1,1,0,1,7,0),(13,40,15,0,0,5,12,25,0,0,0,1,1,0,1,8,0),(14,41,15,0,0,4,20,0,0,0,0,1,1,0,1,6,0),(15,42,20,0,0,10,0,43,0,0,0,2,2,0,1,7,0),(16,43,20,0,0,8,0,36,0,0,0,2,2,0,1,8,0),(17,44,20,0,0,6,29,0,0,0,0,2,2,0,1,6,0),(18,45,28,0,0,14,0,64,0,0,0,3,3,0,1,7,0),(19,47,28,0,0,12,0,53,0,0,0,3,3,0,1,8,0),(20,46,28,0,0,10,42,0,0,0,0,3,3,0,1,6,0),(23,50,30,0,0,0,115,60,0,0,0,0,0,0,1,0,0),(24,51,10,19,0,0,0,0,16,8,1,0,0,5,2,0,0),(25,52,15,30,0,0,0,0,25,25,2,0,0,8,2,0,0),(26,53,25,72,0,0,0,0,55,55,3,0,0,15,2,6,0),(27,54,25,48,0,0,0,0,37,37,3,0,0,15,2,7,0),(28,55,25,60,0,0,0,0,46,46,3,0,0,15,2,8,0),(29,56,35,118,0,0,0,0,88,88,5,0,0,23,2,6,0),(30,57,35,78,0,0,0,0,58,58,5,0,0,23,2,7,0),(31,58,35,98,0,0,0,0,73,73,5,0,0,23,2,8,0),(32,67,10,0,0,2,2,0,0,0,0,0,3,0,6,0,0),(33,68,15,0,0,3,2,1,0,0,0,1,4,0,6,0,0),(34,69,20,0,0,4,3,3,0,0,0,1,6,0,6,0,0),(35,70,25,0,0,6,0,4,0,0,0,1,8,0,6,7,0),(36,76,10,0,0,22,2,2,0,0,0,1,1,0,4,0,0),(37,77,15,0,0,35,4,3,0,0,0,1,1,0,4,0,0),(38,78,20,0,0,53,5,5,0,0,0,2,2,0,4,0,0),(39,85,10,10,0,0,0,0,1,1,1,0,0,18,5,0,0),(40,86,15,15,0,0,0,0,2,2,1,0,0,28,5,0,0),(41,89,20,28,0,0,0,0,4,4,1,0,0,40,5,6,0),(42,96,10,135,0,0,0,0,2,2,1,0,0,5,3,0,0),(43,97,10,0,0,3,2,2,0,0,0,6,1,0,7,0,0),(44,98,10,19,0,0,0,0,2,2,7,0,0,5,8,0,0),(45,99,15,30,0,0,0,0,4,4,11,0,0,8,8,0,0),(46,100,20,45,0,0,0,0,5,5,16,0,0,11,8,0,0),(47,101,25,60,0,0,0,0,7,7,20,0,0,15,8,0,0),(48,102,15,0,0,5,4,4,0,0,0,8,1,0,7,0,0),(49,103,20,0,0,8,5,5,0,0,0,12,2,0,7,0,0),(50,104,25,0,0,10,7,7,0,0,0,15,2,0,7,0,0),(51,105,15,210,0,0,0,0,4,4,2,0,0,8,3,0,0),(52,106,20,315,0,0,0,0,5,5,2,0,0,11,3,0,0),(53,107,25,336,0,0,0,0,6,6,3,0,0,15,3,7,0),(54,108,10,0,0,65,0,0,0,0,0,0,0,0,9,0,0),(55,110,1,0,0,22,0,0,0,0,0,0,0,0,9,0,0),(56,111,1,0,0,5,5,5,5,5,5,5,5,5,10,0,0),(57,117,10,0,0,0,1,1,0,0,0,0,0,0,1,0,0),(58,87,20,18,0,0,0,0,2,2,1,0,0,40,5,7,0),(59,88,20,23,0,0,0,0,3,3,1,0,0,40,5,8,0),(60,71,25,0,0,5,0,3,0,0,0,1,8,0,6,8,0),(61,72,25,0,0,4,2,0,0,0,0,1,8,0,6,6,0),(62,79,25,0,0,71,7,7,0,0,0,2,2,0,4,8,0),(63,80,25,0,0,85,8,8,0,0,0,2,2,0,4,7,0),(64,81,25,0,0,57,6,6,0,0,0,2,2,0,4,6,0),(65,119,25,504,0,0,0,0,8,8,3,0,0,15,3,6,0),(66,120,25,420,0,0,0,0,7,7,3,0,0,15,3,8,0),(67,121,25,36,0,0,0,0,4,4,1,0,0,51,5,6,0),(68,122,25,24,0,0,0,0,2,2,1,0,0,51,5,7,0),(69,123,25,30,0,0,0,0,3,3,1,0,0,51,5,8,0),(70,127,20,0,0,145,0,0,0,0,0,0,0,0,9,0,0),(71,128,20,0,0,15,15,15,15,15,15,15,15,15,10,0,0),(72,73,35,0,0,10,0,6,0,0,0,2,12,0,6,7,0),(73,75,35,0,0,6,4,0,0,0,0,2,12,0,6,6,0),(74,138,35,0,0,8,0,5,0,0,0,2,12,0,6,8,0),(75,91,35,39,0,0,0,0,4,4,2,0,0,81,5,7,0),(76,94,35,59,0,0,0,0,6,6,2,0,0,81,5,6,0),(77,90,35,49,0,0,0,0,5,5,2,0,0,81,5,8,0),(78,137,35,0,0,136,0,12,0,0,0,4,4,0,4,7,0),(79,136,35,0,0,90,8,0,0,0,0,4,4,0,4,6,0),(80,136,35,0,0,113,10,0,0,0,0,4,4,0,4,8,0),(81,139,35,0,0,113,0,10,0,0,0,4,4,0,4,8,0),(82,48,38,0,0,22,0,98,0,0,0,4,4,0,1,7,0),(83,49,38,0,0,18,0,82,0,0,0,4,4,0,1,8,0),(84,140,38,0,0,14,66,0,0,0,0,4,4,0,1,6,0),(85,82,45,0,0,132,12,0,0,0,0,5,5,0,4,6,0),(86,83,45,0,0,165,0,15,0,0,0,5,5,0,4,8,0),(87,84,45,0,0,198,0,18,0,0,0,5,5,0,4,7,0),(88,142,45,0,0,10,6,0,0,0,0,3,18,0,6,6,0),(89,143,45,0,0,14,0,8,0,0,0,3,18,0,6,7,0),(90,144,45,0,0,12,0,7,0,0,0,3,18,0,6,8,0),(91,92,45,85,0,0,0,0,8,8,3,0,0,116,5,6,0),(92,93,45,57,0,0,0,0,6,6,3,0,0,116,5,7,0),(93,141,45,71,0,0,0,0,7,7,3,0,0,116,5,8,0),(94,127,30,0,0,233,0,0,0,0,0,0,0,0,9,0,0),(95,147,30,0,0,30,30,30,30,30,30,30,30,30,10,0,0),(96,148,35,98,0,0,0,0,10,10,32,0,0,23,8,0,0),(97,149,45,143,0,0,0,0,15,15,46,0,0,33,8,0,0),(98,150,35,683,0,0,0,0,10,10,5,0,0,23,3,0,0),(99,152,45,998,0,0,0,0,15,15,7,0,0,33,3,0,0),(100,153,35,0,0,16,10,10,0,0,0,25,4,0,7,0,0),(101,154,45,0,0,24,15,15,0,0,0,35,5,0,7,0,0),(102,155,55,87,0,0,0,0,9,9,5,0,0,174,5,7,0),(103,156,55,0,0,22,0,13,0,0,0,4,26,0,6,7,0),(104,157,55,0,0,307,0,26,0,0,0,8,8,0,4,7,0),(105,158,55,131,0,0,0,0,13,13,5,0,0,174,5,6,0),(106,159,55,0,0,14,9,0,0,0,0,4,26,0,6,6,0),(107,160,55,0,0,205,18,0,0,0,0,8,8,0,4,6,0),(108,161,55,109,0,0,0,0,11,11,5,0,0,174,5,8,0),(109,162,55,0,0,18,0,11,0,0,0,4,26,0,6,8,0),(110,163,55,0,0,256,0,22,0,0,0,8,8,0,4,8,0),(111,164,55,1218,0,0,0,0,18,18,10,0,0,50,3,7,0),(112,165,55,1828,0,0,0,0,26,26,10,0,0,50,3,6,0),(113,166,55,1523,0,0,0,0,22,22,10,0,0,50,3,8,0);
/*!40000 ALTER TABLE `equip_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equip_keyword`
--

DROP TABLE IF EXISTS `equip_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equip_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `item_id` int(11) NOT NULL DEFAULT 0 COMMENT '物品表编号',
  `info` varchar(30) NOT NULL DEFAULT '' COMMENT '效果名称',
  `ui_info` varchar(100) NOT NULL DEFAULT '' COMMENT '带样式的显示效果',
  `column` varchar(30) NOT NULL DEFAULT '' COMMENT '修正的字段',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT '修正数值',
  `effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '修正类型，1乘法，2加法',
  `target` tinyint(4) NOT NULL DEFAULT 1 COMMENT '效果目标，1装备自身属性，2人物总属性',
  `is_column` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否是属性修正参数',
  `is_wushang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理伤害修正参数',
  `is_wumian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理免疫修正参数',
  `is_fashang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法术伤害修正参数',
  `is_famian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法伤免疫修正参数',
  `is_mingzhong` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是命中率影响参数',
  `is_shanbi` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是闪避率修正参数',
  `is_baoji` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是暴击率修正参数',
  `is_shenming` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是抗暴率修正参数',
  `is_dot` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是独立计算的DOT效果',
  `is_custom` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是其他用途的效果',
  `custom_effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '自定义效果的影响方式，1增加效果2移除效果',
  `identity` varchar(20) NOT NULL DEFAULT '' COMMENT '效果标识，用于重复判断',
  `is_unique` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否唯一效果',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='装备关键字';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equip_keyword`
--

LOCK TABLES `equip_keyword` WRITE;
/*!40000 ALTER TABLE `equip_keyword` DISABLE KEYS */;
INSERT INTO `equip_keyword` VALUES (1,13,'','','wufang',5,1,1,1,0,0,0,0,0,0,0,0,0,0,1,'',0);
/*!40000 ALTER TABLE `equip_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formula_info`
--

DROP TABLE IF EXISTS `formula_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formula_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `item_id` int(11) NOT NULL DEFAULT 0 COMMENT '物品表编号',
  `ingredients` varchar(100) NOT NULL COMMENT '配方材料',
  `product` int(11) NOT NULL COMMENT '配方成品',
  `others` varchar(100) DEFAULT '' COMMENT '其他副产物',
  `min_rate` tinyint(4) NOT NULL DEFAULT 0 COMMENT '最低成功率',
  `max_rate` tinyint(4) NOT NULL DEFAULT 100 COMMENT '最高成功率',
  `up_rate` tinyint(3) unsigned NOT NULL DEFAULT 5 COMMENT '升级速度',
  `type` tinyint(3) unsigned NOT NULL DEFAULT 3 COMMENT '配方分类，1道具，2装备，3药品，4其他',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='配方信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formula_info`
--

LOCK TABLES `formula_info` WRITE;
/*!40000 ALTER TABLE `formula_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `formula_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game1`
--

DROP TABLE IF EXISTS `game1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game1` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[系统]玩家uid',
  `sid` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '[系统]玩家sid',
  `user_id` int(11) DEFAULT NULL COMMENT '[系统]帐号编号',
  `name` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '[系统]玩家名称',
  `level` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '[系统]玩家等级',
  `uyxb` int(11) NOT NULL DEFAULT 2000 COMMENT '[系统]玩家游戏币',
  `uczb` int(11) NOT NULL DEFAULT 100 COMMENT '[系统]玩家充值币',
  `exp` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]玩家经验',
  `max_exp` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]玩家当前最大经验',
  `vip` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]玩家VIP等级',
  `sex` int(11) NOT NULL DEFAULT 1 COMMENT '[系统]玩家性别',
  `endtime` datetime NOT NULL COMMENT '[系统]玩家下线时间',
  `nowmid` int(11) NOT NULL DEFAULT 225 COMMENT '[系统]玩家所在地图',
  `nowguaiwu` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]现在攻击的怪物',
  `tool1` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置1',
  `tool2` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置2',
  `tool3` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置3',
  `tool4` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置4',
  `tool5` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置5',
  `tool6` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置6',
  `tool7` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置7，法宝位置 ',
  `tool8` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置7，法宝位置 ',
  `tool9` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置9',
  `tool10` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置10',
  `tool11` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置11',
  `tool12` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]装备位置12',
  `sfzx` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]是否在线',
  `qandaotime` datetime DEFAULT NULL COMMENT '[系统]暂定',
  `xiuliantime` datetime DEFAULT NULL COMMENT '[系统]修炼时间',
  `sfxl` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]是否修炼',
  `yp1` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]药品位置1',
  `yp2` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]药品位置2',
  `yp3` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]药品位置3',
  `cw` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]宠物位置',
  `jn1` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]技能位置1',
  `jn2` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]技能位置2',
  `jn3` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]技能位置3',
  `ispvp` int(11) NOT NULL DEFAULT 0 COMMENT '[系统]是否PVP',
  `hp` int(11) NOT NULL DEFAULT 10 COMMENT '气血',
  `mp` int(11) NOT NULL DEFAULT 10 COMMENT '灵气',
  `baqi` int(11) NOT NULL DEFAULT 10 COMMENT '霸气',
  `wugong` int(11) NOT NULL DEFAULT 10 COMMENT '物理攻击',
  `fagong` int(11) NOT NULL DEFAULT 10 COMMENT '法术攻击',
  `wufang` int(11) NOT NULL DEFAULT 10 COMMENT '物理防御',
  `fafang` int(11) NOT NULL DEFAULT 10 COMMENT '法术防御',
  `shanbi` int(11) NOT NULL DEFAULT 13 COMMENT '闪避',
  `mingzhong` int(11) NOT NULL DEFAULT 10 COMMENT '命中',
  `baoji` int(11) NOT NULL DEFAULT 10 COMMENT '暴击',
  `shenming` int(11) NOT NULL DEFAULT 67 COMMENT '神明',
  `maxhp` int(11) NOT NULL DEFAULT 10 COMMENT '最大气血',
  `maxmp` int(11) NOT NULL DEFAULT 10 COMMENT '最大灵气',
  `layer_id` tinyint(4) NOT NULL DEFAULT 0 COMMENT '五大境界编号0凡人1炼气2筑基3金丹4元神5真仙',
  `layer_name` varchar(10) NOT NULL DEFAULT '武者' COMMENT '五大境界名称',
  `manual_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法编号',
  `manual_level_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法等级编号',
  `exp_pool` int(11) NOT NULL DEFAULT 0 COMMENT '经验池',
  `player_manual_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色当前功法编号',
  `lifting_capacity` int(10) unsigned NOT NULL DEFAULT 100 COMMENT '负重',
  `party_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '队伍编号',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ认证',
  `vip_ended_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '会员结束时间',
  `master_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '主号编号，为以后多号做准备',
  PRIMARY KEY (`id`),
  KEY `game1_user_id_IDX` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game1`
--

LOCK TABLES `game1` WRITE;
/*!40000 ALTER TABLE `game1` DISABLE KEYS */;
/*!40000 ALTER TABLE `game1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_config`
--

DROP TABLE IF EXISTS `game_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `k` varchar(100) NOT NULL COMMENT '标识',
  `v` varchar(100) NOT NULL DEFAULT '' COMMENT '值',
  PRIMARY KEY (`id`),
  KEY `game_config_k_IDX` (`k`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_config`
--

LOCK TABLES `game_config` WRITE;
/*!40000 ALTER TABLE `game_config` DISABLE KEYS */;
INSERT INTO `game_config` VALUES (1,'firstmid','307'),(2,'shengxing_item_1','112'),(3,'shengxing_item_2','0'),(4,'shengxing_item_3','0'),(5,'mining_tool','117'),(6,'qianghua_level_1','118|2,113|3'),(7,'qianghua_level_2','118|4,114|3'),(8,'qianghua_level_3','118|6,115|3'),(9,'qianghua_level_4','118|8,116|3'),(10,'qianghua_level_5','118|10,132|3'),(11,'qianghua_level_6','118|12,133|3'),(12,'qianghua_level_7','118|14,134|3'),(13,'qianghua_level_8','118|16,135|3'),(14,'qianghua_level_9','0'),(15,'qianghua_level_10','0'),(16,'qianghua_level_11','0'),(17,'qianghua_level_12','0'),(18,'initial_mid','566');
/*!40000 ALTER TABLE `game_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guaiwu`
--

DROP TABLE IF EXISTS `guaiwu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guaiwu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '怪物ID',
  `name` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '[系统]怪物名称',
  `level` int(11) NOT NULL COMMENT '怪物等级',
  `info` text CHARACTER SET utf8 NOT NULL COMMENT '怪物信息',
  `sex` varchar(5) NOT NULL COMMENT '怪物性别',
  `gdj` text CHARACTER SET gb2312 NOT NULL COMMENT '怪物掉落道具',
  `djjv` int(11) NOT NULL COMMENT '道具几率',
  `is_group` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是全体怪物',
  `is_aggressive` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是主动怪',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '怪物种类, 1普通，2精英，3BOSS',
  `pz` tinyint(4) NOT NULL DEFAULT 0 COMMENT '怪物品质',
  `flags` int(11) NOT NULL DEFAULT 0 COMMENT '怪物属性,1私有怪，2一次性怪，3私有一次性',
  `hp` int(11) NOT NULL DEFAULT 10 COMMENT '气血',
  `mp` int(11) NOT NULL DEFAULT 10 COMMENT '灵气',
  `baqi` int(11) NOT NULL DEFAULT 10 COMMENT '霸气',
  `wugong` int(11) NOT NULL DEFAULT 10 COMMENT '物理攻击',
  `fagong` int(11) NOT NULL DEFAULT 10 COMMENT '法术攻击',
  `wufang` int(11) NOT NULL DEFAULT 10 COMMENT '物理防御',
  `fafang` int(11) NOT NULL DEFAULT 10 COMMENT '法术防御',
  `shanbi` int(11) NOT NULL DEFAULT 13 COMMENT '闪避',
  `mingzhong` int(11) NOT NULL DEFAULT 10 COMMENT '命中',
  `baoji` int(11) NOT NULL DEFAULT 10 COMMENT '暴击',
  `shenming` int(11) NOT NULL DEFAULT 67 COMMENT '神明',
  `exp` int(11) NOT NULL DEFAULT 0 COMMENT '经验值',
  `is_private` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是私有怪',
  `max_amount` int(11) NOT NULL DEFAULT 0 COMMENT '最多击杀数量0不限其他情况为对应的数量',
  `manual_level_id` int(11) NOT NULL DEFAULT 0 COMMENT '修炼的功法信息',
  `skills` varchar(100) NOT NULL DEFAULT '' COMMENT '怪物技能编号列表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guaiwu`
--

LOCK TABLES `guaiwu` WRITE;
/*!40000 ALTER TABLE `guaiwu` DISABLE KEYS */;
INSERT INTO `guaiwu` VALUES (106,'鸡',1,'生长或游荡在比奇城居民区附近，如物品商店、银杏树谷和边界村等处，可以挖肉。','雄性','14|120,16|120',0,0,0,1,0,0,40,10,6,10,10,10,10,13,10,10,67,10,0,0,0,'0'),(107,'猪',3,'从等级1开始可以宰杀的动物，为了找出吃的东西村子附近里走动，比鸡稍强，可以挖肉，但是一不小心就会吃亏。','雄性','15|120,17|100,33|100,34|100,35|100',0,0,0,1,0,0,137,10,21,27,27,27,27,18,14,14,91,20,0,0,0,'0'),(108,'鹿',6,'生活在城区和村庄，能提供成块的肉。','雌性','51|20,67|20,76|20,85|20',0,0,0,1,0,0,289,10,43,54,54,54,54,26,19,19,129,35,0,0,0,'0'),(109,'稻草人',7,'在村外的森林里面，你会遇到这种生物。人们用他们保护庄稼不受害鸟的袭击。但是稻草人会在邪恶的、未知的力量驱使下逐渐变异，死亡时会爆炸出火焰，有时候还会丢下乌木剑来。这是一种初级怪物，你能轻易地杀死它们。','无','51|20,67|20,76|20,85|20',0,0,0,1,0,0,335,10,51,63,63,63,63,28,21,21,141,40,0,0,0,'0'),(110,'钉耙猫',8,'与钩子猫类似，它一般生活在荒野中，能站立和行走，并会在邪恶和未知的力量驱使下长大。它偷窃人们的耙子用来做武器，出没在地牢、北门之间的森林与河边，及矿山和南门之间的树林里。它能给你18点经验值。','雄性','51|20,67|20,76|20,85|20',0,0,0,1,0,0,386,10,58,71,71,71,71,31,23,23,153,45,0,0,0,'0'),(111,'毒蜘蛛',10,'毒蜘蛛几乎出现在各个角落。它甚至能够隔着拐角向目标进攻和喷射毒液且命中率高，一旦被它击中，你的身体会变绿并丧失能量。','雄性','51|20,67|20,76|20,85|20',0,0,0,1,0,0,609,10,73,88,88,88,88,35,27,27,178,55,0,0,0,'0'),(112,'食人花',9,'生长在森林里的以人为食的植物，不能移动并隐藏在地下。当人们接近时它会跃起来发动攻击。食人花攻击力很强，但最大的弱点是体力差，很容易被远距离的进攻所消灭。可获取叶子和果实。','雌性','51|20,67|20,76|20,85|20',0,0,0,1,0,0,440,10,65,80,80,80,80,33,25,25,166,50,0,0,0,'0'),(113,'牛',5,'从等级1开始都可以宰杀的动物，由于体格大，可能有些人不敢去惹它，但是请放心，它非常的弱。可以挖肉买卖钱。','雄性','51|20,67|20,76|20,85|20',0,0,0,1,0,0,238,10,36,45,45,45,45,23,17,17,117,30,0,0,0,'0'),(114,'狼',9,'狼是生活在荒漠和牧场的肉食类动物。它一般猎羊为食，但有时也会去居住区袭击人。它们常常群体行动，动作很敏捷，攻击力强而持久，所以你出门远行时要当心点。','雄性','51|20,67|20,76|20,85|20',0,1,0,1,0,0,440,10,65,80,80,80,80,33,25,25,166,50,0,0,0,'0'),(115,'多钩猫',10,'一种大多生活在荒野中的猫，能够步行，并会在邪恶和未知的力量驱使下变大。它偷窃人们的钩子用做武器。','雌性','51|20,67|20,76|20,85|20',0,0,0,1,0,0,609,10,73,88,88,88,88,35,27,27,178,55,0,0,0,'0'),(116,'山洞蝙蝠',12,'在地牢里到处都是这种飞翔的生物。尽管他们的攻击力不强，但它们群起进攻时还是躲远些为好。','无','',0,0,0,1,0,0,730,10,88,107,107,107,107,41,30,30,203,115,0,0,0,'0'),(117,'洞蛆',14,'在地牢里他们蠕动着，并通过喷射气体向你进攻。他们体力较差但喷射的气体能使你瘫痪。一旦瘫痪的话你不能行动，只能使用药水。它们的尸体是一种','无','',0,0,0,1,0,0,857,10,103,124,124,124,124,46,34,34,227,175,0,0,0,'0'),(118,'腐尸',15,'由腐烂程度较深的尸体复活而成，缺胳膊少腿四肢不全，只能爬行移动。不容易被发现，他们抓住人腿或用手抓破攻击。他具有复活力，可以重生很多次。只能像对付僵尸一样，将他打碎。但以为他是爬行的，速度慢，所以容易逃走。','无','',0,0,0,1,0,0,920,10,110,132,132,132,132,48,36,36,240,205,0,0,0,'0'),(119,'僵尸',16,' 僵尸原先是一个矿山中的矿工。矿山在奇怪力量导致的地震中坍塌，他们被困其中。他们死去后重生为僵尸。他们有再生的力量，最终消亡前可以再生三次。一些僵尸会在人们接近时从地下迸出来，主要以手猛击的方式攻击，但有时会咬你。消灭的方法只有完全烧毁或弄成碎片。','无','',0,0,0,1,0,0,1006,10,120,142,142,142,142,52,39,39,260,235,0,0,0,'0'),(120,'僧侣僵尸',18,'是因犯下错误而被赶出庙的和尚的尸体受山洞中奇怪力量复活而成。平常在地下，一旦听到有脚步声就会从地下爬出来，用双手猛抓。和其他僵尸不同，他不能重生。','无','',0,0,0,1,0,0,1175,10,141,163,163,163,163,60,45,45,300,295,0,0,0,'0'),(121,'雷电僵尸',20,'他生前是使用强有力的魔法的法师，他的尸体原先放在山洞附近，是受奇怪力量作用成为僵尸，会使用强大的电击魔法。这种魔法使聚在一起的人同时受攻击，所以最好尽量散开。','无','',0,0,0,1,0,0,1618,10,162,183,183,183,183,68,51,51,340,355,0,0,0,'21'),(122,'尸王',30,'尸王是僵尸之王，生活在死矿中。它能在死尸中注入奇怪的气体使他们成为僵尸。原先他拥有强大的魔力，但都消耗在制造僵尸，现在他正试图找回这种力量。如果人们经过死矿，它会用任意支配的能力和缠绕在身体上的铁链杀死他们。','无','',0,1,0,3,0,0,8838,10,265,432,432,572,572,200,81,81,540,905,0,0,0,'0'),(123,'蜈蚣',22,'它们居住在洞穴深处，尽管没有很强的力量，但快速的行动和攻击却能使你防不胜防。','无','',0,0,0,1,0,0,1826,10,182,204,204,204,204,76,57,57,380,465,0,0,0,'0'),(124,'洞穴蜈蚣',23,'它们居住在洞穴深处，尽管没有很强的力量，但快速的行动和攻击却能使你防不胜防。','无','',0,0,0,1,0,0,1930,10,193,214,214,214,214,80,60,60,400,520,0,0,0,'0'),(125,'巨型蠕虫',24,'和蜈蚣相似，也居住在洞穴深处，尽管没有很强的力量，但快速的行动和攻击却能使你防不胜防。','无','',0,0,0,1,0,0,2034,10,203,224,224,224,224,84,63,63,420,575,0,0,0,'0'),(126,'跳跳蜂',21,'这是一种长着翅膀的昆虫。相比它巨大的身体那翅膀显得很小，所以它们无法飞翔，而是通过快速跳跃而行动。','无','',0,1,0,1,0,0,1722,10,172,194,194,194,194,72,54,54,360,410,0,0,0,'0'),(127,'黑色恶蛆',25,'它们由蛆的尸体变异而来，以高速旋转的身体为武器向人们进攻。它们会对所有靠近的人发起攻击，由于它们这样快的速度，使被攻击的人很难逃开。','无','',0,0,0,1,0,0,2138,10,213,235,235,235,235,88,66,66,440,630,0,0,0,'0'),(128,'钳虫',27,'这是一种用长在前额的巨大的、锋利的钳子进攻的，强有力的昆虫类生物。它们有坚硬的外壳保护身体，进攻敌人时会以六条腿快速移动。一定要当心被它坚硬的表皮伤害。由于长期生活在黑暗的洞穴中，它们已经丧失了视力，但它们敏锐的触角可以通过气味或湿度来感知敌人的威胁。','无','',0,0,0,1,0,0,2346,10,234,255,255,255,255,96,72,72,480,740,0,0,0,'0'),(129,'粪虫',30,'它们本来是沃玛教众，但在沃玛教主召唤时被邪恶可怕的力量变成奇怪的动物。','无','',0,0,0,1,0,0,3535,10,331,286,286,286,286,108,81,81,540,905,0,0,0,'0'),(130,'暗黑战士',31,'它们曾是沃玛教的成员，但在沃玛教主复活后被邪恶和有害的力量变成奇怪的生物。','无','',0,0,0,1,0,0,3711,10,348,300,300,300,300,114,86,86,570,990,0,0,0,'0'),(131,'沃玛战士',32,'沃玛寺庙的护卫，它们残忍地攻击敢于进入神殿的人。','无','',0,0,0,1,0,0,3895,10,365,313,313,313,313,120,90,90,600,1075,0,0,0,'0'),(132,'沃玛勇士',33,'沃玛寺庙的护卫，它们残忍地攻击敢于进入神殿的人。','无','',0,0,0,1,0,0,4071,10,381,326,326,326,326,126,95,95,630,1160,0,0,0,'0'),(133,'沃玛战将',35,'沃玛神殿的保卫者，他们曾是沃玛教的成员，沃玛教主复活后他们被杀死，死后的尸体变成沃玛战将。','无','',0,0,0,1,0,0,4423,10,415,353,353,353,353,138,104,104,690,1330,0,0,0,'0'),(134,'血巨人',45,'它们习惯停留在赤月峡谷不见天日的地方, 一旦受到打扰就会依靠它们强悍的肌肉和锋利的牙进行攻击，直到目标死亡。它本是很正常的猴子, 但是赤月恶魔的影响使他的性格变的残暴，躯体也变的更加强壮有力。','无','',0,0,0,1,0,0,7759,10,665,486,486,486,486,198,149,149,990,2355,0,0,0,'0'),(135,'血金刚',47,'这个可怜的生物在受到赤月恶魔影响前只是一只普通的猴子。现在它的身体开始发生变化，两个头颅的融合带来了超凡的力量，像脑一样的神经组织变成一团，冲破皮肤的包围而凸现出来。','无','',0,0,0,1,0,0,8496,10,729,530,530,530,530,218,164,164,1090,2595,0,0,0,'0'),(136,'赤血恶魔',49,'受到赤月恶魔影响的红色猩猩，更加强大。','无','',0,0,0,1,0,0,9242,10,792,575,575,575,575,238,179,179,1190,2835,0,0,0,'0'),(137,'赤月灰血魔',51,'受到赤月恶魔影响的灰色猩猩。','无','',0,0,0,1,0,0,11975,10,856,619,619,619,619,258,194,194,1290,3115,0,0,0,'0'),(138,'神鬼王',53,'长期潜伏在山谷深处，赤月恶魔最忠诚的守护者。','无','',0,0,0,1,0,0,12871,10,919,663,663,663,663,278,209,209,1390,3435,0,0,0,'0'),(139,'赤月恶魔',55,'赤月恶魔长期潜伏在山谷深处，代表着传奇世界最邪恶、最强大的势力。它们是一切罪恶的根源，是他们将沃玛森林里的生物变成怪物的。它们最为可怕的能力来自非凡的黑暗智慧。','无','',0,0,0,3,0,0,13755,10,983,708,708,708,708,298,224,224,1490,3755,0,0,0,'0'),(140,'大老鼠',35,'它用发达的前爪、尾巴和锋利的牙齿攻击你。虽然不是强大的怪物，但是动作很灵活。','无','',0,0,0,1,0,0,4423,10,415,353,353,353,353,138,104,104,690,1330,0,0,0,'0'),(141,'祖玛弓箭手',37,'有着羊头的它们使用弓箭以准确地攻击远距离的入侵者。它们力量并不强大，但动作迅速，能远距离攻击。如果你被它们瞄准，将处于十分危险的境地。','无','',0,0,0,1,0,0,4783,10,448,380,380,380,380,150,113,113,750,1500,0,0,0,'0'),(142,'祖玛雕像',39,'羊被祖玛教作为祭品而成了神的象征。这种石头雕像在祖玛神殿里随处可见，当入侵者靠得足够近时，他们那以羊血构成的化身会从雕像中出来发动攻击。','无','',0,0,0,1,0,0,5135,10,482,406,406,406,406,162,122,122,810,1670,0,0,0,'0'),(143,'祖玛卫士',41,'它们是祖玛教的保卫者，也具有隐藏在雕像中的羊血化身。','无','',0,0,0,1,0,0,6869,10,515,433,433,433,433,174,131,131,870,1875,0,0,0,'0'),(144,'护法天',43,'它们象祖玛雕像那样分布在祖玛神殿的各个角落，而且当他们的化身隐藏在雕像中时，你不能伤到它。','无','',0,0,0,2,0,0,7319,10,549,459,459,459,459,186,140,140,930,2115,0,0,0,'0'),(145,'祖玛教主',45,'祖玛教徒把恶魔的石像供奉为它们的神。它是一尊站立在祖玛神殿深处的神像，当入侵者靠近时它就会苏醒。一旦醒来，它会发出巨大的火焰攻击它的敌人。','无','',0,0,0,3,0,0,7759,10,466,486,486,486,486,198,149,149,990,2355,0,0,0,'0'),(146,'火焰沃玛',37,'它们出现在沃玛神殿，曾是沃玛教的成员，沃玛教主复活后他们被杀死，死后的尸体变成火焰沃玛。火焰沃玛在这里很强大，但也不能离开神殿。他没有武器但能喷射出强有力的火焰。作为沃玛中最高的等级，它们还长有翅膀。','无','',0,0,0,1,0,0,4783,10,448,380,380,380,380,150,113,113,750,1500,0,0,0,'0'),(147,'沃玛护卫',38,'在神殿中保卫沃玛教主，并为保护教主重生竭尽全力。','无','',0,0,0,1,0,0,4959,10,465,392,392,392,392,156,117,117,780,1585,0,0,0,'0'),(148,'沃玛卫士',39,'沃玛卫士在神殿中保卫沃玛教主，并为保护教主重生竭尽全力。','无','',0,0,0,1,0,0,5135,10,482,406,406,406,406,162,122,122,810,1670,0,0,0,'0'),(149,'沃玛教主',40,'是个强大的魔鬼，被沃玛教徒奉为神明而被追随。','无','',0,1,0,3,0,0,18646,10,1332,818,818,818,818,348,261,261,1740,4555,0,0,0,'0');
/*!40000 ALTER TABLE `guaiwu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `im`
--

DROP TABLE IF EXISTS `im`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `im` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '发送者编号',
  `tid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '目标对象编号',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '消息内容',
  `type` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '消息类型，1系统广播',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '发送时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='消息列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `im`
--

LOCK TABLES `im` WRITE;
/*!40000 ALTER TABLE `im` DISABLE KEYS */;
/*!40000 ALTER TABLE `im` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `ui_name` varchar(100) NOT NULL DEFAULT '' COMMENT '显示名称',
  `info` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `ui_info` varchar(100) NOT NULL DEFAULT '' COMMENT '带样式的描述',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '物品类型，1普通道具2装备3药品4配方5功法',
  `sub_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '子分类，目前仅普通道具有效，1功法,2技能,3矿物',
  `price` int(11) NOT NULL DEFAULT 0 COMMENT '游戏币价值',
  `recharge_price` int(11) NOT NULL DEFAULT 0 COMMENT '充值币价值',
  `quality` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '品质，1粗糙，2普通，3优秀，4精良',
  `event` varchar(100) NOT NULL DEFAULT '' COMMENT '物品触发事件',
  `extra` varchar(100) NOT NULL DEFAULT '' COMMENT '道具特殊字段',
  `is_bound` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否绑定',
  `is_stackable` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否可堆叠',
  `is_sellable` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否可出售',
  `is_task` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否任务专属物品',
  `is_launched` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否在商店出售',
  `launched_shop_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '出售物品的商店类型1道具店2装备3药品4配方店',
  `is_package` tinyint(4) DEFAULT 0 COMMENT '是否是礼包，礼包道具使用后可以获得其他类型物品',
  `package_items` varchar(100) DEFAULT '' COMMENT '礼包内容，1装备，2道具，3药品，4符篆，5配方',
  `operations` varchar(100) NOT NULL DEFAULT '' COMMENT '物品对应的操作选项',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COMMENT='物品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (1,'硬翅蜂-蜂蜜','硬翅蜂-蜂蜜','硬翅蜂的蜂蜜','',1,0,5,0,2,'','',0,1,1,1,0,0,0,'',''),(2,'清锋剑','清锋剑','一把锋利的长剑，剑身锃亮如镜。','',2,0,100,0,2,'','',0,0,1,0,0,0,0,'',''),(3,'还元丹','还元丹','村里郎中炼制的丹药。','',3,0,30,0,3,'','',0,1,1,0,0,0,0,'',''),(4,'硬翅蜂-尾针','硬翅蜂-尾针','从硬翅蜂身上收集的尾针','',1,0,5,0,2,'','',0,1,1,1,0,0,0,'',''),(5,'郎中手札','郎中手札','老郎中的手札','',1,0,0,0,4,'','',1,1,0,0,0,0,1,'5|1|1,5|3|1',''),(6,'硬翅蜂-毒液精华','硬翅蜂-毒液精华','从硬翅蜂尾针中提取出来的毒液精华，可以用来制作药材。','',1,0,10,0,3,'','',0,1,1,0,0,0,0,'',''),(7,'葵花炼神经','葵花炼神经','一本功法秘籍','',1,1,0,0,4,'','2',1,1,0,1,0,0,0,'',''),(8,'青羽翎','青羽翎','一枚奇特的羽毛，散发着动人心魄的灵力。','',2,0,1000,0,5,'','',1,0,0,0,0,0,0,'',''),(9,' 破损的藤甲',' 破损的藤甲','一件破损的藤甲，已经无法穿戴了。','',1,0,0,0,1,'','',1,0,0,1,0,0,0,'','2'),(10,'铁钥匙(巨阙)','铁钥匙(巨阙)','一把普通的铁钥匙','',1,0,0,0,2,'','',1,0,1,1,0,0,0,'','0'),(11,'铜钥匙(巨阙)','铜钥匙(巨阙)','一把普通的铜钥匙','',1,0,0,0,2,'','',1,0,0,1,0,0,0,'','0'),(12,'巨阙剑','巨阙剑','锋利的宝剑','',2,0,0,0,4,'','',1,0,0,0,0,0,0,'','0'),(13,'青霜甲','青霜甲','衣服','',2,0,0,0,4,'','',1,0,0,0,0,0,0,'','0'),(14,'鸡肉','鸡肉','杀死鸡得到的肉，价格因品质而异','',1,0,5,0,1,'','',0,1,1,1,0,0,0,'',''),(15,'猪肉','猪肉','杀死鹿,狼牛和猪得到的肉，价格因品质而异，早期的赚钱手段之一','',1,0,5,0,1,'','',0,1,1,1,0,0,0,'',''),(16,'金创药(小)','金创药(小)','恢复少量体力值，可以在商店购买。','',3,0,30,0,2,'','',0,1,1,0,0,0,0,'',''),(17,'木剑','木剑','一把普普通通的木剑','',2,0,10,0,1,'','',0,0,1,0,0,0,0,'','0'),(18,'战士符文','战士符文','加入战士职业的必须物品。','',1,1,0,0,4,'','6',1,0,1,1,0,0,0,'',''),(19,'法师符文','法师符文','加入法师职业的必须物品。','',1,1,0,0,4,'','7',1,0,1,1,0,0,0,'',''),(20,'道士符文','道士符文','加入道士职业的必须物品。','',1,1,0,0,4,'','8',1,0,1,1,0,0,0,'',''),(21,'半月弯刀','半月弯刀','修炼半月弯刀的基础是“内力”，使用时会发出银色的月魄，与之前所学的武功不同，半月弯刀的攻击范围要广的多，可以同时攻击附近的数个敌人。','',1,2,0,0,4,'','10',0,1,1,0,0,0,0,'',''),(22,'基本剑术','基本剑术','基本剑术是武士的入门剑术。剑术招式是很久以前帝国的士兵们从战场上领悟到的。基本剑术学习起来非常容易，没有什么花哨的招式，属于剑术中的基本功。随着练习程度的深入，对增加攻击命中率很有帮助。','',1,2,0,0,4,'','11',0,1,1,0,0,0,0,'',''),(23,'攻杀剑术','攻杀剑术','攻杀剑术也属于入门剑术，其目标是修炼爆发力，修炼时要求精神高度集中。修炼攻杀剑术到了一定境界，就能在瞬间爆发出强大的攻击力，给敌人强烈打击。','',1,2,15000,0,4,'','12',0,1,1,0,0,0,0,'',''),(24,'刺杀剑术','刺杀剑术','刺杀剑术是必须有一定武功基础才能够修炼的中级剑术，刺杀剑术讲求“快”、“准”、“狠”，经过刻苦修炼后，可以从出其不意的部位出手攻击敌人，达到最高境界时，甚至可以杀死数丈之外的敌人。','',1,2,0,0,4,'','13',0,1,1,0,0,0,0,'',''),(25,'召唤骷髅','召唤骷髅','道术士们在长久的修炼中掌握了生死的奥秘，他们可以利用精神力，召唤并控制古代英雄的枯骨。随着被召唤次数的增加，骷髅与主人之间的信任度也会越高，当召唤术修炼到一定等级后，就会发挥出非常的威力。','',1,2,0,0,4,'','14',0,1,1,0,0,0,0,'',''),(26,'灵魂火符','灵魂火符','使用符咒是道术士的看家本领，他们将封印了怨灵的符纸飞向敌人，符纸将会在空气中燃烧，引爆怨灵的力量攻击敌人。可以用于远距离攻击，与其它魔法相结合使用，会得到多样效果。','',1,2,0,0,4,'','9',0,1,1,0,0,0,0,'',''),(27,'神圣战甲术','神圣战甲术','神圣战甲术是道家武功研究的延伸，通过将精神力贯注到自己或他人的战甲之上，在一段时间提升战甲的防御力。在实际战斗中，通常对冲在最前方的武士帮助最大。','',1,2,0,0,4,'','15',0,1,1,0,0,0,0,'',''),(28,'施毒术','施毒术','神圣战甲术是道家武功研究的延伸，通过将精神力贯注到自己或他人的战甲之上，在一段时间提升战甲的防御力。在实际战斗中，通常对冲在最前方的武士帮助最大。','',1,2,15000,0,4,'','16',0,1,1,0,0,0,0,'',''),(29,'大火球','大火球','是火系魔法的进阶，可以发出大得多的火球，火焰的高温甚至可以融化钢铁。','',1,2,0,0,4,'','17',0,1,1,0,0,0,0,'',''),(30,'雷电术','雷电术','是雷系魔法的进阶，念动咒语引发雷电，劈向目标，雷电术的威力巨大，所以念诵咒语的时间也比较长，要小心敌人趁机偷袭。','',1,2,15000,0,4,'','18',0,1,1,0,0,0,0,'',''),(31,'火墙','火墙','传说此 火墙是一位怀恨去世的天才法师所创，用咒语点燃的不灭之火就跟法师心中的怨恨一样熊熊燃起，会使身处火墙中的所有敌人融化。火墙持续燃烧的时间和法师的个人法力有关系。','',1,2,0,0,4,'','19',0,1,1,0,0,0,0,'',''),(32,'魔法盾','魔法盾','用魔力在自己周围形成保护膜，在一定的时间内减少伤害的技能。修炼等级越高，持续时间越长。','',1,2,0,0,4,'','20',0,1,1,0,0,0,0,'',''),(33,'乌木剑','乌木剑','','',2,0,10,0,1,'','',0,0,1,0,0,0,0,'','0'),(34,'布衣(男)','布衣(男)','','',2,0,10,0,1,'','',0,0,1,0,0,0,0,'','0'),(35,'布衣(女)','布衣(女)','','',2,0,10,0,1,'','',0,0,1,0,0,0,0,'','0'),(36,'短剑','短剑','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(37,'铁剑','铁剑','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(38,'青铜斧','青铜斧','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(39,'海魂','海魂','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(40,'八荒','八荒','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(41,'半月 ','半月 ','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(42,'偃月','偃月','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(43,'降魔','降魔','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(44,'斩马刀','斩马刀','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(45,'魔杖','魔杖','','',2,0,280,0,1,'','',0,0,1,0,0,0,0,'','0'),(46,'炼狱','炼狱','','',2,0,280,0,1,'','',0,0,1,0,0,0,0,'','0'),(47,'银蛇','银蛇','','',2,0,280,0,1,'','',0,0,1,0,0,0,0,'','0'),(48,'血饮','血饮','','',2,0,380,0,1,'','',0,0,1,0,0,0,0,'','0'),(49,'无极棍','无极棍','','',2,0,380,0,1,'','',0,0,1,0,0,0,0,'','0'),(50,'裁决','裁决','看起来象根木杖，但其实却是用精良的铸铁打造而成。据传，这种武器曾经被一位统治了大片疆域的王者使用。','',2,0,0,0,1,'','',0,0,1,0,0,0,0,'','0'),(51,'轻型盔甲','轻型盔甲','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(52,'中型盔甲','中型盔甲','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(53,'重盔甲','重盔甲','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(54,'魔法长袍','魔法长袍','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(55,'灵魂战衣 ','灵魂战衣 ','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(56,'战神盔甲','战神盔甲','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(57,'恶魔长袍','恶魔长袍','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(58,'幽灵战衣','幽灵战衣','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(59,'法神披风','法神披风','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(60,'霓裳羽衣','霓裳羽衣','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(61,'天魔神甲','天魔神甲','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(62,'圣战宝甲','圣战宝甲','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(63,'天尊道袍','天尊道袍','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(64,'天师长袍','天师长袍','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(65,'玻璃戒指','玻璃戒指','','',2,0,0,0,1,'','',0,0,1,0,0,0,0,'','0'),(66,'古铜戒指','古铜戒指','','',2,0,0,0,1,'','',0,0,1,0,0,0,0,'','0'),(67,'牛角戒指','牛角戒指','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(68,'蓝色水晶戒指','蓝色水晶戒指','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(69,'珍珠戒指 ','珍珠戒指 ','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(70,'降妖除魔戒指 ','降妖除魔戒指 ','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(71,'道德戒指','道德戒指','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(72,'珊瑚戒指','珊瑚戒指','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(73,'红宝石戒指','红宝石戒指','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(74,'骷髅戒指','骷髅戒指','','',2,0,0,0,1,'','',0,0,1,0,0,0,0,'','0'),(75,'龙之戒指','龙之戒指','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(76,'金项链','金项链','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(77,'黄色水晶项链','黄色水晶项链','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(78,'蓝翡翠项链','蓝翡翠项链','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(79,'竹笛','竹笛','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(80,'放大镜','放大镜','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(81,'幽溟项链','幽溟项链','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(82,'绿色项链','绿色项链','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(83,'灵魂项链 ','灵魂项链 ','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(84,'恶魔铃铛','恶魔铃铛','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(85,'铁手镯 ','铁手镯 ','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(86,'银手镯','银手镯','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(87,'魔法手镯 ','魔法手镯 ','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(88,'方士手镯 ','方士手镯 ','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(89,'黑檀手镯','黑檀手镯','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(90,'心灵手镯','心灵手镯','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(91,'思贝儿手镯','思贝儿手镯','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(92,'骑士手镯 ','骑士手镯 ','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(93,'龙之手镯','龙之手镯','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(94,'幽灵手套','幽灵手套','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(95,'阎罗手套','阎罗手套','','',2,0,0,0,1,'','',0,0,1,0,0,0,0,'','0'),(96,'青铜头盔','青铜头盔','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(97,'麻布腰带','麻布腰带','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(98,'布鞋','布鞋','','',2,0,100,0,1,'','',0,0,1,0,0,0,0,'','0'),(99,'牛皮靴','牛皮靴','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(100,'鹿皮靴','鹿皮靴','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(101,'紫绸鞋','紫绸鞋','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(102,'兽皮腰带','兽皮腰带','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(103,'铁腰带','铁腰带','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(104,'青铜腰带','青铜腰带','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(105,'魔法头盔','魔法头盔','','',2,0,150,0,1,'','',0,0,1,0,0,0,0,'','0'),(106,'道士头盔','道士头盔','','',2,0,200,0,1,'','',0,0,1,0,0,0,0,'','0'),(107,'斗笠','斗笠','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(108,'白玉宝石','<span class=\"color-green\">白玉宝石</span>','','',2,0,1000,0,1,'','',0,0,1,0,0,0,0,'','0'),(109,'金创药(中)','金创药(中)','恢复部分体力值，可以在商店购买。','',3,0,100,0,2,'','',0,1,1,0,0,0,0,'',''),(110,'传奇宝石','<span class=\"color-red\">传奇宝石</span>','','',2,0,500,0,1,'','',1,0,1,0,0,0,0,'','0'),(111,'传奇勋章','<span class=\"color-red\">传奇勋章</span>','','',2,0,500,0,1,'','',1,0,1,0,0,0,0,'','0'),(112,'升星石','<span class=\"color-red\">升星石</span>','来自天外的特殊矿石，蕴藏着巨大的神秘能量。','',1,3,5000,0,1,'','100',1,1,1,0,0,0,0,'','0'),(113,'铁矿','铁矿','','',1,3,20,0,1,'','250',1,1,1,0,0,0,0,'','0'),(114,'铜矿','铜矿','','',1,3,20,0,1,'','250',1,1,1,0,0,0,0,'','0'),(115,'银矿','银矿','','',1,3,20,0,1,'','250',1,1,1,0,0,0,0,'','0'),(116,'金矿','金矿','','',1,3,20,0,1,'','250',1,1,1,0,0,0,0,'','0'),(117,'鹤嘴锄','鹤嘴锄','特制的挖矿工具','',2,0,2000,0,1,'','',0,0,1,0,0,0,0,'','0'),(118,'熔铸石','熔铸石','','',1,3,5000,0,1,'','',1,1,1,0,0,0,0,'','0'),(119,'骷髅头盔','骷髅头盔','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(120,'明思头盔','明思头盔','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(121,'金手镯 ','金手镯 ','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(122,'夏普儿手镯','夏普儿手镯','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(123,'避邪手镯','避邪手镯','','',2,0,250,0,1,'','',0,0,1,0,0,0,0,'','0'),(124,'金创药(大)','金创药(大)','恢复大量体力值，可以在商店购买。','',3,0,350,0,2,'','',0,1,1,0,0,0,0,'',''),(125,'尸王殿传送卷','<span class=\"color-green\">尸王殿传送卷</span>','被强大魔力封印的邪恶卷轴，传说解开封印会打开通往死亡的黑暗通道。','',1,0,3000,0,4,'','0',1,1,1,0,0,0,0,'','15'),(126,'书页','<span class=\"color-green\">书页</span>','从上古遗存至今的古籍残页，模糊的字迹间散发着神秘的气息。','',1,0,1000,0,4,'','0',1,1,1,1,0,0,0,'','0'),(127,'暗殿宝石','<span class=\"color-red\">暗殿宝石</span>','','',2,0,10000,0,1,'','',0,0,1,0,0,0,0,'','0'),(128,'暗殿勋章','<span class=\"color-red\">暗殿勋章</span>','','',2,0,10000,0,1,'','',0,0,1,0,0,0,0,'','0'),(129,'烈火剑法','<span class=\"color-golden\">烈火剑法</span>','烈火剑法是使用内功的更高境界，将胸中的怒火注入手中的武器之中，唤起刀剑的杀气，可以造成惊人的破坏力。由于消耗过大，这种武功不能连续使用，另外修炼不足的情况下，聚气成功的可能性会降低。','',1,2,10000,0,4,'','22',0,1,1,0,0,0,0,'',''),(130,'冰咆哮','<span class=\"color-golden\">冰咆哮</span>','是冰系魔法的最高境界，可以呼唤上古冰之精灵，引发冰雪暴风攻击敌人。无数冰块形成的旋风再加上刺骨的寒气，会给敌人造成致命的打击。','',1,2,10000,0,4,'','23',0,1,1,0,0,0,0,'',''),(131,'召唤神兽','<span class=\"color-golden\">召唤神兽</span>','是道家召唤术的更高境界，道术士们可以与冥界沟通，召唤守护地狱之门的神兽作为自己的仆从。被召唤的神兽疾恶如仇，嘴里喷出的地狱之火可以烧毁一切。','',1,2,10000,0,4,'','24',0,1,1,0,0,0,0,'',''),(132,'黑铁矿','<span class=\"color-green\">黑铁矿</span>','','',1,3,100,0,1,'','150',1,1,1,0,0,0,0,'','0'),(133,'精铜矿','<span class=\"color-green\">精铜矿</span>','','',1,3,100,0,1,'','150',1,1,1,0,0,0,0,'','0'),(134,'秘银矿','<span class=\"color-green\">秘银矿</span>','','',1,3,100,0,1,'','150',1,1,1,0,0,0,0,'','0'),(135,'铂金矿','<span class=\"color-green\">铂金矿</span>','','',1,3,100,0,1,'','150',1,1,1,0,0,0,0,'','0'),(136,'幽灵项链','幽灵项链','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(137,'生命项链','生命项链','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(138,'铂金戒指','铂金戒指','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(139,'天珠项链','天珠项链','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(140,'井中月','井中月','','',2,0,380,0,1,'','',0,0,1,0,0,0,0,'','0'),(141,'三眼手镯','三眼手镯','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(142,'力量戒指','力量戒指','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(143,'紫碧螺','紫碧螺','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(144,'泰坦戒指','泰坦戒指','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(145,'沃玛传送卷','<span class=\"color-green\">沃玛传送卷</span>','被强大魔力封印的邪恶卷轴，传说解开封印会打开通往死亡的黑暗通道。','',1,0,6000,0,4,'','0',1,1,1,0,0,0,0,'','17'),(146,'火焰宝石','<span class=\"color-red\">火焰宝石</span>','','',2,0,20000,0,1,'','',0,0,1,0,0,0,0,'','0'),(147,'火焰勋章','<span class=\"color-red\">火焰勋章</span>','','',2,0,20000,0,1,'','',0,0,1,0,0,0,0,'','0'),(148,'避魂靴','避魂靴','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(149,'浮游靴','浮游靴','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(150,'沃玛头盔','沃玛头盔','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(151,'黑铁头盔','黑铁头盔','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(152,'浮游头盔','浮游头盔','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(153,'沃玛腰带','沃玛腰带','','',2,0,350,0,1,'','',0,0,1,0,0,0,0,'','0'),(154,'浮游腰带','浮游腰带','','',2,0,450,0,1,'','',0,0,1,0,0,0,0,'','0'),(155,'法神手镯','法神手镯','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(156,'法神戒指','法神戒指','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(157,'法神项链','法神项链','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(158,'圣战手镯','圣战手镯','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(159,'圣战戒指','圣战戒指','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(160,'圣战项链','圣战项链','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(161,'天尊手镯','天尊手镯','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(162,'天尊戒指','天尊戒指','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(163,'天尊项链','天尊项链','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(164,'法神头盔','法神头盔','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(165,'圣战头盔','圣战头盔','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0'),(166,'天尊头盔','天尊头盔','','',2,0,550,0,1,'','',0,0,1,0,0,0,0,'','0');
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loot`
--

DROP TABLE IF EXISTS `loot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '区域编号',
  `monster_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '怪物编号',
  `monster_name` varchar(30) NOT NULL DEFAULT '' COMMENT '怪物名称',
  `item_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '物品编号',
  `item_name` varchar(100) NOT NULL DEFAULT '' COMMENT '掉落物品名称',
  `min_amount` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '最小掉落数量',
  `max_amount` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '最大掉落数量',
  `chance` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '掉落机率',
  `range` int(10) unsigned NOT NULL DEFAULT 100 COMMENT '掉落范围',
  PRIMARY KEY (`id`),
  KEY `loot_monster_id_IDX` (`monster_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=457 DEFAULT CHARSET=utf8mb4 COMMENT='掉落表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loot`
--

LOCK TABLES `loot` WRITE;
/*!40000 ALTER TABLE `loot` DISABLE KEYS */;
INSERT INTO `loot` VALUES (1,0,106,'鸡',14,'鸡肉',1,2,1,5),(2,0,106,'鸡',16,'金创药(小)',1,1,1,5),(3,0,106,'鸡',17,'木剑',1,1,1,10),(4,0,106,'鸡',33,'乌木剑',1,1,1,10),(5,0,106,'鸡',34,'布衣(男)',1,1,1,10),(6,0,106,'鸡',35,'布衣(女)',1,1,1,10),(7,0,107,'猪',16,'金创药(小)',1,1,1,5),(8,0,107,'猪',17,'木剑',1,1,1,10),(9,0,107,'猪',33,'乌木剑',1,1,1,10),(10,0,107,'猪',34,'布衣(男)',1,1,1,10),(11,0,107,'猪',35,'布衣(女)',1,1,1,10),(12,0,113,'牛',36,'短剑',1,1,1,15),(13,0,113,'牛',37,'铁剑',1,1,1,15),(14,0,113,'牛',38,'青铜斧',1,1,1,15),(15,0,113,'牛',51,'轻型盔甲',1,1,1,15),(16,0,113,'牛',67,'牛角戒指',1,1,1,15),(17,0,113,'牛',76,'金项链',1,1,1,15),(18,0,113,'牛',85,'铁手镯',1,1,1,15),(19,0,113,'牛',16,'金创药(小)',1,1,1,5),(20,0,113,'牛',96,'青铜头盔',1,1,1,15),(21,0,109,'稻草人',36,'短剑',1,1,1,15),(22,0,109,'稻草人',37,'铁剑',1,1,1,15),(23,0,109,'稻草人',38,'青铜斧',1,1,1,15),(24,0,109,'稻草人',51,'轻型盔甲',1,1,1,15),(25,0,109,'稻草人',67,'牛角戒指',1,1,1,15),(26,0,109,'稻草人',76,'金项链',1,1,1,15),(27,0,109,'稻草人',85,'铁手镯',1,1,1,15),(28,0,109,'稻草人',16,'金创药(小)',1,1,1,5),(29,0,109,'稻草人',96,'青铜头盔',1,1,1,15),(30,0,110,'钉耙猫',36,'短剑',1,1,1,15),(31,0,110,'钉耙猫',37,'铁剑',1,1,1,15),(32,0,110,'钉耙猫',38,'青铜斧',1,1,1,15),(33,0,110,'钉耙猫',51,'轻型盔甲',1,1,1,15),(34,0,110,'钉耙猫',67,'牛角戒指',1,1,1,15),(35,0,110,'钉耙猫',76,'金项链',1,1,1,15),(36,0,110,'钉耙猫',85,'铁手镯',1,1,1,15),(37,0,110,'钉耙猫',16,'金创药(小)',1,1,1,5),(38,0,110,'钉耙猫',96,'青铜头盔',1,1,1,15),(39,0,111,'毒蜘蛛',36,'短剑',1,1,1,15),(40,0,111,'毒蜘蛛',37,'铁剑',1,1,1,15),(41,0,111,'毒蜘蛛',38,'青铜斧',1,1,1,15),(42,0,111,'毒蜘蛛',51,'轻型盔甲',1,1,1,15),(43,0,111,'毒蜘蛛',67,'牛角戒指',1,1,1,15),(44,0,111,'毒蜘蛛',76,'金项链',1,1,1,15),(45,0,111,'毒蜘蛛',85,'铁手镯',1,1,1,15),(46,0,111,'毒蜘蛛',16,'金创药(小)',1,1,1,5),(47,0,111,'毒蜘蛛',96,'青铜头盔',1,1,1,15),(48,0,112,'食人花',36,'短剑',1,1,1,15),(49,0,112,'食人花',37,'铁剑',1,1,1,15),(50,0,112,'食人花',38,'青铜斧',1,1,1,15),(51,0,112,'食人花',51,'轻型盔甲',1,1,1,15),(52,0,112,'食人花',67,'牛角戒指',1,1,1,15),(53,0,112,'食人花',76,'金项链',1,1,1,15),(54,0,112,'食人花',85,'铁手镯',1,1,1,15),(55,0,112,'食人花',16,'金创药(小)',1,1,1,5),(56,0,112,'食人花',96,'青铜头盔',1,1,1,15),(57,0,114,'狼',36,'短剑',1,1,1,15),(58,0,114,'狼',37,'铁剑',1,1,1,15),(59,0,114,'狼',38,'青铜斧',1,1,1,15),(60,0,114,'狼',51,'轻型盔甲',1,1,1,15),(61,0,114,'狼',67,'牛角戒指',1,1,1,15),(62,0,114,'狼',76,'金项链',1,1,1,15),(63,0,114,'狼',85,'铁手镯',1,1,1,15),(64,0,114,'狼',16,'金创药(小)',1,1,1,5),(65,0,114,'狼',96,'青铜头盔',1,1,1,15),(66,0,115,'多钩猫',36,'短剑',1,1,1,15),(67,0,115,'多钩猫',37,'铁剑',1,1,1,15),(68,0,115,'多钩猫',38,'青铜斧',1,1,1,15),(69,0,115,'多钩猫',51,'轻型盔甲',1,1,1,15),(70,0,115,'多钩猫',67,'牛角戒指',1,1,1,15),(71,0,115,'多钩猫',76,'金项链',1,1,1,15),(72,0,115,'多钩猫',85,'铁手镯',1,1,1,15),(73,0,115,'多钩猫',16,'金创药(小)',1,1,1,5),(74,0,115,'多钩猫',96,'青铜头盔',1,1,1,15),(75,0,108,'鹿',36,'短剑',1,1,1,15),(76,0,108,'鹿',37,'铁剑',1,1,1,15),(77,0,108,'鹿',38,'青铜斧',1,1,1,15),(78,0,108,'鹿',51,'轻型盔甲',1,1,1,15),(79,0,108,'鹿',67,'牛角戒指',1,1,1,15),(80,0,108,'鹿',76,'金项链',1,1,1,15),(81,0,108,'鹿',85,'铁手镯',1,1,1,15),(82,0,108,'鹿',16,'金创药(小)',1,1,1,5),(83,0,108,'鹿',96,'青铜头盔',1,1,1,15),(84,0,110,'钉耙猫',97,'麻布腰带',1,1,1,15),(85,0,111,'毒蜘蛛',97,'麻布腰带',1,1,1,15),(86,0,112,'食人花',97,'麻布腰带',1,1,1,15),(87,0,114,'狼',97,'麻布腰带',1,1,1,15),(88,0,115,'多钩猫',97,'麻布腰带',1,1,1,15),(89,0,110,'钉耙猫',98,'布鞋',1,1,1,15),(90,0,111,'毒蜘蛛',98,'布鞋',1,1,1,15),(91,0,112,'食人花',98,'布鞋',1,1,1,15),(92,0,114,'狼',98,'布鞋',1,1,1,15),(93,0,115,'多钩猫',98,'布鞋',1,1,1,15),(94,0,116,'山洞蝙蝠',39,' 海魂',1,1,1,30),(95,0,116,'山洞蝙蝠',40,'八荒',1,1,1,30),(96,0,116,'山洞蝙蝠',41,'半月 ',1,1,1,30),(97,0,116,'山洞蝙蝠',52,'中型盔甲 ',1,1,1,30),(98,0,116,'山洞蝙蝠',68,'蓝色水晶戒指',1,1,1,30),(99,0,116,'山洞蝙蝠',77,'黄色水晶项链',1,1,1,30),(100,0,116,'山洞蝙蝠',86,'银手镯',1,1,1,30),(101,0,116,'山洞蝙蝠',102,'兽皮腰带',1,1,1,30),(102,0,116,'山洞蝙蝠',99,'牛皮靴',1,1,1,30),(103,0,116,'山洞蝙蝠',105,'魔法头盔',1,1,1,30),(104,0,114,'狼',108,'白玉宝石',1,1,1,200),(105,0,116,'山洞蝙蝠',109,'金创药(中)',1,1,1,10),(106,0,117,'洞蛆',39,' 海魂',1,1,1,30),(107,0,117,'洞蛆',40,'八荒',1,1,1,30),(108,0,117,'洞蛆',41,'半月 ',1,1,1,30),(109,0,117,'洞蛆',52,'中型盔甲 ',1,1,1,30),(110,0,117,'洞蛆',68,'蓝色水晶戒指',1,1,1,30),(111,0,117,'洞蛆',77,'黄色水晶项链',1,1,1,30),(112,0,117,'洞蛆',86,'银手镯',1,1,1,30),(113,0,117,'洞蛆',102,'兽皮腰带',1,1,1,30),(114,0,117,'洞蛆',99,'牛皮靴',1,1,1,30),(115,0,117,'洞蛆',105,'魔法头盔',1,1,1,30),(116,0,117,'洞蛆',109,'金创药(中)',1,1,1,10),(117,0,118,'腐尸',39,' 海魂',1,1,1,30),(118,0,118,'腐尸',40,'八荒',1,1,1,30),(119,0,118,'腐尸',41,'半月 ',1,1,1,30),(120,0,118,'腐尸',52,'中型盔甲 ',1,1,1,30),(121,0,118,'腐尸',68,'蓝色水晶戒指',1,1,1,30),(122,0,118,'腐尸',77,'黄色水晶项链',1,1,1,30),(123,0,118,'腐尸',86,'银手镯',1,1,1,30),(124,0,118,'腐尸',102,'兽皮腰带',1,1,1,30),(125,0,118,'腐尸',99,'牛皮靴',1,1,1,30),(126,0,118,'腐尸',105,'魔法头盔',1,1,1,30),(127,0,118,'腐尸',109,'金创药(中)',1,1,1,10),(128,0,119,'僵尸',42,'偃月',1,1,1,100),(129,0,119,'僵尸',43,'降魔',1,1,1,100),(130,0,119,'僵尸',44,'斩马刀',1,1,1,100),(131,0,119,'僵尸',69,'珍珠戒指 ',1,1,1,100),(132,0,119,'僵尸',78,'蓝翡翠项链',1,1,1,100),(133,0,119,'僵尸',87,'魔法手镯 ',1,1,1,100),(134,0,119,'僵尸',88,'方士手镯 ',1,1,1,100),(135,0,119,'僵尸',89,'黑檀手镯',1,1,1,100),(136,0,119,'僵尸',100,'鹿皮靴',1,1,1,100),(137,0,119,'僵尸',103,'铁腰带',1,1,1,100),(138,0,119,'僵尸',106,'道士头盔',1,1,1,100),(139,0,119,'僵尸',109,'金创药(中)',1,1,1,10),(140,31,0,'',53,'重盔甲 ',1,1,1,400),(141,31,0,'',54,'魔法长袍',1,1,1,400),(142,31,0,'',55,'灵魂战衣 ',1,1,1,400),(143,0,120,'僧侣僵尸',42,'偃月',1,1,1,90),(144,0,120,'僧侣僵尸',43,'降魔',1,1,1,90),(145,0,120,'僧侣僵尸',44,'斩马刀',1,1,1,90),(146,0,120,'僧侣僵尸',69,'珍珠戒指 ',1,1,1,90),(147,0,120,'僧侣僵尸',78,'蓝翡翠项链',1,1,1,90),(148,0,120,'僧侣僵尸',87,'魔法手镯 ',1,1,1,90),(149,0,120,'僧侣僵尸',88,'方士手镯 ',1,1,1,90),(150,0,120,'僧侣僵尸',89,'黑檀手镯',1,1,1,90),(151,0,120,'僧侣僵尸',100,'鹿皮靴',1,1,1,90),(152,0,120,'僧侣僵尸',103,'铁腰带',1,1,1,90),(153,0,120,'僧侣僵尸',106,'道士头盔',1,1,1,90),(154,0,120,'僧侣僵尸',109,'金创药(中)',1,1,1,10),(158,0,121,'雷电僵尸',42,'偃月',1,1,1,80),(159,0,121,'雷电僵尸',43,'降魔',1,1,1,80),(160,0,121,'雷电僵尸',44,'斩马刀',1,1,1,80),(161,0,121,'雷电僵尸',69,'珍珠戒指 ',1,1,1,80),(162,0,121,'雷电僵尸',78,'蓝翡翠项链',1,1,1,80),(163,0,121,'雷电僵尸',87,'魔法手镯 ',1,1,1,80),(164,0,121,'雷电僵尸',88,'方士手镯 ',1,1,1,80),(165,0,121,'雷电僵尸',89,'黑檀手镯',1,1,1,80),(166,0,121,'雷电僵尸',100,'鹿皮靴',1,1,1,80),(167,0,121,'雷电僵尸',103,'铁腰带',1,1,1,80),(168,0,121,'雷电僵尸',106,'道士头盔',1,1,1,80),(169,0,121,'雷电僵尸',109,'金创药(中)',1,1,1,10),(173,0,115,'多钩猫',108,'白玉宝石',1,1,1,200),(174,0,111,'毒蜘蛛',108,'白玉宝石',1,1,1,200),(175,0,121,'雷电僵尸',21,'半月弯刀',1,1,1,200),(176,0,121,'雷电僵尸',31,'火墙',1,1,1,200),(177,0,121,'雷电僵尸',25,'召唤骷髅',1,1,1,200),(178,0,120,'僧侣僵尸',21,'半月弯刀',1,1,1,250),(179,0,120,'僧侣僵尸',31,'火墙',1,1,1,250),(180,0,120,'僧侣僵尸',25,'召唤骷髅',1,1,1,250),(181,0,126,'跳跳蜂',45,'魔杖',1,1,1,250),(182,0,126,'跳跳蜂',46,'炼狱',1,1,1,250),(183,0,126,'跳跳蜂',47,'银蛇',1,1,1,250),(187,0,126,'跳跳蜂',70,'降妖除魔戒指 ',1,1,1,250),(188,0,126,'跳跳蜂',71,'道德戒指',1,1,1,250),(189,0,126,'跳跳蜂',72,'珊瑚戒指',1,1,1,250),(190,0,126,'跳跳蜂',79,'竹笛',1,1,1,250),(191,0,126,'跳跳蜂',80,'放大镜',1,1,1,250),(192,0,126,'跳跳蜂',81,'幽溟项链',1,1,1,250),(193,0,126,'跳跳蜂',101,'紫绸鞋',1,1,1,250),(194,0,126,'跳跳蜂',104,'青铜腰带',1,1,1,250),(195,0,126,'跳跳蜂',107,'斗笠',1,1,1,250),(196,0,126,'跳跳蜂',119,'骷髅头盔',1,1,1,250),(197,0,126,'跳跳蜂',120,'明思头盔',1,1,1,250),(198,0,126,'跳跳蜂',121,'金手镯 ',1,1,1,250),(199,0,126,'跳跳蜂',122,'夏普儿手镯',1,1,1,250),(200,0,126,'跳跳蜂',123,'避邪手镯',1,1,1,250),(201,0,126,'跳跳蜂',124,'金创药(大)',1,1,1,10),(202,0,123,'蜈蚣',45,'魔杖',1,1,1,220),(203,0,123,'蜈蚣',46,'炼狱',1,1,1,220),(204,0,123,'蜈蚣',47,'银蛇',1,1,1,220),(208,0,123,'蜈蚣',70,'降妖除魔戒指 ',1,1,1,220),(209,0,123,'蜈蚣',71,'道德戒指',1,1,1,220),(210,0,123,'蜈蚣',72,'珊瑚戒指',1,1,1,220),(211,0,123,'蜈蚣',79,'竹笛',1,1,1,220),(212,0,123,'蜈蚣',80,'放大镜',1,1,1,220),(213,0,123,'蜈蚣',81,'幽溟项链',1,1,1,220),(214,0,123,'蜈蚣',101,'紫绸鞋',1,1,1,220),(215,0,123,'蜈蚣',104,'青铜腰带',1,1,1,220),(216,0,123,'蜈蚣',107,'斗笠',1,1,1,220),(217,0,123,'蜈蚣',119,'骷髅头盔',1,1,1,220),(218,0,123,'蜈蚣',120,'明思头盔',1,1,1,220),(219,0,123,'蜈蚣',121,'金手镯 ',1,1,1,220),(220,0,123,'蜈蚣',122,'夏普儿手镯',1,1,1,220),(221,0,123,'蜈蚣',123,'避邪手镯',1,1,1,220),(222,0,123,'蜈蚣',124,'金创药(大)',1,1,1,10),(223,0,124,'洞穴蜈蚣',45,'魔杖',1,1,1,210),(224,0,124,'洞穴蜈蚣',46,'炼狱',1,1,1,210),(225,0,124,'洞穴蜈蚣',47,'银蛇',1,1,1,210),(229,0,124,'洞穴蜈蚣',70,'降妖除魔戒指 ',1,1,1,210),(230,0,124,'洞穴蜈蚣',71,'道德戒指',1,1,1,210),(231,0,124,'洞穴蜈蚣',72,'珊瑚戒指',1,1,1,210),(232,0,124,'洞穴蜈蚣',79,'竹笛',1,1,1,210),(233,0,124,'洞穴蜈蚣',80,'放大镜',1,1,1,210),(234,0,124,'洞穴蜈蚣',81,'幽溟项链',1,1,1,210),(235,0,124,'洞穴蜈蚣',101,'紫绸鞋',1,1,1,210),(236,0,124,'洞穴蜈蚣',104,'青铜腰带',1,1,1,210),(237,0,124,'洞穴蜈蚣',107,'斗笠',1,1,1,210),(238,0,124,'洞穴蜈蚣',119,'骷髅头盔',1,1,1,210),(239,0,124,'洞穴蜈蚣',120,'明思头盔',1,1,1,210),(240,0,124,'洞穴蜈蚣',121,'金手镯 ',1,1,1,210),(241,0,124,'洞穴蜈蚣',122,'夏普儿手镯',1,1,1,210),(242,0,124,'洞穴蜈蚣',123,'避邪手镯',1,1,1,210),(243,0,124,'洞穴蜈蚣',124,'金创药(大)',1,1,1,10),(244,0,125,'巨型蠕虫',45,'魔杖',1,1,1,200),(245,0,125,'巨型蠕虫',46,'炼狱',1,1,1,200),(246,0,125,'巨型蠕虫',47,'银蛇',1,1,1,200),(250,0,125,'巨型蠕虫',70,'降妖除魔戒指 ',1,1,1,200),(251,0,125,'巨型蠕虫',71,'道德戒指',1,1,1,200),(252,0,125,'巨型蠕虫',72,'珊瑚戒指',1,1,1,200),(253,0,125,'巨型蠕虫',79,'竹笛',1,1,1,200),(254,0,125,'巨型蠕虫',80,'放大镜',1,1,1,200),(255,0,125,'巨型蠕虫',81,'幽溟项链',1,1,1,200),(256,0,125,'巨型蠕虫',101,'紫绸鞋',1,1,1,200),(257,0,125,'巨型蠕虫',104,'青铜腰带',1,1,1,200),(258,0,125,'巨型蠕虫',107,'斗笠',1,1,1,200),(259,0,125,'巨型蠕虫',119,'骷髅头盔',1,1,1,200),(260,0,125,'巨型蠕虫',120,'明思头盔',1,1,1,200),(261,0,125,'巨型蠕虫',121,'金手镯 ',1,1,1,200),(262,0,125,'巨型蠕虫',122,'夏普儿手镯',1,1,1,200),(263,0,125,'巨型蠕虫',123,'避邪手镯',1,1,1,200),(264,0,125,'巨型蠕虫',124,'金创药(大)',1,1,1,10),(265,0,127,'黑色恶蛆',45,'魔杖',1,1,1,150),(266,0,127,'黑色恶蛆',46,'炼狱',1,1,1,150),(267,0,127,'黑色恶蛆',47,'银蛇',1,1,1,150),(270,0,127,'黑色恶蛆',45,'魔杖',1,1,1,190),(271,0,127,'黑色恶蛆',46,'炼狱',1,1,1,190),(273,0,127,'黑色恶蛆',70,'降妖除魔戒指 ',1,1,1,150),(274,0,127,'黑色恶蛆',47,'银蛇',1,1,1,190),(275,0,127,'黑色恶蛆',71,'道德戒指',1,1,1,150),(278,0,127,'黑色恶蛆',72,'珊瑚戒指',1,1,1,150),(280,0,127,'黑色恶蛆',79,'竹笛',1,1,1,150),(281,0,127,'黑色恶蛆',70,'降妖除魔戒指 ',1,1,1,190),(282,0,127,'黑色恶蛆',80,'放大镜',1,1,1,150),(283,0,127,'黑色恶蛆',81,'幽溟项链',1,1,1,150),(284,0,127,'黑色恶蛆',71,'道德戒指',1,1,1,190),(285,0,127,'黑色恶蛆',72,'珊瑚戒指',1,1,1,190),(286,0,127,'黑色恶蛆',101,'紫绸鞋',1,1,1,150),(287,0,127,'黑色恶蛆',104,'青铜腰带',1,1,1,150),(288,0,127,'黑色恶蛆',79,'竹笛',1,1,1,190),(289,0,127,'黑色恶蛆',107,'斗笠',1,1,1,150),(290,0,127,'黑色恶蛆',80,'放大镜',1,1,1,190),(291,0,127,'黑色恶蛆',119,'骷髅头盔',1,1,1,150),(292,0,127,'黑色恶蛆',81,'幽溟项链',1,1,1,190),(293,0,127,'黑色恶蛆',101,'紫绸鞋',1,1,1,190),(294,0,127,'黑色恶蛆',120,'明思头盔',1,1,1,150),(295,0,127,'黑色恶蛆',104,'青铜腰带',1,1,1,190),(296,0,127,'黑色恶蛆',107,'斗笠',1,1,1,190),(297,0,127,'黑色恶蛆',121,'金手镯 ',1,1,1,150),(298,0,127,'黑色恶蛆',122,'夏普儿手镯',1,1,1,150),(299,0,127,'黑色恶蛆',119,'骷髅头盔',1,1,1,190),(300,0,127,'黑色恶蛆',120,'明思头盔',1,1,1,190),(301,0,127,'黑色恶蛆',123,'避邪手镯',1,1,1,150),(302,0,127,'黑色恶蛆',124,'金创药(大)',1,1,1,150),(303,0,127,'黑色恶蛆',121,'金手镯 ',1,1,1,190),(304,0,127,'黑色恶蛆',122,'夏普儿手镯',1,1,1,190),(305,0,127,'黑色恶蛆',123,'避邪手镯',1,1,1,190),(306,0,127,'黑色恶蛆',124,'金创药(大)',1,1,1,10),(307,0,128,'钳虫',45,'魔杖',1,1,1,180),(308,0,128,'钳虫',46,'炼狱',1,1,1,180),(309,0,128,'钳虫',47,'银蛇',1,1,1,180),(313,0,128,'钳虫',70,'降妖除魔戒指 ',1,1,1,180),(314,0,128,'钳虫',71,'道德戒指',1,1,1,180),(315,0,128,'钳虫',72,'珊瑚戒指',1,1,1,180),(316,0,128,'钳虫',79,'竹笛',1,1,1,180),(317,0,128,'钳虫',80,'放大镜',1,1,1,180),(318,0,128,'钳虫',81,'幽溟项链',1,1,1,180),(319,0,128,'钳虫',101,'紫绸鞋',1,1,1,180),(320,0,128,'钳虫',104,'青铜腰带',1,1,1,180),(321,0,128,'钳虫',107,'斗笠',1,1,1,180),(322,0,128,'钳虫',119,'骷髅头盔',1,1,1,180),(323,0,128,'钳虫',120,'明思头盔',1,1,1,180),(324,0,128,'钳虫',121,'金手镯 ',1,1,1,180),(325,0,128,'钳虫',122,'夏普儿手镯',1,1,1,180),(326,0,128,'钳虫',123,'避邪手镯',1,1,1,180),(327,0,128,'钳虫',124,'金创药(大)',1,1,1,10),(328,0,121,'雷电僵尸',125,'尸王殿传送卷',1,1,1,300),(329,0,122,'尸王',126,'书页',1,1,1,40),(330,0,122,'尸王',127,'暗殿宝石',1,1,1,100),(331,0,122,'尸王',128,'暗殿勋章',1,1,1,100),(332,0,122,'尸王',124,'金创药(大)',1,2,1,1),(333,0,122,'尸王',129,'烈火剑法',1,1,1,500),(334,0,122,'尸王',130,'冰咆哮',1,1,1,500),(335,0,122,'尸王',131,'召唤神兽',1,1,1,500),(336,0,122,'尸王',21,'半月弯刀',1,1,1,60),(337,0,122,'尸王',31,'火墙',1,1,1,60),(338,0,122,'尸王',25,'召唤骷髅',1,1,1,60),(339,0,122,'尸王',45,'魔杖',1,1,1,40),(340,0,122,'尸王',46,'炼狱',1,1,1,40),(341,0,122,'尸王',47,'银蛇',1,1,1,40),(342,0,122,'尸王',56,'战神盔甲',1,1,1,400),(343,0,122,'尸王',57,'恶魔长袍',1,1,1,400),(344,0,122,'尸王',58,'幽灵战衣',1,1,1,400),(345,33,0,'',124,'金创药(大)',1,1,1,10),(346,33,0,'',109,'金创药(中)',1,1,1,10),(347,33,0,'',73,'红宝石戒指',1,1,1,500),(348,33,0,'',75,'龙之戒指',1,1,1,500),(349,33,0,'',90,'心灵手镯',1,1,1,500),(350,33,0,'',91,'思贝儿手镯',1,1,1,500),(351,33,0,'',94,'幽灵手套',1,1,1,500),(352,33,0,'',136,'幽灵项链',1,1,1,500),(353,33,0,'',137,'生命项链',1,1,1,500),(354,33,0,'',138,'铂金戒指',1,1,1,500),(355,33,0,'',139,'天珠项链',1,1,1,500),(356,33,0,'',45,'魔杖',1,1,1,140),(357,33,0,'',46,'炼狱',1,1,1,140),(358,33,0,'',47,'银蛇',1,1,1,140),(359,33,0,'',56,'战神盔甲',1,1,1,600),(360,33,0,'',57,'恶魔长袍',1,1,1,600),(361,33,0,'',58,'幽灵战衣',1,1,1,600),(362,0,122,'尸王',24,'刺杀剑术',1,1,1,300),(363,0,122,'尸王',27,'神圣战甲术',1,1,1,300),(364,0,122,'尸王',32,'魔法盾',1,1,1,300),(365,34,0,'',48,'血饮',1,1,1,800),(366,34,0,'',49,'无极棍',1,1,1,800),(367,34,0,'',140,'井中月',1,1,1,800),(368,34,0,'',124,'金创药(大)',1,1,1,10),(369,34,0,'',109,'金创药(中)',1,1,1,10),(370,34,0,'',82,'绿色项链',1,1,1,1000),(371,34,0,'',83,'灵魂项链 ',1,1,1,1000),(372,34,0,'',84,'恶魔铃铛',1,1,1,1000),(373,34,0,'',92,'骑士手镯 ',1,1,1,1000),(374,34,0,'',93,'龙之手镯',1,1,1,1000),(375,34,0,'',141,'三眼手镯',1,1,1,1000),(376,34,0,'',142,'力量戒指',1,1,1,1000),(377,34,0,'',143,'紫碧螺',1,1,1,1000),(378,34,0,'',144,'泰坦戒指',1,1,1,1000),(379,34,0,'',56,'战神盔甲',1,1,1,500),(380,34,0,'',57,'恶魔长袍',1,1,1,500),(381,34,0,'',58,'幽灵战衣',1,1,1,500),(382,36,0,'',124,'金创药(大)',1,1,1,10),(383,36,0,'',109,'金创药(中)',1,1,1,10),(384,36,0,'',73,'红宝石戒指',1,1,1,500),(385,36,0,'',75,'龙之戒指',1,1,1,500),(386,36,0,'',90,'心灵手镯',1,1,1,500),(387,36,0,'',91,'思贝儿手镯',1,1,1,500),(388,36,0,'',94,'幽灵手套',1,1,1,500),(389,36,0,'',136,'幽灵项链',1,1,1,500),(390,36,0,'',137,'生命项链',1,1,1,500),(391,36,0,'',138,'铂金戒指',1,1,1,500),(392,36,0,'',139,'天珠项链',1,1,1,500),(393,36,0,'',45,'魔杖',1,1,1,140),(394,36,0,'',46,'炼狱',1,1,1,140),(395,36,0,'',47,'银蛇',1,1,1,140),(396,36,0,'',56,'战神盔甲',1,1,1,600),(397,36,0,'',57,'恶魔长袍',1,1,1,600),(398,36,0,'',58,'幽灵战衣',1,1,1,600),(399,36,0,'',145,'沃玛传送卷',1,1,1,1200),(400,37,0,'',124,'金创药(大)',1,1,1,10),(401,37,0,'',109,'金创药(中)',1,1,1,10),(402,37,0,'',73,'红宝石戒指',1,1,1,500),(403,37,0,'',75,'龙之戒指',1,1,1,500),(404,37,0,'',90,'心灵手镯',1,1,1,500),(405,37,0,'',91,'思贝儿手镯',1,1,1,500),(406,37,0,'',94,'幽灵手套',1,1,1,500),(407,37,0,'',136,'幽灵项链',1,1,1,500),(408,37,0,'',137,'生命项链',1,1,1,500),(409,37,0,'',138,'铂金戒指',1,1,1,500),(410,37,0,'',139,'天珠项链',1,1,1,500),(411,37,0,'',45,'魔杖',1,1,1,140),(412,37,0,'',46,'炼狱',1,1,1,140),(413,37,0,'',47,'银蛇',1,1,1,140),(414,37,0,'',56,'战神盔甲',1,1,1,600),(415,37,0,'',57,'恶魔长袍',1,1,1,600),(416,37,0,'',58,'幽灵战衣',1,1,1,600),(417,37,0,'',145,'沃玛传送卷',1,1,1,1200),(418,0,149,'沃玛教主',146,'火焰宝石',1,1,1,800),(420,0,149,'沃玛教主',147,'火焰勋章',1,1,1,800),(421,0,149,'沃玛教主',148,'避魂靴',1,1,1,500),(422,37,0,'',150,'沃玛头盔',1,1,1,500),(423,0,149,'沃玛教主',153,'沃玛腰带',1,1,1,500),(425,38,0,'',48,'血饮',1,1,1,800),(426,38,0,'',49,'无极棍',1,1,1,800),(427,38,0,'',140,'井中月',1,1,1,800),(428,38,0,'',124,'金创药(大)',1,1,1,10),(429,38,0,'',109,'金创药(中)',1,1,1,10),(430,38,0,'',82,'绿色项链',1,1,1,1000),(431,38,0,'',83,'灵魂项链 ',1,1,1,1000),(432,38,0,'',84,'恶魔铃铛',1,1,1,1000),(433,38,0,'',92,'骑士手镯 ',1,1,1,1000),(434,38,0,'',93,'龙之手镯',1,1,1,1000),(435,38,0,'',141,'三眼手镯',1,1,1,1000),(436,38,0,'',142,'力量戒指',1,1,1,1000),(437,38,0,'',143,'紫碧螺',1,1,1,1000),(438,38,0,'',144,'泰坦戒指',1,1,1,1000),(439,38,0,'',56,'战神盔甲',1,1,1,500),(440,38,0,'',57,'恶魔长袍',1,1,1,500),(441,38,0,'',58,'幽灵战衣',1,1,1,500),(442,38,0,'',152,'浮游头盔',1,1,1,1000),(443,35,0,'',124,'金创药(大)',1,1,1,10),(444,35,0,'',109,'金创药(中)',1,1,1,10),(445,35,0,'',155,'法神手镯',1,1,1,2000),(446,35,0,'',156,'法神戒指',1,1,1,2000),(447,35,0,'',157,'法神项链',1,1,1,2000),(448,35,0,'',164,'法神头盔',1,1,1,2000),(449,35,0,'',158,'圣战手镯',1,1,1,2000),(450,35,0,'',159,'圣战戒指',1,1,1,2000),(451,35,0,'',160,'圣战项链',1,1,1,2000),(452,35,0,'',165,'圣战头盔',1,1,1,2000),(453,35,0,'',161,'天尊手镯',1,1,1,2000),(454,35,0,'',162,'天尊戒指',1,1,1,2000),(455,35,0,'',163,'天尊项链',1,1,1,2000),(456,35,0,'',166,'天尊头盔',1,1,1,2000);
/*!40000 ALTER TABLE `loot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manual`
--

DROP TABLE IF EXISTS `manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '功法编号',
  `name` varchar(30) NOT NULL COMMENT '功法名称',
  `info` varchar(100) NOT NULL DEFAULT '' COMMENT '功法说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='功法表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manual`
--

LOCK TABLES `manual` WRITE;
/*!40000 ALTER TABLE `manual` DISABLE KEYS */;
INSERT INTO `manual` VALUES (1,'大荒剑经','一本破损的剑谱，来源十分神秘，传闻其来自远古大荒的隐秘剑宗。'),(2,'葵花炼神大法','太一门镇派五行真诀。'),(3,'平民','平平无奇'),(4,'修仙怪物通用功法','修仙怪物通用功法'),(5,'普通怪物通用功法','普通怪物通用功法'),(6,'战士','战士职业'),(7,'法师','法师职业'),(8,'道士','道士职业');
/*!40000 ALTER TABLE `manual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manual_level`
--

DROP TABLE IF EXISTS `manual_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manual_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `manual_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法编号',
  `sequence` tinyint(4) NOT NULL DEFAULT 0 COMMENT '境界等级序号，一个大境界可能有多个功法等级',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '境界名称',
  `sub_name` varchar(20) NOT NULL DEFAULT '' COMMENT '小境界名称',
  `level` int(11) NOT NULL DEFAULT 0 COMMENT '境界等级',
  `is_min_exp` tinyint(4) NOT NULL DEFAULT 0 COMMENT '达成条件，超过等级即为达成',
  `is_max_exp` tinyint(4) NOT NULL DEFAULT 0 COMMENT '达成条件，需要达到等级最大经验',
  `layer` tinyint(4) NOT NULL COMMENT '五大境界划分，1炼气2筑基3金丹4元神5真仙',
  `item_condition` varchar(50) NOT NULL DEFAULT '' COMMENT '突破瓶颈的物品需求',
  `battle_condition` varchar(50) NOT NULL DEFAULT '' COMMENT '突破瓶颈的战斗需求',
  `is_max` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是功法最高等级',
  `rate` int(11) NOT NULL DEFAULT 0 COMMENT '突破成功率',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manual_level`
--

LOCK TABLES `manual_level` WRITE;
/*!40000 ALTER TABLE `manual_level` DISABLE KEYS */;
INSERT INTO `manual_level` VALUES (1,2,1,'少阴之体','',1,1,0,1,'','',0,0),(2,2,1,'少阴之体','交感',19,0,1,1,'','',0,95),(3,2,1,'少阴之体','入体',29,0,1,1,'','',0,90),(4,2,2,'中阴成丹','通衢',59,0,1,2,'','',0,0),(5,2,2,'中阴成丹','百汇',39,0,1,2,'','',0,0),(6,2,2,'中阴成丹','成丹',49,0,1,2,'','',0,0),(7,2,3,'太阴元神','忘我',69,0,1,3,'','',0,0),(8,2,3,'太阴元神','从真',79,0,1,3,'','',0,0),(9,2,3,'太阴元神','返虚',89,0,1,3,'','',0,0),(10,2,4,'破碎虚空','具足',99,0,1,4,'','',0,0),(11,2,4,'破碎虚空','化神',109,0,1,4,'','',0,0),(12,2,4,'破碎虚空','合道',119,0,1,4,'','',0,0),(13,2,5,'不灭真仙','',120,1,0,5,'','',1,0),(14,2,1,'少阴之体','婴宁',9,0,1,1,'','',0,100),(15,2,2,'中阴成丹','',30,1,0,2,'','',0,0),(16,2,3,'太阴元神','',60,1,0,3,'','',0,0),(17,2,4,'破碎虚空','',90,1,0,4,'','',0,0),(18,3,1,'武林宗师','',1,1,0,0,'','',1,0),(19,1,1,'剑心','',1,1,0,1,'','',0,0),(20,4,1,'炼气','',1,1,0,1,'','',1,0),(21,4,2,'筑基','',30,1,0,2,'','',1,0),(22,4,3,'金丹','',60,1,0,3,'','',1,0),(23,4,4,'元神','',90,1,0,4,'','',1,0),(24,4,5,'真妖','',120,1,0,5,'','',1,0),(25,5,1,'普通','',1,1,0,0,'','',1,0),(26,6,1,'战士','',1,1,0,0,'','',1,0),(27,7,1,'法师','',1,1,0,0,'','',1,0),(28,8,1,'道士','',1,1,0,0,'','',1,0);
/*!40000 ALTER TABLE `manual_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manual_level_bonus`
--

DROP TABLE IF EXISTS `manual_level_bonus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manual_level_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `manual_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法编号',
  `manual_level_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法等级编号',
  `column` varchar(100) NOT NULL DEFAULT '' COMMENT '影响字段',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT '影响数量',
  `is_column` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是人物属性',
  `type` tinyint(4) DEFAULT NULL COMMENT '奖励类型，1属性2技能3配方4技能5脚本',
  `bonus_id` int(11) NOT NULL DEFAULT 0 COMMENT '奖励物品编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='功法奖励';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manual_level_bonus`
--

LOCK TABLES `manual_level_bonus` WRITE;
/*!40000 ALTER TABLE `manual_level_bonus` DISABLE KEYS */;
INSERT INTO `manual_level_bonus` VALUES (1,2,1,'',0,0,2,4);
/*!40000 ALTER TABLE `manual_level_bonus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `market_item`
--

DROP TABLE IF EXISTS `market_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '物品名称',
  `uid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '物品所有者编号',
  `player_item_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '物品编号，player_item.id',
  `item_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '物品编号',
  `price` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '单价',
  `amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '数量',
  `item_type` tinyint(3) unsigned DEFAULT 2 COMMENT ' 物品分类',
  `quality` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '品质',
  `currency` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '货币种类，1游戏2充值币',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT ' 创建时间',
  `ended_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '寄售结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='坊市';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `market_item`
--

LOCK TABLES `market_item` WRITE;
/*!40000 ALTER TABLE `market_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `market_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicine_effects`
--

DROP TABLE IF EXISTS `medicine_effects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medicine_effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `item_id` int(11) NOT NULL DEFAULT 0 COMMENT '物品表编号',
  `desc` varchar(100) NOT NULL DEFAULT '' COMMENT '效果描述',
  `info` varchar(30) NOT NULL DEFAULT '' COMMENT '效果名称',
  `ui_info` varchar(100) NOT NULL DEFAULT '' COMMENT '带样式的显示效果',
  `column` varchar(30) NOT NULL DEFAULT '' COMMENT '修正的字段',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT '修正数值',
  `effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '修正类型，1乘法，2加法',
  `target` tinyint(4) NOT NULL DEFAULT 1 COMMENT '技能目标，0攻击参数，1敌方单位，2己方单位',
  `turns` tinyint(4) NOT NULL DEFAULT 1 COMMENT '持续回合，默认1',
  `effect_turn` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否当前回合生效，1当前回合生效，2 下回合',
  `is_column` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否是属性修正参数',
  `is_wushang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理伤害修正参数',
  `is_wumian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理免疫修正参数',
  `is_fashang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法术伤害修正参数',
  `is_famian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法伤免疫修正参数',
  `is_mingzhong` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是命中率影响参数',
  `is_shanbi` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是闪避率修正参数',
  `is_baoji` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是暴击率修正参数',
  `is_shenming` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是抗暴率修正参数',
  `is_dot` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是独立计算的DOT效果',
  `is_temporary` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否限时',
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT '持续时间，秒',
  `is_combat` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否可以在战斗中生效',
  `is_raw` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否直接作用于源字段，比如直接增加HP',
  `is_custom` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是其他用途的效果',
  `custom_effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '自定义效果的影响方式，1增加效果2移除效果',
  `identity` varchar(20) NOT NULL DEFAULT '' COMMENT '效果标识，用于重复判断',
  `is_unique` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否唯一效果',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='药品效果';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicine_effects`
--

LOCK TABLES `medicine_effects` WRITE;
/*!40000 ALTER TABLE `medicine_effects` DISABLE KEYS */;
INSERT INTO `medicine_effects` VALUES (1,3,'恢复气血值 %d','恢复气血值 %d','还元丹','hp',100,2,2,1,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,'',0),(2,3,'气血上限提升%d%%','最大血量上升10%','还元丹','max_hp',10,1,2,3,1,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,'max_hp_item_mul_inc',1),(3,16,'恢复气血值 %d','恢复气血值 %d','金创药(小)','hp',100,2,2,1,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,'',0),(4,109,'恢复气血值 %d','恢复气血值 %d','金创药(中)','hp',300,2,2,1,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,'',0),(5,124,'恢复气血值 %d','恢复气血值 %d','金创药(大)','hp',800,2,2,1,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,'',0);
/*!40000 ALTER TABLE `medicine_effects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mid`
--

DROP TABLE IF EXISTS `mid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mid` (
  `mname` varchar(30) NOT NULL,
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mgid` text DEFAULT NULL,
  `mnpc` text DEFAULT NULL,
  `mgtime` datetime DEFAULT NULL,
  `ms` int(11) DEFAULT 0,
  `midinfo` text NOT NULL,
  `midboss` int(11) NOT NULL,
  `mup` int(11) NOT NULL,
  `mdown` int(11) NOT NULL,
  `mleft` int(11) NOT NULL,
  `mright` int(11) NOT NULL,
  `mqy` int(11) NOT NULL,
  `playerinfo` varchar(255) NOT NULL,
  `ispvp` int(255) NOT NULL,
  `resources` varchar(255) NOT NULL DEFAULT '' COMMENT '地图资源，形式为type|id|chance，1为采集，2为遭遇怪物',
  `notes` varchar(100) NOT NULL DEFAULT '' COMMENT '备注，区分名称相同的地图',
  `flags` int(11) NOT NULL DEFAULT 0 COMMENT '地图属性，以bit 为单位，0x01为是否为副本',
  `lingqi` int(11) NOT NULL DEFAULT 15 COMMENT '场景的灵气值，满分100',
  `enter_condition` int(11) NOT NULL DEFAULT 0 COMMENT '进入条件',
  `ornaments` varchar(100) NOT NULL DEFAULT '' COMMENT '物件列表',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=567 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mid`
--

LOCK TABLES `mid` WRITE;
/*!40000 ALTER TABLE `mid` DISABLE KEYS */;
INSERT INTO `mid` VALUES ('银杏村口',307,NULL,'33,27',NULL,0,'这里是银杏村村口，几个草垛随意的堆放在路边，三两个村民慵懒的躺在那里。',0,308,310,311,0,27,'绾绾向村北小路走去',0,'','',0,15,0,''),('村北小路',308,NULL,NULL,NULL,0,'这是一条穿过银杏村口松树林的小路。',0,330,307,0,0,27,'绾绾向银杏山谷野外走去',0,'','',0,15,0,''),('杏林',309,NULL,NULL,NULL,0,'这是一片溪边的杏树林，一群孩童在此玩耍。',0,0,0,0,0,27,'巫山向银杏村口走去',0,'','',0,15,0,''),('广场小径',310,NULL,NULL,NULL,0,'南边是村广场，几个泼皮在街上游荡。',0,307,314,0,319,27,'东山宁宁向银杏村口走去',0,'','',0,15,0,''),('书店',311,NULL,'35',NULL,0,'村口一个简易书店，放着几张木质桌椅，干净齐整。',0,0,0,0,307,27,'东山宁宁向银杏村口走去',0,'','',0,15,0,''),('肉店',312,NULL,NULL,NULL,0,'这是一片溪边的杏树林，一群孩童在此玩耍。',0,0,0,0,0,27,'老郭向杏林走去',0,'','',0,15,0,''),('寄售商',313,NULL,NULL,NULL,0,'村口一个简易寄售商，放着几张木质桌椅，干净齐整。',0,0,0,0,0,27,'老郭向杏林走去',0,'','',0,15,0,''),('银杏广场',314,NULL,NULL,NULL,0,'一棵千年银杏树屹立在广场中央，树下有一口古井，据说这口古井的水清澈甘甜，村里的人每天都会来这里挑水。',0,310,322,321,315,27,'东山宁宁向广场小径走去',0,'','',0,15,0,''),('杂货铺',315,NULL,'36',NULL,0,'村里的杂货铺，店老板正在清点货品。',0,0,0,314,0,27,'绾绾向银杏广场走去',0,'','',0,15,0,''),('仓库',316,NULL,'37',NULL,0,'村里的仓库，店老板正在清点货品。',0,0,0,0,317,27,'巫山向杂货铺走去',0,'','',0,15,0,''),('后院',317,NULL,NULL,NULL,0,'杂货铺后院，堆放着一些杂物，东边角落里放着一个马车车厢，一个跛脚汉子坐在一旁假寐。',0,0,0,316,0,27,'老郭向仓库走去',0,'','',0,15,0,''),('碎石路',318,NULL,NULL,NULL,0,'这是一条碎石小路，前面有一个武器店。',0,0,0,0,0,27,'巫山向棉布店走去',0,'','',0,15,0,''),('武器店',319,NULL,'39',NULL,0,'这是一间武器店，炉火烧的正旺，一名汉子赤膊挥舞着巨锤，锤落之处但见火花四溅。',0,0,0,310,0,27,'绾绾向广场小径走去',0,'','',0,15,0,''),('棉布店',320,NULL,'40',NULL,0,'这是一条碎石小路，前面有一个棉布店。',0,0,0,0,0,27,'巫山向赤月峡谷走去',0,'','',0,15,0,''),('药店',321,NULL,'38',NULL,0,'这是村内药店大门，门口一棵古槐，树干低垂。',0,0,0,0,314,27,'绾绾向赤月峡谷走去',0,'','',0,15,0,''),('广场小径',322,NULL,NULL,NULL,0,'北边是村广场，几棵野草从石缝中钻出，清澈的溪水自桥下湍湍流过。',0,314,324,0,0,27,'东山宁宁向银杏广场走去',0,'','',0,15,0,''),('药店厅堂',323,NULL,NULL,NULL,0,'药店的大厅，这里排放着大量药品。',0,0,0,0,0,27,'巫山向药店走去',0,'','',0,15,0,''),('村南小路',324,NULL,NULL,NULL,0,'村南泥泞的小路，一个稻草人孤单的立在一旁，似乎在指着某个地方。',0,322,0,328,327,27,'东山宁宁向广场小径走去',0,'','',0,15,0,''),('杂草小路',325,NULL,NULL,NULL,0,'一条杂草丛生的乡间小路，时有毒蛇出没。',0,0,0,0,326,27,'绾绾向田间小路走去',0,'','',0,15,0,''),('材料商',326,NULL,NULL,NULL,0,'一间看起来有些破败的小茅屋，屋内角落里堆着一堆稻草，只见稻草堆悉悉索索响了一阵，竟然从里面钻出一个人来。',0,0,0,325,0,27,'绾绾向杂草小路走去',0,'','',0,15,0,''),('破庙',327,NULL,NULL,NULL,0,'一间破败的庙。',0,0,0,324,0,27,'萝蜜小洋向村南小路走去',0,'','',0,15,0,''),('篱笆小院',328,NULL,NULL,NULL,0,'这是一间竹篱围城的小院，院内种着几株桃花，屋后竹林环绕，颇为雅致。',0,0,0,0,324,27,'越青向矿区入口走去',0,'','',0,15,0,''),('小厅',329,NULL,NULL,NULL,0,'这是小院的厅堂，迎面墙壁上挂着一幅山水画，看来小院的主人不是普通农人。',0,0,0,0,0,27,'老郭向饰品店走去',0,'','',0,15,0,''),('银杏山谷野外',330,'106|5',NULL,'2021-04-24 22:21:40',0,'山谷以其中遍布的银杏树得名。银杏村中的那颗千年银杏更是玛法十大景点之一。',0,331,308,0,0,28,'东山宁宁向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',331,'107|5',NULL,'2020-12-22 15:49:45',0,'山谷以其中遍布的银杏树得名。银杏村中的那颗千年银杏更是玛法十大景点之一。',0,332,330,0,0,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',332,'106|3,107|3,113|2',NULL,'2020-12-22 15:49:46',0,'山谷以其中遍布的银杏树得名。银杏村中的那颗千年银杏更是玛法十大景点之一。',0,335,331,334,333,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',333,'115|2,114|3,112|3',NULL,'2020-11-11 20:52:30',0,'山谷以其中遍布的银杏树得名。银杏村中的那颗千年银杏更是玛法十大景点之一。',0,0,0,332,336,28,'巫山向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',334,'108|3,109|3,110|2',NULL,'2021-05-24 09:18:54',0,'山谷以其中遍布的银杏树得名。银杏村中的那颗千年银杏更是玛法十大景点之一。',0,0,0,345,332,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',335,'113|3,106|3,107|3',NULL,'2020-12-22 15:49:52',0,'山谷以其中遍布的银杏树得名。银杏村中的那颗千年银杏更是玛法十大景点之一。',0,342,332,0,0,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',336,'112|3,114|3',NULL,'2020-11-19 11:14:49',0,'山谷以其中遍布的银杏树得名。银杏村中的那颗千年银杏更是玛法十大景点之一。',0,337,0,333,0,28,'巫山向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',337,'115|3,114|3',NULL,'2020-11-27 14:20:15',0,'',0,338,336,0,0,28,'巫山向银杏村口走去',1,'','',0,15,0,''),('银杏山谷野外',338,'115|3,114|1,112|2',NULL,'2020-11-27 13:17:14',0,'',0,339,337,0,0,28,'巫山向矿区入口走去',1,'','',0,15,0,''),('银杏山谷野外',339,'110|3,114|1,112|3',NULL,'2020-11-11 20:52:56',0,'',0,0,338,340,0,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',340,'114|3,112|3,110|3',NULL,'2020-11-11 20:53:01',0,'',0,0,0,341,339,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',341,'111|5,112|3',NULL,'2020-11-17 17:05:41',0,'',0,343,342,349,340,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',342,'114|3,111|3',NULL,'2020-12-27 17:55:28',0,'',0,341,335,0,0,28,'绾绾向银杏村口走去',1,'','',0,15,0,''),('银杏山谷野外',343,'112|3,111|3',NULL,'2020-11-11 16:33:10',0,'',0,344,341,0,0,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',344,'112|3,114|3,111|3',NULL,'2020-11-12 00:06:41',0,'',0,0,343,0,0,28,'绾绾向银杏村口走去',1,'','',0,15,0,''),('银杏山谷野外',345,'110|3,108|3',NULL,'2021-05-24 09:19:31',0,'',0,346,0,0,334,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',346,'109|3,108|3',NULL,'2021-05-24 09:19:47',0,'',0,347,345,0,0,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',347,'109|3,110|3,108|3',NULL,'2021-05-24 09:19:55',0,'',0,348,346,0,0,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',348,'109|3,110|3,108|3',NULL,'2021-05-24 09:19:57',0,'',0,0,347,0,349,28,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('银杏山谷野外',349,'109|2,108|4,110|2',NULL,'2021-05-24 09:23:39',0,'',0,0,0,348,341,28,'绾绾向银杏村口走去',1,'','',0,15,0,''),('中央广场',350,NULL,'27',NULL,0,'这里是传奇城中心地带，一些游手好闲的人在这里游荡，五湖四海的艺人会在这里表演。中央有一棵大榕树，盘根错节，据传已有千年的树龄。',0,367,362,379,380,29,'越青向银杏村口走去',0,'','',0,15,0,''),('矿区入口',351,'116|5',NULL,'2020-12-28 10:47:25',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,352,0,0,0,30,'绾绾向祖玛一层走去',1,'','',0,15,0,''),('比奇矿区',352,'116|5',NULL,'2020-12-28 14:11:26',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,0,351,353,395,30,'绾绾向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',353,'116|3,117|3',NULL,'2020-12-31 13:30:32',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,354,0,0,352,30,'绾绾向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',354,'117|4,118|2',NULL,'2020-12-31 13:30:34',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,355,353,0,0,30,'绾绾向赤月峡谷走去',1,'','',0,15,0,'10'),('比奇矿区',355,'117|2,116|1,118|3',NULL,'2020-11-11 09:25:44',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,0,354,0,356,30,'巫山向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',356,'118|2,119|4',NULL,'2020-11-29 00:08:44',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,0,0,355,357,30,'巫山向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',357,'120|2,119|3',NULL,'2020-12-15 10:39:40',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,359,358,356,0,30,'巫山向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',358,NULL,NULL,NULL,0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,357,0,0,0,30,'巫山向祖玛一层走去',1,'','',0,15,0,'10'),('比奇矿区',359,'119|3,120|3',NULL,'2020-11-27 17:22:16',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,0,357,360,400,30,'巫山向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',360,'120|3,121|3',NULL,'2020-11-09 08:23:13',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,361,0,399,359,30,'归无咎向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',361,'121|3,120|3',NULL,'2020-11-02 17:27:35',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同。',0,0,360,0,0,30,'越青向银杏村口走去',1,'','',0,15,0,'10'),('比奇长街',362,NULL,NULL,NULL,0,'走在比奇长街，可见街边酒楼茶肆，重楼高阁。入夜各酒馆茶楼挑出灯笼，行人游客络绎不绝，一派繁华景象。',0,350,363,0,0,29,'巫山向中央广场走去',0,'','',0,15,0,''),('比奇长街',363,NULL,NULL,NULL,0,'沿着比奇长街南行，西边是官河，岸边相间种有绿柳碧桃，倒影水面，摇曳风中。西边临街的屋子挂有招牌，是一家老字号杂货店。西边是比奇皇宫。',0,362,364,381,382,29,'巫山向皇宫大门走去',0,'','',0,15,0,''),('比奇长街',364,NULL,NULL,NULL,0,'西边是一家武器店，叮叮当当的声音老远就能听到。西面就是美容店了，继续往南走就出南门了。',0,363,365,386,0,29,'老郭向武器店走去',0,'','',0,15,0,''),('南门大街',365,NULL,NULL,NULL,0,'西边是官河，也叫草河，岸边依次种着杨柳和碧桃、街西是太平桥，街东是马匹店。',0,364,366,387,390,29,'老郭向比奇长街走去',0,'','',0,15,0,''),('安定门',366,NULL,NULL,NULL,0,'一座高大的城门耸峙在渡江桥头，城门嵌有「安定门」三字石额。城墙上建有一个城楼，左右各有一个水门，几个巡逻的官兵正在在执勤。',0,365,0,0,0,29,'老郭向南门大街走去',0,'','',0,15,0,''),('比奇长街',367,NULL,NULL,NULL,0,'走到这里，路边传来的阵阵酒肉香让你垂涎欲滴，抬头一看，原来是到了有名的“客栈”。西边是一座样式古朴的寄售店。',0,368,350,0,377,29,'绾绾向银杏村口走去',0,'','',0,15,0,''),('比奇长街',368,NULL,NULL,NULL,0,'西边是比奇最大的棉布店，仔细倾听，可以听到压低的讨价还价的声音。东边则是有名的肉店。',0,369,367,375,376,29,'老郭向比奇长街走去',0,'','',0,15,0,''),('比奇长街',369,NULL,NULL,NULL,0,'到了这里，行人渐渐少了下来，往西便是虹桥。',0,370,368,374,0,29,'老郭向北门大街走去',0,'','',0,15,0,''),('北门大街',370,NULL,NULL,NULL,0,'街西是琼花街，街东通向东关街。北边是比奇的北门「镇淮门」。',0,371,369,372,373,29,'老郭向银杏村口走去',0,'','',0,15,0,''),('镇淮门',371,NULL,NULL,NULL,0,'这是比奇的北门，城门两边站着几个士兵，盘查着过往行人车辆。门前有一道深深的护城河，左右各开一水门，连接护城河及内城河。',0,0,370,0,0,29,'老郭向北门大街走去',0,'','',0,15,0,''),('琼花街',372,NULL,NULL,NULL,0,'琼花街两边的屋檐密密重重，傍河而建，数条小巷穿插其中，。北面传来悠扬的钟声。',0,0,0,0,370,29,'老郭向北门大街走去',0,'','',0,15,0,''),('东关街',373,NULL,NULL,NULL,0,'东关街两边商家林立，颇为繁华，有名的小玲珑山关就坐落在这条街上。',0,0,0,370,0,29,'老郭向北门大街走去',0,'','',0,15,0,''),('虹桥',374,NULL,NULL,NULL,0,' 是一座木制拱桥，围以红栏，横卧在草河上，如同卧虹于波，站在桥上可以远眺秀丽湖色。',0,0,0,394,369,29,'',0,'','',0,15,0,''),('棉布店',375,NULL,NULL,NULL,0,'这是一家以买卖公平著称的棉布店，一个五尺高的柜台挡在你的面前，柜台后坐着唐老板，一双精明的眼睛上上下下打量着你。',0,0,0,0,368,29,'越青向比奇长街走去',0,'','',0,15,0,''),('肉店',376,NULL,NULL,NULL,0,'你一走进来，一股清幽的茶香沁入心脾。茶楼临河处围以朱栏，来自各地的客人或高声谈笑，或交头接耳品茶闲谈。这里是打听江湖掌故和谣言的好所在。',0,0,0,368,0,29,'',0,'','',0,15,0,''),('客栈',377,NULL,NULL,NULL,0,'这里是客栈的大厅，里面坐满了慕名前来的客人，据说这里的花雕酒可是让不少文人侠客赞不绝口。',0,0,0,367,378,29,'露草向比奇长街走去',0,'','',0,15,0,''),('二楼仓库',378,NULL,NULL,NULL,0,'二楼是雅座，文人学士经常在这里吟诗作画，富商土豪也在这里边吃喝边作交易。',0,0,0,377,0,29,'露草向客栈走去',0,'','',0,15,0,''),('通泗桥',379,NULL,NULL,NULL,0,'通泗桥连接比奇旧城的中心十里街和官衙，宽敞平整。桥的西头种着一大片芍药，一株老杏，横卧水上。桥的东头，立一巨石，上书「通泗桥」。',0,0,0,391,350,29,'绾绾向沃玛寺庙走去',0,'','',0,15,0,''),('甘泉街',380,NULL,NULL,NULL,0,'是一条青石街道，街南是名扬天下的甘泉书院，隐隐可以听到朗朗的读书声。',0,0,0,350,0,29,'绾绾向中央广场走去',0,'','',0,15,0,''),('杂货店',381,NULL,NULL,NULL,0,'大箱小箱堆满了一地，都是一些日常用品。黄掌柜懒洋洋地躺在一只躺椅上，招呼着过往行人。据说私底下他也卖一些贵重的东西。',0,0,0,0,363,29,'绾绾向比奇长街走去',0,'','',0,15,0,''),('皇宫大门',382,NULL,NULL,NULL,0,'门口高悬一个大匾“比奇皇宫”，左右立着石狮，门口站着两个护卫，神情威严。',0,0,0,363,383,29,'巫山向比奇长街走去',0,'','',0,15,0,''),('皇宫大院',383,NULL,NULL,NULL,0,'这是个大院子，南西两边都是练武场，不少人在这里习武强身，这里很吵，乱哄哄的，你看见不时有扛着东西的，挑着水的匆匆而过。北面有一个练武场。',0,384,385,382,0,29,'巫山向皇宫大门走去',0,'','',0,15,0,''),('皇宫练武场',384,NULL,NULL,NULL,0,'这是露天练武场，好多人在这里辛苦的练着，你走在场中，没有人回头看你一眼，都在聚精汇神的练着自己的功夫。',0,0,383,0,0,29,'老郭向皇宫大院走去',0,'','',0,15,0,''),('皇宫大厅',385,NULL,NULL,NULL,0,'这里是皇宫大厅，正中靠北摆着一张八仙桌，墙上写着大大的一个“武”字。',0,383,0,0,0,29,'老郭向皇宫大院走去',0,'','',0,15,0,''),('武器店',386,NULL,NULL,NULL,0,'这是一家简陋的武器店，中心摆着一个火炉，炉火把四周照得一片通红，一位铁匠满头大汗挥舞着铁锤，专心致志地在打铁。',0,0,0,0,364,29,'老郭向比奇长街走去',0,'','',0,15,0,''),('太平桥',387,NULL,NULL,NULL,0,'这是一座别致的拱桥，远望之如美人的两弯柳眉；明月当空时，两拱之下各一月影，如美人之妙目顾盼流转。',0,0,0,388,365,29,'老郭向南门大街走去',0,'','',0,15,0,''),('草河南街',388,NULL,NULL,NULL,0,'河岸边上的杨柳、碧桃倒影河中，摇曳多姿，整条街显的极为幽静，街北有一家租赁店。',0,389,0,0,387,29,'',0,'','',0,15,0,''),('租赁店',389,NULL,NULL,NULL,0,'店内百花齐放，清香满室。一个伙计手持花洒，在花间轻轻走动，精心照料着每朵鲜花。',0,0,388,0,0,29,'',0,'','',0,15,0,''),('马匹店',390,NULL,NULL,NULL,0,'小东门桥由青石筑成，青青的河水从桥下悠然流过，小秦淮河。',0,0,0,365,0,29,'老郭向南门大街走去',0,'','',0,15,0,''),('通泗街',391,NULL,NULL,NULL,0,'通泗街由大块青石砌成，两边整齐的种着槐树，南面都是民宅，几条小巷曲折穿插其中。',0,392,393,0,379,29,'',0,'','',0,15,0,''),('饰品店',392,NULL,NULL,NULL,0,'通泗街由大块青石砌成，两边整齐的种着槐树，几条小巷曲折穿插其中。',0,0,391,0,0,29,'',0,'','',0,15,0,''),('芍药巷',393,NULL,NULL,NULL,0,' 小巷两侧种满了各色芍药，花香馥郁，这比奇城里到处藏着意外的惊喜。',0,391,0,0,0,29,'',0,'','',0,15,0,''),('草河北街',394,NULL,NULL,NULL,0,'这是草河小街的尽头了，北边就是瘦湖。西北边是声望道具店。这里游人稀少，只有几个顽童在街上打闹，给这寂静的街道添了些许生气。',0,0,0,0,374,29,'',0,'','',0,15,0,''),('比奇矿区',395,NULL,NULL,NULL,0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同',0,0,0,352,396,30,'归无咎向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',396,NULL,NULL,NULL,0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同',0,397,398,395,0,30,'归无咎向比奇矿区走去',1,'','',0,15,0,'10'),('比奇矿区',397,NULL,NULL,NULL,0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同',0,0,396,0,0,30,'',1,'','',0,15,0,'10'),('比奇矿区',398,NULL,NULL,NULL,0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同',0,396,0,0,0,30,'',1,'','',0,15,0,'10'),('比奇矿区',399,'119|3,118|1,120|2',NULL,'2020-11-04 17:13:20',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同',0,0,0,0,360,30,'',1,'','',0,15,0,'10'),('比奇矿区',400,'120|3,121|3',NULL,'2020-11-29 03:27:56',0,'黝黑的洞口深不见底，里面似乎传来叮叮当当的声音。挖矿可以产出矿品质的不同',0,0,0,359,0,30,'巫山向银杏村口走去',1,'','',0,15,0,'10'),('地牢入口',401,'126|1,123|2',NULL,'2020-12-22 10:20:06',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,402,0,0,0,31,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('地牢一层',402,'123|4,124|1',NULL,'2020-11-23 13:37:09',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,405,401,404,403,31,'巫山向地牢入口走去',1,'','',0,15,0,''),('地牢一层',403,'123|3,124|1,126|1',NULL,'2020-11-13 21:03:42',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,406,0,402,0,31,'巫山向地牢入口走去',1,'','',0,15,0,''),('地牢一层',404,'123|5',NULL,'2020-11-26 21:17:36',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,410,0,0,402,31,'巫山向地牢一层走去',1,'','',0,15,0,''),('地牢一层',405,'125|3,126|1,123|2',NULL,'2020-11-22 15:27:21',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,0,402,0,0,31,'巫山向银杏村口走去',1,'','',0,15,0,''),('地牢一层',406,'126|1,123|2,125|1,124|1',NULL,'2020-11-13 21:03:44',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,407,403,0,412,31,'巫山向地牢一层走去',1,'','',0,15,0,''),('地牢一层',407,'125|4,124|1',NULL,'2020-11-25 08:34:21',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,415,406,408,413,31,'巫山向地牢一层走去',1,'','',0,15,0,''),('地牢一层',408,'123|1,124|3,125|2',NULL,'2020-11-13 22:01:09',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,0,0,409,407,31,'巫山向地牢一层走去',1,'','',0,15,0,''),('地牢一层',409,'126|1,124|2,125|1,123|1',NULL,'2020-11-12 19:46:45',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,420,410,418,408,31,'巫山向地牢一层走去',1,'','',0,15,0,''),('地牢一层',410,'123|3,124|2',NULL,'2020-11-21 04:04:22',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,409,404,417,0,31,'巫山向地牢一层走去',1,'','',0,15,0,''),('地牢一层',411,NULL,NULL,NULL,0,'',0,0,0,0,0,31,'',1,'','',0,15,0,''),('地牢一层',412,'126|3,123|2',NULL,'2020-11-10 14:15:26',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,413,0,406,0,31,'越青向地牢一层走去',1,'','',0,15,0,''),('地牢一层',413,'126|4,123|1',NULL,'2020-11-25 17:14:04',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,0,412,407,0,31,'越青向地牢一层走去',1,'','',0,15,0,''),('地牢一层',414,NULL,NULL,NULL,0,'',0,0,0,0,0,31,'',1,'','',0,15,0,''),('地牢一层',415,'125|5',NULL,'2020-11-25 19:15:20',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,0,407,0,0,31,'',1,'','',0,15,0,''),('地牢一层',416,NULL,NULL,NULL,0,'',0,0,0,0,0,31,'',1,'','',0,15,0,''),('地牢一层',417,'126|3,123|3',NULL,'2020-11-20 15:49:12',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,418,0,0,410,31,'绾绾向地牢一层走去',1,'','',0,15,0,''),('地牢一层',418,'126|5',NULL,'2020-11-20 15:49:50',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,0,417,0,409,31,'越青向地牢一层走去',1,'','',0,15,0,''),('地牢一层',419,NULL,NULL,NULL,0,'',0,0,0,0,0,31,'越青向地牢入口走去',1,'','',0,15,0,''),('地牢一层',420,'124|5',NULL,'2020-11-13 20:31:01',0,'隐藏在黑暗中的危险山谷，充满了各种巨大而邪恶的怪物，每踏出一步都可能步入深渊，直到死亡。',0,0,409,0,0,31,'',1,'','',0,15,0,''),('地牢一层',421,NULL,NULL,NULL,0,'',0,0,0,0,0,31,'',1,'','',0,15,0,''),('地牢一层',422,NULL,NULL,NULL,0,'',0,0,0,0,0,31,'',1,'','',0,15,0,''),('尸王殿',423,'122|3,121|3,120|3',NULL,'2020-11-30 21:55:11',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,438,0,424,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',424,'122|3,121|3,120|3',NULL,'2020-11-29 17:42:37',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,0,423,425,32,'绾绾向银杏村口走去',1,'','',0,15,0,''),('尸王殿',425,'122|3,121|3,120|3',NULL,'2020-11-30 21:58:21',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,442,424,426,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',426,'122|3,121|3,120|3',NULL,'2020-12-04 22:01:56',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,0,425,427,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',427,'122|3,121|3,120|3',NULL,'2020-12-04 22:08:26',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,428,426,0,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',428,'122|3,121|3,120|3',NULL,'2020-12-08 04:46:59',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,427,429,0,0,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',429,'122|3,121|3,120|3',NULL,'2020-12-04 22:23:32',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,428,430,441,0,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',430,'122|3,121|3,120|3',NULL,'2020-12-08 04:43:57',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,429,431,0,0,32,'巫山向银杏村口走去',1,'','',0,15,0,''),('尸王殿',431,'122|3,121|3,120|3',NULL,'2020-12-08 04:50:13',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,430,0,432,0,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',432,'122|3,121|3,120|3',NULL,'2020-12-04 21:49:30',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,0,433,431,32,'绾绾向银杏山谷野外走去',1,'','',0,15,0,''),('尸王殿',433,'122|3,121|3,120|3',NULL,'2020-11-29 19:35:48',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,443,0,434,432,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',434,'122|3,121|3,120|3',NULL,'2020-12-08 04:43:40',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,0,435,433,32,'巫山向银杏村口走去',1,'','',0,15,0,''),('尸王殿',435,'122|3,121|3,120|3',NULL,'2020-11-16 19:39:20',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,436,0,0,434,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',436,'122|3,121|3,120|3',NULL,'2020-11-25 20:16:58',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,437,435,0,0,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',437,'122|3,121|3,120|3',NULL,'2020-11-30 21:45:19',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,438,436,0,439,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',438,'122|3,121|3,120|3',NULL,'2020-12-04 21:59:58',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,423,437,0,0,32,'巫山向尸王殿走去',1,'','',0,15,0,''),('尸王殿',439,'122|3,121|3,120|3',NULL,'2020-12-04 21:59:47',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,0,437,440,32,'巫山向祭坛走去',1,'','',0,15,0,''),('祭坛',440,'122|5',NULL,'2020-12-08 04:55:00',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,442,443,439,441,32,'巫山向祖玛神殿走去',1,'','',0,15,0,''),('尸王殿',441,'122|3,121|3,120|3',NULL,'2020-11-29 17:43:40',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,0,0,440,429,32,'巫山向祭坛走去',1,'','',0,15,0,''),('尸王殿',442,'122|3,121|3,120|3',NULL,'2020-12-04 22:23:20',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,425,440,0,0,32,'巫山向祭坛走去',1,'','',0,15,0,''),('尸王殿',443,'122|3,121|3,120|3',NULL,'2020-11-18 12:36:22',0,'比奇矿区的最深处，被强大的魔力所封印。死亡在殿中的生灵无法得到超脱，变成了恐怖的怪物。',0,440,433,0,0,32,'绾绾向祭坛走去',1,'','',0,15,0,''),('沃玛寺庙',444,'129|5',NULL,'2020-12-19 12:04:27',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,446,445,33,'绾绾向祖玛一层走去',1,'','',0,15,0,''),('沃玛寺庙',445,NULL,NULL,'2020-11-26 19:29:57',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,449,0,444,0,33,'越青向祖玛神殿走去',1,'','',0,15,0,''),('沃玛寺庙',446,NULL,NULL,'2020-11-26 13:44:55',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,447,0,0,444,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',447,NULL,NULL,'2020-11-26 13:44:57',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,448,446,0,0,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',448,NULL,NULL,'2020-11-26 13:45:07',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,447,471,0,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',449,NULL,NULL,'2020-11-26 19:00:17',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,450,445,0,0,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',450,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,449,0,451,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',451,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,452,0,450,0,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',452,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,453,451,0,0,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',453,NULL,NULL,'2020-12-03 23:09:15',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,454,452,474,0,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',454,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,455,453,0,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',455,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,456,454,475,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',456,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,457,455,0,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',457,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,456,458,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',458,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,459,0,0,457,33,'',1,'','',0,15,0,''),('沃玛寺庙',459,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,460,458,0,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',460,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,459,461,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',461,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,462,460,33,'',1,'','',0,15,0,''),('沃玛寺庙',462,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,463,0,461,33,'',1,'','',0,15,0,''),('沃玛寺庙',463,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,462,464,0,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',464,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,463,0,465,0,33,'',1,'','',0,15,0,''),('沃玛寺庙',465,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,466,0,464,33,'',1,'','',0,15,0,''),('沃玛寺庙',466,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,465,467,0,0,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',467,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,466,468,0,477,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',468,NULL,NULL,'2020-12-03 23:09:07',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,467,469,0,0,33,'越青向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',469,NULL,NULL,'2020-12-03 23:09:04',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,468,470,0,472,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',470,NULL,NULL,'2020-12-03 23:09:02',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,469,471,0,0,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',471,NULL,NULL,'2020-12-03 23:08:58',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,470,0,0,448,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',472,NULL,NULL,'2020-12-03 23:09:11',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,469,473,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',473,NULL,NULL,'2020-12-03 23:09:12',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,478,0,472,474,33,'巫山向沃玛二层入口走去',1,'','',0,15,0,''),('沃玛寺庙',474,NULL,NULL,'2020-12-03 23:09:14',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,473,453,33,'巫山向沃玛寺庙走去',1,'','',0,15,0,''),('沃玛寺庙',475,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,476,455,33,'',1,'','',0,15,0,''),('沃玛寺庙',476,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,478,477,475,33,'',1,'','',0,15,0,''),('沃玛寺庙',477,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,467,476,33,'',1,'','',0,15,0,''),('沃玛二层入口',478,NULL,NULL,'2020-12-11 12:50:54',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,476,473,0,0,33,'巫山向沃玛二层走去',1,'','',0,15,0,'11'),('祖玛一层',479,NULL,NULL,'2021-02-21 14:34:10',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,537,0,534,531,34,'绾绾向银杏村口走去',1,'','',0,15,0,''),('赤月峡谷',480,NULL,NULL,'2021-05-19 12:47:03',0,'你走在一条山谷上，两旁种满了竹子，修篁森森，绿荫满地，除了竹叶声和鸟鸣声，听不到别的动静。',0,0,0,0,0,35,'绾绾向矿区入口走去',1,'','',0,15,0,''),('沃玛二层',481,NULL,NULL,'2020-12-16 04:29:00',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,498,482,36,'绾绾向沃玛二层走去',1,'','',0,15,0,''),('沃玛二层',482,NULL,NULL,'2020-12-11 13:25:40',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,484,494,481,0,36,'越青向沃玛三层_传送点走去',1,'','',0,15,0,''),('沃玛三层',483,NULL,NULL,'2020-12-16 04:49:00',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,529,530,515,509,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛二层',484,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,485,482,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',485,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,484,501,486,36,'',1,'','',0,15,0,''),('沃玛二层',486,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,485,487,36,'',1,'','',0,15,0,''),('沃玛二层',487,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,488,486,0,36,'',1,'','',0,15,0,''),('沃玛二层',488,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,487,489,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',489,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,488,490,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',490,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,489,491,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',491,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,490,0,492,0,36,'',1,'','',0,15,0,''),('沃玛二层',492,NULL,NULL,'2020-12-13 11:25:28',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,493,491,36,'',1,'','',0,15,0,''),('沃玛二层',493,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,494,0,495,492,36,'',1,'','',0,15,0,''),('沃玛二层',494,NULL,NULL,'2020-12-13 11:26:49',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,482,493,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',495,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,496,493,36,'',1,'','',0,15,0,''),('沃玛二层',496,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,497,0,508,495,36,'',1,'','',0,15,0,''),('沃玛二层',497,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,498,496,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',498,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,499,497,0,481,36,'',1,'','',0,15,0,''),('沃玛二层',499,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,500,498,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',500,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,499,502,501,36,'',1,'','',0,15,0,''),('沃玛二层',501,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,500,485,36,'',1,'','',0,15,0,''),('沃玛二层',502,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,503,500,36,'',1,'','',0,15,0,''),('沃玛二层',503,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,504,0,502,36,'',1,'','',0,15,0,''),('沃玛二层',504,NULL,NULL,'2020-12-13 11:48:12',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,503,505,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',505,NULL,NULL,'2020-12-13 11:48:08',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,504,506,0,0,36,'越青向沃玛三层走去',1,'','',0,15,0,''),('沃玛二层',506,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,505,507,0,0,36,'',1,'','',0,15,0,''),('沃玛二层',507,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,506,0,0,508,36,'',1,'','',0,15,0,''),('沃玛二层',508,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,507,496,36,'',1,'','',0,15,0,''),('沃玛三层',509,NULL,NULL,'2020-12-15 17:29:16',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,510,520,483,0,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',510,NULL,NULL,'2020-12-15 19:39:20',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,511,509,529,0,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',511,NULL,NULL,'2020-12-15 18:44:02',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,527,510,512,528,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',512,NULL,NULL,'2020-12-15 18:03:12',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,529,513,511,37,'越青向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',513,NULL,NULL,'2020-12-15 19:41:20',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,525,514,526,512,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',514,NULL,NULL,'2020-12-15 19:38:10',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,513,515,0,529,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',515,NULL,NULL,'2020-12-15 18:33:49',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,514,516,0,483,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',516,NULL,NULL,'2020-12-15 18:08:06',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,515,517,0,530,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',517,NULL,NULL,'2020-12-15 18:03:49',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,516,524,523,518,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',518,NULL,NULL,'2020-12-15 17:55:37',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,530,0,517,519,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',519,NULL,NULL,'2020-12-15 17:54:04',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,520,522,518,521,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',520,NULL,NULL,'2020-12-15 17:22:49',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,509,519,530,0,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',521,NULL,NULL,'2020-12-15 17:58:17',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,519,0,37,'',1,'','',0,15,0,''),('沃玛三层',522,NULL,NULL,'2020-12-15 17:28:52',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,519,0,0,0,37,'',1,'','',0,15,0,''),('沃玛三层',523,NULL,NULL,'2020-12-15 18:04:37',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,0,517,37,'',1,'','',0,15,0,''),('沃玛三层',524,NULL,NULL,NULL,0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,517,0,0,0,37,'',1,'','',0,15,0,''),('沃玛三层',525,NULL,NULL,'2020-12-15 19:44:35',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,513,0,0,37,'巫山向银杏村口走去',1,'','',0,15,0,''),('沃玛三层',526,NULL,NULL,'2020-12-15 18:48:08',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,0,513,37,'',1,'','',0,15,0,''),('沃玛三层',527,NULL,NULL,'2020-12-15 18:45:30',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,511,0,0,37,'',1,'','',0,15,0,''),('沃玛三层',528,NULL,NULL,'2020-12-15 18:45:12',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,0,0,511,0,37,'',1,'','',0,15,0,''),('沃玛三层',529,NULL,NULL,'2020-12-15 18:01:50',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,512,483,514,510,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('沃玛三层',530,NULL,NULL,'2020-12-15 17:22:18',0,'隐藏在茂密森林中古老寺庙，这里供奉的并非神明，而是堕入黑暗的魔鬼。',0,483,518,516,520,37,'巫山向沃玛三层走去',1,'','',0,15,0,''),('祖玛一层',531,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,0,479,532,34,'',1,'','',0,15,0,''),('祖玛一层',532,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,533,0,531,0,34,'',1,'','',0,15,0,''),('祖玛一层',533,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,532,0,0,34,'',1,'','',0,15,0,''),('祖玛一层',534,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,0,535,479,34,'',1,'','',0,15,0,''),('祖玛一层',535,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,536,0,0,534,34,'',1,'','',0,15,0,''),('祖玛一层',536,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,535,0,0,34,'',1,'','',0,15,0,''),('祖玛一层',537,NULL,NULL,'2020-12-17 13:03:40',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,538,479,0,0,34,'绾绾向祖玛一层走去',1,'','',0,15,0,''),('祖玛一层',538,NULL,NULL,'2020-12-17 13:03:41',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,537,540,539,34,'绾绾向祖玛一层走去',1,'','',0,15,0,''),('祖玛一层',539,NULL,NULL,'2020-12-17 13:03:43',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,541,0,538,0,34,'',1,'','',0,15,0,''),('祖玛一层',540,NULL,NULL,'2020-12-17 13:14:18',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,545,0,0,538,34,'绾绾向祖玛一层走去',1,'','',0,15,0,''),('祖玛一层',541,NULL,NULL,'2020-12-17 12:55:59',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,542,539,0,555,34,'越青向祖玛一层走去',1,'','',0,15,0,''),('祖玛一层',542,NULL,NULL,'2020-12-17 12:56:44',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,541,543,0,34,'越青向祖玛一层走去',1,'','',0,15,0,''),('祖玛一层',543,NULL,NULL,'2020-12-17 12:56:46',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,546,556,544,542,34,'绾绾向二层入口走去',1,'','',0,15,0,''),('祖玛一层',544,NULL,NULL,'2020-12-17 13:04:55',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,545,0,543,34,'绾绾向祖玛一层走去',1,'','',0,15,0,''),('祖玛一层',545,NULL,NULL,'2020-12-17 13:04:57',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,544,540,554,0,34,'绾绾向祖玛一层走去',1,'','',0,15,0,''),('祖玛一层',546,NULL,NULL,'2020-12-17 13:04:52',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,547,543,0,0,34,'',1,'','',0,15,0,''),('祖玛一层',547,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,546,551,548,34,'',1,'','',0,15,0,''),('祖玛一层',548,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,0,547,549,34,'',1,'','',0,15,0,''),('祖玛一层',549,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,550,548,0,34,'',1,'','',0,15,0,''),('祖玛一层',550,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,549,0,0,0,34,'',1,'','',0,15,0,''),('祖玛一层',551,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,0,552,547,34,'',1,'','',0,15,0,''),('祖玛一层',552,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,553,0,551,34,'',1,'','',0,15,0,''),('祖玛一层',553,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,552,0,0,0,34,'',1,'','',0,15,0,''),('祖玛一层',554,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,0,0,545,34,'',1,'','',0,15,0,''),('祖玛一层',555,NULL,NULL,'2020-12-17 13:03:46',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,0,541,0,34,'',1,'','',0,15,0,''),('二层入口',556,NULL,NULL,'2020-12-17 12:57:47',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,543,0,0,0,34,'绾绾向赤月峡谷走去',1,'','',0,15,0,'12'),('祖玛二层',557,NULL,NULL,'2020-12-17 13:10:41',0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,560,564,562,558,38,'越青向银杏村口走去',1,'','',0,15,0,''),('祖玛二层',558,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,559,565,557,0,38,'',1,'','',0,15,0,''),('祖玛二层',559,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,558,560,0,38,'',1,'','',0,15,0,''),('祖玛二层',560,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,557,561,559,38,'',1,'','',0,15,0,''),('祖玛二层',561,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,0,562,0,560,38,'',1,'','',0,15,0,''),('祖玛二层',562,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,561,563,0,557,38,'',1,'','',0,15,0,''),('祖玛二层',563,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,562,0,0,564,38,'',1,'','',0,15,0,''),('祖玛二层',564,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,557,0,563,565,38,'',1,'','',0,15,0,''),('祖玛二层',565,NULL,NULL,NULL,0,'祖玛神殿是祖玛族不可侵犯的圣地，同样也是玛法大陆上，祖玛教唯一一座仍旧存在着的祭祀之地。',0,558,0,564,0,38,'',1,'','',0,15,0,''),('时空裂缝',566,NULL,'41',NULL,0,'你从睡梦中醒来，发现四周都是无尽的黑暗， 恐怖的破碎声此起彼伏。一个神秘的黑袍人站在眼前，一动不动。',0,0,0,0,0,39,'槐花九九向银杏村口走去',0,'','',0,15,0,'');
/*!40000 ALTER TABLE `mid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `midguaiwu`
--

DROP TABLE IF EXISTS `midguaiwu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `midguaiwu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `level` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `gid` int(11) NOT NULL COMMENT '怪物原型编号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '怪物所属玩家编号',
  `exp` int(11) NOT NULL,
  `hp` int(11) NOT NULL DEFAULT 10 COMMENT '气血',
  `mp` int(11) NOT NULL DEFAULT 10 COMMENT '灵气',
  `maxhp` int(11) NOT NULL DEFAULT 10 COMMENT '最大气血',
  `maxmp` int(11) NOT NULL DEFAULT 10 COMMENT '最大灵气',
  `baqi` int(11) NOT NULL DEFAULT 10 COMMENT '霸气',
  `wugong` int(11) NOT NULL DEFAULT 10 COMMENT '物理攻击',
  `fagong` int(11) NOT NULL DEFAULT 10 COMMENT '法术攻击',
  `wufang` int(11) NOT NULL DEFAULT 10 COMMENT '物理防御',
  `fafang` int(11) NOT NULL DEFAULT 10 COMMENT '法术防御',
  `shanbi` int(11) NOT NULL DEFAULT 13 COMMENT '闪避',
  `mingzhong` int(11) NOT NULL DEFAULT 10 COMMENT '命中',
  `baoji` int(11) NOT NULL DEFAULT 10 COMMENT '暴击',
  `shenming` int(11) NOT NULL DEFAULT 67 COMMENT '神明',
  `active_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '怪物活跃时间，过期自动删除',
  PRIMARY KEY (`id`),
  KEY `midguaiwu_gyid_IDX` (`gid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1606 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `midguaiwu`
--

LOCK TABLES `midguaiwu` WRITE;
/*!40000 ALTER TABLE `midguaiwu` DISABLE KEYS */;
INSERT INTO `midguaiwu` VALUES (4,'沃玛勇士',33,444,132,0,1160,4071,10,4071,10,381,326,326,326,326,126,95,95,630,'2020-12-19 04:04:27'),(5,'沃玛勇士',33,444,132,0,1160,4071,10,4071,10,381,326,326,326,326,126,95,95,630,'2020-12-19 04:04:27'),(983,'跳跳蜂',21,401,126,0,410,1722,10,1722,10,172,194,194,194,194,72,54,54,360,'2020-12-22 02:20:06'),(989,'猪',3,331,107,478,20,93,10,137,10,21,27,27,27,27,18,14,14,91,'2021-04-24 14:21:24'),(990,'猪',3,331,107,0,20,137,10,137,10,21,27,27,27,27,18,14,14,91,'2020-12-22 07:49:45'),(991,'猪',3,331,107,0,20,137,10,137,10,21,27,27,27,27,18,14,14,91,'2020-12-22 07:49:45'),(992,'猪',3,331,107,0,20,137,10,137,10,21,27,27,27,27,18,14,14,91,'2020-12-22 07:49:45'),(993,'猪',3,331,107,0,20,137,10,137,10,21,27,27,27,27,18,14,14,91,'2020-12-22 07:49:45'),(994,'猪',3,332,107,0,20,137,10,137,10,21,27,27,27,27,18,14,14,91,'2020-12-22 07:49:46'),(995,'猪',3,332,107,0,20,137,10,137,10,21,27,27,27,27,18,14,14,91,'2020-12-22 07:49:46'),(996,'鸡',1,332,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2020-12-22 07:49:46'),(997,'鸡',1,332,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2020-12-22 07:49:46'),(998,'鸡',1,332,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2020-12-22 07:49:46'),(999,'鸡',1,335,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2020-12-22 07:49:52'),(1000,'鸡',1,335,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2020-12-22 07:49:52'),(1001,'鸡',1,335,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2020-12-22 07:49:52'),(1002,'牛',5,335,113,0,30,238,10,238,10,36,45,45,45,45,23,17,17,117,'2020-12-22 07:49:52'),(1003,'猪',3,335,107,0,20,137,10,137,10,21,27,27,27,27,18,14,14,91,'2020-12-22 07:49:52'),(1138,'狼',9,342,114,0,50,440,10,440,10,65,80,80,80,80,33,25,25,166,'2020-12-27 09:55:28'),(1139,'狼',9,342,114,0,50,440,10,440,10,65,80,80,80,80,33,25,25,166,'2020-12-27 09:55:28'),(1140,'毒蜘蛛',10,342,111,0,55,609,10,609,10,73,88,88,88,88,35,27,27,178,'2020-12-27 09:55:28'),(1141,'毒蜘蛛',10,342,111,0,55,609,10,609,10,73,88,88,88,88,35,27,27,178,'2020-12-27 09:55:28'),(1142,'毒蜘蛛',10,342,111,0,55,609,10,609,10,73,88,88,88,88,35,27,27,178,'2020-12-27 09:55:28'),(1463,'山洞蝙蝠',12,351,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 02:47:25'),(1465,'山洞蝙蝠',12,351,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 02:47:25'),(1466,'山洞蝙蝠',12,351,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 02:47:25'),(1467,'山洞蝙蝠',12,351,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 02:47:25'),(1473,'山洞蝙蝠',12,352,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 06:11:26'),(1474,'山洞蝙蝠',12,352,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 06:11:26'),(1475,'山洞蝙蝠',12,352,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 06:11:26'),(1476,'山洞蝙蝠',12,352,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 06:11:26'),(1477,'山洞蝙蝠',12,352,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-28 06:11:26'),(1503,'洞蛆',14,353,117,0,175,857,10,857,10,103,124,124,124,124,46,34,34,227,'2020-12-31 05:30:32'),(1504,'洞蛆',14,353,117,0,175,857,10,857,10,103,124,124,124,124,46,34,34,227,'2020-12-31 05:30:32'),(1505,'山洞蝙蝠',12,353,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-31 05:30:32'),(1506,'山洞蝙蝠',12,353,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-31 05:30:32'),(1507,'山洞蝙蝠',12,353,116,0,115,730,10,730,10,88,107,107,107,107,41,30,30,203,'2020-12-31 05:30:32'),(1509,'腐尸',15,354,118,0,205,920,10,920,10,110,132,132,132,132,48,36,36,240,'2020-12-31 05:30:34'),(1521,'祖玛卫士',41,479,143,0,1875,6869,10,6869,10,515,433,433,433,433,174,131,131,870,'2021-02-21 06:34:10'),(1538,'鸡',1,330,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2021-04-24 14:21:40'),(1539,'鸡',1,330,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2021-04-24 14:21:40'),(1540,'鸡',1,330,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2021-04-24 14:21:40'),(1541,'鸡',1,330,106,0,10,40,10,40,10,6,10,10,10,10,13,10,10,67,'2021-04-24 14:21:40'),(1544,'赤月灰血魔',51,480,137,0,3115,11975,10,11975,10,856,619,619,619,619,258,194,194,1290,'2021-05-19 04:47:03'),(1545,'赤血恶魔',49,480,136,0,2835,9242,10,9242,10,792,575,575,575,575,238,179,179,1190,'2021-05-19 04:47:03'),(1546,'赤血恶魔',49,480,136,0,2835,9242,10,9242,10,792,575,575,575,575,238,179,179,1190,'2021-05-19 04:47:03'),(1547,'赤血恶魔',49,480,136,0,2835,9242,10,9242,10,792,575,575,575,575,238,179,179,1190,'2021-05-19 04:47:03'),(1554,'鹿',6,334,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:18:54'),(1555,'鹿',6,334,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:18:54'),(1559,'鹿',6,345,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:31'),(1560,'鹿',6,345,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:31'),(1561,'鹿',6,345,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:31'),(1562,'鹿',6,346,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:47'),(1563,'鹿',6,346,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:47'),(1565,'稻草人',7,346,109,0,40,335,10,335,10,51,63,63,63,63,28,21,21,141,'2021-05-24 01:19:47'),(1566,'稻草人',7,346,109,0,40,335,10,335,10,51,63,63,63,63,28,21,21,141,'2021-05-24 01:19:47'),(1567,'钉耙猫',8,347,110,0,45,386,10,386,10,58,71,71,71,71,31,23,23,153,'2021-05-24 01:19:55'),(1568,'鹿',6,347,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:55'),(1569,'鹿',6,347,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:55'),(1570,'鹿',6,347,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:19:55'),(1571,'稻草人',7,347,109,0,40,335,10,335,10,51,63,63,63,63,28,21,21,141,'2021-05-24 01:19:55'),(1572,'稻草人',7,348,109,0,40,335,10,335,10,51,63,63,63,63,28,21,21,141,'2021-05-24 01:19:57'),(1573,'稻草人',7,348,109,0,40,335,10,335,10,51,63,63,63,63,28,21,21,141,'2021-05-24 01:19:57'),(1574,'稻草人',7,348,109,0,40,335,10,335,10,51,63,63,63,63,28,21,21,141,'2021-05-24 01:19:57'),(1575,'钉耙猫',8,348,110,0,45,386,10,386,10,58,71,71,71,71,31,23,23,153,'2021-05-24 01:19:57'),(1576,'钉耙猫',8,348,110,0,45,386,10,386,10,58,71,71,71,71,31,23,23,153,'2021-05-24 01:19:57'),(1601,'鹿',6,349,108,0,35,289,10,289,10,43,54,54,54,54,26,19,19,129,'2021-05-24 01:23:39'),(1605,'钉耙猫',8,349,110,0,45,386,10,386,10,58,71,71,71,71,31,23,23,153,'2021-05-24 01:23:39');
/*!40000 ALTER TABLE `midguaiwu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc`
--

DROP TABLE IF EXISTS `npc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `npc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'NPC的ID',
  `name` text CHARACTER SET utf8 NOT NULL COMMENT 'NPC昵称',
  `sex` varchar(255) CHARACTER SET gb2312 NOT NULL COMMENT 'NPC性别',
  `info` text CHARACTER SET utf8 DEFAULT '' COMMENT 'NPC信息',
  `muban` text CHARACTER SET gb2312 DEFAULT '' COMMENT 'NPC模板',
  `taskid` text CHARACTER SET gb2312 NOT NULL DEFAULT '' COMMENT 'NPC任务',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc`
--

LOCK TABLES `npc` WRITE;
/*!40000 ALTER TABLE `npc` DISABLE KEYS */;
INSERT INTO `npc` VALUES (11,'村长','男',' 银杏村的一位隐世高人，不问世事，拥有各种神奇的能力。','','25,24,36'),(13,'王老五','男','唉……一个人的日子，真的好难。','','24'),(14,'赫炳','男','唉，整天站在这里真是无聊。','','28'),(15,'周富贵[商人]','男','来来来   便宜','商店.php,equip',''),(16,'聚仙城主','男','聚仙城城主','',''),(17,'云游仙医[治疗]','男','云游的仙医，似乎在哪都能看见他','治疗.php',''),(18,'王大妈','女','王大妈','','24,29'),(19,'符箓大师','男','技能大师，负责兑换技能','',''),(20,'小蛮','女','小蛮好怕...','','20'),(21,'蛮族长老','男','蛮族长老','','19'),(22,'蛮族猎手','男','老了,干不动了','','21'),(23,'兑换大使','男','兑换大使','','27'),(24,'正规仙医','男','正规仙医\r\n比云游的更在行','治疗_级别1',''),(25,'城主雪琴','女','城炎阳城的城主,雪琴','',''),(26,'门派管理员','男','门派管理','门派管理员',''),(27,'‹医师›老郎中','男',' 救死扶伤的医师，哪怕重伤也能很快恢复。','治疗','30,32,33'),(28,'余桂香','女','陈小萍的妈妈','','30,33'),(29,'陈小萍','女','生病躺着床上的小萍','','35'),(30,'小宝','男','小萍怎么还没来呢？','','35'),(31,'李猎户','男','躺着床上养伤','',''),(33,'‹银杏村›村长','男','银杏村的村长，不问世事，默默维持着村里的一切。','',''),(34,'‹通缉›正义联盟','男','   正义联盟NPC专门通缉红名玩家。','',''),(35,'‹书店›吕秀才','男','  活到老学到老是他的口头禅，满腹经纶的他通晓传奇大陆上各个职业的技能。','书店',''),(36,'‹杂货店›李老板','男',' 长年奔走于各地，做一些差价买卖，本着商人的原则，会额外收取一些道具。','杂货铺',''),(37,'‹仓库›文姬','女','银杏村落存取物品、元宝绑密宝、捆扎物品','',''),(38,'‹药店›张医师','男','熟悉各种药材的医师，善于炮制各种上好药材。','商店',''),(39,'‹武器店›铁匠师傅','男','银杏村铁匠铺的掌柜兼伙计。所打制的兵刃虽失于精巧，却价格实惠，童叟无欺，是初学者的最佳选择。 ','武器店',''),(40,'‹棉布店›福氏','女','银杏村落买卖衣服头盔、修理衣服头盔、制作宝甲。','',''),(41,'‹传说›神秘人',' ??','   身着黑色长袍与面具，蓝色的瞳孔夺人心魄。','','');
/*!40000 ALTER TABLE `npc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc_override`
--

DROP TABLE IF EXISTS `npc_override`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `npc_override` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'NPC override id',
  `player_id` int(11) NOT NULL DEFAULT 0,
  `npc_id` int(11) NOT NULL COMMENT '所属 NPC 编号',
  `name` text NOT NULL COMMENT 'NPC昵称',
  `sex` char(10) NOT NULL COMMENT 'NPC性别',
  `info` text NOT NULL COMMENT 'NPC信息',
  `muban` varchar(255) NOT NULL DEFAULT '' COMMENT 'NPC模板',
  `taskid` varchar(255) NOT NULL DEFAULT '' COMMENT 'NPC任务',
  `nowmid` int(11) DEFAULT 0 COMMENT 'NPC 当前位置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc_override`
--

LOCK TABLES `npc_override` WRITE;
/*!40000 ALTER TABLE `npc_override` DISABLE KEYS */;
/*!40000 ALTER TABLE `npc_override` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc_override_rel`
--

DROP TABLE IF EXISTS `npc_override_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `npc_override_rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关系编号',
  `player_id` int(11) NOT NULL COMMENT '角色编号',
  `npc_id` int(11) NOT NULL COMMENT 'NPC 编号',
  `npc_override_id` int(11) NOT NULL COMMENT 'NPC 覆盖状态',
  `mid` int(11) NOT NULL DEFAULT 0 COMMENT 'npc新位置',
  PRIMARY KEY (`id`),
  KEY `uni_idx` (`player_id`,`npc_id`,`npc_override_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc_override_rel`
--

LOCK TABLES `npc_override_rel` WRITE;
/*!40000 ALTER TABLE `npc_override_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `npc_override_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc_shop_item`
--

DROP TABLE IF EXISTS `npc_shop_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `npc_shop_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `npc_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'NPC编号',
  `item_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '物品编号',
  `item_type` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '物品类型',
  `item_sub_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '物品子分类',
  `is_launched` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否已上架',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='NPC 售卖物品列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc_shop_item`
--

LOCK TABLES `npc_shop_item` WRITE;
/*!40000 ALTER TABLE `npc_shop_item` DISABLE KEYS */;
INSERT INTO `npc_shop_item` VALUES (1,15,3,3,0,0),(2,15,2,2,0,0),(3,35,21,1,2,0),(4,35,22,1,2,1),(5,35,23,1,2,1),(6,35,24,1,2,0),(7,35,25,1,2,0),(8,35,26,1,2,1),(9,35,27,1,2,0),(10,35,28,1,2,1),(11,35,29,1,2,1),(12,35,30,1,2,1),(13,35,31,1,2,0),(14,35,32,1,2,0),(15,39,117,2,0,1),(16,38,16,3,0,1),(17,38,109,3,0,1),(18,36,118,1,0,1),(19,38,124,3,0,1);
/*!40000 ALTER TABLE `npc_shop_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation`
--

DROP TABLE IF EXISTS `operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '显示名称',
  `notes` varchar(100) NOT NULL DEFAULT '' COMMENT '备忘',
  `cmd` varchar(100) NOT NULL DEFAULT '' COMMENT '执行链接',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '操作类型，1查看，需要对象编号',
  `condition` int(11) NOT NULL DEFAULT 0 COMMENT '显示条件',
  `message` varchar(30) NOT NULL DEFAULT '' COMMENT '操作完成后设置消息提示',
  `area_id` int(11) NOT NULL DEFAULT 0 COMMENT '副本编号，属性副本的操作需要设置此选项',
  `new_tasks` varchar(100) NOT NULL DEFAULT '' COMMENT '触发新任务',
  `inc_identity` varchar(100) NOT NULL DEFAULT '' COMMENT '增加私有物品中的操作标识',
  `get_items` varchar(100) NOT NULL DEFAULT '' COMMENT '获取物品',
  `lose_items` varchar(100) NOT NULL DEFAULT '' COMMENT '失去物品',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='操作';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation`
--

LOCK TABLES `operation` WRITE;
/*!40000 ALTER TABLE `operation` DISABLE KEYS */;
INSERT INTO `operation` VALUES (1,'检查遗骸','','cmd=task&rwid=7',1,3,'',0,'7','','',''),(2,'查看藤甲','','cmd=task&rwid=8',1,4,'',0,'8','','',''),(3,'查看藤甲后触发任务','','',1,0,'',0,'9','','',''),(4,'触发学习功法剧情','','cmd=task&rwid=10',1,0,'',0,'10','','',''),(5,'打开道具页面','','cmd=getbagdj',1,0,'查看葵花炼神经，学习新的功法',0,'0','','',''),(6,'撑竹筏去小岛','','cmd=gomid&newmid=233',1,12,'',0,'0','','',''),(7,'撑筏回村','','cmd=gomid&newmid=231',1,13,'',0,'0','','',''),(8,'上山','','cmd=gomid&newmid=289',1,6,'',0,'0','','',''),(9,'拉动机关','','cmd=gomid',1,7,'从吊桥方向传来了声响，似乎触发了什么。',23,'0','副本_巨阙帮_吊桥机关_左','',''),(11,'拉动机关','','cmd=gomid',1,8,'从吊桥方向传来了声响，似乎触发了什么。',23,'0','副本_巨阙帮_吊桥机关_右','',''),(12,'打开','巨阙副本-打开铜箱子','cmd=gomid',1,10,'',23,'0','副本_巨阙帮_铜箱子','13|100|1','11|100|1'),(13,'打开','巨阙副本-打开铁箱子','cmd=gomid',1,11,'箱子空空如也',23,'0','副本_巨阙帮_铁箱子','','10|100|1'),(14,'挖矿','比奇矿区-挖矿','cmd=ornament-mining',0,0,'',0,'','','',''),(15,'使用卷轴','尸王殿传送','cmd=move-to-mid&mid=423',0,0,'',0,'','','','125|100|1'),(16,'前往','传送沃玛二层','cmd=move-to-mid&mid=481',0,0,'',0,'','','',''),(17,'使用卷轴','沃玛三层传送','cmd=move-to-mid&mid=483',0,0,'',0,'','','','145|100|1'),(18,'前往','前往祖玛二层','cmd=move-to-mid&mid=557',0,0,'',0,'','','',''),(19,'传送到银杏村','传送到银杏村','cmd=move-to-mid&mid=307',0,0,'一阵眩晕后你苏醒了过来',0,'','','','');
/*!40000 ALTER TABLE `operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ornament`
--

DROP TABLE IF EXISTS `ornament`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ornament` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `info` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `operations` varchar(100) NOT NULL DEFAULT '' COMMENT '操作列表',
  `show_condition` int(11) NOT NULL DEFAULT 0 COMMENT '显示条件',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='摆件，其他物品';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ornament`
--

LOCK TABLES `ornament` WRITE;
/*!40000 ALTER TABLE `ornament` DISABLE KEYS */;
INSERT INTO `ornament` VALUES (1,'青羽遗骸','一只巨大妖兽的遗骸','1',0),(2,'竹筏','静静靠在岸边的竹筏，可以用它渡过小湖。','6,7',0),(4,'上山小路','前往巨厥帮的唯一路径','8',0),(5,'机关(左)','木制的机关把手','9',0),(6,'机关(右)','木制的机关把手','11',0),(7,'铜箱子','精致的铜箱子，需要对应的钥匙才能打开','12',0),(8,'铁箱子','精致的铁箱子，需要对应的钥匙才能打开','13',0),(10,'矿石','藏在泥土中的矿石，需要用工具挖出后才能分辨。','14',14),(11,'沃玛二层','沃玛二层入口','16',0),(12,'祖玛二层','进入祖玛二层','18',0);
/*!40000 ALTER TABLE `ornament` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peifang`
--

DROP TABLE IF EXISTS `peifang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peifang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(25) NOT NULL COMMENT ' 配方名称',
  `description` varchar(100) DEFAULT '' COMMENT '配方说明',
  `ingredients` varchar(100) NOT NULL COMMENT '配方材料',
  `product` int(11) NOT NULL COMMENT '配方成品',
  `others` varchar(100) DEFAULT '' COMMENT '其他副产物',
  `min_rate` tinyint(4) NOT NULL DEFAULT 0 COMMENT '最低成功率',
  `max_rate` tinyint(4) NOT NULL DEFAULT 100 COMMENT '最高成功率',
  `up_rate` tinyint(3) unsigned NOT NULL DEFAULT 5 COMMENT '升级速度',
  `type` tinyint(3) unsigned NOT NULL DEFAULT 3 COMMENT '配方分类，1装备，2道具，3药品，4符篆',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='各种技能配方';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peifang`
--

LOCK TABLES `peifang` WRITE;
/*!40000 ALTER TABLE `peifang` DISABLE KEYS */;
INSERT INTO `peifang` VALUES (1,'还元丹-配方','炼制还元丹的配方','6|1,1|1',3,'',20,100,5,3),(2,'野兽皮甲-配方','制作野兽皮甲的配方','15|3,16|1',49,'',20,100,5,1),(3,'蜂毒精华-配方','从硬翅蜂尾针中提取毒液的方法。','4|2',6,'',20,100,5,2);
/*!40000 ALTER TABLE `peifang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_combat_condition`
--

DROP TABLE IF EXISTS `player_combat_condition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_combat_condition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '玩家编号',
  `target` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '目标类型，0无，1己方，2敌方',
  `target_num` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '目标数量',
  `target_num_op` varchar(5) NOT NULL DEFAULT '' COMMENT '目标数量比较',
  `target_mode` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '选择模式，0无: 0无，1轮数，2间隔，1己方:1自己',
  `target_property` varchar(30) NOT NULL DEFAULT '' COMMENT '对象属性',
  `operation` char(5) NOT NULL DEFAULT '' COMMENT '比较操作',
  `num` int(11) NOT NULL DEFAULT 0 COMMENT '对比数值',
  `selection_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '选择类型，1技能，2物品',
  `selection_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '选择编号',
  `sequence` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `player_combat_condition_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='玩家战斗策略';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_combat_condition`
--

LOCK TABLES `player_combat_condition` WRITE;
/*!40000 ALTER TABLE `player_combat_condition` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_combat_condition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_effects`
--

DROP TABLE IF EXISTS `player_effects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL COMMENT '角色编号',
  `desc` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `column` varchar(30) NOT NULL DEFAULT '' COMMENT '修正的字段',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT '修正数值',
  `effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '修正类型，1乘法，2加法',
  `target` tinyint(4) NOT NULL DEFAULT 1 COMMENT '技能目标，0攻击参数，1敌方单位，2己方单位',
  `turns` tinyint(4) NOT NULL DEFAULT 1 COMMENT '持续回合，默认1',
  `effect_turn` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否当前回合生效，1当前回合生效，2 下回合',
  `is_column` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否是属性修正参数',
  `is_wushang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理伤害修正参数',
  `is_wumian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理免疫修正参数',
  `is_fashang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法术伤害修正参数',
  `is_famian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法伤免疫修正参数',
  `is_mingzhong` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是命中率影响参数',
  `is_shanbi` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是闪避率修正参数',
  `is_baoji` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是暴击率修正参数',
  `is_shenming` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是抗暴率修正参数',
  `is_dot` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是独立计算的DOT效果',
  `is_temporary` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否限时',
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT '持续时间，秒',
  `is_combat` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否可以在战斗中生效',
  `is_custom` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是其他用途的效果',
  `end_at` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`),
  KEY `player_effects_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_effects`
--

LOCK TABLES `player_effects` WRITE;
/*!40000 ALTER TABLE `player_effects` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_effects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_equip_info`
--

DROP TABLE IF EXISTS `player_equip_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_equip_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `ui_name` varchar(100) NOT NULL DEFAULT '' COMMENT '显示名称',
  `item_id` int(11) NOT NULL DEFAULT 0 COMMENT '物品表编号',
  `level` int(11) NOT NULL DEFAULT 1 COMMENT '等级',
  `hp` int(11) NOT NULL DEFAULT 0 COMMENT '气血',
  `mp` int(11) NOT NULL DEFAULT 0 COMMENT '灵气',
  `baqi` int(11) NOT NULL DEFAULT 0 COMMENT '霸气',
  `wugong` int(11) NOT NULL DEFAULT 0 COMMENT '物理攻击',
  `fagong` int(11) NOT NULL DEFAULT 0 COMMENT '法术攻击',
  `wufang` int(11) NOT NULL DEFAULT 0 COMMENT '物理防御',
  `fafang` int(11) NOT NULL DEFAULT 0 COMMENT '法术防御',
  `shanbi` int(11) NOT NULL DEFAULT 0 COMMENT '闪避',
  `mingzhong` int(11) NOT NULL DEFAULT 0 COMMENT '命中',
  `baoji` int(11) NOT NULL DEFAULT 0 COMMENT '暴击',
  `shenming` int(11) NOT NULL DEFAULT 0 COMMENT '神明',
  `equip_type` int(11) NOT NULL COMMENT '部位1武器2头饰3衣服4腰带5首饰6鞋子7法宝',
  `shengxing` int(11) NOT NULL DEFAULT 0 COMMENT '升星次数',
  `qianghua` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '强化次数',
  `manual_id` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '职业限制，0通用，6战士7法师8道士',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '性别0通用1男2女',
  `quality_hp` int(11) NOT NULL DEFAULT 0 COMMENT '气血',
  `quality_mp` int(11) NOT NULL DEFAULT 0 COMMENT '灵气',
  `quality_baqi` int(11) NOT NULL DEFAULT 0 COMMENT '霸气',
  `quality_wugong` int(11) NOT NULL DEFAULT 0 COMMENT '物理攻击',
  `quality_fagong` int(11) NOT NULL DEFAULT 0 COMMENT '法术攻击',
  `quality_wufang` int(11) NOT NULL DEFAULT 0 COMMENT '物理防御',
  `quality_fafang` int(11) NOT NULL DEFAULT 0 COMMENT '法术防御',
  `quality_shanbi` int(11) NOT NULL DEFAULT 0 COMMENT '闪避',
  `quality_mingzhong` int(11) NOT NULL DEFAULT 0 COMMENT '命中',
  `quality_baoji` int(11) NOT NULL DEFAULT 0 COMMENT '暴击',
  `quality_shenming` int(11) NOT NULL DEFAULT 0 COMMENT '神明',
  `quality` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '装备品质',
  `source_location` varchar(30) NOT NULL DEFAULT '' COMMENT '装备获取地点',
  `source_monster` varchar(30) NOT NULL DEFAULT '' COMMENT '装备获取怪物',
  `source_player` varchar(10) NOT NULL DEFAULT '' COMMENT '装备获取玩家',
  `source_timestamp` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '装备获取时间',
  PRIMARY KEY (`id`),
  KEY `player_equip_info_item_id_IDX` (`item_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='装备信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_equip_info`
--

LOCK TABLES `player_equip_info` WRITE;
/*!40000 ALTER TABLE `player_equip_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_equip_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_equip_keyword`
--

DROP TABLE IF EXISTS `player_equip_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_equip_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `item_id` int(11) NOT NULL DEFAULT 0 COMMENT '物品表编号',
  `info` varchar(30) NOT NULL DEFAULT '' COMMENT '效果名称',
  `ui_info` varchar(100) NOT NULL DEFAULT '' COMMENT '带样式的显示效果',
  `column` varchar(30) NOT NULL DEFAULT '' COMMENT '修正的字段',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT '修正数值',
  `effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '修正类型，1乘法，2加法',
  `target` tinyint(4) NOT NULL DEFAULT 1 COMMENT '效果目标，1装备自身属性，2人物总属性',
  `is_column` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否是属性修正参数',
  `is_wushang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理伤害修正参数',
  `is_wumian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理免疫修正参数',
  `is_fashang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法术伤害修正参数',
  `is_famian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法伤免疫修正参数',
  `is_mingzhong` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是命中率影响参数',
  `is_shanbi` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是闪避率修正参数',
  `is_baoji` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是暴击率修正参数',
  `is_shenming` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是抗暴率修正参数',
  `is_dot` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是独立计算的DOT效果',
  `is_custom` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是其他用途的效果',
  `custom_effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '自定义效果的影响方式，1增加效果2移除效果',
  `identity` varchar(20) NOT NULL DEFAULT '' COMMENT '效果标识，用于重复判断',
  `is_unique` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否唯一效果',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='玩家装备关键键字';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_equip_keyword`
--

LOCK TABLES `player_equip_keyword` WRITE;
/*!40000 ALTER TABLE `player_equip_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_equip_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_event`
--

DROP TABLE IF EXISTS `player_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '角色编号',
  `cmd` varchar(255) NOT NULL DEFAULT '' COMMENT '事件cmd',
  `white_list` varchar(255) NOT NULL DEFAULT '' COMMENT '拦截白名单',
  `is_temporary` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否是一次性事件',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `player_event_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户事件栈';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_event`
--

LOCK TABLES `player_event` WRITE;
/*!40000 ALTER TABLE `player_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_item`
--

DROP TABLE IF EXISTS `player_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户编号',
  `item_id` int(11) NOT NULL DEFAULT 0 COMMENT '道具表编号',
  `sub_item_id` int(11) NOT NULL DEFAULT 0 COMMENT '详细信息表编号',
  `amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '物品数量',
  `storage` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '物品保存位置，1背包，2身上，3仓库',
  `is_bound` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否绑定',
  PRIMARY KEY (`id`),
  KEY `player_item_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_item`
--

LOCK TABLES `player_item` WRITE;
/*!40000 ALTER TABLE `player_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_manual`
--

DROP TABLE IF EXISTS `player_manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户编号',
  `manual_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法编号',
  `manual_level_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法等级',
  `max_manual_level_id` int(11) NOT NULL DEFAULT 0 COMMENT '最高功法等级编号',
  `level` int(11) NOT NULL DEFAULT 1 COMMENT '功法等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色功法';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_manual`
--

LOCK TABLES `player_manual` WRITE;
/*!40000 ALTER TABLE `player_manual` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_manual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_party`
--

DROP TABLE IF EXISTS `player_party`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_party` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '队伍名称',
  `uid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '队长编号',
  `is_closed` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否关闭入队申请',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `player_party_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='队伍信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_party`
--

LOCK TABLES `player_party` WRITE;
/*!40000 ALTER TABLE `player_party` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_party` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_party_member`
--

DROP TABLE IF EXISTS `player_party_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_party_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `party_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '队伍编号',
  `uid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户编号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '入队状态，0申请中，1自由活动，2跟随',
  `is_leader` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否是队长',
  PRIMARY KEY (`id`),
  KEY `player_party_member_party_id_IDX` (`party_id`) USING BTREE,
  KEY `player_party_member_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='队伍成员信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_party_member`
--

LOCK TABLES `player_party_member` WRITE;
/*!40000 ALTER TABLE `player_party_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_party_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_peifang`
--

DROP TABLE IF EXISTS `player_peifang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_peifang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(25) NOT NULL COMMENT '配方名称',
  `peifang_id` int(11) NOT NULL COMMENT '配方编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `proficiency` tinyint(3) unsigned DEFAULT 0 COMMENT '配方熟练度',
  `rate` tinyint(3) unsigned DEFAULT 5 COMMENT '熟练度增长率',
  PRIMARY KEY (`id`),
  KEY `player_peifang_uid_IDX` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_peifang`
--

LOCK TABLES `player_peifang` WRITE;
/*!40000 ALTER TABLE `player_peifang` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_peifang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_pet`
--

DROP TABLE IF EXISTS `player_pet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_pet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `level` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `gid` int(11) NOT NULL DEFAULT 0 COMMENT '怪物原型编号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '怪物所属玩家编号',
  `exp` int(11) NOT NULL,
  `max_exp` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '升级经验',
  `hp` int(11) NOT NULL DEFAULT 10 COMMENT '气血',
  `mp` int(11) NOT NULL DEFAULT 10 COMMENT '灵气',
  `maxhp` int(11) NOT NULL DEFAULT 10 COMMENT '最大气血',
  `maxmp` int(11) NOT NULL DEFAULT 10 COMMENT '最大灵气',
  `baqi` int(11) NOT NULL DEFAULT 10 COMMENT '霸气',
  `wugong` int(11) NOT NULL DEFAULT 10 COMMENT '物理攻击',
  `fagong` int(11) NOT NULL DEFAULT 10 COMMENT '法术攻击',
  `wufang` int(11) NOT NULL DEFAULT 10 COMMENT '物理防御',
  `fafang` int(11) NOT NULL DEFAULT 10 COMMENT '法术防御',
  `shanbi` int(11) NOT NULL DEFAULT 13 COMMENT '闪避',
  `mingzhong` int(11) NOT NULL DEFAULT 10 COMMENT '命中',
  `baoji` int(11) NOT NULL DEFAULT 10 COMMENT '暴击',
  `shenming` int(11) NOT NULL DEFAULT 67 COMMENT '神明',
  `is_born` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否已孵化',
  `quality` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '品质',
  `player_skill_id` int(10) unsigned NOT NULL COMMENT '被召唤的技能编号',
  `is_out` tinyint(3) unsigned NOT NULL COMMENT '是否已出战',
  `active_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '怪物活跃时间，过期自动删除',
  `skills` varchar(100) NOT NULL DEFAULT '' COMMENT '宠物技能编号列表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户宠物';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_pet`
--

LOCK TABLES `player_pet` WRITE;
/*!40000 ALTER TABLE `player_pet` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_pet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_private_items`
--

DROP TABLE IF EXISTS `player_private_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_private_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `type` tinyint(4) NOT NULL COMMENT '记录类型,1私有怪刷新时间2杀怪记录3操作标识',
  `k` varchar(100) NOT NULL DEFAULT '' COMMENT '键名',
  `v` varchar(100) NOT NULL DEFAULT '' COMMENT ' 值',
  `area_id` int(11) NOT NULL DEFAULT 0 COMMENT '地区编号，可能用作副本相关信息',
  `extra` varchar(100) NOT NULL DEFAULT '' COMMENT '备用字段',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `player_private_items_uid_IDX` (`uid`,`type`,`k`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='玩家杀过的一次性怪';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_private_items`
--

LOCK TABLES `player_private_items` WRITE;
/*!40000 ALTER TABLE `player_private_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_private_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_relationship`
--

DROP TABLE IF EXISTS `player_relationship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_relationship` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户编号',
  `tid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '对象编号',
  `type` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '关系类型，1好友，2仇人，3黑名单',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_relationship`
--

LOCK TABLES `player_relationship` WRITE;
/*!40000 ALTER TABLE `player_relationship` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_relationship` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_skill`
--

DROP TABLE IF EXISTS `player_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '玩家编号',
  `skill_id` int(11) NOT NULL DEFAULT 0 COMMENT '技能编号',
  `level` tinyint(4) NOT NULL DEFAULT 1 COMMENT '技能当前等级，待用',
  `manual_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法编号',
  `score` int(11) NOT NULL DEFAULT 0 COMMENT '熟练度',
  `max_score` int(10) unsigned NOT NULL DEFAULT 600 COMMENT '升级经验',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='玩家技能';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_skill`
--

LOCK TABLES `player_skill` WRITE;
/*!40000 ALTER TABLE `player_skill` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_task`
--

DROP TABLE IF EXISTS `player_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '角色编号',
  `task_id` int(11) NOT NULL DEFAULT 0 COMMENT '任务编号',
  `task_info_id` int(11) NOT NULL DEFAULT 0 COMMENT '任务信息编号',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '任务状态，1可提交2不可提交3已完成',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '最后更新时间',
  PRIMARY KEY (`id`),
  KEY `player_task_uid_IDX` (`uid`,`task_id`,`task_info_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色任务';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_task`
--

LOCK TABLES `player_task` WRITE;
/*!40000 ALTER TABLE `player_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_task_condition`
--

DROP TABLE IF EXISTS `player_task_condition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_task_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户编号',
  `player_task_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户任务编号',
  `task_id` int(11) NOT NULL DEFAULT 0 COMMENT '任务编号',
  `task_info_id` int(11) NOT NULL DEFAULT 0 COMMENT '任务信息编号',
  `condition_id` int(11) NOT NULL DEFAULT 0 COMMENT '条件编号，比如物品编号，怪物编号',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT '条件数量',
  `required_amount` int(11) NOT NULL DEFAULT 0 COMMENT '要求达到数量',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '条件类型，1物品2怪物',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='任务条件相关记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_task_condition`
--

LOCK TABLES `player_task_condition` WRITE;
/*!40000 ALTER TABLE `player_task_condition` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_task_condition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pve_logs`
--

DROP TABLE IF EXISTS `pve_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pve_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '战斗结果编号',
  `attackers` varchar(255) NOT NULL COMMENT '角色方信息',
  `defenders` varchar(255) NOT NULL COMMENT '防守方信息',
  `mid` int(11) NOT NULL COMMENT '场景编号',
  `target` varchar(30) NOT NULL DEFAULT '' COMMENT '对象名字',
  `status` tinyint(4) NOT NULL COMMENT '战斗结果，1胜利，0死亡，-1重伤，-2逃跑,-3抢怪失败',
  `notes` text DEFAULT NULL COMMENT '记录备注',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '战斗类型，1pve 2pvp',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=467 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pve_logs`
--

LOCK TABLES `pve_logs` WRITE;
/*!40000 ALTER TABLE `pve_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `pve_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qy`
--

DROP TABLE IF EXISTS `qy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qy` (
  `qyid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qyname` varchar(255) NOT NULL,
  `mid` int(11) NOT NULL,
  `teleport` int(11) DEFAULT 0 COMMENT '传送点',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '区域类型，1城市２野外３副本',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '说明',
  `is_portal` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否可传送',
  `is_launched` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否上架',
  PRIMARY KEY (`qyid`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qy`
--

LOCK TABLES `qy` WRITE;
/*!40000 ALTER TABLE `qy` DISABLE KEYS */;
INSERT INTO `qy` VALUES (29,'比奇城',350,350,1,'',0,1),(30,'比奇矿区',307,351,2,'(等级 10~20)',1,1),(27,'银杏村',307,307,1,'',1,1),(28,'银杏山谷野外',307,330,2,'(等级 1~10)',1,1),(31,'死亡山谷',307,401,2,'(等级 20~30)',1,1),(32,'尸王殿',307,423,3,'(等级 30~35)',0,0),(33,'沃玛寺庙',307,444,2,'(等级 30~40)',1,1),(34,'祖玛神殿',307,479,2,'(等级 35~45)',1,1),(35,'赤月峡谷',307,480,2,'(等级 45~55)',1,1),(36,'沃玛二层',307,481,2,'(等级 30~40)',0,1),(37,'沃玛三层',307,483,2,'(等级 35~40)',0,1),(38,'祖玛二层',307,557,2,'(等级 35~45)',0,1),(39,'时空裂缝',566,566,1,'',0,1);
/*!40000 ALTER TABLE `qy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `renwu`
--

DROP TABLE IF EXISTS `renwu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renwu` (
  `rwid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务ID',
  `rwname` varchar(255) NOT NULL COMMENT '任务名称',
  `rwzl` int(11) NOT NULL COMMENT '任务种类',
  `rwdj` varchar(255) NOT NULL DEFAULT '' COMMENT '奖励道具',
  `rwzb` varchar(255) NOT NULL DEFAULT '' COMMENT '奖励装备',
  `rwexp` int(11) NOT NULL DEFAULT 0 COMMENT '奖励经验',
  `rwyxb` varchar(255) NOT NULL DEFAULT '' COMMENT '奖励游戏币',
  `rwyq` int(11) NOT NULL DEFAULT 0 COMMENT '任务要求',
  `rwinfo` varchar(255) NOT NULL COMMENT '任务信息',
  `rwinfo2` varchar(255) NOT NULL COMMENT '任务可提交时显示的任务信息',
  `rwcount` int(11) NOT NULL DEFAULT 1 COMMENT '要求数量',
  `rwlx` tinyint(4) NOT NULL COMMENT '任务类型',
  `rwyp` varchar(255) NOT NULL DEFAULT '' COMMENT '奖励药品',
  `lastrwid` int(11) NOT NULL DEFAULT 0 COMMENT '上个任务关联',
  `rwjineng` varchar(255) NOT NULL DEFAULT '',
  `npc_override` varchar(255) NOT NULL DEFAULT '' COMMENT 'NPC 覆盖信息',
  `update_npc_override` varchar(255) NOT NULL DEFAULT '' COMMENT '提交任务时清除 NPC 覆盖',
  PRIMARY KEY (`rwid`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `renwu`
--

LOCK TABLES `renwu` WRITE;
/*!40000 ALTER TABLE `renwu` DISABLE KEYS */;
INSERT INTO `renwu` VALUES (13,'山猪祸乱',2,'1|5','24',100,'120',56,'最近山猪下山,扰乱了我们的生活,请帮我们赶跑他们','',5,2,'6|3',0,'','',''),(14,'收集蜂蜜',1,'1|5','23',200,'100',8,'收集硬翅蜂的蜂蜜','',5,2,'6|3',0,'','',''),(19,'蛮!',2,'1|30,9|50','38',500,'500',76,'魔道对这片大地始终不死心,诱惑了我们很多族人入魔了,希望你能够解救他们','',10,1,'',-1,'','',''),(20,'杀!',2,'1|50,9|50','39',600,'400',77,'我好怕,帮我杀了他们!!!','',10,1,'',-1,'','',''),(21,'赤鳞兽皮',2,'1|10,10|50','39',800,'350',82,'部落现在缺少大量兽皮过冬','',5,2,'',-1,'','',''),(24,'找王大妈',3,'1|20','25',200,'100',11,'找王大妈','',18,3,'6|10',25,'','',''),(25,'硬翅蜂扰',2,'1|15,6|100,7|100','',200,'150',55,'硬翅蜂扰','',5,3,'',36,'','',''),(27,'屠尽妖王',1,'','45',2000,'2000',12,'屠尽妖王','',150,1,'9|5',-1,'','',''),(28,'故人',3,'1|50','29',400,'200',11,'故人','',14,1,'6|10',-1,'','',''),(29,'狼患',2,'1|100','',400,'300',62,'狼患成灾，帮帮我们','',5,3,'',24,'','',''),(30,'焦急的桂香-求医',3,'','',400,'',28,'小萍早上怎么也叫不醒，失了魂一样，我一个人走不开，你能帮我去找一下村里的郎中吗？','陈家小萍生病了，让您去看看。',27,3,'',-1,'','',''),(32,'焦急的桂香-解药',1,'','',400,'',13,'昏睡是被硬翅蜂螫伤中毒的症状，解药还需要从硬翅蜂身上提取，你快去帮我收集一些硬翅蜂的蜂蜜和尾针，完成后直接送到小萍家。','来得还算及时，只要这些调制出解药，很快就能醒来了。',2,3,'',30,'','27|1|274','27|0|0'),(33,'焦急的桂香-感谢',3,'14|1','',400,'',28,'真是谢谢你，看着小萍吃了药就安心多了。刚才光顾着给小萍熬药喂药，老先生走了都没有发现。你能再帮我把诊金送过去吗？','只要孩子没事就好，身外之物不重要。村外面的野兽越来越多了，就连不出村小孩子都可能被它们伤害，你们年轻人经常在外走动，可要当心呢。这里是我的手札，也许你能用得上。',27,3,'',32,'','',''),(35,'小萍在哪里',3,'','',100,'0',30,'昨天和小萍说好早上在这里等的，现在都还没来，你能帮我去她家看看吗？','......',29,3,'',0,'','',''),(36,'村里的困难',3,'','',100,'0',11,'大侠的武功果然很高强，之前我们也雇过一些江湖人士去驱除村外的野兽，可惜......不知道大侠愿意帮助我们吗？','有什么问题请尽管说！',11,3,'',33,'','','');
/*!40000 ALTER TABLE `renwu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skill_effects`
--

DROP TABLE IF EXISTS `skill_effects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skill_effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '技能效果编号',
  `info` varchar(30) NOT NULL DEFAULT '' COMMENT '效果名称',
  `ui_info` varchar(100) NOT NULL DEFAULT '' COMMENT '带样式的显示效果',
  `skill_id` int(11) NOT NULL COMMENT '技能编号',
  `column` varchar(30) NOT NULL DEFAULT '' COMMENT '修正的字段',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT '修正数值',
  `effect_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '修正类型，1乘法，2加法',
  `turns` tinyint(4) NOT NULL DEFAULT 1 COMMENT '持续回合，默认1',
  `effect_turn` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否当前回合生效，1当前回合生效，2 下回合',
  `is_column` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否是属性修正参数',
  `is_wushang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理伤害修正参数',
  `is_wumian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是物理免疫修正参数',
  `is_fashang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法术伤害修正参数',
  `is_famian` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是法伤免疫修正参数',
  `is_mingzhong` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是命中率影响参数',
  `is_shanbi` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是闪避率修正参数',
  `is_baoji` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是暴击率修正参数',
  `is_shenming` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是抗暴率修正参数',
  `is_dot` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是独立计算的DOT效果',
  `is_raw` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否直接作用于源字段，比如直接增加HP',
  `attack_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '攻击类型，0攻击参数，1物攻，2法攻',
  `target` tinyint(4) NOT NULL DEFAULT 1 COMMENT '技能目标，0攻击参数，1敌方单位，2己方单位',
  `target_num` tinyint(4) NOT NULL DEFAULT 1 COMMENT '目标数量',
  `target_mode` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1自身，2随机，3全体目标，暂时只支持随机',
  `combo` tinyint(4) NOT NULL DEFAULT 1 COMMENT '连击次数',
  `identity` varchar(20) NOT NULL DEFAULT '' COMMENT '效果标识，用于重复判断',
  `is_unique` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否唯一效果',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COMMENT='技能效果';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skill_effects`
--

LOCK TABLES `skill_effects` WRITE;
/*!40000 ALTER TABLE `skill_effects` DISABLE KEYS */;
INSERT INTO `skill_effects` VALUES (1,'三阳真火','<span class=\"text-green-500 font-bold\">三阳真火</span>',1,'wugong',100,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,'',0),(2,'剑气袭身','<span class=\"text-purple-500 font-bold\">剑气袭身</span>',4,'wugong',40,1,3,1,1,0,0,0,0,0,0,0,0,1,0,1,1,1,1,1,'',0),(5,'会心一击','<span class=\"text-red-500 font-bold\">会心一击</span>',1,'',50,2,1,1,0,0,0,0,0,0,0,1,0,0,0,0,0,1,1,1,'',0),(6,'攻击','攻击',2,'wugong',80,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,2,1,'',0),(7,'普通攻击','普通攻击',3,'wugong',50,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,2,1,'',0),(8,'看朱成碧','<span class=\"text-purple-500 font-bold\"><span class=\"text-purple-500 font-bold\">看朱成碧</span></span>',1,'',-50,2,1,1,0,0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,'',0),(9,'凝珀诀','<span class=\"text-green-500 font-bold\">凝珀诀</span>',4,'fagong',100,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,1,1,1,1,'',0),(10,'挥砍','挥砍',5,'wugong',60,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,'',0),(11,'普通攻击','普通攻击',6,'wugong',50,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,'',0),(12,'爪击','爪击',7,'wugong',60,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,'',0),(13,'冲撞','冲撞',8,'wugong',100,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,'',0),(14,'灵魂火符','灵魂火符',9,'fagong',80,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,1,1,2,1,'',0),(15,'半月弯刀','半月弯刀',10,'wugong',70,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,3,2,1,'',0),(16,'基本剑术','基本剑术',11,'wugong',80,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,2,1,'',0),(17,'凝神静气','凝神静气',11,'mingzhong',10,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,1,1,1,'',0),(18,'攻杀剑术','攻杀剑术',12,'wugong',90,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,2,1,'',0),(19,'刺杀剑术','刺杀剑术',13,'wugong',100,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,2,1,'',0),(20,'刺杀破防','刺杀破防',13,'wufang',-10,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,2,1,'',0),(21,'神圣战甲术','神圣战甲术',15,'wufang',20,1,3,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,3,1,'',0),(22,'神圣战甲术','神圣战甲术',15,'fafang',20,1,3,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,3,1,'',0),(23,'施毒术','施毒术',16,'wufang',-20,1,3,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,2,1,'',0),(24,'施毒术','施毒术',16,'fafang',-20,1,3,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,2,1,'',0),(25,'大火球','大火球',17,'fagong',80,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,1,1,2,1,'',0),(26,'雷电术','雷电术',18,'fagong',100,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,1,1,2,1,'',0),(27,'火墙','火墙',19,'fagong',70,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,1,4,2,1,'',0),(28,'魔法盾','魔法盾',20,'fafang',15,1,5,1,0,0,0,0,1,0,0,0,0,0,0,0,2,1,1,1,'',0),(29,'魔法盾','魔法盾',20,'',15,1,5,1,0,0,1,0,0,0,0,0,0,0,0,0,2,1,1,1,'',0),(30,'疾光电影','疾光电影',21,'fagong',80,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,2,0,4,1,'',0),(31,'烈火剑法','烈火剑法',22,'wugong',120,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,2,1,'',0),(32,'冰咆哮','冰咆哮',23,'fagong',110,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,1,1,3,1,'',0),(33,'烈焰吐息','<span class=\"color-red\">烈焰吐息</span>',25,'fagong',80,1,1,1,1,0,0,0,0,0,0,0,0,0,0,2,2,1,2,1,'',0);
/*!40000 ALTER TABLE `skill_effects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '技能编号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '技能名称',
  `info` varchar(100) NOT NULL DEFAULT '' COMMENT '技能描述',
  `manual_id` int(11) NOT NULL DEFAULT 0 COMMENT '所属功法编号',
  `level` int(11) NOT NULL DEFAULT 0 COMMENT '等级要求',
  `tiaoxi` tinyint(4) NOT NULL DEFAULT 0 COMMENT '公共技能调息回合数',
  `equip_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '施展技能需要的武器类型，0无限制，1剑',
  `manual_level_id` int(11) NOT NULL DEFAULT 0 COMMENT '功法等级要求',
  `sequence` tinyint(4) NOT NULL DEFAULT 1 COMMENT '功法境界序号',
  `type` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '技能类型，1物攻，2法攻，3辅助技能',
  `in_combat` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '是否可以在战斗中使用',
  `outside_combat` tinyint(3) unsigned DEFAULT 0 COMMENT '是否可以在战斗外使用',
  `event` varchar(100) NOT NULL DEFAULT '' COMMENT '技能自定义操作',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COMMENT='技能表信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
INSERT INTO `skills` VALUES (1,'三阳真火诀','剑气燃烧劈向对手，并附带剑气划伤效果，持续3回合。',1,1,0,0,19,1,1,1,0,''),(2,'攻击','撕咬对方',0,1,0,0,0,1,1,1,0,''),(3,'攻击','最简单的普通攻击',0,1,0,0,18,1,1,1,0,''),(4,'凝珀诀','凝珀成型',2,1,0,0,1,1,1,1,0,''),(5,'挥砍','最简单的招式，直接使用武器攻击敌方',0,1,0,0,0,1,1,1,0,''),(6,'普通攻击','最简单的招式，直接用小拳拳锤对方胸口',0,1,0,0,0,1,1,1,0,''),(7,'爪击','用爪子攻击敌方',0,1,0,0,0,1,1,1,0,''),(8,'冲撞','用蛮力冲撞敌方',0,1,0,0,0,1,1,1,0,''),(9,'灵魂火符','使用符咒是道术士的看家本领，他们将封印了怨灵的符纸飞向敌人，符纸将会在空气中燃烧，引爆怨灵的力量攻击敌人。可以用于远距离攻击，与其它魔法相结合使用，会得到多样效果。',8,1,0,0,0,1,2,1,0,''),(10,'半月弯刀','修炼半月弯刀的基础是“内力”，使用时会发出银色的月魄，与之前所学的武功不同，半月弯刀的攻击范围要广的多，可以同时攻击附近的数个敌人。',6,20,0,0,0,1,1,1,0,''),(11,'基本剑术','基本剑术是武士的入门剑术。剑术招式是很久以前帝国的士兵们从战场上领悟到的。基本剑术学习起来非常容易，没有什么花哨的招式，属于剑术中的基本功。随着练习程度的深入，对增加攻击命中率很有帮助。',6,3,0,0,0,1,1,1,0,''),(12,'攻杀剑术','攻杀剑术也属于入门剑术，其目标是修炼爆发力，修炼时要求精神高度集中。修炼攻杀剑术到了一定境界，就能在瞬间爆发出强大的攻击力，给敌人强烈打击。',6,15,0,0,0,1,1,1,0,''),(13,'刺杀剑术','刺杀剑术是必须有一定武功基础才能够修炼的中级剑术，刺杀剑术讲求“快”、“准”、“狠”，经过刻苦修炼后，可以从出其不意的部位出手攻击敌人，达到最高境界时，甚至可以杀死数丈之外的敌人。',6,25,0,0,0,1,1,1,0,''),(14,'召唤骷髅','道术士们在长久的修炼中掌握了生死的奥秘，他们可以利用精神力，召唤并控制古代英雄的枯骨。随着被召唤次数的增加，骷髅与主人之间的信任度也会越高，当召唤术修炼到一定等级后，就会发挥出非常的威力。',8,20,0,0,0,1,3,0,1,'cmd=summon-pet&type=1&skill_id=14'),(15,'神圣战甲术','神圣战甲术是道家武功研究的延伸，通过将精神力贯注到自己或他人的战甲之上，在一段时间提升战甲的防御力。在实际战斗中，通常对冲在最前方的武士帮助最大。',8,25,0,0,0,1,3,1,0,''),(16,'施毒术','道术士对药草知识的了解十分深入，他们不仅会制作药品，还可制造毒粉，这就是施毒术的由来。目前道术士门流行使用两种毒药，黄色药粉可以降低敌人的防御力，灰色药粉可以消耗敌人的生命。',8,15,0,0,0,1,3,1,0,''),(17,'大火球','是火系魔法的进阶，可以发出大得多的火球，火焰的高温甚至可以融化钢铁。',7,1,0,0,0,1,2,1,0,''),(18,'雷电术','是雷系魔法的进阶，念动咒语引发雷电，劈向目标，雷电术的威力巨大，所以念诵咒语的时间也比较长，要小心敌人趁机偷袭。',7,15,0,0,0,1,2,1,0,''),(19,'火墙','传说此 火墙是一位怀恨去世的天才法师所创，用咒语点燃的不灭之火就跟法师心中的怨恨一样熊熊燃起，会使身处火墙中的所有敌人融化。火墙持续燃烧的时间和法师的个人法力有关系。',7,20,0,0,0,1,2,1,0,''),(20,'魔法盾','用魔力在自己周围形成保护膜，在一定的时间内减少伤害的技能。修炼等级越高，持续时间越长。',7,25,0,0,0,1,3,1,0,''),(21,'疾光电影','是雷系魔法的中级法术，疾光电影可以激射出蓝色的电光，最多可以同时攻击排成一列的所有敌人。',7,20,0,0,0,1,2,1,0,''),(22,'烈火剑法','烈火剑法是使用内功的更高境界，将胸中的怒火注入手中的武器之中，唤起刀剑的杀气，可以造成惊人的破坏力。由于消耗过大，这种武功不能连续使用，另外修炼不足的情况下，聚气成功的可能性会降低。',6,35,0,0,0,1,1,1,0,''),(23,'冰咆哮','是冰系魔法的最高境界，可以呼唤上古冰之精灵，引发冰雪暴风攻击敌人。无数冰块形成的旋风再加上刺骨的寒气，会给敌人造成致命的打击。',7,35,0,0,0,1,2,1,0,''),(24,'召唤神兽','是道家召唤术的更高境界，道术士们可以与冥界沟通，召唤守护地狱之门的神兽作为自己的仆从。被召唤的神兽疾恶如仇，嘴里喷出的地狱之火可以烧毁一切。',8,35,0,0,0,1,3,0,1,'cmd=summon-pet&type=2&skill_id=24'),(25,'烈焰吐息','神兽专用技能',8,35,0,0,0,1,2,1,0,'');
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_data`
--

DROP TABLE IF EXISTS `system_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标号',
  `level` int(11) NOT NULL DEFAULT 1 COMMENT '等级',
  `player_exp` int(11) NOT NULL DEFAULT 0 COMMENT '角色升级经验',
  `player_hp` int(11) NOT NULL DEFAULT 0 COMMENT '角色血量',
  `player_gongji` int(11) NOT NULL DEFAULT 0 COMMENT '角色攻击',
  `player_fangyu` int(11) NOT NULL DEFAULT 0 COMMENT '角色防御',
  `player_baqi` int(11) NOT NULL DEFAULT 0 COMMENT '角色霸气',
  `player_mingzhong` int(11) NOT NULL DEFAULT 0 COMMENT '角色命中',
  `player_shanbi` int(11) NOT NULL DEFAULT 0 COMMENT '角色闪避',
  `player_baoji` int(11) NOT NULL DEFAULT 0 COMMENT '角色暴击',
  `player_shenming` int(11) NOT NULL DEFAULT 0 COMMENT '角色神明',
  `monster_exp` int(11) NOT NULL DEFAULT 0 COMMENT '怪物经验',
  `monster_hp` int(11) NOT NULL DEFAULT 0 COMMENT '怪物血量',
  `monster_gongji` int(11) NOT NULL DEFAULT 0 COMMENT '怪物攻击',
  `monster_fangyu` int(11) NOT NULL DEFAULT 0 COMMENT '怪物防御',
  `monster_baqi` int(11) NOT NULL DEFAULT 0 COMMENT '怪物霸气',
  `monster_mingzhong` int(11) NOT NULL DEFAULT 0 COMMENT '怪物命中',
  `monster_shanbi` int(11) NOT NULL DEFAULT 0 COMMENT '怪物闪避',
  `monster_baoji` int(11) NOT NULL DEFAULT 0 COMMENT '怪物暴击',
  `monster_shenming` int(11) NOT NULL DEFAULT 0 COMMENT '怪物神明',
  `exp_per_min` int(11) NOT NULL DEFAULT 0 COMMENT '每分钟修炼经验',
  PRIMARY KEY (`id`),
  KEY `system_exp_level_IDX` (`level`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COMMENT='系统经验表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_data`
--

LOCK TABLES `system_data` WRITE;
/*!40000 ALTER TABLE `system_data` DISABLE KEYS */;
INSERT INTO `system_data` VALUES (1,1,30,60,10,10,10,10,13,10,67,10,40,10,10,6,10,13,10,67,15),(2,2,68,70,11,11,12,11,14,11,74,15,91,19,19,13,12,15,12,80,23),(3,3,120,80,12,12,13,12,16,12,80,20,137,27,27,21,14,18,14,91,30),(4,4,188,90,13,13,15,13,17,13,87,25,192,36,36,28,16,20,16,104,38),(5,5,450,100,14,14,17,14,18,14,94,30,238,45,45,36,17,23,17,117,45),(6,6,630,110,15,15,18,15,20,15,100,35,289,54,54,43,19,26,19,129,53),(7,7,840,120,16,16,20,16,21,16,107,40,335,63,63,51,21,28,21,141,60),(8,8,1080,130,17,17,21,17,23,17,113,45,386,71,71,58,23,31,23,153,68),(9,9,1350,140,18,18,23,18,24,18,120,50,440,80,80,65,25,33,25,166,75),(10,10,1320,150,19,19,25,19,25,19,127,55,609,88,88,73,27,35,27,178,132),(11,11,3060,160,20,20,26,20,27,20,133,85,672,97,97,80,29,38,29,190,204),(12,12,5520,170,21,21,28,21,28,21,140,115,730,107,107,88,30,41,30,203,276),(13,13,8700,180,22,22,30,22,29,22,147,145,798,115,115,95,32,43,32,216,348),(14,14,12600,190,23,23,31,23,31,23,153,175,857,124,124,103,34,46,34,227,420),(15,15,17220,200,24,24,33,24,32,24,160,205,920,132,132,122,36,48,36,240,492),(16,16,22560,220,26,26,36,26,35,26,173,235,1006,142,142,134,39,52,39,260,564),(17,17,28620,240,28,28,40,28,37,28,187,265,1093,153,153,145,42,56,42,280,636),(18,18,35400,260,30,30,43,30,40,30,200,295,1175,163,163,157,45,60,45,300,708),(19,19,42900,280,32,32,46,32,43,32,213,325,1261,173,173,168,48,64,48,320,780),(20,20,46150,300,34,34,50,34,45,34,227,355,1618,183,183,180,51,68,51,340,1065),(21,21,65600,320,36,36,53,36,48,36,240,410,1722,194,194,191,54,72,54,360,1230),(22,22,88350,340,38,38,56,38,51,38,253,465,1826,204,204,203,57,76,57,380,1395),(23,23,114400,360,40,40,60,40,53,40,267,520,1930,214,214,214,60,80,60,400,1560),(24,24,143750,380,42,42,63,42,56,42,280,575,2034,224,224,226,63,84,63,420,1725),(25,25,176400,400,44,44,66,44,59,44,293,630,2138,235,235,237,66,88,66,440,1890),(26,26,212350,420,46,46,70,46,61,46,307,685,2242,245,245,249,69,92,69,460,2055),(27,27,251600,440,48,48,73,48,64,48,320,740,2346,255,255,260,72,96,72,480,2220),(28,28,294150,460,50,50,76,50,67,50,333,795,2444,265,265,271,75,100,75,500,2385),(29,29,340000,480,52,52,80,52,69,52,347,850,2547,276,276,283,78,104,78,520,2550),(30,30,407250,500,54,54,83,54,72,54,360,905,3535,286,286,331,81,108,81,540,2715),(31,31,490050,530,57,57,88,57,76,57,380,990,3711,300,300,348,86,114,86,570,2970),(32,32,580500,560,60,60,93,60,80,60,400,1075,3895,313,313,365,90,120,90,600,3225),(33,33,678600,590,63,63,98,63,84,63,420,1160,4071,326,326,381,95,126,95,630,3480),(34,34,784350,620,66,66,103,66,88,66,440,1245,4247,339,339,398,99,132,99,660,3735),(35,35,897750,650,69,69,108,69,92,69,460,1330,4423,353,353,415,104,138,104,690,3990),(36,36,1018800,680,72,72,113,72,96,72,480,1415,4607,366,366,432,108,144,108,720,4245),(37,37,1147500,710,75,75,118,75,100,75,500,1500,4783,380,380,448,113,150,113,750,4500),(38,38,1283850,740,78,78,123,78,104,78,520,1585,4959,392,392,465,117,156,117,780,4755),(39,39,1427850,770,81,81,128,81,108,81,540,1670,5135,406,406,482,122,162,122,810,5010),(40,40,1495260,800,84,84,133,84,112,84,560,1755,6649,419,419,498,126,168,126,840,5265),(41,41,1766250,830,87,87,138,87,116,87,580,1875,6869,433,433,515,131,174,131,870,5625),(42,42,2058840,860,90,90,143,90,120,90,600,1995,7089,446,446,532,135,180,135,900,5985),(43,43,2373030,890,93,93,148,93,124,93,620,2115,7319,459,459,549,140,186,140,930,6345),(44,44,2708820,920,96,96,153,96,128,96,640,2235,7539,472,472,565,144,192,144,960,6705),(45,45,3066210,950,99,99,158,99,132,99,660,2355,7759,486,486,665,149,198,149,990,7065),(46,46,3445200,1000,104,104,166,104,139,104,693,2475,8132,508,508,697,156,208,156,1040,7425),(47,47,3845790,1050,109,109,175,109,145,109,727,2595,8496,530,530,729,164,218,164,1090,7785),(48,48,4267980,1100,114,114,183,114,152,114,760,2715,8869,552,552,760,171,228,171,1140,8145),(49,49,4711770,1150,119,119,191,119,159,119,793,2835,9242,575,575,792,179,238,179,1190,8505),(50,50,4314300,1200,124,124,200,124,165,124,827,2955,11539,596,596,824,186,248,186,1240,7388),(51,51,4874975,1250,129,129,208,129,172,129,860,3115,11975,619,619,856,194,258,194,1290,7788),(52,52,5469250,1300,134,134,216,134,179,134,893,3275,12423,641,641,887,201,268,201,1340,8188),(53,53,6097125,1350,139,139,225,139,185,139,927,3435,12871,663,663,919,209,278,209,1390,8588),(54,54,6758600,1400,144,144,233,144,192,144,960,3595,13319,685,685,951,216,288,216,1440,8988),(55,55,7453675,1450,149,149,241,149,199,149,993,3755,13755,708,708,983,224,298,224,1490,9388),(56,56,8182350,1500,154,154,250,154,205,154,1027,3915,14202,729,729,1014,231,308,231,1540,9788),(57,57,8944625,1550,159,159,258,159,212,159,1060,4075,14650,752,752,1046,239,318,239,1590,10188),(58,58,9740500,1600,164,164,266,164,219,164,1093,4235,15086,774,774,1078,246,328,246,1640,10588),(59,59,10569975,1650,169,169,275,169,225,169,1127,4395,15534,796,796,1110,254,338,254,1690,10988),(60,60,10385400,1700,174,174,283,174,232,174,1160,4555,18646,818,818,1332,261,348,261,1740,11713),(61,61,11424000,1790,183,183,298,183,244,183,1220,4760,19542,857,857,1396,275,366,275,1830,12240),(62,62,12511800,1880,192,192,313,192,256,192,1280,4965,20452,896,896,1461,288,384,288,1920,12767),(63,63,13648800,1970,201,201,328,201,268,201,1340,5170,21362,935,935,1526,302,402,302,2010,13294),(64,64,14835000,2060,210,210,343,210,280,210,1400,5375,22272,973,973,1590,315,420,315,2100,13822),(65,65,16070400,2150,219,219,358,219,292,219,1460,5580,23168,1012,1012,1655,329,438,329,2190,14348),(66,66,17355000,2240,228,228,373,228,304,228,1520,5785,24064,1051,1051,1720,342,456,342,2280,14876),(67,67,18688800,2330,237,237,388,237,316,237,1580,5990,24988,1090,1090,1784,356,474,356,2370,15403),(68,68,20071800,2420,246,246,403,246,328,246,1640,6195,25884,1128,1128,1849,369,492,369,2460,15930),(69,69,21504000,2510,255,255,418,255,340,255,1700,6400,26794,1167,1167,1914,383,510,383,2550,16457),(70,70,22985400,2600,264,264,433,264,352,264,1760,6605,27690,1206,1206,1978,396,528,396,2640,16984),(71,71,24931200,2690,273,273,448,273,364,273,1820,6860,28600,1245,1245,2043,410,546,410,2730,17640),(72,72,26955686,2780,282,282,463,282,376,282,1880,7115,29510,1283,1283,2108,423,564,423,2820,18296),(73,73,29058857,2870,291,291,478,291,388,291,1940,7370,30406,1322,1322,2172,437,582,437,2910,18952),(74,74,31240714,2960,300,300,493,300,400,300,2000,7625,31316,1361,1361,2237,450,600,450,3000,19607),(75,75,33501257,3050,309,309,508,309,412,309,2060,7880,32226,1400,1400,2302,464,618,464,3090,20263),(76,76,35840486,3140,318,318,523,318,424,318,2120,8135,33122,1438,1438,2366,477,636,477,3180,20918),(77,77,38258400,3230,327,327,538,327,436,327,2180,8390,34032,1477,1477,2431,491,654,491,3270,21574),(78,78,40755000,3320,336,336,553,336,448,336,2240,8645,34928,1516,1516,2496,504,672,504,3360,22230),(79,79,43330286,3410,345,345,568,345,460,345,2300,8900,35852,1555,1555,2560,518,690,518,3450,22886),(80,80,40167563,3500,354,354,583,354,472,354,2360,9155,41998,1593,1593,3150,531,708,531,3540,24032),(81,81,43089413,3610,365,365,601,365,487,365,2433,9465,43171,1637,1637,3238,548,730,548,3650,24846),(82,82,46113563,3720,376,376,620,376,501,376,2507,9775,44329,1681,1681,3325,564,752,564,3760,25659),(83,83,49240013,3830,387,387,638,387,516,387,2580,10085,45503,1725,1725,3413,581,774,581,3870,26473),(84,84,52468763,3940,398,398,656,398,531,398,2653,10395,46676,1768,1768,3500,597,796,597,3980,27287),(85,85,55799813,4050,409,409,675,409,545,409,2727,10705,47834,1812,1812,3588,614,818,614,4090,28101),(86,86,59233163,4160,420,420,693,420,560,420,2800,11015,49007,1856,1856,3676,630,840,630,4200,28914),(87,87,62768813,4270,431,431,711,431,575,431,2873,11325,50181,1900,1900,3763,647,862,647,4310,29728),(88,88,66406763,4380,442,442,730,442,589,442,2947,11635,51339,1943,1943,3851,663,884,663,4420,30542),(89,89,70147013,4490,453,453,748,453,604,453,3020,11945,52512,1987,1987,3938,680,906,680,4530,31356),(90,90,73989563,4600,464,464,766,464,619,464,3093,12255,53686,2031,2031,4026,696,928,696,4640,32169),(91,91,78827344,4710,475,475,785,475,633,475,3167,12625,54843,2075,2075,4114,713,949,713,4751,33141),(92,92,83817750,4820,486,486,803,486,648,486,3240,12995,56017,2118,2118,4201,729,972,729,4860,34112),(93,93,88960781,4930,497,497,821,497,663,497,3313,13365,57191,2162,2162,4289,746,994,746,4970,35083),(94,94,94256438,5040,508,508,840,508,677,508,3387,13735,58348,2206,2206,4376,762,1015,762,5081,36054),(95,95,99704719,5150,519,519,858,519,692,519,3460,14105,59522,2250,2250,4464,779,1038,779,5190,37026),(96,96,105305625,5260,530,530,876,530,707,530,3533,14475,60679,2293,2293,4552,795,1060,795,5300,37997),(97,97,111059156,5370,541,541,895,541,721,541,3607,14845,61853,2337,2337,4639,812,1081,812,5411,38968),(98,98,116965313,5480,552,552,913,552,736,552,3680,15215,63027,2381,2381,4727,828,1104,828,5520,39939),(99,99,123024094,5590,563,563,932,563,750,563,3754,15585,64184,2425,2425,4814,845,1125,845,5631,40911),(100,100,98465143,5700,574,574,950,574,765,574,3827,15955,85782,2468,2468,6128,861,1148,861,5741,36469),(101,101,106007143,5870,591,591,978,591,788,591,3940,16490,87847,2528,2528,6276,887,1182,887,5910,37691),(102,102,113824286,6040,608,608,1007,608,811,608,4053,17025,89934,2587,2587,6425,912,1217,912,6080,38914),(103,103,121916571,6210,625,625,1035,625,833,625,4167,17560,92041,2647,2647,6573,938,1250,938,6251,40137),(104,104,130284000,6380,642,642,1063,642,856,642,4280,18095,94106,2706,2706,6722,963,1285,963,6420,41360),(105,105,138926571,6550,659,659,1092,659,879,659,4393,18630,96193,2766,2766,6871,989,1319,989,6590,42583),(106,106,147844286,6720,676,676,1120,676,901,676,4507,19165,98258,2824,2824,7019,1014,1352,1014,6761,43806),(107,107,157037143,6890,693,693,1148,693,924,693,4620,19700,100345,2884,2884,7168,1040,1387,1040,6930,45029),(108,108,166505143,7060,710,710,1177,710,947,710,4733,20235,102431,2943,2943,7317,1065,1421,1065,7100,46251),(109,109,176248286,7230,727,727,1205,727,969,727,4847,20770,104517,3003,3003,7465,1091,1454,1091,7270,47474),(110,110,186266571,7400,744,744,1233,744,992,744,4960,21305,106583,3062,3062,7614,1116,1489,1116,7440,48697),(111,111,199836000,7570,761,761,1262,761,1015,761,5073,21960,108669,3122,3122,7762,1142,1523,1142,7610,50194),(112,112,213873286,7740,778,778,1290,778,1038,778,5186,22615,110734,3181,3181,7911,1167,1557,1167,7779,51691),(113,113,228378429,7910,795,795,1318,795,1060,795,5300,23270,112842,3241,3241,8060,1193,1591,1193,7950,53189),(114,114,243351429,8080,812,812,1347,812,1083,812,5413,23925,114928,3300,3300,8208,1218,1625,1218,8120,54686),(115,115,258792286,8250,829,829,1375,829,1106,829,5526,24580,116993,3360,3360,8357,1244,1659,1244,8289,56183),(116,116,274701000,8420,846,846,1403,846,1128,846,5640,25235,119080,3418,3418,8506,1269,1693,1269,8460,57680),(117,117,291077571,8590,863,863,1432,863,1151,863,5753,25890,121145,3478,3478,8654,1295,1727,1295,8630,59177),(118,118,307922000,8760,880,880,1460,880,1174,880,5866,26545,123252,3537,3537,8803,1320,1761,1320,8799,60674),(119,119,325234286,8930,897,897,1489,897,1196,897,5980,27200,125318,3597,3597,8951,1346,1795,1346,8970,62171),(120,120,343014429,9100,914,914,1517,914,1219,914,6093,27855,127404,3656,3656,9100,1371,1829,1371,9140,79586);
/*!40000 ALTER TABLE `system_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_equip`
--

DROP TABLE IF EXISTS `system_equip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_equip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `level` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '等级',
  `slot` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '部位',
  `gongji` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '攻击',
  `fangyu` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '防御',
  `hp` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '血量',
  `baqi` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '神力',
  `shenming` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '神明',
  `baoji` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '暴击',
  `mingzhong` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '命中',
  `shanbi` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '闪避',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=961 DEFAULT CHARSET=utf8mb4 COMMENT='系统装备表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_equip`
--

LOCK TABLES `system_equip` WRITE;
/*!40000 ALTER TABLE `system_equip` DISABLE KEYS */;
INSERT INTO `system_equip` VALUES (1,1,1,0,0,0,0,0,0,0,0),(2,2,1,2,0,0,0,0,0,0,0),(3,3,1,4,0,0,1,0,0,0,0),(4,4,1,6,0,0,1,0,0,0,0),(5,5,1,7,0,0,1,0,0,0,0),(6,6,1,9,0,0,2,0,0,0,0),(7,7,1,11,0,0,2,0,1,1,0),(8,8,1,13,0,0,3,0,1,1,0),(9,9,1,15,0,0,3,0,1,1,0),(10,10,1,16,0,0,3,0,1,1,0),(11,11,1,18,0,0,4,0,1,1,0),(12,12,1,20,0,0,4,0,1,1,0),(13,13,1,22,0,0,4,0,1,1,0),(14,14,1,24,0,0,5,0,1,1,0),(15,15,1,25,0,0,5,0,1,1,0),(16,16,1,27,0,0,6,0,1,1,0),(17,17,1,29,0,0,6,0,1,1,0),(18,18,1,32,0,0,7,0,2,2,0),(19,19,1,34,0,0,7,0,2,2,0),(20,20,1,36,0,0,8,0,2,2,0),(21,21,1,38,0,0,8,0,2,2,0),(22,22,1,40,0,0,9,0,2,2,0),(23,23,1,42,0,0,9,0,2,2,0),(24,24,1,44,0,0,10,0,2,2,0),(25,25,1,46,0,0,10,0,2,2,0),(26,26,1,48,0,0,11,0,2,2,0),(27,27,1,50,0,0,11,0,2,2,0),(28,28,1,53,0,0,12,0,3,3,0),(29,29,1,55,0,0,12,0,3,3,0),(30,30,1,57,0,0,13,0,3,3,0),(31,31,1,60,0,0,13,0,3,3,0),(32,32,1,63,0,0,14,0,3,3,0),(33,33,1,67,0,0,15,0,3,3,0),(34,34,1,69,0,0,16,0,3,3,0),(35,35,1,73,0,0,16,0,4,4,0),(36,36,1,76,0,0,17,0,4,4,0),(37,37,1,79,0,0,18,0,4,4,0),(38,38,1,82,0,0,18,0,4,4,0),(39,39,1,85,0,0,19,0,4,4,0),(40,40,1,88,0,0,20,0,4,4,0),(41,41,1,92,0,0,21,0,4,4,0),(42,42,1,95,0,0,21,0,5,5,0),(43,43,1,98,0,0,22,0,5,5,0),(44,44,1,101,0,0,23,0,5,5,0),(45,45,1,104,0,0,24,0,5,5,0),(46,46,1,109,0,0,25,0,5,5,0),(47,47,1,115,0,0,26,0,6,6,0),(48,48,1,120,0,0,27,0,6,6,0),(49,49,1,125,0,0,29,0,6,6,0),(50,50,1,130,0,0,30,0,6,6,0),(51,51,1,136,0,0,31,0,7,7,0),(52,52,1,141,0,0,33,0,7,7,0),(53,53,1,146,0,0,34,0,7,7,0),(54,54,1,151,0,0,35,0,7,7,0),(55,55,1,157,0,0,37,0,8,8,0),(56,56,1,162,0,0,38,0,8,8,0),(57,57,1,167,0,0,39,0,8,8,0),(58,58,1,172,0,0,40,0,8,8,0),(59,59,1,178,0,0,42,0,9,9,0),(60,60,1,183,0,0,43,0,9,9,0),(61,61,1,193,0,0,45,0,9,9,0),(62,62,1,202,0,0,48,0,10,10,0),(63,63,1,211,0,0,50,0,10,10,0),(64,64,1,221,0,0,52,0,11,11,0),(65,65,1,230,0,0,54,0,11,11,0),(66,66,1,239,0,0,56,0,11,11,0),(67,67,1,249,0,0,59,0,12,12,0),(68,68,1,258,0,0,61,0,12,12,0),(69,69,1,268,0,0,63,0,13,13,0),(70,70,1,277,0,0,65,0,13,13,0),(71,71,1,287,0,0,68,0,14,14,0),(72,72,1,296,0,0,70,0,14,14,0),(73,73,1,306,0,0,72,0,15,15,0),(74,74,1,315,0,0,74,0,15,15,0),(75,75,1,325,0,0,77,0,16,16,0),(76,76,1,334,0,0,79,0,16,16,0),(77,77,1,344,0,0,81,0,16,16,0),(78,78,1,353,0,0,83,0,17,17,0),(79,79,1,363,0,0,86,0,17,17,0),(80,80,1,372,0,0,88,0,18,18,0),(81,81,1,384,0,0,91,0,18,18,0),(82,82,1,395,0,0,93,0,19,19,0),(83,83,1,407,0,0,96,0,19,19,0),(84,84,1,418,0,0,99,0,20,20,0),(85,85,1,430,0,0,101,0,21,21,0),(86,86,1,441,0,0,104,0,21,21,0),(87,87,1,453,0,0,107,0,22,22,0),(88,88,1,464,0,0,109,0,22,22,0),(89,89,1,476,0,0,112,0,23,23,0),(90,90,1,487,0,0,115,0,23,23,0),(91,91,1,499,0,0,117,0,24,24,0),(92,92,1,510,0,0,120,0,24,24,0),(93,93,1,522,0,0,123,0,25,25,0),(94,94,1,533,0,0,125,0,25,25,0),(95,95,1,545,0,0,128,0,26,26,0),(96,96,1,557,0,0,130,0,27,27,0),(97,97,1,568,0,0,133,0,27,27,0),(98,98,1,580,0,0,136,0,28,28,0),(99,99,1,592,0,0,138,0,28,28,0),(100,100,1,603,0,0,141,0,29,29,0),(101,101,1,621,0,0,145,0,30,30,0),(102,102,1,638,0,0,150,0,30,30,0),(103,103,1,657,0,0,154,0,31,31,0),(104,104,1,674,0,0,158,0,32,32,0),(105,105,1,692,0,0,163,0,33,33,0),(106,106,1,710,0,0,167,0,34,34,0),(107,107,1,728,0,0,171,0,35,35,0),(108,108,1,746,0,0,176,0,36,36,0),(109,109,1,764,0,0,180,0,36,36,0),(110,110,1,781,0,0,184,0,37,37,0),(111,111,1,799,0,0,189,0,38,38,0),(112,112,1,817,0,0,193,0,39,39,0),(113,113,1,835,0,0,197,0,40,40,0),(114,114,1,853,0,0,202,0,41,41,0),(115,115,1,871,0,0,206,0,42,42,0),(116,116,1,888,0,0,210,0,42,42,0),(117,117,1,907,0,0,215,0,43,43,0),(118,118,1,924,0,0,219,0,44,44,0),(119,119,1,942,0,0,223,0,45,45,0),(120,120,1,960,0,0,228,0,46,46,0),(121,1,2,0,0,0,0,0,0,0,0),(122,2,2,0,2,2,0,1,0,0,0),(123,3,2,0,4,4,0,1,0,0,0),(124,4,2,0,6,6,0,2,0,0,0),(125,5,2,0,7,9,0,2,0,0,1),(126,6,2,0,9,11,0,3,0,0,1),(127,7,2,0,11,13,0,3,0,0,1),(128,8,2,0,13,15,0,4,0,0,1),(129,9,2,0,15,17,0,5,0,0,1),(130,10,2,0,16,19,0,5,0,0,1),(131,11,2,0,18,21,0,6,0,0,1),(132,12,2,0,20,24,0,6,0,0,1),(133,13,2,0,22,26,0,7,0,0,1),(134,14,2,0,24,28,0,7,0,0,2),(135,15,2,0,25,30,0,8,0,0,2),(136,16,2,0,27,33,0,9,0,0,2),(137,17,2,0,29,36,0,9,0,0,2),(138,18,2,0,32,39,0,10,0,0,2),(139,19,2,0,34,42,0,11,0,0,2),(140,20,2,0,36,45,0,11,0,0,2),(141,21,2,0,38,48,0,12,0,0,2),(142,22,2,0,40,51,0,13,0,0,3),(143,23,2,0,42,54,0,13,0,0,3),(144,24,2,0,44,57,0,14,0,0,3),(145,25,2,0,46,60,0,15,0,0,3),(146,26,2,0,48,63,0,15,0,0,3),(147,27,2,0,50,66,0,16,0,0,3),(148,28,2,0,53,69,0,17,0,0,3),(149,29,2,0,55,72,0,17,0,0,4),(150,30,2,0,57,75,0,18,0,0,4),(151,31,2,0,60,80,0,19,0,0,4),(152,32,2,0,63,84,0,20,0,0,4),(153,33,2,0,67,89,0,21,0,0,4),(154,34,2,0,69,93,0,22,0,0,4),(155,35,2,0,73,98,0,23,0,0,5),(156,36,2,0,76,102,0,24,0,0,5),(157,37,2,0,79,107,0,25,0,0,5),(158,38,2,0,82,111,0,26,0,0,5),(159,39,2,0,85,116,0,27,0,0,5),(160,40,2,0,88,120,0,28,0,0,6),(161,41,2,0,92,125,0,29,0,0,6),(162,42,2,0,95,129,0,30,0,0,6),(163,43,2,0,98,134,0,31,0,0,6),(164,44,2,0,101,138,0,32,0,0,6),(165,45,2,0,104,143,0,33,0,0,7),(166,46,2,0,109,150,0,35,0,0,7),(167,47,2,0,115,158,0,36,0,0,7),(168,48,2,0,120,165,0,38,0,0,8),(169,49,2,0,125,173,0,40,0,0,8),(170,50,2,0,130,180,0,41,0,0,8),(171,51,2,0,136,188,0,43,0,0,9),(172,52,2,0,141,195,0,45,0,0,9),(173,53,2,0,146,203,0,46,0,0,9),(174,54,2,0,151,210,0,48,0,0,10),(175,55,2,0,157,218,0,50,0,0,10),(176,56,2,0,162,225,0,51,0,0,10),(177,57,2,0,167,233,0,53,0,0,11),(178,58,2,0,172,240,0,55,0,0,11),(179,59,2,0,178,248,0,56,0,0,11),(180,60,2,0,183,255,0,58,0,0,12),(181,61,2,0,193,269,0,61,0,0,12),(182,62,2,0,202,282,0,64,0,0,13),(183,63,2,0,211,296,0,67,0,0,13),(184,64,2,0,221,309,0,70,0,0,14),(185,65,2,0,230,323,0,73,0,0,15),(186,66,2,0,239,336,0,76,0,0,15),(187,67,2,0,249,350,0,79,0,0,16),(188,68,2,0,258,363,0,82,0,0,16),(189,69,2,0,268,377,0,85,0,0,17),(190,70,2,0,277,390,0,88,0,0,18),(191,71,2,0,287,404,0,91,0,0,18),(192,72,2,0,296,417,0,94,0,0,19),(193,73,2,0,306,431,0,97,0,0,19),(194,74,2,0,315,444,0,100,0,0,20),(195,75,2,0,325,458,0,103,0,0,21),(196,76,2,0,334,471,0,106,0,0,21),(197,77,2,0,344,485,0,109,0,0,22),(198,78,2,0,353,498,0,112,0,0,22),(199,79,2,0,363,512,0,115,0,0,23),(200,80,2,0,372,525,0,118,0,0,24),(201,81,2,0,384,542,0,122,0,0,24),(202,82,2,0,395,558,0,125,0,0,25),(203,83,2,0,407,575,0,129,0,0,26),(204,84,2,0,418,591,0,133,0,0,27),(205,85,2,0,430,608,0,136,0,0,27),(206,86,2,0,441,624,0,140,0,0,28),(207,87,2,0,453,641,0,144,0,0,29),(208,88,2,0,464,657,0,147,0,0,30),(209,89,2,0,476,674,0,151,0,0,30),(210,90,2,0,487,690,0,155,0,0,31),(211,91,2,0,499,707,0,158,0,0,32),(212,92,2,0,510,723,0,162,0,0,32),(213,93,2,0,522,740,0,166,0,0,33),(214,94,2,0,533,756,0,169,0,0,34),(215,95,2,0,545,773,0,173,0,0,35),(216,96,2,0,557,789,0,177,0,0,35),(217,97,2,0,568,806,0,180,0,0,36),(218,98,2,0,580,822,0,184,0,0,37),(219,99,2,0,592,839,0,188,0,0,38),(220,100,2,0,603,855,0,191,0,0,38),(221,101,2,0,621,881,0,197,0,0,39),(222,102,2,0,638,906,0,203,0,0,41),(223,103,2,0,657,932,0,208,0,0,42),(224,104,2,0,674,957,0,214,0,0,43),(225,105,2,0,692,983,0,220,0,0,44),(226,106,2,0,710,1008,0,225,0,0,45),(227,107,2,0,728,1034,0,231,0,0,46),(228,108,2,0,746,1059,0,237,0,0,47),(229,109,2,0,764,1085,0,242,0,0,49),(230,110,2,0,781,1110,0,248,0,0,50),(231,111,2,0,799,1136,0,254,0,0,51),(232,112,2,0,817,1161,0,259,0,0,52),(233,113,2,0,835,1187,0,265,0,0,53),(234,114,2,0,853,1212,0,271,0,0,54),(235,115,2,0,871,1238,0,276,0,0,55),(236,116,2,0,888,1263,0,282,0,0,57),(237,117,2,0,907,1289,0,288,0,0,58),(238,118,2,0,924,1314,0,293,0,0,59),(239,119,2,0,942,1340,0,299,0,0,60),(240,120,2,0,960,1365,0,305,0,0,61),(241,1,3,0,0,0,0,0,0,0,0),(242,2,3,0,0,15,0,1,0,0,0),(243,3,3,0,1,30,0,1,0,0,0),(244,4,3,0,1,45,0,2,0,0,0),(245,5,3,0,1,60,0,2,0,0,1),(246,6,3,0,1,75,0,3,0,0,1),(247,7,3,0,2,90,0,3,0,0,1),(248,8,3,0,2,105,0,4,0,0,1),(249,9,3,0,2,120,0,5,0,0,1),(250,10,3,0,2,135,0,5,0,0,1),(251,11,3,0,3,150,0,6,0,0,1),(252,12,3,0,3,165,0,6,0,0,1),(253,13,3,0,3,180,0,7,0,0,1),(254,14,3,0,3,195,0,7,0,0,2),(255,15,3,0,4,210,0,8,0,0,2),(256,16,3,0,4,231,0,9,0,0,2),(257,17,3,0,4,252,0,9,0,0,2),(258,18,3,0,5,273,0,10,0,0,2),(259,19,3,0,5,294,0,11,0,0,2),(260,20,3,0,5,315,0,11,0,0,2),(261,21,3,0,5,336,0,12,0,0,2),(262,22,3,0,6,357,0,13,0,0,3),(263,23,3,0,6,378,0,13,0,0,3),(264,24,3,0,6,399,0,14,0,0,3),(265,25,3,0,7,420,0,15,0,0,3),(266,26,3,0,7,441,0,15,0,0,3),(267,27,3,0,7,462,0,16,0,0,3),(268,28,3,0,8,483,0,17,0,0,3),(269,29,3,0,8,504,0,17,0,0,4),(270,30,3,0,8,525,0,18,0,0,4),(271,31,3,0,9,556,0,19,0,0,4),(272,32,3,0,9,588,0,20,0,0,4),(273,33,3,0,10,619,0,21,0,0,4),(274,34,3,0,10,651,0,22,0,0,4),(275,35,3,0,10,682,0,23,0,0,5),(276,36,3,0,11,714,0,24,0,0,5),(277,37,3,0,11,745,0,25,0,0,5),(278,38,3,0,12,777,0,26,0,0,5),(279,39,3,0,12,808,0,27,0,0,5),(280,40,3,0,13,840,0,28,0,0,6),(281,41,3,0,13,871,0,29,0,0,6),(282,42,3,0,14,903,0,30,0,0,6),(283,43,3,0,14,934,0,31,0,0,6),(284,44,3,0,14,966,0,32,0,0,6),(285,45,3,0,15,997,0,33,0,0,7),(286,46,3,0,16,1050,0,35,0,0,7),(287,47,3,0,16,1102,0,36,0,0,7),(288,48,3,0,17,1155,0,38,0,0,8),(289,49,3,0,18,1207,0,40,0,0,8),(290,50,3,0,19,1260,0,41,0,0,8),(291,51,3,0,19,1312,0,43,0,0,9),(292,52,3,0,20,1365,0,45,0,0,9),(293,53,3,0,21,1417,0,46,0,0,9),(294,54,3,0,22,1470,0,48,0,0,10),(295,55,3,0,22,1522,0,50,0,0,10),(296,56,3,0,23,1575,0,51,0,0,10),(297,57,3,0,24,1627,0,53,0,0,11),(298,58,3,0,25,1680,0,55,0,0,11),(299,59,3,0,25,1732,0,56,0,0,11),(300,60,3,0,26,1785,0,58,0,0,12),(301,61,3,0,28,1879,0,61,0,0,12),(302,62,3,0,29,1974,0,64,0,0,13),(303,63,3,0,30,2068,0,67,0,0,13),(304,64,3,0,32,2163,0,70,0,0,14),(305,65,3,0,33,2257,0,73,0,0,15),(306,66,3,0,34,2352,0,76,0,0,15),(307,67,3,0,36,2446,0,79,0,0,16),(308,68,3,0,37,2541,0,82,0,0,16),(309,69,3,0,38,2635,0,85,0,0,17),(310,70,3,0,40,2730,0,88,0,0,18),(311,71,3,0,41,2824,0,91,0,0,18),(312,72,3,0,42,2919,0,94,0,0,19),(313,73,3,0,44,3013,0,97,0,0,19),(314,74,3,0,45,3108,0,100,0,0,20),(315,75,3,0,46,3202,0,103,0,0,21),(316,76,3,0,48,3297,0,106,0,0,21),(317,77,3,0,49,3391,0,109,0,0,22),(318,78,3,0,50,3486,0,112,0,0,22),(319,79,3,0,52,3580,0,115,0,0,23),(320,80,3,0,53,3675,0,118,0,0,24),(321,81,3,0,55,3790,0,122,0,0,24),(322,82,3,0,56,3906,0,125,0,0,25),(323,83,3,0,58,4021,0,129,0,0,26),(324,84,3,0,60,4137,0,133,0,0,27),(325,85,3,0,61,4252,0,136,0,0,27),(326,86,3,0,63,4368,0,140,0,0,28),(327,87,3,0,65,4483,0,144,0,0,29),(328,88,3,0,66,4599,0,147,0,0,30),(329,89,3,0,68,4714,0,151,0,0,30),(330,90,3,0,70,4830,0,155,0,0,31),(331,91,3,0,71,4945,0,158,0,0,32),(332,92,3,0,73,5061,0,162,0,0,32),(333,93,3,0,75,5176,0,166,0,0,33),(334,94,3,0,76,5292,0,169,0,0,34),(335,95,3,0,78,5407,0,173,0,0,35),(336,96,3,0,80,5523,0,177,0,0,35),(337,97,3,0,81,5638,0,180,0,0,36),(338,98,3,0,83,5754,0,184,0,0,37),(339,99,3,0,85,5869,0,188,0,0,38),(340,100,3,0,86,5985,0,191,0,0,38),(341,101,3,0,89,6163,0,197,0,0,39),(342,102,3,0,91,6342,0,203,0,0,41),(343,103,3,0,94,6520,0,208,0,0,42),(344,104,3,0,96,6699,0,214,0,0,43),(345,105,3,0,99,6877,0,220,0,0,44),(346,106,3,0,101,7056,0,225,0,0,45),(347,107,3,0,104,7234,0,231,0,0,46),(348,108,3,0,107,7413,0,237,0,0,47),(349,109,3,0,109,7591,0,242,0,0,49),(350,110,3,0,112,7770,0,248,0,0,50),(351,111,3,0,114,7948,0,254,0,0,51),(352,112,3,0,117,8127,0,259,0,0,52),(353,113,3,0,119,8305,0,265,0,0,53),(354,114,3,0,122,8484,0,271,0,0,54),(355,115,3,0,124,8662,0,276,0,0,55),(356,116,3,0,127,8841,0,282,0,0,57),(357,117,3,0,130,9019,0,288,0,0,58),(358,118,3,0,132,9198,0,293,0,0,59),(359,119,3,0,135,9376,0,299,0,0,60),(360,120,3,0,137,9555,0,305,0,0,61),(361,1,4,0,0,0,0,0,0,0,0),(362,2,4,0,0,0,3,0,0,0,0),(363,3,4,1,0,0,5,0,0,0,0),(364,4,4,1,0,0,8,0,0,0,0),(365,5,4,1,0,0,10,0,0,0,0),(366,6,4,1,0,0,13,0,0,0,0),(367,7,4,2,0,0,15,0,1,1,0),(368,8,4,2,0,0,18,0,1,1,0),(369,9,4,2,0,0,20,0,1,1,0),(370,10,4,2,0,0,22,0,1,1,0),(371,11,4,3,0,0,25,0,1,1,0),(372,12,4,3,0,0,27,0,1,1,0),(373,13,4,3,0,0,30,0,1,1,0),(374,14,4,3,0,0,32,0,1,1,0),(375,15,4,4,0,0,35,0,1,1,0),(376,16,4,4,0,0,39,0,1,1,0),(377,17,4,4,0,0,42,0,1,1,0),(378,18,4,5,0,0,46,0,2,2,0),(379,19,4,5,0,0,49,0,2,2,0),(380,20,4,5,0,0,53,0,2,2,0),(381,21,4,5,0,0,56,0,2,2,0),(382,22,4,6,0,0,60,0,2,2,0),(383,23,4,6,0,0,64,0,2,2,0),(384,24,4,6,0,0,67,0,2,2,0),(385,25,4,7,0,0,71,0,2,2,0),(386,26,4,7,0,0,74,0,2,2,0),(387,27,4,7,0,0,78,0,2,2,0),(388,28,4,8,0,0,81,0,3,3,0),(389,29,4,8,0,0,85,0,3,3,0),(390,30,4,8,0,0,88,0,3,3,0),(391,31,4,9,0,0,93,0,3,3,0),(392,32,4,9,0,0,99,0,3,3,0),(393,33,4,10,0,0,104,0,3,3,0),(394,34,4,10,0,0,109,0,3,3,0),(395,35,4,10,0,0,113,0,4,4,0),(396,36,4,11,0,0,119,0,4,4,0),(397,37,4,11,0,0,124,0,4,4,0),(398,38,4,12,0,0,129,0,4,4,0),(399,39,4,12,0,0,134,0,4,4,0),(400,40,4,13,0,0,139,0,4,4,0),(401,41,4,13,0,0,144,0,4,4,0),(402,42,4,14,0,0,149,0,5,5,0),(403,43,4,14,0,0,155,0,5,5,0),(404,44,4,14,0,0,160,0,5,5,0),(405,45,4,15,0,0,165,0,5,5,0),(406,46,4,16,0,0,174,0,5,5,0),(407,47,4,16,0,0,183,0,6,6,0),(408,48,4,17,0,0,192,0,6,6,0),(409,49,4,18,0,0,201,0,6,6,0),(410,50,4,19,0,0,210,0,6,6,0),(411,51,4,19,0,0,219,0,7,7,0),(412,52,4,20,0,0,228,0,7,7,0),(413,53,4,21,0,0,237,0,7,7,0),(414,54,4,22,0,0,246,0,7,7,0),(415,55,4,22,0,0,256,0,8,8,0),(416,56,4,23,0,0,265,0,8,8,0),(417,57,4,24,0,0,274,0,8,8,0),(418,58,4,25,0,0,283,0,8,8,0),(419,59,4,25,0,0,292,0,9,9,0),(420,60,4,26,0,0,301,0,9,9,0),(421,61,4,28,0,0,316,0,9,9,0),(422,62,4,29,0,0,333,0,10,10,0),(423,63,4,30,0,0,348,0,10,10,0),(424,64,4,32,0,0,364,0,11,11,0),(425,65,4,33,0,0,379,0,11,11,0),(426,66,4,34,0,0,395,0,11,11,0),(427,67,4,36,0,0,411,0,12,12,0),(428,68,4,37,0,0,426,0,12,12,0),(429,69,4,38,0,0,442,0,13,13,0),(430,70,4,40,0,0,458,0,13,13,0),(431,71,4,41,0,0,473,0,14,14,0),(432,72,4,42,0,0,489,0,14,14,0),(433,73,4,44,0,0,505,0,15,15,0),(434,74,4,45,0,0,521,0,15,15,0),(435,75,4,46,0,0,536,0,16,16,0),(436,76,4,48,0,0,552,0,16,16,0),(437,77,4,49,0,0,568,0,16,16,0),(438,78,4,50,0,0,583,0,17,17,0),(439,79,4,52,0,0,599,0,17,17,0),(440,80,4,53,0,0,615,0,18,18,0),(441,81,4,55,0,0,634,0,18,18,0),(442,82,4,56,0,0,652,0,19,19,0),(443,83,4,58,0,0,671,0,19,19,0),(444,84,4,60,0,0,690,0,20,20,0),(445,85,4,61,0,0,708,0,21,21,0),(446,86,4,63,0,0,727,0,21,21,0),(447,87,4,65,0,0,746,0,22,22,0),(448,88,4,66,0,0,764,0,22,22,0),(449,89,4,68,0,0,783,0,23,23,0),(450,90,4,70,0,0,802,0,23,23,0),(451,91,4,71,0,0,820,0,24,24,0),(452,92,4,73,0,0,839,0,24,24,0),(453,93,4,75,0,0,858,0,25,25,0),(454,94,4,76,0,0,876,0,25,25,0),(455,95,4,78,0,0,895,0,26,26,0),(456,96,4,80,0,0,913,0,27,27,0),(457,97,4,81,0,0,932,0,27,27,0),(458,98,4,83,0,0,951,0,28,28,0),(459,99,4,85,0,0,969,0,28,28,0),(460,100,4,86,0,0,988,0,29,29,0),(461,101,4,89,0,0,1018,0,30,30,0),(462,102,4,91,0,0,1048,0,30,30,0),(463,103,4,94,0,0,1079,0,31,31,0),(464,104,4,96,0,0,1109,0,32,32,0),(465,105,4,99,0,0,1139,0,33,33,0),(466,106,4,101,0,0,1169,0,34,34,0),(467,107,4,104,0,0,1199,0,35,35,0),(468,108,4,107,0,0,1230,0,36,36,0),(469,109,4,109,0,0,1260,0,36,36,0),(470,110,4,112,0,0,1290,0,37,37,0),(471,111,4,114,0,0,1320,0,38,38,0),(472,112,4,117,0,0,1350,0,39,39,0),(473,113,4,119,0,0,1381,0,40,40,0),(474,114,4,122,0,0,1411,0,41,41,0),(475,115,4,124,0,0,1441,0,42,42,0),(476,116,4,127,0,0,1471,0,42,42,0),(477,117,4,130,0,0,1502,0,43,43,0),(478,118,4,132,0,0,1532,0,44,44,0),(479,119,4,135,0,0,1562,0,45,45,0),(480,120,4,137,0,0,1593,0,46,46,0),(481,1,5,0,0,0,0,0,0,0,0),(482,2,5,0,0,1,0,2,0,0,0),(483,3,5,0,0,2,0,4,0,0,0),(484,4,5,0,0,3,0,6,0,0,0),(485,5,5,0,1,4,0,8,0,0,0),(486,6,5,0,1,5,0,10,0,0,0),(487,7,5,0,1,6,0,12,0,0,0),(488,8,5,0,1,7,0,14,0,0,0),(489,9,5,0,1,9,0,16,0,0,0),(490,10,5,0,1,10,0,18,0,0,1),(491,11,5,0,1,11,0,20,0,0,1),(492,12,5,0,1,12,0,22,0,0,1),(493,13,5,0,2,13,0,24,0,0,1),(494,14,5,0,2,14,0,26,0,0,1),(495,15,5,0,2,15,0,28,0,0,1),(496,16,5,0,2,16,0,30,0,0,1),(497,17,5,0,2,18,0,33,0,0,1),(498,18,5,0,2,19,0,35,0,0,1),(499,19,5,0,2,21,0,37,0,0,1),(500,20,5,0,3,22,0,40,0,0,1),(501,21,5,0,3,24,0,42,0,0,1),(502,22,5,0,3,25,0,44,0,0,1),(503,23,5,0,3,27,0,47,0,0,1),(504,24,5,0,3,28,0,49,0,0,1),(505,25,5,0,3,30,0,51,0,0,1),(506,26,5,0,3,31,0,54,0,0,2),(507,27,5,0,4,33,0,56,0,0,2),(508,28,5,0,4,34,0,58,0,0,2),(509,29,5,0,4,36,0,61,0,0,2),(510,30,5,0,4,37,0,63,0,0,2),(511,31,5,0,4,40,0,67,0,0,2),(512,32,5,0,5,42,0,70,0,0,2),(513,33,5,0,5,44,0,74,0,0,2),(514,34,5,0,5,46,0,77,0,0,2),(515,35,5,0,5,49,0,81,0,0,2),(516,36,5,0,5,51,0,84,0,0,2),(517,37,5,0,6,53,0,88,0,0,3),(518,38,5,0,6,55,0,91,0,0,3),(519,39,5,0,6,58,0,95,0,0,3),(520,40,5,0,6,60,0,98,0,0,3),(521,41,5,0,7,62,0,102,0,0,3),(522,42,5,0,7,64,0,105,0,0,3),(523,43,5,0,7,67,0,109,0,0,3),(524,44,5,0,7,69,0,112,0,0,3),(525,45,5,0,7,71,0,116,0,0,3),(526,46,5,0,8,75,0,121,0,0,3),(527,47,5,0,8,79,0,127,0,0,4),(528,48,5,0,9,82,0,133,0,0,4),(529,49,5,0,9,86,0,139,0,0,4),(530,50,5,0,9,90,0,145,0,0,4),(531,51,5,0,10,94,0,151,0,0,4),(532,52,5,0,10,97,0,156,0,0,4),(533,53,5,0,10,101,0,162,0,0,5),(534,54,5,0,11,105,0,168,0,0,5),(535,55,5,0,11,109,0,174,0,0,5),(536,56,5,0,12,112,0,180,0,0,5),(537,57,5,0,12,116,0,186,0,0,5),(538,58,5,0,12,120,0,191,0,0,5),(539,59,5,0,13,124,0,197,0,0,6),(540,60,5,0,13,127,0,203,0,0,6),(541,61,5,0,14,134,0,214,0,0,6),(542,62,5,0,14,141,0,224,0,0,6),(543,63,5,0,15,148,0,235,0,0,7),(544,64,5,0,16,154,0,245,0,0,7),(545,65,5,0,16,161,0,256,0,0,7),(546,66,5,0,17,168,0,266,0,0,8),(547,67,5,0,18,175,0,277,0,0,8),(548,68,5,0,18,181,0,287,0,0,8),(549,69,5,0,19,188,0,298,0,0,9),(550,70,5,0,20,195,0,308,0,0,9),(551,71,5,0,21,202,0,319,0,0,9),(552,72,5,0,21,208,0,329,0,0,9),(553,73,5,0,22,215,0,340,0,0,10),(554,74,5,0,23,222,0,350,0,0,10),(555,75,5,0,23,229,0,361,0,0,10),(556,76,5,0,24,235,0,371,0,0,11),(557,77,5,0,25,242,0,382,0,0,11),(558,78,5,0,25,249,0,392,0,0,11),(559,79,5,0,26,256,0,403,0,0,12),(560,80,5,0,27,262,0,413,0,0,12),(561,81,5,0,27,271,0,426,0,0,12),(562,82,5,0,28,279,0,439,0,0,13),(563,83,5,0,29,287,0,452,0,0,13),(564,84,5,0,30,295,0,464,0,0,13),(565,85,5,0,31,304,0,477,0,0,14),(566,86,5,0,32,312,0,490,0,0,14),(567,87,5,0,32,320,0,503,0,0,14),(568,88,5,0,33,328,0,516,0,0,15),(569,89,5,0,34,337,0,529,0,0,15),(570,90,5,0,35,345,0,541,0,0,15),(571,91,5,0,36,353,0,554,0,0,16),(572,92,5,0,36,361,0,567,0,0,16),(573,93,5,0,37,370,0,580,0,0,17),(574,94,5,0,38,378,0,593,0,0,17),(575,95,5,0,39,386,0,606,0,0,17),(576,96,5,0,40,394,0,618,0,0,18),(577,97,5,0,41,403,0,631,0,0,18),(578,98,5,0,41,411,0,644,0,0,18),(579,99,5,0,42,419,0,657,0,0,19),(580,100,5,0,43,427,0,670,0,0,19),(581,101,5,0,44,440,0,690,0,0,20),(582,102,5,0,46,453,0,709,0,0,20),(583,103,5,0,47,466,0,729,0,0,21),(584,104,5,0,48,478,0,749,0,0,21),(585,105,5,0,49,491,0,769,0,0,22),(586,106,5,0,51,504,0,789,0,0,23),(587,107,5,0,52,517,0,809,0,0,23),(588,108,5,0,53,529,0,828,0,0,24),(589,109,5,0,55,542,0,848,0,0,24),(590,110,5,0,56,555,0,868,0,0,25),(591,111,5,0,57,568,0,888,0,0,25),(592,112,5,0,58,580,0,908,0,0,26),(593,113,5,0,60,593,0,928,0,0,27),(594,114,5,0,61,606,0,947,0,0,27),(595,115,5,0,62,619,0,967,0,0,28),(596,116,5,0,63,631,0,987,0,0,28),(597,117,5,0,65,644,0,1007,0,0,29),(598,118,5,0,66,657,0,1027,0,0,29),(599,119,5,0,67,670,0,1047,0,0,30),(600,120,5,0,69,682,0,1066,0,0,31),(601,1,6,0,0,0,0,0,0,0,0),(602,2,6,0,0,0,0,0,0,0,0),(603,3,6,0,0,0,0,0,1,0,0),(604,4,6,0,0,0,1,0,1,0,0),(605,5,6,1,0,0,1,0,1,0,0),(606,6,6,1,0,0,1,0,1,0,0),(607,7,6,1,0,0,1,0,2,0,0),(608,8,6,1,0,0,1,0,2,0,0),(609,9,6,1,0,0,1,0,2,0,0),(610,10,6,1,0,0,2,0,3,0,0),(611,11,6,1,0,0,2,0,3,0,0),(612,12,6,1,0,0,2,0,3,0,0),(613,13,6,2,0,0,2,0,4,1,0),(614,14,6,2,0,0,2,0,4,1,0),(615,15,6,2,0,0,3,0,4,1,0),(616,16,6,2,0,0,3,0,5,1,0),(617,17,6,2,0,0,3,0,5,1,0),(618,18,6,2,0,0,3,0,5,1,0),(619,19,6,2,0,0,4,0,6,1,0),(620,20,6,3,0,0,4,0,6,1,0),(621,21,6,3,0,0,4,0,6,1,0),(622,22,6,3,0,0,4,0,7,1,0),(623,23,6,3,0,0,5,0,7,1,0),(624,24,6,3,0,0,5,0,7,1,0),(625,25,6,3,0,0,5,0,8,1,0),(626,26,6,3,0,0,5,0,8,1,0),(627,27,6,4,0,0,6,0,8,1,0),(628,28,6,4,0,0,6,0,9,1,0),(629,29,6,4,0,0,6,0,9,1,0),(630,30,6,4,0,0,6,0,9,1,0),(631,31,6,4,0,0,7,0,10,1,0),(632,32,6,5,0,0,7,0,11,2,0),(633,33,6,5,0,0,7,0,11,2,0),(634,34,6,5,0,0,8,0,12,2,0),(635,35,6,5,0,0,8,0,12,2,0),(636,36,6,5,0,0,9,0,13,2,0),(637,37,6,6,0,0,9,0,13,2,0),(638,38,6,6,0,0,9,0,14,2,0),(639,39,6,6,0,0,10,0,14,2,0),(640,40,6,6,0,0,10,0,15,2,0),(641,41,6,7,0,0,10,0,15,2,0),(642,42,6,7,0,0,11,0,16,2,0),(643,43,6,7,0,0,11,0,16,2,0),(644,44,6,7,0,0,11,0,17,2,0),(645,45,6,7,0,0,12,0,18,3,0),(646,46,6,8,0,0,12,0,18,3,0),(647,47,6,8,0,0,13,0,19,3,0),(648,48,6,9,0,0,14,0,20,3,0),(649,49,6,9,0,0,14,0,21,3,0),(650,50,6,9,0,0,15,0,22,3,0),(651,51,6,10,0,0,16,0,23,3,0),(652,52,6,10,0,0,16,0,23,3,0),(653,53,6,10,0,0,17,0,25,4,0),(654,54,6,11,0,0,18,0,25,4,0),(655,55,6,11,0,0,18,0,26,4,0),(656,56,6,12,0,0,19,0,27,4,0),(657,57,6,12,0,0,20,0,28,4,0),(658,58,6,12,0,0,20,0,29,4,0),(659,59,6,13,0,0,21,0,30,4,0),(660,60,6,13,0,0,22,0,30,4,0),(661,61,6,14,0,0,23,0,32,5,0),(662,62,6,14,0,0,24,0,34,5,0),(663,63,6,15,0,0,25,0,35,5,0),(664,64,6,16,0,0,26,0,37,5,0),(665,65,6,16,0,0,27,0,39,6,0),(666,66,6,17,0,0,28,0,40,6,0),(667,67,6,18,0,0,29,0,42,6,0),(668,68,6,18,0,0,30,0,43,6,0),(669,69,6,19,0,0,32,0,45,6,0),(670,70,6,20,0,0,33,0,46,7,0),(671,71,6,21,0,0,34,0,48,7,0),(672,72,6,21,0,0,35,0,49,7,0),(673,73,6,22,0,0,36,0,51,7,0),(674,74,6,23,0,0,37,0,53,8,0),(675,75,6,23,0,0,38,0,54,8,0),(676,76,6,24,0,0,39,0,56,8,0),(677,77,6,25,0,0,41,0,57,8,0),(678,78,6,25,0,0,42,0,59,8,0),(679,79,6,26,0,0,43,0,61,9,0),(680,80,6,27,0,0,44,0,62,9,0),(681,81,6,27,0,0,45,0,64,9,0),(682,82,6,28,0,0,47,0,66,9,0),(683,83,6,29,0,0,48,0,68,10,0),(684,84,6,30,0,0,49,0,70,10,0),(685,85,6,31,0,0,51,0,72,10,0),(686,86,6,32,0,0,52,0,74,11,0),(687,87,6,32,0,0,53,0,76,11,0),(688,88,6,33,0,0,55,0,77,11,0),(689,89,6,34,0,0,56,0,79,11,0),(690,90,6,35,0,0,57,0,81,12,0),(691,91,6,36,0,0,59,0,83,12,0),(692,92,6,36,0,0,60,0,85,12,0),(693,93,6,37,0,0,61,0,87,12,0),(694,94,6,38,0,0,63,0,89,13,0),(695,95,6,39,0,0,64,0,91,13,0),(696,96,6,40,0,0,65,0,93,13,0),(697,97,6,41,0,0,67,0,95,14,0),(698,98,6,41,0,0,68,0,97,14,0),(699,99,6,42,0,0,69,0,99,14,0),(700,100,6,43,0,0,71,0,100,14,0),(701,101,6,44,0,0,73,0,104,15,0),(702,102,6,46,0,0,75,0,106,15,0),(703,103,6,47,0,0,77,0,110,16,0),(704,104,6,48,0,0,79,0,112,16,0),(705,105,6,49,0,0,81,0,116,17,0),(706,106,6,51,0,0,84,0,118,17,0),(707,107,6,52,0,0,86,0,121,17,0),(708,108,6,53,0,0,88,0,124,18,0),(709,109,6,55,0,0,90,0,127,18,0),(710,110,6,56,0,0,92,0,130,19,0),(711,111,6,57,0,0,94,0,133,19,0),(712,112,6,58,0,0,96,0,136,19,0),(713,113,6,60,0,0,99,0,139,20,0),(714,114,6,61,0,0,101,0,142,20,0),(715,115,6,62,0,0,103,0,145,21,0),(716,116,6,63,0,0,105,0,148,21,0),(717,117,6,65,0,0,107,0,151,22,0),(718,118,6,66,0,0,109,0,154,22,0),(719,119,6,67,0,0,112,0,157,22,0),(720,120,6,69,0,0,114,0,160,23,0),(721,1,7,0,0,0,0,0,0,0,0),(722,2,7,0,0,0,0,0,0,1,0),(723,3,7,1,0,0,1,0,0,1,0),(724,4,7,1,0,0,1,0,0,2,0),(725,5,7,1,0,0,1,0,0,2,0),(726,6,7,1,0,0,2,0,0,3,0),(727,7,7,2,0,0,2,0,1,4,0),(728,8,7,2,0,0,3,0,1,4,0),(729,9,7,2,0,0,3,0,1,5,0),(730,10,7,2,0,0,3,0,1,6,0),(731,11,7,3,0,0,4,0,1,6,0),(732,12,7,3,0,0,4,0,1,6,0),(733,13,7,3,0,0,4,0,1,7,0),(734,14,7,3,0,0,5,0,1,8,0),(735,15,7,4,0,0,5,0,1,8,0),(736,16,7,4,0,0,6,0,1,9,0),(737,17,7,4,0,0,6,0,1,10,0),(738,18,7,5,0,0,7,0,2,11,0),(739,19,7,5,0,0,7,0,2,11,0),(740,20,7,5,0,0,8,0,2,12,0),(741,21,7,5,0,0,8,0,2,13,0),(742,22,7,6,0,0,9,0,2,13,0),(743,23,7,6,0,0,9,0,2,14,0),(744,24,7,6,0,0,10,0,2,15,0),(745,25,7,7,0,0,10,0,2,15,0),(746,26,7,7,0,0,11,0,2,16,0),(747,27,7,7,0,0,11,0,2,17,0),(748,28,7,8,0,0,12,0,3,18,0),(749,29,7,8,0,0,12,0,3,18,0),(750,30,7,8,0,0,13,0,3,19,0),(751,31,7,9,0,0,13,0,3,20,0),(752,32,7,9,0,0,14,0,3,21,0),(753,33,7,10,0,0,15,0,3,22,0),(754,34,7,10,0,0,16,0,3,23,0),(755,35,7,10,0,0,16,0,4,25,0),(756,36,7,11,0,0,17,0,4,25,0),(757,37,7,11,0,0,18,0,4,27,0),(758,38,7,12,0,0,18,0,4,27,0),(759,39,7,12,0,0,19,0,4,29,0),(760,40,7,13,0,0,20,0,4,29,0),(761,41,7,13,0,0,21,0,4,31,0),(762,42,7,14,0,0,21,0,5,32,0),(763,43,7,14,0,0,22,0,5,33,0),(764,44,7,14,0,0,23,0,5,34,0),(765,45,7,15,0,0,24,0,5,35,0),(766,46,7,16,0,0,25,0,5,36,0),(767,47,7,16,0,0,26,0,6,39,0),(768,48,7,17,0,0,27,0,6,40,0),(769,49,7,18,0,0,29,0,6,42,0),(770,50,7,19,0,0,30,0,6,43,0),(771,51,7,19,0,0,31,0,7,46,0),(772,52,7,20,0,0,33,0,7,47,0),(773,53,7,21,0,0,34,0,7,49,0),(774,54,7,22,0,0,35,0,7,50,0),(775,55,7,22,0,0,37,0,8,53,0),(776,56,7,23,0,0,38,0,8,54,0),(777,57,7,24,0,0,39,0,8,56,0),(778,58,7,25,0,0,40,0,8,57,0),(779,59,7,25,0,0,42,0,9,60,0),(780,60,7,26,0,0,43,0,9,61,0),(781,61,7,28,0,0,45,0,9,64,0),(782,62,7,29,0,0,48,0,10,67,0),(783,63,7,30,0,0,50,0,10,71,0),(784,64,7,32,0,0,52,0,11,74,0),(785,65,7,33,0,0,54,0,11,77,0),(786,66,7,34,0,0,56,0,11,80,0),(787,67,7,36,0,0,59,0,12,83,0),(788,68,7,37,0,0,61,0,12,86,0),(789,69,7,38,0,0,63,0,13,90,0),(790,70,7,40,0,0,65,0,13,92,0),(791,71,7,41,0,0,68,0,14,96,0),(792,72,7,42,0,0,70,0,14,99,0),(793,73,7,44,0,0,72,0,15,102,0),(794,74,7,45,0,0,74,0,15,105,0),(795,75,7,46,0,0,77,0,16,109,0),(796,76,7,48,0,0,79,0,16,111,0),(797,77,7,49,0,0,81,0,16,115,0),(798,78,7,50,0,0,83,0,17,118,0),(799,79,7,52,0,0,86,0,17,121,0),(800,80,7,53,0,0,88,0,18,124,0),(801,81,7,55,0,0,91,0,18,128,0),(802,82,7,56,0,0,93,0,19,132,0),(803,83,7,58,0,0,96,0,19,136,0),(804,84,7,60,0,0,99,0,20,139,0),(805,85,7,61,0,0,101,0,21,144,0),(806,86,7,63,0,0,104,0,21,147,0),(807,87,7,65,0,0,107,0,22,151,0),(808,88,7,66,0,0,109,0,22,155,0),(809,89,7,68,0,0,112,0,23,159,0),(810,90,7,70,0,0,115,0,23,162,0),(811,91,7,71,0,0,117,0,24,167,0),(812,92,7,73,0,0,120,0,24,170,0),(813,93,7,75,0,0,123,0,25,174,0),(814,94,7,76,0,0,125,0,25,178,0),(815,95,7,78,0,0,128,0,26,182,0),(816,96,7,80,0,0,130,0,27,186,0),(817,97,7,81,0,0,133,0,27,190,0),(818,98,7,83,0,0,136,0,28,193,0),(819,99,7,85,0,0,138,0,28,197,0),(820,100,7,86,0,0,141,0,29,201,0),(821,101,7,89,0,0,145,0,30,207,0),(822,102,7,91,0,0,150,0,30,213,0),(823,103,7,94,0,0,154,0,31,219,0),(824,104,7,96,0,0,158,0,32,225,0),(825,105,7,99,0,0,163,0,33,231,0),(826,106,7,101,0,0,167,0,34,237,0),(827,107,7,104,0,0,171,0,35,243,0),(828,108,7,107,0,0,176,0,36,249,0),(829,109,7,109,0,0,180,0,36,255,0),(830,110,7,112,0,0,184,0,37,260,0),(831,111,7,114,0,0,189,0,38,267,0),(832,112,7,117,0,0,193,0,39,272,0),(833,113,7,119,0,0,197,0,40,279,0),(834,114,7,122,0,0,202,0,41,284,0),(835,115,7,124,0,0,206,0,42,291,0),(836,116,7,127,0,0,210,0,42,296,0),(837,117,7,130,0,0,215,0,43,302,0),(838,118,7,132,0,0,219,0,44,308,0),(839,119,7,135,0,0,223,0,45,314,0),(840,120,7,137,0,0,228,0,46,320,0),(841,1,8,0,0,0,0,0,0,0,0),(842,2,8,0,0,2,0,1,0,0,1),(843,3,8,0,1,4,0,1,0,0,1),(844,4,8,0,1,6,0,2,0,0,2),(845,5,8,0,1,9,0,2,0,0,4),(846,6,8,0,1,11,0,3,0,0,4),(847,7,8,0,2,13,0,3,0,0,5),(848,8,8,0,2,15,0,4,0,0,6),(849,9,8,0,2,17,0,5,0,0,6),(850,10,8,0,2,19,0,5,0,0,7),(851,11,8,0,3,21,0,6,0,0,8),(852,12,8,0,3,24,0,6,0,0,9),(853,13,8,0,3,26,0,7,0,0,10),(854,14,8,0,3,28,0,7,0,0,11),(855,15,8,0,4,30,0,8,0,0,11),(856,16,8,0,4,33,0,9,0,0,12),(857,17,8,0,4,36,0,9,0,0,13),(858,18,8,0,5,39,0,10,0,0,14),(859,19,8,0,5,42,0,11,0,0,15),(860,20,8,0,5,45,0,11,0,0,16),(861,21,8,0,5,48,0,12,0,0,17),(862,22,8,0,6,51,0,13,0,0,18),(863,23,8,0,6,54,0,13,0,0,19),(864,24,8,0,6,57,0,14,0,0,20),(865,25,8,0,7,60,0,15,0,0,20),(866,26,8,0,7,63,0,15,0,0,22),(867,27,8,0,7,66,0,16,0,0,22),(868,28,8,0,8,69,0,17,0,0,23),(869,29,8,0,8,72,0,17,0,0,25),(870,30,8,0,8,75,0,18,0,0,25),(871,31,8,0,9,80,0,19,0,0,27),(872,32,8,0,9,84,0,20,0,0,28),(873,33,8,0,10,89,0,21,0,0,29),(874,34,8,0,10,93,0,22,0,0,31),(875,35,8,0,10,98,0,23,0,0,32),(876,36,8,0,11,102,0,24,0,0,34),(877,37,8,0,11,107,0,25,0,0,35),(878,38,8,0,12,111,0,26,0,0,36),(879,39,8,0,12,116,0,27,0,0,38),(880,40,8,0,13,120,0,28,0,0,39),(881,41,8,0,13,125,0,29,0,0,41),(882,42,8,0,14,129,0,30,0,0,42),(883,43,8,0,14,134,0,31,0,0,43),(884,44,8,0,14,138,0,32,0,0,45),(885,45,8,0,15,143,0,33,0,0,46),(886,46,8,0,16,150,0,35,0,0,48),(887,47,8,0,16,158,0,36,0,0,51),(888,48,8,0,17,165,0,38,0,0,53),(889,49,8,0,18,173,0,40,0,0,55),(890,50,8,0,19,180,0,41,0,0,58),(891,51,8,0,19,188,0,43,0,0,60),(892,52,8,0,20,195,0,45,0,0,62),(893,53,8,0,21,203,0,46,0,0,65),(894,54,8,0,22,210,0,48,0,0,67),(895,55,8,0,22,218,0,50,0,0,69),(896,56,8,0,23,225,0,51,0,0,72),(897,57,8,0,24,233,0,53,0,0,74),(898,58,8,0,25,240,0,55,0,0,76),(899,59,8,0,25,248,0,56,0,0,79),(900,60,8,0,26,255,0,58,0,0,81),(901,61,8,0,28,269,0,61,0,0,85),(902,62,8,0,29,282,0,64,0,0,90),(903,63,8,0,30,296,0,67,0,0,94),(904,64,8,0,32,309,0,70,0,0,98),(905,65,8,0,33,323,0,73,0,0,102),(906,66,8,0,34,336,0,76,0,0,106),(907,67,8,0,36,350,0,79,0,0,111),(908,68,8,0,37,363,0,82,0,0,115),(909,69,8,0,38,377,0,85,0,0,119),(910,70,8,0,40,390,0,88,0,0,123),(911,71,8,0,41,404,0,91,0,0,127),(912,72,8,0,42,417,0,94,0,0,132),(913,73,8,0,44,431,0,97,0,0,136),(914,74,8,0,45,444,0,100,0,0,140),(915,75,8,0,46,458,0,103,0,0,144),(916,76,8,0,48,471,0,106,0,0,148),(917,77,8,0,49,485,0,109,0,0,153),(918,78,8,0,50,498,0,112,0,0,157),(919,79,8,0,52,512,0,115,0,0,161),(920,80,8,0,53,525,0,118,0,0,165),(921,81,8,0,55,542,0,122,0,0,170),(922,82,8,0,56,558,0,125,0,0,176),(923,83,8,0,58,575,0,129,0,0,181),(924,84,8,0,60,591,0,133,0,0,186),(925,85,8,0,61,608,0,136,0,0,191),(926,86,8,0,63,624,0,140,0,0,196),(927,87,8,0,65,641,0,144,0,0,201),(928,88,8,0,66,657,0,147,0,0,207),(929,89,8,0,68,674,0,151,0,0,211),(930,90,8,0,70,690,0,155,0,0,216),(931,91,8,0,71,707,0,158,0,0,221),(932,92,8,0,73,723,0,162,0,0,227),(933,93,8,0,75,740,0,166,0,0,232),(934,94,8,0,76,756,0,169,0,0,237),(935,95,8,0,78,773,0,173,0,0,242),(936,96,8,0,80,789,0,177,0,0,247),(937,97,8,0,81,806,0,180,0,0,252),(938,98,8,0,83,822,0,184,0,0,258),(939,99,8,0,85,839,0,188,0,0,263),(940,100,8,0,86,855,0,191,0,0,268),(941,101,8,0,89,881,0,197,0,0,276),(942,102,8,0,91,906,0,203,0,0,284),(943,103,8,0,94,932,0,208,0,0,292),(944,104,8,0,96,957,0,214,0,0,300),(945,105,8,0,99,983,0,220,0,0,308),(946,106,8,0,101,1008,0,225,0,0,316),(947,107,8,0,104,1034,0,231,0,0,324),(948,108,8,0,107,1059,0,237,0,0,332),(949,109,8,0,109,1085,0,242,0,0,340),(950,110,8,0,112,1110,0,248,0,0,348),(951,111,8,0,114,1136,0,254,0,0,356),(952,112,8,0,117,1161,0,259,0,0,363),(953,113,8,0,119,1187,0,265,0,0,372),(954,114,8,0,122,1212,0,271,0,0,379),(955,115,8,0,124,1238,0,276,0,0,387),(956,116,8,0,127,1263,0,282,0,0,396),(957,117,8,0,130,1289,0,288,0,0,403),(958,118,8,0,132,1314,0,293,0,0,411),(959,119,8,0,135,1340,0,299,0,0,419),(960,120,8,0,137,1365,0,305,0,0,427);
/*!40000 ALTER TABLE `system_equip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `from_id` int(11) NOT NULL DEFAULT 0 COMMENT '任务来源编号',
  `from_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '来源类型，1NPC2道具3地图4物件5任务',
  `to_id` int(11) NOT NULL DEFAULT 0 COMMENT '任务提交编号',
  `to_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '提交类型，1NPC2道具3地图4物件',
  `task_info_id` int(11) NOT NULL DEFAULT 0 COMMENT '任务主体编号',
  PRIMARY KEY (`id`),
  KEY `task_from_id_IDX` (`from_id`,`from_type`) USING BTREE,
  KEY `task_to_id_IDX` (`to_id`,`to_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='任务';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task`
--

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` VALUES (1,30,1,29,1,1),(2,28,1,27,1,2),(3,27,1,27,1,3),(4,28,1,27,1,4),(5,11,1,11,1,5),(6,31,1,31,1,6),(7,1,4,1,4,7),(8,8,2,8,2,8),(9,8,5,11,1,9),(10,9,5,0,0,10),(11,33,1,33,1,11),(12,33,1,33,1,12),(13,41,1,41,1,13);
/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_info`
--

DROP TABLE IF EXISTS `task_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task_info` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(200) DEFAULT '' COMMENT '名称',
  `summary` varchar(100) NOT NULL DEFAULT '' COMMENT '任务概述',
  `mode` int(2) unsigned NOT NULL DEFAULT 0 COMMENT '任务模式 1主线 2支线 3日常',
  `level` int(3) unsigned NOT NULL DEFAULT 1 COMMENT '任务等级',
  `max_level` int(3) unsigned NOT NULL DEFAULT 0 COMMENT '任务等级',
  `money` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '任务奖励铜板',
  `exp` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '任务奖励经验',
  `trigger_condition` varchar(255) DEFAULT '' COMMENT '触发条件',
  `from_map` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '接受地图',
  `cs_monster_map` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '传送怪物地图',
  `cs_npc_map` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '传送npc地图',
  `cs_item_map` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '传送物品地图',
  `from_npc` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '发布NPC',
  `from_desc` varchar(1000) DEFAULT '' COMMENT '接受描述',
  `from_item` varchar(100) DEFAULT '' COMMENT '接受物品',
  `from_item_count` varchar(100) DEFAULT '' COMMENT '接受物品数量',
  `from_equip` varchar(100) DEFAULT '' COMMENT '接受道具',
  `from_equip_star` varchar(100) DEFAULT '' COMMENT '接受道具星级',
  `from_equip_count` varchar(100) DEFAULT '' COMMENT '接受道具数量',
  `from_operation` varchar(30) DEFAULT '' COMMENT '接受任务触发操作',
  `to_map` int(6) unsigned NOT NULL DEFAULT 0 COMMENT '完成地图',
  `to_npc` int(4) unsigned NOT NULL DEFAULT 0 COMMENT '完成NPC',
  `to_desc` varchar(1000) DEFAULT '' COMMENT '完成描述',
  `to_item` varchar(100) DEFAULT '' COMMENT '完成物品',
  `to_item_count` varchar(100) DEFAULT '' COMMENT '完成物品数量',
  `to_equip` varchar(100) DEFAULT '' COMMENT '完成道具',
  `to_equip_star` varchar(100) DEFAULT '' COMMENT '完成道具星级',
  `to_equip_count` varchar(100) DEFAULT '' COMMENT '完成道具数量',
  `to_operation` varchar(30) DEFAULT '' COMMENT '完成任务触发操作',
  `zhuxian` int(4) unsigned NOT NULL DEFAULT 0 COMMENT '任务主线编号',
  `type` int(2) unsigned NOT NULL DEFAULT 0 COMMENT '任务类型 1物品收集2打怪3对话4剧情',
  `item` varchar(100) DEFAULT '' COMMENT '任务物品',
  `item_count` varchar(100) DEFAULT '' COMMENT '任务物品数量',
  `monster` varchar(100) DEFAULT '' COMMENT '任务宠物',
  `monster_count` varchar(100) DEFAULT '' COMMENT '任务宠物数量',
  `monster_level` varchar(100) DEFAULT '' COMMENT '任务宠物等级',
  `npc` varchar(100) DEFAULT '' COMMENT '任务NPC',
  `npc_count` varchar(100) DEFAULT '' COMMENT '任务NPC数量',
  `lua` text DEFAULT NULL COMMENT '动态完成条件，返回数组',
  `previous_task_ids` varchar(100) NOT NULL DEFAULT '' COMMENT '前置任务编号，以 "," 为分割',
  `npc_override` varchar(100) NOT NULL DEFAULT '' COMMENT '接受任务时NPC重定向',
  `update_npc_override` varchar(100) NOT NULL DEFAULT '' COMMENT '完成任务时NPC重定向',
  `is_loop` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否循环任务',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=Aria AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 PAGE_CHECKSUM=1 COMMENT='任务';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_info`
--

LOCK TABLES `task_info` WRITE;
/*!40000 ALTER TABLE `task_info` DISABLE KEYS */;
INSERT INTO `task_info` VALUES (1,'小萍在哪里','去陈家寻找小萍',1,1,0,0,10,'',0,0,0,0,0,'昨天和小萍说好早上在这里等的，现在都还没来，你能帮我去她家看看吗？','','','','','','',1,0,'......','','0','','','','',0,3,'','','','','','','',NULL,'','','',0),(2,'焦急的桂香-求医','去村广场找郎中',1,1,0,0,10,'',0,0,0,0,0,'小萍早上怎么也叫不醒，失了魂一样，我一个人走不开，你能帮我去找一下村里的郎中吗？','','','','','','',0,0,'陈家小萍生病了，让您去看看。','','0','','','','',0,3,'','','','','','','',NULL,'1','','',0),(3,'焦急的桂香-解药','帮郎中收集药材',1,1,0,0,10,'',0,0,0,0,0,'昏睡是被硬翅蜂螫伤中毒的症状，解药还需要从硬翅蜂身上提取，你快去帮我收集一些硬翅蜂的蜂蜜和尾针，完成后直接送到小萍家。','','','','','','',0,0,'来得还算及时，只要这些调制出解药，很快就能醒来了。','','0','','','','',0,1,'1|1,4','3','','','','','',NULL,'2','27|1|274','27|0|0',0),(4,'焦急的桂香-感谢','为桂香转交诊金',1,1,0,0,10,'',0,0,0,0,0,'真是谢谢你，看着小萍吃了药就安心多了。刚才光顾着给小萍熬药喂药，老先生走了都没有发现。你能再帮我把诊金送过去吗？','','','','','','',0,0,'只要孩子没事就好，身外之物不重要。村外面的野兽越来越多了，就连不出村小孩子都可能被它们伤害，你们年轻人经常在外走动，可要当心呢。这里是我的手札，也许你能用得上。','5','1','','','','',0,3,'','','','','','','',NULL,'3','','',0),(5,'村里的困难','帮村长驱除野兽，村长建议询问李猎户。',1,1,0,0,10,'',0,0,0,0,0,'大侠的武功果然很高强，之前我们也雇过一些江湖人士去驱除村外的野兽，可惜......不知道大侠愿不愿意帮助我们驱除村外的山猪?','','','','','','',0,0,'{f.name}：多谢大侠仗义相助。\n=====\n{u.name}：学武之人本该行侠仗义，不必言谢。只是村外那些山猪绝非寻常野兽，已不惧寻常兵刃，实非普通武者可敌，还请村长告诉村民多加小心。\n=====\n{f.name}：多谢告知，我会让村里的人避免外出。其实以前不是这样的，不知道为什么会变成这样。<br/>\n对了，最早发现异常的是村广场的南面的李猎户，他前些天去树林深处打猎，被野兽咬伤，回来后还说了一些胡话，现在看来了他可能知道一些信息。<br/>\n他最近都在家里养伤，大侠可以去问问他。','','','','','','',0,2,'','','56','5','','','',NULL,'4','','',0),(6,'最早的异常','向李猎户询问异常的信息，使用郎中传授的配方为他炼制一枚还元丹。',1,1,0,0,10,'',0,0,0,0,0,'{u.name}：村长告诉我你受伤是因为前些天遇到了奇怪的事情，能告诉我你看到了什么吗？\n=====\n{f.name}：当天回来时还记得一些，现在怎么也想不起来了，想的时候头会很疼。\n=====\n{u.name}：我观你精神有缺，可能是受了惊吓，也许郎中的还元丹对你有用，我帮你制作一颗。','','','','','','',0,0,'{u.name}：你先服下这颗还元丹。\n=====\n{f.name}：大侠的丹药果真有效，现在头不疼了。\n=====\n{u.name}：那你回想下去树林深处那天到底遇到了什么？\n=====\n{f.name}：那天早上我去树林深处打猎，发现林间小道里多了很多打斗的痕迹，地上还有不少血迹。<br/><br/>\n我以为是野兽打架受伤，想着自己捡了一个大便宜，就往深处追了过去。<br/><br/>\n在树林里看到了一只受伤的大鸟，翅膀有好几个人那么长，当时我就被吓坏了，正准备转身溜走。<br/><br/>\n哪知它转过头用血红色的眼睛一直瞪着我，然后我脑子全是奇怪的声音，再后来的事情就不记得了。\n=====\n{u.name}：之前听江湖传言有奇物出世，想来或许有关。你先歇着，我去树林深处探探。','','','','','','',0,1,'3','1','','','','','',NULL,'5','','',0),(7,'检查青羽遗骸','检查青羽遗骸',1,1,0,0,0,'',0,0,0,0,0,'','','','','','','',0,0,'这具遗憾深深的震撼到了你，你无法想象它生前有着多么恐怖的能力。\n=====\n你强忍着心里的不平静，仔细查看那了这具遗骸，发现了一些奇怪的东西。','7,8,9','1','','','','',0,4,'','0','','','','','',NULL,'','','',0),(8,'查看藤甲','仔细查看破损的藤甲',1,1,0,0,0,'',0,0,0,0,0,'','','','','','','',0,0,'这件藤甲本不该出现在这里\n=====\n或许村长能知道些什么','','0','','','','3',0,4,'','0','','','','','',NULL,'','','',0),(9,'询问村长','询问村长关于藤甲的信息',1,1,0,0,0,'',0,0,0,0,0,'','','','','','','',0,0,'{u.name}：我在树林深处发现了一具妖兽的遗骸，村外野兽的异变应该由它而起。我在遗骸旁边找到了这件藤甲，不知村长是否识得此物？\n=====\n{t.name}：这藤甲看着眼熟，印象中湖中小岛上巨厥帮的帮众穿过类似的衣甲。不过那帮人都是亡命之徒，可不要轻易招惹呀。\n=====\n{u.name}：村长不必担心，我自由分寸。','','0','','','','4',0,3,'','0','','','','','',NULL,'','','',0),(10,'修炼功法','修炼从青羽遗骸处寻得的功法，然后去湖中小岛一探究竟。',1,1,0,0,0,'',0,0,0,0,0,'','','','','','','',0,0,'自从眨眼剑法练到大成以后修为再难有进步，没想到从妖兽遗骸中发现了一部修炼功法，总算天无绝人之路。\n=====\n这件藤甲出现在了妖兽遗骸旁边，肯定有所牵连。为了安全，需要尽快尝试修炼新的功法，再去湖中小岛上一探究竟。','','0','','','','5',0,4,'','0','','','','','',NULL,'','','',0),(11,'新手奖励','领取新手奖励。',1,1,0,0,0,'',0,0,0,0,0,'欢迎进入游戏，我为你准备了一份特别的礼物。','','','','','','',0,0,'','110,111','1','','','','0',0,3,'','0','','','','','',NULL,'','','',0),(12,'收集物质','帮村长收集物质。',3,10,0,3000,500,'',0,0,0,0,0,'村里现在缺少一些初级装备，麻烦你每天都去收集一些来，报酬丰厚哦。','','','','','','',0,0,'','112|2','0','','','','0',0,1,'','0','','','','','','equips = {36,37,38,51,67,76,85,96,97,98}\nindex = math.random(1, #equips)\nreturn {{equips[index], 3}}','','','',1),(13,'时空裂缝','这里有三张符文，我要你选择一张打开，然后我会送你去该去的地方，愿意吗？',1,1,0,0,0,'',0,0,0,0,0,'{f.name}缓缓的睁开眼看向你。\n=====\n{u.name}：你是谁？这是哪里？\n=====\n{f.name}：没想到在这里还能遇到人，这里是时空裂缝，年轻人。\n=====\n{u.name}：时空裂缝？\n=====\n{f.name}：虽然不知道你从哪里来，但是你会永远困在这里，除非你能帮我做件事。\n=====\n{u.name}：什么事？\n=====\n{f.name}：这里有三张符文，我要你选择一张打开，然后我会送你离开，愿意吗？','','','','','','',0,0,'{u.name}：我愿意。\n=====\n{f.name}：很好！这就是命运，年轻人。打开符文后你就会离开这里，去该去的地方，希望我们还有再见的一天。','18|1,19|1,20|1','0','','','','',0,3,'','0','','','','','','','','','',0);
/*!40000 ALTER TABLE `task_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `username` varchar(30) CHARACTER SET gb2312 DEFAULT NULL,
  `token` varchar(100) CHARACTER SET gb2312 DEFAULT NULL,
  `password` varchar(255) NOT NULL COMMENT '用户密码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userinfo_username_IDX` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userinfo`
--

LOCK TABLES `userinfo` WRITE;
/*!40000 ALTER TABLE `userinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `userinfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-20 12:03:30
