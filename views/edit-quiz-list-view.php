<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Select Quiz');
$templateLogic->setHeading('Approved Quiz List');
$templateLogic->startBody();
?>
            <div id="content-centre">

                <form action="#" method="post">
                    <br />

                    
                    <label class="label">Select Quiz: </label>
                        
                    <select class="quiz_list" name="quizid">
        <?php
        foreach ($quizEditId as $answerRow) {
        echo "<option value = ".($answerRow["QUIZ_ID"])."> ".$answerRow["QUIZ_NAME"]."</option>";
        }
        ?>
                    </select>
    <!-- pad  the space between submit button and dropdown box -->
                    <br />
                    <br />
                    
                    <p>
                        Select a quiz from the list above to edit. It contains all quizzes on 
                        which your account is listed with Edit permissions.
                        Upon selection, you will be taken to the Edit Quiz Front Page, where 
                        you can perform a number of actions including edit quiz, add/remove question, 
                        add edit permissions for other users. 
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