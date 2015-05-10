<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/take-quiz-style.css" media="screen" />
<title>Quiz Complete- <?php echo (STYLES_SITE_NAME); ?></title>
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Quiz Complete</h1>

 
<p>
    <img src="<?php echo($questionData["IMAGE"]) ?>" />
    <?php echo ($questionData["QUESTION"]); ?>
    
    <br />
 <br />
<a href="<?php echo(CONFIG_ROOT_URL) ?>/index.php">Go to homepage</a>
 
</p>

 
</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>