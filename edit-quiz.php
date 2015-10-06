<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// include php files here 
require_once("includes/config.php");
// end of php file inclusion

$dbLogic = new DB();

/*
 * Store quizid from edit-quiz-list in session variable to be used in edit-quiz-view.php 
 * as it passes as empty after the first time it is posted from edit-quiz-list and cant be accessed.
 */


$confirmActive = "";
$enableSubMenuLinks = true; //default the links work
$quizIDGet = filter_input(INPUT_GET, "quiz");

if (!is_null($quizIDGet)){
    $quizId = quizLogic::returnRealQuizID($quizIDGet);
} else {
    $quizId = NULL;
}

$quizCreated = filter_input(INPUT_GET, "create");
if ($quizCreated == "yes"){
    $createQuizConfirmation = "Quiz Successfully created!";
} else {
    $createQuizConfirmation = "";
}

$reason = filter_input(INPUT_GET, "message");
if (isset($reason)){
    switch($reason){
        case 'no-quiz-selected':
            $message = "Please select a quiz to contine.";
            break;
        case 'no-edit-permission':
            $message = "You do not have edit permissions on that quiz.";
            break;
        default:
            $message = 'Unknown Error';
    }
} else {
    $message = "";
}

/* User can only edit quiz information if IS_ENABLED is set to inactive so as not to disrupt users. 
     * Check if IS_ENABLED is already set for validation in editing details, questions, editors, takers.
     */
$_SESSION['CURRENT_EDIT_QUIZ_ID'] = $quizId;
$isEnabledState = editQuizLogic::isQuizEnabled($quizId);
if (is_null($isEnabledState)){
    $_SESSION['CURRENT_EDIT_QUIZ_ID'] = NULL; //bomb out
} else {
    $_SESSION["IS_QUIZ_ENABLED"] = $isEnabledState; //set the state (true or false)
    $enableSubMenuLinks = $isEnabledState;
}


if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(!empty($_POST["quizid"])){
        $quizIDPost = filter_input(INPUT_POST, "quizid");
        $_SESSION['CURRENT_EDIT_QUIZ_ID'] = $quizIDPost;
    } 
    $quizUrl = quizLogic::returnQuizUrl($quizIDGet);
    
    //If ENABLE button is pushed, update row in database
    if (isset($_POST['confirmEnabled'])) {
        //now quiz has to have at least 2 questions & 1 answer
        //has to have a question ans teh start and at the end of eah of teh branches
        //has to have no answers adjancent or questions adjancent
        $problemQuestionAnswersArray = editQuestionLogic::returnProblemQuestionAnswersIntegrityCheck($quizId);
        if (empty($problemQuestionAnswersArray)) { //an array with no entries
            //TO DO if changes
            if(isset($_SESSION['CURRENT_EDIT_QUIZ_EDITED'])){ //if the session was set, ergo, was edited, otherwise is NULL
                $_SESSION['CURRENT_EDIT_QUIZ_EDITED'] = NULL; //edited changes are now commmited
            }
            $setColumnsArray = array(
                "IS_ENABLED" => "1"
            );
            $whereValuesArray = array(
                "QUIZ_ID" => $quizId
            );
            $dbLogic->updateSetWhere("QUIZ", $setColumnsArray, $whereValuesArray);
            $confirmActive = "Quiz is now ENABLED, and CAN be attempted by users.";
            //Set flag variable that is checked before commiting edits in other pages
            $_SESSION["IS_QUIZ_ENABLED"] = true;
            $enableSubMenuLinks = true;
            quizLogic::setQuizToConsistentState($dbLogic, $quizId);
        } else {
            //errors
            /* parsing
             * ['problemCode']          = short problem code "first-not-a-question", "add-more-questions", 
             *                                               "add-more-answers", "end-is-a-answer", "adjacent-question", "adjacent-answer"
             * ['shortId']              = the short question or answer id to be displayed
             * ['questionOrAnswerId']   = the real question or answer id
             * 
             * into 
             * 
             * ['problem'] = $problemQuestionAnswer['shortId'] + issue
             * ['fix'] = url + ['questionOrAnswerId']
             */
            //format the list into a friendly way
            $invalidQuestionAnswersDisplayArray = array();
            $i = 0;
            foreach ($problemQuestionAnswersArray as $problemQuestionAnswer) {
                switch($problemQuestionAnswer['problemCode']) {
                    case "first-not-a-question":
                        $invalidQuestionAnswersDisplayArray[$i]['problem'] = "A: ".$problemQuestionAnswer['shortId']. " - The first node is not a question";
                        $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                                "To fix please <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl\"".">add a question at the top</a>";
                        break;
                    case "add-more-questions":
                        $invalidQuestionAnswersDisplayArray[$i]['problem'] = "$numberOfQuestions questions is not enough, more must be added";
                        $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                                "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl\"".">click here</a> add a question at the top";
                        break;
                    case "add-more-answers":
                        $invalidQuestionAnswersDisplayArray[$i]['problem'] = "$numberOfQuestions questions is not enough, more must be added";
                        $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                                "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl\"".">click here</a> add a question at the edit questions screen";
                        break;
                    case "end-is-a-answer":
                        $invalidQuestionAnswersDisplayArray[$i]['problem'] = "A: ".$problemQuestionAnswer['shortId']. " - The end node of branch must be of type question, add one or set answer to jump to question (questions with no answers are end quiz summary screens)";
                        $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                                "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl\"".">click here</a> to add a question at the edit questions screen";
                        break;
                    case "adjacent-question":
                        $invalidQuestionAnswersDisplayArray[$i]['problem'] = "Q: ".$problemQuestionAnswer['shortId']. " - You cannot have a question linked to another question with no answer inbetween";
                        $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                                "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-answer.php$quizUrl&question=".$problemQuestionAnswer['questionOrAnswerId']."\">click here</a> add a answer inbetween (after this question)";
                        break;
                    case "adjacent-answer":
                        $invalidQuestionAnswersDisplayArray[$i]['problem'] = "A: ".$problemQuestionAnswer['shortId']. " - You cannot have an answer linked to another answer with no question inbetween";
                        $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                                "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl&answer=".$problemQuestionAnswer['questionOrAnswerId']."\"".">click here</a> add a question inbetween (after this answer)";
                        break;
                }
            $i++;
            }
        }
        
    //If DISABLE button is pressed, update row in database 
    }else if(isset($_POST['confirmDisabled'])){
        
        $quizIDPost = filter_input(INPUT_POST, "quizID");

        $setColumnsArray = array(
            "IS_ENABLED" => "0"
        );
        $whereValuesArray = array(
            "QUIZ_ID" => $quizIDPost
        );
        $dbLogic->updateSetWhere("QUIZ", $setColumnsArray, $whereValuesArray);
        $confirmActive = "Quiz is now DISABLED, and CANNOT be attempted by users.";
        //Set flag variable that is checked before commiting edits in other pages
        $_SESSION["IS_QUIZ_ENABLED"] = false;
        $enableSubMenuLinks = false; //no menu links
    } else {
        //Page is being loaded from edit-quiz-list with quizid selected    
        header('Location: ' . CONFIG_ROOT_URL . '/edit-quiz.php'.$quizUrl);
        stop();
    }
    include('edit-quiz-view.php');
