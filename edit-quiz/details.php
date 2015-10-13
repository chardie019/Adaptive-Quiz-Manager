<?php
/**
 * The Loader for the details page in edit quiz area
 */
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
$quizId = editQuizInitialLoadLogic::getQuizIdFromUrlElseReturnToEditQuiz();
$sharedQuizId = quizLogic::returnSharedQuizID($quizId);
$quizUrl = editQuizInitialLoadLogic::returnQuizUrl($sharedQuizId);
$username = $userLogic->getUsername();
editQuizInitialLoadLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);
// end of php file inclusion



//create date variable
$currentDate = getdate(date("U"));
//Get current system date values in the same format as user entererd them
$yearCurrent = $currentDate["year"];
    
if (isset($_POST['confirmUpdate'])) {
    $quizName = filter_input(INPUT_POST, "quizName");
    $quizDescription = filter_input(INPUT_POST, "quizDescription");
    $isPublic = filter_input(INPUT_POST, "isPublic");
    $noAttempts = filter_input(INPUT_POST, "noAttempts");
    $isTime = filter_input(INPUT_POST, "isTime");
    $timeHours = filter_input(INPUT_POST, "timeHours");
    $timeMinutes = filter_input(INPUT_POST, "timeMinutes");
    $isSave = filter_input(INPUT_POST, "isSave");

    //Validate opening dates entered by user
    $monthStart = filter_input(INPUT_POST, "monthStart");
    $dayStart = filter_input(INPUT_POST, "dayStart");
    $yearStart = filter_input(INPUT_POST, "yearStart");

    //Validate closing dates entered by user
    $alwaysOpen = filter_input(INPUT_POST, "alwaysOpen");
    $monthEnd = filter_input(INPUT_POST, "monthEnd");
    $dayEnd = filter_input(INPUT_POST, "dayEnd");
    $yearEnd = filter_input(INPUT_POST, "yearEnd");

    //Get image values entered by user
    $quizImageText = filter_input(INPUT_POST, "quizImageText");
    $keepImage = filter_input(INPUT_POST, "keep-image");

    //display current image if post new image works
    $currentImageFileName = filter_input(INPUT_POST, "currentquizImageUpload");
    $imageFieldName = "quizImageUpload";
    //no error yet
    $error = 0;

    if($quizName == " " || $quizName == "" || $quizName == NULL){
        $quizNameError = "Error: You must enter a name for your quiz.";
        $error = 1;
    }
    if($quizDescription == " " || $quizDescription == "" || $quizDescription == NULL){
        $quizDescriptionError = "Error: You must enter a Description for your quiz.";
        $error = 1;
    }
    if($isPublic != "1" && $isPublic != "0"){
        $isPublicError = "Error: You must choose if your quiz is public or private.";
        $error = 1;
    }
    if($noAttempts == NULL || (!is_numeric($noAttempts) && $noAttempts != "Unlimited") 
        || (is_numeric($noAttempts) && ((int)$noAttempts < 1 || (int)$noAttempts > 10))) {
        $noAttemptsError = "Error: You must choose the number of attempts.";
        $error = 1;
    }
    if($isTime != "0" && $isTime != "1"){
        $isTimeError = "Error: You must choose if there a time limit or not.";
        $error = 1;
    } elseif ($isTime == "1") { //is timed, then validate the limit
        if ($timeHours == NULL || !is_numeric($timeHours) || (int)$timeHours < 0 || (int)$timeHours > 5
            || $timeMinutes == NULL || !is_numeric($timeMinutes) || (int)$timeMinutes < 0 || (int)$timeMinutes > 60){
            $timeLimitError = "Error: That Time Limit is invalid, please choose hours between 0-5 and minutes under 60.";
            $error = 1;
        }
    }
    if($isSave != "1" && $isSave != "0"){
        $isSaveError = "Error: Please choose whether the quiz can save progress or not.";
        $error = 1;
    }
    //Check the Start Date
    if (!is_numeric($dayStart) || !is_numeric($monthStart) || !is_numeric($yearStart) //if form was hacked
        || !checkdate($monthStart, $dayStart, $yearStart)) {   //or is invalid date
            //Load error screen up. 
            $invalidDateError1 = "Error: The Date choosen is invalid, please correct the date.";
            $error = 1;
    }

    //Check the End date
    if (!is_numeric($dayEnd) || !is_numeric($monthEnd) || !is_numeric($yearEnd) //if form was hacked
        || !checkdate($monthEnd, $dayEnd, $yearEnd)) {   //or is invalid date
        //Load error screen up.
        $invalidDateError2 = "Error: The Date choosen is invalid, please correct the date."; 
        $error = 1;
    } else {  //is valid entry
        //Check whether CLOSE date is ON or after START date (Which also means it's after current date).
        if (new DateTime($yearStart . "-" . $monthStart . "-" . $dayStart) > 
            new DateTime($yearEnd . "-" . $monthEnd . "-" . $dayEnd)){ //start is past the "end" date - Format:yyyy "-" mm "-" dd
            $invalidDateError1 = "Error: Closing date must be after Opening date.";
            $error = 1;
        } 
    }
    if($_SESSION['IS_QUIZ_ENABLED'] == true){
        $error = 1; 
        $quizEnabledError = "Quiz is still ENABLED. Return to Edit Quiz and DISABLE in order to update your quiz.";
    }
    if(($keepImage != "keep-or-update" && $keepImage != "delete" && 
            $keepImage != "do-nothing") || $keepImage == NULL){
        $keepImageError = "Error: Please select whether to keep the image or not";
        $error = 1;
    }

    // Check if image file is an actual image or fake image
    if ($error == 0){
            if ($keepImage == "delete"){
                editQuizLogic::removeImagefromQuiz($quizId);
            } else {
                if (is_uploaded_file($_FILES[$imageFieldName]["tmp_name"])) { //image is optional
                // If image passed all criteria, attempt to upload
                $targetFileName = basename($_FILES[$imageFieldName]["name"]);
                $imageResult = quizHelper::handleImageUploadValidation($_FILES, $imageFieldName, $quizId, $quizImageText, $currentImageFileName);
                if($imageResult['result'] == false){
                    $error = 1;
                    $imageUploadError = $imageResult['imageUploadError'];
                    $quizImageTextError = $imageResult['imageAltError'];
                } else {
                    $currentImageFileName = $targetFileName;
                }
            }
        }
        if ($error == 0) {// no errors update the database
            //determine if cloning is needed, i is teh same if no needed, else, is the new quiz id
            
            $newQuizArray = editQuizCloneLogic::maybeCloneQuiz($quizId);
            $quizId = $newQuizArray["quizId"];
            if (isset($imageResult)) { //image function was run
                $quizUpdated = editQuizLogic::updateQuizDetails($quizId, $isTime, $timeHours, $timeMinutes, 
                    $noAttempts, $yearStart, $monthStart, $dayStart, $alwaysOpen, 
                    $yearEnd, $monthEnd, $dayEnd, $quizName, $quizDescription, $isPublic, 
                    $isSave, $quizImageText, $targetFileName);
            } else {
                $quizUpdated = editQuizLogic::updateQuizDetails($quizId, $isTime, $timeHours, $timeMinutes, 
                    $noAttempts, $yearStart, $monthStart, $dayStart, $alwaysOpen, 
                    $yearEnd, $monthEnd, $dayEnd, $quizName, $quizDescription, $isPublic, 
                    $isSave, $quizImageText);
            }
        }
    }   
} else { //Get request
    
    //Get current system datetime for insertion into EDITORS table
    $creationDatetime = date('Y-m-d H:i:s');    

    //Retrieve Quiz details to populate form
    $quizInfo = editQuizLogic::getQuizData($quizId);
    
    $time_string = $quizInfo['TIME_LIMIT'];
    $startDate = $quizInfo['DATE_OPEN'];
    $endDate = $quizInfo['DATE_CLOSED'];
}

