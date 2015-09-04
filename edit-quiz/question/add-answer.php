<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
//require_once("../../includes/config.php");
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion

$questionIDPost = filter_input(INPUT_POST, "question");

//html
include("add-answer-view.php");