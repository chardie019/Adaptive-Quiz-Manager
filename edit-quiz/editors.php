<?php
 // include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");

// end of php file inclusion

$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$confirmAddMessage = " ";
$confirmRemoveMessage = " ";
$addDate = date('Y-m-d H:i:s');
$dbLogic = new DB();

if ($_SERVER['REQUEST_METHOD'] === "POST") { 

    $confirmUsername = filter_input(INPUT_POST, "newUser");
    
    
    //First retrieve whether quiz is public or private, must be private to add/remove users from, and SHARED_ID is needed
    $dataArray = array(
        "QUIZ_ID" => $quizIDGet
    );
    
    $quizDetails = $dbLogic -> select('*', 'QUIZ', $dataArray, true);
    
    //IF user wants to add new editor, perform the following:
    if (isset($_POST['confirmAddUser'])) {
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            //Check username is number and letters, then check it doesnt exist before inserting into 'editor'
            if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {

                $array = array(
                    "user_USERNAME" => $confirmUsername,
                    "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID']
                );

                $userResults = $dbLogic->select("user_USERNAME, shared_SHARED_QUIZ_ID", "editor", $array, true);

                if(empty($userResults)){
                    $insertArray = array(
                        "user_USERNAME" => $confirmUsername,
                        "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID'],
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
        }else{
            $confirmAddMessage = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
        }
    }
    //IF user wants to REMOVE taker, perform the following
    //Possibly include a javascript 'Are you sure?' dialogue box??
    else if (isset($_POST['confirmRemoveUser'])) {
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            //Check username is number and letters, then check it doesnt exist before removing from 'editor'
            if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {

                $array = array(
                    "user_USERNAME" => $confirmUsername,
                    "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID']
                );

                $userResults = $dbLogic->select("user_USERNAME, shared_SHARED_QUIZ_ID", "editor", $array, true);

                if(!empty($userResults)){
                    $removeArray = array(
                        "user_USERNAME" => $confirmUsername,
                        "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID']
                    );           
                    $removeUserResults = ($dbLogic->delete("editor", $removeArray));     
                    $confirmRemoveMessage = 'User '.$confirmUsername.' has been successfully removed from the list of approved editors.';

                }
                else{
                    $confirmRemoveMessage = 'User is not a registered Editor for this quiz.';
                }
            }
            else{
                $confirmRemoveMessage = 'Incorrect Username format. Must contain letters and numbers only.';
            }
        
        }else{
            $confirmRemoveMessage = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
        }
    }
}


    $dataArray = array(
        "QUIZ_ID" => $quizIDGet,       
    );
    
    $whereColumn = array(
        "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
    );
    
    $quizUsers = $dbLogic ->selectWithColumnsOrder('user_USERNAME', 'quiz, editor', $dataArray, $whereColumn, "user_USERNAME", false);
    


//html
include("editors-view.php");
