<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$quizIDGet = filter_input(INPUT_GET, "quiz");
$selectionError = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    $addQuestionButtonPost = filter_input(INPUT_POST, "addQuestion");
    $answerPost = filter_input (INPUT_POST, "answer");
    $removeQuestionButtonPost = filter_input (INPUT_POST, "removeQuestion");
    $questionPost = filter_input (INPUT_POST, "question");
    $addAnswerButtonPost = filter_input (INPUT_POST, "addAnswer");
    $removeAnswerButtonPost = filter_input (INPUT_POST, "removeAnswer");
    $inspectButtonPost = filter_input (INPUT_POST, "inspect");
    
    if (isset($addQuestionButtonPost)){
        if (isset($answerPost)) {
            include("add-question.php");
            exit;
        } else {
            $displayMessage = "initalQuestion";
        }
    } else if (isset($removeQuestionButtonPost) && isset($questionPost)) {
            include('remove-question.php');
            exit;
    } else if (isset($addAnswerButtonPost) && isset($questionPost)){
            include('add-answer.php');
            exit;
    } else if (isset($removeAnswerButtonPost) && isset($answerPost)) {
            include('remove-answer.php');
            exit;
    } else if (isset($removeQuestionButtonPost) || isset($addAnswerButtonPost)){
            $selectionError="Please choose a question to edit before continuing e.g to delete or add answers to.";
    }else if (isset($addQuestionButtonPost) || isset($removeAnswerButtonPost)){
            $selectionError="Please choose a answer to edit before continuing e.g to delete or add questions to.";
    } else if (isset($inspectButtonPost) && (isset($answerPost)) || isset($questionPost)) {
            include('inspect.php');
            exit;
    } else {
        //no button pressed, reload page
        $selectionError="There was an error with your selection.";
    }
}

if (!isset($displayMessage)){
    $displayMessage = "0"; //no message if not set
}

//if if there are any questions
$dbLogic = new DB();

        //Create array for Outer join
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
        $quizData = ($dbLogic->selectFullOuterJoinOrder("*", "question_answer", $where, "question", $jointable, "answer", $jointable2, "depth", false));
  
//$dbLogic = new DB();

//"select * from question_answer order by depth;
//$results = $dbLogic->selectAllOrder("question_answer", "depth");
$arrs = array_values($quizData); //aossoiative to simple array of values

//http://stackoverflow.com/a/15307555\

//html
include("question-view.php");