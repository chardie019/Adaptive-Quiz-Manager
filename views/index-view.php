<!DOCTYPE html> 

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>
 
<head>

 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>Home - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Index</h1>



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

<p>
 
Quisque pellentesque sodales aliquam. Morbi mollis neque eget arcu egestas non ultrices neque volutpat. Nam at nunc lectus, id vulputate purus. In et turpis ac mauris viverra iaculis. Cras sed elit a purus ultrices iaculis eget sit amet dolor. Praesent ac libero dolor, id viverra libero. Mauris aliquam nibh vitae eros sodales fermentum. Fusce cursus est varius ante vehicula eget ultrices felis eleifend. Nunc pharetra rutrum nibh et lobortis. Morbi vitae venenatis velit.
 
</p>
</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>