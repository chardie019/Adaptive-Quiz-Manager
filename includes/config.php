<?php

/* 
 * The core bootstrap loaded by all files. 
 * 
 * To disable error displays, change the below "define( 'CONFIG_DEV_ENV', true);" to false
 */
//are developing
define( 'CONFIG_DEV_ENV', true);
//DB variables
define( 'DB_HOST', "localhost");
define( 'DB_DB', "aqm");
define( 'DB_USERNAME', "aqm");
define( 'DB_PASSWORD', "jc66882Dxc9D");

if (CONFIG_DEV_ENV == true){
    assert_options(ASSERT_ACTIVE, 1); //enable asseration
    assert_options (ASSERT_CALLBACK, array('configLogic', 'assertFailed'));
    ini_set("log_errors", 1);
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
    ini_set("error_log", "not_synced/PHP_errors.log"); //relative to htaccess in the aqm
} else {
    assert_options(ASSERT_ACTIVE, 0); //diable assertions sp no performance hit
}

//define site variables (not styles)
define( 'CONFIG_ROOT_DIR', dirname(dirname(__FILE__))); // C:\xampp\htdocs\aqm <inlude file from another location relative to here? >
define( 'CONFIG_ROOT_URL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(CONFIG_ROOT_DIR)))); //    /aqm   <use to set the css location on another php file>
//define('INCLUDES', __DIR__);        //  C:\xampp\htdocs\aqm\includes <include location>


//independant files (needed by others)
//include all files in the "lib" & models directory
/*
 * models is autoloaded now
 * $includePhpFilesArray = array('/lib/', '/models/');     
 */
$includePhpFilesArray = array('/lib/');
foreach ($includePhpFilesArray as $folder){
    try {
        $files = array();
        $lib = dirname(__FILE__) . $folder;
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
            include_once dirname(__FILE__).$folder.$file;
        }
        $lib = null;
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

//set include path (so you don't reference other files, just this)
$slash = DIRECTORY_SEPARATOR;
$directoriesToInclude = array (
    '',                          //include directory
    $slash.'..'.$slash.'about',
    $slash.'..'.$slash.'edit-quiz',
    $slash.'..'.$slash.'help',
    $slash.'..'.$slash.'stats'
);
foreach ($directoriesToInclude as $dir){
    $path = directoryToArrayAndAddCurrentDirectory(dirname(__FILE__) . $dir, true, true, false);
    set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $path));
}
//set include path (so you don't reference other files, just this)
$OtherDirectoriesToInclude = array (
    '/../',                 //root directory
);
//dont explore these fodler
foreach ($OtherDirectoriesToInclude as $dir){
    $path = dirname(__FILE__) . $dir;
    set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}

//set global settings
mb_internal_encoding(); //set internal utf-8 encoding
mb_http_output();       //mb_* string functions must still be used
date_default_timezone_set('Australia/Sydney'); // set default timezone incase system is set to wrong time (and avoid apache error)

if(session_id() == '') { //it may of been started eariler eg login file.
    session_start();
}
//auto classnames by classname.php 
//note not using default (the no parms version) since php 5.3 convert to lowercase, we don't want that
//spl_autoload_register();
spl_autoload_register( function( $class ) { include $class . '.php'; });

//php files needed by all
include_once("quizLogic.php");

include_once("styles.php");

require_once('templateLogic.php');

//check database works
include_once("dbLogic.php");

//test DB works
$dbLogic = new dbLogic();

//include other config files
include_once("userLogic.php");

include_once("userLogin.php");
//note: when echo-ing html other language, use  echo (htmlentities($string));

