<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Add Inital Question');
$templateLogic->setSubMenuType("edit-quiz", "question");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCustomHeadersStart(); 
?>
<style>
    #question-details {
        width: 50%;
        float:left;
        clear:left;
    }
    #answer-details {
        width: 50%;
        float:right;
        clear:right;
    }
</style>
<?php
$templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<p>This area allows you to add a new first question to a quiz. <br />
    To do this, an answer must also be created, so the original first question (if any) can be where that answer leads to.</p>


    
<form action='#' method='post' enctype="multipart/form-data" >
    <div id="question-details" >
        <h3>Please add the Details for the new question.</h3>
        <p class="label">Please enter the Question:</p>
        <input type='text' id='question-title' name='question-title' size='30' value="<?php echo $questionTitle ?>" />
        <br />
        <?php echo "<span class=\"inputError\">".$questionTitleError."</span>"?>       
        <br />
        <p class="label">Please enter the Question's Content:</p>
        <textarea name="question-content" id="question-content" rows="4" cols="50"><?php echo $questionContent ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$questionContentError."</span>"?>
        <br />
        <p class="label">Please enter the Question's Image (optional):</p>
        <input type="file" name="questionImageUpload" accept="image/*">
        <br />
        <?php echo "<span class=\"inputError\">".$questionImageError."</span>"?> 
        <br />
        <p class="label">Please enter the Question's Image's tool tip for the sight impaired:</p>
        <input type='text' id='question-alt' name='question-alt' size='30' value="<?php echo $questionTitle ?>" />
        <br />
        <?php echo "<span class=\"inputError\">".$questionAltError."</span>"?>       
    </div>
    <div id="answer-details" >
        <h3>Please the Details for the new answer.</h3>
        <p class="label">Please enter the Answer:</p>
        <textarea name="answer-content" id="answer-content" rows="4" cols="50"><?php echo $answerContent ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$answerContentError."</span>"?>
        <br />
        <p class="label">Please enter the feedback:</p>
        <textarea name="feedback-content" id="feedback-content" rows="4" cols="50"><?php echo $feedbackContent ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$feedbackContentError."</span>"?>
        <br />
        <p class="label">Will is the answer be correct?:</p>
        <label for="correct">Correct:</label>
        <input type="radio" name="is-correct" id="correct" value='1' <?php if ($isCorrect == "1"){echo "checked=\"checked\"";} ?> />
        <label for="incorrect">Incorrect:</label> 
        <input type='radio' name='is-correct' id="incorrect" value='0' <?php if ($isCorrect == "0"){echo "checked=\"checked\"";} ?> />
        <label for="neutral">Neutral:</label> 
        <input type='radio' name='is-correct' id="neutral" value='2' <?php if ($isCorrect == "3"){echo "checked=\"checked\"";} ?> />
        <br />
        <?php echo "<span class=\"inputError\">".$isCorrectError."</span>"?> 
    </div>
    <p class="submit-buttons-container">
        <a class="mybutton myReturn" href="<?php echo (CONFIG_ROOT_URL . '/edit-quiz/edit-question.php?quiz=' . $quizIDGet) ?>">Back</a>
        <button class="mybutton mySubmit" type="submit" name="confirmQuiz" value="Enter">Create</button>
    </p>
</form>
    


<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();