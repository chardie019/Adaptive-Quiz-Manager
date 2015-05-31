<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//getters and setters


//user class

//admin class


class userBean {
    
    function __construct ($admin = false) {
        //stub
    }
    
    //add useername to DB
    public function create($username, $admin = false) {
        $dbLogic = new DB();
        if ($admin) { //if true
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
        return;
    }
    //returns True if account exists
    public function exists($username, $admin = false) {
        $dbLogic = new DB();
        
        $data = array(
            "USERNAME" => $username
        );

        $results = $dbLogic->select("USERNAME", "user", $data);
        
        if (count($results) == 0){
            return False;
        } else {
            return True;
        }
    }
}
?>