<?php

class changeLinkLogic extends editQuestionLogic {
    /**
     * Updates a answer's link (LOOP_ID_CHILD) and remove below chilren if any
     * 
     * @param string $quizId the real quiz id
     * @param string $linkFromLinkPage the question's id (to be linked to)
     * @param string $answerId the answer's id
     */
    public static function updateLink ($quizId, $linkFromLinkPage, $answerId){
        $dbLogic = new dbLogic();
        //remove children (but not itself)
        $answerConId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $answerId);
        $index = self::prepareRecursiveListQuestionAnswer($dbLogic, $quizId);
        self::removeChildren($dbLogic, $index, $answerConId);
        //get the loop conn id and set the loop to it Or NULL
        $LoopConnId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $linkFromLinkPage);
        if ($LoopConnId ==false) {$LoopConnId = NULL;}
        $setValuesArray = array("LOOP_CHILD_ID" => $LoopConnId);
        $whereValuesArray = array("CONNECTION_ID" => $answerConId);
        $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray); 
    }
    /**
     * Querys the databse for the answer's contant (one field)
     * 
     * @param string $answerId
     * @return The answer data
     */
    public static function getAnswerData($answerId) {
        $dbLogic = new dbLogic();
        $whereValuesArray = array("ANSWER_ID" => $answerId);
        $answerArray = $dbLogic->select("ANSWER", "answer", $whereValuesArray);
        return $answerArray['ANSWER'];
    }
    /**
     * removes a link from an answer (set loop child id to null)
     * 
     * @param string $quizId the real quiz id
     * @param string $linkFromLinkPage the question's id
     */
    public static function removeLink ($quizId, $linkFromLinkPage) {
        $dbLogic = new dbLogic();
        $connId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $linkFromLinkPage);
        $setValuesArray = array("LOOP_CHILD_ID" => NULL);
        $whereValuesArray = array("CONNECTION_ID" => $connId);
        $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray);
    }
}