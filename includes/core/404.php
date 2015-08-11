<?php

// include php files here 
include_once("includes/config.php");
// end of php file inclusion
if (class_exists('DB')) {
    $dbLogic = new DB();
    if ($dbLogic->isError() === false) {
        $errorMessage = "Not found! 404 error";
    } else {
        $errorMessage = $dbLogic->isError();
    }
} else {
    $errorMessage = "hmm something is not quite right";
}
    
//html
include("404-view.php");