<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("log_errors", 1);
ini_set("error_log", "not_synced/PHP_errors.log"); //relative to htaccess in the aqm

//define site variables (not styles)
define( 'CONFIG_ROOT_DIR', dirname(dirname(__FILE__))); // C:\xampp\htdocs\aqm <inlude file from another location relative to here? >
define( 'CONFIG_ROOT_URL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(CONFIG_ROOT_DIR)))); //    /aqm   <use to set the css location on another php file>
//define('INCLUDES', __DIR__);        //  C:\xampp\htdocs\aqm\includes <include location>


//set include path (so you don't reference other files, just this
$paths = array(
    dirname(__FILE__),                  //include directory
    dirname(__FILE__) . '/../views/'    //views directory
 );
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));

//set global settings
mb_internal_encoding(); //set internal utf-8 encoding
mb_http_output();       //mb_* string functions must still be used
header('Content-Type: text/html; charset=UTF-8');

if(session_id() == '') { //it may of been started eariler eg login file.
    session_start();
}

//include other config files
include("userLogic.php");
include_once("dbLogic.php");

include_once("styles.php");



//note: when echo-ing html other language, use  echo (htmlentities($string));




?>