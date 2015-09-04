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
     * Inserts a question and answer infront of the first question
     * 
     * @param string $quizId The Current quiz (real quiz)
     * @param string $questionTitle The Question Heading
     * @param string $questionContent The paragraph about the question
     * @param string $questionImageUploadfile - the file to upload
     * @param string $questionAlt The ALt text for the image
     * @param string $answerContent The answer for teh first question
     * @param string $feedbackContent The feedback for the answer
     * @param string $isCorrect A number that shows if teh answer corretc, not or neutral
     * @return void
     */
    public static function insertInitalQuestionAnswer($quizId, $questionTitle, $questionContent, $questionImageUploadfile, $questionAlt, 
            $answerContent, $feedbackContent, $isCorrect) {
        
        //TODO recalculate the depth
        
        $dbLogic = new DB();
        
        //inset the question and rcord the id
         $insertArray = array(
            "QUESTION" => $questionTitle,
             "CONTENT" => $questionContent,
             "IMAGE" => $questionImageUploadfile,
             "IMAGE_ALT" => $questionAlt
        );
        $questionId = $dbLogic->insert($insertArray, "question");
        
        //insert the answe and record the answer
        $insertArray = array(
            "ANSWER" => $answerContent,
            "FEEDBACK" => $feedbackContent,
            "IS_CORRECT" => $isCorrect
        );
        $answerId = $dbLogic->insert($insertArray, "answer");
        
        //get the root node of the quiz in question_answer before mofification
        $whereValueArray = array(
            "quiz_QUIZ_ID" => $quizId
        );
        $rootQuestionAnswer = $dbLogic->selectAndWhereIsNull("CONNECTION_ID", "question_answer", $whereValueArray, array("PARENT_ID"));
        $rootQuestionAnswerConId = $rootQuestionAnswer["CONNECTION_ID"];
        
        //insert question to question_answer using above question_ID
         $insertArray = array(
            "question_QUESTION_ID" => $questionId,
             "TYPE" => "question",
             "quiz_QUIZ_ID" => $quizId
        );
        $questionConnectionId = $dbLogic->insert($insertArray, "question_answer");
        
        //insert question to question_answer using above question_ID
        $insertArray = array(
            "answer_ANSWER_ID" => $answerId,
             "TYPE" => "answer",
            "PARENT_ID" => $questionConnectionId,
            "quiz_QUIZ_ID" => $quizId,
        );
        $answerConnectionId = $dbLogic->insert($insertArray, "question_answer");
        
        //attach the root node to the new answer
        $setColumnsArray = array("PARENT_ID" => $answerConnectionId);
        $whereValuesArray = array("CONNECTION_ID" => $rootQuestionAnswerConId);//the root)
        $dbLogic->updateSetWhere("question_answer", $setColumnsArray, $whereValuesArray);
    }
    /**
     * Gets the shared quiz ID from the URL and returns the real ID, otherwise, back to edit-quiz.
     *
     * @return string The Real Quiz ID
     */
    public static function getQuizIdFromUrlElseReturnToEditQuiz() {
        $quizIDGet = quizLogic::returnRealQuizID(filter_input(INPUT_GET, "quiz"));
        if(is_null($quizIDGet)){
            //back to edit quiz
            header('Location: ' . CONFIG_ROOT_URL . '/edit-quiz.php?no-quiz-selected=yes');
            exit;  
        }
        return $quizIDGet;
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
        assert(is_string($quizId));
        $dbLogic = new DB();
        
        //return the real quiz ID
        //SELECT SHARED_QUIZ_ID from quiz where quiz_id = 1;
        $where = array("QUIZ_ID" => $quizId);
        $result = $dbLogic->select("SHARED_QUIZ_ID", "quiz", $where);
        return $result['SHARED_QUIZ_ID'];
    }
    
    /**
     * Returns the Real Quiz ID (latest version) for quiz.
     *
     * @param string $sharedQuizId The shared quiz id
     * @return string Returns real quiz ID
     */
    static public function returnRealQuizID($sharedQuizId) {
        assert(is_string($sharedQuizId));
        $dbLogic = new DB();
        
        //return the shared quiz ID
        //SELECT UIZ_ID from quiz where shared_quiz_id = 1;
        $where = array("SHARED_QUIZ_ID" => $sharedQuizId);
        $result = $dbLogic->select("max(QUIZ_ID) as QUIZ_ID", "quiz", $where);
        return $result['QUIZ_ID'];
    }
    
    /**
     * [not implemented yet] Clones all data associated with a quiz. new quiz shares same shared quiz id
     *
     * @param string $oldQuizId The the quiz to be cloned
     * @return string The new quiz's id
     */
    static public function cloneQuiz($oldQuizId) {
        die ("cloneQuiz not working yet");
        assert(is_string($oldQuizId));
        $dbLogic = new DB();
        
        //get the old quiz's data
        $where = array("QUIZ_ID" => $oldQuizId);
        $result = $dbLogic->select("QUIZ_ID", "quiz", $where);
        //build an array for re-insertion
        $newQuizData = array();
        foreach ($result as $column => $value){
            //TOD date
            if ($column != "QUIZ_ID"){ //don't include the primary key
                $newQuizData[$column] = $value;
            }
        }
        //re-insert it now, except the primary key
        $newQuizID = $dbLogic->insert($newQuizData, "quiz");
        //for each table DIRECTLY relating to quizes, duplicatite it now
        //$tableArray = array('question_answer', 'quiz_keyword');
        $where = array("quiz_QUIZ_ID" => $oldQuizId);
        $results = $dbLogic->select("*", 'question_answer', $where, false);
        //TODO change ConnectionID etc
        
        
        $results['quiz_QUIZ_ID'] = $newQuizID;
        
        $results = $dbLogic->select("*", 'quiz_keyword', $where, false);
        $results['quiz_QUIZ_ID'] = $newQuizID;
        $dbLogic->insert($results, "quiz_keyword");
        
        /*
        //find the indirect ones
        question
        answer
        answer_keyword
        question_keyword
        */
        
        
        
        
        
        return $newQuizID;
    }
    /**
     * Returns the next question's data + feedback + question's connection_ID. returns false if fails validation
     *
     * @param string $answerId The Answer ID just just submitted by the user. 
     * @param string $previousQuestionId The question ID aoosiated with the answer just done (validation)
     * @param string $quizId The quiz the user is on (validation)
     * @return array $questionDataAndFeedback An aossicatve array of the next question data, flase if fails validation
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