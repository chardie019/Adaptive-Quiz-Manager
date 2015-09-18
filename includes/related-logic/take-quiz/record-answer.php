<?php


// include php files here 
require_once("includes/config.php");
// end of php file inclusion
//declares needed variable
global $dbLogic;

$dbLogic = new DB();

//Creates a new result if one doesn't exist
if (!isset($_SESSION["RESULT_ID"])) {
  
   //Gets result start time.
    $startDate = date('Y-m-d H:i:s');
    
    //Array to be inserted into DB
    $data1 = array(
        "user_USERNAME" => $_SESSION["username"],
        "quiz_QUIZ_ID" => $_SESSION["QUIZ_CURRENT_QUIZ_ID"],
		"shared_SHARED_QUIZ_ID" => $_SESSION["QUIZ_CONFIRMED"],
        "STARTED_AT" => $startDate,
    );
    
    //Inserts data into result table, stores the new result's ID
    $_SESSION["RESULT_ID"] = $dbLogic->insert($data1, "result");

}

//Gets answer datetime.
$answerDate = date('Y-m-d H:i:s');

//Checks if question has been answered before this session,
//if so gets how many times.
$data2 = array(
       "result_RESULT_ID" => $_SESSION["RESULT_ID"],
       "question_QUESTION_ID" => $_SESSION["QUIZ_CURRENT_QUESTION"],
        );

$answeredData = $dbLogic->select("PASS_NO", "result_answer", $data2, false);

$passNo = count($answeredData) + 1;

$data3 = array(
       "result_RESULT_ID" => $_SESSION["RESULT_ID"],
       "question_QUESTION_ID" => $_SESSION["QUIZ_CURRENT_QUESTION"],
       "PASS_NO" => $passNo,
       "ANSWER" => $answerPosted,
       "ANSWERED_AT" => $answerDate,
       );

$dbLogic->insert($data3, "result_answer");