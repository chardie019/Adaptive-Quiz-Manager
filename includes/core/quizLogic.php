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
     * Returns the next question's data + feedback + question's connection_ID. returns false if fails validation
     *
     * @param $quizId The shared quiz id, as provided in the url by the user
     * @return Returns actual quiz ID, false if shared quiz id doesn't exist
     */
    static public function returnRealQuizID($quizId) {
        
        
    }
    /**
     * Returns the next question's data + feedback + question's connection_ID. returns false if fails validation
     *
     * @param $answerId The Answer ID just just submitted by the user. 
     * @param $previousQuestionId The question ID aoosiated with the answer just done (validation)
     * @param $quizId The quiz the user is on (validation)
     * @return $questionDataAndFeedback An aossicatve array of the next question data, flase if fails validation
     */
    static public function nextQuestionDataFeedbackConnectionId($answerId, $previousQuestionId, $quizId) {
    
        $dbLogic = new DB();
        
        //ensure the answer is on the same quiz & get the question aossicated
        //SELECT PARENT_ID, FEEDBACK FROM `question_answer`, answer where answer_ANSWER_ID = <answerID> AND ANSWER_ID = answer_ANSWER_ID
        $where = array("answer_ANSWER_ID" => $answerId);
        $whereColumn = array("answer_ANSWER_ID" => "ANSWER_ID");
        $parentIDArray = $dbLogic->selectWithColumns("PARENT_ID, FEEDBACK", "question_answer, answer", $where, $whereColumn);
        if (empty($parentIDArray)){
            return false; //bad client input, their answer leads nowhere
        }
        //retun with the question data at the end
        $feedback = $parentIDArray['FEEDBACK']; 
        //find the previous question using parent id
        //SELECT question_QUESTION_ID FROM `question_answer` where CONNECTION_ID = <parent_id> and quiz_QUIZ_ID = <quiz id>;
        $where = array("CONNECTION_ID" => $parentIDArray['PARENT_ID'], "quiz_QUIZ_ID" => $quiz_Id);
        $previousQuestionIdArray = $dbLogic->select("question_QUESTION_ID", "question_answer", $where);
        //if question_QUESTION_ID != <question input>; returen false, else, keep going
        if ($previousQuestionIdArray['question_QUESTION_ID'] != $previousQuestionId){
            return false; //bad client input, are they trying to get to another quiz? too bad it's private
        } 
        //get the answer's connection id
        //SELECT CONNECTION_ID FROM `question_answer` where answer_ANSWER_ID = <answerid input>;
        $where = array("answer_ANSWER_ID" => $answerId);
        $connectionIdArray = $dbLogic->select("CONNECTION_ID", "question_answer", $where);
        ////using the connection id, get the next questions's data now
        //SELECT question.* FROM `question_answer`, question where PARENT_ID = 6 and QUESTION_ID = question_QUESTION_ID;
        $where = array("PARENT_ID" => $connectionIdArray['CONNECTION_ID']);
        $whereColumn = array("QUESTION_ID" => "question_QUESTION_ID");
        $questionDataAndFeedback = $dbLogic->selectWithColumnsOrder("question.*, CONNECTION_ID", "question_answer, question", $where, $whereColumn, "DEPTH");
        //add the feedback and connectionID on now
        $questionDataAndFeedback['FEEDBACK'] = $feedback;
        return $questionDataAndFeedback;
    }
}
?>