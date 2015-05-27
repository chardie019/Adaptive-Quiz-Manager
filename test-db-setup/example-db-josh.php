<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion


    $dbLogic = new DB();
    //where coloumns
    $data = array(
        "LINK"                  => "1",
        "QUIZ_ID"           => "2"
    );
    //name coloumns here
    $whereColoumn = array(
        "Question_QUESTION_ID"  => "QUESTION_ID",
        "Quiz_QUIZ_ID"          => "QUIZ_ID"
    );
    
    //this is placed after the OR
    $dataOr = array(
        "LINK2"                  => "3",
        "QUIZ_ID2"           => "4"
    );
    //name coloumns here
    $whereColoumnOr = array(
        "Question_QUESTION_ID2"  => "QUESTION_ID",
        "Quiz_QUIZ_ID2"          => "QUIZ_ID"
    );

    $answerID = $dbLogic->selectWithColumnsOr("ANSWER_ID", "answer, question, quiz", $data, $whereColoumn, $dataOr, $whereColoumnOr);
    
    //this creates:
        
    //SELECT ANSWER_ID FROM answer, question, quiz 
    //WHERE LINK = '1' AND QUIZ_ID = '2' 
    //AND Question_QUESTION_ID = QUESTION_ID 
    //AND Quiz_QUIZ_ID = QUIZ_ID 
    //OR (LINK2 = '3' 
    //AND QUIZ_ID2 = '4' 
    //AND Question_QUESTION_ID2 = QUESTION_ID 
    //AND Quiz_QUIZ_ID2 = QUIZ_ID)
    
    
    //other usefule stuff
    
    //se what is returned
    echo ("<pre>");
echo (var_dump($answerID));
echo ("</pre>");

//print one item - where ANSWER_ID is the coloumn name (defult- single row)
echo ($answerID["ANSWER_ID"]);

//if multi-row do smae command as above but add a "false on the end" (applicable to all select commnds)
//e.g.
$answerID = $dbLogic->selectWithColumnsOr("ANSWER_ID", "answer, question, quiz", $data, $whereColoumn, $dataOr, $whereColoumnOr, false);

//to print these (loops as many as there are results):
foreach ($answerID as $answerRow) {
    echo ($answerRow["myColoumn"]);
}

//another way for seeing what was actually queried, see mysql log file (2nd answer):
//http://www.facebook.com/l.php?u=http%3A%2F%2Fstackoverflow.com%2Fquestions%2F2411182%2Fhow-to-debug-pdo-database-queries&h=yAQFrcn8Q



