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
                
                <p><span id="label">User: </span> <?php echo $uid ?></p>
                <p><span id="label">Number of attempts:</span>  N/A</p>
                <p><span id="label">Average completion time:</span>  N/A</p>
                <p><span id="label">Shortest completion time:</span>  N/A</p>
                <p><span id="label">Longest completion time:</span>  N/A</p>
                
                <br />
                <br />
                
            
            </div>
            
        </div>
        
<?php
$templateLogic->endBody();

//html
echo $templateLogic->render();

