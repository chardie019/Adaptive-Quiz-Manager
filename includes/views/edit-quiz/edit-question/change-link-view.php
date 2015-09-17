<?php
$templateLogic = new templateLogic;
$templateLogic->setTitle('Change the Link');
$templateLogic->setSubMenuType("edit-quiz", "edit-question");
$templateLogic->addCSS("edit-question/edit-question-tree-list.css");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .tree-area-container {
        height: 25em;
        float: none;
    }
    .tree-area {
        height: 100%;
    }
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <div class="tree-area-container">
        <div class="tree-area">
            <div id="myjstree" class="demo">
                <?php echo $returnHtml; ?>
            </div>
        </div>
    </div>
    <!-- keep the forms values -->
    <input type="hidden" name="answer-content" value="<?php echo $answerContent ?>" />
    <input type="hidden" name="feedback-content" value="<?php echo $feedbackContent ?>" />
    <input type="hidden" name="is-correct" value="<?php echo $isCorrect ?>" />
    <p class="submit-buttons-container">
        <button class="mybutton myReturn" type="submit" name="link-back" value="Enter">Back</button>
        <button class="mybutton mySubmit" type="submit" name="link-update" value="Enter">Update Link</button>
    </p>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());


//html
echo $templateLogic->render();