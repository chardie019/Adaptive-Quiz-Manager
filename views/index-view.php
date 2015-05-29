<!DOCTYPE html> 

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>
 
<head>

<meta charset="utf-8"/>
 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>Home - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<br />    
<br />
<br />


<div id="buttonborder1">
    <a class="menu-icons" href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz" >Take Quiz</a>
</div>

<div id="buttonborder2">
    <a class="menu-icons" href="<?php echo (CONFIG_ROOT_URL); ?>/create-quiz" >Create Quiz</a>
</div>

<div id="buttonborder3">
    <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz">Edit Quiz</a>
</div>

<div id="buttonborder4">
    <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/stats">Statistics</a>
</div>

<div id="buttonborder5">
    <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/about">About AQM</a>
</div>

<div id="buttonborder6">
    <a class="menu-icons" href="<?php echo(CONFIG_ROOT_URL) ?>/help">Help</a>
</div>

<p>
<a href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz/1">Take a quiz (/take-quiz/1)</a>
<br /><br />
<a href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz">Quiz List (/take-quiz)</a>
<br /><br />
<a href=" <?php echo (CONFIG_ROOT_URL); ?>/admin">Administration</a>
<br /> <br />
<a href="<?php echo(CONFIG_ROOT_URL) ?>/test-db-setup/TestEditDB.php">To test that you have you MySQL setup, click here</a>
</p>

<p>
Root Dir: <?php echo (CONFIG_ROOT_DIR); ?>
<br />
Root url: <?php echo (CONFIG_ROOT_URL); ?>
</p>


</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>