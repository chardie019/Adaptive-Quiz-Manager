<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>
<html>
<head>

 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>Select Quiz - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Quiz List (to be replaced by harry)</h1>


<!-- quiz-description not created yet, submit leads to 404 -->
<form action="quiz-description.php" method="post" name="getQuiz">
    <br />
    <select name="quizid">
    <?php
        foreach ($answerID as $answerRow) {
            echo "<option value = ".($answerRow["QUIZ_ID"])."> ".$answerRow["QUIZ_NAME"]."</option>";
        }
    ?>
    </select>
    <br />
    <br />
    <input type="submit" value="Select Quiz" />
</form>


<p>
 Parms: <?php print_r($_GET); ?> <br />
 <br />
 note: only quiz 1 implemented.

</p>

<ol>
    <li><a href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz/1">Quiz 1 </a></li>
    <li><a href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz/2">Quiz 2 </a></li>
    <li><a href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz/3">Quiz 3 </a></li>
    <li><a href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz/4">Quiz 4 </a></li>
    
</ol>




</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>