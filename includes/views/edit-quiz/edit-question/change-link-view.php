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
    p.submit-buttons-container {
        padding-top: 0;
    }
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <span class="inputError"><?php echo $selectionError; ?></span>
    <div class="tree-area-container">
        <div class="tree-area">
            <div id="myjstree" class="demo">
                <?php echo $returnHtml; ?>
            </div>
        </div>
    </div>
    <p>
    <input type="radio" name="link-remove" value="update" id="link-remove-no" checked="checked" />
    <label for="link-remove-no">Update the link</label>
    <input type="radio" name="link-remove" value="remove" id="link-remove-yes" />
    <label for="link-remove-yes">Remove The existing link</label>
    </p>
    <p class="submit-buttons-container">
        <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php'.$quizUrl) ?>">Back</a>
        <button class="mybutton mySubmit" type="submit" name="link-update" value="Enter">Change Link</button>
    </p>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizMiscLogic::printRunJstreeCssCode());


//html
echo $templateLogic->render();