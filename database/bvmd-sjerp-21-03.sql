-- MySQL dump 10.13  Distrib 8.0.23, for Linux (x86_64)
--
-- Host: localhost    Database: bvmd
-- ------------------------------------------------------
-- Server version	8.0.21-0ubuntu0.20.04.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `uuid` varchar(255) NOT NULL DEFAULT 'nog niets',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `street` text NOT NULL,
  `house_number` text NOT NULL,
  `postal_code` text NOT NULL,
  `city` text,
  `lattitude` text,
  `longitude` text,
  `manual_geolocation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES ('5ffc83f51fd9a4-62860907-5ffc83f51fda25-46291895','2021-03-18 05:22:24','Beatrixstraat','25','9503KN','Stadskanaal','52.98243','6.95631',NULL),('5ffc8dd15bd527-24152993-5ffc8dd15bd581-15155095','2021-03-02 10:42:21','De Lange West','26','9201CE','Drachten','53.109490','6.093570',NULL),('5ffc9515222f78-62245182-5ffc9515222ff3-97986957','2021-01-18 20:41:57','Zuidenveld','50','9406LM','Assen','53.0063193','6.5474638',NULL),('5ffc959e05b3b7-84141020-5ffc959e05b412-92551917','2021-01-18 20:42:34','Vreebergen','9-5','9403ET','Assen','53.0001709','6.5880687',NULL),('5ffc96a29e7578-07081139-5ffc96a29e75c0-81696703','2021-03-18 03:43:27','Amsterdamstraat','1','1976EE','Ijmuiden','52.460022','4.599433',NULL),('5ffc9e263b1fa0-15121648-5ffc9e263b2016-92665417','2021-01-18 19:10:14','Vincent van Goghstraat','21','8932LD','Leeuwarden','53.200798','5.810014',NULL),('5ffca4036646e5-54382925-5ffca403664748-36268047','2021-01-18 23:42:03','Slotstraat','54','4053HT','Ijzendoorn','51.9066727','5.531678',NULL),('5ffea0493e8e53-25002050-5ffea0493e8ed4-81421219','2021-02-17 01:40:41','Terneuzensestraat','2','4543BN','Terneuzen','51.3129469','3.911865',NULL),('5ffea3d0e53de9-68227443-5ffea3d0e53e41-97984917','2021-02-17 01:52:48','Heilmaat','2','6825KA','Arnhem','51.981017','5.9617165',NULL),('5ffea3f1d4ca85-76235083-5ffea3f1d4caf3-10877971','2021-02-17 01:58:18','Flevostraat','2','1784DA','Den Helder','52.846122','5.707707',NULL),('5ffeae393c5108-30586613-5ffeae393c5146-85129601','2021-01-13 08:24:25','werengouw','381','1024NZ','Amsterdam','52.394228','4.946947',NULL),('5ffeae43190a36-08190584-5ffeae43190a75-58737167','2021-01-18 20:46:04','Sabastraat','13-1','7556TH','Hengelo','52.269928','6.7809704',NULL),('5ffeae682c2f65-34160725-5ffeae682c2fb7-22203429','2021-03-18 05:37:10','Dijk','2','1601GJ','Enkhuizen','52.700943','5.2935901',NULL),('5ffeb44496bc40-03872366-5ffeb44496bc80-05130672','2021-01-13 09:28:48','Hoekschewaardplein','8','1025NZ','Amsterdam','52.3920704','4.9278557',NULL),('5ffeb68d824d81-30753195-5ffeb68d824dd0-00857517','2021-01-13 08:59:57','Hoekschewaardplein','8','1025NZ','Amsterdam','52.3920704','4.9278557',NULL),('5ffeb6bfbfcf18-91845394-5ffeb6bfbfcf68-85931007','2021-01-13 09:00:47','Hoekschewaardplein','8','1025NZ','Amsterdam','52.3920704','4.9278557',NULL),('5ffebd87ad5ad2-68253510-5ffebd87ad5b89-54028111','2021-01-18 20:50:55','Wilhelminalaan','75','2281EG','Rijswijk','52.0567681','4.3411913',NULL),('5ffebe16c8bfd4-74147966-5ffebe16c8c024-49907012','2021-01-18 20:54:23','Klef','61','5763PG','Milheeze','51.5128448','5.7984737',NULL),('5ffeeecd2e0de4-68560260-5ffeeecd2e0e43-06898015','2021-01-13 13:00:00','Oosterspoorplein','3','1093JW','Amsterdam','52.27960175','4.97645239848711',NULL),('5ffeef0b24aef0-36537475-5ffeef0b24af44-92153596','2021-01-18 20:58:35','Westwalstraat','62','1411PC','Naarden','52.2943509','5.1590583',NULL),('5ffef0f418cc05-06500361-5ffef0f418cc49-03878818','2021-02-17 02:00:01','Nink','28','8321AJ','Urk','52.6642366','5.610523',NULL),('5ffef113bfe440-58098107-5ffef113bfe486-22311966','2021-03-18 05:14:18','zuideinde','421','1035PG','Amsterdam','52.4203754','4.8928696',NULL),('5ffefc10446002-35581268-5ffefc10446074-09359509','2021-01-18 20:47:13','Borstelweg','11','7545MR','Enschede','52.2197285','6.8728626',NULL),('5ffefdc25edf80-54323713-5ffefdc25edfe2-19496228','2021-01-18 20:48:04','Rappardstraat','108','6822DA','Arnhem','51.987687','5.922839',NULL),('5fff0038b2c3e6-18563823-5fff0038b2c433-56619726','2021-01-18 20:49:05','Hatertseveldweg','200','6532XV','Nijmegen','51.8338196','5.840671',NULL),('5fff190f1678a1-48484486-5fff190f167b76-31324725','2021-03-02 09:29:04','Leeuwardenplein','3','1324BG','Almere','52.3724779','5.2114633',NULL),('5fff1d57bf8939-05917028-5fff1d57bf89c8-50418471','2021-01-13 16:18:31','Werengouw','399','1024 NZ','Amsterdam','52.394308','4.9466965',NULL),('5fff1d7515b771-91580236-5fff1d7515b7b1-12815679','2021-01-18 20:46:36','Masterstraat','14','7559AH','Hengelo','52.260996','6.793795',NULL),('5fff1d90377818-31861310-5fff1d90377880-96203470','2021-01-18 20:52:59','Resedalaan','200','3871EK','Hoevelaken','52.1721409','5.4587162',NULL),('5fff7d44e464e8-27913178-5fff7d44e46531-72990226','2021-01-18 21:00:23','Blankenbergestraat','132','1066TK','Amsterdam','52.3455244','4.8007132',NULL),('600a0fa5ae2505-17682953-600a0fa5ae2615-76420987','2021-01-21 23:35:02','Burgemeester Dalleustraat','3-1','5141BK','Waalwijk','51.682315','5.073786',NULL),('600a11c0acd5f2-04951440-600a11c0acd664-58510491','2021-03-18 03:44:15','Lange Noordstraat','29','4331CB','Middelburg','-25.764438','29.463539',NULL),('602c7921ed8f04-33938921-602c7921ed90f3-49126746','2021-02-17 02:02:36','Tureluur','44','8281 EX','Genemuiden','52.6212998','6.0486623',NULL),('603e105463f1c2-35432241-603e105463f3f4-68851904','2021-03-02 10:16:37','Oosterspoorplein','1','1093JW','Amsterdam','52.3607952','4.9320185',NULL),('6053388a8c4d70-96057319-6053388a8c81a8-49037369','2021-03-18 11:24:58','Hoofdweg','1','2908LE','Capelle aan den IJssel','51.9540378','4.5681668',NULL);
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `animal_table`
--

DROP TABLE IF EXISTS `animal_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `animal_table` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `animal_id` int NOT NULL,
  `table_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animal_table`
