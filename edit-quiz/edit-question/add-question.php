<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizId = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$sharedQuizId = quizLogic::returnSharedQuizID($quizId);
$quizUrl = quizLogic::returnQuizUrl($sharedQuizId);
$username = $userLogic->getUsername();
quizLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);
// end of php file inclusion

$prevAnswerId = filter_input(INPUT_GET, "answer");
$prevQuestionId = filter_input(INPUT_GET, "question");
$direction = filter_input(INPUT_GET, "direction"); //above or below

//adding a answer or adding the initial question
if (isset($prevAnswerId)) {
    $prevId = $prevAnswerId;
    $addToType = "answer"; //adding to which type
}else if (isset($prevQuestionId)) {
    $addToType = "question";
    $prevId = $prevQuestionId;
} else {
    $prevId = NULL;
}

if ($direction == "above" && isset($prevId)) {
    $operation = "addAbove";
} else if ($direction == "below" && isset($prevId)) {
    $operation = "addBelow";
}else {
    $operation = "initial";
    $direction = NULL; //direction not needed
}

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $questionTitle = filter_input(INPUT_POST, "question-title");
    $questionContent = filter_input(INPUT_POST, "question-content");
    //questionImageUpload
    $questionAlt = filter_input(INPUT_POST, "question-alt");
    $imageFieldName = "questionImageUpload";
    
    //ToDo
    //other valaiation
    
    $error = 0; //no error yet
    
    if($questionTitle == " " || $questionTitle == "" || $questionTitle == NULL){
        $questionTitleError = "Error: You must enter the question's title.";
        $error = 1;
    }
    if($questionContent == " " || $questionContent == "" || $questionContent == NULL){
        $questionContent = ""; //optional field
    }
    if ($error == 0){
        if (is_uploaded_file($_FILES[$imageFieldName]["tmp_name"])) { //image is optional
            // If image passed all criteria, attempt to upload
            $targetFileName = basename($_FILES[$imageFieldName]["name"]);
            $imageResult = quizHelper::handleImageUploadValidation($_FILES, $imageFieldName, $quizId, $questionAlt);
            if($imageResult['result'] == false){
                $error = 1;
                $imageUploadError = $imageResult['imageUploadError'];
                $questionAltError = $imageResult['imageAltError'];
            }
    } else {
        $targetFileName = NULL;
        $questionAlt = NULL;
    }
        if ($error == 0) {//all good
            $type = "answer"; //adding an answe (for clone quiz)
            $newQuizArray = quizLogic::maybeCloneQuiz($quizId, $prevId, $type);
            $quizId = $newQuizArray["quizId"];
            $prevId = $newQuizArray["newId"];
            if ($operation == "addBelow" || $operation == "addAbove") {
                editQuestionLogic::insertQuestion($quizId, $prevId, $questionTitle, $questionContent, $targetFileName, $questionAlt, $operation, $addToType);
                //show soe the new question added
                header('Location: '. CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl&feedback=question-added");
            } else { //"initial"
                editQuestionLogic::insertInitalQuestionAnswer($quizId, $questionTitle, $questionContent, $targetFileName, $questionAlt);
                //show soe the new question added
                header('Location: '. CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl&feedback=initial-question-added");
            }
            exit();
        }
    }

}
//if adding to a question or answer
if (isset($prevId)) {
    $dbLogic = new dbLogic();
    $parentId = quizLogic::returnParentId($dbLogic, $prevAnswerId, "answer");
    $returnHtml = quizHelper::prepareTree($quizId, $parentId, "none");
}
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