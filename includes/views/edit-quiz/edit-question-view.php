<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Questions');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCSS("edit-question/edit-question-tree-list.css");
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .tree-area-container {
        width: 73%;
        height: 30em;
    }
    .tree-area {
        height: 100%;
    }
    .edit-question-sidebar {
        /* float right and take up teh rest of the room */
        overflow: hidden;
        text-align: center;
    }
    .edit-question-sidebar .mybutton {
        margin-top: 0.5em;
        margin-bottom: 0.5em;
        width: 10em;
    }
    .edit-question-sidebar .surround-controls {
        border: #000 1px solid;
        margin-left: 0.5em;
        padding-bottom: 0.5em;
        margin-bottom: 0.5em;
    }
    /* 2nd and further, apply margin to the first one only */
    .edit-question-sidebar .surround-controls + .surround-controls {
        margin-bottom: 0;
    }
    div.message {
        padding-bottom: 1em;
    }
    .feedback-span {
        color: blue;
    }
    .float-left {
        float:left;
    }
</style>
<?php
$templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']) . $quizUrl; ?>">
    
    <?php if($displayMessage == "initalQuestion"){ ?>
    <div class="message">
        <span class="inputError">No Answer was selected, did you mean to add the first question? <br />
        <a class="mybutton" href="<?php echo CONFIG_ROOT_URL . '/edit-quiz/edit-question/add-initial-question.php'.$quizUrl ?>">Add Initial Question</a>
        </span>
    </div>
    <?php } //end of display question ?>
    
    <?php if($message != ""){ //if there is feedback ?>
    <div class="message">
        <span class="<?php echo $messageClass; ?>"><?php echo $message; ?></span>
    </div>
    <?php } //end of display question ?>
 
<div class="tree-area-container">
    <?php if ($htmlTree != "") { // if there are questions ?>
        <div class="tree-area">
            <div id="myjstree" class="demo">
                <?php echo $htmlTree; ?>
            </div>
        </div>
    <?php } else { //no questions ?>
    <p> There are no questions on this quiz, How about adding some? <br /><br /> Click "Add Question" on the right to get started. </p>
    <p> <input type=hidden" name="no-question" value="" /> <!-- post this to add first question -->
    </p>  
    <?php } ?>
</div>
<div class="edit-question-sidebar">
        <input class="mybutton" type="submit" name="inspect" value="Edit" />
        <div class="surround-controls">
            <input class="mybutton" type="submit" name="addQuestion" value="Add Question" />
            <input class="mybutton" type="submit" name="addAnswer" value="Add Answer" />
            <br />
            <input id="direction-above" type="radio" name="direction" value="above" />
            <label for="direction-above">Add Above</label>
            <input id="direction-below" type="radio" name="direction" value="below" checked="checked" />
            <label for="direction-below">Add Below</label>
        </div>
        <input class="mybutton" type="submit" name="link" value="Change Link" />
        <input class="mybutton" type="submit" name="remove" value="Remove" />
        <input class="mybutton" type="reset" value="Clear" />
</div>
</form>

<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());

//html
echo $templateLogic->render();