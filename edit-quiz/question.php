<?php
    
    
// include php files here 
//kick the user back if they haven't selected quiz
require_once("../includes/config.php");
include ("check-quiz-id-edit-quiz.php");
// end of php file inclusion

$quizIDGet = filter_input(INPUT_GET, "quiz");
$noQuestionSelectedError = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") { //pastt the appropiate page
    if (isset($_POST['addQuestion']) && isset($_POST['question'])) {
            include("add-question.php");
            exit;
    } else if (isset($_POST['removeQuestion']) && isset($_POST['question'])) {
            include('remove-question.php');
            exit;
    } else if (isset($_POST['removeQuestion']) || isset($_POST['addQuestion'])){
            $noQuestionSelectedError="Please choose a question to edit before continuing.";

    } else if (isset($_POST['addAnswer']) && isset($_POST['answer'])){
            include('add-answer.php');
            exit;
    } else if (isset($_POST['removeAnswer']) && isset($_POST['answer'])) {
            include('remove-answer.php');
            exit;
    } else if (isset($_POST['removeAnswer']) || isset($_POST['addAnswer'])){
            $noQuestionSelectedError="Please choose a answer to edit before continuing.";

    } else {
        //no button pressed, reload page
        $noQuestionSelectedError="There was an error with your selection.";
    }
}

//if if there are any questions
$dbLogic = new DB();

        //Create array for insert->quiz
        $where = array(
            "quiz_QUIZ_ID" => "$quizIDGet" 
        );
        $whereAnd = array(
            "QUESTION_ID" => "question_QUESTION_ID" 
        );

        //Insert quiz into database
        $quizData = ($dbLogic->selectDistinct("*", "question, answer", $where, $whereAnd, false));
        
        /* joshua'a shit
        
        if (count($quizData) > 0) { //are quiz questions
            $tableData = array();
            $wide = 20;//todo, see how wide & high the tree structure is
            $high = 3;
            $quizLevels = 5;
            $tableArrayPrinted = array();

            $linkfound = true;
            $currentQuestion = 0;
            $manyconnectedTotal = 0;
            for ($highIterator=0;$highIterator < $high;$highIterator++){
                
                //$linkfound = false; //figuring out if the quiz is not corrupt
                if ($highIterator % 2 == 0){ //if even (from zero)
                    if ($highIterator == 0){ //start from middle
                        $tableArrayPrinted[] = array(array ("Q" . $quizData[$currentQuestion]['QUESTION_ID'], round($wide / 2)));
                    } else { //not the first
                        //how many are connected
                        
                        //$tableArrayPrinted[] = array(array ("Q" . $quizData[$currentQuestion]['QUESTION_ID'], round($wide / 2)));
                        foreach ($tableArrayPrinted[$highIterator - 2] as $previousIterator){
                            $manyconnected = 0;
                            //$manyconnectedTotal
                            for ($connectedIterator=0; $connectedIterator<count($quizData);$connectedIterator++){
                                if ($quizData[$manyconnectedTotal]['QUESTION_ID'] == $quizData[$connectedIterator]['QUESTION_ID']){
                                    //do stuff
                                    $manyconnected++; //slashes
                                }
                            }
                            //
                            for ($i=0;$i<$manyconnected;$i++){  //for each slash / connection
                                
                                
                            }
                            
                        }
                        
                    }
                } else { //even

                    
//how many are connected
                    
                        //for echo question on teh level
                    // count($previousIterator)

                        $manyconnected = 0;
                        for ($connectedIterator=0; $connectedIterator<count($quizData);$connectedIterator++){
                            if ($quizData[$currentQuestion]['QUESTION_ID'] == $quizData[$connectedIterator]['QUESTION_ID']){
                                $manyconnected++; //slashes
                            }
                        }
                        $previousIterator = $tableArrayPrinted[$highIterator - 1];
                        //for each connected, print slash
                        if ($manyconnected % 2 == 0) { //even
                            $space = 1;
                            for ($i=0;$i<count($previousIterator);$i++){
                                $position = $previousIterator[$i][1];
                                $tableArrayPrinted[$highIterator] = array (
                                    array("/", $position - $space),
                                    array("\\", $position + $space) //backwards slash
                                );
                                $space+=2;
                            }    
                        } else { //odd
                            $space = 2;
                            for ($i=0;$i<count($previousIterator);$i++){
                                $position = $previousIterator[$i][1];
                                //where the previous row's question is & put slashes (diagnional)
                                if ($i == 0) {
                                    array("|", $previousIterator[$i][1]);
                                } else {
                                    $tableArrayPrinted[$highIterator] = array (
                                        array("/", $previousIterator[$i][1] - $space), //forward slash
                                        array("\\", $previousIterator[$i][1] + $space) //backwards slash
                                    );
                                }
                            }
                            $space+=2;
                        }

                }
                //processing
                /*
                if link found {
                    $linkfound = true; //all good
                } else {
                    $questionMapError = "Sorry, there was a missing link, printing cancelled.";
                    break;
                }
                 *
                 */
        /*
                $manyconnected = 0;
                for ($connectedIterator=0; $connectedIterator<count($quizData);$connectedIterator++){
                    if ($quizData[$currentQuestion]['QUESTION_ID'] == $quizData[$connectedIterator]['QUESTION_ID']){
                        $manyconnected++; //slashes
                    }
                }
                $manyconnectedTotal+=$manyconnected;
            }
        } else {
            //no quiz results
        }
        
        
        
        
        
        
        /*
        for ($i=0;$i<$quizLevels;$i++){
            $whichCell[]  = round($wide / 2);
        }
        */
        



//html
include("question-view.php");