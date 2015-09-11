<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
// end of php file inclusion

$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$selectionError = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    $addQuestionButtonPost = filter_input(INPUT_POST, "addQuestion");
    $answerPost = filter_input (INPUT_POST, "answer");
    $removeQuestionButtonPost = filter_input (INPUT_POST, "removeQuestion");
    $questionPost = filter_input (INPUT_POST, "question");
    $addAnswerButtonPost = filter_input (INPUT_POST, "addAnswer");
    $removeAnswerButtonPost = filter_input (INPUT_POST, "removeAnswer");
    $inspectButtonPost = filter_input (INPUT_POST, "inspect");
    
    $quizUrl = "?quiz=$quizIDGet";
    if (isset($addQuestionButtonPost)){
        if (isset($answerPost)) {
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl&answer=$answerPost");
            exit;
        } else {
            $displayMessage = "initalQuestion";
        }
    } else if (isset($removeQuestionButtonPost) && isset($questionPost)) {
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/remove-question.php$quizUrl&question=$questionPost");
            exit;
    } else if (isset($addAnswerButtonPost) && isset($questionPost)){
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-answer.php$quizUrl&question=$questionPost");
            exit;
    } else if (isset($removeAnswerButtonPost) && isset($answerPost)) {
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-answer.php$quizUrl&answer=$answerPost");
            exit;
    } else if (isset($removeQuestionButtonPost) || isset($addAnswerButtonPost)){
            $selectionError="Please choose a question to edit before continuing e.g to delete or add answers to.";
    }else if (isset($addQuestionButtonPost) || isset($removeAnswerButtonPost)){
            $selectionError="Please choose a answer to edit before continuing e.g to delete or add questions to.";
    } else if (isset($inspectButtonPost) && (isset($answerPost)) || isset($questionPost)) {
            header('Location: ' . CONFIG_ROOT_URL . "/edit-quiz/edit-question/inspect.php$quizUrl");
            exit;
    } else {
        //no button pressed, reload page
        $selectionError="There was an error with your selection.";
    }
}

$feedbackMessageURL = filter_input(INPUT_GET, "feedback");

switch ($feedbackMessageURL){
    case "initial-question":
        $message = "Initial Question created.";
        $messageClass = "feedback-span";
        break;
    case "question":
        $message = "Question added.";
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
$quizData = quizHelper::prepare_tree($quizIDGet, $dbLogic);

//http://stackoverflow.com/a/15307555\

//html
include("question-view.php");