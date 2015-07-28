<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion

$templateLogic = new templateLogic;
$templateLogic->setTitle('About');
$templateLogic->startBody();
?>

<p>Lorem ipsum dolor sit amet, consectetur...</p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
