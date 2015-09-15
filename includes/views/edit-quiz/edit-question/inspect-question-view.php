<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Inspect Question');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-question/edit-question-tree-list.css");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
.tree-area-container {
    height: 30em;
    width:40%;
}
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <div class="inspect-area">
        <div class="tree-area-container">
            <h3>Selected Question's Q's & A's</h3>
            <div class="tree-area">

                <div id="myjstree" class="demo">

                    <?php quizHelper::build_tree($quizData, quizLogic::returnParentId($dbLogic, $id, "question"), "none"); ?>
                </div>
            </div>
        </div>
        <div class="edit-right-side">
            <h3>Details for the question.</h3>
            <p class="label">Question:</p>
            <input type='text' id='question-title' name='question-title' size='30' value="<?php echo $questionTitle ?>" />
            <br />
            <?php echo "<span class=\"inputError\">".$questionTitleError."</span>"?>       
            <br />
            <p class="label">Question's Content:</p>
            <textarea name="question-content" id="question-content" rows="4" cols="50"><?php echo $questionContent ?></textarea>
            <br />
            <?php echo "<span class=\"inputError\">".$questionContentError."</span>"?>
            <br />
            <div class="upload-inspect">
                <p class="label">Question's Image (optional):</p>
                <input type="file" name="questionImageUpload" accept="image/*">
                <?php echo "<span class=\"inputError\">".$questionImageError."</span>"?>
                <br />
                <input id="keep-image-yes" type="radio" name="keep-image" value="1" <?php if ($questionKeepImage == "1"){echo "checked=\"checked\"";} ?> /> 
                <label for="keep-image-yes">Keep or Update Image.</label>
                <br />
                <input id="keep-image-no" type="radio" name="keep-image" value="0" <?php if ($questionKeepImage == "0"){echo "checked=\"checked\"";} ?> />
                <label for="keep-image-no">Delete Image<br /> (Overrides the uploaded file)</label>
                <?php echo "<span class=\"inputError\">".$questionKeepImageError."</span>"?> 
                <br />
                <p class="label">Question's Image's tool tip for the sight impaired:</p>
                <input type='text' id='question-alt' name='question-alt' size='30' value="<?php echo $questionAlt ?>" />
                <br />
            <?php echo "<span class=\"inputError\">".$questionAltError."</span>"?>  
            </div>
            <div class="current-image-inspect">
                <?php if (!empty($questionImage)) {
                    echo "<img src=\"$questionImage\" alt=\"$questionAlt\" title=\"$questionAlt\" />". PHP_EOL; 
                } else {
                    echo "<p> No Image currently uploaded. </p>";
                } ?>
            </div>     
        </div>
    </div>
    <p class="submit-buttons-container">
        <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz=' . $quizId) ?>">Back</a>
        <button class="mybutton mySubmit" type="submit" name="question-submit" value="Enter">Update</button>
    </p>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());


//html
echo $templateLogic->render();