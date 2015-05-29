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

<div id="content">
 
<h1>Logged out</h1>

<p>
    You have been logged out of the <?php echo (STYLES_SITE_NAME); ?> System.
    <br /><br />
    Would you like to <a href="<?php echo(CONFIG_ROOT_URL); ?>">login in</a> again?

</p>
</div> <!-- end #content -->
 
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>