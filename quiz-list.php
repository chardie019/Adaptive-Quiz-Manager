
<?php

// include php files here 
require_once("includes/config.php");
// end of php file inclusion


$dbLogic = new DB();
$uid = 'jsmith04';
    //where coloumns


    $dataArray = array(
        "IS_PUBLIC" => "1",
        "user_USERNAME" => "$uid"
        );
    $columnWhere = array(
        "quiz_QUIZ_ID" => "QUIZ_ID"
    );
    ($answerID = $dbLogic->selectQuiz("QUIZ_NAME, QUIZ_ID", "quiz, taker", $dataArray, $columnWhere, false));
    //QUIZ_ID needed as you can put it in the URL maybe?

//to print these (loops as many as there are results):
//foreach ($answerID as $answerRow) {
//    echo ($answerRow["QUIZ_NAME"]);
//}
//html
    
include("quiz-list-view.php");


/*

// include php files here 
require_once("includes/config.php");
// end of php file inclusion


$dbLogic = new DB();
$uid = ($_SESSION["username"]);
$dataArray = array(
        "IS_PUBLIC" => "1",
        "user_USERNAME" => "$uid"
        );
    $columnWhere = array(
        "quiz_QUIZ_ID" => "QUIZ_ID"
    );
    ($answerID = $dbLogic->selectQuiz("QUIZ_NAME, QUIZ_ID", "quiz, taker", $dataArray, $columnWhere, false));
    //QUIZ_ID needed as you can put it in the URL maybe?

//html
include("quiz-list-view.php");
*/
