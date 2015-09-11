<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Add Question');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-quiz.css");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .tree-area-container {
        width: 40%;
        height: 25em;
        float:left;
    }
    .tree-area{
        width: 100%;
        height: 90%;
    }
    .add-question {
        float:left;
        padding-left: 1em;
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

                <?php quizHelper::build_tree($quizData, quizLogic::returnParentId($dbLogic, $prevAnswerId, "answer")); ?>
            </div>
        </div>
    </div>
    <div class="add-question">
        <h3>Please add the Details for the new question.</h3>
        <p class="label">Please enter the Question:</p>
        <input type='text' id='question-title' name='question-title' size='30' value="<?php echo $questionTitle ?>" />
        <br />
        <?php echo "<span class=\"inputError\">".$questionTitleError."</span>"?>       
        <br />
        <p class="label">Please enter the Question's Content:</p>
        <textarea name="question-content" id="question-content" rows="4" cols="50"><?php echo $questionContent ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$questionContentError."</span>"?>
        <br />
        <p class="label">Please enter the Question's Image (optional):</p>
        <input type="file" name="questionImageUpload" accept="image/*">
        <br />
        <?php echo "<span class=\"inputError\">".$questionImageError."</span>"?> 
        <br />
        <p class="label">Please enter the Question's Image's tool tip for the sight impaired:</p>
        <input type='text' id='question-alt' name='question-alt' size='30' value="<?php echo $questionAlt ?>" />
        <br />
        <?php echo "<span class=\"inputError\">".$questionAltError."</span>"?>       
    </div>
    <p><a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz=' . $quizIDGet) ?>">Back</a></p>
    <button class="mybutton mySubmit" type="submit" name="confirmQuiz" value="Enter">Create</button>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());


//html
echo $templateLogic->render();