//If coming from home page, display quiz list for user to select
}else if(is_null($quizIDGet)){
    
    
    //Retrieve the most current versions of quizzes for which the user is an editor
    $uid = $_SESSION["username"];
    //where coloumns

    $whereValuesArray = array(
        "user_USERNAME" => "$uid"
        );
    $whereColumnsArray = array(
        "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID"
        );
    
    $quizEditId = $dbLogic->selectWithColumnsGroupBy("SHARED_QUIZ_ID, MAX(QUIZ_ID) AS QUIZ_ID", "quiz, editor", 
        $whereValuesArray, $whereColumnsArray, 'SHARED_QUIZ_ID', false);
    
    
   /*Run another set of queries using the QUIZ_ID and SHARED_QUIZ_ID retrieved above to obtain the name
     *of the most current version of the quiz, as well as the most current version. Include QUIZ_ID and
     *SHARED_QUIZ_ID in returned results so all fields can be accessed from the nameArray array on stats-view.
     *WARNING** Simply running the single query above and including the additional fields does not result in 
     *the correct QUIZ_NAME being linked with the correct QUIZ_ID due to the MAX requirement. Second query is required.
     */
    $nameArray = array();
    foreach($quizEditId as $columnResult){
        
        $wherevalues2 = array(
            "user_USERNAME" => "$uid"
        );
        
        $wherevalues3 = array(
            "shared_SHARED_QUIZ_ID" => "SHARED_QUIZ_ID",
            "SHARED_QUIZ_ID" => $columnResult['SHARED_QUIZ_ID'],
            "QUIZ_ID" => $columnResult['QUIZ_ID']
        );
        
        $quizNameArray = $dbLogic->selectWithColumnsGroupBy("QUIZ_NAME, DESCRIPTION, SHARED_QUIZ_ID, QUIZ_ID, MAX(VERSION) AS VERSION",
                'quiz, editor', $wherevalues2, $wherevalues3, 'SHARED_QUIZ_ID', false);
        
        //Merge the array as $quizNameArray will be overwritten each iteration of foreach loop
        //Store the values inside nameArray which will be unaffected by foreach loop as it merges the values onto itself
            
        $nameArray = array_merge($nameArray, $quizNameArray);          
               
    }
    include('edit-quiz-list-view.php');
}else{
    $quizId = quizLogic::getQuizIdFromUrlElseReturnToEditQuiz();
    $sharedQuizId = quizLogic::returnSharedQuizID($quizId);
    $quizUrl = quizLogic::returnQuizUrl($sharedQuizId);
    $username = $userLogic->getUsername();
    quizLogic::canUserEditQuizElseReturnToEditQuiz($sharedQuizId, $username);
    //get request and the quiz was specified
    include('edit-quiz-view.php');
}   
    
