<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Quiz');
$templateLogic->setHeading('Manage Quiz');
$templateLogic->startBody();
?>
            
   <div id="content-centre">                  
                <br />
                <h3>Create Quiz</h3>
                <br />
                <p>
                    Create a new adaptive quiz on a topic of your choosing. 
                    <br />
                    <br />
                    
                    <a href="<?php echo (CONFIG_ROOT_URL . "/create-quiz.php") ?>" class="mybuttonlink mySubmit">
                            Create
                    </a>
                </p>
                <br />
                <br />
                <br />
                <br />
                <?php if (count($quizEditId) > 0) { //there are quizes?>

                <form action="#" method="post">
                    <h3>Manage existing quiz</h3>
                    <div id='listWrapper'>
                        <h4 class='lightLabel'>Select a quiz to manage from the list below:</h4>
                        <div id='listScroll'>
                            <div class="radios">
                            <?php foreach ($nameArray as $answerRow) { ?>
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
                    <br />
                    <?php echo "<input type=\"hidden\" name=\"selectQuizId\" value=". ($answerRow["QUIZ_ID"])." />"?>

                        
                        
                    </form>
                <?php } else {  //there are NO quizes?>
                <p>
                    Currently you have no quizzes created or have no edit permissions on any quiz.
                    <br />
                    <br />
                    How about having a go an creating a quiz?
                    <a href="<?php echo (CONFIG_ROOT_URL . "/create-quiz.php") ?>" class="mybuttonlink mySubmit">
                            Create Quiz
                    </a>
                    <a href="<?php echo (CONFIG_ROOT_URL) ?>" class="mybutton myReturn">
                            Home
                    </a>
                </p>
                
                <?php }//end quiz if statement ?> 
                
                
            </div>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();