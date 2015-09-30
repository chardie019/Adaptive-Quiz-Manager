UPDATE  `question_answer` SET  `question_QUESTION_ID` = NULL ,
`answer_ANSWER_ID` =  '14',
`PARENT_ID` =  '13',
`LOOP_CHILD_ID` =  '38',
`quiz_QUIZ_ID` =  '1' WHERE  `question_answer`.`CONNECTION_ID` =21;

UPDATE  `question_answer` SET  `question_QUESTION_ID` = NULL ,
`answer_ANSWER_ID` =  '8',
`PARENT_ID` =  '10',
`LOOP_CHILD_ID` =  '38',
`quiz_QUIZ_ID` =  '1' WHERE  `question_answer`.`CONNECTION_ID` =15;

UPDATE  `question_answer` SET  `question_QUESTION_ID` = NULL ,
`answer_ANSWER_ID` =  '9',
`PARENT_ID` =  '11',
`LOOP_CHILD_ID` =  '38',
`quiz_QUIZ_ID` =  '1' WHERE  `question_answer`.`CONNECTION_ID` =16;

UPDATE  `question_answer` SET  `question_QUESTION_ID` = NULL ,
`answer_ANSWER_ID` =  '10',
`PARENT_ID` =  '11',
`LOOP_CHILD_ID` =  '38',
`quiz_QUIZ_ID` =  '1' WHERE  `question_answer`.`CONNECTION_ID` =17;

UPDATE  `question_answer` SET  `question_QUESTION_ID` = NULL ,
`answer_ANSWER_ID` =  '11',
`PARENT_ID` =  '12',
`LOOP_CHILD_ID` =  '38',
`quiz_QUIZ_ID` =  '1' WHERE  `question_answer`.`CONNECTION_ID` =18;

UPDATE  `question_answer` SET  `question_QUESTION_ID` = NULL ,
`answer_ANSWER_ID` =  '12',
`PARENT_ID` =  '12',
`LOOP_CHILD_ID` =  '38',
`quiz_QUIZ_ID` =  '1' WHERE  `question_answer`.`CONNECTION_ID` =19;

UPDATE  `question_answer` SET  `question_QUESTION_ID` = NULL ,
`answer_ANSWER_ID` =  '13',
`PARENT_ID` =  '13',
`LOOP_CHILD_ID` =  '38',
`quiz_QUIZ_ID` =  '1' WHERE  `question_answer`.`CONNECTION_ID` =20;

DELETE FROM `aqm`.`question_answer` WHERE `question_answer`.`CONNECTION_ID` = 39;
DELETE FROM `aqm`.`question_answer` WHERE `question_answer`.`CONNECTION_ID` = 40;
DELETE FROM `aqm`.`question_answer` WHERE `question_answer`.`CONNECTION_ID` = 41;
DELETE FROM `aqm`.`question_answer` WHERE `question_answer`.`CONNECTION_ID` = 42;
DELETE FROM `aqm`.`question_answer` WHERE `question_answer`.`CONNECTION_ID` = 43;
DELETE FROM `aqm`.`question_answer` WHERE `question_answer`.`CONNECTION_ID` = 44;
DELETE FROM `aqm`.`question_answer` WHERE `question_answer`.`CONNECTION_ID` = 45;

UPDATE `version` SET  `BUILD_NUMBER`='6';













