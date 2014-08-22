-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: phpci
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.14.04.1

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
-- Table structure for table `build`
--

DROP TABLE IF EXISTS `build`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `build` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `commit_id` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `log` text,
  `branch` varchar(50) NOT NULL DEFAULT 'master',
  `created` datetime DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  `finished` datetime DEFAULT NULL,
  `committer_email` varchar(512) DEFAULT NULL,
  `commit_message` text,
  `extra` text,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `status` (`status`),
  CONSTRAINT `build_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `build`
--

LOCK TABLES `build` WRITE;
/*!40000 ALTER TABLE `build` DISABLE KEYS */;
/*!40000 ALTER TABLE `build` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `build_meta`
--

DROP TABLE IF EXISTS `build_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `build_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `build_id` int(11) NOT NULL,
  `meta_key` varchar(250) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `build_id` (`build_id`,`meta_key`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `build_meta_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `build_meta_ibfk_1` FOREIGN KEY (`build_id`) REFERENCES `build` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `build_meta`
--

LOCK TABLES `build_meta` WRITE;
/*!40000 ALTER TABLE `build_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `build_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` bigint(14) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES (20140513143726,'2014-08-22 15:21:01','2014-08-22 15:21:09'),(20140513153133,'2014-08-22 15:21:09','2014-08-22 15:21:10'),(20140611170618,'2014-08-22 15:21:10','2014-08-22 15:21:10'),(20140730143702,'2014-08-22 15:21:10','2014-08-22 15:21:14');
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `reference` varchar(250) NOT NULL,
  `branch` varchar(50) NOT NULL DEFAULT 'master',
  `ssh_private_key` text,
  `ssh_public_key` text,
  `type` varchar(50) NOT NULL,
  `access_information` varchar(250) DEFAULT NULL,
  `last_commit` varchar(250) DEFAULT NULL,
  `build_config` text NOT NULL,
  `allow_public_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `hash` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `is_admin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'webmaster@email.com','$2y$10$FCd.C4ynsmL.Nl2dFSmae.YmYGUh3uGIGEKK.F1RYVpnUL6ndB61G','Administrator',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-22 17:39:29
