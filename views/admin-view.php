<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Administration');
$templateLogic->startBody();

?>
<p>
Welcome to admin! 
<br /><br />
<a href="<?php echo(CONFIG_ROOT_URL) ?>/index.php">Go to homepage</a>

</p>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
