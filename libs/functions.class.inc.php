<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class functions {
    
    /** Returns a list of themes this user has permission to edit
     * 
     * @param db $db Database object
     * @param ing $user_id User ID to check permissions ofr
     * @return string A list of theme names this user has permission to edit
     */
    public static function list_permissions($db, $user_id) {

        $user = new user($db, $user_id);
        $permissions = $user->get_permissions($user_id);

        for($i=0; $i<count($permissions); $i++) {
            $theme_id = $permissions[$i]['theme_id'];

            $name = $db->query("SELECT short_name from themes where theme_id='".$theme_id."'");

            $theme_list .= $name[0]['short_name'];
            if($i < count($permissions)-1) {
                $theme_list .= ", ";
            } 
        }
        return $theme_list;
    }


    /** Create thumbnail and large images
     * 
     * @param string $filename Base file name
     * @param string $filetype Filetype 
     *  (like "image/jpeg", "image/gif", "image/png", "image/pjpeg", etc.)
     */
    public static function resizeImage($filename, $filetype) {
        // The file
        //$filename = 'test.jpg';
        //
        // Filetypes:
        // "image/jpeg", "image/gif", "image/png", "image/pjpeg"
        // 
        // Set a maximum height and width
        $width = 100;
        $height = 100;

        $width_large=500;
        $height_large=500;
        
        // Add "_large" suffix for large image,
        // "_orig" to the original image.
        // Base filename will be the thumbnail
        $filename_large = str_replace(".", "_large.", $filename);
        $filename_orig= str_replace(".", "_orig.", $filename);
        // Content type
        //header('Content-Type: '.$filetype);

        // Get new dimensions
        list($width_orig, $height_orig) = getimagesize($filename);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            //height > width

           $width = $height*$ratio_orig;
           $width_large = $height_large*$ratio_orig;
        } else {

           $height = $width/$ratio_orig;
           $height_large = $width_large/$ratio_orig;
        }



        // Resample
        $image_p = imagecreatetruecolor($width, $height);
        $image_large = imagecreatetruecolor($width_large, $height_large);
        $image_orig = imagecreatetruecolor($width_orig, $height_orig);

        if($filetype == "image/jpeg" || $filetype == "image/pjpeg") {
            $image = imagecreatefromjpeg($filename);
        } else if($filetype == "image/gif") {
            $image = imagecreatefromgif($filename);
        } else if($filetype == "image/png") {
            $image = imagecreatefrompng($filename);
        }
        try {

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        imagecopyresampled($image_orig, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
        imagecopyresampled($image_large, $image, 0, 0, 0, 0, $width_large, $height_large, $width_orig, $height_orig);

        // Output

        if($filetype == "image/jpeg" || $filetype == "image/pjpeg") {
            imagejpeg($image_p, $filename, 100);
            imagejpeg($image_large, $filename_large, 100);
            imagejpeg($image_orig, $filename_orig, 100);
        } else if($filetype == "image/gif") {

            imagegif($image_p, $filename);
            imagegif($image_large, $filename_large);
            imagegif($image_orig, $filename_orig);
        } else if($filetype == "image/png") {

            imagepng($image_p, $filename, 9);
            imagepng($image_large, $filename_large, 9);
            imagepng($image_orig, $filename_orig, 9);
        }
        } catch(Exception $e) {
            echo($e."<BR>");
            echo($e->getTraceAsString(). "<BR>");
        }

    }
}