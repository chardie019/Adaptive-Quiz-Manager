<?php
class editQuizLogic extends quizLogic {
    /**
     * Returns teh state if quiz is enabled or not
     * 
     * @param string $quizId the real quiz id
     * @return boolean|NULL true is enabled, false if not and NULL if unknown (could not find quiz in db)
     */
    public static function isQuizEnabled($quizId){
        $dbLogic = new DB();
        $whereValuesArray = array(
            "QUIZ_ID" => $quizId
        );
        $isEnabledStateArray = $dbLogic->select("IS_ENABLED", "QUIZ", $whereValuesArray, true);
        if (!empty($isEnabledStateArray)){
            if($isEnabledStateArray['IS_ENABLED'] == '1'){
                return true;

            }else{
                return false;
            }
        } else {
            return NULL;
        }
    }
    /**
     * Sets a quiz to be enabled
     * 
     * @param string $quizId The real quiz id to be enabled
     * @return the message that it is enabled
     */
    public static function setQuizToEnabled ($quizId) {
        $dbLogic = new DB();
        $setColumnsArray = array(
            "IS_ENABLED" => "1"
        );
        $whereValuesArray = array(
            "QUIZ_ID" => $quizId
        );
        $dbLogic->updateSetWhere("QUIZ", $setColumnsArray, $whereValuesArray);
        return "Quiz is now ENABLED, and CAN be attempted by users.";
    }
    /**
     * Set the Quiz to Disabled
     * 
     * @param string $quizId The real quiz ID to be disabled
     * @return string the message that it is disabled.
     */
    public static function setQuizToDisabled ($quizId) {
        $dbLogic = new DB();
        $setColumnsArray = array(
            "IS_ENABLED" => "0"
        );
        $whereValuesArray = array(
            "QUIZ_ID" => $quizId
        );
        $dbLogic->updateSetWhere("QUIZ", $setColumnsArray, $whereValuesArray);
        return "Quiz is now DISABLED, and CANNOT be attempted by users.";
    }
    /**
     * Returns an array matrix of the quiz's QUIZ_NAME, DESCRIPTION, SHARED_QUIZ_ID, QUIZ_ID and VERSION
     * 
     * @param string $username The username to be checked with
     */
    public static function returnEditorQuizList ($username) {
        $dbLogic = new DB();
         //where coloumns

        $whereValuesArray = array(
            "user_USERNAME" => "$username"
            );
        $whereColumnsArray = array(
            "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
            );

        $quizEditId = $dbLogic->selectWithColumnsGroupBy("SHARED_QUIZ_ID, MAX(QUIZ_ID) AS QUIZ_ID", "quiz, editor", 
            $whereValuesArray, $whereColumnsArray, 'SHARED_QUIZ_ID', false);

        /*Run another set of queries using the QUIZ_ID and SHARED_QUIZ_ID retrieved above to obtain the name
         *of the most current version of the quiz, as well as the most current version. Include QUIZ_ID and
         *SHARED_QUIZ_ID in returned results so all fields can be accessed from the nameArray array on stats-view.
         *WARNING** Simply running the single query above and including the additional fields does not result in 
         *the correct QUIZ_NAME being linked with the correct QUIZ_ID due to the MAX requirement. Second query is required.
         */
        $nameArray = array();
        foreach($quizEditId as $columnResult){

            $wherevalues2 = array(
                "user_USERNAME" => "$uid"
            );

            $wherevalues3 = array(
                "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID",
                "SHARED_QUIZ_ID" => $columnResult['SHARED_QUIZ_ID'],
                "QUIZ_ID" => $columnResult['QUIZ_ID']
            );

            $quizNameArray = $dbLogic->selectWithColumnsGroupBy("QUIZ_NAME, DESCRIPTION, SHARED_QUIZ_ID, QUIZ_ID, MAX(VERSION) AS VERSION",
                    'quiz, editor', $wherevalues2, $wherevalues3, 'SHARED_QUIZ_ID', false);

            //Merge the array as $quizNameArray will be overwritten each iteration of foreach loop
            //Store the values inside nameArray which will be unaffected by foreach loop as it merges the values onto itself

            $nameArray = array_merge($nameArray, $quizNameArray);                  
        }
        return $nameArray;
    }
}

