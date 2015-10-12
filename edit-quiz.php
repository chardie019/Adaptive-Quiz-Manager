<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

$dbLogic = new dbLogic();

/*
 * Store quizid from edit-quiz-list in session variable to be used in edit-quiz-view.php 
 * as it passes as empty after the first time it is posted from edit-quiz-list and cant be accessed.
 */


$confirmActive = "";
$enableSubMenuLinks = true; //default the links work
$quizIDGet = filter_input(INPUT_GET, "quiz");

if (!is_null($quizIDGet)){
    $quizId = quizLogic::returnRealQuizID($quizIDGet);
} else {
    $quizId = NULL;
}

$quizCreated = filter_input(INPUT_GET, "create");
if ($quizCreated == "yes"){
    $createQuizConfirmation = "Quiz Successfully created!";
} else {
    $createQuizConfirmation = "";
}

$reason = filter_input(INPUT_GET, "message");
if (isset($reason)){
    switch($reason){
        case 'no-quiz-selected':
            $message = "Please select a quiz to contine.";
            break;
        case 'no-edit-permission':
            $message = "You do not have edit permissions on that quiz.";
            break;
        default:
            $message = 'Unknown Error';
    }
} else {
    $message = "";
}

/* User can only edit quiz information if IS_ENABLED is set to inactive so as not to disrupt users. 
     * Check if IS_ENABLED is already set for validation in editing details, questions, editors, takers.
     */
$isEnabledState = editQuizLogic::isQuizEnabled($quizId);
if (is_null($isEnabledState)){

} else {
    $_SESSION["IS_QUIZ_ENABLED"] = $isEnabledState; //set the state (true or false)
    $enableSubMenuLinks = $isEnabledState;
}


if($_SERVER['REQUEST_METHOD'] === "POST"){
    $quizIDPost = filter_input(INPUT_POST, "quizid");

    $quizId = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz($quizIDPost);
    $sharedQuizId = quizLogic::returnSharedQuizID($quizId);
    $quizUrl = quizLogic::returnQuizUrl($sharedQuizId);
    $username = $userLogic->getUsername();
    //quizLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);

    $selectQuizButton = filter_input(INPUT_POST, "selectQuiz");
    $confirmEnabledButton = filter_input(INPUT_POST, 'confirmEnabled');
    $confirmDisabledButton = filter_input(INPUT_POST, 'confirmDisabled');

    
    if (isset($selectQuizButton)) {
        
        //Page is being loaded from edit-quiz-list with quizid selected    
        header('Location: ' . CONFIG_ROOT_URL . '/edit-quiz.php'.$quizUrl);
        exit;
        //If ENABLE button is pushed, update row in database
    }else if (isset($confirmEnabledButton)) {
        //now quiz has to have at least 2 questions & 1 answer
        //has to have a question ans teh start and at the end of eah of teh branches
        //has to have no answers adjancent or questions adjancent
        $problemQuestionAnswersArray = editQuestionLogic::returnProblemQuestionAnswersIntegrityCheck($quizId);
        //if returned an array with no entries
        if (empty($problemQuestionAnswersArray)) {
            //TO DO if changes
            if(isset($_SESSION['CURRENT_EDIT_QUIZ_EDITED'])){ //if the session was set, ergo, was edited, otherwise is NULL
                $_SESSION['CURRENT_EDIT_QUIZ_EDITED'] = NULL; //edited changes are now commmited
            }
            $confirmActive = editQuizLogic::setQuizToEnabled($quizId);
            //Set flag variable that is checked before commiting edits in other pages
            $_SESSION["IS_QUIZ_ENABLED"] = true;
            $enableSubMenuLinks = true;
            quizLogic::setQuizToConsistentState($dbLogic, $quizId);
        } else {
            $invalidQuestionAnswersDisplayArray = editQuizViewLogic::formatProblemQuizArray($problemQuestionAnswersArray, $quizUrl);
        }
    //If DISABLE button is pressed, update row in database 
    }else if(isset($confirmDisabledButton)){
        $confirmActive = editQuizLogic::setQuizToDisabled($quizId);
        //Set flag variable that is checked before commiting edits in other pages
        $_SESSION["IS_QUIZ_ENABLED"] = false;
        $enableSubMenuLinks = false; //no menu links
    }

    include('edit-quiz-view.php');
//If coming from home page, display quiz list for user to select
}else if(is_null($quizIDGet)){
    $username = $userLogic->getUsername();
    //Retrieve the most current versions of quizzes for which the user is an editor
    $nameArray = editQuizLogic::returnEditorQuizList($username);
    include('edit-quiz-list-view.php');
// GET request
}else{
    $quizId = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
    $sharedQuizId = quizLogic::returnSharedQuizID($quizId);
    $quizUrl = quizLogic::returnQuizUrl($sharedQuizId);
    $username = $userLogic->getUsername();
    quizLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);
    //get request and the quiz was specified
    include('edit-quiz-view.php');
}   
    
