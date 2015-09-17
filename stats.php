<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

$uid = $_SESSION['username'];

$dbLogic = new DB();

$quizArray = array();


if ($_SERVER['REQUEST_METHOD'] === "POST") { 
    if(isset($_POST['editorStats'])){

        //If user selects editor, then pass 'Editor' session variable to stats-quiz-list page to forward to stats-editor.php
        $_SESSION['statsType'] = 'editor';
        //Retrieve the most current versions of quizzes for which the user is an editor
        $uid = $_SESSION["username"];
        //where coloumns

        $whereValuesArrayEditor = array(
            "user_USERNAME" => "$uid"
            );
        $whereColumnsArrayEditor = array(
            "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID",
            );

        $editorResultId = $dbLogic->selectWithColumnsGroupBy("SHARED_QUIZ_ID, MAX(QUIZ_ID) AS QUIZ_ID", "quiz, editor", 
            $whereValuesArrayEditor, $whereColumnsArrayEditor, 'SHARED_QUIZ_ID', false);

        /*Run another set of queries using the QUIZ_ID and SHARED_QUIZ_ID retrieved above to obtain the name
         *of the most current version of the quiz, as well as the most current version. Include QUIZ_ID and
         *SHARED_QUIZ_ID in returned results so all fields can be accessed from the nameArray array on stats-view.
         *WARNING** Simply running the single query above and including the additional fields does not result in 
         *the correct QUIZ_NAME being linked with the correct QUIZ_ID due to the MAX requirement. Second query is required.
         */

        foreach($editorResultId as $columnEditor){

            $whereValues2 = array(
                "user_USERNAME" => "$uid"
            );

            $whereColumn2 = array(
                "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID",
                "SHARED_QUIZ_ID" => $columnEditor['SHARED_QUIZ_ID'],
                "QUIZ_ID" => $columnEditor['QUIZ_ID']
            );

            $quizNameArray = $dbLogic->selectWithColumnsGroupBy("QUIZ_NAME, DESCRIPTION, SHARED_QUIZ_ID, QUIZ_ID, MAX(VERSION) AS VERSION",
                    'quiz, editor', $whereValues2, $whereColumn2, 'SHARED_QUIZ_ID', false);

            //Merge the array because $quizNameArray will be overwritten each iteration of foreach loop
            //Store the values inside nameArray which will be unaffected by foreach loop as it merges the values onto itself

            $quizArray = array_merge($quizArray, $quizNameArray);          

        }
    }

    else if(isset($_POST['takerStats'])){

        //If user selects taker, then pass 'Taker' session variable to stats-quiz-list page to forward to stats-taker.php
        $_SESSION['statsType'] = 'taker';
        //Retrieve the most current versions of quizzes for which the user is a taker or are public

        
        //where coloumns
        $joinWhere = array(
            "SHARED_QUIZ_ID" => "shared_SHARED_QUIZ_ID"
        );

        $whereValuesArrayTaker = array(
            "IS_PUBLIC" => '1',
            "user_USERNAME" => $uid
            );

        $takerResultId = $dbLogic->selectLeftJoinOrGroupBy("SHARED_QUIZ_ID, MAX(QUIZ_ID) AS QUIZ_ID", "quiz", $whereValuesArrayTaker,
                "taker", $joinWhere, 'SHARED_QUIZ_ID', false);

        foreach($takerResultId as $columnTaker){

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
    }
         //html
        include("stats-quiz-list-view.php");
        
}else{
    
    include ("stats-view.php");
}