<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Statistics');
$templateLogic->startBody();
?>

<div id="content-centre">
<form action="<?php if($_SESSION['statsType'] == 'editor'){
                        echo "stats/stats-editor.php";
                    }else if($_SESSION['statsType'] == 'taker'){
                        echo "stats/stats-taker.php";
                    }
              ?>" method="post">
        <br />                 
        <div id='listWrapper'>
                        <h4 class='lightLabel'>Select a quiz to attempt from the list below:</h4>
                        <div id='listScroll'>
                            <div class="radios">
                            <?php foreach ($quizArray as $answerRow) { ?>
                                <div class="radio-group">
                                <?php echo ("<input type=\"radio\" name=\"quizid\" value=\"".$answerRow["QUIZ_ID"]."\" id=\"q".$answerRow["QUIZ_ID"]."\" />") ?>
                                    <?php echo ("<label class=\"radio\" for=\"q".$answerRow["QUIZ_ID"]."\">"); ?>
                                        <span class="quiz-title"><span id='label'>Quiz Name: </span><?php echo $answerRow["QUIZ_NAME"]; ?></span>
                                        <span class="quiz-desc"><span id='label'>Description: </span><?php echo $answerRow["DESCRIPTION"]; ?></span>
                                    <?php echo "</label>" ?>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- pad  the space between submit button and dropdown box -->
        <br />
        <br />
                    
        <p>
            Selecting a quiz will load that quiz result summary. Here you are able to view overall results for the
            most current version of the quiz or all versions combined.
        </p>

        <br />
        <br />

        <button class="mybutton mySubmit" type="submit" name="selectStatistics" value="Select Quiz">
            Select Quiz
        </button>
    </form>
</div>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
