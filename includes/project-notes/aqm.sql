-- DROP ALL TABLES
use aqm;

SET FOREIGN_KEY_CHECKS = 0;
SET GROUP_CONCAT_MAX_LEN=32768;
SET @tables = NULL;
SELECT GROUP_CONCAT('`', table_name, '`') INTO @tables
  FROM information_schema.tables
  WHERE table_schema = (SELECT DATABASE());
SELECT IFNULL(@tables,'dummy') INTO @tables;

SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables);
PREPARE stmt FROM @tables;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET FOREIGN_KEY_CHECKS = 1;

-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: aqm
-- ------------------------------------------------------
-- Server version	5.5.16-log

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
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answer` (
  `ANSWER_ID` int(11) NOT NULL,
  `question_QUESTION_ID` int(11) NOT NULL,
  `ANSWER` varchar(255) NOT NULL,
  `FEEDBACK` varchar(255) DEFAULT NULL,
  `LINK` int(11) NOT NULL,
  `IS_CORRECT` tinyint(2) NOT NULL,
  PRIMARY KEY (`ANSWER_ID`),
  KEY `fk_Answer_Question1_idx` (`question_QUESTION_ID`),
  CONSTRAINT `fk_Answer_Question1` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `LINK` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer`
--

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;
INSERT INTO `answer` VALUES (1,1,'Go to 2','feedback2',2,3),(2,1,'Go to 3','feedback3',3,3),(3,2,'Go to 4','feedback4',4,3),(4,2,'Go to 5','feedback5',5,3),(5,3,'Go to 6','feedback6',6,3),(6,3,'Go to 7','feedback7',7,3),(7,4,'Go to 8 - end of quiz','feedback8',8,3),(8,4,'Go to 8 - end of quiz','feedback9',8,3),(9,5,'Go to 8 - end of quiz','feedback10',8,3),(10,5,'Go to 8 - end of quiz','feedback11',8,3),(11,6,'Go to 8 - end of quiz','feedback12',8,3),(12,6,'Go to 8 - end of quiz','feedback13',8,3),(13,7,'Go to 8 - end of quiz','feedback14',8,3),(14,7,'Go to 8 - end of quiz','feedback15',8,3),(15,9,'Go to 2 (quiz 2)','feedback2',10,3),(16,9,'Go to 3 (quiz 2)','feedback3 (quiz 2)',11,3),(17,10,'Go to 4 (quiz 2)','feedback4 (quiz 2)',12,3),(18,10,'Go to 5 (quiz 2)','feedback5 (quiz 2)',13,3),(19,11,'Go to 6 (quiz 2)','feedback6 (quiz 2)',14,3),(20,11,'Go to 7 (quiz 2)','feedback7 (quiz 2)',15,3),(21,12,'Go to 8 - end of quiz (quiz 2)','feedback8 (quiz 2)',16,3),(22,12,'Go to 8 - end of quiz (quiz 2)','feedback9 (quiz 2)',16,3),(23,13,'Go to 8 - end of quiz (quiz 2)','feedback10 (quiz 2)',16,3),(24,13,'Go to 8 - end of quiz (quiz 2)','feedback11 (quiz 2)',16,3),(25,14,'Go to 8 - end of quiz (quiz 2)','feedback12 (quiz 2)',16,3),(26,14,'Go to 8 - end of quiz (quiz 2)','feedback13 (quiz 2)',16,3),(27,15,'Go to 8 - end of quiz (quiz 2)','feedback14 (quiz 2)',16,3),(28,15,'Go to 8 - end of quiz (quiz 2)','feedback15 (quiz 2)',16,3),(29,17,'To set someone`s work on fire.','You should consider looking into the definition of Plagiarism more deeply',18,0),(30,17,'Stealing someone`s work and passing it off as your own.','This is the most appropriate definition. ',17,1),(31,17,'Playing video games competitively.','You should consider looking into the definition of Plagiarism more deeply',18,0),(32,17,'To steal someone`s work to sell.','You should consider looking into the definition of Plagiarism more deeply',18,0),(33,18,'When you don`t know the original author.','Does this choice seem morally and ethically correct to you? What are the legal date requirements of copyright?',19,0),(34,18,'If the original document was published in a different country to you.','Does this choice seem morally and ethically correct to you? What are the legal date requirements of copyright?',19,0),(35,18,'If the document is over 1 year old.','Does this choice seem morally and ethically correct to you? What are the legal date requirements of copyright?',19,0),(36,18,'It is never acceptable.','Correct. Plagiarism cannot be justified.',19,1),(37,19,'Harvard Referencing.','Which of these IS NOT a known referencing style.',20,0),(38,19,'APA Referencing.','Which of these IS NOT a known referencing style.',20,0),(39,19,'Chicago Referencing.','Which of these IS NOT a known referencing style.',20,0),(40,19,'Wagga Wagga Referencing.','Correct. Wagga Wagga Referencing is not a widely accepted referencing style.  yet',20,1);
/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `answer_keyword`
--

