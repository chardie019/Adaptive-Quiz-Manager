<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Help and FAQ');
$templateLogic->startBody();
?>

<script type="text/javascript">
	function sHide(x){

				   if(document.getElementById(x) .style.display=="none") {
					   document.getElementById(x).style.display="block";
				   }
				   else{
					   document.getElementById(x).style.display='none';
				   }
			   }
</script>

<style>
.myButton {
	background-color:#e9ab00;
	border-radius:42px;
	border:1px solid #dcdcdc;
	display:inline-block;
	cursor:pointer;
	color:#f6f6f6;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:16px 24px;
	text-decoration:none;
}
.myButton:hover {
	background-color:#f6f6f6;
	color:#666666;
}
.myButton:active {
	position:relative;
	top:1px;
}
</style>

<div id="hasScript" style="display:none">
	<br />
	<p>
	<button title="Show/Hide" class="myButton" type="button" onclick="sHide('help')">How to...</button>

	<div id="help" style="display:none"> 
		
		<br />
		<p>
		<button title="Show/Hide" class="myButton" type="button" onclick="sHide('take')">Take a quiz</button>

		<div id="take" style="display:none"> 
		<p>1. Select take a quiz, the site will display a list of quizzes you can take.</p>
		<p>2. Select a quiz and you will be shown details about that quiz, click begin to proceed.</p>
		<p>3. A page consisting of feedback on the previous answer (except on the first page), 
			learning content and a multiple choice question will be shown. Read carefully and select an answer.</p>
		<p>4. Click submit and the site will store your answer and show you the next question.</p>
		<p>5. Repeat this process until you reach the end of the quiz.</p>
		<p>NOTE: it is possible for a question to loop to previous questions or even to itself depending on the answer chosen,
			if stuck in a loop try re-reading the learning content and answering again.</p>
		<p>6. After submitting the final question you will be shown your results which can also be viewed later.</p>
		
		</div> 
		</p>

		<br />
		<p>
		<button title="Show/Hide" class="myButton" type="button" onclick="sHide('createquiz')">Create or Edit a quiz</button>

		<div id="createquiz" style="display:none">
		<h4>Create a quiz</h4>		
		<p>1. Select create a quiz and the site will display a form of quiz details for you to fill.</p>
		<p>2. Read carefully through the form filling in details and selection options where necessary.</p>
		<p>3. Press the create button to create the quiz.</p>
		<p>4. You will be shown the edit quiz menu, please ensure your quiz is disabled before editing it.</p>
		<p>5. When finished editing the quiz, enable it on the edit quiz menu and it will be ready to take.</p>
		
		<br />
		<h4>Edit a quiz</h4>
		<p>1. To edit a quiz select Edit Details from the Edit Quiz menu.</p> 
		<p>2. Make the desired changes and click Update.</p>
		</div> 
		</p>
	
		<br />
		<p>
		<button title="Show/Hide" class="myButton" type="button" onclick="sHide('createquestion')">Create questions and answers</button>

		<div id="createquestion" style="display:none"> 
		<h4>Create Questions</h4>
		<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
		<p>2a. If the quiz contains no questions, click the Add Question button followed by the Add Initial Question button.</p>
		<p>3a. Enter the question and answer details asked for and click Create.</p>
		<br />
		<p>2b. If the quiz already contains one or more questions,
		click on the answer you would like to link to the new question then click Add Question.</p>
		<p>3b. Enter the question details asked for and click Create.</p>
		
		<br />
		<h4>Create Answers</h4>
		<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
		<p>2. Click on the question you would like to add an answer to and click Add Answer.</p>
		<p>3. Enter the answer details asked for and click Create.</p>
		
		<br />
		<h4>Edit Questions and Answers</h4>
		<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
		<p>2. Click on the question or answer to edit and then click Inspect.</p>
		<p>3. Edit the details as desired then click Update.</p>
		
		<br />
		<h4>Remove Questions and Answers</h4>
		<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
		<p>2. Click on the question or answer to remove and then click Remove.</p>
		</div> 
		</p>
		
		<br />
		<p>
		<button title="Show/Hide" class="myButton" type="button" onclick="sHide('users')">Manage quiz takers and editors</button>

		<div id="users" style="display:none">
		<h4>Add Editors and Takers</h4>
		<p>1. From the Edit Quiz menu, select the Manage Editors or Manage Takers option.</p>
		<p>2. Enter the CSU username of the person(s) you want to edit or take the quiz and click Add user.</p>
		
		<br />
		<h4>Remove Editors and Takers</h4>
		<p>1. From the Edit Quiz menu, select the Manage Editors or Manage Takers option.</p>
		<p>2. Enter the CSU username of the person(s) you no longer want to edit or take the quiz and click Remove user.</p>
		</div> 
		</p>
	
		<br />
		<p>
		<button title="Show/Hide" class="myButton" type="button" onclick="sHide('stat')">View statistics or results</button>

		<div id="stat" style="display:none">
		<h4>Quiz Statistics</h4>
		<p>1. Select Statistics from the navigation bar or home menu.</p>
		<p>2. Select Editor results in the Created quiz results box.</p>
		<p>3. Select the quiz you wish to view the results for.</p>
		<p>4. Statistics will be displayed, use the previous results button to
		view statistics of outdated versions of the quiz.</p>
		
		<br />
		<h4>Personal Results</h4>
		<p>1. Select Statistics from the navigation bar or home menu.</p>
		<p>2. Select Taker results in the Personal quiz results box.</p>
		<p>3. Select the quiz you wish to view the results for.</p>
		<p>4. Results will be displayed, use the previous versions button to
		view results from outdated versions of the quiz.</p>
		</div> 
		</p>

	</div> 
	</p>
	
	<br />
	<br />
	
	<p>
	<button title="Show/Hide" class="myButton" type="button" onclick="sHide('faq')">Frequently Asked Questions</button>

	<div id="faq" style="display:none"> 
	FAQ CONTENT HERE 
	</div> 
	</p>
