<?php
/**
 * The Loader for the Manage Takers page
 */
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");

// end of php file inclusion
$quizId = editQuizInitialLoadLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$sharedQuizId = quizLogic::returnSharedQuizID($quizId);
$quizUrl = editQuizInitialLoadLogic::returnQuizUrl($sharedQuizId);
$username = $userLogic->getUsername();
editQuizInitialLoadLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);

$confirmAddMessage = " ";
$confirmAddError = " ";
$confirmRemoveMessage = " ";
$confirmRemoveError = " ";

if ($_SERVER['REQUEST_METHOD'] === "POST") { 

    $confirmUsername = filter_input(INPUT_POST, "newUser");
    
    //First retrieve whether quiz is public or private, must be private to add/remove users from.
    $isPublic = takerLogic::isQuizPublic($quizId);

    //IF user wants to add new taker, perform the following:
    if (isset($_POST['confirmAddUser'])) {
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            if ($isPublic == false){
                //Check username is number and letters, then check it doesnt exist before inserting into 'taker'
                if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {
                    if(takerLogic::isUserATakerOnThisQuiz($confirmUsername, $sharedQuizId)== false){ //if not a taker
                        userLogic::createUserIfNotExist($confirmUsername);
                        takerLogic::addUseerToTakersOnThisQuiz($confirmUsername, $sharedQuizId);
                        $confirmAddMessage = 'User '.$confirmUsername.' has been successfully added to the list of approved takers.';
                    } else {
                        $confirmAddError = 'User is already a taker for this quiz.';
                    }
                } else {
                    $confirmAddError = 'Incorrect Username format. Must contain letters and numbers only.';
                }                     
        } else {
            $confirmAddError = 'This is a public quiz. Every registered user can attempt it, there is no need to add eligible takers.'; 
        }
    } else {
        $confirmAddError = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
    }

    //IF user wants to REMOVE taker, perform the following:
    //Possibly include a javascript 'Are you sure?' dialogue box??
    }   else if (isset($_POST['removename'])) {
        $confirmUsername = ($_POST['removename']);
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            if ($isPublic == false){
                //Check username is number and letters, then check it doesnt exist before removing from 'taker'
                if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {
                    if(takerLogic::isUserATakerOnThisQuiz($confirmUsername, $sharedQuizId)== true) { //if they are taker
                        takerLogic::removeUserFromTakersOnThisQuiz($confirmUsername, $sharedQuizId);
                        $confirmRemoveMessage = 'User '.$confirmUsername.' has been successfully removed from the list of approved takers.';

                    } else{
                        $confirmRemoveError = 'User is not a registered taker for this quiz.';
                    }
                } else{
                    $confirmRemoveError = 'Incorrect Username format. Must contain letters and numbers only.';
                }
            } else{
                $confirmRemoveError = 'This is a public quiz, every registered user can attempt it. There are no takers to remove while quiz is public.';
            }
 
        } else{
            $confirmRemoveError = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
        }
    }
}

$quizUsers = takerLogic::returnListOfTakers($sharedQuizId);

//html
include("takers-view.php");


