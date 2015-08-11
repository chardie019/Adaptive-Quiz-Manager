<div id="nav">
 <ul>
        <li class="<?php echo echoSelectedClassIfRequestMatches("index", True)?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>">Home</a>
        </li>
        <li> | </li>
        <li class="<?php echo echoSelectedClassIfRequestMatches("take-quiz")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/take-quiz">Take Quiz</a></li>
        <li> | </li>
        <li class="<?php echo echoSelectedClassIfRequestMatches("create-quiz")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/create-quiz">Create Quiz</a></li>
        <li> | </li>
        <li class="<?php echo echoSelectedClassIfRequestMatches("edit-quiz")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz">Edit Quiz</a></li>
        <li> | </li>
        <li class="<?php echo echoSelectedClassIfRequestMatches("stats")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/stats">Statistics</a></li>
        <li> | </li>
        <li class="<?php echo echoSelectedClassIfRequestMatches("about")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/about">About AQM</a></li>
        <li> | </li>
        <li class="<?php echo echoSelectedClassIfRequestMatches("help")?>">
            <a href="<?php echo(CONFIG_ROOT_URL) ?>/help">Help & FAQ</a>
        </li>
    </ul>
</div> <!-- end #nav -->