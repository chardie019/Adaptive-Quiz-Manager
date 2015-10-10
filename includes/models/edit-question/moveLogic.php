<?php

class moveLogic extends editQuestionLogic {
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