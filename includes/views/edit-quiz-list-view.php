<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Quiz');
$templateLogic->setHeading('Manage Quiz');
$templateLogic->startBody();
?>
            
   <div id="content-centre">                  
                <br />
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <?php if (count($nameArray) > 0) { //there are quizes?>
                    <h3>Manage existing quiz</h3>
                    <div id='listWrapper'>
                        <h4 class='lightLabel'>Select a quiz to manage from the list below:</h4>
                        <div id='listScroll'>
                            <div class="radios">
                            <?php foreach ($nameArray as $answerRow) { ?>
                                <div class="radio-group">
                                <?php echo ("<input type=\"radio\" name=\"quizid\" value=\"".$answerRow["SHARED_QUIZ_ID"]."\" id=\"quiz-".$answerRow["SHARED_QUIZ_ID"]."\" />") ?>
                                    <?php echo ("<label class=\"radio\" for=\"quiz-".$answerRow["SHARED_QUIZ_ID"]."\">"); ?>
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
                    </p>
                    <p>
                        The quiz list contains all those quizzes for which you currently hold 'Edit' permissions.
                        <br />
                        <br />
                        <button class="mybutton mySubmit" type="submit" name="selectQuiz" value="Select Quiz">
                            Manage
                        </button>
                    </p>
                    <br />
                <?php } else {  //there are NO quizes?>
                <p>
                    Currently you have no quizzes created or have no edit permissions on any quiz.
                    <br />
                    <br />
                    How about having a go an creating a quiz?
                </p>
                
                <?php }//end quiz if statement ?> 
                <h3>Create Quiz</h3>
                <p>
                    Create a new adaptive quiz on a topic of your choosing. 
                    <br />
                    <br /> 
                    <a href="<?php echo (CONFIG_ROOT_URL . "/edit-quiz/create-quiz.php") ?>" class="mybuttonlink mySubmit">
                            Create
                    </a>
                </p>
                </form>
            </div>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();