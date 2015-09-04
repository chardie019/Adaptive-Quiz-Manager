<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Questions');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCustomHeaders('
<style>
    .edit-question-area{
        background-color: #E2E2E2;
        width: 80%;
        height: 30em;
        float: left;
        overflow-y: scroll;
    }
    .answer {
        background-color: burlywood;
    }
    .edit-question-sidebar {
        float: right;
        width: 15%;
        padding-left: 2em;
    }
    table, td {
        border: 1px solid black;
        width: 100%;
    }
    .nested {
        padding-left: 2em;
    }
    /* non-js styles */
    li 
    { 
        position: relative; 
        margin-left: -15px;
        list-style: none;
    } 
    .question-list {
        border-left: black 1px solid;
    }
    /* remove non-js styles */
    .jstree-children .question-list {
        border-left: initial;
    }
    .jstree-children li 
    { 
        position: initial; 
        margin-left: initial;
        list-style: initial;
    } 
    .jstree-children .answer {
        background-color: initial;
    }
    .jstree-children .question {
        font-weight: bold;
    }
    </style>');
$templateLogic->startBody();
?>
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?quiz=' . $quizIDGet; ?>">
    
    <?php if($displayMessage == "initalQuestion"){ ?>
    <div><span class="inputError">No Answer was selected, did you mean to add the first question? <br />
        <a class="mybutton" href="<?php echo CONFIG_ROOT_URL . '/edit-quiz/question/add-initial-question.php?quiz=' . $quizIDGet ?>">Add Initial Question</a>
        </span></div>
    <?php } //end of display question ?>
    <div><span class="inputError"><?php echo ($selectionError); ?></span></div>
<div class="edit-question-area">
    <?php if (count($quizData) > 0) { // if there are questions ?>
    
    	<div id="myjstree" class="demo">
<?php

function build_tree($arrs, $parent_id="", $level=0) {
    $listNum = 0; //open first only
    foreach ($arrs as $arr) {
        if ($arr['PARENT_ID'] == $parent_id) {
                 if ($arr['TYPE'] == "question"){
                    $typeItem = "question";
                    if($listNum == 0){
                        $addClass = " jstree-open";
                    }else {$addClass = "";}
                    $listNum++;
                    $typeList= "question-list". $addClass;
                    $jsTreeType = "question";
                    $letter = "Q";
                    $item = $letter . ":  " . $arr['question_QUESTION_ID']." - ".$arr['QUESTION'];
                    $id = $letter . $arr['question_QUESTION_ID'];
                    $value = $arr['question_QUESTION_ID'];
                } else {
                    $typeItem = "answer";
                    $typeList = "answer-list";
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
            build_tree($arrs, $arr['CONNECTION_ID'], $level+1);
            echo "</li>".PHP_EOL."</ul>";
        }
    }
}

build_tree($arrs);
?>
</div>
       
    <?php } else { //no questions ?>
    <p> There are no questions on this quiz, How about adding some? </p>
    <p> <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question/add-question.php?quiz=' . $quizIDGet) ?>">
            Add Questions
        </a>
    </p>
        
    <?php } ?>

    
</div>
<div class="edit-question-sidebar">
    <p>
        <input class="mybutton" type="submit" name="inspect" value="Inspect" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="addQuestion" value="Add Question" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="removeQuestion" value="Remove Question" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="addAnswer" value="Add Answer" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="removeAnswer" value="Remove Answer" />
        <br />
        <br />
        <input class="mybutton" type="reset" value="Clear" />
    </p>
</div>
</form>

<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(
        '<script>
        $(function () {
            $("#myjstree").jstree({
                "types": {
                    "answer": {
                        "icon": "'. STYLES_THIRD_PARTY_LOCATION .'/jstree/themes/default/answer.png"
                    },
                    "question": {
                        "icon": "'. STYLES_THIRD_PARTY_LOCATION .'/jstree/themes/default/question.png"
                    },
                    "loop": {
                        "icon": "'. STYLES_THIRD_PARTY_LOCATION .'/jstree/themes/default/loop.png"
                    }
                },
                "plugins" : [ "types" ]
            });
            // open all questions
            $("#myjstree").on("ready.jstree", function() {
                /*$("#myjstree").jstree("open_all"); */         
            });
            $("#myjstree").bind(
                "select_node.jstree", function (evt, data) {
                //console.log($("#myjstree").jstree("get_selected"));
                var CurrentNode = $("#myjstree").jstree("get_selected");
                console.log($("#"+CurrentNode).find("input[type=radio]:first")[0]);
//              //$("#divtree").jstree("get_selected")
                //remove checks
                $("#myjstree").find("input[type=radio]").prop("checked", false);
                var r = $("#"+CurrentNode).find("input[type=radio]:first")[0];
                $(r).prop("checked", true);
            });
            
       });
        </script>');
//html
echo $templateLogic->render();