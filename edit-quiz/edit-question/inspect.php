<?php
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
// end of php file inclusion

$quizIdGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();


$answerIdGet = filter_input (INPUT_GET, "answer");
$questionIdGet = filter_input (INPUT_GET, "question");

//after validation
$quizId = $quizIdGet;

$type = "";
if (isset($answerIdGet)){
    $type = "answer";
    //todo validation
    $id = $answerIdGet;
    $answerId = $id; //temp until build tree is changed
} else {
    $type = "question";
    //todo validation
    $id = $questionIdGet;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    //stuff
    $submitQuestionButton = filter_input (INPUT_POST, "question-submit");
    $submitAnswerButton = filter_input (INPUT_POST, "answer-submit");
    
    $answerContent =filter_input(INPUT_POST, "answer-content");
    $feedbackContent = filter_input(INPUT_POST, "feedback-content");
    $isCorrect = filter_input(INPUT_POST, "is-correct");
    $link = filter_input(INPUT_POST, "link");
    
    $linkFromLinkPage = filter_input(INPUT_POST, "question"); //from the link page
    $linkPageButton = filter_input (INPUT_POST, "to-link-page");
    //link page's controls
    $linkPageUpdateButton = filter_input (INPUT_POST, "link-update");
    $linkPageBackButton = filter_input (INPUT_POST, "link-back");
    
    if (isset($submitQuestionButton)){
        $questionTitle = filter_input(INPUT_POST, "question-title");
        $questionContent = filter_input(INPUT_POST, "question-content");
        $questionKeepImage = filter_input(INPUT_POST, "keep-image");
        $questionAlt = filter_input(INPUT_POST, "question-alt");
        $error = 0; //no error yet

        if($questionTitle == " " || $questionTitle == "" || $questionTitle == NULL){
            $questionTitleError = "Error: You must enter the question's title.";
            $error = 1;
        }
        if($questionContent == " " || $questionContent == "" || $questionContent == NULL){
            $questionContentError = "Error: You must enter the question's content.";
            $error = 1;
        }
        if(($questionKeepImage != "1" && $questionKeepImage != "0") || $questionKeepImage == NULL){
            $questionKeepImageError = "Error: Please select whether to keep the image or not";
            $error = 1;
        }
        if ($link == ""){
            $link = NULL; //insert NULL into db
        }
        if ($error == 0){
            if ($questionKeepImage == "0"){
                quizLogic::removeImagefromQuestion($quizIdGet, $id);
            } else {
                if (is_uploaded_file($_FILES["questionImageUpload"]["tmp_name"])) { //image is optional
                    // If image passed all criteria, attempt to upload
                    $targetFileName = basename($_FILES["questionImageUpload"]["name"]);
                    $imageResult = quizHelper::handleImageUploadValidation($_FILES, $targetFileName, $quizIdGet, $questionAlt);
                    if($imageResult['result'] == false){
                        $error = 1;
                        $questionImageError = $imageResult['imageUploadError'];
                        $questionAltError = $imageResult['imageAltError'];
                    }
                }
            }
            if ($error == 0) {//if still all good
                if (is_uploaded_file($_FILES["questionImageUpload"]["tmp_name"])) {
                    //don't update the image
                    quizLogic::updateQuestion($quizIdGet, $questionIdGet, $questionTitle, $questionContent, $questionAlt);
                } else {
                    quizLogic::updateQuestion($quizIdGet, $questionIdGet, $questionTitle, $questionContent, $questionAlt, $targetFileName);
                }
                //show the new question added
                header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizIdGet)."&feedback=question-updated");
                exit();
            }
        }
    } else if (isset($submitAnswerButton)) { //Answer button/ editing an answer
        
        $error = 0; //no error yet
        //validation
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
        if ($error == 0){ //no error
            quizLogic::updateAnswer($quizIdGet, $id, $answerContent, $feedbackContent, $isCorrect, $link);
            //show the updated answer
            header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizIdGet)."&feedback=answer-updated");
            exit();
        }
    } else if (isset($linkPageButton)){
        //reset the data if tampered with
        if (!isset($answerContent)){$answerContent = "";}
        if (!isset($feedbackContent)){$feedbackContent = "";}
        if (!isset($isCorrect)){$isCorrect = "";}
        $dbLogic = new DB();
        $quizData = quizHelper::prepare_tree($quizId, $dbLogic);
        include('change-link-view.php');
        exit; 
    } else if (isset($linkPageBackButton)){
        $linkFromLinkPage = NULL; //cancel the link
    } else if (isset($linkPageUpdateButton)){
        //do nothing just load the page
        //post data already gotten using above statements 
    } else {
        loadErrorPage("Unspecified action");
    }
}
//get request or error
$result = quizLogic::returnQuestionOrAnswerData($id , $type);
$quizData = quizHelper::prepare_tree($quizIdGet, $dbLogic);

//html
if ($type == "answer"){
    //initalies strings;
    if (!isset($answerContentError)){$answerContentError = "";}
    if (!isset($feedbackContentError)){$feedbackContentError = "";}
    if (!isset($isCorrectError)){$isCorrectError = "";}

    if (!isset($answerContent)){$answerContent = $result['ANSWER'];}
    if (!isset($feedbackContent)){$feedbackContent = $result['FEEDBACK'];}
    if (!isset($isCorrect)){$isCorrect = (string)$result['IS_CORRECT'];}
    if (isset($link)){
        $linkStatus = "Linked to Q". $link;
    } if (isset($linkFromLinkPage)){
        $link = $linkFromLinkPage; //pass teh variable from the link page to this page
        $linkStatus = "Linked to Q". $linkFromLinkPage;
    } else {
        $link = "";
        $linkStatus = "Not Linked";
    }
    include("inspect-answer-view.php");
} else {
    //initalies strings;
    if (!isset($questionTitleError)){$questionTitleError = "";}
    if (!isset($questionContentError)){$questionContentError = "";}
    if (!isset($questionImageError)){$questionImageError = "";}
    if (!isset($questionKeepImageError)){$questionKeepImageError = "";}
    if (!isset($questionAltError)){$questionAltError = "";}
    if (!isset($questionImageError)){$questionImageError = "";}

    if (!isset($questionTitle)){$questionTitle = $result['QUESTION'];}
    if (!isset($questionContent)){$questionContent = $result['CONTENT']; }
    if (!isset($questionImage) && $result['IMAGE'] != NULL){ //only set if not null
            $questionImage = quizHelper::returnWebImageFilePath($quizIdGet, $result['IMAGE']);
    }  
    if (!isset($questionKeepImage)){$questionKeepImage = "1";}
    if (!isset($questionAlt)){$questionAlt = $result['IMAGE_ALT'];}
    include("inspect-question-view.php");
}