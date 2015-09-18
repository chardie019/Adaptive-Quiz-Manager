<?php

/* 
 * TO-DO
 * track which question the user is up up
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") { //next question
//Gets hidden inputs from forms and checks their values to detemine what to load into take-quiz.
    $quizConfirmPosted = filter_input(INPUT_POST, "confirmQuiz");
    $quizConfirmIdPosted = filter_input(INPUT_POST, "confirmQuizId");
    $quizNotConfirmPosted = filter_input(INPUT_POST, "notConfirmQuiz");
    $quizSelected = filter_input(INPUT_POST, "selectQuiz");
    

    if ($quizSelected != ""){
        $quizIdPosted = filter_input(INPUT_POST,'quizid', FILTER_SANITIZE_STRING);
        if (isset($quizIdPosted)){
            include('quiz-description.php');       
            exit();//Loads quiz-description-view into take-quiz, keeps same URL
        } else {
            $quizSelectionError = "Please select a Quiz to continue.";
            $quizArray = takeQuizListLogic::getQuizList($_SESSION["username"]);
            include("quiz-list-view.php");
            exit;
        }
    }           
     if ($quizConfirmPosted != "") {
        $_SESSION["QUIZ_CONFIRMED"] = quizLogic::returnSharedQuizID($quizConfirmIdPosted);
        header('Location: '. CONFIG_ROOT_URL . '/take-quiz.php?quiz='.$_SESSION["QUIZ_CONFIRMED"]);          
        exit(); //refresh the page an rerun script
    }
    if ($quizNotConfirmPosted != "") {
        $_SESSION["QUIZ_CONFIRMED"] = ""; //not confirmed anymore
        header('Location: ' . CONFIG_ROOT_URL . '/take-quiz.php');   
        exit; //refresh the page an rerun script (with no quiz this time)
    }

        
    //otherwise continue, retrieve user answer from form
    $answerPosted = filter_input(INPUT_POST, "answer");

    //get the next question
    //check answer is legit and belongs the same quiz
    $questionData = takeQuizLogic::nextQuestionDataFeedbackConnectionId($answerPosted, $_SESSION["QUIZ_CURRENT_QUESTION"], $_SESSION["QUIZ_CURRENT_QUIZ_ID"]);
    
    if (empty($questionData)){
        //display the not found page (for hackers)  
        configLogic::loadErrorPage();
        exit; //nothing to do past here
    }

    //Set the feedback to appear on the next question page for the one previously answered
    if(!empty($questionData['FEEDBACK'])){
        $answerFeedback = $questionData['FEEDBACK'];
    }
    
    //Call record-answer before a new question ID is set
    include("record-answer.php");    

    //get answer stuff
    $answerData = takeQuizLogic::prepareViewPageGetAnswerData($questionData);
    if (!empty($answerData) > 0 && $_SESSION["QUIZ_TIME_LIMIT"] > 0){ //are there answers and time remaining, or is this the end of the quiz?      
        $_SESSION['TIME_STARTED'] = time();
        include("take-quiz-view.php");
    } else {
    //Moved $_SESSION["RESULT_ID"] = NULL; to quiz-complete.php needed for result display
        include("quiz-complete.php"); 
    }

} else { //GET - start quiz
	
	//If a previous result is still active, unsets the session variable CHANGE TO SAVE RESULT WHEN POSSIBLE?
	if (!isset($_SESSION["RESULT_ID"])) {
		unset($_SESSION["RESULT_ID"]);
	}
	
    //if ID is passed- load take quiz, otherwise load quiz-list
    $quizIdRequested = filter_input(INPUT_GET, "quiz");

    //html
    //if no quiz submitted or empty url changed (trailing slash "/" with nothing on the end)
    if (empty($quizIdRequested) || $quizIdRequested  == ".php") {
        $quizSelectionError = "";
        $quizArray = takeQuizListLogic::getQuizList($_SESSION["username"]);
        include("quiz-list-view.php");
    } 
    else {
        //find the real quiz id
        $quizId = quizLogic::returnRealQuizID($quizIdRequested);
        if ($quizId != false){  //success - it exists
            //store the REAL qiz ID to a session
            $_SESSION["QUIZ_CURRENT_QUIZ_ID"] = $quizId;
            //if QUIZ_CONFIRMED is NOT NULL OR QUIZ_CONFIRMED is different to QUIZ_CURRENT_QUIZ_ID - diplay the desciption page
            if ((!isset($_SESSION["QUIZ_CONFIRMED"])) 
                    || ($_SESSION["QUIZ_CONFIRMED"] != $_SESSION["QUIZ_CURRENT_QUIZ_ID"])) {    //same quiz and is confirmed
                include ("quiz-description.php");
            } else {                                                                   // straight to the actual quiz
                //find question now
                $whereValuesArray = array("quiz_QUIZ_ID" => $_SESSION["QUIZ_CURRENT_QUIZ_ID"]);
                //find the first question
                $questionData = takeQuizLogic::runQueryGetQuestionDataOnly ($dbLogic, $whereValuesArray, $quizId);
				
				//Sets the quiz time limit and time started session variables if the quiz is timed
				$timeData = array(
                    "QUIZ_ID" => $quizId
                );
				$quizData = $dbLogic->select("TIME_LIMIT", "quiz", $timeData);
				
				if ($quizData["TIME_LIMIT"] != "00:00:00") {
					$_SESSION['TIME_STARTED'] = time();
					$timeLimit = $quizData["TIME_LIMIT"];
					$hours = substr($timeLimit, 0, 2);
					$minutes = substr($timeLimit, 3, 2);
					$timeLimit = (((int) $hours) * 60 * 60) + ((int) $minutes * 60);
					$_SESSION["QUIZ_TIME_LIMIT"] = $timeLimit;
				}
				else {
					$_SESSION["QUIZ_TIME_LIMIT"] = 86000;
				}
				
                $_SESSION["QUIZ_CURRENT_QUESTION"] = $questionData["QUESTION_ID"];
                $answerData = takeQuizLogic::prepareViewPageGetAnswerData($questionData);
                //Sets Feedback value on take-quiz-view.php to empty for the first question.
                $answerFeedback = ' ';
                //html
                include("take-quiz-view.php");
            }
        }else {                     //fail, no result
            //display the not found page
            configLogic::loadErrorPage("That quiz doesn't exist.");
        }
    } 
}