//initalise strings if no error or input
if(!isset($quizName)){ $quizName= $quizInfo['QUIZ_NAME'];}
if(!isset($quizDescription)){$quizDescription = $quizInfo['DESCRIPTION']; }
if(!isset($isPublic)){ $isPublic = $quizInfo['IS_PUBLIC']; }
if(!isset($noAttempts)){ 
    if (isset($quizInfo['NO_OF_ATTEMPTS'])) {
        $noAttempts = $quizInfo['NO_OF_ATTEMPTS'];
    } else {
        $noAttempts = "0";
    }
}
if(!isset($isTime)){ 
    if (isset($isTime) || $quizInfo['TIME_LIMIT'] == '00:00:00') {
        $isTime = 0;
        if(!isset($timeHours)){ $timeHours = "0"; }
        if(!isset($timeMinutes)){ $timeMinutes = "0"; }
    } else {
        $isTime = 1;
        if(!isset($timeHours)){ $timeHours = substr($time_string, 1, 1); }
        if(!isset($timeMinutes)){ $timeMinutes = substr($time_string, 3, 2); }
    }
}
if(!isset($isSave)){ $isSave = $quizInfo['IS_SAVABLE']; }

//opening dates
if(!isset($monthStart)){ $monthStart = substr($startDate, 8, 2); }
if(!isset($dayStart)){ $dayStart = substr($startDate, 5, 2); }
if(!isset($yearStart)){ $yearStart = substr($startDate, 0, 4); }

