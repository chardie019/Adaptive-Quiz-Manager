<?php
/**
 * The Loader for the Manage Editors Page
 */

//include php files here 
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
    
    //IF user wants to add new editor, perform the following:
    if (isset($_POST['confirmAddUser'])) {
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            //Check username is number and letters, then check it doesnt exist before inserting into 'editor'
            if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {
                if(editorLogic::isUserAnEditorOnThisQuiz($confirmUsername, $sharedQuizId) == false){
                    userLogic::createUserIfNotExist($confirmUsername);
                    editorLogic::addUserToEditorsOnThisQuiz($confirmUsername, $sharedQuizId, $_SESSION["username"]);
                    $confirmAddMessage = 'User '.$confirmUsername.' has been successfully added to the list of approved Editors.';
                }
                else{
                    $confirmAddError = 'User is already an Editor for this quiz.';
                }
            } else{
                $confirmAddError = 'Incorrect Username format. Must contain letters and numbers only.';
            }
        } else{
            $confirmAddError = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
        }
    }
    //IF user wants to REMOVE taker, perform the following
    //Possibly include a javascript 'Are you sure?' dialogue box??
    else if (isset($_POST['removename'])) {
        $confirmUsername = ($_POST['removename']);
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            if($_SESSION['username'] != $confirmUsername){
                //Check username is number and letters, then check it doesnt exist before removing from 'editor'
                if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {
                    if(editorLogic::isUserAnEditorOnThisQuiz($confirmUsername, $sharedQuizId) == true){
                        editorLogic::removeUserFromEditorsOnThisQuiz($confirmUsername, $sharedQuizId);
                        $confirmRemoveMessage = 'User '.$confirmUsername.' has been successfully removed from the list of approved editors.';

                    } else{
                        $confirmRemoveError = 'User is not a registered Editor for this quiz.';
                    }
                } else{
                    $confirmRemoveError = 'Incorrect Username format. Must contain letters and numbers only.';
                }
            } else{
                $confirmRemoveError = 'You cannot remove yourself as an Editor.';
            }
        } else{
            $confirmRemoveError = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
        }
    }
}

$quizUsers = editorLogic::returnListOfEditors($sharedQuizId);

//html
include("editors-view.php");
