<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Questions');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCustomHeaders('<style>
    .edit-question-area{
        background-color: #E2E2E2;
        width: 80%;
        height: 30em;
        float: left;
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
            <table>
                <?php
                //logic flwed, need to fix up later
                /* output i'm thinking
                 * --------------------------
                 * |    |     | Q1 |    |    |
                 * |    |  /  |    | \  |    |
                 * | Q2 |     |    |    | Q3 |
                 * --------------------------
                 */
                
                /* joshua's shit 
                echo "<pre>";
                print_r($tableArrayPrinted);
                echo "</pre>";
                $i2 = 0;
                foreach ($tableArrayPrinted as $quiz){
                    echo "<tr>" . PHP_EOL;
                    for ($i=0;$i<$wide;$i++){ 
                        echo "<td>";
                        $printed = false;
                        
                        for ($i3=0;$i3<count($tableArrayPrinted[$i2]);$i3++){
                            if ($i == $tableArrayPrinted[$i2][$i3][1]){
                                echo "&nbsp" . $tableArrayPrinted[$i2][$i3][0] . "&nbsp";
                                $printed = true;
                            }
                        }
                        if ($printed == false){
                            echo "&nbsp&nbsp&nbsp";
                        }
                        
                        echo "</td>". PHP_EOL;
                    }
                    echo "</tr>";
                    $i2++;
                }*/
                ?>   
            </table>
            
            
        
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