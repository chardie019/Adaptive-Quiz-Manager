<?php
    
    
    // include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
include ("check-quiz-id-edit-quiz.php");

// end of php file inclusion

$quizID = filter_input(INPUT_GET, "quiz");

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
    


    $column = "*";
    
    $dataArray = array(
        "QUIZ_ID" => $quizID
    );
    
    $quizInfo = $dbLogic->select('*', 'quiz', $dataArray, true);

//html
include("details-view.php");