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
        <p>
            View analysis for the results linked to quizzes for which you currently hold 'Edit' permissions. 
        </p>
        <br />
        <br />
        <button class="mybutton mySubmit" type="submit" name="editorStats" value="Select Quiz">Editor results</button>
    </div>
    
    <div id="right-column">
        <h2>Personal quiz results</h2>
        <br />  
        <p>
            View your personal results for the public and private quizzes you have completed. 
        </p>
        <br />
        <br />
        <button class="mybutton mySubmit" type="submit" name="takerStats" value="Select Quiz">Taker results</button>
    </div>
</form>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
