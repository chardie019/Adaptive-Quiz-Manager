<?php 
//add ?quiz=ID to the nav buttons
$quizIDGet = filter_input(INPUT_GET, "quiz");
if (!is_null($quizIDGet)){
    $quizIDMenu = '?quiz=' . $quizIDGet;
} else {
    $quizIDMenu = "";
}
?>
<div id='edit-console'>
    <a class='nav-button<?php echo echoClassIfRequestMatches($this->subMenuIndex, " selectedSubMenu", "edit-quiz.php")?>' 
        href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz.php<?php echo $quizIDMenu; ?>">Edit Quiz</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("details.php", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/details.php<?php echo $quizIDMenu; ?>">Edit Details</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("question.php", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/question.php<?php echo $quizIDMenu; ?>">Edit Questions</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("editors.php", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/editors.php<?php echo $quizIDMenu; ?>">Manage Editors</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("takers.php", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/takers.php<?php echo $quizIDMenu; ?>">Manage Takers</a>
</div>