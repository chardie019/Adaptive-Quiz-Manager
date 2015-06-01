<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

$_SESSION["QUIZ_CONFIRMED"] = ""; //not confirmed anymore
//
$dbLogic = new DB();

//Get the data from result_answer table
    $data = array(
        "result_RESULT_ID" => $_SESSION["RESULT_ID"]
        
        );
    
    $quizResults = $dbLogic->select("*", "result_answer", $data, false); 
    

//Get the data from Answer table using ANSWER value collected from result_answer table
    
    /* Doesn't work as quizResults["ANSWER"] contains more than one ID 

    $data2 = array(
        "ANSWER_ID" => $quizResults["ANSWER"]
            
    );

    $quizAnswers = $dblogic->select("ANSWER, FEEDBACK", "answer", $data2, false);
    extract($quizAnswers);
    
    */
    
    //Moved resetSession to final page so value can be used to retrieve results
    $_SESSION["RESULT_ID"] = NULL;
//html
include("quiz-complete-view.php");