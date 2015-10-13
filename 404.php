<?php

/*
 * The loader for the error page
 */

// include php files here 
include_once("includes/config.php");
// end of php file inclusion

if (empty($errorMessage)){
    $errorMessage = "That page doesn't exist.";
}
if (empty($errorMessageSpecific)){
    $errorMessageSpecific = '404 Error';
}
//html
include("404-view.php");