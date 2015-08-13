<?php 
$quizIDGet = filter_input(INPUT_GET, "quiz");
if (!is_null($quizIDGet)){
    $quizIDMenu = '/' . $quizIDGet . '/';
} else {
    $quizIDMenu = "";
}
?>
<div id='edit-console'>
    <a class='nav-button<?php echo echoClassIfRequestMatches($this->subMenuIndex, " selectedSubMenu", "edit-quiz")?>' 
        href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz<?php echo $quizIDMenu; ?>">Edit Quiz</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("details", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/details<?php echo $quizIDMenu; ?>">Edit Details</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("question", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/question<?php echo $quizIDMenu; ?>">Edit Questions</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("editors", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/editors<?php echo $quizIDMenu; ?>">Manage Editors</a>
    <a class='nav-button<?php echo echoClassIfRequestMatches("takers", " selectedSubMenu")?>' 
       href="<?php echo(CONFIG_ROOT_URL) ?>/edit-quiz/takers<?php echo $quizIDMenu; ?>">Manage Takers</a>
</div>