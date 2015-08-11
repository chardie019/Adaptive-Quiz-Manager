<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('About');
$templateLogic->startBody();
?>

<p>Lorem ipsum dolor sit amet, consectetur...</p>
<pre><?php print_r($_GET); ?></pre>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
