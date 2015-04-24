<!DOCTYPE html>

<!-- include php files here -->
<?php require_once("/includes/config.php"); ?>
<!-- end of php file inclusion -->
 
 
<head>

 
<link rel="stylesheet" type="text/css" href="<?php echo(SITE_STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>Select Quiz</title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Heading1</h1>

 
<p>
 
Welcome This might customised depending user type?
<br/><br/>
 How to chose different quiz?
<br />
dynamic? eg www.example.com?quiz=ITC105
<br />
or? www.example.com/quiz/ITC105
<br/>
so it's like example.com/Controller/View/Action

<br/>
<br/>
Would need .htaccess rewrite though (what wordpress uses)
</p>

<ol>
    <li><a href="take-quiz.php">Quiz 1 </a></li>
    <li><a href="take-quiz.php">Quiz 2 </a></li>
    <li><a href="take-quiz.php">Quiz 3 </a></li>
    <li><a href="take-quiz.php">Quiz 4 </a></li>
    
</ol>




</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>