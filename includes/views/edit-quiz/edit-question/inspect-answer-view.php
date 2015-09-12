<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Inspect Answer');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-quiz.css");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .tree-area-container {
        width: 40%;
        height: 22em;
        float:left;
    }
    .tree-area{
        width: 100%;
        height: 90%;
    }
    .add-answer {
        overflow:hidden;
        padding-left: 1em;
    }
    .add-answer textarea {
        width: 90%;
    }
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <div class="tree-area-container">
        <h3>Selected Answer's Q's & A's</h3>
        <div class="tree-area">

            <div id="myjstree" class="demo">

                <?php quizHelper::build_tree($quizData, quizLogic::returnParentId($dbLogic, $id, "answer")); ?>
            </div>
        </div>
    </div>
    <div class="add-answer">
        <h3>These are the details for the answer.</h3>
        <p class="label">The answer:</p>
        <textarea name="answer-content" id="answer-content" rows="4" cols="50"><?php echo $answerContent ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$answerContentError."</span>"?>
        <br />
        <p class="label">Feedback:</p>
        <textarea name="feedback-content" id="feedback-content" rows="4" cols="50"><?php echo $feedbackContent ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$feedbackContentError."</span>"?>
        <br />
        <p class="label">Correctness:</p>
        <label for="correct">Correct:</label>
        <input type="radio" name="is-correct" id="correct" value='1' <?php if ($isCorrect == "1"){echo "checked=\"checked\"";} ?> />
        <label for="incorrect">Incorrect:</label> 
        <input type='radio' name='is-correct' id="incorrect" value='0' <?php if ($isCorrect == "0"){echo "checked=\"checked\"";} ?> />
        <label for="neutral">Neutral:</label> 
        <input type='radio' name='is-correct' id="neutral" value='2' <?php if ($isCorrect == "2"){echo "checked=\"checked\"";} ?> />
        <br />
        <?php echo "<span class=\"inputError\">".$isCorrectError."</span>"?> 
    </div>
    <p><a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz=' . $quizIDGet) ?>">Back</a></p>
    <button class="mybutton mySubmit" type="submit" name="answer-submit" value="Enter">Update</button>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());


//html
echo $templateLogic->render();