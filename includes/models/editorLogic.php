<?php
/**
 * Some functions to do with the manage editors page
 */

class editorLogic {
    /**
     * Rturns a list of editors from the database
     * 
     * @param string $sharedQuizId the real quiz Id to query
     * @return array An array from he databse of the editors
     */
    public static function returnListOfEditors($sharedQuizId){
        $dbLogic = new dbLogic();
        $whereValuesArray = array(
            "shared_SHARED_QUIZ_ID" => $sharedQuizId,       
        );
        return $dbLogic->selectOrder('user_USERNAME', 'editor', $whereValuesArray, "user_USERNAME", false);
    }
    /**
     * Determines of a user is an editor on a quiz
     * 
     * @param string $username the username of te user
     * @param string $sharedQuizId The shared quiz ID of the quiz 
     * @return boolean Return true if they are an editor, false otherwise
     */
    public static function isUserAnEditorOnThisQuiz ($username, $sharedQuizId) {
        $dbLogic = new dbLogic();
        $array = array(
            "user_USERNAME" => $username,
            "shared_SHARED_QUIZ_ID" => $sharedQuizId
        );
        $userResults = $dbLogic->select("1", "editor", $array, true);
        if(empty($userResults)){
            return false;
        } else {
            return true;
        }
    }
    /**
     * Adds a user to the list if editors for a quiz
     * 
     * @param string $username the username to be an editor
     * @param string $sharedQuizId the shared quiz id to be be an editor for
     * @param string $addedByUsername the username of the current username to be be in the "add by " column
     */
    public static function addUserToEditorsOnThisQuiz($username, $sharedQuizId, $addedByUsername) {
        $addDate = date('Y-m-d H:i:s');
        $dbLogic = new dbLogic();
        $insertArray = array(
            "user_USERNAME" => $username,
            "shared_SHARED_QUIZ_ID" => $sharedQuizId,
            "ADDED_AT" => $addDate,
            "ADDED_BY" => $addedByUsername
        );           
        $dbLogic->insert($insertArray, "editor");
    }
    /**
     * Removes a user from teh the editors ist form a quiz
     * 
     * @param string $username the username to remove
     * @param string $sharedQuizId the shared quiz ID for teh quiz to be have the editor removed from
     */
    public static function removeUserFromEditorsOnThisQuiz($username, $sharedQuizId) {
        $dbLogic = new dbLogic();
        $removeArray = array(
            "user_USERNAME" => $username,
            "shared_SHARED_QUIZ_ID" => $sharedQuizId
        );           
        $dbLogic->delete("editor", $removeArray);
    }
}