<?php
$templateLogic = new templateLogic;
$templateLogic->setTitle($title);
$templateLogic->setSubMenuType("edit-quiz", "edit-question");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <h3>Are you sure you want to remove the <?php echo $type ?>:</h3>
    <p><?php echo $content ?></p>
    <br />
    <h4>This will delete the <?php echo $deleteTypeDisplay ?>.</h4>
    <p class="submit-buttons-container">
        <button class="mybutton myReturn" type="submit" name="delete-return" value="Enter">Back</button>
        <button class="mybutton mySubmit" type="submit" name="delete-confirm" value="Enter">Confirm</button>
    </p>
</form>
<?php
$templateLogic->endBody();
//html
echo $templateLogic->render();