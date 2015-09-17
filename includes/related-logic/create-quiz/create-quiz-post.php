<?php

//Get current system date values in the same format as user entererd them
$currentDate = getdate(date("U"));
//Get current system datetime for insertion into EDITORS table
$creationDatetime = date('Y-m-d H:i:s');


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
    $monthEnd = filter_input(INPUT_POST, "monthEnd");
    $dayEnd = filter_input(INPUT_POST, "dayEnd");
    $yearEnd = filter_input(INPUT_POST, "yearEnd");
   
    //Get image values entered by user
    $quizImageText = filter_input(INPUT_POST, "quizImageText");
    
    $alwaysOpen = filter_input(INPUT_POST, "alwaysOpen");
    //no error yet
    $error = 0;
    
    $dbLogic = new DB();

    if($quizName == " " || $quizName == "" || $quizName == NULL){
        $quizNameError = "Error: You must enter a name for your quiz.";
        $error = 1;
    } else {
        /* //not used due more details being avilable on take-quiz page
        //Ensure quiz name is not already taken in database
        $quiz_name_list = $dbLogic->selectAll('quiz');   
        foreach ($quiz_name_list as $answerRow) {
            if($answerRow["QUIZ_NAME"] == $quizName){
                $quizNameError = "Quiz name already in use. Please rename your quiz.";
                $error = 1;
            }
        }*/
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
    } else { //is valid entry
        //Ensure date hasnt passed already and is set EQUAL to or AFTER current system date

        //Check START date is set to today or AFTER current system date
        if (new DateTime($yearStart . "-" . $monthStart . "-" . $dayStart) < 
            new DateTime($currentDate["year"] . "-" . $currentDate["mon"] . "-" . $currentDate["mday"])){ //in the past - Format:yyyy "-" mm "-" dd
            $invalidDateError1 = "Error: Opening date has already passed.";
            $error = 1;
        }  
    }
    //validate always open
    if($alwaysOpen != "0" && $alwaysOpen != "1"){
        $alwaysOpenError = "Error: You must choose whether the quiz will always be open or not.";
        $error = 1;
    } else {
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
    }
    
    /*Validate Image upload
     * 
     *Double \\ is needed at the end of path to cancel out the single \ effect leading into "
     * 
     */
    $target_dir = "C:\Users\Admin\Documents\GitHub\Adaptive-Quiz-Manager\data\quiz-images\\";
    $target_file = $target_dir . basename($_FILES["quizImageUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    // Check if image file is an actual image or fake image
    if (is_uploaded_file($_FILES["quizImageUpload"]["tmp_name"])){
        $check = getimagesize($_FILES["quizImageUpload"]["tmp_name"]);
        if($check !== false) {
            //$imageUploadError = "File is an image - " . $check["mime"] . ".";
            // purpose? - commented out by josh
            $uploadOk = 1;
        } else {
            $imageUploadError = "File is not an image.";
            $uploadOk = 0;
        }
        // Check if file already exists inside folders
        if (file_exists($target_file)) {
            $imageUploadError = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size is smaller than 500kb, can change this later
        if ($_FILES["quizImageUpload"]["size"] > 500000) {
            $imageUploadError = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain image file types only *Stop people uploading other file types e.g. pdf
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }
        //only check ALT text if there is an image (which is optional)
        if($quizImageText == " " || $quizImageText == "" || $quizImageText == NULL){
            $quizImageTextError = "Error: Please enter alternative text to the quiz more accessible.";
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

    
        
    //now display error pag
    if ($error == 1){
        //tell user to re-include the image
        if (is_uploaded_file($_FILES["quizImageUpload"]["tmp_name"]) && $imageUploadError == ""){
            $imageUploadError = "Due to another error, please upload the picture again.";
        }
        
        //set errors if not set
        if (!isset($quizNameError)){$quizNameError = "";}
        if (!isset($quizDescriptionError)){$quizDescriptionError = "";}
        if (!isset($isPublicError)){$isPublicError = "";}
        if (!isset($noAttemptsError)){$noAttemptsError = "";}
        if (!isset($timeLimitError)){$timeLimitError = "";}
        if (!isset($isTimeError)){$isTimeError = "";}
        if (!isset($isSaveError)){$isSaveError = "";}
        if (!isset($invalidDateError1)){$invalidDateError1 = "";}
        if (!isset($invalidDateError2)){$invalidDateError2 = "";}
        if (!isset($imageUploadError)){$imageUploadError = "";}
        if (!isset($quizImageTextError)){$quizImageTextError = "";}
        //auto create the years (used for the view only)
        $yearCurrent = $currentDate["year"];

        include("create-quiz-view.php");
    } else {// no errors
        // If image passed all criteria, attempt to upload
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["quizImageUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["quizImageUpload"]["name"]). " has been uploaded.";
            } else {
                $imageUploadError = "Sorry, there was an error uploading your file.";
                $error = 1;
            }
        }
        

        //Get username for use with Editors table when required
        $uid = $_SESSION["username"];
        //Set time limit to 00:00:00 for storing in database if there is NO time limit
        if($isTime == '0'){
            $isTime = '00:00:00';
        }else{
        $isTime = '0'.$timeHours.':'.$timeMinutes.':00';
        
        }

        //Set Number of attempts to 0 for storing in database if there are unlimited attempts
        if($noAttempts == 'Unlimited'){
            $noAttempts = '0';
        }

        //Create String value for dateStart and dateEnd values
        $dateOpen = $yearStart."-".$monthStart."-".$dayStart." 00:00:00";
        if ($alwaysOpen == "0"){
            $dateClose = $yearEnd."-".$monthEnd."-".$dayEnd." 11:59:00"; 
        } else {
            $dateClose = NULL;
        }
        //Create array for insert->quiz
        $dataArray = array(
			"VERSION" => "0",
            "QUIZ_NAME" => "$quizName",
            "DESCRIPTION" => "$quizDescription",
            "IS_PUBLIC" => "$isPublic",
            "NO_OF_ATTEMPTS" => "$noAttempts",
            "TIME_LIMIT" => "$isTime",
            "IS_SAVABLE" => "$isSave",
            "DATE_OPEN" => $dateOpen,
            "DATE_CLOSED" => $dateClose,
            "INTERNAL_DESCRIPTION" => "",
            "IMAGE" => basename( $_FILES["quizImageUpload"]["name"]),
            "IMAGE_ALT" => "$quizImageText",
            "IS_ENABLED" => "0" //not enabled yet
            );

        //Insert quiz into database
        $current_quiz_id = ($dbLogic->insert($dataArray, "quiz"));
        
        quizLogic::setSharedQuizId($current_quiz_id);
        
        //success
        $quizNameError = "Success";
        
        //Insert creator of quiz into the editors table upon successful creation of quiz
        $dataArray2 = array(
            "user_USERNAME" => "$uid",
            "shared_SHARED_QUIZ_ID" => "$current_quiz_id",
            "ADDED_AT" => "$creationDatetime",
            "ADDED_BY" => "$uid"
        );

        $dbLogic->insert($dataArray2, "editor");        
        
        //Set current quiz being created as session variable
        $_SESSION['CURRENT_CREATE_QUIZ_ID'] = $current_quiz_id;
        header('Location: '. CONFIG_ROOT_URL . '/edit-quiz.php?quiz='.$_SESSION["CURRENT_CREATE_QUIZ_ID"].'&create=yes');
        
        $_SESSION['SET_QUIZ_ID'] = '1';
        exit();

        
    }