<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion
$quizId = $quizIDGet;

$answerIdGet = filter_input (INPUT_GET, "answer");
$questionIdGet = filter_input (INPUT_GET, "question");
$type = "";
if (isset($answerIdGet)){
    $type = "answer";
    $id = $answerIdGet;
} else {
    $type = "question";
    $id = $questionIdGet;
}
$result = quizLogic::returnQuestionOrAnswerData($id , $type);

if ($type == "answer"){
    //initalies strings;
    $answerContent = $result['ANSWER'];
    $feedbackContent = $result['FEEDBACK'];
    switch ((string)$result['IS_CORRECT']) {
        case "0": 
            $correctText = "Incorrect";
            break;
        case "1":
            $correctText = "Correct";
            break;
        case "2":
            $correctText = "Neutral";
            break;
        default:
            $correctText = "Unknown";
            break; 
    }
} else {
    //initalies strings;
    $questionTitle = $result['QUESTION'];
    $questionContent = $result['CONTENT'];
    if ($result['IMAGE'] != NULL){ //only set if not null
            $questionImage = quizHelper::returnWebImageFilePath($quizIDGet, $result['IMAGE']);
    }  
    $questionKeepImage = "1";
    $questionAlt = $result['IMAGE_ALT'];
}


if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    //stuff
    $deleteSubmit = filter_input (INPUT_POST, "delete-submit");
    $deleteConfirm = filter_input (INPUT_POST, "delete-confirm");
    if (isset($deleteSubmit)){
        //diplsay confirm screen
        if ($type == "question") {
            $title = "Remove Question";
            $content = $questionTitle;
        } else {
            $title = "Remove Answer";
            $content = $answerContent;
        }
        include("remove-confirmation-view.php");
        exit;
    } else if (isset($deleteConfirm)){
        //really delete it
        //past the quiz and 
        quizLogic::removeAnswerOrQuestion($quizIDGet, $id, $type);
        //show the removed question or answer
        header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizIDGet)."&feedback=$type-removed");
        exit();
    } else {
        configLogic::loadErrorPage("Unspecified action on remove page.");
    }
}



//html
if ($type == "answer"){
    $parentId = quizLogic::returnParentId($dbLogic, $id, "answer");
    $returnHtml = quizHelper::prepareTree($dbLogic, $quizId, $parentId, "none");
    include("remove-answer-view.php");
} else {
    $parentId = quizLogic::returnParentId($dbLogic, $id, "question");
    $returnHtml = quizHelper::prepareTree($dbLogic, $quizId, $parentId, "none");
    include("remove-question-view.php");
}