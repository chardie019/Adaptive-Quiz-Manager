<div id="header">
    <div id="logo">
        <img alt="Logo" src="<?php echo(STYLES_LOCATION) ?>/logo.png" />
            <p> 
            <?php if (isset($_SESSION['username']) 
                && !empty($_SESSION['username'])
                && isset($_SESSION['usertype'])
                && !empty($_SESSION['usertype'])){ //if logged in ?>

                <span>USER: </span><?php echo ($_SESSION["username"]); ?>
                <br />
                <span>TYPE: </span><?php echo ($_SESSION["usertype"]); ?>
            <?php } else { ?>
                Not logged in
                <br />
            <?php } ?>
                <br />
                <span><!-- line up the button --></span><a href="<?php echo(CONFIG_ROOT_URL); ?>/misc/logout.php">Logout</a>
            </p>
    </div>
 
 
</div>