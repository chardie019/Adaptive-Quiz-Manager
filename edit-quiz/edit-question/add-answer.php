<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion

$questionIDPost = filter_input(INPUT_GET, "question");

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $answerContent =filter_input(INPUT_POST, "answer-content");
    $feedbackContent = filter_input(INPUT_POST, "feedback-content");
    $isCorrect = filter_input(INPUT_POST, "is-correct");

    //other validation
    
    $error = 0; //no error yet
    
    if($answerContent == " " || $answerContent == "" || $answerContent == NULL){
        $answerContentError = "Error: You must enter the answer's content.";
        $error = 1;
    }
    if($feedbackContent == " " || $feedbackContent == "" || $feedbackContent == NULL){
        $feedbackContentError = "Error: You must enter the feedback for the answer.";
        $error = 1;
    }
    if($isCorrect != "1" && $isCorrect != "0"&& $isCorrect != "2"){
        $isCorrectError = "Error: Please choose whether the answer is correct, incorrect or neutral.";
        $error = 1;
    }
    
    if ($error == 0){
        //all good
        $result = quizLogic::insertAnswer($quizIDGet, $questionIDPost, $answerContent, $feedbackContent, $isCorrect);
        if ($result == true){
            //show soe the new question added
            header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizIDGet)."&feedback=answer");
            exit();
        } else {
            loadErrorPage("There was Problem adding a answer.");
        }
    }
}

$dbLogic = new DB();
$quizData = quizHelper::prepare_tree($quizIDGet, $dbLogic);

//initalies strings;
if (!isset($answerContentError)){$answerContentError = "";}
if (!isset($feedbackContentError)){$feedbackContentError = "";}
if (!isset($isCorrectError)){$isCorrectError = "";}

if (!isset($answerContent)){$answerContent = "";}
if (!isset($feedbackContent)){$feedbackContent = "";}
if (!isset($isCorrect)){$isCorrect = "3";}


//html
include("add-answer-view.php");