</div>

<script type="text/javascript">
document.getElementById('hasScript').style.display='block';
</script>

<noscript>
<br />
<p>
<h2>How to...</h2>

	<br />
	<h4>Take a quiz</h4>

	<p>1. Select take a quiz, the site will display a list of quizzes you can take.</p>
	<p>2. Select a quiz and you will be shown details about that quiz, click begin to proceed.</p>
	<p>3. A page consisting of feedback on the previous answer (except on the first page), 
		learning content and a multiple choice question will be shown. Read carefully and select an answer.</p>
	<p>4. Click submit and the site will store your answer and show you the next question.</p>
	<p>5. Repeat this process until you reach the end of the quiz.</p>
	<p>NOTE: it is possible for a question to loop to previous questions or even to itself depending on the answer chosen,
		if stuck in a loop try re-reading the learning content and answering again.</p>
	<p>6. After submitting the final question you will be shown your results which can also be viewed later.</p>
	
	<br />
	<h4>Create a quiz</h4>		
	<p>1. Select create a quiz and the site will display a form of quiz details for you to fill.</p>
	<p>2. Read carefully through the form filling in details and selection options where necessary.</p>
	<p>3. Press the create button to create the quiz.</p>
	<p>4. You will be shown the edit quiz menu, please ensure your quiz is disabled before editing it.</p>
	<p>5. When finished editing the quiz, enable it on the edit quiz menu and it will be ready to take.</p>
	
	<br />
	<h4>Edit a quiz</h4>
	<p>1. To edit a quiz select Edit Details from the Edit Quiz menu.</p> 
	<p>2. Make the desired changes and click Update.</p>
	
	<br />
	<h4>Create Questions</h4>
	<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
	<p>2a. If the quiz contains no questions, click the Add Question button followed by the Add Initial Question button.</p>
	<p>3a. Enter the question and answer details asked for and click Create.</p>
	<br />
	<p>2b. If the quiz already contains one or more questions,
	click on the answer you would like to link to the new question then click Add Question.</p>
	<p>3b. Enter the question details asked for and click Create.</p>
	
	<br />
	<h4>Create Answers</h4>
	<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
	<p>2. Click on the question you would like to add an answer to and click Add Answer.</p>
	<p>3. Enter the answer details asked for and click Create.</p>
	
	<br />
	<h4>Edit Questions and Answers</h4>
	<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
	<p>2. Click on the question or answer to edit and then click Inspect.</p>
	<p>3. Edit the details as desired then click Update.</p>
	
	<br />
	<h4>Remove Questions and Answers</h4>
	<p>1. From the Edit Quiz menu, select the Edit Questions option.</p>
	<p>2. Click on the question or answer to remove and then click Remove.</p>
	
	<br />
	<h4>Add Editors and Takers</h4>
	<p>1. From the Edit Quiz menu, select the Manage Editors or Manage Takers option.</p>
	<p>2. Enter the CSU username of the person(s) you want to edit or take the quiz and click Add user.</p>
	
	<br />
	<h4>Remove Editors and Takers</h4>
	<p>1. From the Edit Quiz menu, select the Manage Editors or Manage Takers option.</p>
	<p>2. Enter the CSU username of the person(s) you no longer want to edit or take the quiz and click Remove user.</p>
	
	<br />
	<h4>Quiz Statistics</h4>
	<p>1. Select Statistics from the navigation bar or home menu.</p>
	<p>2. Select Editor results in the Created quiz results box.</p>
	<p>3. Select the quiz you wish to view the results for.</p>
	<p>4. Statistics will be displayed, use the previous results button to
	view statistics of outdated versions of the quiz.</p>
	
	<br />
	<h4>Personal Results</h4>
	<p>1. Select Statistics from the navigation bar or home menu.</p>
	<p>2. Select Taker results in the Personal quiz results box.</p>
	<p>3. Select the quiz you wish to view the results for.</p>
	<p>4. Results will be displayed, use the previous versions button to
	view results from outdated versions of the quiz.</p>
</p>

<br />
<br />

<p>
<h2>FAQ</h2>

	<p> 
	FAQ CONTENT HERE 
	</p> 
	
</p>
</noscript>

<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
