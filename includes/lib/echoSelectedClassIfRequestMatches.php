<?php
function echoSelectedClassIfRequestMatches($requestUri, $rootLink = false)
{
    $currentFileName = basename($_SERVER['REQUEST_URI'], ".php");
    $urlArray = explode('/', $_SERVER['REQUEST_URI']);
    $current2ndLastFolderName = $urlArray[count($urlArray)-2];
    
    $isRoot = false;
    if ($rootLink == True){
        if (($_SERVER['REQUEST_URI'] == CONFIG_ROOT_URL) ||
            ($_SERVER['REQUEST_URI'] == CONFIG_ROOT_URL  . '/')) {
            $isRoot = True;
        }
    }
   
    //if on the same url OR url is same one a folder down OR is root folder (the aqm folder)
    if (($currentFileName == $requestUri)  || ($current2ndLastFolderName == $requestUri) || ($isRoot)){
        echo 'selected';
    }
}
?>