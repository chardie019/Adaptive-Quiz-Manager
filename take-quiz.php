<?php

/* 
 * TO-DO
 * track which question the user is up up
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
        "question_QUESTION_ID" => $questionData["QUESTION_ID"],
        "quiz_QUIZ_ID" => $_SESSION["QUIZ_CURRENT_QUIZ_ID"],
    );
    $whereColumn = array(
        "question_QUESTION_ID"  => "QUESTION_ID",
        "quiz_QUIZ_ID"          => "QUIZ_ID"
    );
        

    //find this question's answers
    return $dbLogic->selectWithColumns("answer.*", "answer, question, quiz", $data, $whereColumn, False);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") { //next question
//Gets hidden inputs from forms and checks their values to detemine what to load into take-quiz.
    $quizConfirmPosted = filter_input(INPUT_POST, "confirmQuiz");
    $quizConfirmIdPosted = filter_input(INPUT_POST, "confirmQuizId");
    $quizNotConfirmPosted = filter_input(INPUT_POST, "notConfirmQuiz");
    $quizSelected = filter_input(INPUT_POST, "selectQuiz");
    

    if ($quizSelected != ""){
        include('quiz-description.php');       
        exit();//Loads quiz-description-view into take-quiz, keeps same URL
    }           
    if ($quizConfirmPosted != "") {
        $_SESSION["QUIZ_CONFIRMED"] = $quizConfirmIdPosted;
        header('Location: '. CONFIG_ROOT_URL . '/take-quiz/'.$_SESSION["QUIZ_CONFIRMED"]);   
        stop(); //refresh the page an rerun script
    }
    if ($quizNotConfirmPosted != "") {
        $_SESSION["QUIZ_CONFIRMED"] = ""; //not confirmed anymore
        echo ("notconfirm");
        header('Location: ' . CONFIG_ROOT_URL . '/take-quiz');   
        stop(); //refresh the page an rerun script (with no quiz this time)
    }

    //otherwise continue
    $answerPosted = filter_input(INPUT_POST, "answer");
    $dbLogic = new DB();
    //check answer is legit and belongs the same quiz
    $data = array(
        "LINK"              => $answerPosted,
        "QUIZ_ID"           => $_SESSION["QUIZ_CURRENT_QUIZ_ID"]
    );
    $whereColoumn = array(
        "question_QUESTION_ID"  => "QUESTION_ID",
        "quiz_QUIZ_ID"          => "QUIZ_ID"
    );

    $answerID = $dbLogic->selectWithColumns("ANSWER_ID", "answer, question, quiz", $data, $whereColoumn);

    //success - input was legit - answer is valid
    if (count($answerID) > 0){  
        //get the next question
        $data = array(
            "QUESTION_ID" => $answerPosted
        );
        //find next question
        $questionData = $dbLogic->select("*", "question", $data);
        $answerData = prepareViewPage();
        
        include("record-answer.php");
        if (!empty($answerData) > 0){ //are there answers or is this the end of the quiz?
            include("take-quiz-view.php");
        } else {
            //Sets result session variable back to NULL for more quiz attempts
            $_SESSION["RESULT_ID"] = NULL;
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
    } 
    else {
        $dbLogic = new DB();

        //find the quiz
        $data = array(
                    "QUIZ_ID" => $quizIdRequested
                );
        $quizData = $dbLogic->select("*", "quiz", $data);
        if (count($quizData) > 0){  //success - it exists
            $_SESSION["QUIZ_CURRENT_QUIZ_ID"] = $quizData["QUIZ_ID"];
            if ((!isset($_SESSION["QUIZ_CONFIRMED"])) || ($_SESSION["QUIZ_CONFIRMED"] != $_SESSION["QUIZ_CURRENT_QUIZ_ID"])) {    //same quiz and is confirmed
                include ("quiz-description.php");
            } else {                                                                   // straight to the actual quiz
                //find question now
            $data = array(
                        "quiz_QUIZ_ID" => $_SESSION["QUIZ_CURRENT_QUIZ_ID"]
                    );
            //find first question - we assume first in table is the first
            $questionData = $dbLogic->select("*", "question", $data);
            $_SESSION["QUIZ_CURRENT_QUESTION"] = $questionData["QUESTION_ID"];
            $answerData = prepareViewPage(); 
            //html
            include("take-quiz-view.php");
            }
        }else {                     //fail, no result
            //display the not found page
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            include("404.php");
        }
    } 
}





