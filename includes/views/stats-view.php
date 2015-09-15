<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Statistics');
$templateLogic->startBody();
?>
<h2>Choose what type of statistics you wish to view</h2>

<form action="#" method="post">
    <div id="left-column">
        <h2>Created quiz results</h2>
        <br />   
        <p>View the analysis for the attempts on quizzes for which you currently hold 'Edit' permissions on. Choose to 
        include only the overall statistics for the most current version of the quiz, or include data from all previous 
        versions of the quiz as well.</p>
        <br />
        <br />
        <button class="mybutton mySubmit" type="submit" name="editorStats" value="Select Quiz">Editor results</button>
    </div>
    
    <div id="right-column">
        <h2>Personal quiz results</h2>
        <br />  
        <p>View your personal attempt results for the public quizzes you have completed, as well as private quizzes
        which you currently hold 'Take' permissions on. Choose to include only the overall statistics for the most 
        current version of the quiz, or include data from all previous versions of the quiz as well.</p>
        <br />
        <button class="mybutton mySubmit" type="submit" name="takerStats" value="Select Quiz">Taker results</button>
    </div>
</form>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
