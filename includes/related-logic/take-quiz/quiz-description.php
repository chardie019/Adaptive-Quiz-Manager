
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

// include php files here 
require_once("includes/config.php");


//retrieves QUIZ_ID from quiz-list

//Create a table of quiz information
// $_POST filter first

  $quizID = filter_input(INPUT_POST,'quizid', FILTER_SANITIZE_STRING);  
  // For insert
  $_SESSION['quiz_QUIZ_ID'] = $quizID;
  //For view in quiz-description-view.php
  $_SESSION['QUIZ_CURRENT_QUIZ_ID'] = $quizID;
  
  
    $quizAttempts = false;
    $attemptsReached = false; 
    $isEnabled = true;
    $dbLogic = new DB();
    
    //Get all quiz information
    $data = array(
        "QUIZ_ID" => "$quizID"
    );
    
    $columns = "*";

    $quizData = $dbLogic->select($columns, "quiz", $data, true);
    extract($quizData);
    
    
    if($quizData['NO_OF_ATTEMPTS'] == '0' || $quizData['NO_OF_ATTEMPTS'] == null){        
        $quizAttempts = true;    
    }

    //Only run second query if there is a limit on attempts.
    
    if($quizAttempts == false){
    
        //Select all QUIZ_ID attempts from result for user, store in array, 
        //then count identical elements and compare that against number of permitted attempts.

        $dataArray = array(
            "user_USERNAME"=>$_SESSION['username'],
            "quiz_QUIZ_ID"=>"$quizID"
        );

        ($attemptID = $dbLogic->select("quiz_QUIZ_ID", "result", $dataArray, false));

        //Compare NO_OF_ATTEMPTS with result table, count identical quiz id attemtps

        $attemptsDone = count($attemptID);
        
        if($attemptsDone>=$quizData['NO_OF_ATTEMPTS']){
            
            $attemptsReached = true;          
        }
    }
    
    if($quizData['IS_ENABLED'] == '0'){
        $isEnabled = false;
        echo "QUIZ IS DISABLED FALSE";
    }
    
  
    //Set null value to appropriate terminology for the view file.
    if($quizData['DESCRIPTION'] == null){
        $quiz_description = "No description provided";
    }
    else{
        $quiz_description = $quizData['DESCRIPTION'];
    }
    
    if($quizAttempts != false){
        $no_of_attempts = "Unlimited";
    }
    else{
        $no_of_attempts = $attemptsDone." / ".$quizData['NO_OF_ATTEMPTS'];
    }
    
    if($quizData['IS_SAVABLE'] == null || $quizData['IS_SAVABLE'] == '0'){
        $is_savable =  "No";
    }
    else{
        $is_savable = $quizData['IS_SAVABLE'];
    }
   
    if($quizData['TIME_LIMIT'] == '00:00:00' || $quizData['TIME_LIMIT'] == null){
        $time_limit = "Unlimited";
    }
    else{
        $time_limit = $quizData['TIME_LIMIT'];
    }
    if (empty($quizData["IMAGE"])){
        $quizData["IMAGE"] = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="; //transparent gif
    } else {
        $quizData["IMAGE"] = STYLES_QUIZ_IMAGES_LOCATION . "/" . $quizData["IMAGE"];
    }
    //html view
    include ('quiz-description-view.php');

