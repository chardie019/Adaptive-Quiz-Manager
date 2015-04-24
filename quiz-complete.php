<!DOCTYPE html>

<!-- include php files here -->
<?php require("/includes/config.php"); ?>

<!-- end of php file inclusion -->

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo(SITE_STYLES_LOCATION) ?>/style.css" media="screen" />
<title>Title</title>
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Quiz Complete</h1>

 
<p>
 
<a href="<?php echo(ROOT_URL) ?>/index.php">Go to homepage</a>
 
</p>

 
</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>