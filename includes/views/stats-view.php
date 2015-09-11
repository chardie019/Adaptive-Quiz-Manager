<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Statistics');
$templateLogic->startBody();
?>

<form action="#" method="post">
        <br />                 
        <label class="label">Select Quiz: </label>
                        
        <select class="quiz_list" name="quizid">
            <?php
                foreach ($resultID as $answerRow) {
                echo "<option value = ".($answerRow["QUIZ_ID"])."> ".$answerRow["QUIZ_NAME"]."</option>";
            }
        ?>
        </select>
                    <!-- pad  the space between submit button and dropdown box -->
        <br />
        <br />
                    
        <p>
            Select a quiz from the list above to view useage statistics for. It contains all quizzes on 
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

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
