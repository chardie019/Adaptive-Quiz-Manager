<?php

$templateLogic = new templateLogic;
$templateLogic->setTitle('User results');

$templateLogic->addCustomHeadersStart();?>   
       <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">

          // Load the Visualization API and the piechart package.
          google.load('visualization', '1.0', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.setOnLoadCallback(drawChart);

          // Callback that creates and populates a data table,
          // instantiates the pie chart, passes in the data and
          // draws it.
          function drawChart() {
            
            <?php
            for($n=0; $n<count($newQuestionArray); $n++){
            // Create the data table.
                echo "var data = new google.visualization.DataTable();
                data.addColumn('string', 'Possible Answers');
                data.addColumn('number', 'Times chosen');
                data.addRows([";
                            
                //traverse arrays to create graph details
                foreach($graphData{$n} as $answerNumbers=>$answerNumbers_values){
                    echo  "['".$answerNumbers."', ".$answerNumbers_values."], ";                    
                }
                echo "]);";
         
                // Set chart options
                echo "var options = {'title':'Question: ".$questionText[$n]."',
                           'width':800,
                           'height':500
                           };
                           ";
                // Instantiate and draw our chart, passing in some options.
                echo "var chart = new google.visualization.PieChart(document.getElementById('chart_div".$n."'));";
                echo "chart.draw(data, options);"; 
            }?>
          }
        </script>
     <?php   
$templateLogic->addCustomHeadersEnd();
$templateLogic->startBody();
?>
        
        <div id="content-charts">
            
                <div id='chart-actions'>
                <form action='#' method='post'>    
                    <a class="mybuttonlink" href="<?php echo (CONFIG_ROOT_URL)."/stats.php"?>">Change<br /> quiz</a>
                    <br />
                    <br />
                    <button class="mybutton <?php {echo "statsActive";}?>" type="submit" name="selectStatistics" >Select a<br /> User</button> 
                    <br />
                    <br />
                </form>
                <form action='stats-editor.php' method='post'>
                    <button class="mybutton" type="submit" name="selectStatistics" >Current version</button> 
                    <br />
                    <br />
                    <button class="mybutton" type="submit" name="previousVersions" >Previous versions</button>  
                </form>
            </div>
            <div id="content-table-data">  
                <p><span id="label">User: </span> <?php echo $pageUser ?></p>
                <p><span id="label">Number of attempts: </span><?php echo $countAttempts ?> attempts</p>
                <p><span id="label">Average completion time: </span> <?php echo $averageTime ?></p>
                <p><span id="label">Shortest completion time: </span> <?php echo $minTime ?></p>
                <p><span id="label">Longest completion time: </span> <?php echo $maxTime ?></p>
                <br />
       <div id='tableWrapper'>
        <div id='tableScroll'>
            <table>
                <thead>
                    <tr>
                        <th>Current Takers</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                        for($j=0; $j<count($uniqueFinishers); $j++){
                            echo "<tr>"
                                    . "<td><form action='#' name='".$uniqueFinishers[$j]."' method='post'>" . 
        "<input type='hidden' name='getResult' value='".$uniqueFinishers[$j]."'>" . $uniqueFinishers[$j] .
        "<input type='submit' class='myReturn' name='userCurrent' value='Current Version'>" . 
           "<input type='submit' class='myReturn' name='userPrevious' value='Previous Version'></form>"
                                    . "</td>"
                               . "</tr>";
                        }
                    ?>
                
                </tbody>
            </table>
        </div>
    </div>
           
              
             
            
            
            
                <h2 class=''>Answers selected for each question</h2>
                <?php
                    for($x=0; $x<count($newQuestionArray); $x++){
                        echo "<div id=\"chart_div".$x."\"></div>";                    
                    }
                ?>
                </div>
            
        </div>
        
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
