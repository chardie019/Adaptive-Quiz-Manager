<?php

/* 
 * This Class creates and set user permssions (admin or not)
 */

class userLogic {
    private $admin;
    private $username;
    /**
     * creates a user instance
     * 
     * @param string $username the username to be used/created (if not supllied, it is NULL -= no one logged in)
     * @param boolean $admin true if they should be an admin
     */
    function __construct ($username = NULL, $admin = false) {
            $this->username = $username;
            $this->admin = $admin;
            if (isset($username) && self::exists($username) === false) {  //if not NULL & not exists
                self::create($username, $admin);
            }
    }
    /**
     * Returns a boolean if the user is an admin or not
     * 
     * @return boolean false if not admin, true is they are
     */
    public function getUserTypeDisplay() {
        if ($this->admin === true) {
            return "administrator";
        } else {
            return "standard";
        }
    }
    /**
     * Returns true they are an admin
     * 
     * @return boolean true is admin, false if not
     */
    public function getUserTypeBoolean() {
        if ($this->admin === true) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Tells you if user is logged in or not
     * 
     * @return boolean true if user logged in, false if not
     */
    public function isAUserLoggedIn (){
        if (isset($this->username)) {
            return true; //yes there is someone logged in
        } else {
            return false; //no one here
        }
    }
    /**
     * returns the username
     * 
     * @return string|NULL returns the username or NULL if no one logged in
     */
    public function getUsername() {
        if (isset($this->username)) {
            return $this->username; //return name
        } else {
            return NULL; //return NULL
        } 
    }
    /**
     * Add useername to DB
     * 
     * @param string $username the username to be used
     * @param string $admin [optional] set user to admin or not (default is not)
     */
    private static function create($username, $admin = false) {
        $dbLogic = new dbLogic();
        if ($admin === true) { //if true
            $adminToggle = "1";
        } else {
            $adminToggle = "0";
        }
        //make the mysql data
        $data = array(
            "USERNAME"      => $username,
            "ADMIN_TOGGLE"  => $adminToggle
        );
        $dbLogic->insert($data, "user");
    }
    /**
     * Returns True if account exists
     * 
     * @param string $username the username to check if exists
     * @return boolean return true if exists, false if not
     */
    private static function exists($username) {
        $dbLogic = new dbLogic();
        $data = array("USERNAME" => $username);
        $results = $dbLogic->select("USERNAME", "user", $data);
        if (count($results) == 0){
            return False;
        } else {
            return True;
        }
    }
    /**
     * Checks  the database if the user specified exists and creates it if not exist
     * 
     * @param string $username the username to check if exists & create
     * @param boolean $admin [optioanl] create user as an admin (if they don't exist) (true for admin, false if not [default])
     */
    public static function createUserIfNotExist($username, $admin = false){
        if (isset($username) && self::exists($username) === false) {  //if not NULL & not exists
            self::create($username, $admin);
        }
    }
    public static function setUserType ($username, $admin = false){
        $dbLogic = dbLogic();
        if ($admin === true) {
            $adminToggle = "1";
        } else {
            $adminToggle = "0";
        }
        $setValuesArray = array ("ADMIN_TOGGLE"  => $adminToggle);
        $whereValuesArray = array("USERNAME" => $username);
        $tables = "user";
        $dbLogic->updateSetWhere($tables, $setValuesArray, $whereValuesArray);
    }
}