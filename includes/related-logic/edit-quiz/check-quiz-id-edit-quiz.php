<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$quizIDGet = filter_input(INPUT_GET, "quiz");

if(is_null($quizIDGet)){
    //back to edit quiz
    header('Location: ' . CONFIG_ROOT_URL . '/edit-quiz.php?no-quiz-selected=yes');
    stop();
    
}