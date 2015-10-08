<?php
/**
 * Class quizLogic
 *
 * Provides generic functions to supoport quiz use case
 * These functions usually query the databse and return some result.
 */
class quizLogic
{   
    /**
     * Runs a query against teh databse to ensure quiz exists
     * 
     * @param string $quizId the quizId to verify it exists
     * @return string|boolean the quizID if it's exists, false if not
     */
    public static function verifyQuizIdExistsReturnQuizId ($quizId){
        $dbLogic = new DB();
        $whereValuesArray = array("QUIZ_ID" => $quizId);
        $quizIdArray = $dbLogic->select("QUIZ_ID", "quiz", $whereValuesArray);
        if ($quizIdArray['QUIZ_ID'] == $quizId){
            return $quizId;
        } else {
            return false;
        }
    }
    /**
     * Check if there is question attached to an answer 
     * 
     * Used to stop having two questiosn being put on an answer
     * 
     * @param string $answerId The answer to check of question flows on or not
     * @return boolean returns true is this is the end, otherwise false
     */
    public static function isThereAQuestionAttachedtoThisAnswer($answerId){
        $dbLogic = new DB();
        //get the connection ID
        $whereValuesArray = array("answer_ANSWER_ID" => $answerId);
        $result = $dbLogic->select("CONNECTION_ID, LOOP_CHILD_ID", "question_answer", $whereValuesArray);
        if (count($result) < 1){
            return false; //invalid input
        } else if (isset($result['LOOP_CHILD_ID'])){
            return true; //a loop is a question, so bad!
        }
        //is there a question on it or not?
        $whereValuesArray = array("PARENT_ID" => $result['CONNECTION_ID']);
        $result = $dbLogic->select("CONNECTION_ID", "question_answer", $whereValuesArray);
        if (count($result) > 0){
            return true; //There is a question (BAD!)
        } else {
            return false;
        }
    }
    
