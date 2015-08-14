<div id="header">
    <div id="logo">
        <img alt="Logo" src="<?php echo(STYLES_LOCATION) ?>/logo.png" />
        <p> 
            <span class="label">USER: </span><?php echo ($_SESSION["username"]); ?>
            <br />
            <span class="label">TYPE: </span><?php echo ($_SESSION["usertype"]); ?>
            <br />
            <a href="<?php echo(CONFIG_ROOT_URL); ?>/misc/logout.php">Logout</a>
        </p>
        
    </div>
 
 
</div>