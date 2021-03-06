<?php

// include php files here 
require_once("../includes/config.php");
// end of php file inclusion

/* 
 * Kill the sesssion
 * 
 * The following is from php.net - covers everything
 */

// Unset all of the session variables.
$_SESSION = array();
$userLogic->logOut();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

if (empty($_SERVER['uid'])){
    //logout of dev enironment
    include("logout-view.php");
} else {
    //logout of CSU enironment
    header("Location: /Shibboleth.sso/Logout");
}
//html
