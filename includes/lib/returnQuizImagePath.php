<?php

function returnQuizImagepath($quizId, $file) {
    $path = STYLES_QUIZ_IMAGES_LOCATION . '/' . $quizId . '/' . $file;
    return $path;    
}
