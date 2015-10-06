<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Quiz');
$templateLogic->setSubMenuType("edit-quiz", NULL, $enableSubMenuLinks);
$templateLogic->addCustomHeadersStart();
?>
<style type="text/css">
    span.status-enabled,  span.status-disabled{
        padding: 1em 5em;
        line-height: 3em;
        font-size: large;
    }
    span.status-disabled{
        background-color: #FF9191;
    }
    span.status-enabled{
        background-color: #A5FFA5;
    }
    .feedback-span {
        color: blue;
    }
    span.inputError {
        font-weight: normal;
    }
    /* span inside the error span */
    .inputError span {
        font-weight: bold;
    }
</style>
<?php
$templateLogic->startBody();
?>
     <div id="content-create-quiz">
        <p>Welcome to the Edit Quiz Menu, please ensure the quiz is Disabled before choosing an option above to continue.</p>
        <br />
        <span class="feedback-span"><?php echo $confirmActive ?></span><span><?php echo $createQuizConfirmation; ?></span>
        <span class="inputError">
        <?php if(isset($invalidQuestionAnswersDisplayArray)){
            echo "<span>There Were some integrity checks that failed:</span><br /><br />";
            foreach ($invalidQuestionAnswersDisplayArray as $row) {
                echo $row['problem']; ?>
                <br />
                <br />
                <?php echo $row['fix']; ?>
                <br /><br />
                <?php
            }
        } ?>
        </span>
        <br />
        
        <form action='<?php echo "?quiz=".  quizLogic::returnSharedQuizID($_SESSION['CURRENT_EDIT_QUIZ_ID']) ?>' id='enable' method='post'>
            <p>This quiz is currently: </p>
            <p>
            <?php if($_SESSION['IS_QUIZ_ENABLED'] == true){   
                    echo "<span class='status-enabled'>ENABLED</span><br /><br />" . PHP_EOL . 
                            "<p>Users can currently take the quiz.</p>" .
                            "<p>Please <span class='bold'>DISABLE</span> to begin editing quiz information, note that no users can take the quiz whilst it is disabled.</p>";
                }else{
                    echo "<span class='status-disabled'>DISABLED</span><br /><br />" . PHP_EOL . 
                            "<p>You can Currently edit the quiz.</p>" .
                            "<p>Please <span class='bold'>ENABLE</span> to allow users to take the quiz, note that no editing can done whilst it is enabled.</p>";
                }
            ?>
            </p>
            <input type="hidden" name="quizID" value='<?php echo $_SESSION['CURRENT_EDIT_QUIZ_ID'];?>' >
            <button class="mybutton myEnabled <?php if($_SESSION['IS_QUIZ_ENABLED'] == true){echo 'myGreyedOut" disabled="disabled';} ?>" type="submit" name="confirmEnabled" >Enable</button>
            <button class="mybutton myDisabled <?php if($_SESSION['IS_QUIZ_ENABLED'] == false){echo 'myGreyedOut" disabled="disabled';} ?>" type="submit" name="confirmDisabled" >Disable</button>
        </form>
    </div>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();