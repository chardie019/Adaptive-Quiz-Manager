<?php
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
// end of php file inclusion

$quizId = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$sharedQuizId = quizLogic::returnSharedQuizID($quizId);
$quizUrl = quizLogic::returnQuizUrl($sharedQuizId);
$username = $userLogic->getUsername();
quizLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);


$answerIdGet = filter_input (INPUT_GET, "answer");
$questionIdGet = filter_input (INPUT_GET, "question");

$type = "";
if (isset($answerIdGet)){
    $type = "answer";
    $id = $answerIdGet;
    $answerId = $id; //temp until build tree is changed
} else {
    $type = "question";
    $id = $questionIdGet;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    //stuff
    $submitQuestionButton = filter_input (INPUT_POST, "question-submit");
    $submitAnswerButton = filter_input (INPUT_POST, "answer-submit");
    
    $answerContent =filter_input(INPUT_POST, "answer-content");
    $feedbackContent = filter_input(INPUT_POST, "feedback-content");
    $isCorrect = filter_input(INPUT_POST, "is-correct");
    $imageFieldName = "questionImageUpload";
    
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
        if(($questionKeepImage != "keep-or-update" && $questionKeepImage != "delete" && 
                $questionKeepImage != "do-nothing") || $questionKeepImage == NULL){
            $questionKeepImageError = "Error: Please select whether to keep the image or not";
            $error = 1;
        }

        if ($error == 0){
            if ($questionKeepImage == "delete"){
                editQuestionLogic::removeImagefromQuestion($quizId, $id);
            } else {
                if (is_uploaded_file($_FILES[$imageFieldName]["tmp_name"])) { //image is optional
                    // If image passed all criteria, attempt to upload
                    $targetFileName = basename($_FILES[$imageFieldName]["name"]);
                    $imageResult = quizHelper::handleImageUploadValidation($_FILES, $imageFieldName, $quizId, $questionAlt);
                    if($imageResult['result'] == false){
                        $error = 1;
                        $questionImageError = $imageResult['imageUploadError'];
                        $questionAltError = $imageResult['imageAltError'];
                    }
                }
            }
            if ($error == 0) {//if still all good
                $newQuizArray = quizLogic::maybeCloneQuiz($quizId, $id, $type);
                $quizId = $newQuizArray["quizId"];
                $id = $newQuizArray["newId"];
                
                if (isset($imageResult)) { //image function was run
                    editQuestionLogic::updateQuestion($quizId, $id, $questionTitle, $questionContent, $questionAlt, $targetFileName);
                } else {
                    //don't update the image
                    editQuestionLogic::updateQuestion($quizId, $id, $questionTitle, $questionContent, $questionAlt);
                }
                //show the new question added
                header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizId)."&feedback=question-updated");
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
            $newQuizArray = quizLogic::maybeCloneQuiz($quizId, $id, $type);
            $quizId = $newQuizArray["quizId"];
            $id = $newQuizArray["newId"];
            editQuestionLogic::updateAnswer($quizId, $id, $answerContent, $feedbackContent, $isCorrect);
            //show the updated answer
            header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz='.quizLogic::returnSharedQuizID($quizId)."&feedback=answer-updated");
            exit();
        }
    } else {
        configLogic::loadErrorPage("Unspecified action");
    }
}
//get request or error
$result = quizLogic::returnQuestionOrAnswerData($id , $type);
//html
if ($type == "answer"){
    $parentId = quizLogic::returnParentId($dbLogic, $id, "answer");
    $returnHtml = quizHelper::prepareTree($dbLogic, $quizId, $parentId, "none");
    //initalies strings;
    if (!isset($answerContentError)){$answerContentError = "";}
    if (!isset($feedbackContentError)){$feedbackContentError = "";}
    if (!isset($isCorrectError)){$isCorrectError = "";}

    if (!isset($answerContent)){$answerContent = nl2br($result['ANSWER']);}
    if (!isset($feedbackContent)){$feedbackContent = nl2br($result['FEEDBACK']);}
    if (!isset($isCorrect)){$isCorrect = (string)$result['IS_CORRECT'];}
    
    include("inspect-answer-view.php");
} else {
    $parentId = quizLogic::returnParentId($dbLogic, $id, "question");
    $returnHtml = quizHelper::prepareTree($dbLogic, $quizId, $parentId, "none");
    //initalies strings;
    if (!isset($questionTitleError)){$questionTitleError = "";}
    if (!isset($questionContentError)){$questionContentError = "";}
    if (!isset($questionImageError)){$questionImageError = "";}
    if (!isset($questionKeepImageError)){$questionKeepImageError = "";}
    if (!isset($questionAltError)){$questionAltError = "";}
    if (!isset($questionImageError)){$questionImageError = "";}

    if (!isset($questionTitle)){$questionTitle = $result['QUESTION'];}
    if (!isset($questionContent)){$questionContent = nl2br($result['CONTENT']); }
    if (!isset($questionImage) && $result['IMAGE'] != NULL){ //only set if not null
            $questionImage = quizHelper::returnWebImageFilePath($quizId, $result['IMAGE']);
    }  
    if (!isset($questionKeepImage)){$questionKeepImage = "keep-or-update";}
    if (!isset($questionAlt)){$questionAlt = nl2br($result['IMAGE_ALT']);}
    include("inspect-question-view.php");
}