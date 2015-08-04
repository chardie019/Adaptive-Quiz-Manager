<?php
/** 
 * recursively create a long directory path
 * 
 * This will take a path, possibly with a long chain of uncreated directories, 
 * and keep going up one directory until it gets to an existing directory. 
 * Then it will attempt to create the next directory in that directory, and 
 * continue till it's created all the directories. It returns true if successful.
 * source: http://stackoverflow.com/questions/2303372/create-a-folder-if-it-doesnt-already-exist
 */
function createPath($path) {
    if (is_dir($path)) return true;
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
    $return = createPath($prev_path);
    return ($return && is_writable($prev_path)) ? mkdir($path) : false;
}