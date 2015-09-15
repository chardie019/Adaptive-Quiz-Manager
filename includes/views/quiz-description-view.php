<?php

$templateLogic = new templateLogic;
$templateLogic->addCSS(STYLES_LOCATION . "/take-quiz-style.css");
$templateLogic->setTitle($quizData['QUIZ_NAME']);
$templateLogic->setHeading("Take Quiz Confirmation");
$templateLogic->startBody();
?>

 <div id="content-centre">

<img alt="<?php echo($quizData['IMAGE_ALT']); ?>" src="<?php echo($quizData["IMAGE"]); ?>" />
    <br />
    <br />
    <span id="label">Quiz ID: </span><?php echo ($_SESSION["QUIZ_CURRENT_QUIZ_ID"]); ?>
    <br />
    <br />
    <span id="label">Description: </span><?php echo ($quiz_description); ?>
    <br />
    <br />
    <span id="label">Number of attempts allowed: </span><?php echo $no_of_attempts; ?>
    <br />
    <br />
    <span id="label">Are attempts savable: </span><?php echo ($is_savable); ?>
    <br />
    <br />
    <span id="label">Time Limit: </span><?php echo ($time_limit); ?>
    <br />
    <br />
</p>

<!-- Quiz confirmed, user and form are sent to /take-quiz/QUIZ_CURRENT_QUIZ_ID, which is question 1 of quiz-->

    <?php 
    if($attemptsReached == false && $isEnabled == true){
        echo "<form action=\"#\" method=\"post\">";
        echo "<input type=\"hidden\" name=\"confirmQuizId\" value=\"" . $_SESSION['QUIZ_CURRENT_QUIZ_ID'] . "\" />"; 
        echo "<button class=\"mybutton mySubmit\" type=\"submit\" name=\"confirmQuiz\" value=\"Enter\">Begin</button>";
        echo "</form>";
        
    }
    else if($attemptsReached == true){
        echo "<span class=\"inputError\"> You have reached your maximum number of attempts. Please select another quiz. </span>";
    }
    else if($isEnabled == false){
        echo "<span class=\"inputError\"> This quiz is currently under revision. As a result, it is disabled to takers. "
            . "Please contact one of the quiz editors to gain further information on this quiz's status. </span>";
    }
    
    
        ?>

<form action="" method="post"> 
    <button class="mybutton myReturn" type="submit" name="notConfirmQuiz" value="Return">Return</button>
</form>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();

