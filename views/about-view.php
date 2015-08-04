<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('About');
$templateLogic->startBody();
?>

<p>Lorem ipsum dolor sit amet, consectetur...</p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
