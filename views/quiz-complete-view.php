<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>

<head>
    
<meta charset="utf-8"/>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/take-quiz-style.css" media="screen" />
<title>Quiz Complete- <?php echo (STYLES_SITE_NAME); ?></title>
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content-centre">
 
<h1>Quiz Complete</h1>
 
<p>
    <img alt="<?php echo($questionData["IMAGE_ALT"]) ?>" src="<?php echo($questionData["IMAGE"]) ?>" />
    <?php echo ($questionData["QUESTION"]); ?>
    
    <br />
    <br />
    <h2>Your Results</h2>
    <div id="results">
<table>
    <tr><th>Question</th><th>Answer</th><th>Answered At</th></tr>
    
 <?php 
 
 foreach ($quizResults as $answerRow) {
        echo "<tr>";
        echo "<td> ".$answerRow["QUESTION"]."</td>";
        echo "<td> ".$answerRow["ANSWER"]."</td>";
        echo "<td> ".$answerRow["ANSWERED_AT"]."</td>";
        echo "</tr>";
        }
        ?>
    
</table>
    </div>
 <br />
<a href="<?php echo(CONFIG_ROOT_URL) ?>">Go to homepage</a>
 
</p>

 
</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>