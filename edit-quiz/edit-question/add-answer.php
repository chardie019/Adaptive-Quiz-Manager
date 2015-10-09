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

if (isset($prevAnswerId)) {
    $prevId = $prevAnswerId;
    $addToType = "answer"; //adding to which type
}else if (isset($prevQuestionId)) {
    $addToType = "question";
    $prevId = $prevQuestionId;
}

if ($direction == "above") {
    $operation = "addAbove";
} else if ($direction == "below") {
    $operation = "addBelow";
}

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $answerContent =filter_input(INPUT_POST, "answer-content");
    $feedbackContent = filter_input(INPUT_POST, "feedback-content");
    $isCorrect = filter_input(INPUT_POST, "is-correct");
    $link = filter_input(INPUT_POST, "link");
    
    $linkFromLinkPage = filter_input(INPUT_POST, "question"); //from the link page
    $createAnswerButton = filter_input (INPUT_POST, "create-answer");
    $linkPageButton = filter_input (INPUT_POST, "to-link-page");
    //link page's controls
    $linkPageUpdateButton = filter_input (INPUT_POST, "link-update");
    $linkPageBackButton = filter_input (INPUT_POST, "link-back");
    
    if (isset($createAnswerButton)){
        //validation
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
            $type = "question"; //adding to a question (for clone quiz only)
            $newQuizArray = quizLogic::maybeCloneQuiz($quizId, $prevId, $type);
            $quizId = $newQuizArray["quizId"];
            $prevId = $newQuizArray["newId"];
            $result = editQuestionLogic::insertAnswer($quizId, $prevId, $answerContent, $feedbackContent, $isCorrect, $direction, $addToType);
            //show soe the new question added
            header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php'.$quizUrl."&feedback=answer-added");
            exit();
        }
    } else {
        configLogic::loadErrorPage("Unspecified action");
    }
}
if (isset($prevId)) {
    $dbLogic = new dbLogic();
    $parentId = quizLogic::returnParentId($dbLogic, $prevId, "question");
    $returnHtml =  quizHelper::prepareTree($quizId, $parentId, "none");
} else {
    $returnHtml = "";
}

//initalies strings;
if (!isset($answerContentError)){$answerContentError = "";}
if (!isset($feedbackContentError)){$feedbackContentError = "";}
if (!isset($isCorrectError)){$isCorrectError = "";}

if (!isset($answerContent)){$answerContent = "";}
if (!isset($feedbackContent)){$feedbackContent = "";}
if (!isset($isCorrect)){$isCorrect = "2";}

//html
include("add-answer-view.php");