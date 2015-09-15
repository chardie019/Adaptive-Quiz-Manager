<?php

// include php files here 
require_once("includes/config.php");
// end of php file inclusion


$dbLogic = new DB();
$quizArray = array();
$uid = $_SESSION["username"];
    //where coloumns

        $whereValuesArrayTaker = array(
            "user_USERNAME" => "$uid",
            "IS_PUBLIC" => '1'
            );
        $whereColumnsArrayTaker = array(
            "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
            );
        

        $resultID = $dbLogic->selectDistinctWithColumnsOrAndGroupBy("SHARED_QUIZ_ID, MAX(QUIZ_ID) AS QUIZ_ID", "quiz, taker", 
            $whereValuesArrayTaker, $whereColumnsArrayTaker, 'SHARED_QUIZ_ID', false);

        foreach($resultID as $columnTaker){

            $whereValues2 = array(
               "SHARED_QUIZ_ID" => $columnTaker['SHARED_QUIZ_ID'] 
            );

            $whereColumn2 = array(
                "QUIZ_ID" => $columnTaker['QUIZ_ID']   
            );

            $quizNameArray = $dbLogic->selectWithColumnsGroupBy("QUIZ_NAME, DESCRIPTION, SHARED_QUIZ_ID, QUIZ_ID, MAX(VERSION) AS VERSION",
                    'quiz', $whereValues2, $whereColumn2, 'SHARED_QUIZ_ID', false);

            //Merge the array because $quizNameArray will be overwritten each iteration of foreach loop
            //Store the values inside nameArray which will be unaffected by foreach loop as it merges the values onto itself

            $quizArray = array_merge($quizArray, $quizNameArray);         
    }
    
//html
    
include("quiz-list-view.php");

