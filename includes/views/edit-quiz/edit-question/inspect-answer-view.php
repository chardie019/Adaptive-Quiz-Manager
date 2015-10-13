<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Answer');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-question/edit-question-tree-list.css");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .tree-area-container {
        width:40%;
    }
    .tree-area {
        height: 22em;
    }
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <div class="tree-area-container">
        <h3>Selected Answer's Q's & A's</h3>
        <div class="tree-area">
            <div id="myjstree" class="demo">
                <?php echo $returnHtml; ?>
            </div>
        </div>
    </div>
    <div class="edit-right-side">
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
        <div class="is-correct">
            <p class="label">Will is the answer be correct?:</p>
            <label for="correct">Correct:</label>
            <input type="radio" name="is-correct" id="correct" value='1' <?php if ($isCorrect == "1"){echo "checked=\"checked\"";} ?> />
            <label for="incorrect">Incorrect:</label> 
            <input type='radio' name='is-correct' id="incorrect" value='0' <?php if ($isCorrect == "0"){echo "checked=\"checked\"";} ?> />
            <label for="neutral">Neutral:</label> 
            <input type='radio' name='is-correct' id="neutral" value='2' <?php if ($isCorrect == "2"){echo "checked=\"checked\"";} ?> />
            <br />
            <?php echo "<span class=\"inputError\">".$isCorrectError."</span>"?> 
        </div>
    </div>
    <p class="submit-buttons-container">
        <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php' . $quizUrl) ?>">Back</a>
        <button class="mybutton mySubmit" type="submit" name="answer-submit" value="Enter">Update</button>
    </p>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizMiscLogic::printRunJstreeCssCode());


//html
echo $templateLogic->render();