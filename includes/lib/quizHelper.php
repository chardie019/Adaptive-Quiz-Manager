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
     * Uploads an image for the quiz question
     * 
     * @param array $_FILES The file post array
     * @param string $targetFileName The name of the file being uploaded
     * @param string $quizId The id of the quiz assoicated
     * @return array ['result'] is false on fail. otherwise is true and ['imageUploadError'] is a message and ['imageAltError'] is also a message
     *                      ['targetDir'] is the upload directory
     */
    public static function handleImageUploadValidation($_FILES, $targetFileName, $quizId, $questionAlt) {
        //Validate Image upload
        //Double \\ is needed at the end of path to cancel out the single \ effect leading into "
        //$target_dir = "C:\xampp\htdocs\aqm\data\quiz-images\\"; 
        $target_dir = STYLES_QUIZ_IMAGES_LOCATION_DIR . "/$quizId/";  
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
        return $result;
    }
    /**
     * Prints a tree of question answers using ul and li's (and jstree)
     * 
     * Note: actual printing indone an another private function "build_tree"
     * 
     * @param string $quizId The quiz to print from
     * @param DB $dbLogic reuse tha current connection to the Database
     * @return void
     */
    public static function prepare_tree($quizId, DB $dbLogic){
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

        return array_values($quizData); //aossoiative to simple array of values
    }
    /**
     * Prints a tree using ul and li's (and jstree)
     * 
     * @param array $arrs The Array to print and sort through
     * @param string $parent_id The prent ID to print from (optional, prints from root) [is also recurive]
     * @param integer $listNum where to open the jstree from (mostly just a internal variable, leave be)
     * @return void
     */
    public static function build_tree($arrs, $parent_id="", $listNum = 0 /*open first only*/) {
        foreach ($arrs as $arr) {
            if ($arr['PARENT_ID'] == $parent_id) {
                if($listNum <= 2){ //open first 2
                    $addClass = " jstree-open";
                }else {$addClass = "";}
                $listNum++;
                if ($arr['TYPE'] == "question"){
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
                   $typeItem += " loop";
                   foreach ($arrs as $arrLoopChild) {
                       if ($arrLoopChild['CONNECTION_ID'] == $arr['LOOP_CHILD_ID']) {
                           if ($arrLoopChild['TYPE'] == "question"){
                               $letterLoopChild = "Q";
                           } else {
                               $letterLoopChild = "A";
                           }
                       }
                   }
                   $item = $item . " (loop to " . $letterLoopChild . $arr['LOOP_CHILD_ID'].")";
                   $jsTreeType = "loop";
               }
                echo "<ul>" . PHP_EOL;
                echo "\t <li class=\"" . $typeList . '" data-jstree=\'{"type":"'.$jsTreeType.'"}\'>'
                        . '<input type="radio" name="'.$typeItem.'" id="'.$id.'" value="'.$value.'" />'
                        . PHP_EOL . '<label class="' . $typeItem . '" for="'.$id.'">'. $item . '</label>';
                self::build_tree($arrs, $arr['CONNECTION_ID'], $listNum);
                echo "</li>".PHP_EOL."</ul>";
            }
        }
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