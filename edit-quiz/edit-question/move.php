<?php

/*
 * The Loader for the move page in edit quiz area
 */
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
// end of php file inclusion

$quizId = editQuizInitialLoadLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$sharedQuizId = quizLogic::returnSharedQuizID($quizId);
$quizUrl = editQuizInitialLoadLogic::returnQuizUrl($sharedQuizId);
$username = $userLogic->getUsername();
editQuizInitialLoadLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);


$answerIdGet = filter_input (INPUT_GET, "answer");
$questionIdGet = filter_input (INPUT_GET, "question");

$type = "";
if (isset($answerIdGet)){
    $type = "answer";
    $displayType = "Answer"; //for the view only
    $displayOpposite = "question";
    $id = $answerIdGet;
} else {
    $type = "question";
    $displayType = "Question";
    $displayOpposite = "answer";
    $id = $questionIdGet;
}
$shortId= moveLogic::getShortId($id, $type);


if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    //stuff
    $submitButton = filter_input (INPUT_POST, "submit");
    
    $answerPost = filter_input (INPUT_POST, "answer");
    $questionPost = filter_input (INPUT_POST, "question");
    
    if (isset($submitButton)){
        $answerIdPost = filter_input (INPUT_POST, "answer");
        $questionIdPost = filter_input (INPUT_POST, "question");
        $error = 0; //no error yet

        if (isset($answerIdPost)) {
            //do question stuff on a answer post
            $newQuizArray = editQuizCloneLogic::maybeCloneQuiz($quizId, $id, $type);
            $quizId = $newQuizArray["quizId"];
            $id = $newQuizArray["newId"];
            moveLogic::moveQuestion($quizId, $id, $answerIdPost);
            //show the moved question
            header('Location: '. CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl&feedback=answer-moved");
            exit();
        } else if (isset($questionIdPost)) {
            //do answer stuff on a question post
            $newQuizArray = editQuizCloneLogic::maybeCloneQuiz($quizId, $id, $type);
            $quizId = $newQuizArray["quizId"];
            $id = $newQuizArray["newId"];
            moveLogic::moveAnswer($quizId, $id, $questionPost);
            //show the moved question
            header('Location: '. CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl&feedback=question-moved");
            exit();
        } else {
            $selectionError = "Choose a $displayOpposite before moving the $type.";
        }
    } else {
        configLogic::loadErrorPage("Unspecified action on the move page");
    }
}
//get request or error
$result = quizLogic::returnQuestionOrAnswerData($id , $type);
//html
if ($type == "answer"){
    $returnHtml = quizMiscLogic::prepareTree($quizId, '', "questions"); //only questions are radio boxes
} else {
    $returnHtml = quizMiscLogic::prepareTree($quizId, '', "answers"); //only answers are radio boxes
}
if (!isset($selectionError)) {$selectionError = "";}
include("move-view.php");