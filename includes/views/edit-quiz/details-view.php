<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Quiz');
$templateLogic->setSubMenuType("edit-quiz", "details");
$templateLogic->startBody();
?>
                
<div id="content-create-quiz">
    <br />
    <br />
    <?php echo "<span class=\"inputError\">".$quizEnabledError."</span>"?>
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
                    <option <?php if ($startDay == "1"){echo "selected=\"selected\"";} ?>>1</option>       
                    <option <?php if ($startDay == "2"){echo "selected=\"selected\"";} ?>>2</option>       
                    <option <?php if ($startDay == "3"){echo "selected=\"selected\"";} ?>>3</option>       
                    <option <?php if ($startDay == "4"){echo "selected=\"selected\"";} ?>>4</option>       
                    <option <?php if ($startDay == "5"){echo "selected=\"selected\"";} ?>>5</option>       
                    <option <?php if ($startDay == "6"){echo "selected=\"selected\"";} ?>>6</option>       
                    <option <?php if ($startDay == "7"){echo "selected=\"selected\"";} ?>>7</option>       
                    <option <?php if ($startDay == "8"){echo "selected=\"selected\"";} ?>>8</option>       
                    <option <?php if ($startDay == "9"){echo "selected=\"selected\"";} ?>>9</option>       
                    <option <?php if ($startDay == "10"){echo "selected=\"selected\"";} ?>>10</option>       
                    <option <?php if ($startDay == "11"){echo "selected=\"selected\"";} ?>>11</option>       
                    <option <?php if ($startDay == "12"){echo "selected=\"selected\"";} ?>>12</option>       
                    <option <?php if ($startDay == "13"){echo "selected=\"selected\"";} ?>>13</option>       
                    <option <?php if ($startDay == "14"){echo "selected=\"selected\"";} ?>>14</option>       
                    <option <?php if ($startDay == "15"){echo "selected=\"selected\"";} ?>>15</option>       
                    <option <?php if ($startDay == "16"){echo "selected=\"selected\"";} ?>>16</option>       
                    <option <?php if ($startDay == "17"){echo "selected=\"selected\"";} ?>>17</option>       
                    <option <?php if ($startDay == "18"){echo "selected=\"selected\"";} ?>>18</option>       
                    <option <?php if ($startDay == "19"){echo "selected=\"selected\"";} ?>>19</option>       
                    <option <?php if ($startDay == "20"){echo "selected=\"selected\"";} ?>>20</option>       
                    <option <?php if ($startDay == "21"){echo "selected=\"selected\"";} ?>>21</option>       
                    <option <?php if ($startDay == "22"){echo "selected=\"selected\"";} ?>>22</option>       
                    <option <?php if ($startDay == "23"){echo "selected=\"selected\"";} ?>>23</option>       
                    <option <?php if ($startDay == "24"){echo "selected=\"selected\"";} ?>>24</option>       
                    <option <?php if ($startDay == "25"){echo "selected=\"selected\"";} ?>>25</option>       
                    <option <?php if ($startDay == "26"){echo "selected=\"selected\"";} ?>>26</option>       
                    <option <?php if ($startDay == "27"){echo "selected=\"selected\"";} ?>>27</option>       
                    <option <?php if ($startDay == "28"){echo "selected=\"selected\"";} ?>>28</option>       
                    <option <?php if ($startDay == "29"){echo "selected=\"selected\"";} ?>>29</option>       
                    <option <?php if ($startDay== "30"){echo "selected=\"selected\"";} ?>>30</option>       
                    <option <?php if ($startDay == "31"){echo "selected=\"selected\"";} ?>>31</option>      
                </select> - 
                <select name="monthStart" /> 
                    <option <?php if ($startMonth == "1"){echo "selected=\"selected\"";} ?>>1</option>       
                    <option <?php if ($startMonth == "2"){echo "selected=\"selected\"";} ?>>2</option>       
                    <option <?php if ($startMonth == "3"){echo "selected=\"selected\"";} ?>>3</option>       
                    <option <?php if ($startMonth == "4"){echo "selected=\"selected\"";} ?>>4</option>       
                    <option <?php if ($startMonth == "5"){echo "selected=\"selected\"";} ?>>5</option>       
                    <option <?php if ($startMonth == "6"){echo "selected=\"selected\"";} ?>>6</option>       
                    <option <?php if ($startMonth == "7"){echo "selected=\"selected\"";} ?>>7</option>       
                    <option <?php if ($startMonth == "8"){echo "selected=\"selected\"";} ?>>8</option>       
                    <option <?php if ($startMonth == "9"){echo "selected=\"selected\"";} ?>>9</option>       
                    <option <?php if ($startMonth == "10"){echo "selected=\"selected\"";} ?>>10</option>       
                    <option <?php if ($startMonth == "11"){echo "selected=\"selected\"";} ?>>11</option>       
                    <option <?php if ($startMonth == "12"){echo "selected=\"selected\"";} ?>>12</option>      
                </select> - 
                <select name="yearStart" />     
                    <option <?php if ($startYear == $yearCurrent){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent; ?></option>       
                    <option <?php if ($startYear == $yearCurrent + 1){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 1; ?></option>       
                    <option <?php if ($startYear == $yearCurrent + 2){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 2; ?></option>       
                    <option <?php if ($startYear == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 3; ?></option>     
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
                    <option <?php if ($endDay == "1"){echo "selected=\"selected\"";} ?>>1</option>       
                    <option <?php if ($endDay == "2"){echo "selected=\"selected\"";} ?>>2</option>       
                    <option <?php if ($endDay == "3"){echo "selected=\"selected\"";} ?>>3</option>       
                    <option <?php if ($endDay == "4"){echo "selected=\"selected\"";} ?>>4</option>       
                    <option <?php if ($endDay == "5"){echo "selected=\"selected\"";} ?>>5</option>       
                    <option <?php if ($endDay == "6"){echo "selected=\"selected\"";} ?>>6</option>       
                    <option <?php if ($endDay == "7"){echo "selected=\"selected\"";} ?>>7</option>       
                    <option <?php if ($endDay == "8"){echo "selected=\"selected\"";} ?>>8</option>       
                    <option <?php if ($endDay == "9"){echo "selected=\"selected\"";} ?>>9</option>       
                    <option <?php if ($endDay == "10"){echo "selected=\"selected\"";} ?>>10</option>       
                    <option <?php if ($endDay == "11"){echo "selected=\"selected\"";} ?>>11</option>       
                    <option <?php if ($endDay == "12"){echo "selected=\"selected\"";} ?>>12</option>       
                    <option <?php if ($endDay == "13"){echo "selected=\"selected\"";} ?>>13</option>       
                    <option <?php if ($endDay == "14"){echo "selected=\"selected\"";} ?>>14</option>       
                    <option <?php if ($endDay == "15"){echo "selected=\"selected\"";} ?>>15</option>       
                    <option <?php if ($endDay == "16"){echo "selected=\"selected\"";} ?>>16</option>       
                    <option <?php if ($endDay == "17"){echo "selected=\"selected\"";} ?>>17</option>       
                    <option <?php if ($endDay == "18"){echo "selected=\"selected\"";} ?>>18</option>       
                    <option <?php if ($endDay == "19"){echo "selected=\"selected\"";} ?>>19</option>       
                    <option <?php if ($endDay == "20"){echo "selected=\"selected\"";} ?>>20</option>       
                    <option <?php if ($endDay == "21"){echo "selected=\"selected\"";} ?>>21</option>       
                    <option <?php if ($endDay == "22"){echo "selected=\"selected\"";} ?>>22</option>       
                    <option <?php if ($endDay == "23"){echo "selected=\"selected\"";} ?>>23</option>       
                    <option <?php if ($endDay == "24"){echo "selected=\"selected\"";} ?>>24</option>       
                    <option <?php if ($endDay == "25"){echo "selected=\"selected\"";} ?>>25</option>       
                    <option <?php if ($endDay == "26"){echo "selected=\"selected\"";} ?>>26</option>       
                    <option <?php if ($endDay == "27"){echo "selected=\"selected\"";} ?>>27</option>       
                    <option <?php if ($endDay == "28"){echo "selected=\"selected\"";} ?>>28</option>       
                    <option <?php if ($endDay == "29"){echo "selected=\"selected\"";} ?>>29</option>       
                    <option <?php if ($endDay == "30"){echo "selected=\"selected\"";} ?>>30</option>       
                    <option <?php if ($endDay == "31"){echo "selected=\"selected\"";} ?>>31</option>         
                </select> - 
                <select name="monthEnd" /> 
                    <option <?php if ($endMonth == "1"){echo "selected=\"selected\"";} ?>>1</option>       
                    <option <?php if ($endMonth == "2"){echo "selected=\"selected\"";} ?>>2</option>       
                    <option <?php if ($endMonth == "3"){echo "selected=\"selected\"";} ?>>3</option>       
                    <option <?php if ($endMonth == "4"){echo "selected=\"selected\"";} ?>>4</option>       
                    <option <?php if ($endMonth == "5"){echo "selected=\"selected\"";} ?>>5</option>       
                    <option <?php if ($endMonth == "6"){echo "selected=\"selected\"";} ?>>6</option>       
                    <option <?php if ($endMonth == "7"){echo "selected=\"selected\"";} ?>>7</option>       
                    <option <?php if ($endMonth == "8"){echo "selected=\"selected\"";} ?>>8</option>       
                    <option <?php if ($endMonth == "9"){echo "selected=\"selected\"";} ?>>9</option>       
                    <option <?php if ($endMonth == "10"){echo "selected=\"selected\"";} ?>>10</option>       
                    <option <?php if ($endMonth == "11"){echo "selected=\"selected\"";} ?>>11</option>       
                    <option <?php if ($endMonth == "12"){echo "selected=\"selected\"";} ?>>12</option>      
                </select> - 
                <select name="yearEnd" />     
                    <option <?php if ($endYear == $yearCurrent){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent; ?></option>
                    <option <?php if ($endYear == $yearCurrent + 1){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 1; ?></option>
                    <option <?php if ($endYear == $yearCurrent + 2){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 2; ?></option>
                    <option <?php if ($endYear == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 3; ?></option>     
                </select> 
                <span class="dateOrder">(Day-Month-Year)</span> 
                <br />
                <?php echo "<span class=\"inputError\">".$dayEndError."</span>"?>
                <?php echo "<span class=\"inputError\">".$monthEndError."</span>"?>
                <?php echo "<span class=\"inputError\">".$yearEndError."</span>"?>
                <?php echo "<span class=\"inputError\">".$invalidDateError2."</span>"?>
                <br />
                
                <p class="label">Check this box if you would like to keep the original quiz image. </p>
                <input type="checkbox" name="useCurrentImage" value="yes"/>Keep current cover image
                <br />
                <p class="dateOrder">(Leave it unchecked if you wish to remove the current Quiz Cover image, or 
                select the new file you wish to upload to replace the old Cover image.) </p>
                <br />
                <p class="label">Is there a cover image to upload with your quiz(optional):</p>
                <input type="file" name="quizImageUpload" accept="image/*">
                <br />
                <img src='<?php echo "C:\Users\Admin\Documents\GitHub\Adaptive-Quiz-Manager\Adaptive-Quiz-Manager\data\quiz-images\\" . $quizInfo["IMAGE"]; ?>' alt='<?php echo $quizInfo['IMAGE_ALT']?>'>
                <?php echo "<span class=\"inputError\">".$imageUploadError."</span>"?>
                <br />
                <p class="label">Please provide alternative text for the image you uploaded:</p>
                <textarea name="quizImageText" cols="40" rows="5"><?php echo $quizInfo['IMAGE_ALT'] ?></textarea>
                <br />
                <button class="mybutton mySubmit" type="submit" name="confirmUpdate" value="Enter">Update</button>
            </form>
                 <a class='mybutton myReturn' href='<?php echo(CONFIG_ROOT_URL) . "/edit-quiz.php?quiz=".$quizIDPost?>'>Cancel</a>
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();