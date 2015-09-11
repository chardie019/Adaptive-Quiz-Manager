<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
// end of php file inclusion

$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    //stuff
    die("sorry this isn't implemented yet");
    
} else {
    $answerPost = filter_input (INPUT_GET, "answer");
    $questionPost = filter_input (INPUT_GET, "question");
    $type = "";
    if (isset($answerPost)){
        $type = "answer";
        $id = $answerPost;
    } else {
        $type = "question";
        $id = $questionPost;
    }

    $result = quizLogic::returnQuestionOrAnswerData($id , $type);
    $quizData = quizHelper::prepare_tree($quizIDGet, $dbLogic);
}

//html
if ($type == "answer"){
    //initalies strings;
    if (!isset($answerContentError)){$answerContentError = "";}
    if (!isset($feedbackContentError)){$feedbackContentError = "";}
    if (!isset($isCorrectError)){$isCorrectError = "";}

    if (!isset($answerContent)){$answerContent = $result['ANSWER'];}
    if (!isset($feedbackContent)){$feedbackContent = $result['FEEDBACK'];}
    if (!isset($isCorrect)){$isCorrect = (string)$result['IS_CORRECT'];}
    include("inspect-answer-view.php");
} else {
    //initalies strings;
if (!isset($questionTitleError)){$questionTitleError = "";}
if (!isset($questionContentError)){$questionContentError = "";}
if (!isset($questionImageError)){$questionImageError = "";}
if (!isset($questionAltError)){$questionAltError = "";}
if (!isset($questionImageError)){$questionImageError = "";}

if (!isset($questionTitle)){$questionTitle = "";}
if (!isset($questionContent)){$questionContent = ""; }
if (!isset($questionAlt)){$questionAlt = "";}
    include("inspect-question-view.php");
}