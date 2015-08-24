<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$quizIDGet = filter_input(INPUT_GET, "quiz");
$selectionError = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    if (isset($_POST['addQuestion']) && isset($_POST['answer'])) {
            include("add-question.php");
            exit;
    } else if (isset($_POST['removeQuestion']) && isset($_POST['question'])) {
            include('remove-question.php');
            exit;
    }  else if (isset($_POST['addAnswer']) && isset($_POST['question'])){
            include('add-answer.php');
            exit;
    } else if (isset($_POST['removeAnswer']) && isset($_POST['answer'])) {
            include('remove-answer.php');
            exit;
    } else if (isset($_POST['removeQuestion']) || isset($_POST['addAnswer'])){
            $selectionError="Please choose a question to edit before continuing e.g to delete or add answers to.";
    }else if (isset($_POST['addQuestion']) || isset($_POST['removeAnswer'])){
            $selectionError="Please choose a answer to edit before continuing e.g to delete or add questions to.";
    } else if (isset($_POST['inspect']) && (isset($_POST['answer']) || isset($_POST['question']))) {
            include('inspect.php');
            exit;
    } else {
        //no button pressed, reload page
        $selectionError="There was an error with your selection.";
    }
}

//if if there are any questions
$dbLogic = new DB();

        //Create array for insert->quiz
        $where = array(
            "quiz_QUIZ_ID" => "$quizIDGet" 
        );
        $jointable = array(
            "QUESTION_ID" => "question_QUESTION_ID"
        );
        $jointable2 = array(
            "ANSWER_ID" => "answer_ANSWER_ID"
        );

        //Insert quiz into database
        $quizData = ($dbLogic->selectFullOuterJoin("*", "question_answer", $where, "question", $jointable, "answer", $jointable2 , false));
        

//html
include("question-view.php");