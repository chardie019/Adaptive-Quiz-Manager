<?php
/**
 * Some functions to all programs share - mostly error displaying etc.
 */
class configLogic {
    /**
     * Show what happened when an assert failed
     * 
     * @param string $file the name of the file that broke
     * @param string $line the specific line of the assert command
     * @param string $expr the expession error code
     */
    public static function assertFailed($file, $line, $expr) {
        print "Assertion failed in $file on line $line: $expr\n";
        echo "<pre>";
        debug_print_backtrace();
        echo "</pre>";
        
    }
    /**
     * Displays the error 404 page with error explanations
     * 
     * @param string [optional] $errorMessage the general error message
     * @param string [optional] $errorMessageSpecific the specific error if provided
     */
    public static function loadErrorPage($errorMessage = "", $errorMessageSpecific = "") {
        include '404.php';
        exit;
    }    
}
