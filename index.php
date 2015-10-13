<?php

/* 
 * The Loader for the index (root page)
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion
//Set  
$_SESSION['SET_QUIZ_ID'] = "";
//html
include("index-view.php");