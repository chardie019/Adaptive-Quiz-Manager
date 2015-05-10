<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

function prepareViewPage() {
    //declare variables we need access to
    global $questionData;
    global $dbLogic;
    
    if (empty($questionData["IMAGE"])){
        $questionData["IMAGE"] = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="; //transparent gif
    } else {
        $questionData["IMAGE"] = STYLES_QUIZ_IMAGES_LOCATION . "/" . $questionData["IMAGE"];
    }
            
    $data = array(
        "Question_QUESTION_ID" => $questionData["QUESTION_ID"]
    );
    //find this questions's answers
    return $dbLogic->select("*", "answer", $data, false);
}


if ($_SERVER['REQUEST_METHOD'] === "POST") { //next question
    $answerPosted = filter_input(INPUT_POST, "answer");
    $dbLogic = new DB();
    //check answer is legit and belongs the same quiz
    $data = array(
        "LINK"                  => $answerPosted,
        "QUIZ_ID"           => $_SESSION["QUIZ_CURRENT_QUESTION"]
    );
    $whereColoumn = array(
        "Question_QUESTION_ID"  => "QUESTION_ID",
        "Quiz_QUIZ_ID"          => "QUIZ_ID"
    );

    $answerID = $dbLogic->selectWithColumns("ANSWER_ID", "answer, question, quiz", $data, $whereColoumn);
    
    //find the next question
    if (count($answerID) > 0){  //success - input was legit - answer is valid
        //get the next question
        $data = array(
            "QUESTION_ID" => $answerPosted
        );
        //find next question
        $questionData = $dbLogic->select("*", "question", $data);
        $answerData = prepareViewPage();
        if (!empty($answerData) > 0){ //are there answers or is this the end of the quiz?
            include("take-quiz-view.php");
        } else {
            include("quiz-complete.php");
        }
    } else {
        //display the not found page (for hackers)
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        include("404.php");
        
    }
} else {                                    //start quiz
    //stub for getting quiz id
    //if ID is passed- load take quiz, otherwise load quiz-list
    $quizIdRequested = filter_input(INPUT_GET, "quiz");

    //html
    if (empty($quizIdRequested)) {
        include("quiz-list.php");
    } else {
        $dbLogic = new DB();

        //find the quiz
        $data = array(
                    "QUIZ_ID" => $quizIdRequested
                );

        $quizIdDb = $dbLogic->select("*", "quiz", $data);
        if (count($quizIdDb) > 0){  //success - it exists
            $_SESSION["QUIZ_CURRENT_QUIZ_ID"] = $quizIdDb["QUIZ_ID"];
            $data = array(
                        "Quiz_QUIZ_ID" => $_SESSION["QUIZ_CURRENT_QUIZ_ID"]
                    );
            //find first question - we assume first in table is the first
            $questionData = $dbLogic->select("*", "question", $data);
            $_SESSION["QUIZ_CURRENT_QUESTION"] = $questionData["QUESTION_ID"];
            $answerData = prepareViewPage(); 
            //html
            include("take-quiz-view.php");
        }else {                     //fail, no result
            //display the not found page
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            include("404.php");
        }
    } 
}





