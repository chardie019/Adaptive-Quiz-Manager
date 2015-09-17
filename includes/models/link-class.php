<?php
class linkLogic extends quizLogic {
    /**
     * Inistalises the link variable and the html, 
     * 
     * The params must be set to Null first so there no php notice
     * 
     * @param string $link the input from the current page (eg add-answer or inspect)
     * @param string $linkFromLinkPage the input from the link changing page
     * @return array ['linkHtml'] & ['linkStatus'] set if one of teh input is set, else Not linked
     */
    public static function prepareLinkHtml ($link, $linkFromLinkPage) {
        if (isset($link)){
            $linkArray['linkHtml'] = $link;
            $linkArray['linkStatus'] = "Linked to Q". $link;
        } else if (isset($linkFromLinkPage)){
            //$link = $linkFromLinkPage; //pass teh variable from the link page to this page
            $linkArray['linkHtml'] = $linkFromLinkPage;
            $linkArray['linkStatus'] = "Linked to Q". $linkFromLinkPage;
        } else {
            $linkArray['linkHtml'] = "";
            $linkArray['linkStatus'] = "Not Linked";
        }
        return $linkArray;
    }
}