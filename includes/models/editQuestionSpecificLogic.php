<?php
/**
 * Contains some specfic logic for teh edit question page only
 */

class editQuestionSpecificLogic {
        /**
     * Check if there is question attached to an answer 
     * 
     * Used to stop having two questiosn being put on an answer
     * 
     * @param string $answerId The answer to check of question flows on or not
     * @return boolean returns true is this is the end, otherwise false
     */
    public static function isThereAQuestionAttachedtoThisAnswer($answerId){
        $dbLogic = new dbLogic();
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
}