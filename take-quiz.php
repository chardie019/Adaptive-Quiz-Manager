<?php

/* 
 * TO-DO
 * track which question the user is up up
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion
// 
//Sets Feedback value on take-quiz-view.php to empty for the first question.
$answerFeedback = ' ';

function prepareViewPage() {
    //declare variables we need access to
    global $questionData;
    global $dbLogic;
    
    if (empty($questionData["IMAGE"])){
        $questionData["IMAGE"] = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="; //transparent gif
    } else {
        $questionData["IMAGE"] = returnQuizImagepath($_SESSION["QUIZ_CURRENT_QUIZ_ID"], $questionData["IMAGE"]);
    }
  
    $data = array(
        "PARENT_ID" => $questionData["CONNECTION_ID"]
    );
    $whereColumn = array(
        "answer_ANSWER_ID"          => "ANSWER_ID"
    );
    
    //Set the current QUESTION_ID to a session varibale for use when storing the answer.    
    $_SESSION["QUIZ_CURRENT_QUESTION"] = $questionData["QUESTION_ID"];
    //find this question's answers
    return $dbLogic->selectWithColumns("answer.*", "answer, question_answer", $data, $whereColumn, False);
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
        header('Location: '. CONFIG_ROOT_URL . '/take-quiz.php?quiz='.$_SESSION["QUIZ_CONFIRMED"]);   
        stop(); //refresh the page an rerun script
    }
    if ($quizNotConfirmPosted != "") {
        $_SESSION["QUIZ_CONFIRMED"] = ""; //not confirmed anymore
        echo ("notconfirm");
        header('Location: ' . CONFIG_ROOT_URL . '/take-quiz');   
        stop(); //refresh the page an rerun script (with no quiz this time)
    }

        
    //otherwise continue, retrieve user answer from form
    $answerPosted = filter_input(INPUT_POST, "answer");
    $dbLogic = new DB();

    
    //check answer is legit and belongs the same quiz
    $questionData = quizLogic::nextQuestionDataFeedbackConnectionId($answerPosted, $_SESSION["QUIZ_CURRENT_QUESTION"], $_SESSION["QUIZ_CURRENT_QUIZ_ID"]);
    
    /*
    $data = array(
        "ANSWER_ID"              => $answerPosted,
        "QUIZ_ID"           => $_SESSION["QUIZ_CURRENT_QUIZ_ID"]
    );
    $whereColoumn = array(
        "quiz_QUIZ_ID"                      => "QUIZ_ID",
        "question_answer.answer_ANSWER_ID"      => "ANSWER_ID"
    );

    $answerID = $dbLogic->selectWithColumns("answer.*, CONNECTION_ID", "answer, quiz, question_answer", $data, $whereColoumn);
    */
    
    if (empty($questionData)){
        //display the not found page (for hackers)  
        include("404.php");
        exit; //nothing to do past here
    }

    //Set the feedback to appear on the next question page for the one previously answered
    if(!empty($questionData['FEEDBACK'])){
        $answerFeedback = $questionData['FEEDBACK'];
    }
    
    //Call record-answer before a new question ID is set
    include("record-answer.php");    
    /*
    //get the next question
    $where = array(
        "PARENT_ID" => $answerID['CONNECTION_ID']
    );      
        $whereColumns = array(
            "question_answer.question_QUESTION_ID" => "QUESTION_ID"
        );
        //find first question - we assume first in table is the first
        //find the first question
        $questionData = $dbLogic->selectWithColumnsOrder("question.*, CONNECTION_ID", "question_answer, question", $where, $whereColumns, "DEPTH");
    */
    //get answer stuff
$answerData = prepareViewPage();

    if (!empty($answerData) > 0){ //are there answers or is this the end of the quiz?      

        include("take-quiz-view.php");
    } else { 
    //Moved $_SESSION["RESULT_ID"] = NULL; to quiz-complete.php needed for result display
        include("quiz-complete.php"); 
    }

} else { //GET - start quiz

    //stub for getting quiz id
    //if ID is passed- load take quiz, otherwise load quiz-list
    $quizIdRequested = filter_input(INPUT_GET, "quiz");

    //html
    //if no quiz submitted or empty url changed (trailing slash "/" with nothing on the end)
    if (empty($quizIdRequested) || $quizIdRequested  == ".php") {
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
            $where = array(
                "quiz_QUIZ_ID" => $_SESSION["QUIZ_CURRENT_QUIZ_ID"]
            );
            $whereColumns = array(
                "question_answer.question_QUESTION_ID" => "question.QUESTION_ID"
            );
            //find first question - we assume first in table is the first
            //find the first question
            $questionData = $dbLogic->selectWithColumnsOrder("question.*, CONNECTION_ID", "question_answer, question", $where, $whereColumns, "DEPTH");
            
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





