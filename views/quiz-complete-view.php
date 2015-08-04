<?php

$templateLogic = new templateLogic;
$templateLogic->addCSS(STYLES_LOCATION . "/take-quiz-style.css");
$templateLogic->setTitle('Quiz Complete');
$templateLogic->startBody();
?>

 <div id="content-centre">
    <p>
        <img alt="<?php echo($questionData["IMAGE_ALT"]) ?>" src="<?php echo($questionData["IMAGE"]) ?>" />
        <?php echo ($questionData["QUESTION"]); ?>

        <br />
        <br />
    </p>
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
 </div>


<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
