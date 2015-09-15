-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 15, 2015 at 11:29 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aqm`
--

-- --------------------------------------------------------

--
-- Table structure for table `question_answer`
--

CREATE TABLE IF NOT EXISTS `question_answer` (
  `question_QUESTION_ID` int(11) DEFAULT NULL,
  `answer_ANSWER_ID` int(11) DEFAULT NULL,
  `CONNECTION_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PARENT_ID` int(11) DEFAULT NULL,
  `LOOP_CHILD_ID` int(11) DEFAULT NULL,
  `TYPE` text NOT NULL,
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `DEPTH` int(11) NOT NULL,
  PRIMARY KEY (`CONNECTION_ID`),
  KEY `PARENT_ID` (`PARENT_ID`),
  KEY `quiz_QUIZ_ID` (`quiz_QUIZ_ID`),
  KEY `CHILD_ID` (`LOOP_CHILD_ID`),
  KEY `question_QUESTION_ID` (`question_QUESTION_ID`,`answer_ANSWER_ID`),
  KEY `answer_ANSWER_ID` (`answer_ANSWER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `question_answer`
--

INSERT INTO `question_answer` (`question_QUESTION_ID`, `answer_ANSWER_ID`, `CONNECTION_ID`, `PARENT_ID`, `LOOP_CHILD_ID`, `TYPE`, `quiz_QUIZ_ID`, `DEPTH`) VALUES
(1, NULL, 1, NULL, NULL, 'question', 1, 0),
(NULL, 1, 2, 1, NULL, 'answer', 1, 1),
(NULL, 2, 3, 1, NULL, 'answer', 1, 1),
(2, NULL, 4, 2, NULL, 'question', 1, 2),
(3, NULL, 5, 3, NULL, 'question', 1, 2),
(NULL, 3, 6, 4, NULL, 'answer', 1, 3),
(NULL, 4, 7, 4, NULL, 'answer', 1, 3),
(NULL, 5, 8, 5, NULL, 'answer', 1, 4),
(NULL, 6, 9, 5, NULL, 'answer', 1, 4),
(4, NULL, 10, 6, NULL, 'question', 1, 5),
(5, NULL, 11, 7, NULL, 'question', 1, 5),
(6, NULL, 12, 8, NULL, 'question', 1, 5),
(7, NULL, 13, 9, NULL, 'question', 1, 5),
(NULL, 7, 14, 10, NULL, 'answer', 1, 6),
(NULL, 8, 15, 10, 38, 'answer', 1, 6),
(NULL, 9, 16, 11, 38, 'answer', 1, 6),
(NULL, 10, 17, 11, 38, 'answer', 1, 6),
(NULL, 11, 18, 12, 38, 'answer', 1, 6),
(NULL, 12, 19, 12, 38, 'answer', 1, 6),
(NULL, 13, 20, 13, 38, 'answer', 1, 6),
(NULL, 14, 21, 13, 38, 'answer', 1, 6),
(8, NULL, 38, 14, NULL, 'question', 1, 7);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD CONSTRAINT `question_answer_ibfk_12` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`),
  ADD CONSTRAINT `question_answer_ibfk_13` FOREIGN KEY (`answer_ANSWER_ID`) REFERENCES `answer` (`ANSWER_ID`),
  ADD CONSTRAINT `question_answer_ibfk_14` FOREIGN KEY (`PARENT_ID`) REFERENCES `question_answer` (`CONNECTION_ID`),
  ADD CONSTRAINT `question_answer_ibfk_15` FOREIGN KEY (`LOOP_CHILD_ID`) REFERENCES `question_answer` (`CONNECTION_ID`),
  ADD CONSTRAINT `question_answer_ibfk_16` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
