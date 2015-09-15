<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Quiz');
$templateLogic->setHeading('Edit Quiz');
$templateLogic->startBody();
?>
            
   <div id="content-centre">
                <h2>Update quiz information and content</h2>
                
                <?php if (count($quizEditId) > 0) { //there are quizes?>

                <form action="#" method="post">
                    <br />
                    
                    <p id="label">Select Quiz:
                        
                    <select class="quiz_list" name="quizid">
                    <?php
                    foreach ($nameArray as $answerRow) {
                    echo "<option value = ".($answerRow["QUIZ_ID"])."> ".$answerRow["QUIZ_NAME"]."</option>";
                    };?>
                    </select>
                    <!-- pad  the space between submit button and dropdown box -->
                    <br />
                    <br />
                    </p>
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

                        <button class="mybutton mySubmit" type="submit" name="selectQuiz" value="Select Quiz">
                            Select Quiz
                        </button>
                    </form>
                <?php } else {  //there are NO quizes?>
                <p>
                    Currently you have no quizzes created or have no edit permissions on any quiz.
                    <br />
                    <br />
                    How about having a go an creating a quiz?
                    <a href="<?php echo (CONFIG_ROOT_URL . "/create-quiz") ?>" class="mybutton mySubmit">
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