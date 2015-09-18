<?php

$templateLogic = new templateLogic;
$templateLogic->addCSS(STYLES_LOCATION . "/take-quiz-style.css");
$templateLogic->setTitle('Quiz Complete');
$templateLogic->addCustomHeadersStart();
?>
<style type="text/css">
    #content-centre img {
        float: right;
    }
    #content-centre h2 {
        clear: both;
        padding-top: 1em;
    }
    #results table {
            width:100%
    }
</style>
<?php
$templateLogic->addCustomHeadersEnd();
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
    <a class="mybutton myReturn" href="<?php echo(CONFIG_ROOT_URL) ?>">Go to Homepage</a>
 </div>


<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
