<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("log_errors", 1);
ini_set("error_log", "includes/PHP_errors.log");

//define site variables (not styles)
define( 'ROOT_DIR', dirname(dirname(__FILE__))); // C:\xampp\htdocs\aqm <inlude file from another location relative to here? >
define( 'ROOT_URL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(ROOT_DIR)))); //    /aqm   <use to set the css location on another php file>
//define('INCLUDES', __DIR__);        //  C:\xampp\htdocs\aqm\includes <include location>


//set include path (so you don't reference other files, just this
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));

//include other config files
include_once("setupUser.php");
include_once("DbLogic.php");

include_once("styles.php");




?>