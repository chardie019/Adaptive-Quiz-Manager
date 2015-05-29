<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>
 
<head>

<meta charset="utf-8"/>
 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/take-quiz-style.css" media="screen" />
 
<title>Take Quiz - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content-centre">
 
<h1>Take Quiz</h1>

<p>
Parms: <?php print_r($_GET); ?>

</p>
<p> 
    <img alt="<?php echo($questionData["IMAGE_ALT"]) ?>" src="<?php echo($questionData["IMAGE"]) ?>" />
    
Quiz ID: <?php echo ($_SESSION["QUIZ_CURRENT_QUIZ_ID"]); ?>
<br />
Question: <?php echo ($questionData["QUESTION"]); ?>
<p>
 Please choose an answer:
</p>
<form action="#" method="post"> 
<?php 
foreach ($answerData as $answerRow) {
                //$result = array_values($oneResult); //convert from assocative array to numeric(normal) array
                echo ("<label>");
                    echo ("<input type=\"radio\" name=\"answer\" value=\"" . $answerRow["LINK"] . "\" />");
                    echo ($answerRow["ANSWER"]);
                echo ("</label>");
                echo ("<br />");
            }
?>
    <br />
    <button class="mySubmit" type="submit" value="Submit">Submit</button>
</form>


</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>