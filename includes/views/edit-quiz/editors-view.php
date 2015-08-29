<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Manage Editors');
$templateLogic->setSubMenuType("edit-quiz", "editors");
$templateLogic->startBody();
?>
<?php echo "<p class=\"inputError\">$confirmAddMessage</p>"; ?>
<br />
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
   Removing a user will disable an Editor's ability to edit the quiz or its permissions in any way.</p>
<br />
<form action='#' method='post'>
    <p>Please enter the CSU Username of the User you would like to <span id='label'>enable</span> permissions for:</p>
    <br />
    <span id='label'> Username: </span>
    <input type='text' name='addNewUser' maxlength="20" />
    <button class="mybutton mySubmit" type="submit" name="confirmAddUser" value="Enter">Add user</button>
    <br />
    <br />
    <br />
    <p>Please enter the CSU Username of the User you would like to <span id='label'>disable</span> permissions for:</p>
    <br />
    <span id='label'> Username: </span>
    <input type='text' name='removeUser' maxlength="20" />
    <button class="mybutton mySubmit" type="submit" name="confirmRemoveUser" value="Enter">Remove user</button>
    <br />
</form>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();