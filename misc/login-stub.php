<?php
define ("USERLOGIC_ON_LOGIN_PAGE", "SET");       //Arrived

if(session_id() == '') { //if redirected from another place
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $_SESSION['USERLOGIC_UID_IS_SET'] = "SET";
    //die ("-=----");
    $_SESSION["username"] = filter_input(INPUT_POST, "username");
    $myDirStub = dirname(dirname(__FILE__));
    $myRootStub = substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen($myDirStub)));
    if (!isset($_SESSION["REQUEST_URI"]) || $myRootStub . "/misc/login-stub.php" == $_SESSION["REQUEST_URI"]){
        header('Location: ' . $myRootStub);    //don't go to the login page agin, go to the home page
    } else {
        header('Location: ' . $_SESSION["REQUEST_URI"]);    //go back to requested page (recorded vai userLogic)
    }
    exit;
}
?>
<?php
//include php files related to html output here
require_once("../includes/config.php");
// end of php file inclusion

$templateLogic = new templateLogic;
$templateLogic->setTitle('Dev Login');
$templateLogic->setHeading('Dev Login');
$templateLogic->startBody();
?>

<p> This website is not running on the CSU Server </p>
<p> Please login with your username below </p>

<form action="" method="post">
    <!-- set a test username -->
    Username <input type="text" name="username" value="testuser" 
                    onfocus="if(this.value=='testuser'){this.value='';}" onblur="if(this.value==''){this.value='testuser'}" />&nbsp;
                <input type="submit" name="add" value="Login">
            </form>

<br />
<br />
<p>
<?php
$buildNumber = 3;
$con = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DB);

// Check connection
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    $res = mysqli_query($con, "SELECT BUILD_NUMBER from version;");
    $row = mysqli_fetch_assoc($res);
    if ($row['BUILD_NUMBER'] >= $buildNumber){
        echo "Database Version Check";
        echo "<br />";
        echo "Sucess: Build ".$row['BUILD_NUMBER']." is the correct database version.";
        echo "<br /><br />";
        echo '<a href="/phpmyadmin/db_export.php?db=aqm" target="_blank">Backup database</a>' . 
                 ' and <a href="/phpmyadmin/db_import.php?db=aqm" target="_blank">Import Database</a>.';
         echo '<br />(phpMyAdmin links)';
    } else { //fail
        echo "Database Version Check";
        echo "<br />"; 
        echo "Fail: Build ".$row['BUILD_NUMBER']." is NOT the correct database version.";
         echo "<br /><br />";
         echo 'Please <a href="/phpmyadmin/db_export.php?db=aqm" target="_blank">backup your database</a>' . 
                 ' and overide with <a href="/phpmyadmin/db_import.php?db=aqm" target="_blank">latest version</a>.';
         echo '<br />(phpMyAdmin links)';
         echo "<br /><br />";
         echo 'The latest SQL file can be found in: "Adaptive-Quiz-Manager\includes\project-notes"';
     }
}
?>
</p>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
