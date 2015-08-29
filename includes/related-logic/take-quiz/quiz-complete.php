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
    $whereColumn = array(
        "RESULT_ID" => "result_RESULT_ID",
        "QUESTION_ID" => "result_answer.question_QUESTION_ID",
        "result_answer.ANSWER" => "ANSWER_ID"
    );
    
    $quizResults = $dbLogic->selectWithColumns("QUESTION, answer.ANSWER, STARTED_AT, "
            . "ANSWERED_AT", "result_answer, question, answer, result", $data, 
            $whereColumn, false); 

    
 
    //Moved resetSession to final page so value can be used to retrieve results
    $_SESSION["RESULT_ID"] = NULL;
//html
include("quiz-complete-view.php");