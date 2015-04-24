<?php 
    $firstName = "";
    $lastName = "";
    $age = "";
    $ageError = "";
    $deleteError = "";
    
    require_once("../includes/config.php");
    //create and instance of the DB class

    $DbClass = new DB();

        

    
    
if ($_SERVER['REQUEST_METHOD'] === "POST") { 
    if (!empty($_POST['add'])) { //adding
        //do something here;
        $firstName = filter_input(INPUT_POST, "firstName");
        $lastName = filter_input(INPUT_POST, "lastName");
        $age = filter_input(INPUT_POST, "age");
        if (intval(filter_input(INPUT_POST, "age")) == 0){
            $ageError = "Age has to be a number!";
        } else {            
            $data = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "age" => $age
            );
            $DbClass->insert($data, "test");
            //reset
            $firstName = "";
            $lastName = "";
            $age = "";
            $ageError = "";
            $deleteError = "";
        }
    }else { //deleteing
        $id = filter_input(INPUT_POST, "id");
        if ($id != "") { //not empty
            //retrieve data
            $DbClass->delete("id", $id, "test");
        } else {
            $deleteError = "An option must be selected in order to delete it!";
        }
    }
}
?>