<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
// end of php file inclusion

$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();

$questionIdPost = filter_input(INPUT_POST, "question");
$answerIdPost = filter_input(INPUT_POST, "answer");

//html
include("inspect-view.php");