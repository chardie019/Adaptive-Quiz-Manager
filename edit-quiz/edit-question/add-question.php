<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion
 $quizId = $quizIDGet;

$prevAnswerId = filter_input(INPUT_GET, "answer");

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $questionTitle = filter_input(INPUT_POST, "question-title");
    $questionContent = filter_input(INPUT_POST, "question-content");
    //questionImageUpload
    $questionAlt = filter_input(INPUT_POST, "question-alt");
    
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
    if ($error == 0){
        if (is_uploaded_file($_FILES["questionImageUpload"]["tmp_name"])) { //image is optional
            // If image passed all criteria, attempt to upload
            $targetFileName = basename($_FILES["questionImageUpload"]["name"]);
            $imageResult = $quizHelper::handleImageUploadValidation($_FILES, $targetFileName, $quiz, $questionAlt);
            if($imageResult['RESULT'] == false){
                $error = 1;
                $imageUploadError = $imageResult['imageUploadError'];
                $questionAltError = $imageResult['imageAltError'];
            }
        }
        
        if ($error == 0) {//all good
            quizLogic::insertQuestion($quizId, $prevAnswerId, $questionTitle, $questionContent, $targetFileName, $questionAlt);
            //show soe the new question added
            header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizIDGet)."&feedback=question-added");
            exit();
        }
    }

}

$dbLogic = new DB();
$parentId = quizLogic::returnParentId($dbLogic, $prevAnswerId, "answer");
$returnHtml = quizHelper::prepareTree($dbLogic, $quizId, $parentId, "none");

//initalies strings;
if (!isset($questionTitleError)){$questionTitleError = "";}
if (!isset($questionContentError)){$questionContentError = "";}
if (!isset($questionImageError)){$questionImageError = "";}
if (!isset($questionAltError)){$questionAltError = "";}
if (!isset($questionImageError)){$questionImageError = "";}

if (!isset($questionTitle)){$questionTitle = "";}
if (!isset($questionContent)){$questionContent = ""; }
if (!isset($questionAlt)){$questionAlt = "";}






//html
include("add-question-view.php");