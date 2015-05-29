<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>
<html>
<head>
    
<meta charset="utf-8"/>
 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>Select Quiz - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
            <div id="content-centre">
 
                <h1>Approved Quiz List </h1>

                <form action="#" method="post">
                    <br />

                    
                    <label class="label">Select Quiz: </label>
                        
                    <select class="quiz_list" name="quizid">
        <?php
        foreach ($answerID as $answerRow) {
        echo "<option value = ".($answerRow["QUIZ_ID"])."> ".$answerRow["QUIZ_NAME"]."</option>";
        }
        ?>
                    </select>
    <!-- pad  the space between submit button and dropdown box -->
                    <br />
                    <br />
                    
                    <p>
                        Select a quiz from the list above, it contains all public quizzes,
                        along with those which you have been assigned private access to attempt. 
                        Upon selection, you will be taken to the Quiz Front Page, where you will
                        be able to view the quiz information and confirm your choice. 
                    </p>
                    
                    <br />
                    <br />
        <?php echo "<input type=\"hidden\" name=\"selectQuizId\" value=". ($answerRow["QUIZ_ID"])." />"?>

                        <button class="mySubmit" type="submit" name="selectQuiz" value="Select Quiz">
                            Select Quiz
                        </button>
                    </form>



            </div> <!-- end #content -->
 

        <?php include('sidebar.php'); ?>
 
        <?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>