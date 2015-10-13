<?php
/**
 * Contains function to do wth the edit quiz page
 */
class editQuizLogic extends quizLogic {
    /**
     * Returns the state if quiz is enabled or not
     * 
     * @param string $quizId the real quiz id
     * @return boolean|NULL true is enabled, false if not and NULL if unknown (could not find quiz in db)
     */
    public static function isQuizEnabled($quizId){
        $dbLogic = new dbLogic();
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
        $dbLogic = new dbLogic();
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
        $dbLogic = new dbLogic();
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
        $dbLogic = new dbLogic();
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
                "user_USERNAME" => "$username"
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
    /**
     * Removes type image on a quiz from the databse and the filesystem
     * 
     * @param string $quizId trhe real quiz id to have it's image removed
     */
    public static function removeImagefromQuiz($quizId){
        $dbLogic = new dbLogic();
        //get image value
        $whereValuesArray = array("QUIZ_ID" => $quizId);
        $result = $dbLogic->select("IMAGE", "quiz", $whereValuesArray);

        //remove the image from db
        $setColumnsArray = array("IMAGE" => NULL);
        $dbLogic->updateSetWhere("quiz", $setColumnsArray, $whereValuesArray);
        //delete current image file
        unlink(quizHelper::returnRealImageFilePath($quizId, $result['IMAGE']));
    }
    /**
     * 
     * @param type $quizId
     * @param type $isTime
     * @param type $timeHours
     * @param type $timeMinutes
     * @param string $noAttempts
     * @param type $yearStart
     * @param type $monthStart
     * @param type $dayStart
     * @param type $alwaysOpen
     * @param type $yearEnd
     * @param type $monthEnd
     * @param type $dayEnd
     * @param type $quizName
     * @param type $quizDescription
     * @param type $isPublic
     * @param type $isSave
     * @param type $quizImageText
     * @param type $quizImageUpload
     * @return string
     */
    public static function updateQuizDetails($quizId, $isTime, $timeHours, $timeMinutes,
            $noAttempts, $yearStart, $monthStart, $dayStart, $alwaysOpen, 
            $yearEnd, $monthEnd, $dayEnd, $quizName, $quizDescription, $isPublic, 
            $isSave, $quizImageText, $imageFieldName = NULL ) {
        $dbLogic = new dbLogic();
        
        if($isTime == '0'){
            $isTime = '00:00:00';
        }else{
            $isTime = '0'.$timeHours.':'.$timeMinutes.':00';
        }

        //Set Number of attempts to 0 for storing in database if there are unlimited attempts
        if($noAttempts == 'Unlimited'){
            $noAttempts = '0';
        }

        //Create String value for dateStart and dateEnd values
        $dateOpen = $yearStart."-".$monthStart."-".$dayStart." 00:00:00";
        if ($alwaysOpen == 0) {
            $dateClose = $yearEnd."-".$monthEnd."-".$dayEnd." 11:59:00"; 
        } else {
            $dateClose = NULL; 
        }
          

        $setValuesArray = array(
            "QUIZ_NAME" => $quizName,
            "DESCRIPTION" => $quizDescription,
            "IS_PUBLIC" => $isPublic,
            "NO_OF_ATTEMPTS" => $noAttempts,
            "TIME_LIMIT" => $isTime,
            "IS_SAVABLE" => $isSave,
            "DATE_OPEN" => $dateOpen,
            "DATE_CLOSED" => $dateClose,
            "IMAGE_ALT" => $quizImageText
        );
        if (isset($imageFieldName)) { //if not null
            $setValuesArray["IMAGE"] = $imageFieldName; //add image file name to array
        }

        $whereValuesArray = array("QUIZ_ID" => $quizId);

        //Insert quiz into database
        $dbLogic->updateSetWhere("quiz", $setValuesArray, $whereValuesArray);
        return "Your quiz has been successfully updated.";
    }
    public static function getQuizData($quizId){
        //Retrieve Quiz details to populate form
        $dbLogic = new dbLogic();
        $columns = "*";

        $dataArray = array(
            "QUIZ_ID" => $quizId
        );
        return $dbLogic->select($columns, 'quiz', $dataArray, true);
    }
}

