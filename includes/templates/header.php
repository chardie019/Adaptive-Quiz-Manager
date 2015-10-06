<div id="header">
    <div id="logo">
        <img alt="Logo" src="<?php echo(STYLES_LOCATION) ?>/logo.png" />
            <p> 
            <?php if ($userLogic->isAUserLoggedIn() === true){ ?>
                <span>USER: </span><?php echo ($userLogic->getUsername()); ?>
                <br />
                <span>TYPE: </span><?php echo ($userLogic->getUserTypeDisplay()); ?>
            <?php } else { ?>
                Not logged in
                <br />
            <?php } ?>
                <br />
                <span><!-- line up the button --></span><a href="<?php echo(CONFIG_ROOT_URL); ?>/misc/logout.php">Logout</a>
            </p>
    </div>
 
 
</div>