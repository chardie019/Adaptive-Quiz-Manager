<div id="header">
    <div id="logo">
        <img alt="Logo" src="<?php echo(STYLES_LOCATION) ?>/logo.png" />
            <p> 
                <span>USER: </span><?php echo ($this->username); ?>
                <br />
                <span>TYPE: </span><?php echo ($this->userType); ?>
                <br />
                <span><!-- line up the button --></span><?php echo $this->logoutLinkHtml; ?>
            </p>
    </div>
</div>