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
    public static function removeImagefromQuestion($quizId, $questionId){
        $dbLogic = new DB();
        //check the question is on the same quiz
        $prevConAnswerConId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $questionId);
        if ($prevConAnswerConId == false){
            return false;
        }
        $result = quizLogic::returnQuestionOrAnswerData($questionId , "question"); //get the image filename
        //remove the image from db
        $whereValuesArray = array("QUESTION_ID" => $questionId);
        $setColumnsArray = array("IMAGE" => NULL);
        $dbLogic->updateSetWhere("question", $setColumnsArray, $whereValuesArray);
        //delete current image file
        unlink(quizHelper::returnRealImageFilePath($quizId, $result['IMAGE']));
    }
    /**
     * Updates a question in the database
     * 
     * @param string $quizId  The quiz associated 
     * @param string $questionId The question ID to update
     * @param string $questionTitle The question's heading
     * @param string $questionContent The paragraph for the question
     * @param string $questionAlt The alternate text for those impaired
     * @param string $targetFileName The filename of the image for the question (optional, keeps old image)
     * @return boolean false if operation fails, true if success
     */
    public static function updateQuestion($quizId, $questionId, $questionTitle, $questionContent, $questionAlt, $targetFileName = NULL){
        $dbLogic = new DB();
        //check the question is on the same quiz
        $prevConAnswerConId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $questionId);
        if ($prevConAnswerConId == false){
            return false;
        }
        $whereValuesArray = array("QUESTION_ID" => $questionId);
        if (is_null($targetFileName)){
            $setColumnsArray = array(
                "QUESTION" => $questionTitle,
                "CONTENT" => $questionContent,
                "IMAGE_ALT" => $questionAlt
            );
        } else {
            //delete the existing file
            $result = $dbLogic->select("IMAGE", "question", $whereValuesArray);
            unlink(quizHelper::returnRealImageFilePath($quizId, $result['IMAGE']));
            //prepare update arrays
            $setColumnsArray = array(
                "QUESTION" => $questionTitle,
                "CONTENT" => $questionContent,
                "IMAGE" => $targetFileName,
                "IMAGE_ALT" => $questionAlt
            );
        }
        $dbLogic->updateSetWhere("question", $setColumnsArray, $whereValuesArray);
        //all good, so returnn true
        return true;
    }
    /**
     * Updates a answer in the database
     * 
     * @param string $quizId The quiz associated 
     * @param string $answerId The answer assiocated
     * @param string $answerContent The actual answer
     * @param string $feedbackContent The feedback when choosing that answer
     * @param string $isCorrect is it correct 0,2, or 2 (0 is incorrect, 1 is correct, 2 is neutral)
     * @param string $link The question to link the answer to
     * @return void
     */
    public static function updateAnswer($quizId, $answerId, $answerContent, $feedbackContent, $isCorrect, $link = NULL){
        $dbLogic = new DB();
         //check the question is on the same quiz
        $connId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $answerId);
        
        if ($connId == false){
            return false;
        }
        if (!empty($link)){ //if set and NOT NULL, remove children (but not itself)
            $index = self::prepareRecursiveListQuestionAnswer($dbLogic, $quizId);
            self::removeChildren($dbLogic, $index, $connId, $connId);
        }
        //get the loop conn id and set the loop to it Or NULL
        $LoopConnId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $link);
        if ($LoopConnId ==false) {$LoopConnId = NULL;}
        $setValuesArray = array("LOOP_CHILD_ID" => $LoopConnId);
        $whereValuesArray = array("CONNECTION_ID" => $connId);
        $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray); 
            
        $whereValuesArray = array("ANSWER_ID" => $answerId);
        //prepare update arrays
        $setColumnsArray = array(
            "ANSWER" => $answerContent,
            "FEEDBACK" => $feedbackContent,
            "IS_CORRECT" => $isCorrect
        );
        $dbLogic->updateSetWhere("answer", $setColumnsArray, $whereValuesArray);
    }
    /**
     * Remove a Answer in the database
     * 
     * @param string $quizId The quiz associated 
     * @param string $answerId The answer assiocated
     * @param string $answerContent The actual answer
     * @param string $feedbackContent The feedback when choosing that answer
     * @param string $isCorrect is it correct 0,2, or 2 (0 is incorrect, 1 is correct, 2 is neutral)
     * @param boolean $removeItSelf true - delelete it self, false keep itself (optional, default it will delete itself)
     * @return void
     */
    public static function removeAnswerOrQuestion($quizId, $id, $type, $removeItSelf = true){
        $dbLogic = new DB();
        //find the connection id
        if ($type == "question"){
            $connId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $id);
            
        } else {
            $connId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $id);
        }
        if ($connId == false){
            return false;
        }
        $index = self::prepareRecursiveListQuestionAnswer($dbLogic, $quizId);;
        self::removeChildren($dbLogic, $index, $connId);
        //now delete it self
        if ($removeItSelf == true){
            //do delete
            $deleteValues = array("CONNECTION_ID" => $connId);
            $dbLogic->delete("question_answer", $deleteValues);
            if ($type == "question"){
                $deleteValues = array("QUESTION_ID" => $id);
                $dbLogic->delete("question", $deleteValues);
            } else {
                $deleteValues = array("ANSWER_ID" => $id);
                $dbLogic->delete("answer", $deleteValues);
            }
            
        }

    }
    /**
     * Recursive bottom-up tree traversal - Delete children on question_answer and their tables
     * 
     * @param DB $dbLogic reuse current connection to Databse
     * @param string $index the array to go through
     * @param string $parentId The node(it's Connection ID field) to be deleted and it's children
     * return void
     */
   protected static function removeChildren(DB $dbLogic, $index, $parentId) {
       $parentId = $parentId === NULL ? "NULL" : $parentId;
       if (isset($index[$parentId])) {
            foreach ($index[$parentId] as $singleParentId) {
            self::RemoveChildren($dbLogic, $index, $singleParentId['id']);
            //set any references to LOOP_CHILD_ID to NULL
            $index = self::removeReferencetoLoopChild($dbLogic, $index, $singleParentId['id']);
                $deleteValues = array("CONNECTION_ID" => $singleParentId['id']);
                $dbLogic->delete("question_answer", $deleteValues);
                if ($singleParentId['type'] === "question"){
                    $deleteValues = array("QUESTION_ID" => $singleParentId['questionOrAnswerId']);
                    $dbLogic->delete("question", $deleteValues);
                } else {
                    $deleteValues = array("ANSWER_ID" => $singleParentId['questionOrAnswerId']);
                    $dbLogic->delete("answer", $deleteValues);
                }
                ;
            }
       }
   }
    /**
     * Recursive bottom-up tree traversal - Delete children on question_answer and their tables
     * 
     * @param DB $dbLogic reuse current connection to Databse
     * @param string $index the array to go through
     * @param string $id The node to checked if there loop child matching
     * return array the modified array
     */
   private static function removeReferencetoLoopChild(DB $dbLogic, array $index, $id){
       foreach ($index as $value){
            /* @var $value2 type */
            foreach ($value as $value2){
                if ($value2['loopChildId'] == $id){
                    $setColumnsArray = array("LOOP_CHILD_ID" => NULL);
                    $whereValuesArray = array("CONNECTION_ID" => $value2['id']);
                    $dbLogic->updateSetWhere("question_answer", $setColumnsArray, $whereValuesArray);
                }
           }
        }
        return $index;
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
     * @param string $link the the question that the answer will jump to afterwards in a different branch
     * @return boolean false if operation fails, true if success
     */
    public static function insertAnswer($quizId, $prevQuestionId, $answerContent, $feedbackContent, $isCorrect, $link){
        $dbLogic = new DB();
        //check the answer is on the same quiz
        $prevConQuestionConId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $prevQuestionId);
        if ($prevConQuestionConId == false){
            return false;
        }
        //insert answer and get it's answer id
        $answerId = self::insertAnswerintoAnswerTable($dbLogic, $answerContent, $feedbackContent, $isCorrect);
        //insert it using the id retrieved eariler
        self::insertAnswerIntoQuestionAnswerTable($dbLogic, $answerId, $quizId, $link, $prevConQuestionConId);
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
        $prevConAnswerConId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $prevAnswerId);
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
        
        $link = NULL; //no link
                
        //insert question to question_answer using above question_ID
        $answerConnectionId = self::insertAnswerIntoQuestionAnswerTable ($dbLogic, $answerId, $quizId, $link, $questionConnectionId);
        
        //attach the root node to the new answer
        $setColumnsArray = array("PARENT_ID" => $answerConnectionId);
        $whereValuesArray = array("CONNECTION_ID" => $rootQuestionAnswerConId);//the root)
        $dbLogic->updateSetWhere("question_answer", $setColumnsArray, $whereValuesArray);
        
        $index = self::prepareRecursiveListQuestionAnswer($dbLogic, $quizId);
        //do updating of depth for the children
        self::increaseDepthOfChildNodes($dbLogic, $index, $rootQuestionAnswerConId);
    }
    /**
     * Creates a Array from the question_answer table to iterate through with other fuctions
     * 
     * @param DB $dbLogic reuse current connection to the databse
     * @param string $quizId the quiz to generate the list from
     * @return array The PHP array of similar structyre to the databse
     * ['id'] = the connection id
     * ['type'] = the type, question or answer, a string
     * ['questionOrAnswerId'] the id of the question or answer id
     */
    protected static function prepareRecursiveListQuestionAnswer (DB $dbLogic, $quizId) {
        $whereValuesArray = array("quiz_QUIZ_ID" => $quizId);//the root
        //do updating of depth for the children
        $children = $dbLogic->selectOrder("CONNECTION_ID, PARENT_ID, question_QUESTION_ID, answer_ANSWER_ID, LOOP_CHILD_ID", "question_answer",  $whereValuesArray, "DEPTH", false);
        $index = array();
        foreach ($children as $child){
            //if question or answer, put the type into aarray
            if (!is_null($child["question_QUESTION_ID"])){
                $type = "question";
                $questionOrAnswerId = $child["question_QUESTION_ID"];
            } else {
                $type = "answer";
                $questionOrAnswerId = $child["answer_ANSWER_ID"];
            }
            $id = $child["CONNECTION_ID"];
            $parent_id = $child["PARENT_ID"] === NULL ? "NULL" : $child["PARENT_ID"];
            $index[$parent_id][] = array (
                'loopChildId' => $child['LOOP_CHILD_ID'],
                'id' => $id,
                'type' => $type,
                'questionOrAnswerId' => $questionOrAnswerId
            );
        }
        return $index;
    }
    /*
 * Recursive top-down tree traversal example:
 * Indent and print child nodes
 */
    protected static function increaseDepthOfChildNodes (DB $dbLogic, $index, $parent_id){
       //if NULL, set the string to to "NULL", otherwise be yourself
        $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
        //if exist
        if (isset($index[$parent_id])) {
            foreach ($index[$parent_id] as $row) { //rPll through the ARRAY OF 1 result (needs to be array as some are missed somehow)
                $setColumnsArray = array("DEPTH" => "DEPTH+1");
                $whereValuesArray = array("CONNECTION_ID" => $row['id']);
                //increase the current depth of the row
                $dbLogic->updateSetButSetNotEscaped("question_answer", $setColumnsArray, $whereValuesArray);
                //recursive loop
                self::increaseDepthOfChildNodes($dbLogic, $index, $row['id']);
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
    protected static function insertAnswerintoAnswerTable (DB $dbLogic, $answerContent, $feedbackContent, $isCorrect){
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
    protected static function insertQuestionIntoQuestionTable (DB $dbLogic, $questionTitle, $questionContent, $questionImageUploadfile, $questionAlt){
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
    protected static function insertQuestionIntoQuestionAnswerTable (DB $dbLogic, $questionId, $quizId, $parentId = NULL){
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
     * @param string $quizId The quiz to assoicate the question with
     * @param string $link the question to link the naswer to in a different branch
     * @param string $questionConnectionId The connectionId from the previous question [insert] (attach it)
     * @param string $parentId The question to attach to (optional)
     * @return string The question_answer's primary key, ConnectionID (for the answer just inserted)
     */
    protected static function insertAnswerIntoQuestionAnswerTable (DB $dbLogic, $answerId, $quizId, $link, $questionConnectionId, $parentId = NULL){
        $dbLink = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $link);
        if ($dbLink == false) {$dbLink = NULL;}
        $insertArray = array(
                "answer_ANSWER_ID" => $answerId,
                 "TYPE" => "answer",
                "PARENT_ID" => $questionConnectionId,
                "quiz_QUIZ_ID" => $quizId,
                "LOOP_CHILD_ID" => $dbLink
        );
        if (is_null($parentId)) {    //inserting at the top
            $insertArray["DEPTH"] = "1";  //add to arry - lower than the first question
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
        $quizIDGet = (string)quizLogic::returnRealQuizID(filter_input(INPUT_GET, "quiz"));
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
        assert(is_string($quizId) || is_int($quizId));
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
     * @return string The new quiz's id or not needed, the existing quiz id sent to it
     */
    static public function maybeCloneQuiz($oldQuizId) {
        /*die ("cloneQuiz not working yet"); */
        assert(!is_null($oldQuizId));
        $dbLogic = new DB();
        $whereValuesArray = array("QUIZ_ID" => $oldQuizId);
        $consistentArray = $dbLogic->select("CONSISTENT_STATE", "quiz", $whereValuesArray);
        if ($consistentArray['CONSISTENT_STATE'] == 0){ //if already cloned (1 is consistent, zero is NOT consistent
            return $oldQuizId; // bail, no cloning needed
        }
        //else if not cloned yet        
        //get the old quiz's data
        $where = array("QUIZ_ID" => $oldQuizId);
        $quizArray = $dbLogic->select(
                /* All colums except QUIZ_ID & increase Version */
                "SHARED_QUIZ_ID, VERSION, QUIZ_NAME, DESCRIPTION, IS_PUBLIC, NO_OF_ATTEMPTS, TIME_LIMIT, IS_SAVABLE, DATE_OPEN, DATE_CLOSED, INTERNAL_DESCRIPTION, IMAGE, IMAGE_ALT, IS_ENABLED", 
                "quiz", $where);
        //build an array for re-insertion
        $newQuizArray = array();
        foreach ($quizArray as $column => $value){
            if ($column === "VERSION"){
                $value++; //increase the version
            } else if ($column === "CONSISTENT_STATE"){
                $value = 1; //zero is NOT consistent
            }
            $newQuizArray[$column] = $value; //apply change to the array
        }
        //insert quiz
        $newQuizID = $dbLogic->insert($newQuizArray, "quiz");
        //create a list of the columns to be built
        $questonAnswerColums = array("question_QUESTION_ID", "answer_ANSWER_ID", "PARENT_ID", "LOOP_CHILD_ID", "TYPE", "quiz_QUIZ_ID", "DEPTH"); //no primary key connection_id
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
                "question_QUESTION_ID, answer_ANSWER_ID, CONNECTION_ID, PARENT_ID, LOOP_CHILD_ID, TYPE, quiz_QUIZ_ID, DEPTH, " .
                "QUESTION_ID, CONTENT, QUESTION, IMAGE, IMAGE_ALT, " . 
                "ANSWER_ID, ANSWER, FEEDBACK, IS_CORRECT", 
                "question_answer", $where, "question", $jointable, "answer", $jointable2, false);
        $newQuestionAnswerArray = array();
        $newQuestionArray = array();
        $newAnswerArray = array();
        
        $i = 0;
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
                    $newQuestionArray[$i][$column] = $value2;
                // answers 
                } else if ($addToOtherTable == "answer" && in_array($column, $answerColums)){ 
                    $newAnswerArray[$i][$column] = $value2;
                }
            }
            $i++;
        }
        //insert questions 
        $questionIdsArray = array();
        foreach ($newQuestionArray as $value){
            $questionIdsArray[] = $dbLogic->insert($value, "question"); //insert and build array of auto-increment ids
        }
        //insert answers
        $answerIdsArray = array();
        foreach ($newAnswerArray as $value){
            $answerIdsArray[] = $dbLogic->insert($value, "answer"); //insert and build array of auto-increment ids
        }
        //fix question & answer Ids (edit $newQuestionAnswerArray)
        $qi = 0;
        $ai= 0;
        foreach ($newQuestionAnswerArray as $key => $arrayRow) {
            if (isset($arrayRow[$questionLinkedPrimaryKey])){
                //var_dump($newQuestionAnswerArray[$key][$questionLinkedPrimaryKey]);
                //var_dump($newQuestionAnswerArray[0]);
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
        return $newQuizID;
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
    private static function updateQuestionAnswerTableColumn(DB $dbLogic, array $arrayRow, $i, 
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
}