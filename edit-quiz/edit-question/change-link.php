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

$answerId = filter_input(INPUT_GET, "answer");  

$dbLogic = new DB();

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $linkFromLinkPage = filter_input(INPUT_POST, "question");
    $linkPageUpdateButton = filter_input (INPUT_POST, "link-update");
    
    $linkRemoveRadio = filter_input (INPUT_POST, "link-remove");
    
    $confirmLinkButton = filter_input (INPUT_POST, "link-confirm");
    $returnLinkButton = filter_input (INPUT_POST, "link-return");
    
    if (isset($linkPageUpdateButton) && ((isset($linkFromLinkPage) &&  $linkRemoveRadio == "update") || $linkRemoveRadio == "remove")) {
        if(!isset($linkFromLinkPage)) { $linkFromLinkPage = "";} //removal
        
        $whereValuesArray = array("ANSWER_ID" => $answerId);
        $answerArray = $dbLogic->select("ANSWER", "answer", $whereValuesArray);
        $answer = $answerArray['ANSWER'];
        include("change-link-confirmation-view.php");
        exit;
    } else if (isset($linkFromLinkPage) && isset($confirmLinkButton) &&  $linkRemoveRadio == "update") {
        $type = "question";
        $newQuizArray = quizLogic::maybeCloneQuiz($quizId, $linkFromLinkPage, $type);
        $quizId = $newQuizArray["quizId"];
        $linkFromLinkPage = $newQuizArray["newId"];
        
        //remove children (but not itself)
        $index = self::prepareRecursiveListQuestionAnswer($dbLogic, $quizId);
        self::removeChildren($dbLogic, $index, $connId, $connId);
        //get the loop conn id and set the loop to it Or NULL
        $LoopConnId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $linkFromLinkPage);
        if ($LoopConnId ==false) {$LoopConnId = NULL;}
        $setValuesArray = array("LOOP_CHILD_ID" => $LoopConnId);
        $whereValuesArray = array("CONNECTION_ID" => $connId);
        $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray); 
        
        
        //show the updated link
        header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizId)."&feedback=link-updated");
        exit();
    }else if (isset($confirmLinkButton) && $linkRemoveRadio == "remove") { 
        $type = "question";
        $newQuizArray = quizLogic::maybeCloneQuiz($quizId, $linkFromLinkPage, $type);
        $quizId = $newQuizArray["quizId"];
        $linkFromLinkPage = $newQuizArray["newId"];

        $LoopConnId = self::checkQuestionBelongsToQuizReturnId($dbLogic, $quizId, $linkFromLinkPage);
        if ($LoopConnId ==false) {$LoopConnId = NULL;}
        $setValuesArray = array("LOOP_CHILD_ID" => NULL);
        $whereValuesArray = array("CONNECTION_ID" => $connId);
        $dbLogic->updateSetWhere("question_answer", $setValuesArray, $whereValuesArray);

        //show the updated link
        header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizId)."&feedback=link-updated");
        exit();
    }else if (isset($returnLinkButton)) {
        //just load the orignal change link page
    } else {
        $selectionError = "If Updating the link, please choose a question to link it to, otherwise, choose the remove or back controls";
    }
}

if (!isset($selectionError)) {$selectionError = "";}
$returnHtml = quizHelper::prepareTree($dbLogic, $quizId, NULL, "questions");

//html
include("change-link-view.php");