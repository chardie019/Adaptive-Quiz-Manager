<div id="nav">
 <ul>
        <li class="<?php echo echoClassIfRequestMatches("index", "selected", True)?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>">Home</a>
        </li>
        <li> | </li>
        <li class="<?php echo echoClassIfRequestMatches("take-quiz", "selected")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/take-quiz.php">Take Quiz</a></li>
        <li> | </li>
        <li class="<?php echo echoClassIfRequestMatches("create-quiz", "selected")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/create-quiz.php">Create Quiz</a></li>
        <li> | </li>
        <li class="<?php echo echoClassIfRequestMatches("edit-quiz", "selected")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz.php">Edit Quiz</a></li>
        <li> | </li>
        <li class="<?php echo echoClassIfRequestMatches("stats", "selected")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/stats.php">Statistics</a></li>
        <li> | </li>
        <li class="<?php echo echoClassIfRequestMatches("about", "selected")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/about.php">About AQM</a></li>
        <li> | </li>
        <li class="<?php echo echoClassIfRequestMatches("help", "selected")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/help.php">Help & FAQ</a>
        </li>
    </ul>
</div> <!-- end #nav -->