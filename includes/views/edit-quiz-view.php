<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Quiz');
$templateLogic->setSubMenuType("edit-quiz");
$templateLogic->startBody();
?>
                
                <div id="content-create-quiz">
                    <span><?php echo $createQuizConfirmation; ?></span>
                    <br />
                    <br />
                    <p>Welcome to the Edit Quiz Menu, please ensure the quiz is Disabled before choosing an option above to continue.</p>
                    <br />
                    <br />
                    <form action='#' id='enable' method='post'>
                        <p>
                            <?php if($_SESSION['IS_QUIZ_ENABLED'] == true){
                                echo "This quiz is currently <span  id='label'>ENABLED</span>. Please <span  id='label'>DISABLE</span> to begin editing quiz information.";
                            }else{
                                echo "This quiz is currently <span  id='label'>DISABLED</span>. You can begin editing quiz information.";
                            }
                            ?>
                        </p>
                        <input type="hidden" name="quizID" value='<?php echo $_SESSION['CURRENT_EDIT_QUIZ_ID'];?>' >
                        <button class=" mybutton <?php if($_SESSION['IS_QUIZ_ENABLED'] == true){echo $_SESSION['enableButton'];}else{echo 'myDisabled';} ?> " type="submit" name="confirmEnabled" >Enable</button>
                        <button class=" mybutton <?php if($_SESSION['IS_QUIZ_ENABLED'] == false){echo $_SESSION['enableButton'];}else{echo 'myDisabled';} ?> " type="submit" name="confirmDisabled" >Disable</button>
                    </form>
                </div>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();