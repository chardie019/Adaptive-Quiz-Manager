<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('Edit Quiz');
$templateLogic->setSubMenuType("edit-quiz", "details");
$templateLogic->addCSS("edit-question/edit-question-forms.css");
$templateLogic->addCustomHeadersStart(); ?>
<style type="text/css">
    textarea {
    box-sizing: initial;
    width: inherit;
}
</style>
<?php $templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
                
<div id="content-create-quiz">
    <br />
    <br />
    <?php echo "<span class=\"inputError\">".$quizEnabledError."</span>"?>
    <?php echo "<span class=\"feedback-span\">".$quizUpdated."</span>"?>
    <br />
    <!--enctype="" used because of image file upload in form-->
    <form action='#' method='post' enctype="multipart/form-data" >                    
        <p class="label">Please enter the Title of your quiz:</p>
        <input type='text' name='quizName' value='<?php echo $quizName; ?>' size='30'></input>
        <br />
        <?php echo "<span class=\"inputError\">".$quizNameError."</span>"?>

        <br />
        <br />

        <p class="label">Please enter a description for your quiz:</p>
        <textarea name="quizDescription" cols="40" rows="5"><?php echo $quizDescription; ?></textarea>
        <br />
        <?php echo "<span class=\"inputError\">".$quizDescriptionError."</span>"?>
        <br />
        <p class="label">Is this public or private:</p>
        <label for="is-public-yes">Public:</label>
        <input id="is-public-yes" type='radio' name='isPublic' value='1' <?php if ($isPublic == "1"){echo "checked=\"checked\"";} ?> />
        <label for="is-public-no">Private: </label>
        <input id="is-public-no" type='radio' name='isPublic' value='0' <?php if ($isPublic == "0"){echo "checked=\"checked\"";} ?> />
        <br />
        <?php echo "<span class=\"inputError\">".$isPublicError."</span>"?>
        <br />
        <p class="label">Please enter the number of attempts permitted:</p>
        <select name='noAttempts'>
              <option <?php if($noAttempts == '0'){echo("selected");}?>>Unlimited</option>
              <option <?php if($noAttempts == '1'){echo("selected");}?>>1</option>
              <option <?php if($noAttempts == '2'){echo("selected");}?>>2</option>
              <option <?php if($noAttempts == '3'){echo("selected");}?>>3</option>
              <option <?php if($noAttempts == '4'){echo("selected");}?>>4</option>
              <option <?php if($noAttempts == '5'){echo("selected");}?>>5</option>
              <option <?php if($noAttempts == '6'){echo("selected");}?>>6</option>
              <option <?php if($noAttempts == '7'){echo("selected");}?>>7</option>
              <option <?php if($noAttempts == '8'){echo("selected");}?>>8</option>
              <option <?php if($noAttempts == '9'){echo("selected");}?>>9</option>
              <option <?php if($noAttempts == '10'){echo("selected");}?>>10</option>
        </select>
        <br />
        <?php echo "<span class=\"inputError\">".$noAttemptsError."</span>"?>
        <br />
        <p class="label">Is there a time limit to complete the quiz:</p>
        <label for="is-time-limit-no">No:</label>
        <input id="is-time-limit-no" type='radio' name='isTime' value='0' <?php if($isTime == '0'){echo("checked");}?>></input>
        <label for=is-time-limit-yes">Yes:</label>
        <input id=is-time-limit-yes" type='radio' name='isTime' value='1' <?php if($isTime == '1'){echo("checked");}?>></input>
        <br />
        <?php echo "<span class=\"inputError\">".$isTimeError."</span>"?>
        <br />
        <div id="time-limit-div">
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
        </div>
        <p class="label">Can users save progress and return later to the quiz:</p>
        <label for="is-save-no">No:</label>
        <input id="is-save-no" type='radio' name='isSave' value='0' <?php if($isSave == '0'){echo("checked");}?>></input>
        <label for="is-save-yes">Yes:</label>
        <input id="is-save-yes" type='radio' name='isSave' value='1' <?php if($isSave == '1'){echo("checked");}?>></input>
        <br />
        <?php echo "<span class=\"inputError\">".$isSaveError."</span>"?>
        <br />
        <br />
        <p class="label">When does this quiz open:</p>
        <select name="dayStart" /> 
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
            <option <?php if ($dayStart== "30"){echo "selected=\"selected\"";} ?>>30</option>       
            <option <?php if ($dayStart == "31"){echo "selected=\"selected\"";} ?>>31</option>      
        </select> - 
        <select name="monthStart" /> 
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
        <select name="yearStart" />
            <?php if ($yearStart < $yearCurrent) { //if the date is way in the past ?>
                <option <?php echo "selected=\"selected\""; ?>><?php echo $yearCurrent; ?></option>
            <?php } ?>
            <option <?php if ($yearStart == $yearCurrent){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent; ?></option>       
            <option <?php if ($yearStart == $yearCurrent + 1){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 1; ?></option>       
            <option <?php if ($yearStart == $yearCurrent + 2){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 2; ?></option>       
            <option <?php if ($yearStart == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 3; ?></option>     
        </select> 
        <span class="dateOrder">(Day-Month-Year)</span> 
        <br />
        <?php echo "<span class=\"inputError\">".$dayStartError."</span>"?>
        <?php echo "<span class=\"inputError\">".$monthStartError."</span>"?>
        <?php echo "<span class=\"inputError\">".$yearStartError."</span>"?>
        <?php echo "<span class=\"inputError\">".$invalidDateError1."</span>"?>
        <br />
        <?php echo "<span class=\"inputError\">".$alwaysOpenError."</span>"?>
        <p class="label">Will this quiz be permanently open?</p>
        <label for="noAlwaysOpen">No:</label>
        <input type='radio' name='alwaysOpen' id="noAlwaysOpen" value='0' <?php if ($alwaysOpen == "0"){echo "checked=\"checked\"";} ?> />
        <label for="yesAlwaysOpen">Yes:</label>
        <input type='radio' name='alwaysOpen' id="yesAlwaysOpen" value='1' <?php if ($alwaysOpen == "1"){echo "checked=\"checked\"";} ?> />
        <div id="when-does-quiz-close-div">
            <br />
            <p class="label">*** If NO, When does this quiz close: ***</p>
            <select name="dayEnd" /> 
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
            <select name="monthEnd" /> 
                <option <?php if ($monthEnd == "1"){echo "selected=\"selected\"";} ?>>1</option>       
                <option <?php if ($monthEnd == "2"){echo "selected=\"selected\"";} ?>>2</option>       
                <option <?php if ($monthEnd == "3"){echo "selected=\"selected\"";} ?>>3</option>       
                <option <?php if ($monthEnd == "4"){echo "selected=\"selected\"";} ?>>4</option>       
                <option <?php if ($monthEnd == "5"){echo "selected=\"selected\"";} ?>>5</option>       
                <option <?php if ($monthEnd == "6"){echo "selected=\"selected\"";} ?>>6</option>       
                <option <?php if ($monthEnd == "7"){echo "selected=\"selected\"";} ?>>7</option>       
                <option <?php if ($monthEnd == "8"){echo "selected=\"selected\"";} ?>>8</option>       
                <option <?php if ($monthEnd == "9"){echo "selected=\"selected\"";} ?>>9</option>       
                <option <?php if ($monthEnd == "10"){echo "selected=\"selected\"";} ?>>10</option>       
                <option <?php if ($monthEnd == "11"){echo "selected=\"selected\"";} ?>>11</option>       
                <option <?php if ($monthEnd == "12"){echo "selected=\"selected\"";} ?>>12</option>      
            </select> - 
            <select name="yearEnd" />     
                <option <?php if ($yearEnd == $yearCurrent){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent; ?></option>
                <option <?php if ($yearEnd == $yearCurrent + 1){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 1; ?></option>
                <option <?php if ($yearEnd == $yearCurrent + 2){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 2; ?></option>
                <option <?php if ($yearEnd == $yearCurrent + 3){echo "selected=\"selected\"";} ?>><?php echo $yearCurrent + 3; ?></option>     
            </select> 
            <span class="dateOrder">(Day-Month-Year)</span> 
            <br />
            <?php echo "<span class=\"inputError\">".$dayEndError."</span>"?>
            <?php echo "<span class=\"inputError\">".$monthEndError."</span>"?>
            <?php echo "<span class=\"inputError\">".$yearEndError."</span>"?>
            <?php echo "<span class=\"inputError\">".$invalidDateError2."</span>"?>  
        </div>
        <br />
        <p class="label">Is there a cover image to upload with your quiz(optional):</p>
        <input type="file" name="quizImageUpload" accept="image/*">
        <input type="hidden" name="currentquizImageUpload" value="<?php echo $currentImageFileName; ?>">
        
        
        <?php if (!empty($currentImage)) { ?>
            <img src='<?php echo $currentImage; ?>' alt='<?php echo $quizImageText; ?>'>
            <p class="label">&nbsp;</p>
            <input id="keep-image-yes" type="radio" name="keep-image" value="keep-or-update" <?php if ($keepImage == "keep-or-update"){echo "checked=\"checked\"";} ?> /> 
            <label for="keep-image-yes">Keep or Update Image.</label>
            <input id="keep-image-no" type="radio" name="keep-image" value="delete" <?php if ($keepImage == "delete"){echo "checked=\"checked\"";} ?> />
            <label for="keep-image-no">Delete Image</label>
        <?php } else { ?>
            No Image currently Uploaded
            <input type="hidden" name="keep-image" value="do-nothing" />
        <?php } ?>
        <?php echo "<span class=\"inputError\">".$imageUploadError."</span>"?>
        <br />
        <p class="label">Please provide alternative text for the image you uploaded:</p>
        <textarea name="quizImageText" cols="40" rows="5"><?php echo $quizImageText ?></textarea>
        <br />
        <p class="submit-buttons-container">
            <button class="mybutton mySubmit" type="submit" name="confirmUpdate" value="Enter">Update</button>
            <a class='mybutton myReturn' href='<?php echo(CONFIG_ROOT_URL) . "/edit-quiz.php".$quizUrl?>'>Cancel</a>
        </p>
    </form>
                 
<?php
$templateLogic->endBody();
$templateLogic->addCustomBottomStart(); ?>
<script>
$(document).ready(function() {
    /* maybe hide the when-does-quiz-close-div on load */
    if($('input[name="alwaysOpen:checked"]').val() == 0) {
        $('#when-does-quiz-close-div').show();           
    } else {
        $('#when-does-quiz-close-div').hide();   
    }
    /* show/hide div on toggle */
    $('input[name="alwaysOpen"]').click(function() {
        /*console.log($('input[name=alwaysOpen]:checked').val());*/
        if($(this).filter(':checked').val() == 0) {
            $('#when-does-quiz-close-div').show();           
        } else {
            $('#when-does-quiz-close-div').hide();   
        }
    });
    /* maybe hide the time-limit-div on load */
    if($('input[name="isTime"]:checked').val() == 1) {
        $('#time-limit-div').show();           
    } else {
        $('#time-limit-div').hide();   
    }
    /* show/hide div on toggle */
    $('input[name="isTime"]').click(function() {
        if($(this).filter(':checked').val() == 1) {
            $('#time-limit-div').show();           
        } else {
            $('#time-limit-div').hide();   
        }
    });
});
</script>
<?php $templateLogic->addCustomBottomEnd();

//html
echo $templateLogic->render();