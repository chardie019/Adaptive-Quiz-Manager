<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('404 - Page Not Found');
$templateLogic->setHeading('Sorry...');
$templateLogic->startBody();
?>
<h3><?php echo($errorMessage); ?></h3>
<pre><?php print_r($_GET); ?></pre>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render("error");
