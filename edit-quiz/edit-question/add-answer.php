<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizId = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion

$questionIdPost = filter_input(INPUT_GET, "question");
//after validation
$questionId = $questionIdPost;

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
        if ($link == ""){
            $link = NULL; //insert NULL into db
        }
        if ($error == 0){
            //all good
            $quizId = quizLogic::maybeCloneQuiz($quizId);
            $result = quizLogic::insertAnswer($quizId, $questionIdPost, $answerContent, $feedbackContent, $isCorrect, $link);
            if ($result == true){
                //show soe the new question added
                header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizId)."&feedback=answer-added");
                exit();
            } else {
                configLogic::loadErrorPage("There was Problem adding a answer.");
            }
        }
    }else if (isset($linkPageButton)){
        //reset the data if tampered with
        if (!isset($answerContent)){$answerContent = "";}
        if (!isset($feedbackContent)){$feedbackContent = "";}
        if (!isset($isCorrect)){$isCorrect = "";}
        $dbLogic = new DB();
        $returnHtml = quizHelper::prepareTree($dbLogic, $quizId);
        include('change-link-view.php');
        exit; 
    } else if (isset($linkPageBackButton)){
        $link = NULL; //cancel the link
    } else if (isset($linkPageUpdateButton)){
        //do nothing just load the page
        //post data already gotten using above statements 
    } else {
        configLogic::loadErrorPage("Unspecified action");
    }
}

$dbLogic = new DB();
$parentId = quizLogic::returnParentId($dbLogic, $questionId, "question");
$returnHtml =  quizHelper::prepareTree($dbLogic, $quizId, $parentId, "none");

//initalies strings;
if (!isset($answerContentError)){$answerContentError = "";}
if (!isset($feedbackContentError)){$feedbackContentError = "";}
if (!isset($isCorrectError)){$isCorrectError = "";}

if (!isset($answerContent)){$answerContent = "";}
if (!isset($feedbackContent)){$feedbackContent = "";}
if (!isset($isCorrect)){$isCorrect = "2";}

if(!isset($linkFromLinkPage) && !isset($linkPageUpdateButton)){$linkFromLinkPage=NULL;}
if(!isset($link)){$link= NULL;}
$linkArray = linkLogic::prepareLinkHtml($link, $linkFromLinkPage);
$linkFromLinkPage = $linkArray['linkHtml'];
$linkStatus = $linkArray['linkStatus'];


//html
include("add-answer-view.php");