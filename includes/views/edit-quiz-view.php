<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Quiz');
$templateLogic->setSubMenuType("edit-quiz");
$templateLogic->startBody();
?>
                
                <div id="content-create-quiz">
                    <p>Welcome to the Edit Quiz Menu, please choose a option above to continue.</p>
                </div>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();