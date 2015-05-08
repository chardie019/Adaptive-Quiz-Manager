<div id="header">
    <div id="logo">
        <img src="<?php echo(STYLES_LOCATION) ?>/logo.png" />
        <p> USER: <?php echo ($_SESSION["username"]); ?>
            <br />
            TYPE: <?php echo ($_SESSION["usertype"]); ?>
            <br />
            <a href="<?php echo(CONFIG_ROOT_URL); ?>/logout.php">Logout</a>
        </p>
        
    </div>
 
    <h2><?php echo $heading ?></h2>
 
</div> <!-- end #header -->