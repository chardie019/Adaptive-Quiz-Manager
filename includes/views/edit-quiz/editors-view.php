<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Editors');
$templateLogic->setSubMenuType("edit-quiz", "editors");
$templateLogic->startBody();
?>
<p>stub</p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();