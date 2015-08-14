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
    </style>');
$templateLogic->startBody();
?>
<div class="edit-question-area">
    <?php if (count($quizData) > 0) { // if there are questions ?>
        <form>
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
                $i2 = 0;
                foreach ($quizData as $quiz){
                    echo "<tr>";
                    for ($i=0;$i<$wide;$i++){ 
                        echo "<td>";
                            if ($i % 2 == 1) { //if true if odd
                                if (array_key_exists($i2, $whichCell) && $i == $whichCell[$i2]){
                                    echo "Q " . $quiz["QUESTION_ID"] ;
                                } else {
                                    echo "&nbsp&nbsp&nbsp";
                                }
                            } else {
                                echo "&nbsp&nbsp&nbsp";
                            }
                        echo "</td>";
                    }
                    //print_r($quiz);

                    echo "</tr>";
                    $i2++;
                } ?>   
            </table>


        </form>
    <?php } else { //no questions ?>
    <p> There are no questions on this quiz, How about adding some? </p>
    <p> <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question/add-question.php?quiz=' . $quizIDGet) ?>">
            Add Questions
        </a>
    </p>
        
    <?php } ?>

    
</div>
<div class="edit-question-sidebar">
    <h2>Operations</h2>
    <p><a href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question/add-question.php?quiz=' . $quizIDGet); ?>">Add Question</a> 
    <br />
    <br />
<a href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question/remove-question.php?quiz=' . $quizIDGet); ?>">Remove Question</a> </p>
</div>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();