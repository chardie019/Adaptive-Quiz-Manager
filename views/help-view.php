<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Help');
$templateLogic->startBody();
?>

<p> The help section </p>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
