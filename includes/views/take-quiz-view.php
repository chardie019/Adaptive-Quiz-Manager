<?php

$templateLogic = new templateLogic;
$templateLogic->addCSS("take-quiz-style.css");
$templateLogic->setTitle('Take Quiz');
$templateLogic->addCustomHeadersStart();
?>
<style type="text/css">
    .image-container{
        width: 50%;
        float:right;
    }
    .question-details {
        /* fill teh rest of the left hand screen */
        overflow:hidden;
    }
    
</style>
<?php
$templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<div id="content-centre">
<p> 
	Time remaining as of loading this page: 
	<?php
	if ($_SESSION["QUIZ_TIME_LIMIT"] != 86000) {
		echo gmdate("H:i:s", $_SESSION["QUIZ_TIME_LIMIT"]);
	}
	else {
		echo "Unlimited";
	}?>
	<br />
	<br />
	
    <?php echo ("<span class=\"feedbackStyle\">".nl2br($answerFeedback)."</span>");

          echo ("<br />");
    ?>
</p>
    <br />
    <div class="image-container">
        <img alt="<?php echo($questionData["IMAGE_ALT"]) ?>" src="<?php echo($questionData["IMAGE"]) ?>" />
    </div>
    <div class="question-details">
        <p>
            Question: <?php echo ($questionData["QUESTION"]); ?>
        </p>
        <p>
        <?php echo (nl2br($questionData["CONTENT"])); ?>
        <br />
        <br />
         Please choose an answer:
         <br />
        </p>
        <form action="#" method="post"> 
        <?php 
        foreach ($answerData as $answerRow) {
                        //$result = array_values($oneResult); //convert from assocative array to numeric(normal) array
                        echo ("<label>");
                            echo ("<input type=\"radio\" name=\"answer\" value=\"" . $answerRow["ANSWER_ID"] . "\" />");
                            echo (nl2br($answerRow["ANSWER"]));
                        echo ("</label>");
                        echo ("<br />");
                    }
        ?>
    </div>
        <br />
        <button class="mybutton mySubmit" type="submit" value="Submit">Submit</button>
    </form>
</div>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
