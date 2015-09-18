<?php
class configLogic {
    /**
     * stub
     */
    public static function assertFailed($file, $line, $expr) {
        print "Assertion failed in $file on line $line: $expr\n";
    }

    public static function loadErrorPage($errorMessage = "", $errorMessageSpecific = "") {
        include '404.php';
        exit;
    }
}
