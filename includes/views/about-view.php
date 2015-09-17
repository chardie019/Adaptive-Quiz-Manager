<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('About AQM');
$templateLogic->startBody();
?>

<p>
The Adaptive Quiz Manager allows the creation, management and taking of online quizzes in a manner that addresses each
individual quiz taker's needs and weaknesses.
<br />
This is achieved by allowing the creation of flowchart-like quizzes where a quiz taker's understanding of the topic
can lead them through different questions or even loop through previous content until they understand it.
<br />
The quizzes consist of 'question pages' with each containing learning content, a question and its answers which can each
have its own link to a different question page as well as unique feedback.
<br />
The site then allows the creator of a quiz to examine answers given, time taken and other statistics to determine
more effective ways to teach the content.
</p>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
