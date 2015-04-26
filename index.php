<!DOCTYPE html>

<!-- include php files here -->
<?php require_once("./includes/config.php"); ?>
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
<h2>Heading2</h2>
 
<h3>Paragraph Element</h3>
 
<p>
 
Welcome This might customized depending user type?
 
</p>

<p>
<a href="<?php echo (ROOT_URL); ?>/quiz-list.php">Select a Quiz</a>
<br /><br />
<a href=" <?php echo (ROOT_URL); ?>/admin.php">Administration</a>
<br /> <br />
<a href="<?php echo(ROOT_URL) ?>/test-db-setup/TestEditDB.php">To test that you have you MySQL setup, click here</a>
</p>

<p>
Root Dir: <?php echo (ROOT_DIR); ?>
<br />
Root url: <?php echo (ROOT_URL); ?>

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