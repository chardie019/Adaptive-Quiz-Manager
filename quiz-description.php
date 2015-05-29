
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


//retrieves QUIZ_ID from quiz-list

//Create a table of quiz information
// $_POST filter first

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
    
    //Set null value to appropriate terminology for the view file.
    if($quizData['DESCRIPTION'] == null){
        $quiz_description = "None";
    }
    else{
        $quiz_description = $quizData['DESCRIPTION'];
    }
    
    if($quizData['NO_OF_ATTEMPTS'] == null){
        $no_of_attempts = "Unlimited";
    }
    else{
        $no_of_attempts = $quizData['NO_OF_ATTEMPTS'];
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
        $quizData["IMAGE"] = STYLES_QUIZ_IMAGES_LOCATION . "/" . $questionData["IMAGE"];
    }
    //html view
    include ('quiz-description-view.php');

