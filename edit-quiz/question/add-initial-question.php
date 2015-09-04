<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../../includes/config.php");
$quizIDGet = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
// end of php file inclusion

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST") { //past the appropiate page
    $questionTitle = filter_input(INPUT_POST, "question-title");
    $questionContent = filter_input(INPUT_POST, "question-content");
    //questionImageUpload
    $questionAlt = filter_input(INPUT_POST, "question-alt");
    $answerContent =filter_input(INPUT_POST, "answer-content");
    $feedbackContent = filter_input(INPUT_POST, "feedback-content");
    $isCorrect = filter_input(INPUT_POST, "is-correct");
    
    //ToDo
    //other valaiation
    
    $error = 0; //no error yet

/*Validate Image upload
     * 
     *Double \\ is needed at the end of path to cancel out the single \ effect leading into "
     * 
     */
     //$target_dir = "C:\xampp\htdocs\aqm\data\quiz-images\\";   
    $target_dir = STYLES_QUIZ_IMAGES_LOCATION_DIR . "/$quizIDGet/";    
    $targetFileName = basename($_FILES["questionImageUpload"]["name"]);
    $target_file = $target_dir . $targetFileName;
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    // Check if image file is an actual image or fake image
    if (createPath($target_dir) && is_uploaded_file($_FILES["questionImageUpload"]["tmp_name"])){
        $check = getimagesize($_FILES["questionImageUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
        // Check if file already exists inside folders
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
        // Check file size is smaller than 500kb, can change this later
        if ($_FILES["questionImageUpload"]["size"] > 5000000) { //5MB
            $uploadOk = 0;
        }
        // Allow certain image file types only *Stop people uploading other file types e.g. pdf
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }
        //only check ALT text if there is an image (which is optional)
        if($questionAlt == " " || $questionAlt == "" || $questionAlt == NULL){
            $questionAlt = "Error: Please enter alternative text to the question more accessible.";
            $error = 1;
        }
    }
    // Check if $uploadOk is set to 0 by an upload error. Exit if true.
    if ($uploadOk == 0) {
        $imageUploadError = "Error: There was an error with your image upload. Please check the following: \n"
                . "- File size is 500kb or less "
                . "- File must be in .jpg, .png, .jpeg and .gif file types\n"
                . "- The name of your file may be taken. Try renaming the file ";
        $error = 1; 
    }
    if ($error == 0){
        // If image passed all criteria, attempt to upload
            if (move_uploaded_file($_FILES["questionImageUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["questionImageUpload"]["name"]). " has been uploaded.";
            } else {
                $imageUploadError = "Sorry, there was an error uploading your file.";
                $error = 1;
            }
        //all good
    quizLogic::insertInitalQuestionAnswer($quizIDGet, $questionTitle, $questionContent, $targetFileName, $questionAlt, $answerContent, $feedbackContent, $isCorrect);
    

        //show soe the new question added
        header('Location: '. CONFIG_ROOT_URL . '/edit-quiz/question.php?quiz='.quizLogic::returnSharedQuizID($quizIDGet));
        exit();
    }
    
}

//initalies strings;
if (!isset($questionTitleError)){$questionTitleError = "";}
if (!isset($questionContentError)){$questionContentError = "";}
if (!isset($questionImageError)){$questionImageError = "";}
if (!isset($questionAltError)){$questionAltError = "";}
if (!isset($answerContentError)){$answerContentError = "";}
if (!isset($feedbackContentError)){$feedbackContentError = "";}
if (!isset($questionImageError)){$questionImageError = "";}
if (!isset($isCorrectError)){$isCorrectError = "";}

if (!isset($questionTitle)){$questionTitle = "";}
if (!isset($questionContent)){$questionContent = ""; }
if (!isset($questionAlt)){$questionAlt = "";}
if (!isset($answerContent)){$answerContent = "";}
if (!isset($feedbackContent)){$feedbackContent = "";}
if (!isset($isCorrect)){$isCorrect = "3";}


//html
include("add-initial-question-view.php");