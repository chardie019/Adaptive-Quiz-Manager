<!DOCTYPE html>

<?php
// include php files to do with view
require_once("includes/config.php");
// end of php file inclusion
?>
 
<head>
    
<meta charset="utf-8"/>
 
<link rel="stylesheet" type="text/css" href="<?php echo(STYLES_LOCATION) ?>/style.css" media="screen" />
 
<title>Create Quiz - <?php echo (STYLES_SITE_NAME); ?></title>
 
</head>
 
    <body>
 
        <div id="wrapper">
            <?php include('header.php'); ?>

            <?php include('nav.php'); ?>

 
            <div id="content-create-quiz">
 
                <h1>Create Quiz</h1>
                
                <br />
                <br />
                <!--enctype="" used because of image file upload in form-->
                <form action='#' method='post' enctype="multipart/form-data" >
    
                <label>Please enter the Title of your quiz: </label>
                <input type='text' name='quizName' value='<?php echo $quizName ?>' size='30'></input>
                <br />
                <?php echo "<span class=\"inputError\">".$quizNameError."</span>"?>
                <br />
                <br />

                <label>Please enter a description for your quiz: </label>
                <textarea name="quizDescription" cols="40" rows="5"><?php echo $quizDescription ?></textarea>
                <br />
                <?php echo "<span class=\"inputError\">".$quizDescriptionError."</span>"?>
                <br />
                <br />
                
                <label>Is this public or private: </label>
                Public: 
                <input type='radio' name='isPublic' value='1' checked></input>
                Private: 
                <input type='radio' name='isPublic' value='0'></input>
                <br />
                <?php echo "<span class=\"inputError\">".$isPublicError."</span>"?>
                <br />
                <br />

                <label>Please enter the number of attempts permitted: </label>
                <select name='noAttempts'>
                    <option>Unlimited</option>
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
                </select>
                <br />
                <?php echo "<span class=\"inputError\">".$noAttemptsError."</span>"?>
                <br />
                <br />

                <label>Is there a time limit to complete the quiz: </label>
                No:
                <input type='radio' name='isTime' value='0' checked></input>
                Yes:
                <input type='radio' name='isTime' value='1'></input>
                <br />
                <?php echo "<span class=\"inputError\">".$isTimeError."</span>"?>
                <br />
                <br />
                <label>***If YES, please enter that time limit: </label>
                Hours
                <select name='timeHours'>
                    <option>0</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
                Minutes
                <select name='timeMinutes'>
                    <option>0</option>
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                    <option>25</option>
                    <option>30</option>
                    <option>35</option>
                    <option>40</option>
                    <option>45</option>
                    <option>50</option>
                    <option>55</option>
                </select> 
                <br />
                <?php echo "<span class=\"inputError\">".$timeLimitError."</span>"?>
                <br />                
                <br />

                <label>Can users save progress and return later to the quiz: </label>
                No:
                <input type='radio' name='isSave' value='0' checked></input>
                Yes:
                <input type='radio' name='isSave' value='1'></input>
                <br />
                <?php echo "<span class=\"inputError\">".$isSaveError."</span>"?>
                <br />
                <br />
                <label for="dayStart">When does this quiz open:</label>
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
                <label for="dayEnd">When does this quiz close: </label> 
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
                <br />
                
                <label>Is there a cover image to upload with your quiz:</label>
                <input type="file" name="quizImageUpload" accept="image/*">
                <br />
                <?php echo "<span class=\"inputError\">".$imageUploadError."</span>"?>
                <br />
                <br />

                <label>Please provide alternative text for the image you uploaded:</label>
                <textarea name="quizImageText" cols="40" rows="5"><?php echo $quizImageText ?></textarea>
                <br />
                <br />

                <button class="mySubmit" type="submit" name="confirmQuiz" value="Enter">Create</button>
            </form>
                
            </div> <!-- end #content -->


            <?php include('sidebar.php'); ?>

            <?php include('footer.php'); ?>
 
        </div> <!-- End #wrapper -->
 
    </body>
 
</html>
