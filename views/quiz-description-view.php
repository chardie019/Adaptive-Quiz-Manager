<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>

 
<head>
 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/take-quiz-style.css" media="screen" />
 
<title>Take Quiz - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
<div id="content">
 
<h1>Confirmation - <?php echo ($quizData['QUIZ_NAME']); ?> </h1>

<p>
Parms: <?php print_r($_GET); ?>

</p>
<p> 
    <img src="<?php echo($quizData["IMAGE"]) ?>" />
    
Quiz ID: <?php echo ($_SESSION["QUIZ_CURRENT_QUIZ_ID"]); ?>
<br />
Description: <?php echo ($quizData['DESCRIPTION']); ?>
<br />

</p>

<!-- Quiz confirmed, user and form are sent to /take-quiz/QUIZ_CURRENT_QUIZ_ID, which is question 1 of quiz-->
<form action="" method="post"> 
<?php echo "<input type=\"hidden\" name=\"confirmQuizId\" value=\"" . $_SESSION['QUIZ_CURRENT_QUIZ_ID'] . "\" />"; ?>
<input type="submit" name="confirmQuiz" value="Enter">
</form>


<form action="" method="post"> 
<input type="submit" name="notConfirmQuiz" value="Return to Quiz List">
</form>


</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>

