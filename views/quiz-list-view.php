<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle("Select Quiz");
$templateLogic->setHeading("Approved Quiz List");
$templateLogic->startBody();
?>

 <div id="content-centre">
    <form action="#" method="post">
                    <br />

                    
                    <label class="label">Select Quiz: </label>
                        
                    <select class="quiz_list" name="quizid">
        <?php
        foreach ($answerID as $answerRow) {
        echo "<option value = ".($answerRow["QUIZ_ID"])."> ".$answerRow["QUIZ_NAME"]."</option>";
        }
        ?>
                    </select>
    <!-- pad  the space between submit button and dropdown box -->
                    <br />
                    <br />
                    
                    <p>
                        Select a quiz from the list above, it contains all public quizzes,
                        along with those which you have been assigned private access to attempt. 
                        Upon selection, you will be taken to the Quiz Front Page, where you will
                        be able to view the quiz information and confirm your choice. 
                    </p>
                    
                    <br />
                    <br />
        <?php echo "<input type=\"hidden\" name=\"selectQuizId\" value=". ($answerRow["QUIZ_ID"])." />"?>

                        <button class="mySubmit" type="submit" name="selectQuiz" value="Select Quiz">
                            Select Quiz
                        </button>
                    </form>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();

