<?php
class takeQuizListLogic extends takeQuizLogic {
    /**
     * Returns an array for the for the quiz list page
     * 
     * @param string $uid The username to compare against for takers
     */
    public static function getQuizList ($uid) {
        //Get current date to check for open quizzes
        $dateCheck = date('Y-m-d H:i:s');
        $dbLogic = new DB();
        //SELECT SHARED_QUIZ_ID, QUIZ_NAME, DESCRIPTION FROM 
        //(SELECT SHARED_QUIZ_ID, QUIZ_NAME, DESCRIPTION, IS_PUBLIC, DATE_CLOSED, 
        //DATE_OPEN, IS_ENABLED FROM quiz ORDER BY VERSION desc) as TEMP_TABLE 
        //LEFT JOIN taker ON SHARED_QUIZ_ID = shared_SHARED_QUIZ_ID 
        //WHERE (IS_PUBLIC = '1' OR user_USERNAME = 'testuser') AND IS_ENABLED = '1' AND 
        //DATE_OPEN < '2015-09-18 23:57:33' AND (DATE_CLOSED IS NULL OR DATE_CLOSED > '2015-09-18 23:57:33') 
        //GROUP BY SHARED_QUIZ_ID
        $whereValues = array(
            "IS_ENABLED" => '1'
        );
        $whereValuesOr = array(
            "IS_PUBLIC" => '1',
            "user_USERNAME" => $uid
        );
            $joinWhere = array(
            "SHARED_QUIZ_ID" => "shared_SHARED_QUIZ_ID"
        );
        $whereDateAfter = array(
            "DATE_OPEN" => $dateCheck,
        );

        $whereDateBefore = array(               
            "DATE_CLOSED" => $dateCheck
        );
        return $dbLogic->selectOrderDescWithSelectWhereOrWithDateGroupBy(
            "SHARED_QUIZ_ID, QUIZ_NAME, DESCRIPTION", 
            "SHARED_QUIZ_ID, QUIZ_NAME, DESCRIPTION, IS_PUBLIC, DATE_CLOSED, DATE_OPEN, IS_ENABLED",
            "quiz", "VERSION",
            "taker", $joinWhere, $whereValuesOr, $whereValues, $whereDateAfter, $whereDateBefore, 'SHARED_QUIZ_ID',
            false
        );
    }
    
}