    /**
     * Returns the Data of question or Answer
     * 
     * @param string $questionOrAnswerId the ID of the question or answer
     * @param string $type indicates if teh id is a question or an answer ("question" for question etc)
     * @return array|boolean return the result array, false if operation fails
     */
    public static function returnQuestionOrAnswerData($questionOrAnswerId, $type){
        $dbLogic = new DB();
        if ($type == "question"){
            $whereValuesArray = array("QUESTION_ID" => $questionOrAnswerId);
            $result = $dbLogic->select("CONTENT, QUESTION, IMAGE, IMAGE_ALT", "question", $whereValuesArray);
        } else { //$type == "answer"
            $whereValuesArray = array("ANSWER_ID" => $questionOrAnswerId);
            $whereColumnsArray = array("ANSWER_ID" => "answer_ANSWER_ID");
            $result = $dbLogic->selectWithColumns("ANSWER, FEEDBACK, IS_CORRECT, LOOP_CHILD_ID", "answer, question_answer", $whereValuesArray, $whereColumnsArray);
        }
        if (count($result) > 0){
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * Removes a image from a question and it's file
     * 
     * @param string $sharedQuizId  The quiz associated -The real quiz ID
     * @param string $questionId The question ID associated
     * @return void
     */
    
    public static function returnParentId(DB $dbLogic, $questionOrAnswerId, $type){
        if ($type == "question"){
            $whereValuesArray = array("question_QUESTION_ID" => $questionOrAnswerId);
        } else { //$type == "answer"
            $whereValuesArray = array("answer_ANSWER_ID" => $questionOrAnswerId);
        }
        $result = $dbLogic->select("PARENT_ID", "question_answer", $whereValuesArray);
        if (count($result) > 0){
            return $result["PARENT_ID"];
        } else {
            return false;
        }
    }
        /**
     * Check if the question is on the same quiz, returns the CONNECTION_ID, false otherwise
     * 
     * @param DB $dbLogic resue the current connection to teh databse
     * @param string $quizId The quiz ID
     * @param string $answerId The question ID to check
     * @return boolean|string CONNECTION_ID if is on same quiz, false otherwise
     */
    protected static function checkAnswerBelongsToQuizReturnId(DB $dbLogic, $quizId, $answerId){
        //ensure the question  is on the same quiz
        //SELECT CONNECTION_ID FROM `question_answer`, WHERE answer_ANSWER_ID = <answerID> 
        $where = array(
            "answer_ANSWER_ID" => $answerId,
            "quiz_QUIZ_ID" => $quizId
            );
        $result = $dbLogic->select("CONNECTION_ID", "question_answer", $where);
        if (empty($result)){
            return false; //bad client input, question is not on this quiz
        } else {
            return $result['CONNECTION_ID'];
        }
    }
    /**
     * Check if the question is on the same quiz, returns false if not
     * 
     * @param DB $dbLogic resue the current connection to teh databse
     * @param string $quizId The quiz ID
     * @param string $questionId The question ID to check
     * @return boolean|string CONNECTION_ID if is on same quiz, false otherwise
     */
    protected static function checkQuestionBelongsToQuizReturnId(DB $dbLogic, $quizId, $questionId){
        //ensure the question  is on the same quiz
        //SELECT CONNECTION_ID FROM `question_answer`, WHERE question_QUESTION_ID = <questionID> 
        $where = array(
            "question_QUESTION_ID" => $questionId,
            "quiz_QUIZ_ID" => $quizId
            );
        $result = $dbLogic->select("CONNECTION_ID", "question_answer", $where);
        if (empty($result)){
            return false; //bad client input, question is not on this quiz
        } else {
            return $result['CONNECTION_ID'];
        }
    }
    /**
     * Checks if a user can edit a quiz. exits if user can not edit, returns void if so.
     * 
     * @param string $sharedQuizId The shared quiz ID
     * @param string $username The username eg jgraha50
     * @return boolean void; 
     */
    public static function canUserEditQuizElseReturnToEditQuiz ($sharedQuizId, $username) {
        $dbLogic = new DB();
        $whereValuesArray = array(
          "shared_SHARED_QUIZ_ID" => $sharedQuizId,
          "user_USERNAME" => $username
        );
        $result = $dbLogic->select("1", "editor", $whereValuesArray); //1 is retun a manningless column (if query is correct)
        if (count($result) > 0){
            return; //all good so return
        } else {
            self::jumpBackToEditQuizList("no-edit-permission");
        }
    }
    /**
     * Jumps teh user to the edit quiz list and putsthe reason in the URL
     * 
     * @param string $reason
     */
    public static function jumpBackToEditQuizList ($reason) {
        header('Location: ' . CONFIG_ROOT_URL . '/edit-quiz.php?message='.$reason);
        exit; 
    }
    /**
     * gets the REAL quiz ID by inspecting the db with teh shared quiz ID from teh URL
     * 
     * @param string $quizIDPost [optional] validate the a different quiz id instead (eg post)
     * @return string THE REAL Quiz ID
     */

    public static function getQuizIdFromUrlElseReturnToEditQuiz($quizIDPost = NULL) {
        if (isset($quizIDPost)) {
            $untrustedSharedQuizId = $quizIDPost;
        } else {
            $untrustedSharedQuizId = (string)quizLogic::returnRealQuizID(filter_input(INPUT_GET, "quiz"));
        }
        if(is_null($untrustedSharedQuizId)){
            //back to edit quiz
            self::jumpBackToEditQuizList("no-quiz-selected");  
        } else {
            //it's real so return it
            return $untrustedSharedQuizId;
        }
    }
    
    /**
     * Set the Shared Quiz ID of quiz, if sharedQuizID passed, make it this, otherwise, make it the same as quiz ID
     *
     * @param string $quizId The actual quiz id
     * @param string $sharedQuizID The shared Quiz ID to set the quiz to, else, make the same as quiz ID (optional)
     * @return void
     */
    public static function setSharedQuizId($quizId, $sharedQuizID = NULL){
        assert(is_string($quizId));
        if (is_null($sharedQuizID)){ //not passed, mkae it the sameas quizID
            $updateSharedQuizID = $quizId;
        } else {                    //shared ID passed, this is what we'll use
            $updateSharedQuizID = $sharedQuizID;
        }
        $dbLogic = new DB();
        
        //set the shared quiz ID to the correct one
        //UPDATE quiz SET SHARED_QUIZ_ID =  '16' WHERE QUIZ_ID = 16;
        $setValue = array("SHARED_QUIZ_ID" => $updateSharedQuizID);
        $where = array("QUIZ_ID" => $quizId);
        $dbLogic->updateSetWhere("quiz", $setValue, $where);
    }
    
    /**
     * Returns the Shared Quiz ID for quiz.
     *
     * @param string $quizId The actual quiz id
     * @return string Returns shared quiz ID
     */
    static public function returnSharedQuizID($quizId) {
        assert(is_string($quizId) || is_int($quizId));
        $dbLogic = new DB();
        
        //return the real quiz ID
        //SELECT SHARED_QUIZ_ID from quiz where quiz_id = 1;
        $where = array("QUIZ_ID" => $quizId);
        $result = $dbLogic->select("SHARED_QUIZ_ID", "quiz", $where);
        return $result['SHARED_QUIZ_ID'];
    }
    /**
     * Generates the start of prarametrs for URL - aka ?quiz=sharedQuizId
     * 
     * No modication/checking at all!
     * 
     * @param string $sharedQuizId the shared quiz ID
     * @return string URL params - "?quiz=sharedQuizId"
     */
    public static function returnQuizUrl ($sharedQuizId) {
        return "?quiz=".quizLogic::returnSharedQuizID($sharedQuizId);
    }


    /**
     * Returns the Real Quiz ID (latest version) for quiz.
     *
     * @param string $sharedQuizId The shared quiz id
     * @return string|boolean Returns real quiz ID, flase if not exist
     */
    static public function returnRealQuizID($sharedQuizId) {
        assert(is_string($sharedQuizId));
        $dbLogic = new DB();
        
        //return the shared quiz ID
        //SELECT UIZ_ID from quiz where shared_quiz_id = 1;
        $where = array("SHARED_QUIZ_ID" => $sharedQuizId);
        $result = $dbLogic->select("max(QUIZ_ID) as QUIZ_ID", "quiz", $where);
        if (!empty($result)){
            return $result['QUIZ_ID'];
        } else {
            return false;
        }
        
    }
    /**
     * Sets quiz be consistent (no validation, just changes db value)
     * 
     * @param DB $dbLogic Reuse current connection to dataabse
     * @param type $quizId The real quiz ID to set to be consistent
     * return void
     */
    public static function setQuizToConsistentState(DB $dbLogic, $quizId){
        assert(!is_null($quizId));
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
        $dbLogic = new DB();
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
        /*
        // NOTE: this are is Not used due to keyword not being implemented yet (or ever)
         * Also not tested all, purely working notes
        
        
        //insertion
        //quiz

        //for each table DIRECTLY relating to quizes, duplicatite it now
        //$tableArray = array('question_answer', 'quiz_keyword');
        $where = array("quiz_QUIZ_ID" => $oldQuizId);
        $results = $dbLogic->select("*", 'question_answer', $where, false);
        //TODO change ConnectionID etc
        
        
        $results['quiz_QUIZ_ID'] = $newQuizID;
        
        $results = $dbLogic->select("*", 'quiz_keyword', $where, false);
        $results['quiz_QUIZ_ID'] = $newQuizID;
        $dbLogic->insert($results, "quiz_keyword");

        //find the indirect ones
        question
        answer
        answer_keyword
        question_keyword
        */
        if (isset($type)) {
            $returnArray['quizId'] = $newQuizID;
            return $returnArray;
        } else {
            return $newQuizID;
        }
    }
    /**
     * A loop to update the Question Answer table eg update all prent ids etc
     */
     protected static function updateQuestionAnswerTableColumn(DB $dbLogic, array $arrayRow, $i, 
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
    /**
     * Searches an array row and array matrix to and updates the apprioate column in the database
     * 
     * @param DB $dbLogic reuse the current connection to teh database
     * @param array $arrayRow the current array of the foreach loop this fuction is in
     * @param integer $i the cuurent iteration of the foreach loop this function is inside
     * @param array $joinedQuestionAnswerArray the array matrix to lookup
     * @param array $newQuestionAnswerIds the Connection to used in the where cause in update databse
     * @param type $column the column to loopkup and update
     */
}