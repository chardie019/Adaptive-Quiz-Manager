<?php
define ("USERLOGIC_ON_LOGIN_PAGE", "SET");       //Arrived

if(session_id() == '') { //if redirected from another place
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === "POST") { 
    $_SESSION['USERLOGIC_UID_IS_SET'] = "SET";
    //die ("-=----");
    $_SESSION["username"] = filter_input(INPUT_POST, "username");
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>

<head>

<!-- include php files related to html output here -->
<?php require_once("includes/config.php"); ?>
<!-- end of php file inclusion -->
 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>1stWebDesigner PHP Template</title>
 
</head>
 
    <body>
 
        <div id="wrapper">


 
<div id="content">
 
<h1>Login</h1>

<p> This website is not running on the CSU Server </p>
<p> Please login with your username below </p>

<form action="" method="post">
                Username <input type="text" name="username"  />&nbsp;
                <input type="submit" name="add" value="Login">
            </form>

</div> <!-- end #content -->
 

<?php include('sidebar.php'); ?>
 
<?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>