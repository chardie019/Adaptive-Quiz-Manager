<?php

/**
 * The loader for the stats user page
 */

// include php files here 
require_once("../includes/config.php");
include ("timeConverter.php");
// end of php file inclusion

$uid = $_SESSION['username'];
$welcomeMessage = ' ';
$dbLogic = new dbLogic();

    //On first load
    
        //On first load, set the $_SESSION variable to the QUIZ_ID selected form the quiz list
        //On subsequent page lloads between previous/current versions of the quiz, the $quizidconfirm will remain the same
        
        if(isset($_POST['quizid'])){
            $_SESSION['quizid'] = filter_input(INPUT_POST, "quizid");
            

        }

        if(!isset($_POST['getResult'])){
            $pageUser = 'No user selected';
            $userGet = '';
        }else{
            $pageUser = $_POST['getResult']." - Current version";
            $userGet = $_POST['getResult'];
        }
        
            $quizidconfirm = $_SESSION['quizid'];
            
            $shareColumn = "quiz_QUIZ_ID";
            
            $currentResults = true;
            //Get shared quiz id for chosen quiz to prepare results inclusive of older versions
            
            $whereSharedQuiz = array(
                "QUIZ_ID"=> $quizidconfirm
            );
        
            $sharedID = $dbLogic->select("SHARED_QUIZ_ID", "quiz", $whereSharedQuiz, true);   
            
            $quizIdShared = $sharedID['SHARED_QUIZ_ID'];
            
           
        if(isset($_POST['userPrevious'])){
            $pageUser = $_POST['getResult']." - Previous versions";
            
            $shareColumn = "shared_SHARED_QUIZ_ID";
            $quizidconfirm = $quizIdShared;
            $currentResults = false;
        } 

        $wherecolumn = array(
              "user_USERNAME" => $userGet,
              $shareColumn => $quizidconfirm
        );

        $wherecolumn2 = array(
            "RESULT_ID" => "result_RESULT_ID"       
        );

        $notNullColumn = "FINISHED_AT";
        
        $graphResults = $dbLogic->selectWithColumnsIsNotNull('*', 'result, result_answer', $wherecolumn, 
                $wherecolumn2, $notNullColumn, false);
        
        //Get list of users for user table that have completed the quiz so far
        $dataArray2 = array(
        "shared_SHARED_QUIZ_ID" => $quizIdShared
            );
        $whereColumn3 = array(
            "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
        );
            
            $quizCompleters = $dbLogic ->selectWithColumnsIsNotNull('user_USERNAME', 'result, quiz', $dataArray2, $whereColumn3, $notNullColumn, false);

            //Get the usernames of each user who has completed the quiz, and store a single record of them in a new array
            $finisherList = array();
            $v = 0;
            foreach($quizCompleters as $finishers){
                $finisherList[$v] = $finishers['user_USERNAME'];
                $v++;
            } 
            //Keep only 1 instance of each username in a new array
             $newFinisherList = array_unique($finisherList);
             //Creates new array and fills it with unique values granting new array keys [0], [1] etc instead of old.
             $uniqueFinishers = array_values($newFinisherList);
        
        if(empty($graphResults)){
	    
            include ("stats-editor-empty-view.php");
            exit();
        }
        
        //Creates a new array of the RESULT_ID's
        $totalAttempts = array();
        $i= 0;
        foreach ($graphResults as $rowResults){         
            $totalAttempts[$i] = ($rowResults["RESULT_ID"]);
            $i++;
        }
        //Retrieves only the unqiue values of RESULT_ID's
        $uniqueAttempts = array_unique($totalAttempts);
        //Creates new array and fills it with unique values granting new array keys [0], [1] etc instead of old.
        $newArray = array_values($uniqueAttempts);

        //Select QUESTION FROM QUESTION WHERE QUESTION_ID = ''
        $questions = array();
        $j= 0;
        foreach ($graphResults as $rowResults){         
            $questions[$j] = ($rowResults["question_QUESTION_ID"]);
            $j++;
        }
           
        //Retrieves only the unqiue questions answered from attempts
        $uniqueQuestions = array_unique($questions);
        //Creates new array and fills it with unique question values
        $newQuestionArray = array_values($uniqueQuestions);
        
        //Place questions of quiz in ascending order
        sort($newQuestionArray);
        
        //Get question text for Graph Title
        //Retrieves Question text using question_ids from unique question array, then populates an array to use in stats-graph-view.php
        $questionText = array();
        $q=0;
        for($y=0; $y<count($newQuestionArray); $y++){
                $whereQuestionDescr = array(
                    "QUESTION_ID" => $newQuestionArray[$y]
                );
                
                $questionDescr = $dbLogic->select("QUESTION", "question", $whereQuestionDescr, false);
                
                foreach ($questionDescr as $rowResults){         
                    $questionText[$q] = ($rowResults["QUESTION"]);
                    $q++;
                }
        }
        
        //Retrieves answers selected and number of times answer was chosen for each question collected above
        $graphData = array();
        for($l=0; $l<count($newQuestionArray); $l++){
            
            $whereQuestion = array(
                "user_USERNAME" => $_POST['getResult'],
                $shareColumn => $quizidconfirm,
                "question_QUESTION_ID" => $newQuestionArray[$l]
            );
            
            $whereAnswer = array (
                "result_answer.ANSWER" => "answer.ANSWER_ID",
                "result_RESULT_ID" => "RESULT_ID"
            );

            $answerResults = $dbLogic->selectWithColumnsIsNotNullGroupBy('question_QUESTION_ID, answer.answer, '
                    . 'COUNT(*) as CHOSEN', "result, result_answer, answer", $whereQuestion, $whereAnswer, 
                    $notNullColumn, "answer.answer", false);
            
            foreach($answerResults as $answerNumbers){
  
                $graphData{$l}[$answerNumbers['answer']] = $answerNumbers['CHOSEN'];
            }            
        }

        $countAttempts = count(array_unique($totalAttempts));
        $countQuestions = count(array_unique($questions));
     
        //Retrieve time values for attempts 
        $timearray = array(
            'times' => array()
            );
        
        foreach($graphResults as $timeResults){
            
                $timearray['times'][] = array(
                    'Started' => $timeResults['STARTED_AT'], 'Finished' =>$timeResults['FINISHED_AT']
                        );               
        }
        
        $sessions = array();
        
        foreach ($timearray as $name) {
            if (is_array($name)) {
                foreach ($name as $application) {
                    $sessions[] = strtotime($application['Finished']) - strtotime($application['Started']);
                }
            } else {
                echo "There was an error, Start/Finish dates are not stored correctly.";
            }
        }
        
        $average = array_sum($sessions) / count($sessions);
        
        //Pass time in seconds to timeConverter function to change to a readable format
        $averageTime = secs_to_h($average);
        
        $h=0;
        $min = $sessions[$h];
        $max = $sessions[$h];
        
        for($h=0; $h<count($sessions); $h++){

            if($min > $sessions[$h]){
                $min = $sessions[$h];
            }
            if($max < $sessions[$h]){
                $max = $sessions[$h];
            }
        }
        
        $minTime = secs_to_h($min);
        $maxTime = secs_to_h($max);
        
    include("stats-user-graph-view.php");
