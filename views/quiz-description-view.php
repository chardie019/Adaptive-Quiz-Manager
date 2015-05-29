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
 
<h1><?php echo ($quizData['QUIZ_NAME']); ?> </h1>

<p> 
    <img alt="<?php $quizData['TIME_LIMIT'] ?>" src="<?php echo($quizData["IMAGE"]) ?>" />
    <br />
    <br />
    <span class="label">Quiz ID: </span><?php echo ($_SESSION["QUIZ_CURRENT_QUIZ_ID"]); ?>
    <br />
    <br />
    <span class="label">Description: </span><?php echo ($quiz_description); ?>
    <br />
    <br />
    <span class="label">Number of attempts allowed: </span><?php echo ($no_of_attempts); ?>
    <br />
    <br />
    <span class="label">Are attempts savable: </span><?php echo ($is_savable); ?>
    <br />
    <br />
    <span class="label">Time Limit: </span><?php echo ($time_limit); ?>
    <br />
    <br />
</p>

<!-- Quiz confirmed, user and form are sent to /take-quiz/QUIZ_CURRENT_QUIZ_ID, which is question 1 of quiz-->
<form action="#" method="post"> 
    <?php echo "<input type=\"hidden\" name=\"confirmQuizId\" value=\"" . $_SESSION['QUIZ_CURRENT_QUIZ_ID'] . "\" />"; ?>
    <button class="mySubmit" type="submit" name="confirmQuiz" value="Enter">Begin</button>
</form>


<form action="#" method="post"> 
    <button class="myReturn" type="submit" name="notConfirmQuiz" value="Return">Return</button>
</form>


</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>

