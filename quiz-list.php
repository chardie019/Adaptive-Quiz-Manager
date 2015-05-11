<?php

// include php files here 
require_once("includes/config.php");
// end of php file inclusion


$dbLogic = new DB();
$uid = 'jsmith04';
    //where coloumns
/*
    $dataArray = array(
        "IS_PUBLIC" => "1"
        );
    $dataArrayOr = array(
        "Quiz_QUIZ_ID" => "QUIZ_ID",
        "user_USERNAME" => "$uid"
    );
    $columnWhere = array();
    $columnWhereOr = array();
    ($answerID = $dbLogic->selecDistinctWithColumnsOr("QUIZ_NAME, QUIZ_ID", "quiz, taker", $dataArray, $columnWhere, $dataArrayOr, $columnWhereOr, false));
    //QUIZ_ID needed as you can put it in the URL maybe?

//to print these (loops as many as there are results):
foreach ($answerID as $answerRow) {
    echo ($answerRow["QUIZ_NAME"]);
}
*/

    $dataArray = array(
        "IS_PUBLIC" => "0",
        "user_USERNAME" => "$uid"
        );
    $columnWhere = array(
        "Quiz_QUIZ_ID" => "QUIZ_ID"
    );
    ($answerID = $dbLogic->selectWithColumns("QUIZ_NAME, QUIZ_ID", "quiz, taker", $dataArray, $columnWhere, false));
    //QUIZ_ID needed as you can put it in the URL maybe?

//to print these (loops as many as there are results):
foreach ($answerID as $answerRow) {
    echo ($answerRow["QUIZ_NAME"]);
}
//html
include("quiz-list-view.php");