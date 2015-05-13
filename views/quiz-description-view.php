<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        echo"<table border=2>";
        echo "<tr><td>Quiz Id</td><td>".$answerID["QUIZ_ID"]."</td></tr>";
        echo "<tr><td>Quiz Name</td><td>".$answerID["QUIZ_NAME"]."</td></tr>";
        echo "<tr><td>Public</td><td>".$answerID["IS_PUBLIC"]."</td></tr>";
        echo"</table>";
        ?>
    </body>
</html>
