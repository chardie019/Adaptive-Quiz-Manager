<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

//Set page error messages blank upon initial loading
$quizNameError = " ";
$invalidDateError1 = " ";
$invalidDateError2 = " ";
$dayStartError = " ";
$monthStartError = " ";
$yearStartError = " ";
$dayEndError = " ";
$monthEndError = " ";
$yearEndError = " ";
$imageUploadError = " ";

//If form is submitted, run this section of code, otherwise just load the view
//Get values from submitted form
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
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
    
   if($quizName == " " || $quizName ==""){
        $quizNameError = "Error: You must enter a name for your quiz.";
        include("create-quiz-view.php");
        exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
   }
   
   
    //Ensure only months with 31 days are selected for Opening dates
    //Ensure February dates are are within the months limits
    if (($monthStart=="January" || $monthStart=="March" || $monthStart=="May" || 
            $monthStart=="July"  || $monthStart=="August" || $monthStart=="October" || 
            $monthStart=="December" && $dayStart=="31") || $monthStart=="February" && $dayStart=="29" || $dayStart=="30" || $dayStart=="31"){
        //Error on days selected dont match month limits
        //Load error screen up. 
        $invalidDateError1 = "Error: Quiz Open month doesn't have that many days.";
        include("create-quiz-view.php");
        exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
    }
    
    //Ensure only months with 31 days are selected for Closing dates
    if (($monthEnd=="January" || $monthEnd=="March" || $monthEnd=="May" || 
            $monthEnd=="July"  || $monthEnd=="August" || $monthEnd=="October" || 
            $monthEnd=="December" && $dayEnd=="31") || $monthEnd=="February" && $dayEnd=="29" || $dayEnd=="30" || $dayEnd=="31"){
        //Error on days selected dont match month limits
        //Load error screen up. 
        $invalidDateError2 = "Error: Quiz Close month doesn't have that many days.";
        include("create-quiz-view.php");
        exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
    }
    
    //Ensure date hasnt passed already and is set EQUAL to or AFTER current system date
    //Get current system date values in the same format as user entererd them
    
    $currentDate = getdate(date("U"));
    
    //Check START date is set AFTER current system date
    if($currentDate['year'] == $yearStart){
        if($currentDate['mon'] == $monthStart){
            if($currentDate['mday'] <= $dayStart){
               //Do something, set a flag? Or just have it continue down program?              
            }
            else{               
                //Load up same page, don't insert quiz into the database, stop processes
                $dayStartError = "Error: Opening date has already passed.";
                include("create-quiz-view.php");
                exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
            }
        }
        else if($currentDate['mon'] < $monthStart){           
              //Do something, set a flag? Or just have it continue down program?   
            }
        
        else{
            //Load up same page, don't insert quiz into the database, stop processes
            $monthStartError = "Error: Opening date has already passed.";
            include("create-quiz-view.php");
            exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
        }
    }
    else if($currentDate['year'] < $yearStart){
        //Do something, set a flag? Or just have it continue down program?   
    }
    else{

        //Load up same page, don't insert quiz into the database, stop processes
        $yearStartError = "Error: Opening date has already passsed.";
        include("create-quiz-view.php");
        exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
    }
    
    //Check whether CLOSE date is after START date (Which also means it's after current date). 
    if($yearEnd == $yearStart){
        if($monthEnd == $monthStart){
            if($dayEnd >= $dayStart){
                //Do something, set a flag? Or just have it continue down program?   
            }
            else{
                //Load up same page, don't insert quiz into the database, stop processes
                $dayEndError = "Error: Closing date must be after Opening date.";
                include("create-quiz-view.php");
                exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
            }
        }
        else if($monthEnd > $monthStart){  
            //Do something, set a flag? Or just have it continue down program?   
            }
        
        else{
            //Load up same page, don't insert quiz into the database, stop processes
            $monthEndError = "Error: Closing date must be after Opening date.";
            include("create-quiz-view.php");
            exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
        }
    }
    else if($yearEnd > $yearStart){
            //Do something, set a flag? Or just have it continue down program?   
    }
    else{
        //Load up same page, don't insert quiz into the database, stop processes
        $yearEndError = "Error: Closing date must be after Opening date.";
        include("create-quiz-view.php");
        exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
    }   
    

    /*Validate Image upload
     * 
     *Swap out $target_dir value with your local path for testing, will change it on SCCI server when uploaded
     *Double \\ is needed at the end of path to cancel out the single \ effect leading into "
     * 
     */
    $target_dir = "C:\Users\Admin\Documents\GitHub\Adaptive-Quiz-Manager\data\quiz-images\\";
    $target_file = $target_dir . basename($_FILES["quizImageUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    // Check if image file is an actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $imageUploadError = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $imageUploadError = "File is not an image.";
            $uploadOk = 0;
        }
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
    // Check if $uploadOk is set to 0 by an upload error. Exit if true.
    if ($uploadOk == 0) {
        $imageUploadError = "Error: There was an error with your image upload. Please check the following: \n"
                . "- File size is 500kb or less "
                . "- File must be in .jpg, .png, .jpeg and .gif file types\n"
                . "- The name of your file may be taken. Try renaming the file ";
        
        include("create-quiz-view.php");
        exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
        
    // If image passed all criteria, attempt to upload
    } else {
        if (move_uploaded_file($_FILES["quizImageUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["quizImageUpload"]["name"]). " has been uploaded.";
        } else {
            $imageUploadError = "Sorry, there was an error uploading your file.";
            include("create-quiz-view.php");
            exit();//Loads create-quiz-view into create-quiz.php, keeps same URL
        }
    }
    
    //Image alt validation needed? Insert here if required
    
    
    $dbLogic = new DB();
    //Get username for use with Editors table when required
    $uid = $_SESSION["username"];
    //Set time limit to 00:00:00 for storing in database if there is NO time limit
    if($isTime == '0'){
        $isTime = '00:00:00';
    }
    
    //Set Number of attempts to 0 for storing in database if there are unlimited attempts
    if($noAttempts == 'Unlimited'){
        $noAttempts = '0';
    }
    
    //Create String value for dateStart and dateEnd values
    
    $dateOpen = $yearStart."-".$monthStart."-".$dayStart." 00:00:00";
    $dateClose = $yearEnd."-".$monthEnd."-".$dayEnd." 11:59:00";    
    
    $dataArray = array(
        "QUIZ_ID" => "",
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
        "IMAGE_ALT" => "$quizImageText"
        
        );

    
    ($dbLogic->insert($dataArray, "quiz"));
     
    include("create-quiz-view.php");
    
    /*Also need to:
     * - Insert the username of creator into the editors table
     * - Validate whether the Quiz name already exists in DB
    */
    
    

}
//html
else{
    include("create-quiz-view.php");}
