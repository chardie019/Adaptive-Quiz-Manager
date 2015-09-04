<?php
/**
 * This file sets the data for the template Page
 */

/**
 * templateLogic is the Class responsable for setting the variables to display
 * the template for the page
 * 
 * It will keep a few variables and store the page into a buffer for the body
 * It is used to separte the a generic chnage in page display to disrupting 
 * everyone
 */
class templateLogic {
    /** @var array Contains a list of CSS files */
    private $stylesheets=array();
    /** @var array Contains a list of JS files for the top */
    private $javascriptsTop=array();
    /** @var string Contains the string to be added to the top */
    private $customHeaders = "";
    /** @var string The title of the page, in the <title> tag */
    private $title;
    /** @var string The heading in the <h1> tag */
    private $heading;
    /** @var string The Sub Menu (if any) e.g edit quiz menu */
    private $subMenuFile; 
    /** @var string Idenifies who is the index for the submenu */
    private $subMenuIndex; 
    /** @var string The main body */
    private $body; 
    /** @var string The words in the footer of the page */
    private $footer; 
    /** @var array Contains a list of JS files for the bottom */
    private $javascriptsBottom=array(); 
    /** @var string Any custom string added at the bottom */
    private $customBottom = "";

    /** templateLogic constructor adds data to ALL the pages */
    function __construct() {
        //add these to all pages
        $this->addCSS("style.css");
        $this->addCSS("misc.css");
        $this->addJavascriptTop("jquery-1.11.2.min.js");
    }
    /** 
     * templateLogic constructor adds data to ALL the pages 
     * 
     * @param string $inputCSS The file name (or subfolder/subfolder/file) of the CSS file to added
     * @return void
     */
    function addCSS($inputCSS, $thirdParty = false) {
        if ($thirdParty == false){
            $this->stylesheets[] = STYLES_LOCATION . '/' . $inputCSS;   //add to array
        } else {
            $this->stylesheets[] = STYLES_THIRD_PARTY_LOCATION . '/' . $inputCSS; 
        }  
    }
    /** 
     * This function adds javascript to the top of the page 
     * 
     * Use ths function if the JS is critial to the display of the page,
     * otherwise, use "addJavascriptBottom" so the page load is faster
     * 
     * @param string $inputJS The file name of the CSS file to added
     * @return void
     */
    function addJavascriptTop($inputJS, $thirdParty = false) {
        if ($thirdParty == false){
            $this->javascriptsTop[] = STYLES_DATA_LOCATION . '/' . $inputJS; //add to array
        } else {
            $this->javascriptsTop[] = STYLES_THIRD_PARTY_LOCATION . '/' . $inputJS; //add to array
        }
    
    }
    /** 
     * This function data data to the top of the HTML page using string parameter. 
     * 
     * Recommended to use addCustomHeadersStart/End for syntax highlighting instead though.
     * 
     * @return void
     */
    function addCustomHeaders($inputString) {
        $this->customHeaders .= $inputString;   //add to string
    }
    /** 
     * This function starts adding data to the top of the HTML page.
     * 
     * Put the data in-between this and addCustomHeadersEnd to achieve result.
     * (like startbody and endbody)
     * 
     * @return void
     */
    function addCustomHeadersStart() {
        ob_start();
    }
    /** 
     * This functionends adding data to the top of the HTML page.
     * 
     * Put the data in-between this and addCustomHeadersStart to achieve result.
     * (like startbody and endbody)
     * 
     * @return void
     */
    function addCustomHeadersEnd() {
        $this->customHeaders .= ob_get_clean();   //add the output to string
    }
    /** 
     * This function sets the Title of the page in the "title" tag
     * 
     * If no title is provided, it will set to "STYLES_SITE_HEADING" constant
     * Also if heading is set, and this is not, it will be same as heading
     * 
     * @param string $inputTitle The title to the set
     * @return void
     */
    function setTitle($inputTitle = NULL) {
        if ($inputTitle !== NULL) {
            $this->title = $inputTitle;     //set the title (if var is sent)
        } else if (!is_null($this->heading)) {
            $this->title = $this->heading;  //same as heading (if it is set)
        } else {
            $this->title = STYLES_SITE_HEADING; //same as default heading (otherwise)
        }
    }
    /** 
     * This function sets the Heading of the page in the <h1> tag
     * 
     * If no title is provided, it will set to the same as the title variable,
     * otherwise, it will be the "STYLES_SITE_HEADING" constant
     * 
     * @param string $inputHeading The heading to be set
     * @return void
     */
    function setHeading($inputHeading = NULL) {
        if ($inputHeading !== NULL) {
            $this->heading = $inputHeading;     //set the heading (if var is sent)
        } else if (!is_null($this->title)) {
            $this->heading = $this->title;          //same as title (if title is set)
        } else {
            $this->heading = STYLES_SITE_HEADING;   //same as default heading/title (otherwise)
        }
        
    }
    /** 
     * This function sets the sub menu type (if any)
     * 
     * If set, a sub meni will be printed
     * Applicable types are "take-quiz", "create-quiz", "edit-quiz", "stats", "about" & "help" 
     * 
     * @param string $inputType The type to be set
     * @param string $index Teels the submenu which is NOT the index, set to the page name
     */
    function setSubMenuType($inputType = NULL, $inputIndex = NULL) {
        //choose which file to include on the template page
        $this->subMenuIndex = $inputIndex;
        switch ($inputType) {
            case "take-quiz":
                $this->addCSS("take-quiz-style.css");
                $this->subMenuFile = "takeQuizSubMenu.php";
                break;
            case "create-quiz":
                $this->addCSS("create-quiz-style.css");
                $this->subMenuFile = "createQuizSubMenu.php";
                break;
            case "edit-quiz":
                $this->addCSS("edit-quiz-style.css");
                $this->subMenuFile = "editQuizSubMenu.php";
                break;
            case "stats":
                $this->addCSS("stats-style.css");
                $this->subMenuFile = "statsSubMenu.php";
                break;    
            case "about":
                $this->addCSS("about-style.css");
                $this->subMenuFile = "aboutSubMenu.php";
                break;    
            case "help":
                $this->addCSS("help-style.css");
                $this->subMenuFile = "helpSubMenu.php";
                break;
            case NULL:
                $this->subMenuFile = NULL;
                break;
            default:    //just in case
                $this->subMenuFile = NULL;
                break;
        }
    }
    /** 
     * This function stores the body in a buffer to output later
     * 
     * This turns output buffering on. While output buffering is 
     * active no output is sent from the script (other than headers), 
     * instead the output is stored in an internal buffer. "endBody" collects 
     * the buffer
     * 
     * @return void
     */
    function startBody() {
    ob_start();
    }
    /** 
     * This function collects the buffer's contents to variable
     * 
     * If a string is passed to it, the buffer will be disacarded and the body 
     * will be set the the that string
     * 
     * @param string $inputString The string that body will be overiden with
     * @return void
     */
    function endBody($inputString = NULL) {
        if ($inputString === NULL){ //normal
            $this->body = ob_get_clean();   //Returns the contents of the output 
                                            //buffer and end output buffering
        } else {
            ob_end_clean();                 //discard the buffer
            $this->body = $inputString;     //Overide & show any errors 
        }
    }
    /** 
     * Set the footer variable
     * 
     * If no string passed, it will be set the default footer, 
     * STYLES_SITE_FOOTER
     * 
     * @param string $inputFooter The footer valve to be set
     * @return void
     */
    function setFooter($inputFooter = NULL) {
        if ($inputFooter !== NULL) {
            $this->footer = $inputFooter;      //set the title
        } else {
            $this->footer = STYLES_SITE_FOOTER; //same as default heading otherwise
        }
    }
    /** 
     * Adds javascript to the bottom of the page 
     * 
     * @param string $inputJS The filename of the JS script
     * @return void
     */
    function addJavascriptBottom($inputJS, $thirdParty = false) {
        if ($thirdParty == false){
            $this->javascriptsBottom[] = STYLES_DATA_LOCATION . '/' . $inputJS;  //add to array
        } else {
            $this->javascriptsBottom[] = STYLES_THIRD_PARTY_LOCATION . '/' . $inputJS;  //add to array
        }
    
    }
    /** 
     * Adds a sring to the bottom of the page 
     * 
     * Use for page specifc styling, of if functionality is present in this 
     * template
     * Note: recommended to use addCustomBottom/End for syntax highlighting
     * 
     * @param string $inputString The filename of the JS script
     * @return void
     */
    function addCustomBottom($inputString) {
        $this->customBottom .= $inputString;    //add to string
    }

