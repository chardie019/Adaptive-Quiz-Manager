<?php
/**
 * A class that does some things in the move page in the edit question area
 */
class moveLogic extends editQuestionLogic {
    /**
     * Moves asingle questtion to different area
     * 
     * @param string $quizId the real quiz id
     * @param string $questionId the question ID of teh the question to be moved
     * @param string $moveToAnswerId the answer ID to be movced under
     * @return boolean returns flase if the question isn't on the same quiz
     */
    public static function moveQuestion($quizId, $questionId, $moveToAnswerId) {
        $dbLogic = new dbLogic();
        $conId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $questionId);
        if ($conId == false) {
            return false;
        } else {
            self::linkSubNodesToParent($dbLogic, $conId);
            $moveToConId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $moveToAnswerId);
            self::assignNodeToAnotherNode($dbLogic, $conId, $moveToConId);
        }
    }
    /**
     * Moves an Answer to under another question
     * 
     * @param string $quizId the real quiz associated
     * @param string $answerId the answer ID of the answer tobe moved
     * @param string $moveToQuestionId the question ID for teh answer to be moved underneath
     * @return boolean returns false if teh answer is not on this quiz
     */
    public static function moveAnswer($quizId, $answerId, $moveToQuestionId) {
        $dbLogic = new dbLogic();
        $conId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $answerId);
        if ($conId == false) {
            return false;
        } else {
            $moveToConId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $moveToQuestionId);
            self::assignNodeToAnotherNode($dbLogic, $conId, $moveToConId);
        }
    }
    /**
     * Assigns question or answer to another question or answer(node)
     * 
     * @param dbLogic $dbLogic reuse databse connection
     * @param string $conId the node be moved - connection id
     * @param string $moveToConId - the node with have the $conId moved to (under)
     */
    private static function assignNodeToAnotherNode(dbLogic $dbLogic, $conId, $moveToConId){
        $whereValueArray = array("CONNECTION_ID" => $conId);
        $setValuesArray = array("PARENT_ID" => $moveToConId);
        $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValueArray);
    }
    public static function getShortId($questionOrAnswerId, $type) {
        $dbLogic = new dbLogic();
            if ($type == "question") {
                $whereValuesArray = array("question_QUESTION_ID" => $questionOrAnswerId);
                $result = $dbLogic->select("SHORT_QUESTION_ID", "question_answer", $whereValuesArray);
                $shortId = $result["SHORT_QUESTION_ID"];
            } else {
                $whereValuesArray = array("Answer_ANSWER_ID" => $questionOrAnswerId);
                $result = $dbLogic->select("SHORT_ANSWER_ID", "question_answer", $whereValuesArray);
                $shortId = $result["SHORT_ANSWER_ID"];
            }
            return $shortId;
    }
}