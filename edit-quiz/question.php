<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$quizIDGet = filter_input(INPUT_GET, "quiz");
$noQuestionSelectedError = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    if (isset($_POST['addQuestion']) && isset($_POST['question'])) {
            include("add-question.php");
            exit;
    } else if (isset($_POST['removeQuestion']) && isset($_POST['question'])) {
            include('remove-question.php');
            exit;
    } else if (isset($_POST['removeQuestion']) || isset($_POST['addQuestion'])){
            $noQuestionSelectedError="Please choose a question to edit before continuing.";

    } else if (isset($_POST['addAnswer']) && isset($_POST['answer'])){
            include('add-answer.php');
            exit;
    } else if (isset($_POST['removeAnswer']) && isset($_POST['answer'])) {
            include('remove-answer.php');
            exit;
    } else if (isset($_POST['removeAnswer']) || isset($_POST['addAnswer'])){
            $noQuestionSelectedError="Please choose a answer to edit before continuing.";

    } else {
        //no button pressed, reload page
        $noQuestionSelectedError="There was an error with your selection.";
    }
}

//if if there are any questions
$dbLogic = new DB();

        //Create array for insert->quiz
        $where = array(
            "quiz_QUIZ_ID" => "$quizIDGet" 
        );
        $whereAnd = array(
            "QUESTION_ID" => "question_QUESTION_ID" 
        );

        //Insert quiz into database
        $quizData = ($dbLogic->selectDistinct("*", "question, answer", $where, $whereAnd, false));
        

//html
include("question-view.php");