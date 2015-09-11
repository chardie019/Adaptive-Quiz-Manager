<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Questions');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->addCSS("edit-quiz.css");
$templateLogic->addCustomHeaders('
<style>
    .tree-area{
        width: 80%;
        height: 30em;
    }
    .edit-question-sidebar {
        float: right;
        width: 15%;
        padding-left: 2em;
    }
div.message {
    padding-bottom: 1em;
}
.feedback-span {
    color: blue;
}
    </style>');
$templateLogic->startBody();
?>
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?quiz=' . $quizIDGet; ?>">
    
    <?php if($displayMessage == "initalQuestion"){ ?>
    <div class="message">
        <span class="inputError">No Answer was selected, did you mean to add the first question? <br />
        <a class="mybutton" href="<?php echo CONFIG_ROOT_URL . '/edit-quiz/edit-question/add-initial-question.php?quiz=' . $quizIDGet ?>">Add Initial Question</a>
        </span>
    </div>
    <?php } //end of display question ?>
    
    <?php if($message != ""){ //if there is feedback ?>
    <div class="message">
        <span class="<?php echo $messageClass; ?>"><?php echo $message; ?></span>
    </div>
    <?php } //end of display question ?>
 
<div class="tree-area">
    <?php if (count($quizData) > 0) { // if there are questions ?>
    
    	<div id="myjstree" class="demo">
<?php   
    quizHelper::build_tree($quizData);
?>
</div>
       
    <?php } else { //no questions ?>
    <p> There are no questions on this quiz, How about adding some? </p>
    <p> <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question/add-question.php?quiz=' . $quizIDGet) ?>">
            Add Questions
        </a>
    </p>
        
    <?php } ?>

    
</div>
<div class="edit-question-sidebar">
    <p>
        <input class="mybutton" type="submit" name="inspect" value="Inspect" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="addQuestion" value="Add Question" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="removeQuestion" value="Remove Question" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="addAnswer" value="Add Answer" />
        <br />
        <br />
        <input class="mybutton" type="submit" name="removeAnswer" value="Remove Answer" />
        <br />
        <br />
        <input class="mybutton" type="reset" value="Clear" />
    </p>
</div>
</form>

<?php
$templateLogic->endBody();
$templateLogic->addJavascriptBottom("jstree/jstree.min.js", true);
$templateLogic->addJavascriptBottom("run-jstree.js");
$templateLogic->addCustomBottom(quizHelper::printRunJstreeCssCode());

//html
echo $templateLogic->render();