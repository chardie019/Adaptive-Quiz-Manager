<div id="nav">
 <ul>
        <li>
            <a class="<?php echo echoClassIfRequestMatches("index", "selected", True)?>"
               href="<?php echo(CONFIG_ROOT_URL) ?>">Home</a>
        </li>
        <li>
            <a class="<?php echo echoClassIfRequestMatches("take-quiz", "selected")?>"
               href="<?php echo(CONFIG_ROOT_URL) ?>/take-quiz.php">Take Quiz</a>
        </li>
        <li>
            <a class="<?php echo echoClassIfRequestMatches("create-quiz", "selected")?>"
               href="<?php echo(CONFIG_ROOT_URL) ?>/create-quiz.php">Create Quiz</a>
        </li>
        <li>
            <a class="<?php echo echoClassIfRequestMatches("edit-quiz", "selected")?>"
               href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz.php">Edit Quiz</a>
        </li>
        <li>
            <a class="<?php echo echoClassIfRequestMatches("stats", "selected")?>"
               href="<?php echo(CONFIG_ROOT_URL) ?>/stats.php">Statistics</a>
        </li>
        <li>
            <a class="<?php echo echoClassIfRequestMatches("about", "selected")?>"
               href="<?php echo(CONFIG_ROOT_URL) ?>/about.php">About AQM</a>
        </li>
        <li>
            <a class="<?php echo echoClassIfRequestMatches("help", "selected")?>"
               href="<?php echo(CONFIG_ROOT_URL) ?>/help.php">Help & FAQ</a>
        </li>
    </ul>
</div> <!-- end #nav -->