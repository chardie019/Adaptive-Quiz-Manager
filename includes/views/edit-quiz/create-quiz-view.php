<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Create Quiz');
$templateLogic->startBody();
?>

<div id="content-create-quiz">

    <br />
    <?php echo "<span class=\"inputError\">".$quizNameError."</span>"?>
    <br />
    <!--enctype="" used because of image file upload in form-->
    <form action='#' method='post' enctype="multipart/form-data" >

        <p class="label">Please enter the Title of your quiz:</p>
        <input type='text' name='quizName' size='30' value="<?php echo $quizName ?>" />
        <br />
        <?php echo "<span class=\"inputError\">".$quizDescriptionError."</span>"?>
        <br />
        <br />
        <p class="label">Please enter a description for your quiz:</p>
        <textarea name="quizDescription" cols="40" rows="5" ><?php echo $quizDescription ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$isPublicError."</span>"?>
        <br />
        <p class="label">Is this public or private:</p>
        <label for="public">Public:</label>
        <input type="radio" name="isPublic" id="public" value='1' <?php if ($isPublic == "1"){echo "checked=\"checked\"";} ?> />
        <label for="private">Private:</label> 
        <input type='radio' name='isPublic' id="private" value='0' <?php if ($isPublic == "0"){echo "checked=\"checked\"";} ?> />
        <br />
        <?php echo "<span class=\"inputError\">".$noAttemptsError."</span>"?>
        <br />
        <p class="label">Please enter the number of attempts permitted:</p>
        <select name='noAttempts'>
            <option <?php if ($noAttempts == "Unlimited"){echo "selected=\"selected\"";} ?>>Unlimited</option>
            <option <?php if ($noAttempts == "1"){echo "selected=\"selected\"";} ?>>1</option>
            <option <?php if ($noAttempts == "2"){echo "selected=\"selected\"";} ?>>2</option>
            <option <?php if ($noAttempts == "3"){echo "selected=\"selected\"";} ?>>3</option>
            <option <?php if ($noAttempts == "4"){echo "selected=\"selected\"";} ?>>4</option>
            <option <?php if ($noAttempts == "5"){echo "selected=\"selected\"";} ?>>5</option>
            <option <?php if ($noAttempts == "6"){echo "selected=\"selected\"";} ?>>6</option>
            <option <?php if ($noAttempts == "7"){echo "selected=\"selected\"";} ?>>7</option>
            <option <?php if ($noAttempts == "8"){echo "selected=\"selected\"";} ?>>8</option>
            <option <?php if ($noAttempts == "9"){echo "selected=\"selected\"";} ?>>9</option>
            <option <?php if ($noAttempts == "10"){echo "selected=\"selected\"";} ?>>10</option>
        </select>
        <br />
        <?php echo "<span class=\"inputError\">".$isTimeError."</span>"?>
        <br />
        <p class="label">Is there a time limit to complete the quiz:</p>
        <label for="noTimelimit">No:</label>
        <input class="timeselect" type='radio' name='isTime' id="noTimelimit" value='0' <?php if ($isTime == "0"){echo "checked=\"checked\"";} ?> />
        <label for="yesTimeLimit">Yes:</label>
        <input type='radio' name='isTime' id="yesTimeLimit" value='1' <?php if ($isTime == "1"){echo "checked=\"checked\"";} ?> />
        <br />
        <?php echo "<span class=\"inputError\">".$timeLimitError."</span>"?>
        <br />
        <p class="label">*** If YES, please enter that time limit: ***</p>
        Hours
        <select class="timeselect" name='timeHours'>
            <option <?php if ($timeHours == "0"){echo "selected=\"selected\"";} ?>>0</option>
            <option <?php if ($timeHours == "1"){echo "selected=\"selected\"";} ?>>1</option>
            <option <?php if ($timeHours == "2"){echo "selected=\"selected\"";} ?>>2</option>
            <option <?php if ($timeHours == "3"){echo "selected=\"selected\"";} ?>>3</option>
            <option <?php if ($timeHours == "4"){echo "selected=\"selected\"";} ?>>4</option>
            <option <?php if ($timeHours == "5"){echo "selected=\"selected\"";} ?>>5</option>
        </select>
        Minutes
        <select class="timeselect"name='timeMinutes'>
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
        <?php echo "<span class=\"inputError\">".$isSaveError."</span>"?>
        <br />
        <p class="label">Can users save progress and return later to the quiz:</p>
        <label for="noSave">No:</label>
        <input type='radio' name='isSave' id="noSave" value='0' <?php if ($isSave == "0"){echo "checked=\"checked\"";} ?> />
        <label for="yesSave">Yes:</label>
        <input type='radio' name='isSave' id="yesSave" value='1' <?php if ($isSave == "1"){echo "checked=\"checked\"";} ?> />
        <br />
        <?php echo "<span class=\"inputError\">".$invalidDateError1."</span>"?>
        <br />
        <p class="label">When does this quiz open:</p>
        <select class="timeselect" name="dayStart"> 
            <option <?php if ($dayStart == "1"){echo "selected=\"selected\"";} ?>>1</option>       
            <option <?php if ($dayStart == "2"){echo "selected=\"selected\"";} ?>>2</option>       
            <option <?php if ($dayStart == "3"){echo "selected=\"selected\"";} ?>>3</option>       
            <option <?php if ($dayStart == "4"){echo "selected=\"selected\"";} ?>>4</option>       
            <option <?php if ($dayStart == "5"){echo "selected=\"selected\"";} ?>>5</option>       
            <option <?php if ($dayStart == "6"){echo "selected=\"selected\"";} ?>>6</option>       
            <option <?php if ($dayStart == "7"){echo "selected=\"selected\"";} ?>>7</option>       
            <option <?php if ($dayStart == "8"){echo "selected=\"selected\"";} ?>>8</option>       
            <option <?php if ($dayStart == "9"){echo "selected=\"selected\"";} ?>>9</option>       
            <option <?php if ($dayStart == "10"){echo "selected=\"selected\"";} ?>>10</option>       
            <option <?php if ($dayStart == "11"){echo "selected=\"selected\"";} ?>>11</option>       
            <option <?php if ($dayStart == "12"){echo "selected=\"selected\"";} ?>>12</option>       
            <option <?php if ($dayStart == "13"){echo "selected=\"selected\"";} ?>>13</option>       
            <option <?php if ($dayStart == "14"){echo "selected=\"selected\"";} ?>>14</option>       
            <option <?php if ($dayStart == "15"){echo "selected=\"selected\"";} ?>>15</option>       
            <option <?php if ($dayStart == "16"){echo "selected=\"selected\"";} ?>>16</option>       
            <option <?php if ($dayStart == "17"){echo "selected=\"selected\"";} ?>>17</option>       
            <option <?php if ($dayStart == "18"){echo "selected=\"selected\"";} ?>>18</option>       
            <option <?php if ($dayStart == "19"){echo "selected=\"selected\"";} ?>>19</option>       
            <option <?php if ($dayStart == "20"){echo "selected=\"selected\"";} ?>>20</option>       
            <option <?php if ($dayStart == "21"){echo "selected=\"selected\"";} ?>>21</option>       
            <option <?php if ($dayStart == "22"){echo "selected=\"selected\"";} ?>>22</option>       
            <option <?php if ($dayStart == "23"){echo "selected=\"selected\"";} ?>>23</option>       
            <option <?php if ($dayStart == "24"){echo "selected=\"selected\"";} ?>>24</option>       
            <option <?php if ($dayStart == "25"){echo "selected=\"selected\"";} ?>>25</option>       
            <option <?php if ($dayStart == "26"){echo "selected=\"selected\"";} ?>>26</option>       
            <option <?php if ($dayStart == "27"){echo "selected=\"selected\"";} ?>>27</option>       
            <option <?php if ($dayStart == "28"){echo "selected=\"selected\"";} ?>>28</option>       
            <option <?php if ($dayStart == "29"){echo "selected=\"selected\"";} ?>>29</option>       
            <option <?php if ($dayStart == "30"){echo "selected=\"selected\"";} ?>>30</option>       
            <option <?php if ($dayStart == "31"){echo "selected=\"selected\"";} ?>>31</option>       
        </select> - 
        <select class="timeselect" name="monthStart"> 
            <option <?php if ($monthStart == "1"){echo "selected=\"selected\"";} ?>>1</option>       
            <option <?php if ($monthStart == "2"){echo "selected=\"selected\"";} ?>>2</option>       
            <option <?php if ($monthStart == "3"){echo "selected=\"selected\"";} ?>>3</option>       
            <option <?php if ($monthStart == "4"){echo "selected=\"selected\"";} ?>>4</option>       
            <option <?php if ($monthStart == "5"){echo "selected=\"selected\"";} ?>>5</option>       
            <option <?php if ($monthStart == "6"){echo "selected=\"selected\"";} ?>>6</option>       
            <option <?php if ($monthStart == "7"){echo "selected=\"selected\"";} ?>>7</option>       
            <option <?php if ($monthStart == "8"){echo "selected=\"selected\"";} ?>>8</option>       
            <option <?php if ($monthStart == "9"){echo "selected=\"selected\"";} ?>>9</option>       
            <option <?php if ($monthStart == "10"){echo "selected=\"selected\"";} ?>>10</option>       
            <option <?php if ($monthStart == "11"){echo "selected=\"selected\"";} ?>>11</option>       
            <option <?php if ($monthStart == "12"){echo "selected=\"selected\"";} ?>>12</option>       
        </select> - 
        <select class="timeselect" name="yearStart">     
            <option <?php if ($yearStart == $yearCurrent){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent; ?></option>       
            <option <?php if ($yearStart == $yearCurrent + 1){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 1; ?></option>       
            <option <?php if ($yearStart == $yearCurrent + 2){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 2; ?></option>       
            <option <?php if ($yearStart == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 3; ?></option>       
        </select> 
        <span class="dateOrder">(Day-Month-Year)</span> 
        <br />
        <?php echo "<span class=\"inputError\">".$invalidDateError2."</span>"?>
        <br />
        <?php echo "<span class=\"inputError\">".$alwaysOpenError."</span>"?>
        <p class="label">Will this quiz be permanently open?</p>
        <label for="noAlwaysOpen">No:</label>
        <input type='radio' name='alwaysOpen' id="noAlwaysOpen" value='0' <?php if ($alwaysOpen == "0"){echo "checked=\"checked\"";} ?> />
        <label for="yesAlwaysOpen">Yes:</label>
        <input type='radio' name='alwaysOpen' id="yesAlwaysOpen" value='1' <?php if ($alwaysOpen == "1"){echo "checked=\"checked\"";} ?> />
        <br />
        <br />
        <p class="label">When does this quiz close:</p>
        <select class="timeselect" name="dayEnd"> 
            <option <?php if ($dayEnd == "1"){echo "selected=\"selected\"";} ?>>1</option>       
            <option <?php if ($dayEnd == "2"){echo "selected=\"selected\"";} ?>>2</option>       
            <option <?php if ($dayEnd == "3"){echo "selected=\"selected\"";} ?>>3</option>       
            <option <?php if ($dayEnd == "4"){echo "selected=\"selected\"";} ?>>4</option>       
            <option <?php if ($dayEnd == "5"){echo "selected=\"selected\"";} ?>>5</option>       
            <option <?php if ($dayEnd == "6"){echo "selected=\"selected\"";} ?>>6</option>       
            <option <?php if ($dayEnd == "7"){echo "selected=\"selected\"";} ?>>7</option>       
            <option <?php if ($dayEnd == "8"){echo "selected=\"selected\"";} ?>>8</option>       
            <option <?php if ($dayEnd == "9"){echo "selected=\"selected\"";} ?>>9</option>       
            <option <?php if ($dayEnd == "10"){echo "selected=\"selected\"";} ?>>10</option>       
            <option <?php if ($dayEnd == "11"){echo "selected=\"selected\"";} ?>>11</option>       
            <option <?php if ($dayEnd == "12"){echo "selected=\"selected\"";} ?>>12</option>       
            <option <?php if ($dayEnd == "13"){echo "selected=\"selected\"";} ?>>13</option>       
            <option <?php if ($dayEnd == "14"){echo "selected=\"selected\"";} ?>>14</option>       
            <option <?php if ($dayEnd == "15"){echo "selected=\"selected\"";} ?>>15</option>       
            <option <?php if ($dayEnd == "16"){echo "selected=\"selected\"";} ?>>16</option>       
            <option <?php if ($dayEnd == "17"){echo "selected=\"selected\"";} ?>>17</option>       
            <option <?php if ($dayEnd == "18"){echo "selected=\"selected\"";} ?>>18</option>       
            <option <?php if ($dayEnd == "19"){echo "selected=\"selected\"";} ?>>19</option>       
            <option <?php if ($dayEnd == "20"){echo "selected=\"selected\"";} ?>>20</option>       
            <option <?php if ($dayEnd == "21"){echo "selected=\"selected\"";} ?>>21</option>       
            <option <?php if ($dayEnd == "22"){echo "selected=\"selected\"";} ?>>22</option>       
            <option <?php if ($dayEnd == "23"){echo "selected=\"selected\"";} ?>>23</option>       
            <option <?php if ($dayEnd == "24"){echo "selected=\"selected\"";} ?>>24</option>       
            <option <?php if ($dayEnd == "25"){echo "selected=\"selected\"";} ?>>25</option>       
            <option <?php if ($dayEnd == "26"){echo "selected=\"selected\"";} ?>>26</option>       
            <option <?php if ($dayEnd == "27"){echo "selected=\"selected\"";} ?>>27</option>       
            <option <?php if ($dayEnd == "28"){echo "selected=\"selected\"";} ?>>28</option>       
            <option <?php if ($dayEnd == "29"){echo "selected=\"selected\"";} ?>>29</option>       
            <option <?php if ($dayEnd == "30"){echo "selected=\"selected\"";} ?>>30</option>       
            <option <?php if ($dayEnd == "31"){echo "selected=\"selected\"";} ?>>31</option>       
        </select> - 
        <select class="timeselect" name="monthEnd"> 
            <option <?php if ($monthEnd == "1"){echo "selected=\"selected\"";} ?>>1</option>       
            <option <?php if ($monthEnd == "2"){echo "selected=\"selected\"";} ?>>2</option>       
            <option <?php if ($monthEnd == "3"){echo "selected=\"selected\"";} ?>>3</option>       
            <option> <?php if ($monthEnd == "4"){echo "selected=\"selected\"";} ?>4</option>       
            <option <?php if ($monthEnd == "5"){echo "selected=\"selected\"";} ?>>5</option>       
            <option <?php if ($monthEnd == "6"){echo "selected=\"selected\"";} ?>>6</option>       
            <option <?php if ($monthEnd == "7"){echo "selected=\"selected\"";} ?>>7</option>       
            <option <?php if ($monthEnd == "8"){echo "selected=\"selected\"";} ?>>8</option>       
            <option <?php if ($monthEnd == "9"){echo "selected=\"selected\"";} ?>>9</option>       
            <option <?php if ($monthEnd == "10"){echo "selected=\"selected\"";} ?>>10</option>       
            <option <?php if ($monthEnd == "11"){echo "selected=\"selected\"";} ?>>11</option>       
            <option <?php if ($monthEnd == "12"){echo "selected=\"selected\"";} ?>>12</option>       
        </select> - 
        <select class="timeselect" name="yearEnd">     
            <option <?php if ($yearEnd == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent; ?></option>
            <option <?php if ($yearEnd == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 1; ?></option>
            <option <?php if ($yearEnd == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 2; ?></option>
            <option <?php if ($yearEnd == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 3; ?></option>     
        </select> 
        <span class="dateOrder">(Day-Month-Year)</span> 
        <br />
        <?php echo "<span class=\"inputError\">".$imageUploadError."</span>"?>
        <br />
        <p class="label">Is there a cover image to upload with your quiz(optional):</p>
        <input type="file" name="quizImageUpload" accept="image/*">
        <br />
        <br />
        <?php echo "<span class=\"inputError\">".$quizImageTextError."</span>"?>
        <br />
        <p class="label">Please provide alternative text for the image you uploaded:</p>
        <textarea name="quizImageText" cols="40" rows="5"></textarea>
        <br />
        <br />
        <br />
        <button class="mybutton mySubmit" type="submit" name="confirmQuiz" value="Enter">Create</button>
        <button class="mybutton myReturn" type="submit" name="cancelQuiz" value="Cancel">Cancel</button>
        <br />
        <br />
        <br />
        <br />
    </form>

</div>




<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();

