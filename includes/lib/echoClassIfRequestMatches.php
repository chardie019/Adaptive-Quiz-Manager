<?php
function echoClassIfRequestMatches($requestUri, $class, $rootLink = false)
{
    $currentFileName = basename($_SERVER['REQUEST_URI'], ".php");
    $url = $_SERVER['REQUEST_URI'];
    $inUrl = FALSE;

    $isRoot = false;
    if ($rootLink == false){
        if (strpos($url, $requestUri) !== false) {
            $inUrl = true;
        }
    }
    if ($rootLink == True){
        if (($_SERVER['REQUEST_URI'] == CONFIG_ROOT_URL) ||
            ($_SERVER['REQUEST_URI'] == CONFIG_ROOT_URL . '/')) {
            $isRoot = True;
        }
    }
    if (is_string($rootLink)) {
        $rootInURL = false;
        $rootIsNotInURL = false; //overides
        if (strpos($url, $rootLink) !== false) {
            $rootInURL = true;
        }
        if (strpos($url, $requestUri) !== false) {
            $rootIsNotInURL = true; //overide root
        }
        if ($rootInURL == true && $rootIsNotInURL == false){
            $inUrl = true;  //root wasn't overided by another link
        }
        
    }
   
    //if on the same url OR url is same one a folder down OR is root folder (the aqm folder)
    if (($currentFileName == $requestUri)  || ($inUrl) || ($isRoot)){
        echo $class;
    }
}
?>