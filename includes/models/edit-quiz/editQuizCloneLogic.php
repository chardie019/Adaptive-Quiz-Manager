<?php

/**
 *  conatins the the functions assoicated with cloning quizzes
 */

class editQuizCloneLogic {
    /**
     * Sets quiz be consistent (no validation, just changes db value)
     * 
     * @param dbLogic $dbLogic Reuse current connection to dataabse
     * return void
     */
    public static function setQuizToConsistentState($quizId){
        assert(!is_null($quizId));
        $dbLogic = new dbLogic();
        $setValuesArray = array("CONSISTENT_STATE" => "1"); //one is consistent (true)
        $whereValuesArray = array("QUIZ_ID" => $quizId);
        $dbLogic->updateSetWhere("quiz", $setValuesArray, $whereValuesArray);
    }
    /**
     * Clones all data associated with a quiz if necessary
     *
     * As usual, a new quiz shares same shared quiz id
     * 
     * @param string $oldQuizId The the quiz to be cloned (real quiz ID)
     * @return string|array The new quiz's id or not needed, the existing quiz id sent to it
     * if $oldQOrAId passed, the return array is ['quizId'] & ['newId'] where the id is the question or answer id
     */
    static public function maybeCloneQuiz($oldQuizId, $oldId = NULL, $type = NULL) {
        /*die ("cloneQuiz not working yet"); */
        assert(!is_null($oldQuizId));
        $returnArray = array();
        $dbLogic = new dbLogic();
        $whereValuesArray = array("QUIZ_ID" => $oldQuizId);
        $consistentArray = $dbLogic->select("CONSISTENT_STATE", "quiz", $whereValuesArray);
        
        if ($consistentArray['CONSISTENT_STATE'] == 0){ //if already cloned (1 is consistent, zero is NOT consistent
            // bail, no cloning needed
            $returnArray['quizId'] = $oldQuizId;
            $returnArray['newId'] = $oldId;
            return $returnArray;
        }
        //else if not cloned yet        
        //get the old quiz's data
        $where = array("QUIZ_ID" => $oldQuizId);
        $quizArray = $dbLogic->select(
                /* All colums except QUIZ_ID & increase Version */
                "SHARED_QUIZ_ID, VERSION, QUIZ_NAME, DESCRIPTION, IS_PUBLIC, NO_OF_ATTEMPTS, TIME_LIMIT, IS_SAVABLE, DATE_OPEN, DATE_CLOSED, INTERNAL_DESCRIPTION, IMAGE, IMAGE_ALT, IS_ENABLED, CONSISTENT_STATE", 
                "quiz", $where);
        //build an array for re-insertion
        $newQuizArray = array();
        foreach ($quizArray as $column => $value){
            if ($column === "VERSION"){
                $value++; //increase the version
            } else if ($column === "CONSISTENT_STATE"){
                $value = 0; //zero is NOT consistent
            }
            $newQuizArray[$column] = $value; //apply change to the array
        }
        //insert quiz
        $newQuizID = $dbLogic->insert($newQuizArray, "quiz");
        //create a list of the columns to be built
        $questonAnswerColums = array("question_QUESTION_ID", "answer_ANSWER_ID", "PARENT_ID", "LOOP_CHILD_ID", "TYPE", "quiz_QUIZ_ID", "DEPTH", "SHORT_QUESTION_ID", "SHORT_ANSWER_ID"); //no primary key connection_id
        $questionColums = array("CONTENT", "QUESTION", "IMAGE", "IMAGE_ALT"); //no primary key question id
        $answerColums = array("ANSWER", "FEEDBACK", "IS_CORRECT"); //no primary key answer_id
        $questionPrimaryKey = "QUESTION_ID";
        $questionLinkedPrimaryKey = "question_QUESTION_ID";
        $answerPrimaryKey = "ANSWER_ID";
        $answerLinkedPrimaryKey = "answer_ANSWER_ID";
        //pull out all question and answer
        $where = array("quiz_QUIZ_ID" => $oldQuizId);
        $jointable = array($questionPrimaryKey => "question_QUESTION_ID");
        $jointable2 = array($answerPrimaryKey => "answer_ANSWER_ID");
        $joinedQuestionAnswerArray = $dbLogic->selectFullOuterJoin(
                /* All colums - connection needed so we can calculate the new parent ids */
                "question_QUESTION_ID, answer_ANSWER_ID, CONNECTION_ID, PARENT_ID, LOOP_CHILD_ID, TYPE, quiz_QUIZ_ID, DEPTH, SHORT_QUESTION_ID, SHORT_ANSWER_ID, " .
                "QUESTION_ID, CONTENT, QUESTION, IMAGE, IMAGE_ALT, " . 
                "ANSWER_ID, ANSWER, FEEDBACK, IS_CORRECT", 
                "question_answer", $where, "question", $jointable, "answer", $jointable2, false);
        $newQuestionAnswerArray = array();
        $newQuestionArray = array();
        $newAnswerArray = array();
        
        $i = 0;
        $buildqi = 0;
        $buildai = 0;
        foreach ($joinedQuestionAnswerArray as $arrayRow){
            if (isset($arrayRow[$questionPrimaryKey])) {
                $addToOtherTable = "question";
            } else { //isset($value[$answerPrimaryKey])
                $addToOtherTable = "answer";
            }
            foreach ($arrayRow as $column => $value2){
                //make question_answer records
                if ($column === "quiz_QUIZ_ID"){ //change the quiz id for question answers
                    $newQuestionAnswerArray[$i][$column] = $newQuizID;
                } else if (in_array ($column, $questonAnswerColums)){ //don't include the primary key, clone everything else (question answer data)
                    $newQuestionAnswerArray[$i][$column] = $value2; 
                }
                //questions
                if ($addToOtherTable == "question" && in_array($column, $questionColums)){
                    $newQuestionArray[$buildqi][$column] = $value2;
                    //primary key
                } else if ($addToOtherTable == "question" && $column == "QUESTION_ID") {
                    if ($value2 == $oldId) {
                        $oldIdPosition = $buildqi; //reorec postion to compare later to get the new id
                    }
                // answers 
                } else if ($addToOtherTable == "answer" && in_array($column, $answerColums)){ 
                    $newAnswerArray[$buildai][$column] = $value2;
                    //primary key
                } else if ($addToOtherTable == "answer" && $column == "ANSWER_ID") {
                    if ($value2 == $oldId) {
                        $oldIdPosition = $buildai; //record postion to compare later to get the new id
                    }
                }
            }
            $i++;
            if ($addToOtherTable == "answer"){
                $buildai++;
            } else if ($addToOtherTable == "question") {
                $buildqi++;
            }
        }
        //insert questions 
        $insertqi = 0;
        $questionIdsArray = array();
        foreach ($newQuestionArray as $valueArray){
            $insertQId = $dbLogic->insert($valueArray, "question"); //insert and build array of auto-increment ids
            $questionIdsArray[] = $insertQId;
            if (isset($type) && $type == "question" && $insertqi == $oldIdPosition) {
                $returnArray['newId'] = $insertQId;
            }
            $insertqi++;
        }
        //insert answers
        $insertai = 0;
        $answerIdsArray = array();
        foreach ($newAnswerArray as $valueArray){
            $insertAId = $dbLogic->insert($valueArray, "answer"); //insert and build array of auto-increment ids
            $answerIdsArray[] = $insertAId;
            if (isset($type) && $type == "answer" && $insertai == $oldIdPosition) { //same position as recorded? store it now
                $returnArray['newId'] = $insertAId;
            }
            $insertai++;
        }
        //fix question & answer Ids (edit $newQuestionAnswerArray)
        $qi = 0;
        $ai= 0;
        foreach ($newQuestionAnswerArray as $key => $arrayRow) {
            if (isset($arrayRow[$questionLinkedPrimaryKey])){
                $newQuestionAnswerArray[$key][$questionLinkedPrimaryKey] = $questionIdsArray[$qi]; //copy over the correct value - its in the same place
                $qi++;
            } else {//answerlinkedid
                $newQuestionAnswerArray[$key][$answerLinkedPrimaryKey] = $answerIdsArray[$ai];
                $ai++;
            }
        }
        //insert the question_answer data (PS: questiona nd answer ids are already fixed in the array)
        $newQuestionAnswerIds = array();
        foreach ($newQuestionAnswerArray as $column => $arrayRow) {
            $newQuestionAnswerIds[] = $dbLogic->insert($arrayRow, "question_answer");
        }
        //fix parent ids (in the database now)
        //using $joinedQuestionAnswerArray has it has the connection ids
        $i = 0;
        foreach ($joinedQuestionAnswerArray as $arrayRow) {
            //parent id
            self::updateQuestionAnswerTableColumn($dbLogic, $arrayRow, $i, $joinedQuestionAnswerArray, $newQuestionAnswerIds, "PARENT_ID");
            //loop child id
            self::updateQuestionAnswerTableColumn($dbLogic, $arrayRow, $i, $joinedQuestionAnswerArray, $newQuestionAnswerIds, "LOOP_CHILD_ID");
            $i++;
        }
        // NOTE: keywords not duplicated
        if (isset($type)) {
            $returnArray['quizId'] = $newQuizID;
            return $returnArray;
        } else {
            return $newQuizID;
        }
    }
    /**
     * Searches an array row and array matrix to and updates the apprioate column in the database
     * 
     * @param dbLogic $dbLogic reuse the current connection to teh database
     * @param array $arrayRow the current array of the foreach loop this fuction is in
     * @param integer $i the cuurent iteration of the foreach loop this function is inside
     * @param array $joinedQuestionAnswerArray the array matrix to lookup
     * @param array $newQuestionAnswerIds the Connection to used in the where cause in update databse
     * @param type $column the column to loopkup and update
     */
     protected static function updateQuestionAnswerTableColumn(dbLogic $dbLogic, array $arrayRow, $i, 
            array $joinedQuestionAnswerArray, array $newQuestionAnswerIds, $column){
        if (!is_null($arrayRow[$column])){ //do NOT update the NULL value
                $position = 0;
                foreach ($joinedQuestionAnswerArray as $conIdArrayRow){
                    //fix parent id here
                    if ($conIdArrayRow['CONNECTION_ID'] == $arrayRow[$column]){
                        //note posistion
                        $setValuesArray = array($column => $newQuestionAnswerIds[$position]);
                        $whereValuesArray = array("CONNECTION_ID" => $newQuestionAnswerIds[$i]);
                        $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray);
                        break; //break the inside for loop (the outside one keep going) don't waste time looking or something that doesn't exist
                    }
                    $position++; 
                }  
            }
    }
}
