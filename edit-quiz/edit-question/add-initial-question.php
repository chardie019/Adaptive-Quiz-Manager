<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion
$quizId = $quizIDGet;

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $questionTitle = filter_input(INPUT_POST, "question-title");
    $questionContent = filter_input(INPUT_POST, "question-content");
    //questionImageUpload
    $questionAlt = filter_input(INPUT_POST, "question-alt");
    $answerContent =filter_input(INPUT_POST, "answer-content");
    $feedbackContent = filter_input(INPUT_POST, "feedback-content");
    $isCorrect = filter_input(INPUT_POST, "is-correct");
    
    //ToDo
    //other valaiation
    
    $error = 0; //no error yet
    
    if($questionTitle == " " || $questionTitle == "" || $questionTitle == NULL){
        $questionTitleError = "Error: You must enter the question's title.";
        $error = 1;
    }
    if($questionContent == " " || $questionContent == "" || $questionContent == NULL){
        $questionContentError = "Error: You must enter the question's content.";
        $error = 1;
    }
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
        if (is_uploaded_file($_FILES["questionImageUpload"]["tmp_name"])) { //image is optional
            // If image passed all criteria, attempt to upload
            $targetFileName = basename($_FILES["questionImageUpload"]["name"]);
            $imageResult = quizHelper::handleImageUploadValidation($_FILES, $targetFileName, $quizId, $questionAlt);
            if($imageResult['result'] == false){
                $error = 1;
                $imageUploadError = $imageResult['imageUploadError'];
                $questionAltError = $imageResult['imageAltError'];
            }
        } else {
            $targetFileName = NULL;
        }
        if ($error == 0) {//all good
        quizLogic::insertInitalQuestionAnswer($quizIDGet, $questionTitle, $questionContent, $targetFileName, $questionAlt, $answerContent, $feedbackContent, $isCorrect);
        //show soe the new question added
        header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizIDGet)."&feedback=initial-question-added");
        exit();
        }
    }
}

//initalies strings;
if (!isset($questionTitleError)){$questionTitleError = "";}
if (!isset($questionContentError)){$questionContentError = "";}
if (!isset($questionImageError)){$questionImageError = "";}
if (!isset($questionAltError)){$questionAltError = "";}
if (!isset($answerContentError)){$answerContentError = "";}
if (!isset($feedbackContentError)){$feedbackContentError = "";}
if (!isset($questionImageError)){$questionImageError = "";}
if (!isset($isCorrectError)){$isCorrectError = "";}

if (!isset($questionTitle)){$questionTitle = "";}
if (!isset($questionContent)){$questionContent = ""; }
if (!isset($questionAlt)){$questionAlt = "";}
if (!isset($answerContent)){$answerContent = "";}
if (!isset($feedbackContent)){$feedbackContent = "";}
if (!isset($isCorrect)){$isCorrect = "3";}


//html
include("add-initial-question-view.php");