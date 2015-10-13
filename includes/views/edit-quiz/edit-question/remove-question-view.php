<?php
$templateLogic = new templateLogic;
$templateLogic->setTitle('Remove Question');
$templateLogic->setSubMenuType("edit-quiz", "edit-question");
$templateLogic->addCSS("edit-question/edit-question-tree-list.css");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
.tree-area {
    height: 28em;
}
.remove-div {
    clear: left;
}
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCustomHeadersStart(); ?>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <div class="inside-form">
        <div class="tree-area-container">
            <h3>Selected Question's Q's & A's</h3>
            <div class="tree-area">
                <div id="myjstree" class="demo">
                    <?php echo $returnHtml; ?>
                </div>
            </div>
        </div>
        <div class="edit-right-side">
            <h3>Details for the question.</h3>
            <p class="label">Question:</p>
            <p><?php echo $questionTitle ?></p>
            <br />
            <p class="label">Question's Content:</p>
            <p><?php echo $questionContent ?></p>
            <br />
            <div class="upload-inspect">
                <p class="label">Question's Image's tool tip for the sight impaired:</p>
                <p><?php echo $questionAlt ?></p>
            </div>
            <div class="current-image-inspect">
                <?php if (!empty($questionImage)) {
                    echo "<img src=\"$questionImage\" alt=\"$questionAlt\" title=\"$questionAlt\" />". PHP_EOL; 
                } else {
                    echo "<p> No Image currently uploaded. </p>";
                } ?>
            </div>
            <p class="label remove-div">Removal type:</p>
                <input id="remove-single-radio" name="delete-type" type="radio" value="single" checked="checked" />
                <label for="remove-single-radio">Remove Single Question</label>
                <br />
                <input id="remove-whole-question-radio" name="delete-type" type="radio" value="whole-question" />
                <label for="remove-whole-question-radio">Remove Single Question and it's Answers</label>
                <br />
                <input id="remove-branch-radio" name="delete-type" type="radio" value="branch" />
                <label for="remove-branch-radio">Remove this Entire Branch</label>
        </div>
    </div>
    <p class="submit-buttons-container">
        <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php' . $quizUrl) ?>">Back</a>
        <button class="mybutton mySubmit" type="submit" name="delete-submit" value="Enter">Delete</button>
    </p>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizMiscLogic::printRunJstreeCssCode());


//html
echo $templateLogic->render();