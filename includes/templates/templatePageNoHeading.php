<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- CSS files -->
<?php
foreach ($this->stylesheets as $stylesheet) {
echo '<link href="' . $stylesheet . '" rel="stylesheet" type="text/css" />' . "\n";
}
?> 
<!-- end of CSS files -->
<!-- JS files top -->
<?php
    foreach ($this->javascriptsTop as $javascript) {
        echo '<script src="' . $javascript . '"></script>' . "\n";
    }
?>
<!-- end of JS files top -->
<!-- Custom data top -->
<?php echo $this->customHeaders; ?>
<!-- end custom data top -->
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
                
                <!-- sub menu -->
                <?php if (!is_null($this->subMenuFile)) {
                    include ($this->subMenuFile);
                } else {
                    echo '<!-- no sub menu included -->';
                } ?>
                <!-- end sub menu -->
                
                <?php echo $this->body; ?>
                
            </div> 
            <!-- end #content -->
        
            <?php include('sidebar.php'); ?>

            <?php include('footer.php'); ?>
        
        </div> 
        <!-- End #wrapper -->
        <!-- Js files bottom -->
        <?php
            foreach ($this->javascriptsBottom as $javascript) {
                echo '<script src="' . $javascript . '"></script>' . "\n";
            }
        ?>
        <!-- end of JS files bottom -->
        <!-- Custom data bottom -->
        <?php echo $this->customBottom; ?>
        <!-- end custom data bottom -->
    </body>
    
</html>