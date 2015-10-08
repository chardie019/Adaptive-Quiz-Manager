<?php
class editQuizViewLogic {
    /**
     * formats the the problem & explanations and urls to be used in edit quiz
     * 
     * @param array $problemQuestionAnswersArray the output from "editQuestionLogic::returnProblemQuestionAnswersIntegrityCheck"
     * @param string the quiz url to be be appended to the ROOT_URL constant
     * @return string ['problem'] + ['fix'] array.  expanation and url to be displayed
     */
    public static function formatProblemQuizArray (array $problemQuestionAnswersArray, $quizUrl){
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
                    $invalidQuestionAnswersDisplayArray[$i]['problem'] = "A: ".$problemQuestionAnswer['shortId']. " - The first node is not a question.";
                    $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                            "To fix please <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl\"".">add a question</a> at the top.";
                    break;
                case "add-more-questions":
                    $invalidQuestionAnswersDisplayArray[$i]['problem'] = "There is not enough questions, ensure there must be least two questions (start and end summary screen).";
                    $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                            "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl\"".">click here</a> to add a question at the top.";
                    break;
                case "add-more-answers":
                    $invalidQuestionAnswersDisplayArray[$i]['problem'] = "There is not enough answers, ensure there is at least one answer.";
                    $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                            "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl\"".">click here</a> to add a answer at the edit questions screen.";
                    break;
                case "end-is-a-answer":
                    $invalidQuestionAnswersDisplayArray[$i]['problem'] = "A: ".$problemQuestionAnswer['shortId']. " - The end node of branch must be of type question, add one or set answer to jump to question (questions with no answers are end quiz summary screens).";
                    $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                            "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizUrl\"".">click here</a> to add a question at the edit questions screen.";
                    break;
                case "adjacent-question":
                    $invalidQuestionAnswersDisplayArray[$i]['problem'] = "Q: ".$problemQuestionAnswer['shortId']. " - You cannot have a question linked to another question with no answer inbetween.";
                    $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                            "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-answer.php$quizUrl&question=".$problemQuestionAnswer['questionOrAnswerId']."\">click here</a> to add a answer inbetween (after this question).";
                    break;
                case "adjacent-answer":
                    $invalidQuestionAnswersDisplayArray[$i]['problem'] = "A: ".$problemQuestionAnswer['shortId']. " - You cannot have an answer linked to another answer with no question inbetween.";
                    $invalidQuestionAnswersDisplayArray[$i]['fix'] = 
                            "To fix <a href=\"". CONFIG_ROOT_URL . "/edit-quiz/edit-question/add-question.php$quizUrl&answer=".$problemQuestionAnswer['questionOrAnswerId']."\"".">click here</a> to add a question inbetween (after this answer).";
                    break;
            }
        $i++;
        }
    return $invalidQuestionAnswersDisplayArray;
    }
}