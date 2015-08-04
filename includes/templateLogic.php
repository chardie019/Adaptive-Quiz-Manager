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
        $this->addCSS(STYLES_LOCATION . "/style.css");
        $this->addJavascriptTop(STYLES_LOCATION . "/jquery-1.11.2.min.js");
    }
    /** 
     * templateLogic constructor adds data to ALL the pages 
     * 
     * @param string $inputCSS The file name of the CSS file to added
     * @return void
     */
    function addCSS($inputCSS) {
    $this->stylesheets[] = $inputCSS;   //add to array
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
    function addJavascriptTop($inputJS) {
    $this->javascriptsTop[] = $inputJS; //add to array
    }
    /** 
     * This function adds a string to head of the html page 
     * 
     * Use ths function to set page specific CSS, &lt;style&gt;,
     * if templateLogic is doesn't have funcationality you need
     * or the use case is rare
     * 
     * @param string $inputString The string to be added to the html page
     * @return void
     */
    function addCustomHeaders($inputString) {
        $this->customHeaders .= $inputString;   //add to string
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
    function addJavascriptBottom($inputJS) {
    $this->javascriptsBottom[] = $inputJS;  //add to array
    }
    /** 
     * Adds a sring to the bottom of the page 
     * 
     * Use for page specifc styling, of if functionality is present in this 
     * template
     * 
     * @param string $inputString The filename of the JS script
     * @return void
     */
    function addCustomBottom($inputString) {
        $this->customBottom .= $inputString;    //add to string
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