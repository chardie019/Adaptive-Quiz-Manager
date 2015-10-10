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
        $sharedQuizId = quizLogic::returnSharedQuizID($quizId);
        $target_dir = STYLES_QUIZ_IMAGES_LOCATION . "/$sharedQuizId/";
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
        $sharedQuizId = quizLogic::returnSharedQuizID($quizId);
        $target_dir = STYLES_QUIZ_IMAGES_LOCATION_DIR . "/$sharedQuizId/";
        if (is_null($targetFileName)){
            return $target_dir;
        } else {
            return $target_dir . $targetFileName;
        }
    }
    /**
     * Uploads an image for the quiz question
     * 
     * @param array $filesUpload The file post array $_FILES
     * @param string $imageFieldName The name of the image upload's field being uploaded
     * @param string $quizId The id of the quiz assoicated
     * @return array|boolean false on fail. otherwise ['imageUploadError'] is a message and 
     * ['imageAltError'] is the alt error message
     * ['targetDir'] is the upload directory
     */
    public static function handleImageUploadValidation($filesUpload, $imageFieldName, $quizId, $questionAlt) {
        //Validate Image upload
        //Double \\ is needed at the end of path to cancel out the single \ effect leading into "
        //$target_dir = "C:\xampp\htdocs\aqm\data\quiz-images\\"; 
        $target_dir = self::returnRealImageFilePath($quizId);
        $targetFileName = basename($_FILES[$imageFieldName]["name"]);
        $result['targetDir'] = $target_dir;
        $target_file = $target_dir . $targetFileName;
        $uploadOk = 1;
        $noError = 1;
        $noFileExistError = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        //check image doesn't voilate php.ini's rules
        if ($_FILES[$imageFieldName]['error'] != 0) {
            $result['imageUploadError'] = $_FILES[$imageFieldName]['error'];
            $uploadOk = 0;
        }
        
        // Check if image file is an actual image or fake image
        if ($uploadOk === 1 && createPath($target_dir) && is_uploaded_file($filesUpload["questionImageUpload"]["tmp_name"])){
            $check = getimagesize($filesUpload["questionImageUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $result['imageUploadError'] = "Error procession the file, are you sure it's an image?";
                $uploadOk = 0;
            }
            // Check if file already exists inside folders
            if (file_exists($target_file)) {
                $result['imageUploadError'] = "The name of your image may be alrady taken in this quiz. Try renaming the file.";
                $uploadOk = 0;
                $noFileExistError = 0;
            }
            // Check file size is smaller than 500kb, can change this later
            if ($filesUpload["questionImageUpload"]["size"] > 5000000) { //5MB
                $result['imageUploadError'] = "The image must be less than 5 MB.";
                $uploadOk = 0;
            }
            // Allow certain image file types only *Stop people uploading other file types e.g. pdf
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $result['imageUploadError'] = "The Image can only be .jpg, .png, .jpeg and .gif file types.";
                $uploadOk = 0;
            }
            //only check ALT text if there is an image (which is optional)
            if($questionAlt == " " || $questionAlt == "" || $questionAlt == NULL){
                $result['imageAltError'] = "Error: Please enter alternative text to the question more accessible.";
                $noError = 0;
            }
            //still good
            if ($uploadOk == 1){
                if (move_uploaded_file($filesUpload["questionImageUpload"]["tmp_name"], $target_file)) {
                    //uploaded
                } else { //nope it failed
                    $uploadOk  = 0;
                }
            }
        }
        // Check if $uploadOk is set to 0 by an upload error. Exit if true.
        if ($uploadOk == 0 || $noError = 0) {
            if ($uploadOk == 1) {
                $result['imageUploadError'] = "There was another unrelated error, please upload again after fixing it.";
            }
            if ($noFileExistError == 1){ //wasnt a files exist error, so delete it
               unlink($target_file); //delete the file 
            }
            $result['result'] = false;
        } else{
            $result['result'] = true;
        }
        if (!isset($result['imageUploadError'])){$result['imageUploadError'] = "";}
        if (!isset($result['imageAltError'])){$result['imageAltError'] = "";}
        if (!isset($result['imageUploadError'])){$result['imageUploadError'] = "";}
        return $result; //retrun the array now
    }
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