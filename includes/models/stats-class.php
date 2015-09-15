<?php
class stats extends quizLogic {
    var $owner;
  
    function sample ($name) {
        $this->owner = $name;
    }
}
?>