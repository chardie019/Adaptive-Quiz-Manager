<?php
class takeQuizLogic extends quizLogic {
    /**
     * Inistalises the link variable and the html, 
     * 
     * The params must be set to Null first so there no php notice
     * 
     * @param array $questionData the assovite array from the next question data
     * @return array ['linkHtml'] & ['linkStatus'] set if one of teh input is set, else Not linked
     */
    public static function prepareViewPageGetAnswerData ($questionData) {
        if (empty($questionData["IMAGE"])){
            $questionData["IMAGE"] = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="; //transparent gif
        } else {
            $questionData["IMAGE"] = returnQuizImagepath($_SESSION["QUIZ_CURRENT_QUIZ_ID"], $questionData["IMAGE"]);
        }
        $dbLogic = new DB();
        $data = array(
            "PARENT_ID" => $questionData["CONNECTION_ID"]
        );
        $whereColumn = array(
            "answer_ANSWER_ID"          => "ANSWER_ID"
        );

        //Set the current QUESTION_ID to a session varibale for use when storing the answer.    
        $_SESSION["QUIZ_CURRENT_QUESTION"] = $questionData["QUESTION_ID"];
        //find this question's answers
        return $dbLogic->selectWithColumns("answer.*", "answer, question_answer", $data, $whereColumn, False);
    }
    /**
     * Returns the next question's data + feedback + question's connection_ID. returns false if fails validation
     *
     * @param string $answerId The Answer ID just just submitted by the user. 
     * @param string $previousQuestionId The question ID aoosiated with the answer just done (validation)
     * @param string $quizId The quiz the user is on (validation)
     * @return array $questionDataAndFeedback An aossicatve array of the next question data, flase if fails validation
     * "QUESTION_ID, CONTENT, QUESTION, IMAGE, IMAGE_ALT, CONNECTION_ID & feedback"
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
        $where = array("CONNECTION_ID" => $parentIDArray['PARENT_ID'], "quiz_QUIZ_ID" => $quizId);
        $previousQuestionIdArray = $dbLogic->select("question_QUESTION_ID", "question_answer", $where);
        //if question_QUESTION_ID != <question input>; returen false, else, keep going
        if ($previousQuestionIdArray['question_QUESTION_ID'] != $previousQuestionId){
            return false; //bad client input, are they trying to get to another quiz? too bad it's private
        } 
        //get the answer's connection id & Loop Id to find the next question
        //SELECT CONNECTION_ID FROM `question_answer` where answer_ANSWER_ID = <answerid input>;
        $whereValuesArray = self::createWhereArrayforAnswer($dbLogic, $answerId);
        //using the connection id, get the next questions's data now
        $questionData = self::runQueryGetQuestionDataOnly ($dbLogic, $whereValuesArray, $quizId);
        //add the feedback and return it
        $questionData['FEEDBACK'] = $feedback;
        return $questionData;
    }
    /**
     * 
     * @param DB $dbLogic reuse current current to Database class
     * @param array $whereValuesArray the value to compare with eg ParentID = ConIdValue
     * return array the associative array result from dbLogic
     */
    public static function runQueryGetQuestionDataOnly ($dbLogic, $whereValuesArray, $quizId){
        //SELECT question.* FROM `question_answer`, question where $whereValuesArray(eg PARENT_ID = 6) and QUESTION_ID = question_QUESTION_ID;
        $whereColumn = array("question_answer.question_QUESTION_ID" => "question.QUESTION_ID");
        $questionData = $dbLogic->selectWithColumnsOrder(
            "QUESTION_ID, CONTENT, QUESTION, IMAGE, IMAGE_ALT, CONNECTION_ID",
            "question_answer, question", 
            $whereValuesArray, $whereColumn, "DEPTH"
        );
        //put the location with the image
        $questionData['IMAGE'] = quizHelper::returnWebImageFilePath($quizId, $questionData['IMAGE']);
        return $questionData;
    }
    /**
     * Creates a where values array for dbLogic later on "questionDataAndFeedback"
     * 
     * @param DB $dbLogic reuse curent connection to the Databse
     * @param string $answerId
     * return array|boolean return array Conn = LOOPIDValue or ParentID = ConIdValue
     */
    private static function createWhereArrayforAnswer ($dbLogic, $answerId){
        //SELECT CONNECTION_ID FROM `question_answer` where answer_ANSWER_ID = <answerid input>;
        $where = array("answer_ANSWER_ID" => $answerId);
        $connectionIdArray = $dbLogic->select("CONNECTION_ID, LOOP_CHILD_ID", "question_answer", $where);
        if (isset($connectionIdArray['LOOP_CHILD_ID'])){
            $whereValuesArray = array("CONNECTION_ID" => $connectionIdArray['LOOP_CHILD_ID']);
        } else {
            $whereValuesArray  = array("PARENT_ID" => $connectionIdArray['CONNECTION_ID']);
        }
        if ($connectionIdArray > 0){
            return $whereValuesArray;
        } else {
            return false;
        }
    }
}