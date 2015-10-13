<?php



$templateLogic = new templateLogic;
$templateLogic->setTitle('Usage Statistics');
$templateLogic->startBody();
?>
        
        <div id="content-charts">
            <div id="chart-actions">
                <form action='#' method='post'>    
                    <a class="mybuttonlink" href="<?php echo (CONFIG_ROOT_URL)."/stats.php"?>">Change<br /> quiz</a>
                    <br />
                    <br />
                    <button class="mybutton" type="submit" name="selectStatistics" >Select a user</button> 
                    <br />
                    <br />
                </form>
                <form action='stats-editor.php' method='post'>
                    <button class="mybutton <?php if($currentResults == true){echo "statsActive";}?>" type="submit" name="selectStatistics" >Current version</button> 
                    <br />
                    <br />
                    <button class="mybutton <?php if($currentResults == false){echo "statsActive";}?>" type="submit" name="previousVersions" >Previous versions</button>  
                </form>
            </div>
            <div id="content-table-data">  
                
                <p>This version of the quiz has not been attempted yet. Select 
                        <span id="label">Change Quiz </span> to view results for another quiz or select 
                        <span id='label'>All versions</span> to view user results for previous versions of this quiz.</p>
                
                <p><span id="label">User: </span> <?php echo $pageUser ?></p>
                <p><span id="label">Number of attempts:</span>  N/A</p>
                <p><span id="label">Average completion time:</span>  N/A</p>
                <p><span id="label">Shortest completion time:</span>  N/A</p>
                <p><span id="label">Longest completion time:</span>  N/A</p>
                
                <br />
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
                                <?php if(!empty($uniqueFinishers)){
                                    for($j=0; $j<count($uniqueFinishers); $j++){
                                        echo "<tr>"
                                                . "<td><form action='#' name='".$uniqueFinishers[$j]."' method='post'>" . 
                    "<input type='hidden' name='getResult' value='".$uniqueFinishers[$j]."'>" . $uniqueFinishers[$j] .
                    "<input type='submit' class='mySubmit' name='userCurrent' value='Current Version'>" . 
                       "<input type='submit' class='mySubmit' name='userPrevious' value='Previous Version'></form>"
                                                . "</td>"
                                           . "</tr>";
                                    }
                                }else{
                                    echo "<tr><td> No user has attempted this quiz. </td></tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            
            </div>
            
        </div>
        
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();
