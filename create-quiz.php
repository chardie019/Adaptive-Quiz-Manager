<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

//If form is submitted, run this section of code, otherwise just load the view
//Get values from submitted form
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    include("create-quiz-post.php");
    
    
} else {    //a Get request
    
    include("create-quiz-get.php");

}