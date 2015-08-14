<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$quizIDGet = filter_input(INPUT_GET, "quiz");

//if if there are any questions
$dbLogic = new DB();

        //Create array for insert->quiz
        $where = array(
            "quiz_QUIZ_ID" => "$quizIDGet",
        );

        //Insert quiz into database
        $quizData = ($dbLogic->select("*", "QUESTION", $where, false));
        
        $tableData = array();
        $wide = 20;//todo, see how wide & high the tree structure is
        $high = 20;
        $quizLevels = 5;
        $whichCell = array();
        
        for ($i=0;$i<$quizLevels;$i++){
            $whichCell[]  = round($wide / 2);
        }
        
        



//html
include("question-view.php");