<?php

$templateLogic = new templateLogic;
$templateLogic->addCSS(STYLES_LOCATION . "/take-quiz-style.css");
$templateLogic->setTitle($quizData['QUIZ_NAME']);
$templateLogic->setHeading("Take Quiz Confirmation");
$templateLogic->startBody();
?>

 <div id="content-centre">

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

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();

