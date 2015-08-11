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


//set include path (so you don't reference other files, just this) (Part 1/2)
$paths = array(
    dirname(__FILE__),                  //include directory
    dirname(__FILE__) . '/core/',       //core directory
    dirname(__FILE__) . '/views/',      //views directory
    dirname(__FILE__) . '/templates/',  //templates directory
    dirname(__FILE__) . '/lib/',        //libraries directory
    dirname(__FILE__) . '/logic/'       //logic directory (related logic to the pages)
 );
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));

//set global settings
mb_internal_encoding(); //set internal utf-8 encoding
mb_http_output();       //mb_* string functions must still be used
date_default_timezone_set('Australia/Sydney'); // set default timezone incase system is set to wrong time (and avoid apache error)

if(session_id() == '') { //it may of been started eariler eg login file.
    session_start();
}

//php files needed by all

//independant files
//include all files in the "lib" directory
try {
    $files = array();
    $lib = dirname(__FILE__) . '/lib/';
        if (is_dir($lib)){
                $dir = opendir($lib);
                while(($currentFile = readdir($dir)) !== false){
                    if ( $currentFile != '.' && $currentFile != '..' && strtolower(substr($currentFile, strrpos($currentFile, '.') + 1)) == 'php' ){
                        $files[] = $currentFile;
                    }
                }
        closedir($dir);
        } else {
            throw new Exception('Error including library in config.');
        }
    foreach ($files as $file) {
        include_once $file;
    }
    $lib = null;
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
//set include path for teh quiz pages (so you don't reference other files, just this) (Part 2/2)

$paths2 = directoryToArray(dirname(__FILE__) . '/logic/', true, true, false);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths2));
include_once("styles.php");



//include the database
include_once("dbLogic.php");
//check database works

    $dbLogic = new DB();
if ($dbLogic == false || $_SESSION["DB_STATUS"] == 0){
        
        if ($_SESSION["DB_REDIRECT"] != 1){
            $_SESSION["DB_REDIRECT"] = 1; //redirecting
            header('Location: ' . CONFIG_ROOT_URL . '/404.php');
            stop();
        }
        
} else {

    //include other config files
    include_once("userBean.php");
   include_once("userLogic.php");
    }


//note: when echo-ing html other language, use  echo (htmlentities($string));

?>