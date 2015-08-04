<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Home');
$templateLogic->startBody();
?>

<br />    
            <br />
            <br />


            <div id="buttonborder1">
                <a class="menu-icons" href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz" >Take Quiz</a>
            </div>

            <div id="buttonborder2">
                <a class="menu-icons" href="<?php echo (CONFIG_ROOT_URL); ?>/create-quiz" >Create Quiz</a>
            </div>

            <div id="buttonborder3">
                <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz">Edit Quiz</a>
            </div>

            <div id="buttonborder4">
                <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/stats">Statistics</a>
            </div>

            <div id="buttonborder5">
                <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/about">About AQM</a>
            </div>

            <div id="buttonborder6">
                <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/help">Help</a>
            </div>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
