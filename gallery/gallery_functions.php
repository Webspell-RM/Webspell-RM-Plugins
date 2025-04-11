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

    public function showThumb($picID)
    {

        global $_language;
        $_language->readModule('gallery', true);
        global $thumbwidth, $_language;

        $pic = mysqli_fetch_array(
            safe_query(
                "SELECT * FROM `" . PREFIX . "plugins_gallery_pictures` WHERE `picID` = " . (int)$picID
            )
        );
        if ($pic['picID']) {
            $pic['gallery'] = str_break(stripslashes($this->getGalleryName($picID)), 45);
            if (file_exists('./includes/plugins/gallery/images/thumb/' . $picID . '.jpg')) {
                $pic['image'] =
                    '<a href="index.php?site=gallery&amp;picID=' . $picID . '">' .
                    '<img class="img-fluid" style="height: 250px" src="./includes/plugins/gallery/images/thumb/' . $picID . '.jpg" width="' . $thumbwidth . '" alt="" /></a>';
            } else {
                $pic['image'] =
                    '<a href="index.php?site=gallery&amp;picID=' . $picID . '">' .
                    '<img src="no-image.jpg" width="' . $thumbwidth . '" alt="' .
                    $_language->module['no_thumb'] . '" /></a>';
            }
            $pic['comments'] = mysqli_num_rows(
                safe_query(
                    "SELECT
                        `commentID`
                    FROM
                        `" . PREFIX . "comments`
                    WHERE
                        `parentID` = " . (int)$picID . " AND
                        `type` = 'ga'"
                )
            );
            $ergebnis = mysqli_fetch_array(
                safe_query(
                    "SELECT
                        `date`
                    FROM
                        `" . PREFIX . "plugins_gallery` AS gal,
                        `" . PREFIX . "plugins_gallery_pictures` AS pic
                    WHERE
                        gal.`galleryID` = pic.`galleryID` AND
                        pic.`picID` = " . (int)$picID
                )
            );
            $pic['date'] = getformatdate($ergebnis['date']);
            $pic['groupID'] = $this->getGroupIdByGallery($pic['galleryID']);
            $pic['name'] = stripslashes(clearfromtags($pic['name']));

            $data_array = array();
            $data_array['$galleryID'] = $pic['galleryID'];
            $data_array['$name'] = $pic['name'];
            #$thumb = $GLOBALS["_template"]->replaceTemplate("gallery_content_showthumb", $data_array);
            $thumb = $GLOBALS["_template"]->loadTemplate("gallery","content_showthumb", $data_array, $plugin_path);

        } else {
            $thumb = '<tr><td colspan="2">' . $_language->module['no_picture'] . '</td></tr>';
        }
        return $thumb;
    }

    public function saveThumb($image, $dest)
    {

        global $picsize_h;
        global $thumbwidth;
        global $new_chmod;

        #$picsize_h = "130";
        #$thumbwidth = "300";
        #$max_x = $thumbwidth;
        $max_x = "450";
        #$max_y = $picsize_h;
        $max_y = "500";

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

    public function randomPic($galleryID = 0)
    {

        if ($galleryID) {
            $only = "WHERE `galleryID` = " . (int)$galleryID;
        } else {
            $only = '';
        }
        $anz = mysqli_num_rows(safe_query("SELECT picID FROM `" . PREFIX . "plugins_gallery_pictures` $only"));
        $selected = rand(1, $anz);
        $start = $selected - 1;
        $pic = 
        safe_query(
            "SELECT `picID` FROM `" . PREFIX . "plugins_gallery_pictures` $only LIMIT $start, $anz"
        )
        ;
        if(mysqli_num_rows($pic ) > '0') {
            $pic = mysqli_fetch_array($pic);
        return $pic['picID'];
        } else {
            return '';
        }



        
    }

    public function getGalleryName($picID)
    {

        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT
                    gal.name
                FROM
                    `" . PREFIX . "plugins_gallery_pictures` AS pic,
                    `" . PREFIX . "plugins_gallery` AS gal
                WHERE
                    pic.`picID` = " . (int)$picID . " AND
                    gal.`galleryID` = pic.`galleryID`"
            )
        );
        return htmlspecialchars($ds['name']);

    }

    public function getGroupName($groupID)
    {
        $ds = 
        safe_query(
            "SELECT name FROM `" . PREFIX . "plugins_gallery_groups` WHERE `groupID` = " . (int)$groupID
        )
        ;
        if(mysqli_num_rows($ds ) > '0') {
            $ds = mysqli_fetch_array($ds);
        return htmlspecialchars($ds['name']);
        } else {
            return '';
        }
    }

    public function getGroupIdByGallery($galleryID)
    {

        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT `groupID` FROM `" . PREFIX . "plugins_gallery` WHERE `galleryID` = " . (int)$galleryID
            )
        );
        return $ds['groupID'];
    }

    public function isGalleryOwner($galleryID, $userID)
    {
        if (empty($userID)) {
            return false;
        }

        return (
            mysqli_num_rows(
                safe_query(
                    "SELECT
                        `galleryID`
                    FROM
                        `" . PREFIX . "plugins_gallery`
                    WHERE
                        `userID` = " . (int)$userID . " AND
                        `galleryID` = " . (int)$galleryID
                )
            ) > 0
        );
    }

    public function getGalleryOwner($galleryID)
    {

        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT `userID` FROM `" . PREFIX . "plugins_gallery` WHERE `galleryID` = " . (int)$galleryID
            )
        );
        return $ds['userID'];

    }

    public function getLargeFile($picID)
    {

        if (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.jpg')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.jpg';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.gif')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.gif';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.png')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.png';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.webp')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.webp';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.avif')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.avif';
        } else {
            $file = 'images/no-image.jpg';
        }

        return $file;

    }

    public function getThumbFile($picID)
    {

        if (file_exists('./includes/plugins/gallery/images/thumb/' . $picID . '.jpg')) {
            $file = './includes/plugins/gallery/images/thumb/' . $picID . '.jpg';
        } else {
            $file = 'images/no-image.jpg';
        }

        return $file;

    }

    public function getUserSpace($userID)
    {

        $size = 0;
        $ergebnis = safe_query(
            "SELECT
                pic.picID
            FROM
                `" . PREFIX . "plugins_gallery_pictures` AS pic,
                `" . PREFIX . "plugins_gallery` AS gal
            WHERE
                gal.`userID` = " . (int)$userID . " AND
                gal.`galleryID` = pic.`galleryID`"
        );
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $size +=
                filesize('./includes/plugins/gallery/images/thumb/' . $ds['picID'] . '.jpg') +
                filesize($this->getLargeFile($ds['picID']));
        }
        return $size;
    }
}

function getanzgallerycomments($id, $type)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT commentID FROM `" . PREFIX . "plugins_gallery_comments` WHERE `parentID` = " . (int)$id . " AND type='$type'"
        )
    );
}

function getlastgallerycommentposter($id, $type)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `userID`,
                `nickname`
            FROM
                `" . PREFIX . "plugins_gallery_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        )
    );
    if ($ds['userID']) {
        return getnickname($ds['userID']);
    }

    return htmlspecialchars($ds['nickname']);
}

function getlastgallerycommentdate($id, $type)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `date`
            FROM
                `" . PREFIX . "plugins_gallery_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        )
    );
    return $ds['date'];
}
