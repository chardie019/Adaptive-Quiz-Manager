<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
//require_once("../../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$quizIDGet = filter_input(INPUT_GET, "quiz");

$questionIDPost = filter_input(INPUT_POST, "question");

//html
include("add-question-view.php");