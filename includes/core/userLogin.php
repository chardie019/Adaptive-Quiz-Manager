<?php
/*
 * setups everything when a person logins in
 */

//STUB
//check user is logged in (dev envirnment)
//actually is csu authenication in csu envirnment
if (empty($_SERVER['uid'])){
    if (empty($_SESSION["username"]) 
            && !defined('USERLOGIC_ON_LOGIN_PAGE') 
            && empty($_SESSION["USERLOGIC_UID_IS_SET"])) { //not logged in
            $_SESSION["REQUEST_URI"] = $_SERVER["REQUEST_URI"]; //record where we are going, just like csu
            header('Location: ' . CONFIG_ROOT_URL . '/misc/login-stub.php'); //this page set USERLOGIC_ON_LOGIN_PAGE variable
            exit;
    } else {
        //TODO check we actually going to login stub (user didn't escape by pressing the back button)
    }
} else {                    //is logged in
    $_SESSION["USERLOGIC_UID_IS_SET"] = "SET";    //disable login stub screen
}


//setup user access etc.
if (!empty($_SERVER['uid'])){ //if not logined through the dev envirnment
    $_SESSION["username"] = filter_var($_SERVER["uid"], FILTER_SANITIZE_STRING); //just in case csu auth gets compromised
}

//once logged in, check things
if (!empty($_SESSION["username"])){
    $userLogic = new userLogic($_SESSION["username"]);  
    $_SESSION["usertype"] = $userLogic->getUserTypeDisplay();
} else {
    $userLogic = new userLogic(); //no one here
}