<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Add Inital Question');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->startBody();
?>
<p>stub</p>
<p>Add the first question or stack another question on top of the first question (replace)</p>
<p><a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question.php?quiz=' . $quizIDGet) ?>">Back</a></p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();