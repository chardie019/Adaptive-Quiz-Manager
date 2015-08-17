<div id="header">
    <div id="logo">
        <img alt="Logo" src="<?php echo(STYLES_LOCATION) ?>/logo.png" />
        <?php if (isset($_SESSION['username']) 
                && !empty($_SESSION['username'])
                && isset($_SESSION['usertype'])
                && !empty($_SESSION['usertype'])){ //if logged in ?>
            <p> 
                <span class="label">USER: </span><?php echo ($_SESSION["username"]); ?>
                <br />
                <span class="label">TYPE: </span><?php echo ($_SESSION["usertype"]); ?>
                <br />
                <a href="<?php echo(CONFIG_ROOT_URL); ?>/misc/logout.php">Logout</a>
            </p>
        <?php } ?>
        
    </div>
 
 
</div>