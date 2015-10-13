<?php
/**
 * Class quizLogic
 *
 * Provides generic functions to supoport quiz use case
 * These functions usually query the databse and return some result.
 * some classes extends this also
 */
class quizLogic
{   
    /**
     * Runs a query against the databse to ensure quiz exists
     * 
     * @param string $quizId the quizId to verify it exists
     * @return string|boolean the quizID if it's exists, false if not
     */
    public static function verifyQuizIdExistsReturnQuizId ($quizId){
        $dbLogic = new dbLogic();
        $whereValuesArray = array("QUIZ_ID" => $quizId);
        $quizIdArray = $dbLogic->select("QUIZ_ID", "quiz", $whereValuesArray);
        if ($quizIdArray['QUIZ_ID'] == $quizId){
            return $quizId;
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
        $dbLogic = new dbLogic();
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
     * Returns the Parent ID of a quesion or answer
     * 
     * @param dbLogic $dbLogic reuse datbase connection
     * @param string $questionOrAnswerId the question or answer ID (not con id)
     * @param string $type the type of node "question" or "answer"
     * @return string|boolean retursn the parent id or flase if not found
     */
    public static function returnParentId(dbLogic $dbLogic, $questionOrAnswerId, $type){
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
     * @param dbLogic $dbLogic resue the current connection to teh databse
     * @param string $quizId The quiz ID
     * @param string $answerId The question ID to check
     * @return boolean|string CONNECTION_ID if is on same quiz, false otherwise
     */
    protected static function checkAnswerBelongsToQuizReturnId(dbLogic $dbLogic, $quizId, $answerId){
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
     * @param dbLogic $dbLogic resue the current connection to teh databse
     * @param string $quizId The quiz ID
     * @param string $questionId The question ID to check
     * @return boolean|string CONNECTION_ID if is on same quiz, false otherwise
     */
    protected static function checkQuestionBelongsToQuizReturnId(dbLogic $dbLogic, $quizId, $questionId){
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
        $dbLogic = new dbLogic();
        
        //set the shared quiz ID to the correct one
        //UPDATE quiz SET SHARED_QUIZ_ID =  '16' WHERE QUIZ_ID = 16;
        $setValue = array("SHARED_QUIZ_ID" => $updateSharedQuizID);
        $where = array("QUIZ_ID" => $quizId);
        $dbLogic->updateSetWhere("quiz", $setValue, $where);
    }
    /**
     * Returns the Real Quiz ID (latest version) for quiz.
     *
     * @param string $sharedQuizId The shared quiz id
     * @return string|boolean Returns real quiz ID, flase if not exist
     */
    static public function returnRealQuizID($sharedQuizId) {
        assert(is_string($sharedQuizId));
        $dbLogic = new dbLogic();
        
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
     * Returns the Shared Quiz ID for quiz.
     *
     * @param string $quizId The actual quiz id
     * @return string Returns shared quiz ID
     */
    public static function returnSharedQuizID($quizId) {
        assert(is_string($quizId) || is_int($quizId));
        $dbLogic = new dbLogic();
        //return the real quiz ID
        //SELECT SHARED_QUIZ_ID from quiz where quiz_id = 1;
        $where = array("QUIZ_ID" => $quizId);
        $result = $dbLogic->select("SHARED_QUIZ_ID", "quiz", $where);
        return $result['SHARED_QUIZ_ID'];
    }
}