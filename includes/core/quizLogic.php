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
            $result = $dbLogic->select("ANSWER, FEEDBACK, IS_CORRECT", "answer", $whereValuesArray);
        }
        if (count($result) > 0){
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * Returns the parent ID of a question or Answer
     * 
     * @param DB $dbLogic reuse the current connection the databse
     * @param string $questionOrAnswerId the If of teh question or answer
     * @param string $type indicties if teh id is a question or an answer ("question" for question etc)
     * @return string|boolean return the connection ID, false if operation fails
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
     * Check if the question is on the same quiz, returns false if not
     * 
     * @param string $quizId The quiz associated 
     * @param string $prevQuestionId The question to connect it with
     * @param string $answerContent The actual answer
     * @param string $feedbackContent The feedback when choosing that answer
     * @param string $isCorrect is it correct 0,2, or 2 (0 is incorrect, 1 is correct, 2 is neutral)
     * @return boolean false if operation fails, true if success
     */
    public static function insertAnswer($quizId, $prevQuestionId, $answerContent, $feedbackContent, $isCorrect){
        $dbLogic = new DB();
        //check the answer is on the same quiz
        $prevConQuestionConId = self::checkQuestionBelongsToQuiz($dbLogic, $quizId, $prevQuestionId);
        if ($prevConQuestionConId == false){
            return false;
        }
        //insert answer and get it's answer id
        $answerId = self::insertAnswerintoAnswerTable($dbLogic, $answerContent, $feedbackContent, $isCorrect);
        //insert it using the id retrieved eariler
        self::insertAnswerIntoQuestionAnswerTable($dbLogic, $answerId, $quizId, $prevConQuestionConId);
        //all good, so returnn true
        return true;
    }
    /**
     * Inserts a question into the database
     * 
     * @param string $quizId  The quiz associated 
     * @param string $prevAnswerId The answer to connect this with
     * @param string $questionTitle The question's heading
     * @param string $questionContent The paragraph for the question
     * @param string $targetFileName The filename of the image for the question
     * @param string $questionAlt The alternate text for those impaired
     * @return boolean false if operation fails, true if success
     */
    public static function insertQuestion($quizId, $prevAnswerId, $questionTitle, $questionContent, $targetFileName, $questionAlt){
        $dbLogic = new DB();
        //check the question is on the same quiz
        $prevConAnswerConId = self::checkAnswerBelongsToQuiz($dbLogic, $quizId, $prevAnswerId);
        if ($prevConAnswerConId == false){
            return false;
        }
        //insert question and get it's Connection id
        $questionConId = self::insertQuestionIntoQuestionTable($dbLogic, $questionTitle, $questionContent, $targetFileName, $questionAlt);
        //insert it using the id retrieved eariler
        self::insertQuestionIntoQuestionAnswerTable($dbLogic, $questionConId, $quizId, $prevConAnswerConId);
        //all good, so returnn true
        return true;
    }
        /**
     * Check if the question is on the same quiz, returns the CONNECTION_ID, false otherwise
     * 
     * @param DB $dbLogic resue the current connection to teh databse
     * @param string $quizId The quiz ID
     * @param string $answerId The question ID to check
     * @return boolean|string CONNECTION_ID if is on same quiz, false otherwise
     */
    private static function checkAnswerBelongsToQuiz(DB $dbLogic, $quizId, $answerId){
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
    private static function checkQuestionBelongsToQuiz(DB $dbLogic, $quizId, $questionId){
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
    public static function checkUserCanEditQuiz(){
        
    }
    public static function checkUserCanTakeQuiz(){
        
    }
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
        $questionId = self::insertQuestionIntoQuestionTable($dbLogic, $questionTitle, $questionContent, $questionImageUploadfile, $questionAlt);
        
        //insert the answe and record the answer
        $answerId = self::insertAnswerintoAnswerTable($dbLogic, $answerContent, $feedbackContent, $isCorrect);
        
        //get the root node of the quiz in question_answer before mofification
        $whereValuesArray = array(
            "quiz_QUIZ_ID" => $quizId
        );
        $rootQuestionAnswer = $dbLogic->selectAndWhereIsNull("CONNECTION_ID", "question_answer", $whereValuesArray, array("PARENT_ID"));
        $rootQuestionAnswerConId = $rootQuestionAnswer["CONNECTION_ID"];
        
        //insert question to question_answer using above question_ID
        $questionConnectionId = self::insertQuestionIntoQuestionAnswerTable($dbLogic, $questionId, $quizId);
        
        //insert question to question_answer using above question_ID
        $answerConnectionId = self::insertAnswerIntoQuestionAnswerTable ($dbLogic, $answerId, $questionConnectionId, $quizId);
        
        //attach the root node to the new answer
        $setColumnsArray = array("PARENT_ID" => $answerConnectionId);
        $whereValuesArray = array("CONNECTION_ID" => $rootQuestionAnswerConId);//the root)
        $dbLogic->updateSetWhere("question_answer", $setColumnsArray, $whereValuesArray);
        
        $whereValuesArray = array("quiz_QUIZ_ID" => $quizId);//the root)
        //do updating of depth for the children
        $children = $dbLogic->selectOrder("CONNECTION_ID, PARENT_ID", "question_answer",  $whereValuesArray, "DEPTH", false);
        //$data = array();
        $index = array();
        foreach ($children as $child){
            $id = $child["CONNECTION_ID"];
            $parent_id = $child["PARENT_ID"] === NULL ? "NULL" : $child["PARENT_ID"];
            //$data[$id] = $child;
            $index[$parent_id][] = $id;
        }
        self::increaseDepthOfChildNodes($dbLogic, $index, $rootQuestionAnswerConId);
    }
    /*
 * Recursive top-down tree traversal example:
 * Indent and print child nodes
 */
    private static function increaseDepthOfChildNodes (DB $dbLogic, $index, $parent_id){
       //if NULL, set the string to to "NULL", otherwise be yourself
        $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
        //if exist
        if (isset($index[$parent_id])) {
            var_dump($index[$parent_id]);
            echo "<br />";
            foreach ($index[$parent_id] as $id) { //rPll through the ARRAY OF 1 result (needs to be array as some are missed somehow)
                $setColumnsArray = array("DEPTH" => "DEPTH+1");
                $whereValuesArray = array("CONNECTION_ID" => $id);
                //increase the current depth of the row
                $dbLogic->updateSetButSetNotEscaped("question_answer", $setColumnsArray, $whereValuesArray);
                //recursive loop
                self::increaseDepthOfChildNodes($dbLogic, $index, $id);
            }
        }
    }
    /**
     * Inserts a answer into the "question" table
     * 
     * @param DB $dbLogic The Current connection the databse (reuse it)
     * @param string $answerContent The answer's text
     * @param string $feedbackContent The feedback from the answer
     * @param string $isCorrect The number (string) indictcating if correct, neutral etc
     * @return string The answer's primary key, ConnectionID
     */
    private static function insertAnswerintoAnswerTable (DB $dbLogic, $answerContent, $feedbackContent, $isCorrect){
        $insertArray = array(
            "ANSWER" => $answerContent,
            "FEEDBACK" => $feedbackContent,
            "IS_CORRECT" => $isCorrect
        );
        return $dbLogic->insert($insertArray, "answer");
    }
    /**
     * Inserts a question into the "question" table
     * 
     * @param DB $dbLogic The Current connection the databse (reuse it)
     * @param string $questionTitle The title of the question
     * @param string $questionContent The paragraph for the question
     * @param string $questionImageUploadfile The filename of the image
     * @param string $questionAlt The ALt text for the question's image
     * @return string The answer's primary key, ConnectionID
     */
    private static function insertQuestionIntoQuestionTable (DB $dbLogic, $questionTitle, $questionContent, $questionImageUploadfile, $questionAlt){
        $insertArray = array(
            "QUESTION" => $questionTitle,
             "CONTENT" => $questionContent,
             "IMAGE" => $questionImageUploadfile,
             "IMAGE_ALT" => $questionAlt
        );
        return $dbLogic->insert($insertArray, "question");
    }
    /**
     * Inserts a question into the "question_answer" table
     * 
     * @param DB $dbLogic The Current connection the databse (reuse it)
     * @param string $questionId The Question ID form the question table insert
     * @param string $quizId The quiz to assoicate the question with
     * @param string $parentId The parent CONNECTION_ID (optional, added to the otherwise)
     * @param string $answerConId The 
     * @return string The question_answer's primary key, ConnectionID (for the question just inserted)
     */
    private static function insertQuestionIntoQuestionAnswerTable (DB $dbLogic, $questionId, $quizId, $parentId = NULL){
        if (is_null($parentId)) {    //inserting at the top
            $insertArray = array(
                "question_QUESTION_ID" => $questionId,
                 "TYPE" => "question",
                 "quiz_QUIZ_ID" => $quizId,
                "DEPTH" => '0'
            );
            return $dbLogic->insert($insertArray, "question_answer");
        } else { //inserting somewhere else, use insert Selct to get the depth right
            $insertArray = array(
                "question_QUESTION_ID" => $questionId,
                    "TYPE" => "question",
                    "quiz_QUIZ_ID" => $quizId,
                    "PARENT_ID" => $parentId
                );
                $whereArray = array(
                    "CONNECTION_ID" => $parentId
                );
            //INSERT INTO question_answer (DEPTH, question_QUESTION_ID, TYPE, quiz_QUIZ_ID, PARENT_ID) SELECT DEPTH+1, '114', 'question', '1', '208' FROM question_answer WHERE CONNECTION_ID = '208'
            return $dbLogic->insertWithSelectWhere("question_answer", "DEPTH, question_QUESTION_ID, TYPE, quiz_QUIZ_ID, PARENT_ID",
                    "DEPTH+1", "question_answer",$whereArray, $insertArray); //depth is increased from last

        }
    }
    /**
     * Inserts a answer into the "question_answer" table
     * 
     * @param DB $dbLogic The Current connection the databse (reuse it)
     * @param string $answerId The answer ID form the answer table insert
     * @param string $questionConnectionId The connectionId from the previous question [insert] (attach it)
     * @param string $quizId The quiz to assoicate the question with
     * @param $parentId The question to attach to (optional)
     * @return string The question_answer's primary key, ConnectionID (for the answer just inserted)
     */
    private static function insertAnswerIntoQuestionAnswerTable (DB $dbLogic, $answerId, $quizId, $questionConnectionId, $parentId = NULL){
        if (is_null($parentId)) {    //inserting at the top
            $insertArray = array(
                "answer_ANSWER_ID" => $answerId,
                 "TYPE" => "answer",
                "PARENT_ID" => $questionConnectionId,
                "quiz_QUIZ_ID" => $quizId,
                "DEPTH" => "1"  //lower than the first question
            );
            return $dbLogic->insert($insertArray, "question_answer");
        } else { //inserting somewhere else, use insert Selct to get the depth right
            $insertArray = array(
                "answer_ANSWER_ID" => $answerId,
                 "TYPE" => "answer",
                "PARENT_ID" => $questionConnectionId,
                "quiz_QUIZ_ID" => $quizId
            );
            $whereArray = array(
                "CONNECTION_ID" => $parentId
            );
            return $dbLogic->insertWithSelectWhere("question_answer", "DEPTH, answer_ANSWER_ID, TYPE, PARENT_ID, quiz_QUIZ_ID",
                    "DEPTH+1", "question_answer", $whereArray, $insertArray); //depth is increased from last
        }
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