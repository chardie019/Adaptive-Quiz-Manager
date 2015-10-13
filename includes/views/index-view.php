<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Home');
$templateLogic->addCustomHeadersStart();
?>
<style type="text/css">
    .right-col, .middle-col, .left-col {
        width: 33.33%;
        text-align: center;
    }
    .left-col {
       float: left; 
    }
    .middle-col {
       display: inline-block; 
    }
    .right-col {
       float: right;
    }   
    .buttonborder {
        border: 1px solid black;
        border-radius: 4px;
        width: 100%;
        height: 100%;
        text-align: center;
        display:block;
        position: relative;
        font-family: courier new;
    }
    
    #buttonborder1, #buttonborder2, #buttonborder3, #buttonborder4, #buttonborder5, #buttonborder6 {
        background-repeat: no-repeat;
        background-position: center;
        background-color: #FFFFFF;
        height:150px;
        border-top-left-radius:4px; /*same as button border */
        border-top-right-radius:4px;
    }

    /*Background images not yet created*/
    #buttonborder1{
        background-image: url("<?php echo STYLES_LOCATION; ?>/AQMtakequiz.png");
    } 

    #buttonborder2{
           background-image: url("<?php echo STYLES_LOCATION; ?>/AQMcreatequiz.png"); 
    } 

    #buttonborder3{
           background-image: url("<?php echo STYLES_LOCATION; ?>/AQMeditquiz.png");
    } 

    #buttonborder4{
            background-image: url("<?php echo STYLES_LOCATION; ?>/AQMstats.png");
    }

    #buttonborder5{
            background-image: url("<?php echo STYLES_LOCATION; ?>/AQMabout.png");
    }

    #buttonborder6{
            background-image: url("<?php echo STYLES_LOCATION; ?>/AQMhelp.png");    
    }
    .menu-icons{
        border-top: 1px solid black; 
        background-color: rgb(233, 172, 0);
        font-size: 1.2em;
        font-family: verdana;
        color: #ffffff;
        text-align: center;
        padding: 5px 10px;
        display: block;
        border-bottom-right-radius:4px; /*same as .buttonborder */
        border-bottom-left-radius:4px;
    }
    .button-container a {
        display: inline-block;
        margin-bottom: 3em;
        width: 80%;
        text-decoration: none;
    }
    .button-container a:hover span {
        background-color: #404040;
    }
</style>
<?php
$templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
<div class="button-container">
    <br />    
    <br />
    <br />
    <div class="left-col">
        <a href="<?php echo (CONFIG_ROOT_URL); ?>/take-quiz.php" >
            <div class="buttonborder">
                <div id="buttonborder1">
                </div>
            <span class="menu-icons">Take Quiz</span>
            </div>
        </a>
        <a href="<?php echo (CONFIG_ROOT_URL); ?>/edit-quiz/create-quiz.php" >
            <div class="buttonborder">
                <div id="buttonborder2">
                </div>
                <span class="menu-icons">Create Quiz</span>
            </div>
        </a>
    </div>
    <div class="middle-col">
        <a href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz.php">
            <div class="buttonborder">
                <div id="buttonborder3">
                </div>
                <span class="menu-icons">Manage Quiz</span>
            </div>
        </a>
        <a href="<?php echo(CONFIG_ROOT_URL) ?>/stats.php">
            <div class="buttonborder">
                <div id="buttonborder4">
                </div>
                <span class="menu-icons">Statistics</span>
            </div>
        </a>
    </div>
    <div class="right-col">
        <a href="<?php echo(CONFIG_ROOT_URL) ?>/about.php">
            <div class="buttonborder">
                <div id="buttonborder5">
                </div>
                <span class="menu-icons">About AQM</span>
            </div>
        </a>
        <a href="<?php echo(CONFIG_ROOT_URL) ?>/help.php">
            <div class="buttonborder">
                <div id="buttonborder6">
                </div>
                <span class="menu-icons">Help</span>
            </div>
        </a>
    </div>
</div>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
