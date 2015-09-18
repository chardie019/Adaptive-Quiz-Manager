INSERT INTO `answer` (`ANSWER_ID`, `ANSWER`, `FEEDBACK`, `IS_CORRECT`) VALUES
('41', 'Yes.', 'Good.', '1'),
('42', 'Yes', 'Good.', '1'),
('43', 'Answer.', 'Done', '1');

INSERT INTO `question` (`QUESTION_ID`, `CONTENT`, `QUESTION`, `IMAGE`, `IMAGE_ALT`) VALUES
('21', 'CONTENT!', 'See the timer?', NULL, ''),
('22', 'Try waiting 5 minutes and then answering.', 'Did it go down?', NULL, ''),
('23', 'CONTENT!', 'This is the final question', NULL, '');

INSERT INTO `question_answer` (`question_QUESTION_ID`, `answer_ANSWER_ID`, `CONNECTION_ID`, `PARENT_ID`, `LOOP_CHILD_ID`, `TYPE`, `quiz_QUIZ_ID`, `DEPTH`) VALUES
('21', NULL, '39', NULL, NULL, 'question', '31', '1'),
(NULL, '41', '40', '39', NULL, 'answer', '31', '2'),
('22', NULL, '41', '40', NULL, 'question', '31', '3'),
(NULL, '42', '42', '41', NULL, 'answer', '31', '1'),
('23', NULL, '43', '42', NULL, 'question', '31', '2'),
(NULL, '43', '44', '43', NULL, 'answer', '31', '1');

INSERT INTO `quiz` (`QUIZ_ID`, `SHARED_QUIZ_ID`, `VERSION`, `QUIZ_NAME`, `DESCRIPTION`, `IS_PUBLIC`, `NO_OF_ATTEMPTS`, `TIME_LIMIT`, `IS_SAVABLE`, `DATE_OPEN`, `DATE_CLOSED`, `INTERNAL_DESCRIPTION`, `IMAGE`, `IMAGE_ALT`, `IS_ENABLED`) VALUES
('31', '31', '0', 'Timer Test', 'Tests the timer', '1', '0', '00:05:00', '0', '2015-09-18 00:00:00', NULL, '', '', '', '1');
