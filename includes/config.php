<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// Report all PHP errors (disable to hide mysql error)
//error_reporting(-1);


define( 'CONFIG_DEV_ENV', true); //are developing

if (CONFIG_DEV_ENV == true){
    assert_options(ASSERT_ACTIVE, 1); //enable asseration
    assert_options (ASSERT_CALLBACK, 'assertFailed');
    ini_set("log_errors", 1);
    ini_set("error_log", "not_synced/PHP_errors.log"); //relative to htaccess in the aqm
} else {
    assert_options(ASSERT_ACTIVE, 0); //diable assertions & no performance hit
}

function assertFailed($file, $line, $expr) {
    print "Assertion failed in $file on line $line: $expr\n";
}

function loadErrorPage() {
    include '404.php';
    exit;
}

//DB variables
define( 'DB_HOST', "localhost");
define( 'DB_DB', "aqm");
define( 'DB_USERNAME', "aqm");
define( 'DB_PASSWORD', "jc66882Dxc9D");


//define site variables (not styles)
define( 'CONFIG_ROOT_DIR', dirname(dirname(__FILE__))); // C:\xampp\htdocs\aqm <inlude file from another location relative to here? >
define( 'CONFIG_ROOT_URL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(CONFIG_ROOT_DIR)))); //    /aqm   <use to set the css location on another php file>
//define('INCLUDES', __DIR__);        //  C:\xampp\htdocs\aqm\includes <include location>


//set include path (so you don't reference other files, just this) (Part 1/2)
$paths = array(
    dirname(__FILE__) . '/../',                 //root directory
    dirname(__FILE__),                          //include directory
    dirname(__FILE__) . '/core/',               //core directory
    dirname(__FILE__) . '/views/',              //views directory
    dirname(__FILE__) . '/templates/',          //templates directory
    dirname(__FILE__) . '/subMenus/',           //templates directory
    dirname(__FILE__) . '/lib/',                //libraries directory
    dirname(__FILE__) . '/related-logic/'        //logic directory (related logic to the pages)
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
$OtherDirectoriesToInclude = array (
    '/related-logic/',
    '/views/',
    '/../about/',
    '/../edit-quiz/',
    '/../help/',
    '/../stats/',
    '/../edit-quiz/'  
);
foreach ($OtherDirectoriesToInclude as $dir){
    $path = directoryToArray(dirname(__FILE__) . $dir, true, true, false);
    set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $path));
}
include_once("styles.php");




//check database works
include_once("dbLogic.php");

//test DB works
$dbLogic = new DB();

//include other config files
include_once("userBean.php");
include_once("quizLogic.php");

include_once("userLogic.php");
//note: when echo-ing html other language, use  echo (htmlentities($string));

?>