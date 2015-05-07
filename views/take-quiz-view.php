<!DOCTYPE html>
 
<head>
 
<link rel="stylesheet" type="text/css" href="<?php echo(CONFIG_STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>1stWebDesigner PHP Template</title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Take Quiz</h1>

<p>
Parms: <?php print_r($_GET); ?>

</p>

 
<p>
 Please choose an answer:
</p>
<form action="<?php echo(CONFIG_ROOT_URL) ?>/quiz-complete.php">
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