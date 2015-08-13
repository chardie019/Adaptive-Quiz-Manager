<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Takers');
$templateLogic->setSubMenuType("edit-quiz", "takers");
$templateLogic->startBody();
?>
<p>stub</p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();