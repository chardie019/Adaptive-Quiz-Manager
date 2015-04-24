<!DOCTYPE html>

<!-- include php files here -->
<?php require_once("/includes/config.php"); ?>
<!-- end of php file inclusion -->
 
<head>
 
<link rel="stylesheet" type="text/css" href="<?php echo(SITE_STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>1stWebDesigner PHP Template</title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Heading1</h1>
 
<p>
 Please choose an answer:
</p>
<form action="<?php echo(ROOT_URL) ?>/quiz-complete.php">
<input type="radio" name="answer" value="1">Blah Blah Blah Blah Blah Blah <br>
<input type="radio" name="answer" value="2">Blah Blah Blah Blah Blah Blah <br>
<input type="radio" name="answer" value="3">Blah Blah Blah Blah Blah Blah <br>
<input type="radio" name="answer" value="3">Blah Blah Blah Blah Blah Blah
<br />
<input type="submit" value="Submit">
</form>


</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>