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

$answerIdGet = filter_input (INPUT_GET, "answer");
$questionIdGet = filter_input (INPUT_GET, "question");
$type = "";
if (isset($answerIdGet)){
    $type = "answer";
    $displayType = "answer";
    $id = $answerIdGet;
} else {
    $type = "question";
    $displayType = "question";
    $id = $questionIdGet;
}
$result = quizLogic::returnQuestionOrAnswerData($id , $type);

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    //stuff
    $deleteTypeSelection = filter_input (INPUT_POST, "delete-type"); //radio control input
    $deleteSubmit = filter_input (INPUT_POST, "delete-submit");
    $deleteConfirm = filter_input (INPUT_POST, "delete-confirm");
    $deleteReturnButton = filter_input (INPUT_POST, "delete-return");
    $error = 0; //no error yet
    if ((!isset($deleteReturnButton) && !isset($deleteConfirm) && !isset($deleteSubmit)) || //if no button pressed OR
         !isset($deleteTypeSelection) || //select button not defined OR
            $deleteTypeSelection != "single" &&     //select button isn't correct
            $deleteTypeSelection != "branch" && 
            $deleteTypeSelection != "whole-question") {
        //if NOT the return & confirm buttons & delete type is null or not any of the valid options
        $error = 1;
    }
    if ($error == 1) {
        configLogic::loadErrorPage("Unspecified remove type action on remove page.");
    } else if (isset($deleteSubmit)){
        //diplsay confirm screen
        $displayType = "confirm";
    } else if (isset($deleteConfirm)){
        //really delete it
        //okay clone first?
        $newQuizArray = quizLogic::maybeCloneQuiz($quizId, $id, $type);
        $quizId = $newQuizArray["quizId"];
        $id = $newQuizArray["newId"];
        //delete stuff
        editQuestionLogic::removeAnswerOrQuestion($quizId, $id, $type, $deleteTypeSelection);
        //show the removed question or answer
        header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizId)."&feedback=$type-removed");
        exit();
        echo "<pre>";
    } else if (isset($deleteReturnButton)) {
        //just reload load the html (do nothing)
    } else {
        configLogic::loadErrorPage("Unspecified action on remove page.");
    }
}

//pre html
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
    $questionContent = nl2br($result['CONTENT']); //get the line breaks back
    if ($result['IMAGE'] != NULL){ //only set if not null
            $questionImage = quizHelper::returnWebImageFilePath($quizId, $result['IMAGE']);
    }  
    $questionKeepImage = "1";
    $questionAlt = $result['IMAGE_ALT'];
}

//html
switch ($displayType) {
    case "answer":
        $parentId = quizLogic::returnParentId($dbLogic, $id, "answer");
        $returnHtml = quizHelper::prepareTree($quizId, $parentId, "none");
        include("remove-answer-view.php");
        break;
    case "question":
        $parentId = quizLogic::returnParentId($dbLogic, $id, "question");
        $returnHtml = quizHelper::prepareTree($quizId, $parentId, "none");
        include("remove-question-view.php");
        break;
    case "confirm":
        switch($deleteTypeSelection){
            case "branch":
                $deleteTypeDisplay = "entire branch";
                break;
            case "single":
                $deleteTypeDisplay = "single $type only";
                break;
            case "whole-question":
                $deleteTypeDisplay = "single $type and it's Answers";
                break;
        }
        if ($type == "question") {
            $title = "Remove Question";
            $content = $questionTitle;
        } else {
            $title = "Remove Answer";
            $content = $answerContent;
        }
        if(!isset($deleteTypeSelection)){$deleteTypeSelection = "";} //its nothing if it's a question
        include("remove-confirmation-view.php");
        break;
    default:
        configLogic::loadErrorPage("Unspecified action on remove page.");
        break;
}