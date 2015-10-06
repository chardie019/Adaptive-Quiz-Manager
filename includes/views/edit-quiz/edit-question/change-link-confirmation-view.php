<?php
$templateLogic = new templateLogic;
$templateLogic->setTitle("Change Link");
$templateLogic->setSubMenuType("edit-quiz", "edit-question");
$templateLogic->addCSS("jstree/themes/default/style.min.css", true);
$templateLogic->startBody();
?>
<form action='#' method='post' enctype="multipart/form-data" >
    <h3>Are you sure you want to <?php echo $linkRemoveRadio; ?> the link On this Answer:</h3>
    
    <p><?php echo $answer; ?></p>
    <br />
    <?php if ($linkRemoveRadio == "update") { ?><h4>This will delete any sub questions and answers underneath (if any)!</h4> <?php } ?>
    <input type="hidden" name="question" value="<?php echo $answerId; ?>" />
    <input type="hidden" name="link-remove" value="<?php echo $linkRemoveRadio; ?>" />
    <p class="submit-buttons-container">
        <button class="mybutton myReturn" type="submit" name="link-return" value="Enter">Back</button>
        <button class="mybutton mySubmit" type="submit" name="link-confirm" value="Enter">Confirm</button>
    </p>
</form>
<?php
$templateLogic->endBody();
//html
echo $templateLogic->render();