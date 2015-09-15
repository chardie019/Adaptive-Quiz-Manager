<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Remove Answer');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-question/edit-question-tree-list.css");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .tree-area-container {
        width: 40%;
        height: 26em;
}
</style>
<?php
$templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <div class="inside-form">
        <div class="tree-area-container">
            <h3>Selected Answer's Q's & A's</h3>
            <div class="tree-area">
                <div id="myjstree" class="demo">
                    <?php quizHelper::build_tree($quizData, quizLogic::returnParentId($dbLogic, $id, "answer"), "none"); ?>
                </div>
            </div>
        </div>
        <div class="edit-right-side">
            <h3>These are the details for the answer.</h3>
            <p class="label">The answer:</p>
            <p><?php echo $answerContent ?></p>
            <br />
            <p class="label">Feedback:</p>
            <p><?php echo $feedbackContent ?></p>
            <br />
            <p class="label">Correctness:</p>
            <p> <?php echo $correctText; ?> </p>
            <br />
        </div>
    </div>
    
    <p class="submit-buttons-container">
        <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz=' . $quizId) ?>">Back</a>
        <button class="mybutton mySubmit" type="submit" name="delete-submit" value="Enter">Delete</button>
    </p>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());


//html
echo $templateLogic->render();