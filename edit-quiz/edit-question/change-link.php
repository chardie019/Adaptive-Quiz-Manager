<?php
/**
 * The loader for the change link page in manage quiz area
 */
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizId = editQuizInitialLoadLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$sharedQuizId = quizLogic::returnSharedQuizID($quizId);
$quizUrl = editQuizInitialLoadLogic::returnQuizUrl($sharedQuizId);
$username = $userLogic->getUsername();
editQuizInitialLoadLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);
// end of php file inclusion

$answerId = filter_input(INPUT_GET, "answer");  

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $linkFromLinkPage = filter_input(INPUT_POST, "question");
    $linkPageUpdateButton = filter_input (INPUT_POST, "link-update");
    
    $linkRemoveRadio = filter_input (INPUT_POST, "link-remove");
    
    $confirmLinkButton = filter_input (INPUT_POST, "link-confirm");
    $returnLinkButton = filter_input (INPUT_POST, "link-return");
    
    if (isset($linkPageUpdateButton) && ((isset($linkFromLinkPage) &&  $linkRemoveRadio == "update") || $linkRemoveRadio == "remove")) {
        if(!isset($linkFromLinkPage)) { $linkFromLinkPage = "";} //removal
        $answer = changeLinkLogic::getAnswerData($answerId);
        include("change-link-confirmation-view.php");
        exit;
    } else if (isset($linkFromLinkPage) && isset($confirmLinkButton) &&  $linkRemoveRadio == "update") {
        $type = "question";
        $newQuizArray = editQuizCloneLogic::maybeCloneQuiz($quizId, $linkFromLinkPage, $type);
        $quizId = $newQuizArray["quizId"];
        $linkFromLinkPage = $newQuizArray["newId"];
        changeLinkLogic::updateLink ($quizId, $linkFromLinkPage, $answerId);
        //show the updated link
        header('Location: '. CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl&feedback=link-updated");
        exit();
    }else if (isset($confirmLinkButton) && $linkRemoveRadio == "remove") { 
        $type = "answer";
        $newQuizArray = editQuizCloneLogic::maybeCloneQuiz($quizId, $answerId, $type);
        $quizId = $newQuizArray["quizId"];
        $answerId = $newQuizArray["newId"];
        changeLinkLogic::removeLink($quizId, $answerId);
        //show the updated link
        header('Location: '. CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl&feedback=link-updated");
        exit();
    }else if (isset($returnLinkButton)) {
        //just load the orignal change link page
    } else {
        $selectionError = "If Updating the link, please choose a question to link it to, otherwise, choose the remove or back controls";
    }
}

if (!isset($selectionError)) {$selectionError = "";}
$returnHtml = quizMiscLogic::prepareTree($quizId, NULL, "questions");

//html
include("change-link-view.php");