    /** 
     * This function starts adding data to the bottom of the HTML page.
     * 
     * Put the data in-between this and addCustomBottomEnd to achieve result.
     * (like startbody and endbody)
     * 
     * @return void
     */
    function addCustomBottomStart($inputString) {
        ob_start();
    }
    /** 
     * This functionends adding data to the top of the HTML page.
     * 
     * Put the data in-between this and addCustomBottomStart to achieve result.
     * (like startbody and endbody)
     * 
     * @return void
     */
    function addCustomBottomEnd() {
        $this->customHeaders .= ob_get_clean();   //add the output to string
    }
    /** 
     * Ensures that the core places of the page are set
     * 
     * Check the Heading, Title, Body & Footer are set, if not their respective 
     * functions are tiggered so that the defaults will be used. 
     * 
     * @return void
     */
    function checkPageIsReady() {
        //if heading not set, call it to set defaults, likewise for the rest
        if (is_null($this->heading)){
            $this->setHeading();
        }
        if (is_null($this->title)){
            $this->setTitle();
        }
        if (is_null($this->body)){
            $this->endBody();
        }
        if (is_null($this->footer)){
            $this->setFooter();
        }
    }
    /** 
     * Renders the the page using the previously set variables 
     * 
     * runs "checkPageIsReady" to ensure parts of the page are set, sets 2nd 
     * buffer, chooses which template to use with which headers set, 
     * then displays everything.
     * 
     * @param string $whichTemplate chooses which template/headers to run, if 
     * no input is given, the default template is used.
     * @return void
     */
    function render($whichTemplate = NULL) {
        $this->checkPageIsReady();
        ob_start();
        header('Content-Type: text/html; charset=UTF-8'); //all pages are utf-8
        switch ($whichTemplate) {
            case NULL:
                include("templatePage.php");
                break;
            case "error":
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                include("templatePage.php");
                break;
            case "noHeading":
                include("templatePageNoHeading.php");
                break;
            case "blank":
                include("templatePageBlank.php");
                break;
            default:
                //This should never be reached but in case, show error
                $this->endBody("There was an error choosing a template to display the page.");
                include("templatePage.php");
                break;
        }
            return ob_get_clean();
        }
}