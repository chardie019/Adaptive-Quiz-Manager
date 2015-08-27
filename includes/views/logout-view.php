<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Logged out');
$templateLogic->startBody();
?>

<p>
    You have been logged out of the <?php echo (STYLES_SITE_NAME); ?> System.
    <br /><br />
    Would you like to <a href="<?php echo(CONFIG_ROOT_URL); ?>">login in</a> again?

</p>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
