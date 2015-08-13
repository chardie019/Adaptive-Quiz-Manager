<?php
function echoClassIfRequestMatches($requestUri, $class, $rootLink = false)
{
    $currentFileName = basename($_SERVER['REQUEST_URI'], ".php");
    $urlArray = explode('/', $_SERVER['REQUEST_URI']);
    $inUrl = FALSE;

    $isRoot = false;
    if ($rootLink == false){
        foreach($urlArray as $urlArrayString) {
            if ($urlArrayString == $requestUri){
                $inUrl = true;
                break; //no need to keep going
            }     
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
        foreach($urlArray as $urlArrayString) {
            if ($urlArrayString == $rootLink){
                $rootInURL = true;
                //problem
            }

            if ($urlArrayString != "" && $urlArrayString == $requestUri){
                $rootIsNotInURL = true;
            }
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