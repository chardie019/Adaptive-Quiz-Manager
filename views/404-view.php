<!DOCTYPE html>

<head>
    
<meta charset="utf-8"/>
 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>404 - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Sorry...</h1>

<h3><?php echo($errorMessage); ?></h3>

</div> <!-- end #content -->
<?php include('sidebar.php'); ?>
<?php include('footer.php'); ?>
        </div> <!-- End #wrapper -->
    </body>
</html>