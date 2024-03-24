<?php

/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/



namespace webspell;

class Gallery
{

    public function saveThumb($image, $dest)
    {

        global $picsize_h;
        global $thumbwidth;
        global $new_chmod;

        $picsize_h = "130";
        $thumbwidth = "300";
        #$max_x = $thumbwidth;
        $max_x = "230";
        #$max_y = $picsize_h;
        $max_y = "300";

        $ext = getimagesize($image);
        $stop = true;
        switch ($ext[2]) {
            case IMAGETYPE_JPEG:
                $im = imagecreatefromjpeg($image);
                $stop = false;
                break;
            case IMAGETYPE_GIF:
                $im = imagecreatefromgif($image);
                $stop = false;
                break;
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($image);
                $stop = false;
                break;
            default:
                break;
        }

        if ($stop === false) {
            $result = "";
            @$x = imagesx($im);
            @$y = imagesy($im);


            if (($max_x / $max_y) < ($x / $y)) {
                @$save = imagecreatetruecolor(@$x / (@$x / @$max_x), @$y / (@$x / @$max_x));
            } else {
                @$save = imagecreatetruecolor($x / ($y / $max_y), $y / ($y / $max_y));
            }
            imagecopyresampled($save, $im, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);

            imagejpeg($save, $dest, 80);
            @chmod($dest, $new_chmod);

            imagedestroy($im);
            imagedestroy($save);
            return $result;
        } else {
            return false;
        }
    }

}    