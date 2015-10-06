<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//getters and setters


//user class

//admin class


class userLogic {
    private $admin;
    private $username;
    private $dbLogic; //reuse the db access everytime
    
    /**
     * creates a user instance
     * 
     * @param string $username the username to be used/created (if not supllied, it is NULL -= no one logged in)
     * @param boolean $admin true if they should be an admin
     */
    function __construct ($username = NULL, $admin = false) {
            $this->dbLogic = new DB();  //set a db conenction per user
            $this->username = $username;
            $this->admin = $admin;
            if (isset($this->username) && $this->exists() === false) {  //if not NULL & exists
                $this->create();
            }
    }
    /**
     * add useername to DB
     */
    private function create() {
        if ($this->admin === true) { //if true
            $adminToggle = "1";
        } else {
            $adminToggle = "0";
        }
        //make the mysql data
        $data = array(
            "USERNAME"      => $this->username,
            "ADMIN_TOGGLE"  => $adminToggle
        );
        $this->dbLogic->insert($data, "user");
    }
    /**
     * Returns True if account exists
     */
    private function exists() {
        $data = array("USERNAME" => $this->username);
        $results = $this->dbLogic->select("USERNAME", "user", $data);
        if (count($results) == 0){
            return False;
        } else {
            return True;
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

}