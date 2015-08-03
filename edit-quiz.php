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

//Set page error messages blank upon initial loading
$quizNameError = "";
$quizDescriptionError = "";
$isPublicError = "";
$noAttemptsError = "";
$isTimeError = "";
$isSaveError = "";
$timeLimitError = "";
$invalidDateError1 = "";
$invalidDateError2 = "";
$dayStartError = "";
$monthStartError = "";
$yearStartError = "";
$dayEndError = "";
$monthEndError = "";
$yearEndError = "";
$imageUploadError = "";
$quizImageTextError = "";


if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    $quizID = filter_input(INPUT_POST, "quizid");
    
    $_SESSION['CURRENT_CREATE_QUIZ_ID'] = "$quizID";
    
    $column = "*";
    
    $dataArray = array(
        "QUIZ_ID" => $_SESSION['CURRENT_CREATE_QUIZ_ID']
    );
    
    $quizInfo = $dbLogic->select('*', 'quiz', $dataArray, true);
    
    //html
    include("edit-quiz-view.php");
    
}
//If coming from home page, display quiz list for user to select
else if($_SESSION['SET_QUIZ_ID'] == ""){
    
    $uid = $_SESSION["username"];
    //where coloumns

    $dataArray = array(
        "user_USERNAME" => "$uid"
        );
    $columnWhere = array(
        "quiz_QUIZ_ID" => "QUIZ_ID"
        );
    
    ($quizEditId = $dbLogic->selectDistinct("QUIZ_NAME, QUIZ_ID", "quiz, editor", $dataArray, $columnWhere, false));
      
    include('edit-quiz-list-view.php');
    
    
}

//Once user selects a quiz, the form is posted back to this file and info is gathered

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

//Now perform validation on user input - Insert new entry using old Version no. and Shared Quiz ID. 