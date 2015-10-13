<?php
/**
 * Conatins initiatl loading stuff for the edit quiz pages
 */

class editQuizInitialLoadLogic extends quizLogic {
        /**
     * gets the REAL quiz ID by inspecting the db with teh shared quiz ID from teh URL
     * 
     * @param string $quizIDPost [optional] validate the a different quiz id instead (eg post)
     * @return string THE REAL Quiz ID
     */

    public static function getQuizIdFromUrlElseReturnToEditQuiz($quizIDPost = NULL) {
        if (isset($quizIDPost)) {
            $untrustedSharedQuizId = $quizIDPost;
        } else {
            $untrustedSharedQuizId = filter_input(INPUT_GET, "quiz");
        }
        $sharedQuizId = (string)quizLogic::returnRealQuizID($untrustedSharedQuizId);
        if(!isset($sharedQuizId) || $sharedQuizId === ""){
            //back to edit quiz
            self::jumpBackToEditQuizList("no-quiz-selected");  
        } else {
            //it's real so return it
            return $sharedQuizId;
        }
    }
    /**
     * Checks if a user can edit a quiz. exits if user can not edit, returns void if so.
     * 
     * @param string $sharedQuizId The shared quiz ID
     * @param string $username The username eg jgraha50
     * @return boolean void; 
     */
    public static function canUserEditQuizElseReturnToEditQuiz ($sharedQuizId, $username) {
        $dbLogic = new dbLogic();
        $whereValuesArray = array(
          "shared_SHARED_QUIZ_ID" => $sharedQuizId,
          "user_USERNAME" => $username
        );
        $result = $dbLogic->select("1", "editor", $whereValuesArray); //1 is retun a manningless column (if query is correct)
        if (count($result) > 0){
            return; //all good so return
        } else {
            self::jumpBackToEditQuizList("no-edit-permission");
        }
    }
    /**
     * Jumps teh user to the edit quiz list and putsthe reason in the URL
     * 
     * @param string $reason
     */
    public static function jumpBackToEditQuizList ($reason) {
        header('Location: ' . CONFIG_ROOT_URL . '/edit-quiz.php?message='.$reason);
        exit; 
    }
    /**
     * Generates the start of prarametrs for URL - aka ?quiz=sharedQuizId
     * 
     * No modication/checking at all!
     * 
     * @param string $sharedQuizId the shared quiz ID
     * @return string URL params - "?quiz=sharedQuizId"
     */
    public static function returnQuizUrl ($sharedQuizId) {
        return "?quiz=$sharedQuizId";
    }
}