<?php
/**
 * Class quizHelper
 *
 * Provides generic functions to support general functions
 * These functions usually upload a file etc
 */
class quizHelper
{
    /**
     * Returns the web image path
     * 
     * @param string $quizId the quiz the image belongs to
     * @param string $targetFileName The image filename
     * @return string The real file path and $targetFileName(in the same string, if provided eg C:\images\1.png
     */
    public static function returnWebImageFilePath ($quizId, $targetFileName = ""){
        $target_dir = STYLES_QUIZ_IMAGES_LOCATION . "/$quizId/";
        if ($targetFileName == ""){
            return $target_dir;
        }else if (is_null($targetFileName)){
            return NULL; //dont change it
        } else {
            return $target_dir . $targetFileName;
        }
    }
    /**
     * Return the local image path
     * 
     * @param string $quizId the quiz the image belongs to
     * @param string $targetFileName The image filename
     * @return string The real file path and $targetFileName(in the same string, if provided eg C:\images\1.png
     */
    public static function returnRealImageFilePath ($quizId, $targetFileName = NULL){
        $target_dir = STYLES_QUIZ_IMAGES_LOCATION_DIR . "/$quizId/";
        if (is_null($targetFileName)){
            return $target_dir;
        } else {
            return $target_dir . $targetFileName;
        }
    }
    /**
     * Uploads an image for the quiz question
     * 
     * @param array $_FILES The file post array
     * @param string $targetFileName The name of the file being uploaded
     * @param string $quizId The id of the quiz assoicated
     * @return array|boolean false on fail. otherwise ['imageUploadError'] is a message and 
     * ['imageAltError'] is the alt error message
     * ['targetDir'] is the upload directory
     */
    public static function handleImageUploadValidation($_FILES, $targetFileName, $quizId, $questionAlt) {
        //Validate Image upload
        //Double \\ is needed at the end of path to cancel out the single \ effect leading into "
        //$target_dir = "C:\xampp\htdocs\aqm\data\quiz-images\\"; 
        $target_dir = self::returnRealImageFilePath($quizId);
        $result['targetDir'] = $target_dir;
        $target_file = $target_dir . $targetFileName;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is an actual image or fake image
        if (createPath($target_dir) && is_uploaded_file($_FILES["questionImageUpload"]["tmp_name"])){
            $check = getimagesize($_FILES["questionImageUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
            // Check if file already exists inside folders
            if (file_exists($target_file)) {
                $uploadOk = 0;
            }
            // Check file size is smaller than 500kb, can change this later
            if ($_FILES["questionImageUpload"]["size"] > 5000000) { //5MB
                $uploadOk = 0;
            }
            // Allow certain image file types only *Stop people uploading other file types e.g. pdf
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $uploadOk = 0;
            }
            //only check ALT text if there is an image (which is optional)
            if($questionAlt == " " || $questionAlt == "" || $questionAlt == NULL){
                $result['imageAltError'] = "Error: Please enter alternative text to the question more accessible.";
                $uploadOk = 0;
            }
            //still good
            if ($uploadOk == 1){
                if (move_uploaded_file($_FILES["questionImageUpload"]["tmp_name"], $target_file)) {
                    //uploaded
                } else { //nope it failed
                    $uploadOk  = 0;
                }
            }
        }
        // Check if $uploadOk is set to 0 by an upload error. Exit if true.
        if ($uploadOk == 0) {
            $result['imageUploadError'] =  "Error: There was an error with your image upload. Please check the following: \n"
                    . "- File size is 500kb or less "
                    . "- File must be in .jpg, .png, .jpeg and .gif file types\n"
                    . "- The name of your file may be taken. Try renaming the file ";
            $result['result'] = false;
        } else{
            $result['result'] = true;
        }
        return $result; //retrun the array now
    }
    /**
     * Prints a tree of question answers using ul and li's (and jstree)
     * 
     * Note: actual printing indone an another private function "buildTree"
     * 
     * @param DB $dbLogic reuse tha current connection to the Database
     * @param string $quizId The quiz to print from
     * @return void
     */
    public static function prepareTree(DB $dbLogic, $quizId, $parentId = "", $printType = NULL){
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
     * @param type $printType decide which parts are printed - "NULL(default), "questions" & "none"
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
                    $item = $letter . ":  " . $arr['question_QUESTION_ID']." - ".$arr['QUESTION'];
                    $id = $letter . $arr['question_QUESTION_ID'];
                    $value = $arr['question_QUESTION_ID'];
               } else {
                    $typeItem = "answer";
                    $typeList = "answer-list". $addClass;
                    $jsTreeType = "answer";
                    $letter = "A";
                    $item = $letter . ":  " . $arr['answer_ANSWER_ID']." - ".$arr['ANSWER'];
                    $id = $letter . $arr['answer_ANSWER_ID'];
                    $value = $arr['answer_ANSWER_ID'];
               }
               //to do, loop the tree
               if ($arr['LOOP_CHILD_ID'] != NULL){
                   $loopQuestionId = self::getLoopQuestionId($arr, $arrs);
                   $item = $item . " (loop to Q" . $loopQuestionId.")";
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
                $questionId = $arrLoopChild['question_QUESTION_ID'];
            }
        }
        return $questionId;
    }
    public static function printRunJstreeCssCode(){ ob_start(); ?>
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
                    /*console.log($("#myjstree").jstree("get_selected"));*/
                    var CurrentNode = $("#myjstree").jstree("get_selected");
                    console.log($("#"+CurrentNode).find("input[type=radio]:first")[0]);
                    /*$("#divtree").jstree("get_selected")*/
                    /*remove checks*/
                    $("#myjstree").find("input[type=radio]").prop("checked", false);
                    var r = $("#"+CurrentNode).find("input[type=radio]:first")[0];
                    $(r).prop("checked", true);
                });

            });
        </script>
    <?php
    return ob_get_clean();
    }
}