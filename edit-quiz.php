<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

$dbLogic = new DB();

/*
 * Store quizid from edit-quiz-list in session variable to be used in edit-quiz-view.php 
 * as it passes as empty after the first time it is posted from edit-quiz-list and cant be accessed.
 */
if(!empty($_POST["quizid"])){
    $quizIDPost = filter_input(INPUT_POST, "quizid");
    $_SESSION['CURRENT_EDIT_QUIZ_ID'] = $quizIDPost;
}


$quizIDGet = filter_input(INPUT_GET, "quiz");
$quizCreated = filter_input(INPUT_GET, "create");
if ($quizCreated == "yes"){
    $createQuizConfirmation = "Quiz Successfully created!";
} else {
    $createQuizConfirmation = "";
}

//Set default value used to set the colour of the active enable/disable button in edit-quiz-view
$_SESSION['enableButton'] = "myEnabled";

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    /* User can only edit quiz information if IS_ENABLED is set to inactive so as not to disrupt users. 
     * Check if IS_ENABLED is already set for validation in editing details, questions, editors, takers.
     */
    $checkEnable = array(
        "QUIZ_ID" => $_SESSION['CURRENT_EDIT_QUIZ_ID']
    );
        
    $isEnabled = $dbLogic->select("IS_ENABLED", "QUIZ", $checkEnable, true);
    if($isEnabled['IS_ENABLED'] == '1'){
        $_SESSION["IS_QUIZ_ENABLED"] = true;
        
    }else{
        $_SESSION["IS_QUIZ_ENABLED"] = false;
    }
        
    //If ENABLE button is pushed, update row in database
    if (isset($_POST['confirmEnabled'])) {    
        $quizIDPost = filter_input(INPUT_POST, "quizID");
        
        $setColumnsArray = array(
            "IS_ENABLED" => "1"
        );
        $whereValuesArray = array(
            "QUIZ_ID" => $quizIDPost
        );
        $dbLogic->updateSetWhere("QUIZ", $setColumnsArray, $whereValuesArray);
        $confirmActive = "Quiz is now ENABLED, and CAN be attempted by users.";
        //Set flag variable that is checked before commiting edits in other pages
        $_SESSION["IS_QUIZ_ENABLED"] = true;
        
    //If DISABLE button is pressed, update row in database 
    }else if(isset($_POST['confirmDisabled'])){
        
        $quizIDPost = filter_input(INPUT_POST, "quizID");

        $setColumnsArray = array(
            "IS_ENABLED" => "0"
        );
        $whereValuesArray = array(
            "QUIZ_ID" => $quizIDPost
        );
        $dbLogic->updateSetWhere("QUIZ", $setColumnsArray, $whereValuesArray);
        $confirmActive = "Quiz is now DISABLED, and CANNOT be attempted by users.";
        //Set flag variable that is checked before commiting edits in other pages
        $_SESSION["IS_QUIZ_ENABLED"] = false;
    }
        
    //Page is being loaded from edit-quiz-list with quizid selected    
      
    header('Location: ' . CONFIG_ROOT_URL . '/edit-quiz.php?quiz=' . $quizIDGet = quizLogic::returnSharedQuizID($quizIDPost));
    stop();
    
    
//If coming from home page, display quiz list for user to select
}else if(is_null($quizIDGet)){

    $uid = $_SESSION["username"];
    //where coloumns

    $whereValuesArray = array(
        "user_USERNAME" => "$uid"
        );
    $whereColumnsArray = array(
        "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
        );
    
        $quizEditId = $dbLogic->selectWithColumnsGroupBy("QUIZ_NAME, IS_ENABLED, MAX(QUIZ_ID) as QUIZ_ID , MAX(VERSION) as VERSION", "QUIZ, EDITOR", 
        $whereValuesArray, $whereColumnsArray, 'SHARED_QUIZ_ID', false);
      
    include('edit-quiz-list-view.php');
    
    
} else {
    //html
include("edit-quiz-view.php");
    
}

//Once user selects a quiz, the form is posted back to this file and info is gathered
/*
else{ //Load recent data from create-quiz.php creation

//Get all values regarding quiz from database, and populate form data with current values
    
    $column = "*";
    
    $dataArray = array(
        "QUIZ_ID" => $_SESSION['CURRENT_CREATE_QUIZ_ID']
    );
    
    $quizInfo = $dbLogic->select('*', 'quiz', $dataArray, true);
    
//html
include("edit-quiz-view.php");

$_SESSION['SET_QUIZ_ID'] = "";
}
*/
//Now perform validation on user input - Insert new entry using old Version no. and Shared Quiz ID. 