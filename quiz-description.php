<?php

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

//retrieves QUIZ_ID from quiz-list



//Create a table of quiz information
//Don't access $_POST superglobal directly, filter first

  $quizID = filter_input(INPUT_POST,'quizid', FILTER_SANITIZE_STRING);  
  
    $dbLogic = new DB();
    
    $data = array(
        "QUIZ_ID" => "$quizID"
    );
    
    $columns = "*";

    ($answerID = $dbLogic->select($columns, "quiz", $data, true));
    extract($answerID);
    
    //html
    include ('quiz-description-view.php');
