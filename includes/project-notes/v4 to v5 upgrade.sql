SET foreign_key_checks = 0;
DELETE FROM `answer`;
DELETE FROM `question`;
DELETE FROM `question_answer`;


INSERT INTO `answer` (`ANSWER_ID`, `ANSWER`, `FEEDBACK`, `IS_CORRECT`) VALUES
(1, 'Go to 2', 'feedback2', 3),
(2, 'Go to 3', 'feedback3', 3),
(3, 'Go to 4', 'feedback4', 3),
(4, 'Go to 5', 'feedback5', 3),
(5, 'Go to 6', 'feedback6', 3),
(6, 'Go to 7', 'feedback7', 3),
(7, 'Go to 8 - end of quiz', 'feedback8', 3),
(8, 'Go to 8 - end of quiz', 'feedback9', 3),
(9, 'Go to 8 - end of quiz', 'feedback10', 3),
(10, 'Go to 8 - end of quiz', 'feedback11', 3),
(11, 'Go to 8 - end of quiz', 'feedback12', 3),
(12, 'Go to 8 - end of quiz', 'feedback13', 3),
(13, 'Go to 8 - end of quiz', 'feedback14', 3),
(14, 'Go to 8 - end of quiz', 'feedback15', 3),
(15, 'Go to 2 (quiz 2)', 'feedback2', 3),
(16, 'Go to 3 (quiz 2)', 'feedback3 (quiz 2)', 3),
(17, 'Go to 4 (quiz 2)', 'feedback4 (quiz 2)', 3),
(18, 'Go to 5 (quiz 2)', 'feedback5 (quiz 2)', 3),
(19, 'Go to 6 (quiz 2)', 'feedback6 (quiz 2)', 3),
(20, 'Go to 7 (quiz 2)', 'feedback7 (quiz 2)', 3),
(21, 'Go to 8 - end of quiz (quiz 2)', 'feedback8 (quiz 2)', 3),
(22, 'Go to 8 - end of quiz (quiz 2)', 'feedback9 (quiz 2)', 3),
(23, 'Go to 8 - end of quiz (quiz 2)', 'feedback10 (quiz 2)', 3),
(24, 'Go to 8 - end of quiz (quiz 2)', 'feedback11 (quiz 2)', 3),
(25, 'Go to 8 - end of quiz (quiz 2)', 'feedback12 (quiz 2)', 3),
(26, 'Go to 8 - end of quiz (quiz 2)', 'feedback13 (quiz 2)', 3),
(27, 'Go to 8 - end of quiz (quiz 2)', 'feedback14 (quiz 2)', 3),
(28, 'Go to 8 - end of quiz (quiz 2)', 'feedback15 (quiz 2)', 3),
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

INSERT INTO `question` (`QUESTION_ID`, `CONTENT`, `QUESTION`, `IMAGE`, `IMAGE_ALT`) VALUES
(1, 'SomeContent1', 'The first Question', '1.png', NULL),
(2, 'SomeContent2', 'The second Question', '2.png', NULL),
(3, 'SomeContent3', 'The Third Question', '3.png', NULL),
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
(NULL, 8, 15, 10, NULL, 'answer', 1, 6),
(NULL, 9, 16, 11, NULL, 'answer', 1, 6),
(NULL, 10, 17, 11, NULL, 'answer', 1, 6),
(NULL, 11, 18, 12, NULL, 'answer', 1, 6),
(NULL, 12, 19, 12, NULL, 'answer', 1, 6),
(NULL, 13, 20, 13, NULL, 'answer', 1, 6),
(NULL, 14, 21, 13, NULL, 'answer', 1, 6),
(8, NULL, 38, 14, NULL, 'question', 1, 7),
(8, NULL, 39, 15, NULL, 'question', 1, 7),
(8, NULL, 40, 16, NULL, 'question', 1, 7),
(8, NULL, 41, 17, NULL, 'question', 1, 7),
(8, NULL, 42, 18, NULL, 'question', 1, 7),
(8, NULL, 43, 19, NULL, 'question', 1, 7),
(8, NULL, 44, 20, NULL, 'question', 1, 7),
(8, NULL, 45, 21, 1, 'question', 1, 7);

SET foreign_key_checks = 1;

ALTER TABLE  `editor` ADD INDEX (  `ADDED_BY` ) ;

ALTER TABLE  `editor` ADD FOREIGN KEY (  `ADDED_BY` ) REFERENCES  `aqm`.`user` (
`USERNAME`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

INSERT INTO  `aqm`.`user` (

`USERNAME` ,
`ADMIN_TOGGLE`
)
VALUES (
'chart08',  '1'
), (
'lkentwel',  '1'
);
INSERT INTO `aqm`.`editor` (`user_USERNAME`, `shared_SHARED_QUIZ_ID`, `ADDED_AT`, `ADDED_BY`) VALUES ('chart08', '1', '2015-09-12 00:00:00', 'testuser'), ('hbaile04', '1', '2015-09-12 00:00:00', 'testuser'), ('lkentwel', '1', '2015-09-12 00:00:00', 'testuser'), ('jtulip', '1', '2015-09-12 00:00:00', 'testuser'), ('jgraha50', '1', '2015-09-12 00:00:00', 'testuser');

ALTER TABLE  `taker` ADD INDEX (  `ADDED_BY` ) ;


UPDATE  `taker` SET  `user_USERNAME` =  'jsmith04',
`shared_SHARED_QUIZ_ID` =  '1',
`ADDED_BY` =  'testuser' WHERE  `taker`.`user_USERNAME` =  'jsmith04' AND  `taker`.`shared_SHARED_QUIZ_ID` =1 LIMIT 1;

ALTER TABLE  `taker` ADD FOREIGN KEY (  `ADDED_BY` ) REFERENCES  `aqm`.`user` (
`USERNAME`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

UPDATE `aqm`.`answer` SET `IS_CORRECT` = '2' WHERE `IS_CORRECT` = '3';

UPDATE  `aqm`.`version` SET  `BUILD_NUMBER` =  '5';
