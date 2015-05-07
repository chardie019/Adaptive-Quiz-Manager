<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("/includes/config.php");
// end of php file inclusion

//stub for getting quiz id
//if ID is passed- load take quiz, otherwise load quiz-list
$quizId= filter_input(INPUT_GET, "quiz");

//html
if (empty($quizId)) {
    include("quiz-list.php");
} else {
    include("take-quiz-view.php");
}





