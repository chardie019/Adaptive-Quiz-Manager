<?php
class editQuizLogic extends quizLogic {
    /**
     * Returns teh state if quiz is enabled or not
     * 
     * @param type $quizId
     * @return boolean|NULL true is enabled, false if not and NULL if unknown (could not find quiz in db)
     */
    public static function isQuizEnabled($quizId){
        $dbLogic = new DB();
        $whereValuesArray = array(
            "QUIZ_ID" => $_SESSION['CURRENT_EDIT_QUIZ_ID']
        );
        $isEnabledStateArray = $dbLogic->select("IS_ENABLED", "QUIZ", $whereValuesArray, true);
        if (!empty($isEnabledStateArray)){
            if($isEnabledStateArray['IS_ENABLED'] == '1'){
                return true;

            }else{
                return false;
            }
        } else {
            return NULL;
        }
    }
}

