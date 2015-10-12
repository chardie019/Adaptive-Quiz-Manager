use aqm;

ALTER TABLE `quiz` CHANGE `IS_SAVABLE` `IS_SAVABLE` TINYINT(1) NOT NULL DEFAULT '1';

UPDATE  `aqm`.`version` SET  `BUILD_NUMBER` =  '9';

UPDATE  `aqm`.`question` SET  `CONTENT` =  'SomeContent
(insert quiz specifc remarks)
END OF QUIZ',
`QUESTION` =  'The Eighth Question' WHERE  `question`.`QUESTION_ID` =8;
UPDATE  `aqm`.`question` SET  `IMAGE` =  '3.png' WHERE  `question`.`QUESTION_ID` =3;
UPDATE  `aqm`.`question` SET  `IMAGE` =  '2.png' WHERE  `question`.`QUESTION_ID` =2;