<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$confirmAddMessage = " ";
$confirmRemoveMessage = " ";
$addDate = date('Y-m-d H:i:s');
$dbLogic = new DB();

if ($_SERVER['REQUEST_METHOD'] === "POST") { 

    $confirmUsername = filter_input(INPUT_POST, "addNewUser");
    $confirmRemoveUsername = filter_input(INPUT_POST, "removeUser");
         
    //IF user wants to add new editor, perform the following:
    if (isset($_POST['confirmAddUser'])) {
            //Check username is number and letters, then check it doesnt exist before inserting into 'editor'
            if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {

                $array = array(
                    "user_USERNAME" => $confirmUsername,
                    "quiz_QUIZ_ID" => $quizIDGet
                );

                $userResults = $dbLogic->select("*", "editor", $array, true);

                if(empty($userResults)){
                    $insertArray = array(
                        "user_USERNAME" => $confirmUsername,
                        "quiz_QUIZ_ID" => $quizIDGet,
                        "ADDED_AT" => $addDate,
                        "ADDED_BY" => $_SESSION["username"]
                    );           
                    $insertUserResults = ($dbLogic->insert($insertArray, "editor"));     
                    $confirmAddMessage = 'User '.$confirmUsername.' has been successfully added to the list of approved Editors.';

                }
                else{
                    $confirmAddMessage = 'User is already an Editor for this quiz.';
                }
            }
            else{
                $confirmAddMessage = 'Incorrect Username format. Must contain letters and numbers only.';
            }
    }
        
    //IF user wants to REMOVE taker, perform the following
    //Possibly include a javascript 'Are you sure?' dialogue box??
    else if (isset($_POST['confirmRemoveUser'])) {
            //Check username is number and letters, then check it doesnt exist before removing from 'editor'
            if (preg_match("/^([A-Za-z0-9]+)$/", $confirmRemoveUsername)) {

                $array = array(
                    "user_USERNAME" => $confirmRemoveUsername,
                    "quiz_QUIZ_ID" => $quizIDGet
                );

                $userResults = $dbLogic->select("*", "editor", $array, true);

                if(!empty($userResults)){
                    $removeArray = array(
                        "user_USERNAME" => $confirmRemoveUsername,
                        "quiz_QUIZ_ID" => $quizIDGet,
                    );           
                    $removeUserResults = ($dbLogic->delete($removeArray, "editor"));     
                    $confirmRemoveMessage = 'User '.$confirmRemoveUsername.' has been successfully removed from the list of approved takers.';

                }
                else{
                    $confirmRemoveMessage = 'User is not a registered Editor for this quiz.';
                }
            }
            else{
                $confirmRemoveMessage = 'Incorrect Username format. Must contain letters and numbers only.';
            }
        
    }
  
}

//html
include("editors-view.php");