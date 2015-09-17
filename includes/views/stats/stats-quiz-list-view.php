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
            Select a quiz from the list above to view usage statistics for. It contains all quizzes on 
            which your account are listed with Edit permissions.
            Upon selection, you will be taken to the Statistics Usage page, where 
            you will be presented with a series of graphs and tables displaying the user analysis of this quiz.
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
