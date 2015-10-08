<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Add Question');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-question/edit-question-tree-list.css");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
.tree-area {
    height: 26em;
}
p.submit-buttons-container {
    margin: 0;
}
</style>
<?php
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <?php if (isset($returnHtml)) { ?>
        <div class="tree-area-container">
            <h3>Selected Answer's Q's & A's</h3>
            <div class="tree-area">
                <div id="myjstree" class="demo">
                    <?php echo $returnHtml; ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="edit-right-side">
        <h3>Please add the Details for the new question.</h3>
        <p class="label">Please enter the Question:</p>
        <input type='text' id='question-title' name='question-title' size='30' value="<?php echo $questionTitle ?>" />
        <br />
        <?php echo "<span class=\"inputError\">".$questionTitleError."</span>"?>       
        <br />
        <p class="label">Please enter the Question's Content (optional):</p>
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
        <?php if (isset($direction)) { ?> <p>This answer will be added <?php echo $direction; ?> the selected question.</p> <?php } ?>
    </div>
    <p class="submit-buttons-container">
        <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php'.$quizUrl) ?>">Back</a>
        <button class="mybutton mySubmit" type="submit" name="confirmQuiz" value="Enter">Create</button>
    </p>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());


//html
echo $templateLogic->render();