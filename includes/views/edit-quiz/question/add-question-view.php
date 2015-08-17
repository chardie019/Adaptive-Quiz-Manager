<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Add Question');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->startBody();
?>
<p>stub</p>
<p>You have selected question: <?php echo $questionIDPost; ?> of quiz: <?php echo $quizIDGet; ?></p>
<p><a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question.php?quiz=' . $quizIDGet) ?>">Back</a></p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();