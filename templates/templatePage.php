<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
//print all stylesheets
foreach ($this->stylesheets as $stylesheet) {
echo '<link href="' . $stylesheet . '" rel="stylesheet" type="text/css" />' . "\n";
}
?> 
<title><?php echo $this->title; ?> - <?php echo STYLES_SITE_HEADING ?></title>
 
</head>

    <body>
        <!-- start #wrapper -->
        <div id="wrapper"> 
            <!-- start header -->
            <?php include('header.php'); ?>
            <!-- end header -->
            <!-- start nav -->
            <?php include('nav.php'); ?>
            <!-- end nav -->
            <!-- start #content -->
            <div id="content">
                
                <h1><?php echo $this->heading; ?></h1>
                
                <?php echo $this->body; ?>
                
        </div> 
        <!-- end #content -->
        
        <?php include('sidebar.php'); ?>
        
        <?php include('footer.php'); ?>
        
        </div> 
        <!-- End #wrapper -->
    </body>
    
</html>