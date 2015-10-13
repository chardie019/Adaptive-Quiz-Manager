<?php 
/**
 * Sub menu html for edit quiz menu
 */
//add ?quiz=ID to the nav buttons
$quizIDGet = filter_input(INPUT_GET, "quiz");
if (!is_null($quizIDGet)){
    $quizIDMenu = '?quiz=' . $quizIDGet;
} else {
    $quizIDMenu = "";
}
?>
<div id='edit-console'>
    <?php echo "<$this->SubMenuLinkElement class='nav-button". echoClassIfRequestMatches($this->subMenuIndex, " selectedSubMenu", "edit-quiz.php"). "$this->greyedOutClass' " . 
        "href=\"" . CONFIG_ROOT_URL . "/edit-quiz.php$quizIDMenu\">Edit Quiz</$this->SubMenuLinkElement>".PHP_EOL;
    echo "<$this->SubMenuLinkElement class='nav-button". echoClassIfRequestMatches("details", " selectedSubMenu"). "$this->greyedOutClass' " .
        "href=\"" . CONFIG_ROOT_URL . "/edit-quiz/details.php$quizIDMenu\">Edit Details</$this->SubMenuLinkElement>".PHP_EOL;
    echo "<$this->SubMenuLinkElement class='nav-button". echoClassIfRequestMatches("edit-question", " selectedSubMenu"). "$this->greyedOutClass' " .
        "href=\"" . CONFIG_ROOT_URL . "/edit-quiz/edit-question.php$quizIDMenu\">Edit Questions</$this->SubMenuLinkElement>".PHP_EOL;
    echo "<$this->SubMenuLinkElement class='nav-button". echoClassIfRequestMatches("editors", " selectedSubMenu"). "$this->greyedOutClass' " .
        "href=\"" . CONFIG_ROOT_URL . "/edit-quiz/editors.php$quizIDMenu\">Manage Editors</$this->SubMenuLinkElement>".PHP_EOL;
    echo "<$this->SubMenuLinkElement class='nav-button". echoClassIfRequestMatches("takers", " selectedSubMenu"). "$this->greyedOutClass' " .
        "href=\"" . CONFIG_ROOT_URL . "/edit-quiz/takers.php$quizIDMenu\">Manage Takers</$this->SubMenuLinkElement>".PHP_EOL;?>
</div>