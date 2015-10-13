<?php
/**
 * Various Funcation to do with the manage takers page and other misc taker functions
 */
class takerLogic {
    /**
     * Determines if a quiz is public or not
     * 
     * @param string $quizId the real quiz id of teh quiz to check if it is public
     * @return boolean return true if it is public, flase if not
     */
    public static function isQuizPublic($quizId){
        $dbLogic = new dbLogic();
        $dataArray = array(
            "QUIZ_ID" => $quizId
        );
        $quizDetails = $dbLogic -> select('IS_PUBLIC', 'quiz', $dataArray, true);
        if ($quizDetails['IS_PUBLIC'] == "1") {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Determines if a user is a taker on a quiz
     * 
     * @param string $username the username to check with
     * @param type $sharedQuizId the shared quiz id of the quiz to check
     * @return boolean return true if they are a user, false if not
     */
    public static function isUserATakerOnThisQuiz($username, $sharedQuizId) {
        $dbLogic = new dbLogic();
        $array = array(
            "user_USERNAME" => $username,
            "shared_SHARED_QUIZ_ID" => $sharedQuizId
        );
        $result = $dbLogic->select("1", "taker", $array, true);
        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * Adds a user to the takers on a quiz
     * 
     * @param string $username the the user to add
     * @param string $sharedQuizId the shared quiz ID of the quiz to add
     */
    public static function addUseerToTakersOnThisQuiz($username, $sharedQuizId) {
        $addDate = date('Y-m-d H:i:s');
        $dbLogic = new dbLogic();
        $insertArray = array(
            "user_USERNAME" => $username,
            "shared_SHARED_QUIZ_ID" => $sharedQuizId,
            "ADDED_AT" => $addDate,
            "ADDED_BY" => $_SESSION["username"]
        );           
        $dbLogic->insert($insertArray, "taker");
    }
    /**
     * Remove a user from the takers on a quiz
     * 
     * @param string $username The username to be removed for the quiz
     * @param string $sharedQuizId the shared Quiz ID of teh quiz assocaited with the removal of the user
     */
    public static function removeUserFromTakersOnThisQuiz($username, $sharedQuizId) {
        $dbLogic = new dbLogic();
        $removeArray = array(
            "user_USERNAME" => $username,
            "shared_SHARED_QUIZ_ID" => $sharedQuizId,
        );           
        $dbLogic->delete("taker", $removeArray); 
    }
    /**
     * Returns a list of usernames who are takers for a quiz
     * 
     * @param string $sharedQuizId the shared quiz ID of quiz to check who the takers are
     * @return array the list if users who can take the quiz
     */
    public static function returnListOfTakers($sharedQuizId) {
        $dbLogic = new dbLogic();
        $whereValuesArray = array(
            "shared_SHARED_QUIZ_ID" => $sharedQuizId,       
        );
        return $dbLogic->selectOrder('user_USERNAME', 'taker', $whereValuesArray, "user_USERNAME", false);
    }
}