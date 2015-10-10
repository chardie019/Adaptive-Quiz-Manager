<?php
class editQuestionLogic extends quizLogic {
    public static function removeImagefromQuestion($quizId, $questionId){
        $dbLogic = new dbLogic();
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
        $dbLogic = new dbLogic();
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
    public static function updateAnswer($quizId, $answerId, $answerContent, $feedbackContent, $isCorrect){
        $dbLogic = new dbLogic();
         //check the question is on the same quiz
        $connId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $answerId);
        
        if ($connId == false){
            return false;
        }
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
     * @param string $deleteReturnButton "single" or "branch" - delete it self or entire branch
     * @return void
     */
    public static function removeAnswerOrQuestion($quizId, $id, $type, $deleteReturnButton){
        $dbLogic = new dbLogic();
        //find the connection id
        if ($type == "question"){
            $connId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $id);
            $shortKeyName = "SHORT_QUESTION_ID";
        } else { //type = answer
            $connId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $id);
            $shortKeyName = "SHORT_ANSWER_ID";
        }
        if ($connId == false){
            return false;
        }
        $index = self::prepareRecursiveListQuestionAnswer($dbLogic, $quizId);
        self::removeReferencetoLoopChild($dbLogic, $index, $connId);
        if ($deleteReturnButton == "branch") {
            self::removeChildren($dbLogic, $index, $connId);
        } else if ($deleteReturnButton == "whole-question") {
            //find out how many are connected to the question first
            $whereValuesArray = array(
                "PARENT_ID" => $connId,
                "TYPE" => "answer"  //remove answers only
            );
            $connectedAnswersArray = $dbLogic->select("answer_ANSWER_ID", "question_answer", $whereValuesArray, false);
            foreach ($connectedAnswersArray as $connectedAnswer) {
                //recurisve call a few time to delete the answers
                self::removeAnswerOrQuestion($quizId, $connectedAnswer["answer_ANSWER_ID"], "answer", "single");
            }
            //relink the sub node if any (before delete operation) (like questions)
            self::linkSubNodesToParent($dbLogic, $connId);
        } else { //single
            //relink the sub node if any (before delete operation)
            self::linkSubNodesToParent($dbLogic, $connId);
        }
        //get current short answer Id and then update the database
        $whereValuesArray = array("CONNECTION_ID" => $connId);
        $shortAnswerIdArray = $dbLogic->select($shortKeyName, "question_answer", $whereValuesArray);
        $shortAnswerId = $shortAnswerIdArray[$shortKeyName];
        //update the below answers with their short answer ids beofre removeal
        $setValuesArray = array("$shortKeyName" => "$shortKeyName - 1");
        $whereValuesArray = array("quiz_QUIZ_ID" => $quizId);
        $greaterWhereValuesArray = array($shortKeyName => $shortAnswerId);
        $dbLogic->updateSetWhereAndGreaterThanButSetNotEscaped("question_answer", $setValuesArray, $whereValuesArray, $greaterWhereValuesArray);
        //now delete it self
        $deleteValues = array("CONNECTION_ID" => $connId);
        $dbLogic->delete("question_answer", $deleteValues);
        if ($type == "question"){
            $deleteValues = array("QUESTION_ID" => $id);
            $dbLogic->delete("question", $deleteValues);
        } else {
            $deleteValues = array("ANSWER_ID" => $id);
            $dbLogic->delete("answer", $deleteValues);
        }
        return;
    }
    /**
     * Links the subnodes of question or answer to the parent
     * 
     * This will (with the a question link) create a integrity voilation which is checked later on "enable"
     * 
     * @param dbLogic $dbLogic reuse db connection
     * @param type $connId the id of the answer or question to have its offspring linked to another node
     */
    protected static function linkSubNodesToParent (dbLogic $dbLogic, $connId) {
        //find the parent ID of the current node
            $parentWhereValuesArray = array("CONNECTION_ID" => $connId);
            $parentIDResult = $dbLogic->select("PARENT_ID", "question_answer", $parentWhereValuesArray);
            $parentId = $parentIDResult["PARENT_ID"];
            //relink sub nodes now
            $result = self::returnChildrenArray ($dbLogic, $connId);
            if (count($result) > 0) { //if there is sub node(s)
                foreach ($result as $row) {
                    $whereValuesArray = array("CONNECTION_ID" => $row["CONNECTION_ID"]);
                    $setValuesArray = array("PARENT_ID" => $parentId);
                    $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray);
                }
            }
    }
    /**
     * Querys the database for all nodes with parent ids on teh connection passed (all children)
     * 
     * @param dbLogic $dbLogic
     * @param type $connId
     */
    protected static function returnChildrenArray (dbLogic $dbLogic, $connId) {
        $whereValuesArray = array("PARENT_ID" => $connId);
        return $dbLogic->select("CONNECTION_ID", "question_answer", $whereValuesArray, false);
    }
    /**
     * Recursive bottom-up tree traversal - Delete children on question_answer and their tables
     * 
     * @param dbLogic $dbLogic reuse current connection to Databse
     * @param string $index the array to go through
     * @param string $parentId The node(it's Connection ID field) to be deleted and it's children
     * return void
     */
   protected static function removeChildren(dbLogic $dbLogic, $index, $parentId) {
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
            }
       }
   }
    /**
     * Rmove all refences to the node in the loop column
     * 
     * @param dbLogic $dbLogic reuse current connection to Databse
     * @param string $index the array to go through
     * @param string $id The node to checked if there loop child matching
     * return array the modified array
     */
    private static function removeReferencetoLoopChild(dbLogic $dbLogic, array $index, $id){
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
     * Inserts teh first question into the quiz
     * 
     * @param string $quizId the real quiz ID
     * @param string $questionTitle
     * @param string $questionContent
     * @param string $questionImageUploadfile
     * @param string $questionAlt
     */
    public static function insertInitalQuestionAnswer($quizId, $questionTitle, $questionContent, $questionImageUploadfile, $questionAlt) {
        $dbLogic = new dbLogic();
        
        //inset the question and rcord the id
        $questionId = self::insertQuestionIntoQuestionTable($dbLogic, $questionTitle, $questionContent, $questionImageUploadfile, $questionAlt);
        
        //get the root node of the quiz in question_answer before mofification
        $whereValuesArray = array(
            "quiz_QUIZ_ID" => $quizId
        );
        $rootQuestionAnswer = $dbLogic->selectAndWhereIsNull("CONNECTION_ID", "question_answer", $whereValuesArray, array("PARENT_ID"));
        //if there is a root, update the children
        if (!empty($rootQuestionAnswer)){
            //do updating of depth for the children
            $index = self::prepareRecursiveListQuestionAnswer($dbLogic, $quizId);
            self::increaseDepthOfChildNodes($dbLogic, $index, $rootQuestionAnswer["CONNECTION_ID"]);
        }
        
        //insert question to question_answer using above question_ID
        $questionConnectionId = self::insertQuestionIntoQuestionAnswerTable($dbLogic, $questionId, $quizId);
        
        //if there is a root, attach the (was) root node to the new question
        if (!empty($rootQuestionAnswer)){
            $setColumnsArray = array("PARENT_ID" => $questionConnectionId);
            $whereValuesArray = array("CONNECTION_ID" => $rootQuestionAnswerConId);//the root)
            $dbLogic->updateSetWhere("question_answer", $setColumnsArray, $whereValuesArray);
        }
    }
    /**
     * 
     * @param type $quizId
     * @return array an assoicate array descirbing why the the question or answer is problem (zero rows if no issue)
     *          ['problemCode'] = short problem code "first-not-a-question", "add-more-questions", "add-more-answers", 
     *                                               "end-is-a-answer", "adjacent-question", "adjacent-answer"
     *          ['shortId'] the short question or answer id to be displayed
     *          ['questionOrAnswerId'] the real question or answer id
     */
    public static function returnProblemQuestionAnswersIntegrityCheck ($quizId) {
        //1. now quiz has to have at least 2 questions & 1 answer and has to have a question at the start 
        //3. has to have no answers adjancent or questions adjancent
        //2. has to have a question at the end of each of the branches
        $dbLogic = new dbLogic();
        $problemQuestionAnswersArray = array();
        
        //1. now quiz has to have at least 2 questions & 1 answer and has to have a question at the start 
        $whereValuesArray = array(
            "quiz_QUIZ_ID" => $quizId
        );
        $questionsAndAnswersArray = $dbLogic->select("CONNECTION_ID, question_QUESTION_ID, answer_ANSWER_ID, TYPE, SHORT_QUESTION_ID, SHORT_ANSWER_ID, PARENT_ID", 
                "question_answer", $whereValuesArray, false);
        $numberOfQuestions = 0;
        $numberOfAnswers = 0;
        $firstIsAQuestion = false; //not proven yet
        foreach ($questionsAndAnswersArray as $row) {
            //if first one is question (it must be) 
            if ($row['PARENT_ID'] == NULL && $row['TYPE'] == "question") {
                $firstIsAQuestion = true;
            }
            //count questions
            if ($row['TYPE'] == "question") {
                $numberOfQuestions++;
            } else if ($row['TYPE'] == "answer") {
                $numberOfAnswers++;
            }
            //2. has to have no answers adjancent or questions adjancent
            $problemQuestionAnswersArrayTobeMerged = self::adjacencyCheck($questionsAndAnswersArray, $row);
            if (is_array($problemQuestionAnswersArrayTobeMerged)) { //if didn't return false
                $problemQuestionAnswersArray = $problemQuestionAnswersArray + $problemQuestionAnswersArrayTobeMerged; //merge arrays
            }
        }
        if ($firstIsAQuestion == false) {
            $problemQuestionAnswersArray[] = array (
                'problemCode' => "first-not-a-question",
                "shortId" => NULL,
                "questionOrAnswerId" => NULL
            );
        }
        if ($numberOfQuestions < 2) {
            //must add more questions
            $problemQuestionAnswersArray[] = array (
                'problemCode' => "add-more-questions",
                "shortId" => NULL,
                "questionOrAnswerId" => NULL
            );
        }
        if ($numberOfAnswers < 1 ){
            //must add more answers
            $problemQuestionAnswersArray[] = array (
                'problemCode' => "add-more-answers",
                "shortId" => NULL,
                "questionOrAnswerId" => NULL
            );
        }
        //3. has to have a question at the end of each of the branches
        $whereValuesArray = array(
            "quiz_QUIZ_ID" => $quizId,
            "TYPE" => "answer"
        );
        $invalidQuestionAnswersArray = $dbLogic->selectWithSelectWhereColumnsIsNotinAnotherColumn(
                "answer_ANSWER_ID, TYPE, SHORT_ANSWER_ID", "question_answer", 
                "CONNECTION_ID", "PARENT_ID", $whereValuesArray, "LOOP_CHILD_ID", false);
        if (!empty($invalidQuestionAnswersArray)) {
            foreach ($invalidQuestionAnswersArray as $row) {
                $problemQuestionAnswersArray[] = array (
                    'problemCode' => "end-is-a-answer",
                    "shortId" => $row['SHORT_ANSWER_ID'],
                    "questionOrAnswerId" => $row['answer_ANSWER_ID']
                );
            }
        }
        return $problemQuestionAnswersArray;
    }
    /**
     * checks below the node if they are same and report an error
     * 
     * @param array $questionsAndAnswersArray a array matrix if which $row is a subset of
     * @param array $row see $questionsAndAnswersArray
     * @return array|boolean array if problem found, else is false
     */
    protected static function adjacencyCheck($questionsAndAnswersArray, $row) {
        //4. has to have no answers adjancent or questions adjancent
        //check above (below not necessary)
        If (isset($row['PARENT_ID'])){ //if not NULL, not the top node
            $type = $row['TYPE'];
            foreach ($questionsAndAnswersArray as $row2) {
                if ($row['PARENT_ID'] == $row2['CONNECTION_ID'] && $row['TYPE'] == $row2['TYPE']) {
                    //if same type
                    if ($row2['TYPE'] == "question") {
                        $problemQuestionAnswersArray[] = array (
                            'problemCode' => "adjacent-question",
                            "shortId" => $row2['SHORT_QUESTION_ID'],
                            "questionOrAnswerId" => $row2['question_QUESTION_ID']
                        );
                    } else if ($row2['TYPE'] == "answer") {
                        $problemQuestionAnswersArray[] = array (
                            'problemCode' => "adjacent-answer",
                            "shortId" => $row2['SHORT_ANSWER_ID'],
                            "questionOrAnswerId" => $row2['answer_ANSWER_ID']
                        );
                    }
                }
            }
        
        }
        if (isset($problemQuestionAnswersArray)) {
            return $problemQuestionAnswersArray;
        } else {
            return false;
        }
    }


    /**
     * Creates a Array from the question_answer table to iterate through with other fuctions
     * 
     * @param dbLogic $dbLogic reuse current connection to the databse
     * @param string $quizId the quiz to generate the list from
     * @return array The PHP array of similar structyre to the databse
     * ['id'] = the connection id
     * ['type'] = the type, question or answer, a string
     * ['questionOrAnswerId'] the id of the question or answer id
     */
    protected static function prepareRecursiveListQuestionAnswer (dbLogic $dbLogic, $quizId) {
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
    protected static function increaseDepthOfChildNodes (dbLogic $dbLogic, $index, $parent_id){
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
     * @param dbLogic $dbLogic The Current connection the databse (reuse it)
     * @param string $answerContent The answer's text
     * @param string $feedbackContent The feedback from the answer
     * @param string $isCorrect The number (string) indictcating if correct, neutral etc
     * @return string The answer's primary key, ConnectionID
     */
    protected static function insertAnswerintoAnswerTable (dbLogic $dbLogic, $answerContent, $feedbackContent, $isCorrect){
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
     * @param dbLogic $dbLogic The Current connection the databse (reuse it)
     * @param string $questionTitle The title of the question
     * @param string $questionContent The paragraph for the question
     * @param string $questionImageUploadfile The filename of the image
     * @param string $questionAlt The ALt text for the question's image
     * @return string The answer's primary key, ConnectionID
     */
    protected static function insertQuestionIntoQuestionTable (dbLogic $dbLogic, $questionTitle, $questionContent, $questionImageUploadfile, $questionAlt){
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
     * @param dbLogic $dbLogic The Current connection the databse (reuse it)
     * @param string $questionId The Question ID form the question table insert
     * @param string $quizId The quiz to assoicate the question with
     * @param string $parentId The parent CONNECTION_ID (optional, added to the otherwise)
     * @param string $answerConId The 
     * @return string The question_answer's primary key, ConnectionID (for the question just inserted)
     */
    protected static function insertQuestionIntoQuestionAnswerTable (dbLogic $dbLogic, $questionOrAnswerId, $quizId, $parentId = NULL, $operation = "addBelow", $addToType = "answer"){
        //($operation == "addBelow" || $operation == "addAbove"
        //$addToType
        
        if($operation == "addToAnswerAbove") {
            //get the parent id of the selected question or answer and use it (same parent id as the slected node)
            $whereValuesArray = array(
                    "CONNECTION_ID" => $parentId
                );
            $parentIdArray = $dbLogic->select("PARENT_ID", "question_answer", $whereValuesArray);
            $parentId = $parentIdArray['PARENT_ID'];
        }
        
        $insertArray = array(
            "question_QUESTION_ID" => $questionOrAnswerId,
             "TYPE" => "question",
             "quiz_QUIZ_ID" => $quizId,
            "PARENT_ID" => $parentId
        );
        if (is_null($parentId)) {    //inserting at the top
            $insertArray["DEPTH"] = "0";
            $insertArray["SHORT_QUESTION_ID"] = "1";
            return $dbLogic->insert($insertArray, "question_answer");
        } else { //inserting somewhere else, use insert Selct to get the depth right
            $whereArray = array(
                "question_answer.quiz_QUIZ_ID" => $quizId
            );
            //COALESCE(DEPTH+1, 0) - insert 0 if depth is null
            //INSERT INTO question_answer (DEPTH, SHORT_QUESTION_ID, question_QUESTION_ID, TYPE, quiz_QUIZ_ID, PARENT_ID) SELECT DEPTH+1, MAX(SHORT_QUESTION_ID) +1, '114', 'question', '1', '208' FROM question_answer WHERE CONNECTION_ID = '208'
            return $dbLogic->insertWithSelectWhere("question_answer", "DEPTH, SHORT_QUESTION_ID, question_QUESTION_ID, TYPE, quiz_QUIZ_ID, PARENT_ID",
                    "COALESCE(DEPTH+1, 0), COALESCE(MAX(SHORT_QUESTION_ID)+1, 1)", "question_answer",$whereArray, $insertArray); //depth is increased from last
        }
    }
    /**
     * Inserts a answer into the "question_answer" table
     * 
     * @param dbLogic $dbLogic The Current connection the databse (reuse it)
     * @param string $answerId The answer ID form the answer table insert
     * @param string $quizId The quiz to assoicate the question with
     * @param string $link the question to link the naswer to in a different branch
     * @param string $questionConnectionId The connectionId from the previous question [insert] (attach it)
     * @param string $parentId The question to attach to (optional)
     * @return string The question_answer's primary key, ConnectionID (for the answer just inserted)
     */
    protected static function insertAnswerIntoQuestionAnswerTable (dbLogic $dbLogic, $answerId, $quizId, $questionOrAnswerConId, $operation, $addToType){
        //($operation == "addBelow" || $operation == "addAbove"
        //$addToType
        $parentId = NULL;
        if($operation == "addToAnswerAbove") {
            //get the parent id of the selected question or answer and use it
            $whereValuesArray = array(
                    "CONNECTION_ID" => $parentId
                );
            $parentIdArray = $dbLogic->select("PARENT_ID", "question_answer", $whereValuesArray);
            $parentId = $parentIdArray['PARENT_ID'];
        }
        $insertArray = array(
                "answer_ANSWER_ID" => $answerId,
                 "TYPE" => "answer",
                "PARENT_ID" => $questionOrAnswerConId,
                "quiz_QUIZ_ID" => $quizId
        );
        //inserting somewhere, use insert Select to get the depth right
        $whereArray = array(
            "question_answer.quiz_QUIZ_ID" => $quizId
        );
        //COALESCE - return 1 if MAX(SHORT_ANSWER_ID is null 
        return $dbLogic->insertWithSelectWhere("question_answer", "DEPTH, SHORT_ANSWER_ID, answer_ANSWER_ID, TYPE, PARENT_ID, quiz_QUIZ_ID",
                "COALESCE(DEPTH+1, 0), COALESCE(MAX(SHORT_ANSWER_ID) +1, 1)", "question_answer", $whereArray, $insertArray); //depth is increased from last
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
    public static function insertAnswer($quizId, $prevQuestionOrAnswerId, $answerContent, $feedbackContent, $isCorrect, $operation, $addToType){
        $dbLogic = new dbLogic();
        ///check the question/answer is on the same quiz
        if ($addToType == "answer") {
            $prevConId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $prevQuestionOrAnswerId);
        } else { //question
            $prevConId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $prevQuestionOrAnswerId);
        }
        if ($prevConId == false){
            return false;
        }
        //insert answer and get it's answer id
        $answerId = self::insertAnswerintoAnswerTable($dbLogic, $answerContent, $feedbackContent, $isCorrect);
        //insert it using the id retrieved eariler
        $newConId = self::insertAnswerIntoQuestionAnswerTable($dbLogic, $answerId, $quizId, $prevConId, $operation, $addToType);
        //relink the sub child to this one
        if($operation == "addToAnswerAbove") {
            //set the original to the new connection id of teh inbetweener
            self::linkSubChildToThisConId ($dbLogic, $prevConId, $newConId);
        }
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
    public static function insertQuestion($quizId, $prevQuestionOrAnswerId, $questionTitle, $questionContent, $targetFileName, $questionAlt, $operation, $addToType){
        $dbLogic = new dbLogic();
        //check the question/answer is on the same quiz
        if ($addToType == "answer") {
            $prevConId = self::checkAnswerBelongsToQuizReturnId($dbLogic, $quizId, $prevQuestionOrAnswerId);
        } else { //question
            $prevConId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $prevQuestionOrAnswerId);
        }
        if ($prevConId == false){
            return false;
        }
        
        //insert question and get it's Question id
        $questionId = self::insertQuestionIntoQuestionTable($dbLogic, $questionTitle, $questionContent, $targetFileName, $questionAlt);
        //insert it using the id retrieved eariler
        $newConId = self::insertQuestionIntoQuestionAnswerTable($dbLogic, $questionId, $quizId, $prevConId, $operation, $addToType);
        //relink the sub child to this one
        if($operation == "addAbove") {
            //set the original to the new connection id of teh inbetweener
            self::linkSubChildToThisConId ($dbLogic, $prevConId, $newConId);
        }
        var_dump($operation == "addAbove");
        var_dump($operation);
        //die();
        //all good, so returnn true
        return true;
    }
    private static function linkSubChildToThisConId (dbLogic $dbLogic, $prevConId, $newConId){
        //relink the sub child to this one
        if($operation == "addToAnswerAbove") {
            //set the original to the new connection id of teh inbetweener
            $whereValuesArray = array("CONNECTION_ID" => $prevConId);
            $setValuesArray = array ("PARENT_ID" => $newConId);
            $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray);
        }
    }
}

