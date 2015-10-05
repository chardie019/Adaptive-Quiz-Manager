<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");

// end of php file inclusion
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$confirmAddMessage = " ";
$confirmAddError = " ";
$confirmRemoveMessage = " ";
$confirmRemoveError = " ";
$addDate = date('Y-m-d H:i:s');
$dbLogic = new DB();

if ($_SERVER['REQUEST_METHOD'] === "POST") { 

    $confirmUsername = filter_input(INPUT_POST, "newUser");
    
    //First retrieve whether quiz is public or private, must be private to add/remove users from.
    $dataArray = array(
        "QUIZ_ID" => $quizIDGet
    );
    
    $quizDetails = $dbLogic -> select('SHARED_QUIZ_ID, IS_PUBLIC', 'quiz', $dataArray, true);
    //Is there another place we can pull the IS_PUBLIC value from?
    
        
    //IF user wants to add new taker, perform the following:
    if (isset($_POST['confirmAddUser'])) {
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            if ($quizDetails['IS_PUBLIC'] == '0'){
                //Check username is number and letters, then check it doesnt exist before inserting into 'taker'
                if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {

                    $array = array(
                        "user_USERNAME" => $confirmUsername,
                        "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID']
                    );

                    $userResults = $dbLogic->select("user_USERNAME, shared_SHARED_QUIZ_ID", "taker", $array, true);
                    
                    if(empty($userResults)){
                        $insertArray = array(
                            "user_USERNAME" => $confirmUsername,
                            "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID'],
                            "ADDED_AT" => $addDate,
                            "ADDED_BY" => $_SESSION["username"]
                        );           
                        $insertUserResults = ($dbLogic->insert($insertArray, "taker"));     
                        $confirmAddMessage = 'User '.$confirmUsername.' has been successfully added to the list of approved takers.';
                    }
                    else{
                        $confirmAddError = 'User is already a taker for this quiz.';
                    }
                }
                else{
                    $confirmAddError = 'Incorrect Username format. Must contain letters and numbers only.';
                }                     
        }
        else{
            $confirmAddError = 'This is a public quiz. Every registered user can attempt it, there is no need to add eligible takers.'; 
        }
    }
    else{
        $confirmAddError = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
    }

        
    //IF user wants to REMOVE taker, perform the following:
    //Possibly include a javascript 'Are you sure?' dialogue box??
    }   else if (isset($_POST['removename'])) {
        $confirmUsername = ($_POST['removename']);
        if($_SESSION['IS_QUIZ_ENABLED'] == false){
            if ($quizDetails['IS_PUBLIC'] == '0'){
                //Check username is number and letters, then check it doesnt exist before removing from 'taker'
                if (preg_match("/^([A-Za-z0-9]+)$/", $confirmUsername)) {

                    $array = array(
                        "user_USERNAME" => $confirmUsername,
                        "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID']
                    );

                    $userResults = $dbLogic->select("user_USERNAME, shared_SHARED_QUIZ_ID", "taker", $array, true);
                    
                    if(!empty($userResults)){
                        $removeArray = array(
                            "user_USERNAME" => $confirmUsername,
                            "shared_SHARED_QUIZ_ID" => $quizDetails['SHARED_QUIZ_ID'],
                        );           
                        $removeUserResults = ($dbLogic->delete("taker", $removeArray));     
                        $confirmRemoveMessage = 'User '.$confirmUsername.' has been successfully removed from the list of approved takers.';

                    }
                    else{
                        $confirmRemoveError = 'User is not a registered taker for this quiz.';
                    }
                }
                else{
                    $confirmRemoveError = 'Incorrect Username format. Must contain letters and numbers only.';
                }
            }
            else{
                $confirmRemoveError = 'This is a public quiz, every registered user can attempt it. There are no takers to remove while quiz is public.';
            }
 
        }
        else{
            $confirmRemoveError = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
        }
  
    }
}


    $dataArray = array(
        "QUIZ_ID" => $quizIDGet,       
    );
    
    $whereColumn = array(
        "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
    );
    
    $quizUsers = $dbLogic ->selectWithColumnsOrder('user_USERNAME', 'quiz, taker', $dataArray, $whereColumn, "user_USERNAME", false);
    

//html
include("takers-view.php");


