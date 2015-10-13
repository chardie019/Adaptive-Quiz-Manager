<?php
    /**
     * Get an array that represents directory tree (and a the inputted directory)
     * 
     * Also returns the current directory
     * 
     * from http://php.net/manual/en/function.scandir.php
     * @param string $directory     Directory path
     * @param bool $recursive         Include sub directories
     * @param bool $listDirs         Include directories on listing
     * @param bool $listFiles         Include files on listing
     * @param regex $exclude         Exclude paths that matches this regex
     */
    function directoryToArrayAndAddCurrentDirectory ($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = ''){
        $arrayItems = directoryToArray($directory, $recursive, $listDirs, $listFiles, $exclude);
        //add the current directory
        $arrayItems[] = $directory;
        return $arrayItems;
    }
    /**
    * Get an array that represents directory tree
    * 
    * from http://php.net/manual/en/function.scandir.php
    * @param string $directory     Directory path
    * @param bool $recursive         Include sub directories
    * @param bool $listDirs         Include directories on listing
    * @param bool $listFiles         Include files on listing
    * @param regex $exclude         Exclude paths that matches this regex
    */
    function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
        $arrayItems = array();
        $skipByExclude = false;
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
                if($exclude){
                    preg_match($exclude, $file, $skipByExclude);
                }
                if (!$skip && !$skipByExclude) {
                    if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                        if($recursive) {
                            $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
                        }
                        if($listDirs){
                            $file = $directory . DIRECTORY_SEPARATOR . $file;
                            $arrayItems[] = $file;
                        }
                    } else {
                        if($listFiles){
                            $file = $directory . DIRECTORY_SEPARATOR . $file;
                            $arrayItems[] = $file;
                        }
                    }
                }
            }
            closedir($handle);
        }
        return $arrayItems;
    }
?>