<?php

// include php files here 
require_once("includes/config.php");
// end of php file inclusion


$dbLogic = new DB();
$quizArray = array();
$uid = $_SESSION["username"];

//Get current date to check for open quizzes
$dateCheck = date('Y-m-d H:i:s');

    //where coloumns
        
        $joinWhere = array(
            "SHARED_QUIZ_ID" => "shared_SHARED_QUIZ_ID"
        );

        $whereValuesArrayTaker = array(
            "IS_PUBLIC" => '1',
            "user_USERNAME" => $uid
            );

        $resultID = $dbLogic->selectLeftJoinOrGroupBy("SHARED_QUIZ_ID, MAX(QUIZ_ID) AS QUIZ_ID", "quiz", $whereValuesArrayTaker,
                "taker", $joinWhere, 'SHARED_QUIZ_ID', false);

        
        foreach($resultID as $columnTaker){
            
            $whereValues = array(
               "SHARED_QUIZ_ID" => $columnTaker['SHARED_QUIZ_ID'],
                "QUIZ_ID" => $columnTaker['QUIZ_ID'],              
            );
            
            $whereDateAfter = array(
                "DATE_OPEN" => $dateCheck,
            );
            
            $whereDateBefore = array(               
                "DATE_CLOSED" => $dateCheck
            );
            

            $quizNameArray = $dbLogic->selectWithDateCheckGroupBy("QUIZ_NAME, DESCRIPTION, SHARED_QUIZ_ID, QUIZ_ID, MAX(VERSION) AS VERSION",
                    'quiz', $whereValues, $whereDateAfter, $whereDateBefore, 'SHARED_QUIZ_ID', false);

            //Merge the array because $quizNameArray will be overwritten each iteration of foreach loop
            //Store the values inside nameArray which will be unaffected by foreach loop as it merges the values onto itself

            $quizArray = array_merge($quizArray, $quizNameArray); 
            
    }
//html
    
include("quiz-list-view.php");
