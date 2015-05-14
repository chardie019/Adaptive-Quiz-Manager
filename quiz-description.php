
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

//quizData already used so??
//not sure if there is anything to do here



//echo($quizData["IMAGE"])

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

    ($quizData = $dbLogic->select($columns, "quiz", $data, true));
    extract($quizData);
    
    //Set new QUIZ_ID for the session as the id of selected quiz awaiting confirmation
    $_SESSION['QUIZ_CURRENT_QUIZ_ID'] = $quizData['QUIZ_ID'];
    
    //html
    include ('quiz-description-view.php');

