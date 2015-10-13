<?php

/**
 * The Loader for the edit question page in edit quiz area
 */
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
// end of php file inclusion

//real quiz id
$quizId = editQuizInitialLoadLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$sharedQuizId = quizLogic::returnSharedQuizID($quizId);
$quizUrl = editQuizInitialLoadLogic::returnQuizUrl($sharedQuizId);
$username = $userLogic->getUsername();
editQuizInitialLoadLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);
$selectionError = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    $answerPost = filter_input (INPUT_POST, "answer");
    $questionPost = filter_input (INPUT_POST, "question");
    $noQuestionPost = filter_input (INPUT_POST, "no-question"); //a hidden input diplayed when no questions are are avilable/on screen
    $directionPost = filter_input (INPUT_POST, "direction"); //radio control

    $inspectButtonPost = filter_input (INPUT_POST, "inspect");
    $addQuestionButtonPost = filter_input(INPUT_POST, "addQuestion");
    $addAnswerButtonPost = filter_input (INPUT_POST, "addAnswer");
    $moveButtonPost = filter_input (INPUT_POST, "move");
    $linkButtonPost = filter_input (INPUT_POST, "link");
    $removeButtonPost = filter_input (INPUT_POST, "remove");

    if (isset($inspectButtonPost)) {
        if (isset($answerPost)){    //inspect a answer
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/inspect.php$quizUrl&answer=$answerPost");
            exit;
        } else if (isset($questionPost)) { //inspect a question
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/inspect.php$quizUrl&question=$questionPost");
            exit;
        } else {
            $selectionError="Please choose a question or answer before trying to inspect.";
        }
    } else if (isset($addQuestionButtonPost)){
        //add a question to an answer
        if (isset($directionPost) && $directionPost == "below" && isset($answerPost) && editQuestionSpecificLogic::isThereAQuestionAttachedtoThisAnswer($answerPost) == false){ 
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl&answer=$answerPost&direction=below");
            exit;
        } else if (isset($directionPost) && $directionPost == "above" && isset($answerPost)){ 
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl&answer=$answerPost&direction=above");
            exit;
        }else if (isset($noQuestionPost)) {  //add a question to no answer (so the first question)
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl&direction=below");
            exit;
        } else {    //maybe trying into add an initial question
            $selectionError="Please choose an answer with no questions(or links) before trying to add a question (eg bottom of tree with no links).";
        }
    } else if (isset($addAnswerButtonPost)){
        if (isset($directionPost) && $directionPost == "below" && isset($questionPost)) {
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-answer.php$quizUrl&question=$questionPost&direction=below");
            exit;
        } else if (isset($directionPost) && $directionPost == "above" && isset($questionPost)) {
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-answer.php$quizUrl&question=$questionPost&direction=above");
            exit;
        } else {
            $selectionError="Please choose a question to add an answer to.";
        }
    } else if (isset($linkButtonPost)){
        if (isset($answerPost)){    //link an answer
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/change-link.php$quizUrl&answer=$answerPost");
            exit;
        }  else {
            $selectionError="Please choose an answer before using the change link button.";
        }
    } else if (isset($moveButtonPost)){
        if (isset($answerPost)){    //move an answer
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/move.php$quizUrl&answer=$answerPost");
            exit;
        } else if (isset($questionPost)) {  //move a question
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/move.php$quizUrl&question=$questionPost");
            exit;
        } else {
            $selectionError="Please choose a question or answer before using the remove button.";
        }
    } else if (isset($removeButtonPost)){
        if (isset($answerPost)){    //remove an answer
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/remove.php$quizUrl&answer=$answerPost");
            exit;
        } else if (isset($questionPost)) {  //remove a question
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/remove.php$quizUrl&question=$questionPost");
            exit;
        } else {
            $selectionError="Please choose a question or answer before using the remove button.";
        }
    } else {
        //no button pressed, reload page
        $selectionError="There was an error with your selection, please try another option.";
    }
}

$feedbackMessageURL = filter_input(INPUT_GET, "feedback");
//to be implemented if i get time
//$shortIdURL = filter_input(INPUT_GET, "short-id");

switch ($feedbackMessageURL){
    case "initial-question":
        $message = "Initial Question created.";
        $messageClass = "feedback-span";
        break;
    case "question-added":
        $message = "Question added.";
        $messageClass = "feedback-span";
        break;
    case "answer-added":
        $message = "Answer added.";
        $messageClass = "feedback-span";
        break;
    case "initial-question-added":
        $message = "Answer added.";
        $messageClass = "feedback-span";
        break;
    case "question-updated":
        $message = "Question Updated.";
        $messageClass = "feedback-span";
        break;
    case "answer-updated":
        $message = "Answer Updated.";
        $messageClass = "feedback-span";
        break;
    case "answer-removed":
        $message = "Answer Removed.";
        $messageClass = "feedback-span";
        break;
    case "question-removed":
        $message = "Question Removed.";
        $messageClass = "feedback-span";
        break;
    case "link-updated":
        $message = "Link Updated.";
        $messageClass = "feedback-span";
        break;
    default:
        $feedbackMessage = "";
}
if ($selectionError != ""){
    $messageClass = "inputError";
    $message = $selectionError;
}
//set to display errors or not
if (!isset($displayMessage)){
    $displayMessage = "0"; //no message if not set
}
if (!isset($message)){
    $message = ""; //no message if not set
}
$dbLogic = new dbLogic();
$htmlTree = quizMiscLogic::prepareTree($quizId);


//http://stackoverflow.com/a/15307555\

//html
include("edit-question-view.php");