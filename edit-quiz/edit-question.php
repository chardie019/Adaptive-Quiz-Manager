<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
// end of php file inclusion

//real quiz id
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$quizUrl = "?quiz=".quizLogic::returnSharedQuizID($quizIDGet);
$selectionError = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    $answerPost = filter_input (INPUT_POST, "answer");
    $questionPost = filter_input (INPUT_POST, "question");
    $inspectButtonPost = filter_input (INPUT_POST, "inspect");
    $addQuestionButtonPost = filter_input(INPUT_POST, "addQuestion");
    $addAnswerButtonPost = filter_input (INPUT_POST, "addAnswer");
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
        if (isset($answerPost)) {
            //check if there is already a question there
            if (quizLogic::isThereAQuestionAttachedtoThisAnswer($answerPost) == false){  //add a question to an answer
                header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl&answer=$answerPost");
                exit;
            } else {    //maybe trying into add an initial question
                $selectionError="Please choose a answer with no questions(or links) beofre trying to add a question (bottom of tree).";
            }
        } else {
            $displayMessage = "initalQuestion";
        }
    } else if (isset($addAnswerButtonPost)){
        if (isset($questionPost)){  //add an answer to a question
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-answer.php$quizUrl&question=$questionPost");
            exit;
        } else {
            $selectionError="Please choose a question to add an answer to.";
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
$dbLogic = new DB();
$htmlTree = quizHelper::prepareTree($dbLogic, $quizIDGet);


//http://stackoverflow.com/a/15307555\

//html
include("edit-question-view.php");