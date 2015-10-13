<?php
/**
 * Class quizHelper
 *
 * Provides generic functions to support general functions
 * These functions usually upload a file, return file paths etc and DO NOT touch the databse
 */
class quizHelper extends quizLogic {
    /**
     * Returns the web image path
     * 
     * @param string $quizId the quiz the image belongs to
     * @param string $targetFileName The image filename
     * @return string The real file path and $targetFileName(in the same string, if provided eg C:\images\1.png
     */
    public static function returnWebImageFilePath ($quizId, $targetFileName = ""){
        $sharedQuizId = quizLogic::returnSharedQuizID($quizId);
        $target_dir = STYLES_QUIZ_IMAGES_LOCATION . "/$sharedQuizId/";
        if ($targetFileName == ""){
            return $target_dir;
        }else if (is_null($targetFileName)){
            return NULL; //dont change it
        } else {
            return $target_dir . $targetFileName;
        }
    }
    /**
     * Return the local image path
     * 
     * @param string $quizId the quiz the image belongs to
     * @param string $targetFileName The image filename
     * @return string The real file path and $targetFileName(in the same string, if provided eg C:\images\1.png
     */
    public static function returnRealImageFilePath ($quizId, $targetFileName = NULL){
        $sharedQuizId = quizLogic::returnSharedQuizID($quizId);
        $target_dir = STYLES_QUIZ_IMAGES_LOCATION_DIR . "/$sharedQuizId/";
        if (is_null($targetFileName)){
            return $target_dir;
        } else {
            return $target_dir . $targetFileName;
        }
    }
    /**
     * Uploads an image for the quiz question
     * 
     * @param array $filesUpload The file post array $_FILES
     * @param string $imageFieldName The name of the image upload's field being uploaded
     * @param string $quizId The id of the quiz assoicated
     * @return array|boolean false on fail. otherwise ['imageUploadError'] is a message and 
     * ['imageAltError'] is the alt error message
     * ['targetDir'] is the upload directory
     */
    public static function handleImageUploadValidation($filesUpload, $imageFieldName, $quizId, $questionAlt, $oldFilename = "") {
        //Validate Image upload
        //Double \\ is needed at the end of path to cancel out the single \ effect leading into "
        //$target_dir = "C:\xampp\htdocs\aqm\data\quiz-images\\"; 
        $target_dir = self::returnRealImageFilePath($quizId);
        $targetFileName = basename($_FILES[$imageFieldName]["name"]);
        $result['targetDir'] = $target_dir;
        $target_file = $target_dir . $targetFileName;
        $target_old_file = $target_dir . $oldFilename;
        $uploadOk = 1;
        $noError = 1;
        $noFileExistError = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        //delete old file beofre upload
        if (!empty($oldFilename) && file_exists($target_old_file)) {
            unlink($target_old_file);
        }
        //check image doesn't voilate php.ini's rules
        if ($_FILES[$imageFieldName]['error'] != 0) {
            $result['imageUploadError'] = $_FILES[$imageFieldName]['error'];
            $uploadOk = 0;
        }
        
        // Check if image file is an actual image or fake image
        if ($uploadOk === 1 && createPath($target_dir) && is_uploaded_file($filesUpload[$imageFieldName]["tmp_name"])){
            $check = getimagesize($filesUpload[$imageFieldName]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $result['imageUploadError'] = "Error procession the file, are you sure it's an image?";
                $uploadOk = 0;
            }
            // Check if file already exists inside folders
            if (file_exists($target_file)) {
                $result['imageUploadError'] = "The name of your image may be alrady taken in this quiz. Try renaming the file.";
                $uploadOk = 0;
                $noFileExistError = 0;
            }
            // Check file size is smaller than 500kb, can change this later
            if ($filesUpload[$imageFieldName]["size"] > 5000000) { //5MB
                $result['imageUploadError'] = "The image must be less than 5 MB.";
                $uploadOk = 0;
            }
            // Allow certain image file types only *Stop people uploading other file types e.g. pdf
            if(strcasecmp($imageFileType, "jpg") != 0  && strcasecmp($imageFileType, "png") != 0 && strcasecmp($imageFileType, "jpeg") != 0
            && strcasecmp($imageFileType, "gif") != 0) {
                $result['imageUploadError'] = "The Image can only be .jpg, .png, .jpeg and .gif file types.";
                $uploadOk = 0;
            }
            //only check ALT text if there is an image (which is optional)
            if($questionAlt == " " || $questionAlt == "" || $questionAlt == NULL){
                $result['imageAltError'] = "Error: Please enter alternative text to the question more accessible.";
                $noError = 0;
            }
            //still good
            if ($uploadOk == 1){
                if (move_uploaded_file($filesUpload[$imageFieldName]["tmp_name"], $target_file)) {
                    //uploaded
                } else { //nope it failed
                    $uploadOk  = 0;
                }
            }
        }
        // Check if $uploadOk is set to 0 by an upload error. Exit if true.
        if ($uploadOk == 0 || $noError = 0) {
            if ($uploadOk == 1) {
                $result['imageUploadError'] = "There was another unrelated error, please upload again after fixing it.";
            }
            if ($noFileExistError == 1 && file_exists($target_file)){ //wasnt a files exist error, so delete it
               unlink($target_file); //delete the file 
            }
            $result['result'] = false;
        } else{
            $result['result'] = true;
        }
        if (!isset($result['imageUploadError'])){$result['imageUploadError'] = "";}
        if (!isset($result['imageAltError'])){$result['imageAltError'] = "";}
        if (!isset($result['imageUploadError'])){$result['imageUploadError'] = "";}
        return $result; //retrun the array now
    }
}