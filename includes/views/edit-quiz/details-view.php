<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Quiz');
$templateLogic->setSubMenuType("edit-quiz", "details");
$templateLogic->startBody();
?>
                
<div id="content-create-quiz">
    <br />
    <br />
    <!--enctype="" used because of image file upload in form-->
        <form action='#' method='post' enctype="multipart/form-data" >                    
                <p class="label">Please enter the Title of your quiz:</p>
                <input type='text' name='quizName' value='<?php echo $quizInfo['QUIZ_NAME'] ?>' size='30'></input>
                <br />
                <?php echo "<span class=\"inputError\">".$quizNameError."</span>"?>
                <br />
                <br />

                <p class="label">Please enter a description for your quiz:</p>
                <textarea name="quizDescription" cols="40" rows="5"><?php echo $quizInfo['DESCRIPTION'] ?></textarea>
                <br />
                <?php echo "<span class=\"inputError\">".$quizDescriptionError."</span>"?>
                <br />
                <p class="label">Is this public or private:</p>
                Public: 
                <input type='radio' name='isPublic' value='1' <?php if ($quizInfo['IS_PUBLIC'] == "1"){echo "checked=\"checked\"";} ?> />
                Private: 
                <input type='radio' name='isPublic' value='0' <?php if ($quizInfo['IS_PUBLIC'] == "0"){echo "checked=\"checked\"";} ?> />
                <br />
                <?php echo "<span class=\"inputError\">".$isPublicError."</span>"?>
                <br />
                <p class="label">Please enter the number of attempts permitted:</p>
                <select name='noAttempts'>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '0'){echo("selected");}?>>Unlimited</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '1'){echo("selected");}?>>1</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '2'){echo("selected");}?>>2</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '3'){echo("selected");}?>>3</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '4'){echo("selected");}?>>4</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '5'){echo("selected");}?>>5</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '6'){echo("selected");}?>>6</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '7'){echo("selected");}?>>7</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '8'){echo("selected");}?>>8</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '9'){echo("selected");}?>>9</option>
                      <option <?php if($quizInfo['NO_OF_ATTEMPTS'] == '10'){echo("selected");}?>>10</option>
                </select>
                <br />
                <?php echo "<span class=\"inputError\">".$noAttemptsError."</span>"?>
                <br />
                <p class="label">Is there a time limit to complete the quiz:</p>
                No:
                <input type='radio' name='isTime' value='0' <?php if($quizInfo['TIME_LIMIT'] == '00:00:00'){echo("checked");}?>></input>
                Yes:
                <input type='radio' name='isTime' value='1' <?php if($quizInfo['TIME_LIMIT'] != '00:00:00'){echo("checked");}?>></input>
                <br />
                <?php echo "<span class=\"inputError\">".$isTimeError."</span>"?>
                <br />
                <p class="label">*** If YES, please enter that time limit: ***</p>
                Hours
                <select name='timeHours'>
                    <option <?php if ($timeHours == "0"){echo "selected=\"selected\"";} ?>>0</option>
                    <option <?php if ($timeHours == "1"){echo "selected=\"selected\"";} ?>>1</option>
                    <option <?php if ($timeHours == "2"){echo "selected=\"selected\"";} ?>>2</option>
                    <option <?php if ($timeHours == "3"){echo "selected=\"selected\"";} ?>>3</option>
                    <option <?php if ($timeHours == "4"){echo "selected=\"selected\"";} ?>>4</option>
                    <option <?php if ($timeHours == "5"){echo "selected=\"selected\"";} ?>>5</option>
                </select>
                Minutes
                <select name='timeMinutes'>
                    <option <?php if ($timeMinutes == "00"){echo "selected=\"selected\"";} ?>>00</option>
                    <option <?php if ($timeMinutes == "05"){echo "selected=\"selected\"";} ?>>05</option>
                    <option <?php if ($timeMinutes == "10"){echo "selected=\"selected\"";} ?>>10</option>
                    <option <?php if ($timeMinutes == "15"){echo "selected=\"selected\"";} ?>>15</option>
                    <option <?php if ($timeMinutes == "20"){echo "selected=\"selected\"";} ?>>20</option>
                    <option <?php if ($timeMinutes == "25"){echo "selected=\"selected\"";} ?>>25</option>
                    <option <?php if ($timeMinutes == "30"){echo "selected=\"selected\"";} ?>>30</option>
                    <option <?php if ($timeMinutes == "35"){echo "selected=\"selected\"";} ?>>35</option>
                    <option <?php if ($timeMinutes == "40"){echo "selected=\"selected\"";} ?>>40</option>
                    <option <?php if ($timeMinutes == "45"){echo "selected=\"selected\"";} ?>>45</option>
                    <option <?php if ($timeMinutes == "50"){echo "selected=\"selected\"";} ?>>50</option>
                    <option <?php if ($timeMinutes == "55"){echo "selected=\"selected\"";} ?>>55</option>
                </select> 
                <br />
                <?php echo "<span class=\"inputError\">".$timeLimitError."</span>"?>
                <br />                
                <p class="label">Can users save progress and return later to the quiz:</p>
                No:
                <input type='radio' name='isSave' value='0' <?php if($quizInfo['IS_SAVABLE'] == '0'){echo("checked");}?>></input>
                Yes:
                <input type='radio' name='isSave' value='1' <?php if($quizInfo['IS_SAVABLE'] == '1'){echo("checked");}?>></input>
                <br />
                <?php echo "<span class=\"inputError\">".$isSaveError."</span>"?>
                <br />
                <br />
                <p class="label">When does this quiz open:</p>
                <select name="dayStart" /> 
                    <option>1</option>       
                    <option>2</option>       
                    <option>3</option>       
                    <option>4</option>       
                    <option>5</option>       
                    <option>6</option>       
                    <option>7</option>       
                    <option>8</option>       
                    <option>9</option>       
                    <option>10</option>       
                    <option>11</option>       
                    <option>12</option>       
                    <option>13</option>       
                    <option>14</option>       
                    <option>15</option>       
                    <option>16</option>       
                    <option>17</option>       
                    <option>18</option>       
                    <option>19</option>       
                    <option>20</option>       
                    <option>21</option>       
                    <option>22</option>       
                    <option>23</option>       
                    <option>24</option>       
                    <option>25</option>       
                    <option>26</option>       
                    <option>27</option>       
                    <option>28</option>       
                    <option>29</option>       
                    <option>30</option>       
                    <option>31</option>       
                </select> - 
                <select name="monthStart" /> 
                    <option>1</option>       
                    <option>2</option>       
                    <option>3</option>       
                    <option>4</option>       
                    <option>5</option>       
                    <option>6</option>       
                    <option>7</option>       
                    <option>8</option>       
                    <option>9</option>       
                    <option>10</option>       
                    <option>11</option>       
                    <option>12</option>       
                </select> - 
                <select name="yearStart" />     
                    <option>2015</option>       
                    <option>2016</option>       
                    <option>2017</option>       
                    <option>2018</option>       
                </select> 
                <span class="dateOrder">(Day-Month-Year)</span> 
                <br />
                <?php echo "<span class=\"inputError\">".$dayStartError."</span>"?>
                <?php echo "<span class=\"inputError\">".$monthStartError."</span>"?>
                <?php echo "<span class=\"inputError\">".$yearStartError."</span>"?>
                <?php echo "<span class=\"inputError\">".$invalidDateError1."</span>"?>
                <br />
                <br />
                <p class="label">When does this quiz close:</p>
                <select name="dayEnd" /> 
                    <option>1</option>       
                    <option>2</option>       
                    <option>3</option>       
                    <option>4</option>       
                    <option>5</option>       
                    <option>6</option>       
                    <option>7</option>       
                    <option>8</option>       
                    <option>9</option>       
                    <option>10</option>       
                    <option>11</option>       
                    <option>12</option>       
                    <option>13</option>       
                    <option>14</option>       
                    <option>15</option>       
                    <option>16</option>       
                    <option>17</option>       
                    <option>18</option>       
                    <option>19</option>       
                    <option>20</option>       
                    <option>21</option>       
                    <option>22</option>       
                    <option>23</option>       
                    <option>24</option>       
                    <option>25</option>       
                    <option>26</option>       
                    <option>27</option>       
                    <option>28</option>       
                    <option>29</option>       
                    <option>30</option>       
                    <option>31</option>       
                </select> - 
                <select name="monthEnd" /> 
                    <option>1</option>       
                    <option>2</option>       
                    <option>3</option>       
                    <option>4</option>       
                    <option>5</option>       
                    <option>6</option>       
                    <option>7</option>       
                    <option>8</option>       
                    <option>9</option>       
                    <option>10</option>       
                    <option>11</option>       
                    <option>12</option>       
                </select> - 
                <select name="yearEnd" />     
                    <option>2015</option>       
                    <option>2016</option>       
                    <option>2017</option>       
                    <option>2018</option>       
                </select> 
                <span class="dateOrder">(Day-Month-Year)</span> 
                <br />
                <?php echo "<span class=\"inputError\">".$dayEndError."</span>"?>
                <?php echo "<span class=\"inputError\">".$monthEndError."</span>"?>
                <?php echo "<span class=\"inputError\">".$yearEndError."</span>"?>
                <?php echo "<span class=\"inputError\">".$invalidDateError2."</span>"?>
                <br />
                
                <p class="label">Is there a cover image to upload with your quiz(optional):</p>
                <input type="file" name="quizImageUpload" accept="image/*">
                <br />
                <img src='<?php echo "C:\Users\Admin\Documents\GitHub\Adaptive-Quiz-Manager\data\quiz-images\\" . $quizInfo["IMAGE"]; ?>' alt='<?php echo $quizInfo['IMAGE_ALT']?>'>
                <?php echo "<span class=\"inputError\">".$imageUploadError."</span>"?>
                <br />
                <p class="label">Please provide alternative text for the image you uploaded:</p>
                <textarea name="quizImageText" cols="40" rows="5"><?php echo $quizInfo['IMAGE_ALT'] ?></textarea>
                <br />
                <button class="mybutton mySubmit" type="submit" name="confirmQuiz" value="Enter">Update</button>
            </form>
                 <a class='mybutton myReturn' href='<?php echo(CONFIG_ROOT_URL) . "/edit-quiz.php?quiz=".$quizIDPost?>'>Cancel</a>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();