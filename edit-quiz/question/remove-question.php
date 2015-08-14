<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$quizIDGet = filter_input(INPUT_GET, "quiz");

//html
include("remove-question-view.php");