<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Statistics');
$templateLogic->startBody();
?>

<p> The Statistics section </p>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
