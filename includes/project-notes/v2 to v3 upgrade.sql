use aqm;
SET foreign_key_checks = 0;
ALTER TABLE  `answer` CHANGE  `ANSWER_ID`  `ANSWER_ID` INT( 11 ) NOT NULL AUTO_INCREMENT;
UPDATE  `aqm`.`version` SET  `BUILD_NUMBER` =  '3' WHERE  `version`.`BUILD_NUMBER` =2;
SET foreign_key_checks = 1;


