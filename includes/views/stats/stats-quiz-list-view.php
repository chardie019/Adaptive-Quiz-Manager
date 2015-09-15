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
        <label class="label">Select Quiz: </label>
                        
        <select class="quiz_list" name="quizid">
            <?php
                foreach ($quizArray as $quizRow) {
                echo "<option value = ".($quizRow["QUIZ_ID"])."> ".$quizRow["QUIZ_NAME"]."</option>";
            }
        ?>
        </select>
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
