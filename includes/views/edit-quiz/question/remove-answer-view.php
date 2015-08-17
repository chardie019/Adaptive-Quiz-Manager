<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Remove Answer');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->startBody();
?>
<p>stub</p>
<p>You have selected answer link: <?php echo $linkIDPost; ?> of quiz: <?php echo $quizIDGet; ?></p>
<p><a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/question.php?quiz=' . $quizIDGet) ?>">Back</a></p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();