--

LOCK TABLES `animal_table` WRITE;
/*!40000 ALTER TABLE `animal_table` DISABLE KEYS */;
INSERT INTO `animal_table` VALUES (1,3,94,NULL,NULL),(2,3,19,NULL,NULL),(3,3,103,NULL,NULL),(4,4,62,NULL,NULL),(5,4,80,NULL,NULL),(6,4,94,NULL,NULL),(7,5,49,NULL,NULL),(8,5,50,NULL,NULL),(9,5,62,NULL,NULL),(10,5,97,NULL,NULL),(11,2,88,NULL,NULL),(12,2,104,NULL,NULL),(16,3,16,NULL,NULL),(15,3,15,NULL,NULL),(17,8,50,NULL,NULL),(18,1,16,NULL,NULL),(19,1,15,NULL,NULL),(20,9,49,NULL,NULL),(21,9,104,NULL,NULL),(22,10,49,NULL,NULL),(23,10,50,NULL,NULL),(24,10,88,NULL,NULL);
/*!40000 ALTER TABLE `animal_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `animals`
--

DROP TABLE IF EXISTS `animals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `animals` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `breed_id` int unsigned NOT NULL,
  `animaltype_id` int unsigned NOT NULL,
  `gendertype_id` int unsigned NOT NULL,
  `owner_id` int unsigned DEFAULT NULL,
  `guest_id` int unsigned DEFAULT NULL,
  `shelter_id` int unsigned DEFAULT NULL,
  `chip_number` text,
  `birth_date` date DEFAULT NULL,
  `passport_number` text,
  `registration_date` date DEFAULT NULL,
  `placement_date` date DEFAULT NULL,
  `abused` tinyint DEFAULT NULL,
  `witnessed_abuse` tinyint DEFAULT NULL,
  `updates` tinyint DEFAULT '1',
  `max_hours_alone` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `endtype_id` int unsigned DEFAULT NULL,
  `end_description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animals`
--

LOCK TABLES `animals` WRITE;
/*!40000 ALTER TABLE `animals` DISABLE KEYS */;
INSERT INTO `animals` VALUES (1,'Dier 1',5,24,30,4,NULL,1,'','2018-01-10','','2020-09-01',NULL,0,0,1,'','2021-01-18 17:49:49','2020-09-29 12:48:42',NULL,NULL,NULL),(2,'DIERX',75,25,31,1,1,NULL,'gfhf','2020-02-04','okiyjuvh','2020-09-29',NULL,1,0,1,'2','2021-01-19 00:00:32','2020-09-29 13:47:51',NULL,NULL,NULL),(3,'sjerptest',135,154,30,2,7,NULL,'','2019-08-14','','2020-09-15',NULL,1,1,1,'34','2021-01-18 23:18:11','2020-10-16 22:52:08',NULL,NULL,NULL),(4,'harrys dier',121,154,30,3,7,NULL,'fds','2112-12-22','fds','1987-08-22',NULL,0,0,0,'3','2021-01-18 23:18:32','2021-01-11 20:45:20',NULL,NULL,NULL),(5,'kiyjuhgfc',39,24,31,5,1,NULL,'jhgf','2021-01-16','ghf','2020-12-29',NULL,0,0,0,'hfjg','2021-01-18 23:18:37','2021-01-11 21:38:08',NULL,NULL,NULL),(6,'ybgfjuthffgmj',39,129,28,6,NULL,1,'',NULL,'','2020-12-31',NULL,0,0,0,'','2021-01-18 23:18:42','2021-01-11 21:44:55',NULL,NULL,NULL),(7,'Willem Alexanders hond',121,24,31,7,NULL,3,'','2020-12-30','','2021-01-07',NULL,0,1,0,'dgf','2021-01-19 00:01:32','2021-01-11 22:59:40',NULL,NULL,NULL),(8,'AAA',37,154,31,NULL,NULL,NULL,'gdf','2020-12-30','gfd','2021-01-14',NULL,1,1,0,'2','2021-01-12 02:29:59','2021-01-12 02:08:27','2021-01-12',90,'harry\r\n'),(9,'Willem Alexanders salamander',135,142,30,NULL,NULL,NULL,'','1987-08-22','','1987-08-22',NULL,0,0,0,'','2021-01-19 00:05:10','2021-01-19 00:01:24',NULL,NULL,NULL),(10,'poesje mauw',119,25,31,10,1,NULL,'75943758934','2000-01-01','hjhkj','2019-12-02',NULL,1,0,0,'5','2021-01-21 23:45:03','2021-01-21 23:44:49',NULL,NULL,NULL);
/*!40000 ALTER TABLE `animals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int unsigned NOT NULL,
  `location_id` int unsigned DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `phone_number` varchar(100) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `doctype_id` int unsigned NOT NULL,
  `link_id` int unsigned NOT NULL,
  `link_type` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `text` text NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_type` (`link_type`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,56,1,'animal','2020-09-29','Test van Bas','2020-09-29','2020-09-29');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guest_table`
--

DROP TABLE IF EXISTS `guest_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guest_table` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `guest_id` int NOT NULL,
  `table_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guest_table`
--

LOCK TABLES `guest_table` WRITE;
/*!40000 ALTER TABLE `guest_table` DISABLE KEYS */;
INSERT INTO `guest_table` VALUES (8,1,88,NULL,NULL),(7,1,34,NULL,NULL),(6,1,15,NULL,NULL),(5,1,16,NULL,NULL),(9,1,24,NULL,NULL),(10,1,49,NULL,NULL),(11,1,50,NULL,NULL),(12,7,16,NULL,NULL),(13,7,15,NULL,NULL),(14,7,17,NULL,NULL),(15,7,18,NULL,NULL),(16,7,35,NULL,NULL),(17,7,43,NULL,NULL),(18,7,80,NULL,NULL),(19,7,24,NULL,NULL),(20,7,25,NULL,NULL),(21,7,109,NULL,NULL),(22,7,127,NULL,NULL),(23,7,129,NULL,NULL),(24,7,142,NULL,NULL),(25,4,24,NULL,NULL),(26,9,16,NULL,NULL),(27,9,18,NULL,NULL),(28,9,49,NULL,NULL),(29,9,25,NULL,NULL),(30,9,127,NULL,NULL),(31,9,154,NULL,NULL),(32,10,17,NULL,NULL),(33,10,26,NULL,NULL),(34,10,154,NULL,NULL);
/*!40000 ALTER TABLE `guest_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guests` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `phone_number` text,
  `email_address` text,
  `max_hours_alone` int NOT NULL,
  `text` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `address_id` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guests`
--

LOCK TABLES `guests` WRITE;
/*!40000 ALTER TABLE `guests` DISABLE KEYS */;
INSERT INTO `guests` VALUES (1,'Gastgezin 176','0612345678','mail@mailen.nl',5,'','2021-01-13 13:10:29','2020-09-29 13:03:50','5ffca4036646e5-54382925-5ffca403664748-36268047'),(3,'tante miepmap of had ik die al','0612345678','tante@miepmap.nl',54353,'','2021-01-13 09:28:48','2021-01-13 08:50:12','5ffeb44496bc40-03872366-5ffeb44496bc80-05130672'),(4,'Wilhelmina','0612345678','hjhk@gfdhg.nl',9,'','2021-01-18 20:50:55','2021-01-13 09:29:43','5ffebd87ad5ad2-68253510-5ffebd87ad5b89-54028111'),(5,'Sarinah N Zuur','0612345678','dff@gfd.nl',5,'','2021-01-18 20:54:23','2021-01-13 09:32:06','5ffebe16c8bfd4-74147966-5ffebe16c8c024-49907012'),(6,'Gian T Oosterkamp','0612345678','harry@dgf.mk',0,'','2021-01-18 20:58:35','2021-01-13 13:00:59','5ffeef0b24aef0-36537475-5ffeef0b24af44-92153596'),(7,'Dio H Michielse','0616541143','harry@dgf.mk',34,'','2021-01-18 20:53:45','2021-01-13 13:09:08','5ffef0f418cc05-06500361-5ffef0f418cc49-03878818'),(8,'Wenda S Rijke','0612345678','harry@dgf.mk',5,'','2021-01-18 20:58:09','2021-01-13 13:09:39','5ffef113bfe440-58098107-5ffef113bfe486-22311966'),(9,'Shaun T van der Zandt','0616541143','ik@sjerpvanwouden.nl',3,'','2021-01-18 20:52:59','2021-01-13 16:19:28','5fff1d90377818-31861310-5fff1d90377880-96203470'),(10,'harry met een flat','0616541143','harry@opdeflat.nl',0,'niet zoveel te zeggen eigenlijk','2021-03-18 11:24:58','2021-03-18 11:24:58','6053388a8c4d70-96057319-6053388a8c81a8-49037369');
/*!40000 ALTER TABLE `guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `histories`
--

DROP TABLE IF EXISTS `histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `histories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `link_type` varchar(50) NOT NULL,
  `link_id` int unsigned NOT NULL,
  `source_type` varchar(50) NOT NULL,
  `source_id` int unsigned NOT NULL,
  `history_date` datetime DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_type` (`link_type`,`link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `histories`
--

LOCK TABLES `histories` WRITE;
/*!40000 ALTER TABLE `histories` DISABLE KEYS */;
INSERT INTO `histories` VALUES (1,'owners',1,'animals',1,'2020-09-29 13:15:57','connect','2020-09-29 13:15:57','2020-09-29 13:15:57'),(2,'shelters',1,'animals',1,'2020-09-29 13:27:39','connect','2020-09-29 13:27:39','2020-09-29 13:27:39'),(3,'guests',1,'animals',2,'2020-09-29 13:48:36','connect','2020-09-29 13:48:36','2020-09-29 13:48:36'),(4,'owners',1,'animals',2,'2020-09-29 13:48:47','connect','2020-09-29 13:48:47','2020-09-29 13:48:47'),(5,'guests',1,'animals',2,'2020-10-01 18:19:06','unconnect','2020-10-01 18:19:06','2020-10-01 18:19:06'),(6,'guests',1,'animals',2,'2020-10-01 18:19:27','connect','2020-10-01 18:19:27','2020-10-01 18:19:27'),(7,'owners',2,'animals',3,'2020-10-16 22:52:17','connect','2020-10-16 22:52:17','2020-10-16 22:52:17'),(8,'shelters',1,'animals',3,'2020-10-16 22:52:31','connect','2020-10-16 22:52:31','2020-10-16 22:52:31'),(9,'owners',2,'animals',4,'2021-01-11 20:45:31','connect','2021-01-11 20:45:31','2021-01-11 20:45:31'),(10,'owner',1,'animals',5,'2021-01-11 21:45:06','connect','2021-01-11 21:45:06','2021-01-11 21:45:06'),(11,'owners',1,'animals',1,'2021-01-11 21:49:36','unconnect','2021-01-11 21:49:36','2021-01-11 21:49:36'),(12,'owner',5,'animals',1,'2021-01-11 21:49:43','connect','2021-01-11 21:49:43','2021-01-11 21:49:43'),(13,'owners',5,'animals',1,'2021-01-11 21:54:06','unconnect','2021-01-11 21:54:06','2021-01-11 21:54:06'),(14,'owners',2,'animals',3,'2021-01-11 21:54:52','unconnect','2021-01-11 21:54:52','2021-01-11 21:54:52'),(15,'owners',1,'animals',2,'2021-01-13 18:10:06','unconnect','2021-01-13 18:10:06','2021-01-13 18:10:06'),(16,'owner',6,'animals',1,'2021-01-13 18:10:26','connect','2021-01-13 18:10:26','2021-01-13 18:10:26'),(17,'owners',6,'animals',1,'2021-01-13 18:11:00','unconnect','2021-01-13 18:11:00','2021-01-13 18:11:00'),(18,'shelters',1,'animals',1,'2021-01-13 18:15:22','unconnect','2021-01-13 18:15:22','2021-01-13 18:15:22'),(19,'shelter',1,'animals',1,'2021-01-13 18:15:46','connect','2021-01-13 18:15:46','2021-01-13 18:15:46'),(20,'owner',6,'animals',6,'2021-01-18 17:49:37','connect','2021-01-18 17:49:37','2021-01-18 17:49:37'),(21,'owner',2,'animals',3,'2021-01-18 17:49:42','connect','2021-01-18 17:49:42','2021-01-18 17:49:42'),(22,'owner',4,'animals',1,'2021-01-18 17:49:49','connect','2021-01-18 17:49:49','2021-01-18 17:49:49'),(23,'owner',1,'animals',2,'2021-01-18 17:49:52','connect','2021-01-18 17:49:52','2021-01-18 17:49:52'),(24,'owner',7,'animals',7,'2021-01-18 17:49:56','connect','2021-01-18 17:49:56','2021-01-18 17:49:56'),(25,'owners',1,'animals',5,'2021-01-18 23:14:55','unconnect','2021-01-18 23:14:55','2021-01-18 23:14:55'),(26,'owner',5,'animals',5,'2021-01-18 23:14:59','connect','2021-01-18 23:14:59','2021-01-18 23:14:59'),(27,'owners',2,'animals',4,'2021-01-18 23:15:07','unconnect','2021-01-18 23:15:07','2021-01-18 23:15:07'),(28,'owner',3,'animals',4,'2021-01-18 23:15:14','connect','2021-01-18 23:15:14','2021-01-18 23:15:14'),(29,'shelters',1,'animals',3,'2021-01-18 23:16:45','unconnect','2021-01-18 23:16:45','2021-01-18 23:16:45'),(30,'guest',7,'animals',3,'2021-01-18 23:18:11','connect','2021-01-18 23:18:11','2021-01-18 23:18:11'),(31,'shelter',3,'animals',7,'2021-01-18 23:18:26','connect','2021-01-18 23:18:26','2021-01-18 23:18:26'),(32,'guest',7,'animals',4,'2021-01-18 23:18:32','connect','2021-01-18 23:18:32','2021-01-18 23:18:32'),(33,'guest',1,'animals',5,'2021-01-18 23:18:37','connect','2021-01-18 23:18:37','2021-01-18 23:18:37'),(34,'shelter',1,'animals',6,'2021-01-18 23:18:42','connect','2021-01-18 23:18:42','2021-01-18 23:18:42'),(35,'owners',7,'animals',7,'2021-01-18 23:41:47','unconnect','2021-01-18 23:41:47','2021-01-18 23:41:47'),(36,'owner',7,'animals',7,'2021-01-18 23:43:00','connect','2021-01-18 23:43:00','2021-01-18 23:43:00'),(37,'owner',7,'animals',9,'2021-01-19 00:04:37','connect','2021-01-19 00:04:37','2021-01-19 00:04:37'),(38,'owners',7,'animals',9,'2021-01-19 00:05:10','unconnect','2021-01-19 00:05:10','2021-01-19 00:05:10'),(39,'guest',1,'animals',10,'2021-01-21 23:44:58','connect','2021-01-21 23:44:58','2021-01-21 23:44:58'),(40,'owner',10,'animals',10,'2021-01-21 23:45:03','connect','2021-01-21 23:45:03','2021-01-21 23:45:03');
/*!40000 ALTER TABLE `histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `phone_number` text,
  `email_address` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `address_id` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Opvanglocatie 1','0612345678','mail@mailen.nl','2020-09-29 12:59:12','2021-01-13 08:24:35','5ffeae43190a36-08190584-5ffeae43190a75-58737167'),(2,'opvang de spar','0612345678','ik@fdsfsfg.nl','2021-01-13 08:25:12','2021-01-18 20:49:58','5ffeae682c2f65-34160725-5ffeae682c2fb7-22203429'),(3,'harrys opvang','0612345678','ik@sjerpvanwouden.nl','2021-01-13 13:56:32','2021-01-13 13:56:32','5ffefc10446002-35581268-5ffefc10446074-09359509'),(4,'fietjes flatje','0612345678','ik@sjerpvanwouden.nl','2021-01-13 14:03:46','2021-01-18 20:48:04','5ffefdc25edf80-54323713-5ffefdc25edfe2-19496228'),(5,'opvang nijmegen','0612345678','ik@sjerpvanwouden.nl','2021-01-13 14:14:16','2021-01-18 20:49:05','5fff0038b2c3e6-18563823-5fff0038b2c433-56619726'),(6,'arts Sjerp van Wouden','0616541143','ik@sjerpvanwouden.nl','2021-01-13 16:19:01','2021-01-13 16:19:01','5fff1d7515b771-91580236-5fff1d7515b7b1-12815679'),(7,'Waalwijk jeweetzelluf','0612345678','ik@waalwijk.org','2021-01-21 23:35:02','2021-01-21 23:35:02','600a0fa5ae2505-17682953-600a0fa5ae2615-76420987');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menuitems`
--

DROP TABLE IF EXISTS `menuitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menuitems` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sequence` int NOT NULL,
  `name` text NOT NULL,
  `url` text NOT NULL,
  `icon` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menuitems`
--

LOCK TABLES `menuitems` WRITE;
/*!40000 ALTER TABLE `menuitems` DISABLE KEYS */;
INSERT INTO `menuitems` VALUES (1,0,'Dieren','animals','fa-paw'),(2,6,'Tabellen','tables','fa-cog');
/*!40000 ALTER TABLE `menuitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owners`
--

DROP TABLE IF EXISTS `owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owners` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `prefix` text,
  `surname` text,
  `phone_number` text,
  `email_address` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `address_id` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owners`
--

LOCK TABLES `owners` WRITE;
/*!40000 ALTER TABLE `owners` DISABLE KEYS */;
INSERT INTO `owners` VALUES (1,'eigenaar van DIERX','','','0616541143','ik@sjerpvanwouden.nl','2021-01-11 16:59:33','2021-01-19 00:00:26','5ffc83f51fd9a4-62860907-5ffc83f51fda25-46291895'),(2,'Harry2','vab','','0616541143','ik@sjerpvanwouden.nl','2021-01-11 17:41:37','2021-01-11 18:36:24','5ffc8dd15bd527-24152993-5ffc8dd15bd581-15155095'),(3,'Sjerp ','','Assen','0616541143','ik@sjerpvanwouden.nl','2021-01-11 18:12:37','2021-01-18 23:59:52','5ffc9515222f78-62245182-5ffc9515222ff3-97986957'),(4,'Sjerp van Wouden','','','0616541143','ik@sjerpvanwouden.nl','2021-01-11 18:14:54','2021-01-11 18:14:54','5ffc959e05b3b7-84141020-5ffc959e05b412-92551917'),(5,'test54','','','0616541143','ik@sjerpvanwouden.nl','2021-01-11 18:19:14','2021-01-11 18:19:14','5ffc96a29e7578-07081139-5ffc96a29e75c0-81696703'),(6,'harry','de','vries','0612345678','ik@sjerpvanwouden.nl','2021-01-11 18:51:18','2021-01-18 22:01:23','5ffc9e263b1fa0-15121648-5ffc9e263b2016-92665417'),(7,'willem-alexander','van','oranje-nassau','0616541143','ik@sjerpvanwouden.nl','2021-01-11 19:16:19','2021-01-18 23:35:54','5ffca4036646e5-54382925-5ffca403664748-36268047'),(8,'henkie arnhem','','','0612345678','iK@doei.nl','2021-01-13 07:40:01','2021-02-17 01:52:48','5ffea3d0e53de9-68227443-5ffea3d0e53e41-97984917'),(9,'friese man','j','jlk','0616541143','ik@sjerpvanwouden.nl','2021-01-13 16:00:15','2021-03-02 09:24:27','5fff190f1678a1-48484486-5fff190f167b76-31324725'),(10,'Zeeuwse harry','van ','overstrominghen','0612345678','ik@fdgfgfd.nl','2021-01-21 23:44:01','2021-01-21 23:44:01','600a11c0acd5f2-04951440-600a11c0acd664-58510491'),(11,'hary','dfg','fgd','0616541143','ik@sjerpvanwouden.nl','2021-03-02 10:15:48','2021-03-02 10:15:48','603e105463f1c2-35432241-603e105463f3f4-68851904');
/*!40000 ALTER TABLE `owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shelters`
--

DROP TABLE IF EXISTS `shelters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shelters` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `phone_number` text NOT NULL,
  `email_address` text NOT NULL,
  `website` text NOT NULL,
  `contact_person` text NOT NULL,
  `remarks_contract` text NOT NULL,
  `remarks_general` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `address_id` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shelters`
--

LOCK TABLES `shelters` WRITE;
/*!40000 ALTER TABLE `shelters` DISABLE KEYS */;
INSERT INTO `shelters` VALUES (1,'Pension 123','0612345678','mail@mailen.nl','website.nl','Contactpersoon 1','Afspraak 1\r\nAfspraak 2','Opmerking 1\r\nOpmerking 2','2020-09-29 13:04:16','2021-01-13 10:42:30','5ffca4036646e5-54382925-5ffca403664748-36268047'),(3,'Pension pater','0612345678','hkjhui@fdgdf.nl','','','','','2021-01-13 23:07:49','2021-01-18 21:00:23','5fff7d44e464e8-27913178-5fff7d44e46531-72990226'),(4,'Overijsselse opvang','0612345678','harry@doeidoei.nl','','','','','2021-02-17 02:02:10','2021-02-17 02:02:10','602c7921ed8f04-33938921-602c7921ed90f3-49126746');
/*!40000 ALTER TABLE `shelters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablegroups`
--

DROP TABLE IF EXISTS `tablegroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tablegroups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablegroups`
--

LOCK TABLES `tablegroups` WRITE;
/*!40000 ALTER TABLE `tablegroups` DISABLE KEYS */;
INSERT INTO `tablegroups` VALUES (1,'breed','Rassen'),(2,'behaviour','Gedragskenmerken'),(3,'vaccination','Vaccinaties'),(4,'animal_type','Diersoorten'),(5,'home_type','Wooneigenschappen'),(6,'gender_type','Geslachtseigenschappen'),(7,'employee','Medewerkers'),(8,'doctype','Documentsoort'),(9,'end_type','Afmeldreden'),(10,'update_type','Updatesoort');
/*!40000 ALTER TABLE `tablegroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tables` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tablegroup_id` int unsigned NOT NULL,
  `description` text NOT NULL,
  `description2` text NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1,1,'Boxer','',NULL,NULL),(2,1,'Engelse Bulldog','',NULL,NULL),(3,1,'Labradoodle','',NULL,NULL),(4,1,'Maltezer','',NULL,NULL),(5,1,'West Highland White Terrier','',NULL,NULL),(6,1,'Sint Bernard','',NULL,NULL),(16,2,'Goed met katten','','2016-12-05 22:04:01','2016-12-05 22:04:01'),(15,2,'Goed met honden','','2016-12-05 22:03:17','2016-12-05 22:03:17'),(17,2,'Angstig','','2016-12-05 22:04:41','2016-12-05 22:04:41'),(18,2,'Voernijd','','2016-12-19 23:39:08','2016-12-19 23:39:08'),(19,3,'Rabiës','','2016-12-19 23:45:32','2016-12-19 23:45:32'),(20,3,'Hondenziekte','','2016-12-19 23:46:17','2016-12-19 23:46:17'),(21,3,'Hepatitis','','2016-12-19 23:47:08','2016-12-19 23:47:08'),(22,3,'Parvovirus','','2016-12-19 23:47:41','2016-12-19 23:47:41'),(23,2,'Verlatingsangst','','2016-12-20 19:49:45','2016-12-20 19:49:45'),(24,4,'Hond','','2016-12-31 13:29:46','2016-12-31 13:29:46'),(25,4,'Kat','','2016-12-31 13:30:12','2016-12-31 13:30:12'),(26,5,'Flat','','2017-01-05 09:21:41','2017-01-05 09:21:41'),(27,5,'Rijtjeshuis','','2017-01-05 09:21:55','2017-01-05 09:21:55'),(28,6,'Man (Intact)','','2017-01-05 15:43:02','2017-01-05 09:22:08'),(29,6,'Vrouw (Intact)','','2017-01-05 15:43:18','2017-01-05 09:22:17'),(30,6,'Man (Gecastreerd)','','2017-01-05 15:44:08','2017-01-05 09:27:17'),(31,6,'Vrouw (Gesteriliseerd)','','2017-01-05 15:43:40','2017-01-05 09:27:42'),(32,5,'Tuin','','2017-01-05 15:31:25','2017-01-05 15:31:25'),(34,5,'Balkon','','2017-01-05 15:31:42','2017-01-05 15:31:42'),(35,2,'Goed met kinderen','','2017-01-05 15:50:31','2017-01-05 15:49:46'),(36,1,'Europees korthaar','','2019-02-12 18:52:11','2017-05-23 16:38:41'),(37,1,'Amerikaanse Stafford','','2017-05-23 18:29:17','2017-05-23 18:29:17'),(38,1,'Engelse Stafford','','2017-05-23 18:29:32','2017-05-23 18:29:32'),(39,1,'Amerikaanse Bulldog','','2017-05-23 18:30:41','2017-05-23 18:30:41'),(40,3,'Moet nog gedaan worden','','2017-05-23 18:31:22','2017-05-23 18:31:22'),(41,3,'Niesziekte','','2017-05-23 18:31:54','2017-05-23 18:31:54'),(42,1,'Sharpei X Staffordshire terrier','','2019-02-12 18:56:31','2017-05-23 18:39:46'),(43,2,'Heeft bijtverleden','','2017-05-23 18:42:54','2017-05-23 18:42:54'),(44,1,'Maltezer X Shi Tzu','','2019-02-12 18:53:17','2017-05-23 19:00:43'),(45,1,'Flatcoat Retriever','','2019-02-12 19:03:19','2017-06-25 11:48:11'),(46,3,'Volledig geent (pension ok)','','2017-06-26 20:03:15','2017-06-26 20:03:15'),(47,1,'Pers','','2017-06-26 21:21:12','2017-06-26 21:21:12'),(48,1,'Ondefinieerbare kruising','','2017-06-26 21:29:43','2017-06-26 21:29:43'),(49,2,'Kan niet met katten','','2017-06-29 13:05:37','2017-06-29 13:05:37'),(50,2,'Kan niet met honden','','2017-06-29 13:16:00','2017-06-29 13:16:00'),(158,7,'Medewerker 1','','2020-09-29 13:04:45','2020-09-29 13:04:45'),(54,8,'Contract gastgezin','','2017-07-04 19:04:14','2017-07-04 14:38:15'),(56,8,'Intakeformulier','','2017-07-04 19:03:20','2017-07-04 19:03:20'),(57,8,'Draagvlakformulier','','2017-07-04 19:03:35','2017-07-04 19:03:35'),(58,8,'Specificatie dier','','2017-07-04 19:03:51','2017-07-04 19:03:51'),(59,8,'Contract eigenaresse','','2017-07-04 19:04:23','2017-07-04 19:04:23'),(60,1,'Chihuahua X Dwergkees','','2019-02-12 19:02:51','2017-07-18 10:04:18'),(61,1,'Sharpei X Hollandse Herder','','2019-02-12 18:55:44','2017-07-18 14:54:53'),(62,2,'Kan niet met kinderen','','2017-07-19 10:46:21','2017-07-18 15:06:41'),(63,1,'Vlinderhond X Chihuahua','','2019-02-12 18:56:04','2017-07-18 15:13:06'),(64,1,'Shih Tzu','','2017-07-18 15:16:37','2017-07-18 15:16:37'),(65,1,'Dwergpincher X Chihuahua','','2019-02-12 18:57:41','2017-07-18 15:26:26'),(66,1,'Jack Russell X Teckel','','2019-02-12 18:55:21','2017-07-18 15:34:47'),(67,8,'Entingboekje','','2017-07-18 15:42:23','2017-07-18 15:42:23'),(68,1,'Shih Tzu X Lhasa Apso','','2019-02-12 19:03:51','2017-07-18 15:50:58'),(69,8,'Patientenkaart Dierenarts','','2017-07-18 16:44:12','2017-07-18 16:44:12'),(70,1,'Siamees','','2017-07-18 16:54:56','2017-07-18 16:54:56'),(71,1,'Maine Coon X Europees korthaar','','2019-02-12 19:03:38','2017-07-18 17:01:09'),(72,1,'Siamees X Europees korthaar','','2019-02-12 18:50:10','2017-07-18 17:12:47'),(73,1,'Chihuahua','','2017-07-18 17:20:26','2017-07-18 17:20:26'),(74,4,'Papegaai','','2017-07-19 07:47:05','2017-07-19 07:47:05'),(75,1,'Grijze Roodstaart','','2017-07-19 07:47:38','2017-07-19 07:47:38'),(76,1,'Maine Coon X Europees korthaar','','2019-02-12 18:54:02','2017-07-19 08:25:36'),(77,1,'Engelse Bulldog','','2017-07-19 08:35:29','2017-07-19 08:35:29'),(78,8,'Gedragstherapie','','2017-07-19 08:39:43','2017-07-19 08:39:43'),(79,1,'Maine Coon X Ragdoll','','2019-02-12 18:54:26','2017-07-19 09:44:50'),(80,2,'Mag niet loslopen','','2019-02-12 19:36:23','2017-07-19 10:15:29'),(81,1,'Britse langhaar','','2019-02-12 19:01:48','2017-07-19 10:27:02'),(82,1,'Britse korthaar','','2019-02-12 19:01:59','2017-07-19 10:27:25'),(83,5,'Appartement','','2017-07-19 10:45:48','2017-07-19 10:45:48'),(84,1,'Herdershond X Dingo','','2019-02-12 19:04:07','2017-07-19 10:47:32'),(85,1,'Dwergkonijn','','2017-07-19 12:09:42','2017-07-19 12:09:42'),(86,3,'Konijn','','2018-02-19 11:45:42','2017-07-19 12:09:50'),(87,5,'Vrijstaand huis','','2017-07-19 12:37:55','2017-07-19 12:37:55'),(88,5,'Twee onder één kap','','2017-07-19 12:38:33','2017-07-19 12:38:33'),(89,9,'Is in oude situatie teruggeplaatst','','2017-07-19 21:52:47','2017-07-19 21:52:47'),(90,9,'Bij eigenaresse in nieuwe situatie','','2017-07-19 21:54:36','2017-07-19 21:54:22'),(91,9,'Overgenomen door gastgezin','','2017-07-19 21:55:05','2017-07-19 21:55:05'),(92,9,'Overleden','','2017-07-19 21:55:26','2017-07-19 21:55:26'),(93,9,'Eigenaresse heeft zelf een oplossing gevonden','','2017-07-20 12:14:54','2017-07-20 12:14:54'),(94,2,'Mag niet naar buiten','','2017-07-20 13:04:26','2017-07-20 13:04:26'),(95,1,'Spaanse kruising','','2017-07-20 20:43:58','2017-07-20 20:43:58'),(96,2,'Speciale voeding','','2017-07-21 15:32:35','2017-07-21 15:32:35'),(97,2,'Afwachtend met andere honden','','2017-07-25 11:55:44','2017-07-25 11:55:44'),(98,1,'Toy Fox terrier','','2017-07-25 12:12:36','2017-07-25 12:12:36'),(99,1,'Yorkshire Terrier','','2017-08-06 10:22:53','2017-08-06 10:22:53'),(101,1,'Boomer','','2017-09-01 14:06:15','2017-09-01 14:06:15'),(102,3,'Booster nodig','','2017-09-14 20:21:04','2017-09-14 20:21:04'),(103,5,'Benedenwoning','','2017-09-15 09:47:00','2017-09-15 09:47:00'),(104,5,'Bovenwoning','','2017-09-15 09:47:15','2017-09-15 09:47:15'),(105,1,'Dwergpincher','','2017-09-25 16:21:31','2017-09-25 16:21:31'),(106,1,'Witte herder X Wolfshond','','2019-02-12 18:54:33','2017-09-27 12:09:51'),(107,1,'Poedel X Maltezer','','2017-09-27 14:52:44','2017-09-27 14:52:44'),(108,1,'Siberische kat','','2017-10-05 13:30:49','2017-10-05 13:30:49'),(109,4,'Tamme rat','','2017-10-25 09:40:19','2017-10-25 09:40:19'),(110,1,'Japanner','','2017-10-25 09:40:32','2017-10-25 09:40:32'),(111,9,'Herplaatst bij ander gezin','','2017-10-25 12:08:42','2017-10-25 12:08:42'),(112,1,'Stafford X Bully','','2019-02-12 18:55:02','2017-10-30 07:35:24'),(113,9,'Aan expartner teruggegeven','','2017-11-13 16:23:58','2017-11-13 16:23:58'),(114,1,'Bengaal','','2017-11-23 12:06:15','2017-11-23 12:06:15'),(115,1,'Bombay','','2017-11-23 12:06:28','2017-11-23 12:06:28'),(116,1,'Onherleidbare kruising','','2019-02-12 18:50:26','2017-12-02 16:21:08'),(117,1,'Chihuahua X Yorkshire Terrier','','2019-02-12 18:54:48','2017-12-07 08:09:49'),(118,1,'Chihuahua kruising','','2017-12-07 08:10:01','2017-12-07 08:10:01'),(119,1,'Boerboel','','2017-12-15 16:42:18','2017-12-15 16:42:18'),(120,1,'Jack Russel ruwhaar','','2018-02-01 13:54:03','2018-02-01 13:54:03'),(121,1,'Aidi / Atlashond','','2018-02-19 12:52:39','2018-02-19 12:52:39'),(122,1,'Labrador X Golden Retriever','','2019-02-12 18:55:12','2018-03-27 10:52:08'),(123,8,'Afstandsverklaring','','2018-05-03 13:13:27','2018-05-03 13:13:27'),(124,1,'Blauwe Rus Kruising','','2018-05-07 07:13:03','2018-05-07 07:13:03'),(125,1,'Husky','','2019-02-12 18:59:36','2018-05-22 13:53:16'),(126,9,'Overige reden','','2018-06-05 08:51:57','2018-06-05 08:51:57'),(127,4,'Knaagdieren','','2018-09-06 11:04:12','2018-09-06 11:04:12'),(128,4,'Vogels','','2018-09-06 11:04:28','2018-09-06 11:04:28'),(129,4,'Amfibieën','','2018-09-06 11:04:47','2018-09-06 11:04:47'),(130,1,'Hamster','','2018-10-10 10:39:39','2018-10-10 10:39:39'),(131,1,'Dwergkeeshond','','2019-02-12 18:59:06','2018-10-23 12:14:24'),(132,1,'Pekinees','','2018-10-26 09:50:28','2018-10-26 09:50:28'),(133,1,'Berner Sennen','','2019-02-12 18:49:35','2018-11-30 15:37:49'),(134,1,'Duitse herder/husky X Kaukasische herder','','2018-12-05 15:25:16','2018-12-05 15:25:16'),(135,1,'Agapornis','','2018-12-14 13:04:47','2018-12-14 13:04:47'),(136,1,'Baardagaam','','2018-12-14 13:07:51','2018-12-14 13:07:51'),(137,1,'Shi Tzu','','2018-12-20 13:34:54','2018-12-20 13:34:54'),(139,1,'Labrador','','2019-01-11 10:46:18','2019-01-11 10:46:18'),(140,1,'Cane Corso','','2019-02-12 18:48:12','2019-02-01 15:06:43'),(141,5,'Erf','','2019-02-04 11:45:32','2019-02-04 11:45:32'),(142,4,'Reptielen','','2019-02-12 19:09:50','2019-02-12 19:09:02'),(143,1,'Groenwang parkiet','','2019-02-12 19:19:54','2019-02-12 19:19:54'),(144,1,'Duitse staande X Labrador','','2019-02-12 19:23:45','2019-02-12 19:23:45'),(145,4,'Konijn','','2019-02-13 16:30:08','2019-02-13 16:30:08'),(146,1,'Golden Retriever','','2019-03-19 06:07:59','2019-03-19 06:07:59'),(147,1,'Waterschildpad','','2019-04-05 11:05:58','2019-04-05 11:05:58'),(148,1,'Valkparkiet','','2019-05-06 14:34:18','2019-05-06 14:34:18'),(149,1,'Bordercollie','','2019-05-13 11:44:04','2019-05-13 11:44:04'),(180,10,'Contact pension','','2020-10-01 18:15:55','2020-10-01 18:15:55'),(154,4,'Aap','','2020-01-20 20:58:02','2020-01-20 20:58:02'),(179,10,'Update eigenaar','','2020-05-20 19:36:19','2020-05-20 19:36:19'),(156,10,'Contact gastgezin','','2020-05-20 19:36:39','2020-05-20 19:36:39'),(157,10,'Contact hulpverlening','','2020-05-20 19:37:01','2020-05-20 19:37:01');
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `updates`
--

DROP TABLE IF EXISTS `updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `updates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `animal_id` int unsigned DEFAULT NULL,
  `updatetype_id` int unsigned NOT NULL,
  `employee_id` int unsigned NOT NULL,
  `link_id` int NOT NULL,
  `link_type` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `text` text,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_type` (`link_type`,`link_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `updates`
--

LOCK TABLES `updates` WRITE;
/*!40000 ALTER TABLE `updates` DISABLE KEYS */;
INSERT INTO `updates` VALUES (1,NULL,179,158,1,'animals','2020-09-29','Dit is een update voor dier 1','2020-09-29','2020-09-29'),(2,NULL,156,158,1,'guests','2020-10-01','Bla Bla','2020-10-01','2020-10-01'),(3,NULL,180,158,1,'shelters','2020-10-01','Het gaat goed','2020-10-01','2020-10-01'),(4,NULL,179,158,2,'animals','2021-01-19','dit is een test update','2021-01-19','2021-01-19'),(5,NULL,156,158,2,'animals','2021-01-19','fdgdf h dgfh','2021-01-19','2021-01-19');
/*!40000 ALTER TABLE `updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vets`
--

DROP TABLE IF EXISTS `vets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `phone_number` text,
  `email_address` text,
  `website` text,
  `contact_person` text,
  `remarks_contract` text,
  `remarks_general` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `address_id` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vets`
--

LOCK TABLES `vets` WRITE;
/*!40000 ALTER TABLE `vets` DISABLE KEYS */;
INSERT INTO `vets` VALUES (1,'Dierenarts kees 295','0612345678','mail@mailen.nl','website.nl','Contactpersoon 1','Afspraak 1','Opmerking 1','2020-09-29 12:58:51','2021-01-18 20:44:11','5ffca4036646e5-54382925-5ffca403664748-36268047'),(2,'hjdfgbdgkjhn','0616541143','ik@sjerpvanwouden.nl','sjerpvanwouden.nl','sjerp','','','2020-10-06 14:45:16','2021-01-13 07:30:55','5ffca4036646e5-54382925-5ffca403664748-36268047'),(5,'slager harry','0613245678','harry@deslager.nl','slagerharry.nl','harry','doei','','2021-01-13 07:24:57','2021-01-13 07:24:57','5ffea0493e8e53-25002050-5ffea0493e8ed4-81421219'),(6,'Arts in den helder','0612345678','iK@jsjs.nl','','','','','2021-01-13 07:40:33','2021-02-17 01:54:23','5ffea3f1d4ca85-76235083-5ffea3f1d4caf3-10877971'),(7,'arts Sjerp van Wouden','0616541143','ik@sjerpvanwouden.nl','','','','','2021-01-13 16:18:32','2021-01-13 16:18:32','5fff1d57bf8939-05917028-5fff1d57bf89c8-50418471');
/*!40000 ALTER TABLE `vets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-03-18 13:04:56
