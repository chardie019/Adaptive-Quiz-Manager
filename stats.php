<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
include ("timeConverter.php");
// end of php file inclusion

$dbLogic = new DB();

    if(isset($_POST['selectStatistics']) || isset($_POST['previousVersions'])){
        
            $_SESSION['quizid'] = filter_input(INPUT_POST, "quizid");
            //Replace with '1' or '1' for testing until  take quiz is sorted with newest current version
            $quizidconfirm = $_SESSION['quizid'];
            $shareColumn = "quiz_QUIZ_ID";
            
        if(isset($_POST['previousVersions'])){
            //Get shared quiz id for chosen quiz to prepare results inclusive of older versions
            $whereSharedQuiz = array(
                "QUIZ_ID"=>$quizidconfirm
            );
        
            $sharedID = $dbLogic->select("SHARED_QUIZ_ID", "quiz", $whereSharedQuiz);   
            
            $quizidconfirm = $sharedID['SHARED_QUIZ_ID'];
            $shareColumn = "shared_SHARED_QUIZ_ID";
        } 

        $wherecolumn = array(
          $shareColumn => $quizidconfirm
        );      
        $wherecolumn2 = array(
            "RESULT_ID" => "result_RESULT_ID"           
        );
        
        $graphResults = $dbLogic->selectWithColumns('*', 'result, result_answer', $wherecolumn, $wherecolumn2, false);
        
        if(empty($graphResults)){
	    //Replace this with error message, just loads different viewfor now for final error testing
            include("about-view.php");
            stop();
        }
        //Creates a new array of the RESULT_ID's
        $totalAttempts = array();
        $i= 0;
        foreach ($graphResults as $rowResults){         
            $totalAttempts[$i] = ($rowResults["RESULT_ID"]);
            $i++;
        }
        //Retrieves only the unqiue values of RESULT_ID's
        $uniqueAttempts = array_unique($totalAttempts);
        //Creates new array and fills it with unique values granting new array keys [0], [1] etc instead of old.
        $newArray = array_values($uniqueAttempts);

        //Select QUESTION FROM QUESTION WHERE QUESTION_ID = ''
        $questions = array();
        $j= 0;
        foreach ($graphResults as $rowResults){         
            $questions[$j] = ($rowResults["question_QUESTION_ID"]);
            $j++;
        }
           
        //Retrieves only the unqiue values of RESULT_ID's
        $uniqueQuestions = array_unique($questions);
        //Creates new array and fills it with unique values granting new array keys [0], [1] etc instead of old.
        $newQuestionArray = array_values($uniqueQuestions);
        
        //Place questions of quiz in ascending order
        sort($newQuestionArray);
        
        //Get question text for Graph Title
        //Retrieves Question text using question_ids from result, then populates an array to use in stats-graph-view.php
        $questionText = array();
        $q=0;
        for($y=0; $y<count($newQuestionArray); $y++){
                $whereQuestionDescr = array(
                    "QUESTION_ID" => $newQuestionArray[$y]
                );
                
                $questionDescr = $dbLogic->select("QUESTION", "question", $whereQuestionDescr, false);
                
                foreach ($questionDescr as $rowResults){         
                    $questionText[$q] = ($rowResults["QUESTION"]);
                    $q++;
                }
        }
        $graphData = array();
        for($l=0; $l<count($newQuestionArray); $l++){
            
            $whereQuestion = array(
                "question_QUESTION_ID" => $newQuestionArray[$l]
            );           
            $whereAnswer = array (
                "result_answer.ANSWER" => "answer.ANSWER_ID"
            );

            $answerResults = $dbLogic->selectWithColumnsGroupBy('question_QUESTION_ID, answer.answer, COUNT(*) as CHOSEN', "result_answer, answer", $whereQuestion, $whereAnswer, "answer.answer", false);
            
            foreach($answerResults as $answerNumbers){
  
                $graphData{$l}[$answerNumbers['answer']] = $answerNumbers['CHOSEN'];
            }            
        }

        $countAttempts = count(array_unique($totalAttempts));
        $countQuestions = count(array_unique($questions));
     
        //Retrieve time values for attempts 
        $timearray = array(
            'times' => array()
            );
        
        foreach($graphResults as $timeResults){
            
                $timearray['times'][] = array(
                    'Started' => $timeResults['STARTED_AT'], 'Finished' =>$timeResults['FINISHED_AT']
                        );               
        }
        
        $sessions = array();
        
        foreach ($timearray as $name) {
            if (is_array($name)) {
                foreach ($name as $application) {
                    $sessions[] = strtotime($application['Finished']) - strtotime($application['Started']);
                }
            } else {
                echo "There was an error, Start/Finish dates are not stored correctly.";
            }
        }
        
        $average = array_sum($sessions) / count($sessions);
        
        //Pass time in seconds to timeConverter function to change to a readable format
        $averageTime = secs_to_h($average);
        
        $h=0;
        $min = $sessions[$h];
        $max = $sessions[$h];
        
        for($h=0; $h<count($sessions); $h++){

            if($min > $sessions[$h]){
                $min = $sessions[$h];
            }
            if($max < $sessions[$h]){
                $max = $sessions[$h];
            }
        }
        
        $minTime = secs_to_h($min);
        $maxTime = secs_to_h($max);
        
    include("stats-quiz-graph-view.php");
    }

else{
    //Retrieve the most current versions of quizzes for which the user is an editor
    $uid = $_SESSION["username"];
    //where coloumns

    $whereValuesArray = array(
        "user_USERNAME" => "$uid"
        );
    $whereColumnsArray = array(
        "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
        );
    
    $resultID = $dbLogic->selectWithColumnsGroupBy("QUIZ_NAME, IS_ENABLED, SHARED_QUIZ_ID, MAX(QUIZ_ID) as QUIZ_ID , MAX(VERSION) as VERSION", "quiz, editor", 
        $whereValuesArray, $whereColumnsArray, 'SHARED_QUIZ_ID', false);

    //html
    include("stats-view.php");
}