DROP TABLE IF EXISTS `answer_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answer_keyword` (
  `answer_ANSWER_ID` int(11) NOT NULL,
  `answer_KEYWORD_ID` int(11) NOT NULL,
  PRIMARY KEY (`answer_ANSWER_ID`,`answer_KEYWORD_ID`),
  KEY `answer_KEYWORD_ID_idx` (`answer_KEYWORD_ID`),
  CONSTRAINT `answer_ANSWER_ID` FOREIGN KEY (`answer_ANSWER_ID`) REFERENCES `answer` (`ANSWER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `answer_KEYWORD_ID` FOREIGN KEY (`answer_KEYWORD_ID`) REFERENCES `keyword` (`KEYWORD_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer_keyword`
--

LOCK TABLES `answer_keyword` WRITE;
/*!40000 ALTER TABLE `answer_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `answer_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `editor`
--

DROP TABLE IF EXISTS `editor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `editor` (
  `user_USERNAME` varchar(10) NOT NULL,
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `ADDED_AT` datetime NOT NULL,
  `ADDED_BY` varchar(10) NOT NULL,
  PRIMARY KEY (`user_USERNAME`,`quiz_QUIZ_ID`),
  KEY `fk_Editors_Quiz1_idx` (`quiz_QUIZ_ID`),
  CONSTRAINT `fk_Editors_Quiz1` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Editors_User1` FOREIGN KEY (`user_USERNAME`) REFERENCES `user` (`USERNAME`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editor`
--

LOCK TABLES `editor` WRITE;
/*!40000 ALTER TABLE `editor` DISABLE KEYS */;
/*!40000 ALTER TABLE `editor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keyword`
--

DROP TABLE IF EXISTS `keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keyword` (
  `KEYWORD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `KEYWORD` varchar(45) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  PRIMARY KEY (`KEYWORD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keyword`
--

LOCK TABLES `keyword` WRITE;
/*!40000 ALTER TABLE `keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `QUESTION_ID` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `CONTENT` text NOT NULL,
  `QUESTION` varchar(255) NOT NULL,
  `IMAGE` varchar(255) DEFAULT NULL,
  `IMAGE_ALT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QUESTION_ID`),
  KEY `fk_Question_Quiz1_idx` (`quiz_QUIZ_ID`),
  CONSTRAINT `fk_Question_Quiz1` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,1,'SomeContent1','The first Question','1.png',NULL),(2,1,'SomeContent2','The second Question','2.png',NULL),(3,1,'SomeContent3','The Third Question','3.png',NULL),(4,1,'SomeContent4','The forth Question','4.png',NULL),(5,1,'SomeContent5','The Fift Question','5.png',NULL),(6,1,'SomeContent6','The Sixth Question','6.png',NULL),(7,1,'SomeContent7','The Seveth Question','7.png',NULL),(8,1,'SomeContent8','The Eighth Question <br /> (insert quiz specifc remarks) END OF QUIZ','8.png',NULL),(9,2,'SomeContent1','The first Question(quiz 2)','1.png',NULL),(10,2,'SomeContent2','The second Question(quiz 2)','2.png',NULL),(11,2,'SomeContent3','The third Question(quiz 2)','3.png',NULL),(12,2,'SomeContent4','The forth Question(quiz 2)','4.png',NULL),(13,2,'SomeContent5','The fifth Question(quiz 2)','5.png',NULL),(14,2,'SomeContent6','The sixth Question(quiz 2)','6.png',NULL),(15,2,'SomeContent7','The seventh Question(quiz 2)','7.png',NULL),(16,2,'SomeContent8','The eigth Question(quiz 2) END OF QUIZ','8.png',NULL),(17,3,'Definition, Referencing','Which phrase most accurately describes plagiarism?',NULL,NULL),(18,3,'Morality','When is plagiarism acceptable?',NULL,NULL),(19,3,'Referencing','Which of the following is not a recognised referencing style:',NULL,NULL),(20,3,'END OF QUIZ CONTENT - plagiarism','Thankyou for using the plagiarism quiz',NULL,NULL);
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_keyword`
--

DROP TABLE IF EXISTS `question_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_keyword` (
  `question_QUESTION_ID` int(11) NOT NULL,
  `question_KEYWORD_ID` int(11) NOT NULL,
  PRIMARY KEY (`question_QUESTION_ID`,`question_KEYWORD_ID`),
  KEY `KEYWORD_ID_idx` (`question_KEYWORD_ID`),
  CONSTRAINT `keyword_KEYWORD_ID` FOREIGN KEY (`question_KEYWORD_ID`) REFERENCES `keyword` (`KEYWORD_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `question_QUESTION_ID` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_keyword`
--

LOCK TABLES `question_keyword` WRITE;
/*!40000 ALTER TABLE `question_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `question_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz` (
  `QUIZ_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SHARED_QUIZ_ID` int(11) NOT NULL,
  `VERSION` int(11) NOT NULL,
  `QUIZ_NAME` varchar(255) NOT NULL,
  `DESCRIPTION` text,
  `IS_PUBLIC` tinyint(1) NOT NULL,
  `NO_OF_ATTEMPTS` int(11) DEFAULT NULL,
  `TIME_LIMIT` time DEFAULT NULL,
  `IS_SAVABLE` tinyint(1) DEFAULT NULL,
  `DATE_OPEN` datetime DEFAULT NULL,
  `DATE_CLOSED` datetime DEFAULT NULL,
  `INTERNAL_DESCRIPTION` varchar(255) DEFAULT NULL,
  `IMAGE` varchar(255) DEFAULT NULL,
  `IMAGE_ALT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QUIZ_ID`),
  UNIQUE KEY `QUIZ_ID_UNIQUE` (`QUIZ_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz`
--

LOCK TABLES `quiz` WRITE;
/*!40000 ALTER TABLE `quiz` DISABLE KEYS */;
INSERT INTO `quiz` VALUES (1,1,1,'Test Quiz 1','This is a Test case Quiz',1,NULL,NULL,NULL,'2015-01-01 06:41:00',NULL,NULL,NULL,NULL),(2,2,1,'Test Quiz 2','This is a Test Case Quiz.',0,NULL,NULL,NULL,'2015-01-01 06:41:00',NULL,NULL,NULL,NULL),(3,3,1,'Plagiarism: Beginner','This quiz focuses on What IS plagiarism? It aims to educate users on it`s definition and some techniques that help avoid it.',0,NULL,NULL,NULL,'2015-01-01 06:41:00',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `quiz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz_keyword`
--

DROP TABLE IF EXISTS `quiz_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_keyword` (
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `quiz_KEYWORD_ID` int(11) NOT NULL,
  PRIMARY KEY (`quiz_QUIZ_ID`,`quiz_KEYWORD_ID`),
  KEY `KEYWORD_ID_idx` (`quiz_KEYWORD_ID`),
  CONSTRAINT `KEYWORD_ID` FOREIGN KEY (`quiz_KEYWORD_ID`) REFERENCES `keyword` (`KEYWORD_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `QUIZ_ID` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_keyword`
--

LOCK TABLES `quiz_keyword` WRITE;
/*!40000 ALTER TABLE `quiz_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiz_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result` (
  `RESULT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_USERNAME` varchar(10) NOT NULL,
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `STARTED_AT` datetime NOT NULL,
  `FINISHED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`RESULT_ID`),
  KEY `fk_Result_User1_idx` (`user_USERNAME`),
  KEY `fk_Result_Quiz1_idx` (`quiz_QUIZ_ID`),
  CONSTRAINT `fk_Result_Quiz1` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Result_User1` FOREIGN KEY (`user_USERNAME`) REFERENCES `user` (`USERNAME`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `result`
--

LOCK TABLES `result` WRITE;
/*!40000 ALTER TABLE `result` DISABLE KEYS */;
INSERT INTO `result` VALUES (3,'jsmith04',3,'2015-05-31 17:24:02',NULL),(4,'jsmith04',3,'2015-05-31 17:24:27',NULL),(5,'jsmith04',1,'2015-05-31 17:28:00',NULL),(6,'jsmith04',3,'2015-06-01 01:41:58',NULL),(7,'bob1',3,'2015-06-01 03:14:51',NULL),(8,'bob1',1,'2015-06-01 03:15:02',NULL),(10,'jgraha50',3,'2015-06-01 15:32:28',NULL),(11,'jgraha50',1,'2015-06-05 22:08:39',NULL),(12,'jgraha50',3,'2015-06-05 22:10:43',NULL),(13,'jgraha50',3,'2015-06-05 22:12:12',NULL),(14,'joshua',1,'2015-06-07 03:15:43',NULL),(15,'joshua',3,'2015-06-07 03:16:07',NULL),(16,'joshua',3,'2015-06-07 03:16:46',NULL),(17,'joshua',3,'2015-06-07 03:26:13',NULL),(18,'joshua',3,'2015-06-07 03:35:15',NULL),(19,'joshua',3,'2015-06-07 03:35:18',NULL),(20,'joshua',3,'2015-06-07 03:36:05',NULL),(21,'joshua',1,'2015-06-07 03:36:34',NULL),(22,'joshua',1,'2015-06-07 03:37:25',NULL),(23,'joshua',3,'2015-06-07 03:39:13',NULL),(24,'joshua',3,'2015-06-07 03:39:25',NULL),(25,'joshua',3,'2015-06-07 03:40:23',NULL),(26,'joshua',3,'2015-06-07 03:42:24',NULL),(27,'joshua',3,'2015-06-07 03:42:36',NULL),(28,'joshua',3,'2015-06-07 03:43:29',NULL),(29,'joshua',3,'2015-06-07 03:45:34',NULL),(30,'joshua',3,'2015-06-07 03:48:49',NULL),(31,'joshua',3,'2015-06-07 03:49:01',NULL),(32,'joshua',3,'2015-06-07 03:50:05',NULL),(33,'jtulip',1,'2015-06-08 23:03:30',NULL),(34,'jtulip',1,'2015-06-08 23:13:19',NULL),(35,'jtulip',1,'2015-06-08 23:14:04',NULL),(36,'jtulip',1,'2015-06-08 23:29:20',NULL),(37,'jtulip',1,'2015-06-09 00:00:31',NULL),(38,'jtulip',1,'2015-06-09 00:02:10',NULL),(39,'jtulip',3,'2015-06-09 00:02:26',NULL),(40,'jtulip',3,'2015-06-09 00:08:12',NULL);
/*!40000 ALTER TABLE `result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `result_answer`
--

DROP TABLE IF EXISTS `result_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result_answer` (
  `result_RESULT_ID` int(11) NOT NULL,
  `question_QUESTION_ID` int(11) NOT NULL,
  `PASS_NO` int(11) NOT NULL,
  `ANSWER` varchar(45) DEFAULT NULL,
  `ANSWERED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`result_RESULT_ID`,`question_QUESTION_ID`,`PASS_NO`),
  KEY `fk_result_answer_question1_idx` (`question_QUESTION_ID`),
  CONSTRAINT `fk_result_answer_question1` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_result_answer_result1` FOREIGN KEY (`result_RESULT_ID`) REFERENCES `result` (`RESULT_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `result_answer`
--

LOCK TABLES `result_answer` WRITE;
/*!40000 ALTER TABLE `result_answer` DISABLE KEYS */;
INSERT INTO `result_answer` VALUES (3,18,1,'29','2015-05-31 17:24:02'),(3,19,1,'33','2015-05-31 17:24:04'),(3,20,1,'37','2015-05-31 17:24:06'),(4,18,1,'29','2015-05-31 17:24:27'),(4,19,1,'33','2015-05-31 17:24:29'),(4,20,1,'37','2015-05-31 17:24:31'),(5,2,1,'1','2015-05-31 17:28:00'),(5,4,1,'3','2015-05-31 17:29:37'),(5,18,1,'29','2015-06-01 01:41:14'),(6,18,1,'32','2015-06-01 01:41:58'),(7,18,1,'32','2015-06-01 03:14:51'),(7,19,1,'35','2015-06-01 03:14:53'),(7,20,1,'40','2015-06-01 03:14:54'),(8,2,1,'1','2015-06-01 03:15:02'),(8,4,1,'3','2015-06-01 03:15:03'),(8,8,1,'7','2015-06-01 03:15:05'),(10,2,1,'1','2015-06-01 15:34:25'),(10,18,1,'29','2015-06-01 15:32:28'),(10,18,2,'29','2015-06-01 15:32:40'),(10,19,1,'33','2015-06-01 15:32:34'),(10,19,2,'34','2015-06-01 15:32:42'),(11,1,1,'1','2015-06-05 22:08:39'),(11,17,1,'29','2015-06-05 22:08:55'),(12,17,1,'29','2015-06-05 22:10:43'),(12,18,1,'33','2015-06-05 22:10:49'),(13,17,1,'29','2015-06-05 22:12:12'),(14,1,1,'1','2015-06-07 03:15:43'),(14,2,1,'4','2015-06-07 03:15:47'),(14,5,1,'9','2015-06-07 03:15:50'),(15,17,1,'32','2015-06-07 03:16:07'),(15,18,1,'35','2015-06-07 03:16:09'),(15,19,1,'37','2015-06-07 03:16:11'),(16,17,1,'29','2015-06-07 03:16:46'),(16,17,2,'29','2015-06-07 03:22:45'),(16,18,1,'33','2015-06-07 03:22:49'),(16,19,1,'37','2015-06-07 03:22:51'),(17,20,1,'37','2015-06-07 03:26:13'),(18,20,1,'37','2015-06-07 03:35:15'),(19,20,1,'37','2015-06-07 03:35:18'),(20,20,1,'37','2015-06-07 03:36:05'),(21,1,1,'2','2015-06-07 03:36:34'),(21,3,1,'6','2015-06-07 03:36:35'),(21,7,1,'14','2015-06-07 03:36:37'),(22,8,1,'14','2015-06-07 03:37:25'),(23,17,1,'32','2015-06-07 03:39:13'),(23,18,1,'36','2015-06-07 03:39:14'),(23,19,1,'40','2015-06-07 03:39:16'),(24,20,1,'40','2015-06-07 03:39:25'),(25,20,1,'40','2015-06-07 03:40:23'),(26,17,1,'32','2015-06-07 03:42:24'),(26,18,1,'36','2015-06-07 03:42:25'),(26,19,1,'40','2015-06-07 03:42:26'),(27,20,1,'40','2015-06-07 03:42:36'),(28,17,1,'32','2015-06-07 03:43:29'),(28,17,2,'32','2015-06-07 03:43:43'),(28,18,1,'36','2015-06-07 03:43:30'),(28,18,2,'36','2015-06-07 03:43:44'),(28,19,1,'40','2015-06-07 03:43:45'),(29,17,1,'31','2015-06-07 03:45:34'),(29,18,1,'34','2015-06-07 03:45:36'),(29,19,1,'40','2015-06-07 03:45:46'),(30,17,1,'32','2015-06-07 03:48:49'),(30,18,1,'36','2015-06-07 03:48:50'),(30,19,1,'40','2015-06-07 03:48:51'),(31,20,1,'40','2015-06-07 03:49:01'),(32,20,1,'40','2015-06-07 03:50:05'),(33,1,1,'1','2015-06-08 23:03:30'),(33,2,1,'5','2015-06-08 23:03:36'),(33,3,1,'10','2015-06-08 23:03:39'),(34,1,1,'1','2015-06-08 23:13:39'),(34,2,1,'5','2015-06-08 23:13:42'),(34,3,1,'9','2015-06-08 23:13:47'),(35,1,1,'1','2015-06-08 23:14:04'),(35,2,1,'6','2015-06-08 23:14:14'),(35,3,1,'12','2015-06-08 23:14:22'),(36,1,1,'2','2015-06-08 23:29:20'),(36,3,1,'11','2015-06-08 23:29:21'),(37,1,1,'32','2015-06-09 00:00:31'),(37,2,1,'4','2015-06-09 00:00:35'),(37,5,1,'10','2015-06-09 00:00:38'),(38,1,1,'1','2015-06-09 00:02:10'),(38,2,1,'3','2015-06-09 00:02:11'),(38,4,1,'7','2015-06-09 00:02:14'),(39,17,1,'30','2015-06-09 00:02:26'),(40,17,1,'30','2015-06-09 00:08:12'),(40,17,2,'30','2015-06-09 00:08:14'),(40,17,3,'29','2015-06-09 00:11:41'),(40,18,1,'33','2015-06-09 00:11:43'),(40,19,1,'40','2015-06-09 00:11:44');
/*!40000 ALTER TABLE `result_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taker`
--

DROP TABLE IF EXISTS `taker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taker` (
  `user_USERNAME` varchar(10) NOT NULL,
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `ADDED_AT` datetime NOT NULL,
  `ADDED_BY` varchar(10) NOT NULL,
  PRIMARY KEY (`user_USERNAME`,`quiz_QUIZ_ID`),
  KEY `fk_takers_quiz1_idx` (`quiz_QUIZ_ID`),
  CONSTRAINT `fk_takers_quiz1` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_takers_user1` FOREIGN KEY (`user_USERNAME`) REFERENCES `user` (`USERNAME`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taker`
--

LOCK TABLES `taker` WRITE;
/*!40000 ALTER TABLE `taker` DISABLE KEYS */;
INSERT INTO `taker` VALUES ('jsmith04',1,'0000-00-00 00:00:00',''),('jtulip',3,'2015-05-27 00:00:00','user1'),('user1',1,'2015-05-27 00:00:00','user1');
/*!40000 ALTER TABLE `taker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `USERNAME` varchar(10) NOT NULL,
  `ADMIN_TOGGLE` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`USERNAME`),
  UNIQUE KEY `USERNAME_UNIQUE` (`USERNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('aaaa',0),('admin',1),('hbaile04',0),('jgraha50',0),('joshua',0),('jsmith04',0),('jtulip',0),('random',0),('testuser',0),('user1',0),('user2',0),('user3',0);
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

-- Dump completed on 2015-07-28 17:37:12
