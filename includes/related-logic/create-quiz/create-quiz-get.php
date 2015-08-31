<?php

    //Set page error messages blank upon initial loading
$quizNameError = "";
$quizDescriptionError = "";
$isPublicError = "";
$noAttemptsError = "";
$isTimeError = "";
$isSaveError = "";
$timeLimitError = "";
$invalidDateError1 = "";
$invalidDateError2 = "";
$dayStartError = "";
$monthStartError = "";
$yearStartError = "";
$dayEndError = "";
$monthEndError = "";
$yearEndError = "";
$imageUploadError = "";
$quizImageTextError = "";
$alwaysOpenError = "";

//Get current system date values in the same format as user entererd them
    $currentDate = getdate(date("U"));    

//auto create the years (used for the view only)
    $yearCurrent = $currentDate["year"];

    //set defaults
    $quizName = "";
    $quizDescription = "";
    $isPublic = "1";
    $noAttempts = "Unlimited";
    $isTime = "0";
    $timeHours = "0";
    $timeMinutes = "0";
    $isSave = "0";
    
     //default is today
    $monthStart = $currentDate["mon"];
    $dayStart = $currentDate["mday"];
    $yearStart = $currentDate["year"];
    
    $monthEnd = $currentDate["mon"];
    $dayEnd = $currentDate["mday"];
    $yearEnd = $currentDate["year"];
    $alwaysOpen = "1";
    
    include("create-quiz-view.php");