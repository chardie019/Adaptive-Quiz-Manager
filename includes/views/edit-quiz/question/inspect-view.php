<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Inspect Question/Answer');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->startBody();
?>
<p>stub</p>
<p>You have selected question: <?php echo $questionIdPost; ?> of quiz: <?php echo $quizIDGet; ?></p>
<p>You have selected answer: <?php echo $answerIdPost; ?> of quiz: <?php echo $quizIDGet; ?></p>
<p><a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question.php?quiz=' . $quizIDGet) ?>">Back</a></p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();