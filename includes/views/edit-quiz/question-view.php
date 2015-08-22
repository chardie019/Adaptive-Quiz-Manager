<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Questions');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCustomHeaders('
    <link rel="stylesheet" href="/aqm/data/jstree/themes/default/style.min.css" />
<style>
    .edit-question-area{
        background-color: #E2E2E2;
        width: 80%;
        height: 30em;
        float: left;
        overflow-y: scroll;
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
    </style>');
$templateLogic->startBody();
?>
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?quiz=' . $quizIDGet; ?>">
<div class="edit-question-area">
    <?php if (count($quizData) > 0) { // if there are questions ?>
    <p> Demo tree view, to see other draft, scroll below (no input yet on this tree, is on the other) </p>
    <p> to use , import \includes\project-notes\question_answer.sql table </p>
    
    	<div id="myjstree" class="demo">
<?php
//connect to mysql and select db
$conn = mysqli_connect('localhost', 'aqm', 'jc66882Dxc9D','aqm');

if( !empty($conn->connect_errno)) die("Error " . mysqli_error($conn));


//http://stackoverflow.com/a/15307555
$sql = "select * from question_answer order by depth;";
$result = mysqli_query($conn, $sql);

$arrs = array();

while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $arrs[] = $row;
}

function build_tree($arrs, $parent_id="", $level=0) {
    foreach ($arrs as $arr) {
        if ($arr['PARENT_ID'] == $parent_id) {
                 if ($arr['TYPE'] == "question"){
                    $typeItem = "question-item";
                    $typeList= "question-list";
                    $letter = "Q";
                    $item = $letter . ":  " . $arr['question_QUESTION_ID'];
                } else {
                    $typeItem = "answer-item";
                    $typeList = "answer-list";
                    $item = "A:  " . $arr['answer_ANSWER_ID'];
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
                }
            echo "<ul>\n";
            echo "\t <li class=\"" . $typeList . '"><span class="' . $typeItem . '">' . $item . '</span>';
            build_tree($arrs, $arr['CONNECTION_ID'], $level+1);
            echo "</li>\n</ul>";
        }
    }
}

build_tree($arrs);
?>
</div>

<script src="/aqm/data/jquery-1.11.2.min.js"></script>
	<script src="/aqm/data/jstree/jstree.min.js"></script>
        
        
        <script>
	// html demo
	$('#myjstree').jstree();
        $('#myjstree').on('ready.jstree', function() {
            $("#myjstree").jstree("open_all");          
        });
        </script>
    
    
    <p> (This will be replaced with a user friendly map/tree but submit values will be same though)</p>
    <div><span class="inputError"><?php echo ($noQuestionSelectedError); ?></span></div>
            <?php for ($i=0;$i<count($quizData);$i+=2){
                echo '<input type="radio" name="question" id="' . 'Q'.$quizData[$i]['QUESTION_ID'] . '" value="' . $quizData[$i]['QUESTION_ID'] . '"/>'
                        . PHP_EOL . '<label for="'.'Q'.$quizData[$i]['QUESTION_ID'] . '">' . $quizData[$i]['QUESTION'] . " | " 
                        . $quizData[$i]['CONTENT'] . '</label><br>';
                echo '<div class="nested"><input type="radio" name="answer" id="' . 'A'.$quizData[$i]['LINK'] . '" value="' . $quizData[$i]['LINK'] . '"/>'
                        . PHP_EOL . '<label for="'.'A'.$quizData[$i]['LINK'] . '">' . $quizData[$i]['LINK'] . ". " 
                        . $quizData[$i]['ANSWER'] . '</label></div>';
                echo '<div class="nested"><input type="radio" name="answer" id="' . 'A'.$quizData[$i+1]['LINK'] . '" value="' . $quizData[$i+1]['LINK'] . '"/>'
                        . PHP_EOL . '<label for="'.'A'.$quizData[$i+1]['LINK'] . '">' . $quizData[$i+1]['LINK'] . ". " 
                        . $quizData[$i+1]['ANSWER'] . '</label></div>';
            }
            ?> 
        
    <?php } else { //no questions ?>
    <p> There are no questions on this quiz, How about adding some? </p>
    <p> <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question/add-question.php?quiz=' . $quizIDGet) ?>">
            Add Questions
        </a>
    </p>
        
    <?php } ?>

    
</div>
<div class="edit-question-sidebar">
    <p><input class="mybutton" type="submit" name="addQuestion" value="Add Question" />
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
    <br />
    <br />
    <a class="mybutton" href="<?php echo CONFIG_ROOT_URL . '/edit-quiz/question/add-initial-question.php?quiz=' . $quizIDGet ?>">Add Initial Question</a></p>
</div>
</form>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();