CREATE DATABASE  IF NOT EXISTS `aqm` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `aqm`;
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
INSERT INTO `answer` VALUES (1,1,'Go to 2','feedback2',2),(2,1,'Go to 3','feedback3',3),(3,2,'Go to 4','feedback4',4),(4,2,'Go to 5','feedback5',5),(5,3,'Go to 6','feedback6',6),(6,3,'Go to 7','feedback7',7),(7,4,'Go to 8 - end of quiz','feedback8',8),(8,4,'Go to 8 - end of quiz','feedback9',8),(9,5,'Go to 8 - end of quiz','feedback10',8),(10,5,'Go to 8 - end of quiz','feedback11',8),(11,6,'Go to 8 - end of quiz','feedback12',8),(12,6,'Go to 8 - end of quiz','feedback13',8),(13,7,'Go to 8 - end of quiz','feedback14',8),(14,7,'Go to 8 - end of quiz','feedback15',8),(15,9,'Go to 2 (quiz 2)','feedback2',10),(16,9,'Go to 3 (quiz 2)','feedback3 (quiz 2)',11),(17,10,'Go to 4 (quiz 2)','feedback4 (quiz 2)',12),(18,10,'Go to 5 (quiz 2)','feedback5 (quiz 2)',13),(19,11,'Go to 6 (quiz 2)','feedback6 (quiz 2)',14),(20,11,'Go to 7 (quiz 2)','feedback7 (quiz 2)',15),(21,12,'Go to 8 - end of quiz (quiz 2)','feedback8 (quiz 2)',16),(22,12,'Go to 8 - end of quiz (quiz 2)','feedback9 (quiz 2)',16),(23,13,'Go to 8 - end of quiz (quiz 2)','feedback10 (quiz 2)',16),(24,13,'Go to 8 - end of quiz (quiz 2)','feedback11 (quiz 2)',16),(25,14,'Go to 8 - end of quiz (quiz 2)','feedback12 (quiz 2)',16),(26,14,'Go to 8 - end of quiz (quiz 2)','feedback13 (quiz 2)',16),(27,15,'Go to 8 - end of quiz (quiz 2)','feedback14 (quiz 2)',16),(28,15,'Go to 8 - end of quiz (quiz 2)','feedback15 (quiz 2)',16);
/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
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
  PRIMARY KEY (`QUESTION_ID`),
  KEY `fk_Question_Quiz1_idx` (`quiz_QUIZ_ID`),
  CONSTRAINT `fk_Question_Quiz1` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,1,'SomeContent1','The first Question','1.png'),(2,1,'SomeContent2','The second Question','2.png'),(3,1,'SomeContent3','The Third Question','3.png'),(4,1,'SomeContent4','The forth Question','4.png'),(5,1,'SomeContent5','The Fift Question','5.png'),(6,1,'SomeContent6','The Sixth Question','6.png'),(7,1,'SomeContent7','The Seveth Question','7.png'),(8,1,'SomeContent8','The Eighth Question <br /> (insert quiz specifc remarks) END OF QUIZ','8.png'),(9,2,'SomeContent1','The first Question(quiz 2)','1.png'),(10,2,'SomeContent2','The second Question(quiz 2)','2.png'),(11,2,'SomeContent3','The third Question(quiz 2)','3.png'),(12,2,'SomeContent4','The forth Question(quiz 2)','4.png'),(13,2,'SomeContent5','The fifth Question(quiz 2)','5.png'),(14,2,'SomeContent6','The sixth Question(quiz 2)','6.png'),(15,2,'SomeContent7','The seventh Question(quiz 2)','7.png'),(16,2,'SomeContent8','The eigth Question(quiz 2) END OF QUIZ','8.png');
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz` (
  `QUIZ_ID` int(11) NOT NULL,
  `SHARED_QUIZ_ID` int(11) NOT NULL,
  `VERSION` int(11) NOT NULL,
  `QUIZ_NAME` varchar(255) NOT NULL,
  `DESCRIPTION` varchar(45) DEFAULT NULL,
  `TAGS` varchar(45) DEFAULT NULL,
  `IS_PUBLIC` tinyint(1) NOT NULL,
  `NO_OF_ATTEMPTS` int(11) DEFAULT NULL,
  `TIME_LIMIT` time DEFAULT NULL,
  `IS_SAVABLE` tinyint(1) DEFAULT NULL,
  `DATE_OPEN` datetime DEFAULT NULL,
  `DATE_CLOSED` datetime DEFAULT NULL,
  `INTERNAL_DESCRIPTION` varchar(45) DEFAULT NULL,
  `IMAGE` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`QUIZ_ID`),
  UNIQUE KEY `QUIZ_ID_UNIQUE` (`QUIZ_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz`
--

LOCK TABLES `quiz` WRITE;
/*!40000 ALTER TABLE `quiz` DISABLE KEYS */;
INSERT INTO `quiz` VALUES (1,1,1,'Test Quiz 1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,2,1,'Test Quiz 2',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `quiz` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `result`
--

LOCK TABLES `result` WRITE;
/*!40000 ALTER TABLE `result` DISABLE KEYS */;
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
INSERT INTO `taker` VALUES ('user1',1,'2015-05-27 00:00:00','user1');
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
INSERT INTO `user` VALUES ('admin',1),('user1',0),('user2',0),('user3',0);
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

-- Dump completed on 2015-05-29 17:05:18