//closing dates
if (!isset($monthEnd) && !isset($dayEnd) && !isset($yearEnd)) { 
    if (isset($quizInfo['DATE_CLOSED'])) {
        $monthEnd = substr($endDate, 5, 2);
        $dayEnd =substr($endDate, 8, 2);
        $yearEnd = substr($endDate, 0, 4);
    } else {
        $monthEnd = $currentDate["mon"];
        $dayEnd = $currentDate["mday"];
        $yearEnd = $currentDate["year"];
    }
}

//Get image values entered by user
if (!isset($keepImage)){$keepImage = "keep-or-update";}

if(!isset($quizImageText)){ 
    if (isset($quizInfo['IMAGE_ALT'])) {
        $quizImageText = $quizInfo['IMAGE_ALT'];
    } else {
        $quizImageText = "";
    }
}

if(!isset($currentImage)){
    if (isset($quizInfo['IMAGE'])) {
        $currentImage = quizHelper::returnWebImageFilePath($sharedQuizId, $quizInfo['IMAGE']);
        $currentImageFileName = $quizInfo['IMAGE'];
    } else if (isset($targetFileName)){ //from a image just added
       $currentImage = quizHelper::returnWebImageFilePath($sharedQuizId, $targetFileName);
       $currentImageFileName = $targetFileName;
    } else {
        $currentImage = NULL; //handle it in the view page
        $currentImageFileName = "";
    }
} else {
    $currentImage = quizHelper::returnWebImageFilePath($sharedQuizId, $currentImageFileName);
    //$currentImageFileName stays same
}

//Set page error messages blank upon initial loading
if(!isset($quizUpdated)){ $quizUpdated = ""; }
if(!isset($quizNameError)){ $quizNameError = ""; }
if(!isset($quizDescriptionError)){ $quizDescriptionError = ""; }
if(!isset($isPublicError)){ $isPublicError = ""; }
if(!isset($noAttemptsError)){ $noAttemptsError = ""; }
if(!isset($isTimeError)){ $isTimeError = ""; }
if(!isset($isSaveError)){ $isSaveError = ""; }
if(!isset($timeLimitError)){ $timeLimitError = ""; }
if(!isset($invalidDateError1)){ $invalidDateError1 = ""; }
if(!isset($invalidDateError2)){ $invalidDateError2 = ""; }
if(!isset($dayStartError)){ $dayStartError = ""; }
if(!isset($monthStartError)){ $monthStartError = ""; }
if(!isset($yearStartError)){ $yearStartError = ""; }
if(!isset($dayEndError)){ $dayEndError = ""; }
if(!isset($monthEndError)){ $monthEndError = ""; }
if(!isset($yearEndError)){ $yearEndError = ""; }
if(!isset($imageUploadError)){ $imageUploadError = ""; }
if(!isset($quizImageTextError)){ $quizImageTextError = ""; }
if(!isset($keepImageError)){ $keepImageError = ""; }
if(!isset($quizEnabledError)){ $quizEnabledError = ""; }
if(!isset($alwaysOpen)){$alwaysOpen = "";}
if(!isset($alwaysOpenError)){ $alwaysOpenError = "";}

unset($quizInfo); //stop using the db data
        
//html
include("details-view.php");