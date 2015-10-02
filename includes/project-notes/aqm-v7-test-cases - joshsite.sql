-- DROP ALL TABLES
use joshuagr_aqm;

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

-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 30, 2015 at 04:17 PM
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
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `ANSWER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ANSWER` varchar(255) NOT NULL,
  `FEEDBACK` varchar(255) DEFAULT NULL,
  `IS_CORRECT` tinyint(2) NOT NULL,
  PRIMARY KEY (`ANSWER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`ANSWER_ID`, `ANSWER`, `FEEDBACK`, `IS_CORRECT`) VALUES
(1, 'Go to 2 updated 33333', 'feedback2', 2),
(2, 'Go to 3', 'feedback3', 2),
(3, 'Go to 4', 'feedback4', 2),
(4, 'Go to 5', 'feedback5', 2),
(5, 'Go to 6', 'feedback6', 2),
(6, 'Go to 7', 'feedback7', 2),
(7, 'Go to 8 - end of quiz', 'feedback8', 2),
(8, 'Go to 8 - end of quiz', 'feedback9', 2),
(9, 'Go to 8 - end of quiz', 'feedback10', 2),
(10, 'Go to 8 - end of quiz', 'feedback11', 2),
(11, 'Go to 8 - end of quiz', 'feedback12', 2),
(12, 'Go to 8 - end of quiz', 'feedback13', 2),
(13, 'Go to 8 - end of quiz', 'feedback14', 2),
(14, 'Go to 8 - end of quiz', 'feedback15', 2),
(15, 'Go to 2 (quiz 2)', 'feedback2', 2),
(16, 'Go to 3 (quiz 2)', 'feedback3 (quiz 2)', 2),
(17, 'Go to 4 (quiz 2)', 'feedback4 (quiz 2)', 2),
(18, 'Go to 5 (quiz 2)', 'feedback5 (quiz 2)', 2),
(19, 'Go to 6 (quiz 2)', 'feedback6 (quiz 2)', 2),
(20, 'Go to 7 (quiz 2)', 'feedback7 (quiz 2)', 2),
(21, 'Go to 8 - end of quiz (quiz 2)', 'feedback8 (quiz 2)', 2),
(22, 'Go to 8 - end of quiz (quiz 2)', 'feedback9 (quiz 2)', 2),
(23, 'Go to 8 - end of quiz (quiz 2)', 'feedback10 (quiz 2)', 2),
(24, 'Go to 8 - end of quiz (quiz 2)', 'feedback11 (quiz 2)', 2),
(25, 'Go to 8 - end of quiz (quiz 2)', 'feedback12 (quiz 2)', 2),
(26, 'Go to 8 - end of quiz (quiz 2)', 'feedback13 (quiz 2)', 2),
(27, 'Go to 8 - end of quiz (quiz 2)', 'feedback14 (quiz 2)', 2),
(28, 'Go to 8 - end of quiz (quiz 2)', 'feedback15 (quiz 2)', 2),
(29, 'To set someone`s work on fire.', 'You should consider looking into the definition of Plagiarism more deeply', 0),
(30, 'Stealing someone`s work and passing it off as your own.', 'This is the most appropriate definition. ', 1),
(31, 'Playing video games competitively.', 'You should consider looking into the definition of Plagiarism more deeply', 0),
(32, 'To steal someone`s work to sell.', 'You should consider looking into the definition of Plagiarism more deeply', 0),
(33, 'When you don`t know the original author.', 'Does this choice seem morally and ethically correct to you? What are the legal date requirements of copyright?', 0),
(34, 'If the original document was published in a different country to you.', 'Does this choice seem morally and ethically correct to you? What are the legal date requirements of copyright?', 0),
(35, 'If the document is over 1 year old.', 'Does this choice seem morally and ethically correct to you? What are the legal date requirements of copyright?', 0),
(36, 'It is never acceptable.', 'Correct. Plagiarism cannot be justified.', 1),
(37, 'Harvard Referencing.', 'Which of these IS NOT a known referencing style.', 0),
(38, 'APA Referencing.', 'Which of these IS NOT a known referencing style.', 0),
(39, 'Chicago Referencing.', 'Which of these IS NOT a known referencing style.', 0),
(40, 'Wagga Wagga Referencing.', 'Correct. Wagga Wagga Referencing is not a widely accepted referencing style.  yet', 1);

-- --------------------------------------------------------

--
-- Table structure for table `answer_keyword`
--

CREATE TABLE IF NOT EXISTS `answer_keyword` (
  `answer_ANSWER_ID` int(11) NOT NULL,
  `answer_KEYWORD_ID` int(11) NOT NULL,
  PRIMARY KEY (`answer_ANSWER_ID`,`answer_KEYWORD_ID`),
  KEY `answer_KEYWORD_ID_idx` (`answer_KEYWORD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `editor`
--

CREATE TABLE IF NOT EXISTS `editor` (
  `user_USERNAME` varchar(10) NOT NULL,
  `shared_SHARED_QUIZ_ID` int(11) NOT NULL,
  `ADDED_AT` datetime NOT NULL,
  `ADDED_BY` varchar(10) NOT NULL,
  PRIMARY KEY (`user_USERNAME`,`shared_SHARED_QUIZ_ID`),
  KEY `fk_Editors_Quiz1_idx` (`shared_SHARED_QUIZ_ID`),
  KEY `user_USERNAME` (`user_USERNAME`),
  KEY `ADDED_BY` (`ADDED_BY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `editor`
--

INSERT INTO `editor` (`user_USERNAME`, `shared_SHARED_QUIZ_ID`, `ADDED_AT`, `ADDED_BY`) VALUES
('chart08', 1, '2015-09-12 00:00:00', 'testuser'),
('chart08', 2, '2015-09-19 00:00:00', 'hbaile04'),
('chart08', 3, '2015-09-19 00:00:00', 'testuser'),
('hbaile04', 1, '2015-09-12 00:00:00', 'testuser'),
('hbaile04', 2, '2015-09-19 00:00:00', 'testuser'),
('hbaile04', 3, '2015-09-19 00:00:00', 'testuser'),
('jgraha50', 1, '2015-09-12 00:00:00', 'testuser'),
('jgraha50', 2, '2015-09-19 00:00:00', 'testuser'),
('jgraha50', 3, '2015-09-19 00:00:00', 'testuser'),
('jtulip', 1, '2015-09-12 00:00:00', 'testuser'),
('jtulip', 2, '2015-09-19 00:00:00', 'lkentwel'),
('jtulip', 3, '2015-09-19 00:00:00', 'testuser'),
('lkentwel', 1, '2015-09-12 00:00:00', 'testuser'),
('lkentwel', 2, '2015-09-19 00:00:00', 'testuser'),
('lkentwel', 3, '2015-09-19 00:00:00', 'testuser'),
('testuser', 1, '2015-08-14 00:00:00', 'testuser'),
('testuser', 2, '2015-09-19 00:00:00', 'testuser');

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE IF NOT EXISTS `keyword` (
  `KEYWORD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `KEYWORD` varchar(45) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  PRIMARY KEY (`KEYWORD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `QUESTION_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CONTENT` text NOT NULL,
  `QUESTION` varchar(255) NOT NULL,
  `IMAGE` varchar(255) DEFAULT NULL,
  `IMAGE_ALT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QUESTION_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`QUESTION_ID`, `CONTENT`, `QUESTION`, `IMAGE`, `IMAGE_ALT`) VALUES
(1, 'SomeContent1', 'The first Question', '1.png', NULL),
(2, 'SomeContent2', 'The second QuestionU2', 'Nope.png', 'alttt'),
(3, 'SomeContent3', 'The Third Question', NULL, 't'),
(4, 'SomeContent4', 'The forth Question', '4.png', NULL),
(5, 'SomeContent5', 'The Fift Question', '5.png', NULL),
(6, 'SomeContent6', 'The Sixth Question', '6.png', NULL),
(7, 'SomeContent7', 'The Seveth Question', '7.png', NULL),
(8, 'SomeContent8', 'The Eighth Question <br /> (insert quiz specifc remarks) END OF QUIZ', '8.png', NULL),
(9, 'SomeContent1', 'The first Question(quiz 2)', '1.png', NULL),
(10, 'SomeContent2', 'The second Question(quiz 2)', '2.png', NULL),
(11, 'SomeContent3', 'The third Question(quiz 2)', '3.png', NULL),
(12, 'SomeContent4', 'The forth Question(quiz 2)', '4.png', NULL),
(13, 'SomeContent5', 'The fifth Question(quiz 2)', '5.png', NULL),
(14, 'SomeContent6', 'The sixth Question(quiz 2)', '6.png', NULL),
(15, 'SomeContent7', 'The seventh Question(quiz 2)', '7.png', NULL),
(16, 'SomeContent8', 'The eigth Question(quiz 2) END OF QUIZ', '8.png', NULL),
(17, 'Definition, Referencing', 'Which phrase most accurately describes plagiarism?', NULL, NULL),
(18, 'Morality', 'When is plagiarism acceptable?', NULL, NULL),
(19, 'Referencing', 'Which of the following is not a recognised referencing style:', NULL, NULL),
(20, 'END OF QUIZ CONTENT - plagiarism', 'Thankyou for using the plagiarism quiz', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `question_keyword`
--

CREATE TABLE IF NOT EXISTS `question_keyword` (
  `question_QUESTION_ID` int(11) NOT NULL,
  `question_KEYWORD_ID` int(11) NOT NULL,
  PRIMARY KEY (`question_QUESTION_ID`,`question_KEYWORD_ID`),
  KEY `KEYWORD_ID_idx` (`question_KEYWORD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `QUIZ_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SHARED_QUIZ_ID` int(11) DEFAULT NULL,
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
  `IS_ENABLED` tinyint(4) NOT NULL,
  `CONSISTENT_STATE` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`QUIZ_ID`),
  KEY `SHARED_QUIZ_ID` (`SHARED_QUIZ_ID`),
  KEY `IS_PUBLIC` (`IS_PUBLIC`,`NO_OF_ATTEMPTS`,`TIME_LIMIT`,`DATE_OPEN`,`DATE_CLOSED`,`IS_ENABLED`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`QUIZ_ID`, `SHARED_QUIZ_ID`, `VERSION`, `QUIZ_NAME`, `DESCRIPTION`, `IS_PUBLIC`, `NO_OF_ATTEMPTS`, `TIME_LIMIT`, `IS_SAVABLE`, `DATE_OPEN`, `DATE_CLOSED`, `INTERNAL_DESCRIPTION`, `IMAGE`, `IMAGE_ALT`, `IS_ENABLED`, `CONSISTENT_STATE`) VALUES
(1, 1, 1, 'Test Quiz 1', 'This is a Test case Quiz', 1, NULL, '00:00:00', NULL, '2015-01-01 06:41:00', NULL, NULL, NULL, NULL, 1, 1),
(32, 2, 1, 'Test Quiz 2', 'This is a Test case (No Qs & A''s)', 1, NULL, '00:00:00', NULL, '2015-01-01 06:41:00', NULL, NULL, NULL, NULL, 1, 1),
(33, 3, 1, 'Plagiarism: Beginner', 'Test case Quiz (no Q & A''s)', 0, NULL, '00:00:00', NULL, '2015-01-01 06:41:00', NULL, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_keyword`
--

CREATE TABLE IF NOT EXISTS `quiz_keyword` (
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `quiz_KEYWORD_ID` int(11) NOT NULL,
  PRIMARY KEY (`quiz_QUIZ_ID`,`quiz_KEYWORD_ID`),
  KEY `KEYWORD_ID_idx` (`quiz_KEYWORD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `RESULT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_USERNAME` varchar(10) NOT NULL,
  `quiz_QUIZ_ID` int(11) NOT NULL,
  `shared_SHARED_QUIZ_ID` int(11) NOT NULL,
  `STARTED_AT` datetime NOT NULL,
  `FINISHED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`RESULT_ID`),
  KEY `fk_Result_User1_idx` (`user_USERNAME`),
  KEY `fk_Result_Quiz1_idx` (`quiz_QUIZ_ID`),
  KEY `shared_SHARED_QUIZ_ID` (`shared_SHARED_QUIZ_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `result_answer`
--

CREATE TABLE IF NOT EXISTS `result_answer` (
  `result_RESULT_ID` int(11) NOT NULL,
  `question_QUESTION_ID` int(11) NOT NULL,
  `PASS_NO` int(11) NOT NULL,
  `ANSWER` varchar(45) DEFAULT NULL,
  `ANSWERED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`result_RESULT_ID`,`question_QUESTION_ID`,`PASS_NO`),
  KEY `fk_result_answer_question1_idx` (`question_QUESTION_ID`),
  KEY `PASS_NO` (`PASS_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `taker`
--

CREATE TABLE IF NOT EXISTS `taker` (
  `user_USERNAME` varchar(10) NOT NULL,
  `shared_SHARED_QUIZ_ID` int(11) NOT NULL,
  `ADDED_AT` datetime NOT NULL,
  `ADDED_BY` varchar(10) NOT NULL,
  PRIMARY KEY (`user_USERNAME`,`shared_SHARED_QUIZ_ID`),
  KEY `fk_takers_quiz1_idx` (`shared_SHARED_QUIZ_ID`),
  KEY `ADDED_BY` (`ADDED_BY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taker`
--

INSERT INTO `taker` (`user_USERNAME`, `shared_SHARED_QUIZ_ID`, `ADDED_AT`, `ADDED_BY`) VALUES
('chart08', 1, '2015-09-19 00:00:00', 'testuser'),
('chart08', 2, '2015-09-19 00:00:00', 'testuser'),
('hbaile04', 1, '2015-09-19 00:00:00', 'testuser'),
('hbaile04', 2, '2015-09-19 00:00:00', 'testuser'),
('jgraha50', 1, '2015-09-19 00:00:00', 'testuser'),
('jgraha50', 2, '2015-09-19 00:00:00', 'testuser'),
('jtulip', 1, '2015-09-19 00:00:00', 'testuser'),
('jtulip', 2, '2015-09-19 00:00:00', 'testuser'),
('lkentwel', 1, '2015-09-19 00:00:00', 'testuser'),
('lkentwel', 2, '2015-09-19 00:00:00', 'testuser'),
('testuser', 1, '2015-09-19 00:00:00', 'testuser'),
('testuser', 2, '2015-09-19 00:00:00', 'testuser');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `USERNAME` varchar(10) NOT NULL,
  `ADMIN_TOGGLE` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`USERNAME`),
  KEY `ADMIN_TOGGLE` (`ADMIN_TOGGLE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USERNAME`, `ADMIN_TOGGLE`) VALUES
('hbaile04', 0),
('jgraha50', 0),
('jtulip', 0),
('testuser', 0),
('admin', 1),
('chart08', 1),
('lkentwel', 1);

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `BUILD_NUMBER` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores website DB version';

--
-- Dumping data for table `version`
--

INSERT INTO `version` (`BUILD_NUMBER`) VALUES
(7);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer_keyword`
--
ALTER TABLE `answer_keyword`
  ADD CONSTRAINT `answer_ANSWER_ID` FOREIGN KEY (`answer_ANSWER_ID`) REFERENCES `answer` (`ANSWER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `answer_KEYWORD_ID` FOREIGN KEY (`answer_KEYWORD_ID`) REFERENCES `keyword` (`KEYWORD_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `editor`
--
ALTER TABLE `editor`
  ADD CONSTRAINT `editor_ibfk_1` FOREIGN KEY (`shared_SHARED_QUIZ_ID`) REFERENCES `quiz` (`SHARED_QUIZ_ID`),
  ADD CONSTRAINT `editor_ibfk_2` FOREIGN KEY (`ADDED_BY`) REFERENCES `user` (`USERNAME`),
  ADD CONSTRAINT `fk_Editors_User1` FOREIGN KEY (`user_USERNAME`) REFERENCES `user` (`USERNAME`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD CONSTRAINT `question_answer_ibfk_12` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`),
  ADD CONSTRAINT `question_answer_ibfk_13` FOREIGN KEY (`answer_ANSWER_ID`) REFERENCES `answer` (`ANSWER_ID`),
  ADD CONSTRAINT `question_answer_ibfk_14` FOREIGN KEY (`PARENT_ID`) REFERENCES `question_answer` (`CONNECTION_ID`),
  ADD CONSTRAINT `question_answer_ibfk_15` FOREIGN KEY (`LOOP_CHILD_ID`) REFERENCES `question_answer` (`CONNECTION_ID`),
  ADD CONSTRAINT `question_answer_ibfk_16` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`);

--
-- Constraints for table `question_keyword`
--
ALTER TABLE `question_keyword`
  ADD CONSTRAINT `keyword_KEYWORD_ID` FOREIGN KEY (`question_KEYWORD_ID`) REFERENCES `keyword` (`KEYWORD_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `question_QUESTION_ID` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `quiz_keyword`
--
ALTER TABLE `quiz_keyword`
  ADD CONSTRAINT `KEYWORD_ID` FOREIGN KEY (`quiz_KEYWORD_ID`) REFERENCES `keyword` (`KEYWORD_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `QUIZ_ID` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `result_ibfk_15` FOREIGN KEY (`quiz_QUIZ_ID`) REFERENCES `quiz` (`QUIZ_ID`),
  ADD CONSTRAINT `result_ibfk_16` FOREIGN KEY (`shared_SHARED_QUIZ_ID`) REFERENCES `quiz` (`SHARED_QUIZ_ID`),
  ADD CONSTRAINT `result_ibfk_17` FOREIGN KEY (`user_USERNAME`) REFERENCES `user` (`USERNAME`);

--
-- Constraints for table `result_answer`
--
ALTER TABLE `result_answer`
  ADD CONSTRAINT `fk_result_answer_question1` FOREIGN KEY (`question_QUESTION_ID`) REFERENCES `question` (`QUESTION_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_result_answer_result1` FOREIGN KEY (`result_RESULT_ID`) REFERENCES `result` (`RESULT_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `taker`
--
ALTER TABLE `taker`
  ADD CONSTRAINT `fk_takers_user1` FOREIGN KEY (`user_USERNAME`) REFERENCES `user` (`USERNAME`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `taker_ibfk_1` FOREIGN KEY (`shared_SHARED_QUIZ_ID`) REFERENCES `quiz` (`SHARED_QUIZ_ID`),
  ADD CONSTRAINT `taker_ibfk_2` FOREIGN KEY (`ADDED_BY`) REFERENCES `user` (`USERNAME`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
