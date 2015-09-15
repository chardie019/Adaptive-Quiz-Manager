<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Editors');
$templateLogic->setSubMenuType("edit-quiz", "editors");
$templateLogic->startBody();
?>
<?php echo "<p class=\"inputError\">$confirmAddMessage</p>"; ?>
<br />
<?php echo "<p class=\"inputError\">$confirmRemoveMessage</p>"; ?>
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
    <p>Please enter the CSU Username of the User you would like to <span id='label'>enable/disable</span> permissions for:</p>
    <br />
    <table class="userTable">
        <tr><th>Current active Editors</th></tr>
        <?php 
            foreach($quizUsers as $editors){
                echo "<tr><td>".$editors['user_USERNAME']."</td></tr>";
            }
        ?>
    </table>
    <br />
    <br />
    <br />
    
    <select class="userList">
        <option>--- Click to view current active Editors ---</option>
        <?php 
            foreach($quizUsers as $editors){
                echo "<option>".$editors['user_USERNAME']."</option>";
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
echo $templateLogic->render();