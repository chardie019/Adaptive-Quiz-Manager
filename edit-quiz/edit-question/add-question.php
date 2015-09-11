<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion

//some validation
$quizID = $quizIDGet;

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
    //image upload and validate function
    $targetFileName = basename($_FILES["questionImageUpload"]["name"]);
    $imageResult = quizHelper::handleImageUploadValidation($_FILES, $targetFileName, $quizID, $questionAlt);
    if($imageResult['result'] == false){ //fail
        $error = 1;
        $imageUploadError = $imageResult['imageUploadError'];
        $questionAltError = $imageResult['imageAltError'];
    } else {
        //extract the result
        $target_file = $imageResult['targetDir'];
    }
    if ($error == 0){
        // If image passed all criteria, attempt to upload
            if (move_uploaded_file($_FILES["questionImageUpload"]["tmp_name"], $target_file)) {
                //echo "The file ". basename($_FILES["questionImageUpload"]["name"]). " has been uploaded.";
                //success
            } else {
                $imageUploadError = "Sorry, there was an error uploading your file.";
                $error = 1;
            }
        //all good
        $result = quizLogic::insertQuestion($quizID, $prevAnswerId, $questionTitle, $questionContent, $targetFileName, $questionAlt);
        if ($result == true){
            //show soe the new question added
            header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizIDGet)."&feedback=question");
            exit();
        } else {
            loadErrorPage("There was Problem adding a question");
            //delete the upload file if any
            if(file_exists($target_file)){
                unlink($target_file);
            }else{
                //echo 'file not found';
            }
        }
    }
    
}

$dbLogic = new DB();
$quizData = quizHelper::prepare_tree($quizIDGet, $dbLogic);

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