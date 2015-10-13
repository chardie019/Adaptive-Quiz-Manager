<?php
/**
 * returns the inputted class if teh request URl matches some rules
 * 
 * @param string $requestUri the string to look for inside the URL
 * @param string $class the to output if the raules match
 * @param boolean $rootLink is this a root link or not (doesn't do matching, only match if there are no matches)
 * @return string return the class or an empty string depending if it matches or not
 */
function echoClassIfRequestMatches($requestUri, $class, $rootLink = false /* otherUri's */)
{
    //get the 4 and above augments + normal requestUri and put in array
    $args = array_slice(func_get_args(), 2); //ignore first two parameters
    $args[] = $requestUri; //add to array
        
    $currentFileName = basename($_SERVER['REQUEST_URI'], ".php");
    $url = $_SERVER['REQUEST_URI'];
    $inUrl = FALSE;

    $isRoot = false;
    if ($rootLink == false){
        foreach ($args as $requestUriArg) {
            //if in url
            if (strpos($url, $requestUriArg) !== false) {
                $inUrl = true;
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
        return $class;
    }
}