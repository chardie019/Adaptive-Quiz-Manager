<?php
//setups everything the person

//STUB
//check user is logged in (dev envirnment)
//actually is csu authenication in csu envirnment
if (empty($_SERVER['uid'])){
    if (empty($_SESSION["USERLOGIC_UID_IS_SET"])){         //not logged in
        if (!defined('USERLOGIC_ON_LOGIN_PAGE')){
            $_SESSION["REQUEST_URI"] = $_SERVER["REQUEST_URI"]; //record where we are going, just like csu
            header('Location: ' . CONFIG_ROOT_URL . '/login-stub.php'); //this page set USERLOGIC_ON_LOGIN_PAGE variable
            stop();
        }
    } else {                    //is logged in
        $_SESSION["USERLOGIC_UID_IS_SET"] = "SET";    //disable login stub screen
    }
}

//setup user access etc.
if (!empty($_SERVER['uid'])){ //if not logined through the dev envirnment
    $_SESSION["username"] = filter_var($_SERVER["uid"], FILTER_SANITIZE_STRING); //just in case csu auth gets compromised
}
$_SESSION["usertype"] = "stub";


//once logged in, check things
if (!empty($_SESSION["username"])){
        $userBean = new userBean();
    if ($userBean->exists($_SESSION["username"]) == False) {
        $userBean->create($_SESSION["username"]);
    }
    
}
//check if user exists
//$answeredData = $dbLogic->select("PASS_NO", "result_answer", $data2, false);

// add user





//More stuff to be added pending connor



