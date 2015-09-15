<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Takers');
$templateLogic->setSubMenuType("edit-quiz", "takers");
$templateLogic->startBody();
?>


<?php
      echo "<p class=\"inputError\">$confirmAddMessage</p>";
      echo"<p class=\"inputError\">$confirmRemoveMessage</p>"
?>
<br />
<br />

<p>Adding a 'Taker' enables a user to complete your private quiz. Removing a user will disable a Taker's ability to attempt the quiz.</p>
<br />
<form action='#' method='post'>
    <p>Please enter the CSU Username of the User you would like to <span id='label'>enable/disable</span> permissions for:</p>
    <br />
    <table class="userTable">
        <tr><th>Current active Takers</th></tr>
        <?php 
            foreach($quizUsers as $takers){
                echo "<tr><td>".$takers['user_USERNAME']."</td></tr>";
            }
        ?>
    </table>
    <br />
    <br />
    <br />
    
    <select class="userList">
        <option>--- Click to view current active Takers ---</option>
        <?php 
            foreach($quizUsers as $takers){
                echo "<option>".$takers['user_USERNAME']."</option>";
            }
        ?>
    </select>
    
    <br />
    <br />
    <br />

</form>


<?php
    $templateLogic->endBody();
//html
    echo $templateLogic->render(); ?>