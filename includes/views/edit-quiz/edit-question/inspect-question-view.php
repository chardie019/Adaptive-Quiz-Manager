<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Inspect Question');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-quiz.css");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .tree-area-container {
        width: 40%;
        height:28em;
        float:left;
    }
    .tree-area{
        width: 100%;
        height: 90%;
    }
    .add-question {
        /* take up the rest of the width */
        overflow:hidden;
        padding-left: 1em;
    }
    textarea {
        width: 90%;
    }
    input[type=text] {
        width: 90%
    }
    .upload-inspect {
        width:50%;
        float: left;
    }
    .current-image-inspect {
        overflow:hidden; 
        padding-left: 0.5em;
        height: 11em;
        padding-right: 3em;
    }
    .current-image-inspect img {
        max-width:100%;
        max-height:100%;
        float: right;
    }
    
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <div class="inspect-area">
        <div class="tree-area-container">
            <h3>Selected Question's Q's & A's</h3>
            <div class="tree-area">

                <div id="myjstree" class="demo">

                    <?php quizHelper::build_tree($quizData, quizLogic::returnParentId($dbLogic, $id, "question")); ?>
                </div>
            </div>
        </div>
        <div class="add-question">
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
    <p><a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz=' . $quizIDGet) ?>">Back</a></p>
    <button class="mybutton mySubmit" type="submit" name="question-submit" value="Enter">Update</button>
</form>
<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());


//html
echo $templateLogic->render();