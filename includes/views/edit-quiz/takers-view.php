<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Takers');
$templateLogic->setSubMenuType("edit-quiz", "takers");
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    .myReturn {
        margin-right: 0.5em;
    }
</style>
<?php
$templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>

<br />
<?php
      echo "<p class=\"inputSuccess\">$confirmAddMessage</p>";
      echo"<p class=\"inputSuccess\">$confirmRemoveMessage</p>";
      echo "<p class=\"inputError\">$confirmAddError</p>";
      echo"<p class=\"inputError\">$confirmRemoveError</p>"
?>
<br />


<p>Adding a 'Taker' enables a user to complete your private quiz. Removing a user will disable a Taker's ability to attempt the quiz.</p>
<br />
<form action='#' method='post'>
    <p>Please enter the CSU Username of the User you would like to <span id='label'>enable</span> permissions for, 
        or <span id='label'>delete</span> the user from the list below:
    </p>
    <br />
    <span id='label'> Username: </span>
    <input type='text' class='defaultText' name='newUser' maxlength="20"/>
    <button class="mybutton myEnabled" type="submit" name="confirmAddUser" value="Enter">Add user</button>  
    <br />
    <br />
    <br />
    
    <div id='tableWrapper'>
        <div id='tableScroll'>
            <table>
                <thead>
                    <tr>
                        <th>Current Takers</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                        foreach($quizUsers as $takers){
                            echo "<tr>"
                                    . "<td><form action='#' name='".$takers['user_USERNAME']."' method='post'>" . 
        "<input type='hidden' name='removename' value='".$takers['user_USERNAME']."'>" . $takers['user_USERNAME'] .
        "<input type='submit' class='myReturn' name='removeTaker' value='Delete'></form>"
                                    . "</td>"
                               . "</tr>";
                        }
                    ?>
                
                </tbody>
            </table>
        </div>
    </div>
    
    
    <br />
    <br />
    <br />
</form>



<?php
    $templateLogic->endBody();
//html
    echo $templateLogic->render(); ?>