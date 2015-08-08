<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle("Select Quiz");
$templateLogic->setHeading("Approved Quiz List");
$templateLogic->addCustomHeaders("
<style>
.radios .radio{
    display:block;
    border: 1px solid black;
    cursor: pointer;
}
.radios input[type=radio]{
    display:none
}
.radios input[type=radio]:checked + .radio .quiz-title{
    background-color:#BBBBBB;
}
.radios input[type=radio]:checked + .radio .quiz-desc{
    background-color:#D4D4D4;
}
.quiz-title {
    display:block;
    width:100%;
    height:50%;
    background-color: #c5e043;
}
.radio-group:hover .quiz-title {
background-color:#8F9E43;
}
.quiz-desc {
    display:block;
    width:100%;
    height:50%;
    background-color:#EDFBA7;
    border-bottom: black 1px solid;
}
.radio-group:hover .quiz-desc{
background-color: #B3C16A;
}
</style>");
$templateLogic->startBody();
?>

 <div id="content-centre">
    <?php if (count($answerID) > 0) { //quizzes available ?>
        <form action="#" method="post">
            <br />


            <label class="label">Select Quiz: </label>
            <br /><br />
            <div class="radios">
            <?php foreach ($answerID as $answerRow) { ?>
                <div class="radio-group">
                <?php echo ("<input type=\"radio\" name=\"quizid\" value=\"".$answerRow["QUIZ_ID"]."\" id=\"q".$answerRow["QUIZ_ID"]."\" />") ?>
                    <?php echo ("<label class=\"radio\" for=\"q".$answerRow["QUIZ_ID"]."\">"); ?>
                        <span class="quiz-title"><?php echo $answerRow["QUIZ_NAME"]; ?></span>
                        <span class="quiz-desc"><?php echo $answerRow["DESCRIPTION"]; ?>DESCRIPTION</span>
                    <?php echo "</label>" ?>
                </div>
            <?php } ?>
            </div>
            
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

            <button class="mybutton mySubmit" type="submit" name="selectQuiz" value="Select Quiz">
                Select Quiz
            </button>
        </form>
    <?php } else {  //there are NO quizes ?>
        <p>
            Currently, There are no quizzes available to take.
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

