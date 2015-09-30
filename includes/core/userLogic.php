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
    
    function __construct ($username, $admin = false) {
            $this->dbLogic = new DB();  //set a db conenction per user
            $this->username = $username;
            $this->admin = $admin;
            if ($this->exists() === false) {
                $this->create();
            }
    }
    
    //add useername to DB
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
    //returns True if account exists
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
    public function isAdmin() {
        if ($this->admin === true) {
            return "administrator";
        } else {
            return "standard";
        }
    }
}