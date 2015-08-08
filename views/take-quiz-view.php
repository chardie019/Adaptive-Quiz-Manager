<?php

$templateLogic = new templateLogic;
$templateLogic->addCSS(STYLES_LOCATION . "/take-quiz-style.css");
$templateLogic->setTitle('Take Quiz');
$templateLogic->startBody();
?>

<p> 
    <?php echo ("<span class=\"feedbackStyle\">".$answerFeedback."</span>");

          echo ("<br />");
    ?>
    <br />
    <br />
    <img alt="<?php echo($questionData["IMAGE_ALT"]) ?>" src="<?php echo($questionData["IMAGE"]) ?>" />
    
    Question: <?php echo ($questionData["QUESTION"]); ?>
    <p>
     Please choose an answer:
    </p>
    <form action="#" method="post"> 
    <?php 
    foreach ($answerData as $answerRow) {
                    //$result = array_values($oneResult); //convert from assocative array to numeric(normal) array
                    echo ("<label>");
                        echo ("<input type=\"radio\" name=\"answer\" value=\"" . $answerRow["ANSWER_ID"] . "\" />");
                        echo ($answerRow["ANSWER"]);
                    echo ("</label>");
                    echo ("<br />");
                }
    ?>
        <br />
        <button class="mybutton mySubmit" type="submit" value="Submit">Submit</button>
    </form>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
