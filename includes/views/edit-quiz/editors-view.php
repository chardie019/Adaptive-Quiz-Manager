<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Editors');
$templateLogic->setSubMenuType("edit-quiz", "editors");
$templateLogic->startBody();
?>
<?php   
      echo "<p class=\"inputSuccess\">$confirmAddMessage</p>";
      echo"<p class=\"inputSuccess\">$confirmRemoveMessage</p>";
      echo "<p class=\"inputError\">$confirmAddError</p>";
      echo"<p class=\"inputError\">$confirmRemoveError</p>"
?>
<br />

<p>Adding an 'Editor' enables a user to perform all edit functions including: <br />
    <ul>
        <li>Add other Editors</li>
        <li>Remove other Editors</li>
        <li>Add other Takers</li>
        <li>Remove other Takers</li>
        <li>Edit all Quiz Details</li>
        <li>Remove Quiz from database</li>
    </ul>
   <br /> 
   Removing a user will disable an Editor's ability to edit the quiz or its permissions in any way.
</p>
<br />
<form action='#' method='post'>
    <p>Please enter the CSU Username of the User you would like to <span id='label'>enable</span> permissions for, 
        or <span id='label'>delete</span> a user from the list below:
    </p>
    
    <p id='label'> Username: 
    <input type='text' class='defaultText' name='newUser' maxlength="20" />
    <button class="mybutton myEnabled" type="submit" name="confirmAddUser" value="Enter">Add user</button>
    </p>
    <br />
    <br />
    <div id='tableWrapper'>
        <div id='tableScroll'>
            <table>
                <thead>
                    <tr>
                        <th>Current Editors</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                        foreach($quizUsers as $editors){
                            echo "<tr>"
                                    . "<td><form action='#' name='".$editors['user_USERNAME']."' method='post'>" . 
        "<input type='hidden' name='removename' value='".$editors['user_USERNAME']."'>" . $editors['user_USERNAME'] .
        "<input type='submit' class='myReturn' name='removeEditor' value='Delete'></form>"
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
echo $templateLogic->render();