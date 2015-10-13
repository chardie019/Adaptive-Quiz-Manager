<?php
/**
 * Stores Miscellaneous quiz functions (mostly the tree functions)
 */

class quizMiscLogic extends quizHelper {
    /**
     * returns html tree of question answers using ul and li's (and jstree)
     * 
     * Note: actual printing indone an another private function "buildTree"
     * 
     * @param $parentId
     * @param string $printType [optional] decide which parts are printed - "NULL(default), "questions" & "none"
     * @return string the html code for the tree
     */
    public static function prepareTree($quizId, $parentId = "", $printType = NULL){
        $dbLogic = new dbLogic();
        //Create array for Outer join
        $where = array(
            "quiz_QUIZ_ID" => "$quizId" 
        );
        $jointable = array(
            "QUESTION_ID" => "question_QUESTION_ID"
        );
        $jointable2 = array(
            "ANSWER_ID" => "answer_ANSWER_ID"
        );

        //Insert quiz into database
        $quizData = ($dbLogic->selectFullOuterJoinOrder("*", "question_answer", $where, "question", $jointable, "answer", $jointable2, "depth", false));

        //aossoiative to simple array of values
        return self::buildTree(array_values($quizData), $parentId, $printType);
    }
    /**
     * Prints a tree using ul and li's (and jstree)
     * 
     * @param array $arrs The Array to print and sort through
     * @param string $parentId The prent ID to print from (optional, prints from root) [is also recurive]
     * @param string $printType decide which parts are printed - "NULL(default), "questions" & "none"
     * @param integer $listNum where to open the jstree from (mostly just a internal variable, leave be), default is zero
     * @param string $returnHtml internal string to build up html code
     * @return returns the HTML code
     */
    public static function buildTree($arrs, $parentId = "", $printType = NULL, $listNum = 0, $returnHtml = "") {
        switch($printType){
            case "questions":
                $printQuestions = true;
                $printAnswers = false;
                break;
            case "answers":
                $printQuestions = false;
                $printAnswers = true;
                break;
            case "none":
                $printQuestions = false;
                $printAnswers = false;
                break;
            case NULL:
            default:
                $printQuestions = true;
                $printAnswers = true;
                break;                
        }
        foreach ($arrs as $arr) {
            if ($arr['PARENT_ID'] == $parentId) {
                if($listNum <= 2){ //open first 2
                    $addClass = " jstree-open";
                }else {$addClass = "";}
                $listNum++;
                if ($arr['TYPE'] == "question") {
                    $addClass = " jstree-open"; //questions are always expanded
                    $typeItem = "question";
                    $typeList= "question-list". $addClass;
                    $jsTreeType = "question";
                    $letter = "Q";
                    $item = $letter . ":  " . $arr['SHORT_QUESTION_ID']." - ".$arr['QUESTION'];
                    $id = $letter . $arr['question_QUESTION_ID'];
                    $value = $arr['question_QUESTION_ID'];
               } else {
                    $typeItem = "answer";
                    $typeList = "answer-list". $addClass;
                    $jsTreeType = "answer";
                    $letter = "A";
                    $item = $letter . ":  " . $arr['SHORT_ANSWER_ID']." - ".$arr['ANSWER'];
                    $id = $letter . $arr['answer_ANSWER_ID'];
                    $value = $arr['answer_ANSWER_ID'];
               }
               //to do, loop the tree
               if ($arr['LOOP_CHILD_ID'] != NULL){
                   $loopQuestionId = self::getLoopQuestionId($arr, $arrs);
                   $item = $item . " (jump to Q" . $loopQuestionId.")";
                   $jsTreeType = "loop";
               }
                $returnHtml .= "<ul>" . PHP_EOL;
                $returnHtml .= "\t <li class=\"" . $typeList . '" data-jstree=\'{"type":"'.$jsTreeType.'"}\'>' . PHP_EOL;
                if (($typeItem  == "answer" && $printAnswers == true) || ($typeItem  == "question" && $printQuestions == true)){
                    //print the radio box depending on input
                    $returnHtml .= '<input type="radio" name="'.$typeItem.'" id="'.$id.'" value="'.$value.'" />' . PHP_EOL;
                }
                $returnHtml .= '<label class="' . $typeItem . '" for="'.$id.'">'. $item . '</label>';
                $returnHtml = self::buildTree($arrs, $arr['CONNECTION_ID'], $printType, $listNum, $returnHtml);
                $returnHtml .= "</li>".  \PHP_EOL ."</ul>";
            }
        }
        return $returnHtml;
    }
    public static function printRunJstreeCssCode(){ 
        ob_start(); ?>
        <script type="text/javascript">
            $(function () {
                $("#myjstree").jstree({
                    "types": {
                        "answer": {
                            "icon": "<?php echo STYLES_THIRD_PARTY_LOCATION;?>/jstree/themes/default/answer.png"
                        },
                        "question": {
                            "icon": "<?php echo STYLES_THIRD_PARTY_LOCATION; ?>/jstree/themes/default/question.png"
                        },
                        "loop": {
                            "icon": "<?php echo STYLES_THIRD_PARTY_LOCATION; ?>/jstree/themes/default/loop.png"
                        }
                    },
                    "plugins" : [ "types" ]
                });
                /* open all questions */
                $("#myjstree").on("ready.jstree", function() {
                    /*$("#myjstree").jstree("open_all"); */         
                })
                $("#myjstree").bind(
                    "select_node.jstree", function (evt, data) {
                    var CurrentNode = $("#myjstree").jstree("get_selected");
                    /*remove checks*/
                    $("#myjstree").find("input[type=radio]").prop("checked", false);
                    var r = $("#"+CurrentNode).find("input[type=radio]:first")[0];
                    $(r).prop("checked", true);
                });

            });
        </script>
    <?php return ob_get_clean();
    }
    /**
     * Gets teh ID of teh gets to which the arryrow is linked to
     * 
     * @param array $singleArrayRow a an aossicative array from "buildTree"
     * @param arry $array the array to search where the loop matchs 
     * @return string the ID of the question.
     */
    private static function getLoopQuestionId ($singleArrayRow, $array) {
        foreach ($array as $arrLoopChild) {
            if ($arrLoopChild['CONNECTION_ID'] == $singleArrayRow['LOOP_CHILD_ID']) {
                $questionId = $arrLoopChild['SHORT_QUESTION_ID'];
            }
        }
        return $questionId;
    }
}
