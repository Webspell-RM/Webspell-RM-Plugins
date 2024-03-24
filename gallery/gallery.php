<?php
/*¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\
| _    _  ___  ___  ___  ___  ___  __    __      ___   __  __       |
|( \/\/ )(  _)(  ,)/ __)(  ,\(  _)(  )  (  )    (  ,) (  \/  )      |
| \    /  ) _) ) ,\\__ \ ) _/ ) _) )(__  )(__    )  \  )    (       |
|  \/\/  (___)(___/(___/(_)  (___)(____)(____)  (_)\_)(_/\/\_)      |
|                       ___          ___                            |
|                      |__ \        / _ \                           |
|                         ) |      | | | |                          |
|                        / /       | | | |                          |
|                       / /_   _   | |_| |                          |
|                      |____| (_)   \___/                           |
\___________________________________________________________________/
/                                                                   \
|        Copyright 2005-2018 by webspell.org / webspell.info        |
|        Copyright 2018-2019 by webspell-rm.de                      |
|                                                                   |
|        - Script runs under the GNU GENERAL PUBLIC LICENCE         |
|        - It's NOT allowed to remove this copyright-tag            |
|        - http://www.fsf.org/licensing/licenses/gpl.html           |
|                                                                   |
|               Code based on WebSPELL Clanpackage                  |
|                 (Michael Gruber - webspell.at)                    |
\___________________________________________________________________/
/                                                                   \
|                     WEBSPELL RM Version 2.0                       |
|           For Support, Mods and the Full Script visit             |
|                       webspell-rm.de                              |
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("gallery", $plugin_path);

// -- NEWS INFORMATION -- //
include_once("gallery_functions.php");

    $data_array = array();
    $data_array['$title'] = $plugin_language['gallery'];
    $data_array['$subtitle']='Gallery';

    $template = $GLOBALS["_template"]->loadTemplate("gallery","head", $data_array, $plugin_path);
    echo $template;

    $galclass = new \webspell\Gallery;

    $filepath = $plugin_path."images/thumb/";
    $filelargepath = $plugin_path."images/large/";

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
    $dx = mysqli_fetch_array($settings);
    
    $gallerygroups = @$dx[ 'groups' ];

//Options

$galleries_per_row = 2;
$pics_per_row = 2;

//Script

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if (isset($_POST[ 'saveedit' ])) {
    
    $galclass = new \webspell\Gallery;

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `galleryID`
            FROM
                `" . PREFIX . "plugins_gallery_pictures`
            WHERE
                `picID` = '" . (int)$_POST[ 'picID' ] . "'"
        )
    );

    if ((isgalleryadmin($userID) || $galclass->isGalleryOwner($ds[ 'galleryID' ], $userID)) && $_POST[ 'picID' ]) {
        
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_gallery_pictures`
            SET
                `name` = '" . $_POST[ 'name' ] . "',
                `comment` = '" . $_POST[ 'comment' ] . "',
                `comments` = '" . $_POST[ 'comments' ] . "'
            WHERE
                `picID` = '" . (int)$_POST[ 'picID' ] . "'"
        );
        if (isset($_POST[ 'reset' ])) {
            safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_gallery_pictures`
                SET
                    `views` = '0'
                WHERE
                    `picID` = '" . $_POST[ 'picID' ] . "'"
            );
        }
 
        ##################

        $dir = './includes/plugins/gallery/images/';

        

        $id = $_POST[ 'picID' ];
        $errors = array();

        $_language->readModule('formvalidation', true);
        $upload = new \webspell\HttpUpload('banner');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
 
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());
 
                    if (is_array($imageInformation)) {
                        switch ($imageInformation[ 2 ]) {
                            case 1:
                                $endung = '.gif';
                                break;
                            case 3:
                                $endung = '.png';
                                break;
                            default:
                                $endung = '.jpg';
                                break;
                        }
                        $file = $id.$endung;

                        if ($_POST[ 'name' ]) {
                            $insertname = $_POST[ 'name' ];
                        } else {
                            $insertname = $picture[ 'name' ];
                        }

                        if ($upload->saveAs($filelargepath . $file, true)) {
                                @chmod($file, $new_chmod);
                                safe_query(
                                "UPDATE " . PREFIX . "plugins_gallery_pictures SET name='" . $insertname . "' WHERE picID='" . $_POST[ 'picID' ] . "'"
                            );
                            }    
 
                                                    
                            #copy($dir . 'large/' . $id . $endung);
                            $galclass->saveThumb($dir . 'large/' . $id . $endung, $dir . 'thumb/' . $id . '.jpg');


                       
                    } else {
                        $errors[] = $plugin_language[ 'broken_image' ];
                    }
                } else {
                    $errors[] = $plugin_language[ 'unsupported_image_type' ];
                }
            } else {
                $errors[] = $upload->translateError();
            }
        }
    
        ###################

        } else {
        redirect('index.php?site=gallery', $plugin_language[ 'no_pic_set' ]);
        }

    redirect('index.php?site=gallery&amp;picID=' . $_POST[ 'picID' ], '', 0);
                
} elseif ($action == "edit") {

    $picID = $_GET[ 'id' ];
    if (!ispageadmin($userID) || !isnewsadmin($userID)) {
        echo generateAlert($plugin_language['no_access'], 'alert-danger');
    } else {
        $ds = mysqli_fetch_array(safe_query("SELECT * FROM
                        `" . PREFIX . "plugins_gallery_pictures`
                    WHERE
                        `picID` = '" . $picID . "'")
            );

    $dir = './includes/plugins/gallery/images/';
    
        
        $comments = '<option value="0">' . $plugin_language[ 'no_comments' ] . '</option><option value="1">' .
            $plugin_language[ 'user_comments' ] . '</option><option value="2">' .
            $plugin_language[ 'visitor_comments' ] . '</option>';
        $comments = str_replace(
            'value="' . $ds[ 'comments' ] . '"',
            'value="' . $ds[ 'comments' ] . '" selected="selected"',
            $comments
        );

        if (!empty($ds[ 'picID' ])) {
            $pic = '<img class="img-fluid gallery rounded shadow-lg" style="width: 100%; max-width: 300px;min-width: 120px;" src="../' . $filepath . $ds[ 'picID' ] . '.jpg" alt="">';
        } else {
            $pic = $plugin_language[ 'no_upload' ];
        }

    if ($_GET[ 'id' ]) {
        #####################################
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        $name = str_replace('"', '&quot;', getinput($ds[ 'name' ]));
        $comment = getinput($ds[ 'comment' ]);
        $data_array = array();
        $data_array['$name'] = $name;
        $data_array['$comment'] = $comment;
        $data_array['$comments'] = $comments;
        $data_array['$picID'] = $picID;
        $data_array['$pic'] = $pic;
        
        $data_array['$lang_edit_picture'] = $plugin_language['edit_picture'];
        $data_array['$lang_name'] = $plugin_language['name'];
        $data_array['$lang_comment'] = $plugin_language['comment'];
        $data_array['$lang_comments'] = $plugin_language['comments'];
        $data_array['$lang_reset_views'] = $plugin_language['reset_views'];
        $data_array['$lang_update_picture'] = $plugin_language['update_picture'];
        $data_array['$lang_picture'] = $plugin_language['picture'];
        $data_array['$lang_pic_upload_info'] = $plugin_language['pic_upload_info'];

        $template = $GLOBALS["_template"]->loadTemplate("gallery","edit", $data_array, $plugin_path);
        echo $template;

    } else {
        redirect('index.php?site=gallery', $plugin_language[ 'no_pic_set' ]);
        
    }

  }



} elseif ($action == "delete" ) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    
    $galclass = new \webspell\Gallery;

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `galleryID`
            FROM
                `" . PREFIX . "plugins_gallery_pictures`
            WHERE
                `picID` = '" . (int)$_GET[ 'id' ] . "'"
        )
    );

    if ((isgalleryadmin($userID) || $galclass->isGalleryOwner($ds[ 'galleryID' ], $userID)) && $_GET[ 'id' ]) {
        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `galleryID`
                FROM
                    `" . PREFIX . "plugins_gallery_pictures`
                WHERE `picID` = '" . (int)$_GET[ 'id' ] . "'"
            )
        );

        $dir = './includes/plugins/gallery/images/';

        //delete thumb

        @unlink($dir . 'thumb/' . $_GET[ 'id' ] . '.jpg');

        //delete original

        if (file_exists($dir . 'large/' . $_GET['id'] . '.jpg')) {
            @unlink($dir . 'large/' . $_GET['id'] . '.jpg');
        } elseif (file_exists($dir . 'large/' . $_GET['id'] . '.gif')) {  
            @unlink($dir . 'large/' . $_GET['id'] . '.gif');
        } else {
            @unlink($dir . 'large/' . $_GET['id'] . '.png');
        }

        
        //delete database entry

        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_gallery_pictures`
            WHERE
                `picID` = '" . (int)$_GET[ 'id' ] . "'"
        );
        
    }
    echo $plugin_language[ 'success_delete' ];
    
    redirect('index.php?site=gallery', "", 2); return false;

    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif ($action == "diashow" || $action == "window") {
    
   
    if (!isset($_GET[ 'picID' ])) {
        $result = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `picID`
                FROM
                    `" . PREFIX . "plugins_gallery_pictures`
                WHERE
                    `galleryID` ='" . (int)$_GET[ 'galleryID' ] . "'
                ORDER BY
                    `picID` ASC
                LIMIT 0,1"
            )
        );
        $picID = (int)$result[ 'picID' ];
    } else {
        $picID = (int)$_GET[ 'picID' ];
    }


    //get name+comment
    $ds = mysqli_fetch_array(safe_query("SELECT name, comment FROM ".PREFIX."plugins_gallery_pictures WHERE picID='".$picID."'"));


    //get next

    $browse = mysqli_fetch_array(safe_query("SELECT picID FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".(int)$_GET['galleryID']."' AND picID>".$picID." ORDER BY picID ASC LIMIT 0,1"));
    
  if(@$browse['picID'] and $_GET['action'] == "diashow") echo '<meta http-equiv="refresh" content="2;URL=index.php?site=gallery&action=diashow&amp;galleryID='.(int)$_GET['galleryID'].'&amp;picID='.$browse['picID'].'" />';

    echo '</head><body><center>';

    if($_GET['action'] == "diashow") {
        if(@$browse['picID']) {
            echo '<a href="index.php?site=gallery&action=diashow&amp;galleryID='.$_GET['galleryID'].'&amp;picID='.$browse['picID'].'">';
            safe_query("UPDATE ".PREFIX."plugins_gallery_pictures SET views=views+1 WHERE picID='".$picID."'");
        }
    }
    else echo '<a href="javascript:close()">';

    //output image

    if (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.jpg')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.jpg';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.gif')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.gif';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.png')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.png';
        } else {
            $file = '';
        }

    echo ''.$plugin_language['webs_diashow'].' '.$ds['name'].'<br>

    <!--<img class="img-fluid gallery rounded shadow-lg" src="picture.php?id='.$picID.'" border="0" alt="" />-->
    <img class="img-fluid gallery rounded shadow-lg" src="'.$file.'" alt="Picture '.$picID.'">


    <br /><b>'.$ds['comment'].'</b>';

    if(@$browse['picID'] or $_GET['action'] == "window") echo '</a>';

    echo '<br><a class="btn btn-warning" href="javascript:close()">back</a></center>';
    
} elseif (isset($_POST[ 'saveeditcomment' ])) {
    
    if (!isfeedbackadmin($userID) && !isvideocommentposter($userID, $_POST[ 'commentID' ])) {
        die('No access');
    }
 
    $message = $_POST[ 'message' ];
    $author = $_POST[ 'authorID' ];
    $referer = urldecode($_POST[ 'referer' ]);
 
    // check if any admin edited the post
    if (safe_query(
        "UPDATE
                `" . PREFIX . "plugins_gallery_comments`
            SET
                comments='" . $message . "'
            WHERE
                commentID='" . (int)$_POST[ 'commentID' ] . "'"
    )
    ) {
        header("Location: " . $referer);
    }
} elseif ($action == "editcomment") {
    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("comments", $plugin_path);

    $id = $_GET[ 'id' ];
    $referer = $_GET[ 'ref' ];
    
    if (isfeedbackadmin($userID) || isvideocommentposter($userID, $id)) {
        if (!empty($id)) {
            $dt = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_comments WHERE commentID='" . (int)$id."'");
            if (mysqli_num_rows($dt)) {
                $ds = mysqli_fetch_array($dt);
                $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><b>' .
                    getnickname($ds[ 'userID' ]) . '</b></a>';
                $message = getinput($ds[ 'comments' ]);
                $message = preg_replace("#\n\[br\]\[br\]\[hr]\*\*(.+)#si", '', $message);
                $message = preg_replace("#\n\[br\]\[br\]\*\*(.+)#si", '', $message);
 
                $data_array = array();
                $data_array['$message'] = $message;
                $data_array['$authorID'] = $ds['userID'];
                $data_array['$id'] = $id;
                $data_array['$userID'] = $userID;
                $data_array['$referer'] = $referer;
               
                $data_array['$title_editcomment']=$plugin_language['title_editcomment'];
                $data_array['$edit_comment']=$plugin_language['edit_comment'];    
                
                $template = $GLOBALS["_template"]->loadTemplate("comments","edit", $data_array, $plugin_path);
                echo $template;
            } else {
                redirect($referer, $plugin_language[ 'no_database_entry' ], 2);
            }
        } else {
            redirect($referer, $plugin_language[ 'no_commentid' ], 2);
        }
    } else {
        redirect($referer, $plugin_language[ 'access_denied' ], 2);
    }
} elseif (isset($_GET[ 'picID' ])) {

    $galclass = new \webspell\Gallery;
    
    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_gallery_pictures` WHERE `picID` = '" . $_GET[ 'picID' ] . "'");
    if (mysqli_num_rows($ergebnis)) {
        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT * FROM `" . PREFIX . "plugins_gallery_pictures` WHERE `picID` = '" . (int)$_GET[ 'picID' ] . "'"
            )
        );
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_gallery_pictures`
            SET
                `views` = views+1
            WHERE
                `picID` = '" . (int)$_GET[ 'picID' ] . "'"
        );
		
		$ds[ 'dateupl' ] = getformatdatetime($ds[ 'dateupl' ]);
		
        $picturename = $ds[ 'name' ];
        $picID = $ds[ 'picID' ];

        $picture=$galclass->getlargefile($picID);
    
        $picinfo = getimagesize($picture);
        $xsize = $picinfo[0];
        $ysize = $picinfo[1];
    
        $xwindowsize = $xsize+30;
        $ywindowsize = $ysize+30;

        $comment = $ds[ 'comment' ];
        $views = $ds[ 'views' ];
		$dateimm = $ds[ 'dateupl' ];

        $filesize = round(filesize($picture)/1024,1);

        //next picture
        $browse = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `picID`
                FROM
                    `" . PREFIX . "plugins_gallery_pictures`
                WHERE
                    `galleryID` = '" . (int)$ds[ 'galleryID' ] . "'
                AND
                    `picID` > " . (int)$ds[ 'picID' ] . "
                ORDER BY
                    `picID` ASC
                LIMIT 0,1"
            )
        );
        if (@$browse[ 'picID' ]) {
            $forward = '<a class="btn btn-primary" href="index.php?site=gallery&amp;picID=' . $browse[ 'picID' ] . '#picture">' .
                $plugin_language[ 'next' ] . '</a>';
        } else {
            $forward = '';
        }

        $browse = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `picID`
                FROM
                    `" . PREFIX . "plugins_gallery_pictures`
                WHERE
                    `galleryID` = '" . (int)$ds[ 'galleryID' ] . "'
                AND
                    `picID` < " . (int)$ds[ 'picID' ] . "
                ORDER BY
                    `picID` DESC
                LIMIT 0,1"
            )
        );
        if (@$browse[ 'picID' ]) {
            $backward = '<a class="btn btn-primary" href="index.php?site=gallery&amp;picID=' . $browse[ 'picID' ] . '#picture">' .
                $plugin_language[ 'back' ] . '</a>';
        } else {
            $backward = '';
        }

        //rateform

        if ($loggedin) {
            $getgallery = safe_query(
                "SELECT `gallery_pictures` FROM `" . PREFIX . "user` WHERE `userID` = '" . (int)$userID . "'"
            );
            $found = false;
            if (mysqli_num_rows($getgallery)) {
                $ga = mysqli_fetch_array($getgallery);
                if ($ga[ 'gallery_pictures' ] != "") {
                    $string = $ga[ 'gallery_pictures' ];
                    $array = explode(":", $string);
                    $anzarray = count($array);
                    for ($i = 0; $i < $anzarray; $i++) {
                        if ($array[ $i ] == $_GET[ 'picID' ]) {
                            $found = true;
                        }
                    }
                }
            }

            if ($found) {
                $rateform = "<i>" . $plugin_language[ 'you_have_already_rated' ] . "</i>";
            } else {
                $rateform = '<form method="post" name="rating_picture' . $_GET[ 'picID' ] . '" action="index.php?site=gallery_rating"  class="form-inline row g-3">
                            <div class="col-auto">                               
                                ' . $plugin_language[ 'rate_now' ] . '
                            </div>
                            <div class="col-auto">
                                <select name="rating" class="form-control">
                                <option>0 - ' . $plugin_language[ 'poor' ] . '</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10 - ' . $plugin_language[ 'perfect' ] . '</option>
                                </select>
                                <input type="hidden" name="userID" value="' . $userID . '">
                                <input type="hidden" name="type" value="ga">
                                <input type="hidden" name="id" value="' . $_GET[ 'picID' ] . '">
                            </div>
                            <div class="col-auto">
                                <input type="submit" name="submit" value="' . $plugin_language[ 'rate' ] . '" class="btn btn-primary mb-3">
                            </div></form>';
            }
        } else {
            $rateform = '<i>' . $plugin_language[ 'rate_have_to_reg_login' ] . '</i>';
        }

        $votes = $ds[ 'votes' ];

        unset($ratingpic);
        $ratings = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        for ($i = 0; $i < $ds[ 'rating' ]; $i++) {
            $ratings[ $i ] = 1;
        }
        
        $ratingpic = '';
        foreach ($ratings as $pic) {
            $ratingpic .= '<img src="/includes/plugins/gallery/icons/rating_' . $pic . '.png" width="21" height="21" alt="">';
        }

        //admin

        if (isgalleryadmin($userID) || $galclass->isGalleryOwner($ds[ 'galleryID' ], $userID)) {
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();
  
    
            $adminaction =
                '<a href="index.php?site=gallery&amp;action=edit&amp;id=' . $_GET[ 'picID' ] .
                '" class="btn btn-warning">' . $plugin_language[ 'edit' ] . '</a>


                <input type="button" onclick="MM_confirm(
                        \'' . $plugin_language[ 'really_delete' ] . '\',
                        \'index.php?site=gallery&action=delete&amp;id=' . $ds[ 'picID' ] . '&amp;captcha_hash='.$hash.'\'
                    )" value="' . $plugin_language[ 'delete' ] . '" class="btn btn-danger">';
                    

 
        } else {
            $adminaction = "";
        } 

        //group+gallery

        $gallery = '<a href="index.php?site=gallery&amp;galleryID=' . $ds[ 'galleryID' ] . '" class="titlelink">' .
            $galclass->getGalleryName($_GET[ 'picID' ]) . '</a>';
        if ($galclass->getGroupIdByGallery($ds[ 'galleryID' ])) {
            $group =
                '<a href="index.php?site=gallery&amp;groupID=' . $galclass->getGroupIdByGallery($ds[ 'galleryID' ]) .
                '" class="titlelink">' . $galclass->getGroupName($galclass->getGroupIdByGallery($ds[ 'galleryID' ])) .
                '</a>';
        } else {
            $group = '<a href="index.php?site=gallery&amp;groupID=0" class="titlelink">' .
                $plugin_language[ 'usergalleries' ] .
                '</a> &gt;&gt; <a href="index.php?site=gallery&galleryID=' .
                $galclass->getGalleryOwner($ds[ 'galleryID' ]) . '" class="titlelink">' .
                getnickname($galclass->getGalleryOwner($ds[ 'galleryID' ])) . '</a>';
        }

        $data_array = array();
        $data_array['$group'] = $group;
        $data_array['$gallery'] = $gallery;
        $data_array['$picturename'] = $picturename;
        
        $data_array['$lang_gallery'] = $plugin_language['gallery'];
        $data_array['$lang_views'] = $plugin_language['views'];
        $template = $GLOBALS["_template"]->loadTemplate("gallery","comments_head", $data_array, $plugin_path);
        echo $template;



        if (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.jpg')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.jpg';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.gif')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.gif';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.png')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.png';
        } else {
            $file = '';
        }


        $banner = '<a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title=""
                   data-image="'.$file.'"
                   data-target="#image-gallery">
                    <img class="img-fluid gallery" src="'.$file.'" data-bs-toggle="tooltip" alt="Picture '.$picID.'"></a>';

        $data_array = array();
        $data_array['$banner'] = $banner;
        
        $template = $GLOBALS["_template"]->loadTemplate("gallery","comments_details_header", $data_array, $plugin_path);
        echo $template;


        if (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.jpg')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.jpg';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.gif')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.gif';
        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $picID . '.png')) {
            $file = './includes/plugins/gallery/images/large/' . $picID . '.png';
        } else {
            $file = '';
        }


/////////////////////////////////////////////////////////// Start View Single Image
        $banner = '<div class="text-center"><!-- Button trigger modal -->
			<a type="button" title="Picture '.$picID.'" data-fancybox="gallery" data-src="'.$file.'" data-caption="'.$picturename.'"><img class="img-fluid gallery rounded shadow-lg" src="'.$file.'" alt="Picture '.$picID.'"/></a></div>';
/////////////////////////////////////////////////////////// End View Single Image       

        
        $data_array = array();
        $data_array['$banner'] = $banner;
        $data_array['$group'] = $group;
        $data_array['$gallery'] = $gallery;
        $data_array['$picturename'] = $picturename;
        $data_array['$backward'] = $backward;
        $data_array['$forward'] = $forward;
        $data_array['$picID'] = $picID;
        #$data_array['$width'] = $width;
        $data_array['$views'] = $views;
        $data_array['$xsize'] = $xsize;
        $data_array['$ysize'] = $ysize;
        $data_array['$filesize'] = $filesize;
        $data_array['$comment'] = $comment;
        $data_array['$ratingpic'] = $ratingpic;
        $data_array['$votes'] = $votes;
        $data_array['$rateform'] = $rateform;
        $data_array['$adminaction'] = $adminaction;
		$data_array['$dateupload'] = $dateimm;

        $data_array['$lang_gallery'] = $plugin_language['gallery'];
        $data_array['$lang_views'] = $plugin_language['views'];
        $data_array['$lang_rating'] = $plugin_language['rating'];
        $data_array['$lang_votes'] = $plugin_language['votes'];
		$data_array['$lang_info'] = $plugin_language['info'];
		$data_array['$lang_pic_name'] = $plugin_language['pic_name'];
		$data_array['$lang_date_uploaded'] = $plugin_language['date_uploaded'];
		$data_array['$lang_pic_size'] = $plugin_language['pic_size'];
		$data_array['$lang_file_size'] = $plugin_language['file_size'];
		$data_array['$lang_image_description'] = $plugin_language['image_description'];



        $template = $GLOBALS["_template"]->loadTemplate("gallery","comments", $data_array, $plugin_path);
        echo $template;

        //comments

        $comments_allowed = $ds[ 'comments' ];
        $parentID = $ds[ 'picID' ];
        $type = "ga";
        $referer = "index.php?site=gallery&amp;picID=" . $ds[ 'picID' ];

        include("gallery_comments.php");
    }
} elseif (isset($_GET[ 'galleryID' ])) {
   
    $galclass = new \webspell\Gallery;

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT `name`,`number_of_images`,`images_per_page` FROM `" . PREFIX . "plugins_gallery` WHERE `galleryID` = '" . $_GET[ 'galleryID' ] . "'"
        )
    );
    
    $title = $ds[ 'name' ];
    $numberofimages = $ds[ 'number_of_images' ];

    $galleryID = (int)$_GET[ 'galleryID' ];
    if ($galclass->getGroupIdByGallery($_GET[ 'galleryID' ])) {
        $group =
            '<a href="index.php?site=gallery&amp;groupID=' . $galclass->getGroupIdByGallery($_GET[ 'galleryID' ]) .
            '" class="titlelink">' . $galclass->getGroupName($galclass->getGroupIdByGallery($_GET[ 'galleryID' ])) .
            '</a>';
    } else {
        $group = '<a href="index.php?site=gallery&amp;groupID=0" class="titlelink">' .
            $plugin_language[ 'usergalleries' ] .
            '</a> &gt;&gt; <a href="index.php?site=gallery&galleryID=' .
            $galclass->getGalleryOwner($_GET[ 'galleryID' ]) . '" class="titlelink">' .
            getnickname($galclass->getGalleryOwner($_GET[ 'galleryID' ])) . '</a>';
    }


$ergebnis = safe_query(
        "SELECT
            *
        FROM
            `" . PREFIX . "plugins_gallery_pictures`
        WHERE
            `galleryID` = '" . (int)$_GET[ 'galleryID' ] . "'
        ORDER BY
            `picID`"
    );

    if (mysqli_num_rows($ergebnis)) {
		
		/////////////////////////////////////////////////////////// Start Slideshow Image
        $diashow ='Slideshow';
		echo '<div style="display:none">';
		while ($pic = mysqli_fetch_array($ergebnis)) {
        $dir = $galclass->getLargeFile($pic[ 'picID' ]);
        $data_array = array();
        $data_array['$dir'] = $dir;
        $template = $GLOBALS["_template"]->loadTemplate("gallery","diashow", $data_array, $plugin_path);
        echo $template;
       
		}
		echo '</div>';
		////////////////////////////////////////////////////////// End Slideshow Image
		
		
    } else {
        $diashow = "";
    }

    $gallery_pictures=$numberofimages;

    $pics = mysqli_num_rows(safe_query("SELECT picID FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".$_GET['galleryID']."'"));
    $pages = ceil($pics/$gallery_pictures);
    
    if(!isset($_GET['page'])) {
        $page = 1;
    }else{
        $page = $_GET['page'];
    }    

    if($pages > 1) {
        $pagelink = makepagelink("index.php?site=gallery&amp;galleryID=".$_GET['galleryID'], $page, $pages);
    }else{
        $pagelink = '<!--<img src="images/icons/multipage.gif" width="10" height="12" alt="" /> <small>'.$plugin_language['pg_1_1'].'</small>-->';
    }

    if($page == "1") {
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".$_GET['galleryID']."' ORDER BY picID LIMIT 0, ".$gallery_pictures);
    }
    else {
        $start = $page * $gallery_pictures - $gallery_pictures;
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".$_GET['galleryID']."' ORDER BY picID LIMIT ".$start.", ".$gallery_pictures);
    }


    #$pics = mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".$_GET['galleryID']."'"));
    	
    $data_array = array();
    $data_array['$group'] = $group;
    $data_array['$title'] = $title;
    $data_array['$pics'] = $pics;
    $data_array['$pages'] = $pages;
    $data_array['$page'] = $page;
    $data_array['$diashow'] = $diashow;
    $data_array['$pagelink'] = $pagelink;
    
    $data_array['$lang_gallery'] = $plugin_language['gallery'];
    $data_array['$lang_page_s'] = $plugin_language['page_s'];
    $data_array['$lang_pictures'] = $plugin_language['pictures'];
	

    $template = $GLOBALS["_template"]->loadTemplate("gallery","gallery_head", $data_array, $plugin_path);
    echo $template;
    #echo '<tr>';
    $i = 1;

    #$percent = 100 / $pics_per_row;

    while ($pic = mysqli_fetch_array($ergebnis)) {
        
        $firstactive = '';
        if ($i == 1) {
            $firstactive = 'active';
        }

        $dir = $galclass->getThumbFile($pic[ 'picID' ]);
        #$dir = $galclass->getLargeFile($pic[ 'picID' ]);

        list($width, $height, $type, $attr) = getimagesize($dir);
        $pic[ 'comments' ] =
            mysqli_num_rows(
                safe_query(
                    "SELECT
                        `commentID`
                    FROM
                        `" . PREFIX . "plugins_gallery_comments`
                    WHERE
                        `parentID` = '" . (int)$pic[ 'picID' ] . "'
                    AND
                        `type` = 'ga'"
                )
            );
        
        $pic['pic'] = $dir.'thumb/'.$pic['picID'].'.jpg';
        if(!file_exists($pic['pic'])) $pic['pic'] = 'images/nopic.gif';
        $pic['name'] = $pic['name'];
        
        if ($pic['comment'] == '') {
            $comment = '<i>' . $plugin_language[ 'empty_comments' ] . '</i>';
        }else{
            $comment = $pic['comment'];
        }

        $pic['comments'] = mysqli_num_rows(safe_query("SELECT commentID FROM ".PREFIX."plugins_gallery_comments WHERE parentID='".$pic['picID']."' AND type='ga'"));
        $pic[ 'count' ] =
                        mysqli_num_rows(
                            safe_query(
                                "SELECT
                                    `picID`
                                FROM
                                    `" . PREFIX . "plugins_gallery_pictures`
                                WHERE
                                    `galleryID` = '" . (int)$pic[ 'galleryID' ] . "' ORDER BY `sort`"
                            )
                        ); 

        $picture=$ds['images_per_page'];
        echo'<style>@media (min-width:576px){
                .card-columns-gal{
                    -webkit-column-count:'.$picture.';
                    -moz-column-count:'.$picture.';
                    column-count:'.$picture.';
                    -webkit-column-gap:1.25rem;
                    -moz-column-gap:1.25rem;
                    column-gap:1.25rem;
                    orphans:1;widows:1;
                }
            }</style>';

        $data_array = array();
        $data_array['$diashow'] = $diashow;
        $data_array['$firstactive'] = $firstactive;
        $data_array['$picID'] = $pic['picID'];
        $data_array['$name'] = $pic[ 'name' ];
        $data_array['$comment'] = $comment;
        $data_array['$comments'] = $pic[ 'comments'];
        $data_array['$dir'] = $dir;
        $data_array['$views'] = $pic[ 'views' ];

        $data_array['$lang_views'] = $plugin_language['views'];
        $data_array['$lang_pic_name'] = $plugin_language['pic_name'];
        $data_array['$lang_comments'] = $plugin_language['comments'];
        $data_array['$lang_comment'] = $plugin_language['comment'];
		
		

        $template = $GLOBALS["_template"]->loadTemplate("gallery","showlist", $data_array, $plugin_path);
        echo $template;

        $i++;
    }

    $data_array = array();
    $data_array['$pagelink'] = $pagelink;
    
    $template = $GLOBALS["_template"]->loadTemplate("gallery","gallery_foot", $data_array, $plugin_path);
    echo $template;
} elseif (isset($_GET[ 'groupID' ])) {

$row = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery");

    $galclass = new \webspell\Gallery;

    $galleries =
        mysqli_num_rows(
            safe_query(
                "SELECT `galleryID` FROM `" . PREFIX . "plugins_gallery` WHERE `groupID` = '" . (int)$_GET[ 'groupID' ] . "'"
            )
        );
if ($galleries == '0') {

    $group = $galclass->getGroupName($_GET[ 'groupID' ]);
    if ($_GET[ 'groupID' ] == 0) {
        $group = $plugin_language[ 'usergalleries' ];
    }

    $data_array = array();
    $data_array['$group'] = $group;
    
    $data_array['$lang_gallery'] = $plugin_language['gallery'];
    $data_array['$lang_galleries'] = $plugin_language['galleries'];
    
    $template = $GLOBALS["_template"]->loadTemplate("gallery","user_group_head", $data_array, $plugin_path);
    echo $template;


        echo generateAlert($plugin_language['no_gallery_exists'], 'alert-info');
} else { 


    $_language->readModule('gallery');

    $galclass = new \webspell\Gallery;
############
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
        $db = mysqli_fetch_array($settings);
##########
    $galleries = mysqli_num_rows(safe_query("SELECT  `galleryID` FROM ".PREFIX."plugins_gallery WHERE groupID='" . (int)$_GET[ 'groupID' ] . "'"));
    $pages = ceil($galleries == $gallerygroups);

    if(!isset($_GET['page'])) {
        $page = 1;
    }else{
        $page = $_GET['page'];
    }

    if ($pages > 1) {
        $pagelink = makepagelink("index.php?site=gallery&amp;groupID=" . $_GET[ 'groupID' ], $page, $pages);
    } else {
        $pagelink = '';
    }

    if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery WHERE groupID='".$_GET['groupID']."' ORDER BY galleryID DESC LIMIT 0, ".$gallerygroups);
    }
    else {
        $start=$page*$gallerygroups-$gallerygroups;
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery WHERE groupID='".$_GET['groupID']."' ORDER BY galleryID DESC LIMIT ".$start.", ".$gallerygroups);
    }
    

    $group = $galclass->getGroupName($_GET[ 'groupID' ]);
    if ($_GET[ 'groupID' ] == 0) {
        $group = $plugin_language[ 'usergalleries' ];
    }

    $data_array = array();
    $data_array['$group'] = $group;
    $data_array['$galleries'] = $galleries;
    $data_array['$pages'] = $pages;
    $data_array['$pagelink'] = $pagelink;

    $data_array['$lang_gallery'] = $plugin_language['gallery'];
    $data_array['$lang_page_s'] = $plugin_language['page_s'];
    $data_array['$lang_galleries'] = $plugin_language['galleries'];
    $template = $GLOBALS["_template"]->loadTemplate("gallery","group_head", $data_array, $plugin_path);
    echo $template;

    $groups = safe_query(
                "SELECT
                    *
                FROM
                    `" . PREFIX . "plugins_gallery`
                WHERE
                    groupID = '" . $_GET[ 'groupID' ] . "'
                ORDER BY
                    galleryID DESC"
            );
            $anzgroups = mysqli_num_rows($groups);    

    $i = 1;

    while ($dx = mysqli_fetch_array($groups)) {
        $dir = './includes/plugins/gallery/images/';

        $dx[ 'pics' ] =
            mysqli_num_rows(
                            safe_query(
                                "SELECT
                                    `picID`
                                FROM
                                    `" . PREFIX . "plugins_gallery_pictures`
                                WHERE
                                    `galleryID` = '" . (int)$dx[ 'galleryID' ] . "'"
                            )
                        );
        $dx[ 'count' ] =
                        mysqli_num_rows(
                            safe_query(
                                "SELECT
                                    `picID`
                                FROM
                                    `" . PREFIX . "plugins_gallery_pictures`
                                WHERE
                                    `galleryID` = '" . (int)$dx[ 'galleryID' ] . "'"
                            )
                        );    
        $dx[ 'date' ] = getformatdatetime($dx[ 'date' ]);
        
        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
        $db = mysqli_fetch_array($settings);


        if ( $dx['count'] == 0 ) { 
             $picture = '<img src="./includes/plugins/gallery/icons/no-image.jpg">';
        }else{            
            $randomPic = $galclass->randomPic($dx[ 'galleryID' ]);
            $picture = '
            <a href="index.php?site=gallery&amp;picID='.$randomPic.'">
            <img class="img-fluid gallery" src="/includes/plugins/gallery/images/thumb/'.$randomPic.'.jpg" alt="Picture"></a>';
        }
                
        if ( $dx['count'] == 0 ) { 
            $gallery = '<strong>'.$dx[ 'name' ].'</strong>';            
        }else{
            $gallery = '<a href="index.php?site=gallery&amp;galleryID='.$dx['galleryID'].'"><strong>'.$dx[ 'name' ].'</strong></a>';
        }


        $data_array = array();
        $data_array['$date'] = $dx[ 'date' ];
        $data_array['$count'] = $dx[ 'count' ];
        $data_array['$picture'] = $picture;
        $data_array['$gallery'] = $gallery;

        $data_array['$lang_pictures'] = $plugin_language['pictures'];
        $data_array['$lang_date'] = $plugin_language['date'];
        $template = $GLOBALS["_template"]->loadTemplate("gallery","showlist_group", $data_array, $plugin_path);
        echo $template;
    }
    echo '<td>&nbsp;</td></tr>';

    if ($anzgroups % 2 != 0 && $i == $anzgroups) {
        echo '<div class="col-xs-3"></div>';
    }

    $data_array = array();
    $data_array['$pagelink'] = $pagelink;
    $template = $GLOBALS["_template"]->loadTemplate("gallery","group_foot", $data_array, $plugin_path);
    echo $template;

}


} else {
  
    $galclass = new \webspell\Gallery;

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
    $dx = mysqli_fetch_array($ergebnis);

    $da = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
    if (@$da[ 'modulname' ] != 'squads') {    
        $user_galleries = '';
    } else {
        if ($dx[ 'usergalleries' ] == '1') {
            $user_galleries = '<a class="btn btn-primary" style="margin-bottom: 5px" href="index.php?site=gallery&amp;groupID=0">'.$plugin_language['usergalleries'].'</a>';
        }else{
            $user_galleries = '';
        }
    }

    $data_array = array();
    $data_array['$usergalleries'] = $user_galleries;
    $data_array['$lang_gallery'] = $plugin_language['gallery'];

    $template = $GLOBALS["_template"]->loadTemplate("gallery","content_categorys_head", $data_array, $plugin_path);
    echo $template;


    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups ORDER BY sort");

    if (mysqli_num_rows($ergebnis)) {
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $groupID = $ds[ 'groupID' ];
            $title = $ds[ 'name' ];
            $gallerys = mysqli_num_rows(
                safe_query(
                    "SELECT
                        `galleryID`
                    FROM
                        `" . PREFIX . "plugins_gallery`
                    WHERE
                        `groupID` = '" . $ds[ 'groupID' ] . "'"
                )
            );
            $pics = mysqli_num_rows(safe_query("SELECT picID FROM ".PREFIX."plugins_gallery as gal, ".PREFIX."plugins_gallery_pictures as pic WHERE gal.groupID='".$ds['groupID']."' AND gal.galleryID=pic.galleryID"));


            $data_array = array();
            $data_array['$groupID'] = $groupID;
            $data_array['$title'] = $title;
            $data_array['$gallerys'] = $gallerys;
            $data_array['$pics'] = $pics;

            $data_array['$lang_category'] = $plugin_language['category'];
            $data_array['$lang_galleries'] = $plugin_language['galleries'];
            $data_array['$lang_pictures'] = $plugin_language['pictures'];
            $data_array['$lang_date'] = $plugin_language['date'];
            $data_array['$lang_name'] = $plugin_language['name'];            

            $template = $GLOBALS["_template"]->loadTemplate("gallery","content_categorys_head_head", $data_array, $plugin_path);
            echo $template;

            $groups = safe_query(
                "SELECT
                    *
                FROM
                    `" . PREFIX . "plugins_gallery`
                WHERE
                    groupID = '" . (int)$ds[ 'groupID' ] . "'
                ORDER BY
                    galleryID"
            );
            $anzgroups = mysqli_num_rows($groups);

            $i = 0;
            while ($ds = mysqli_fetch_array($groups)) {
                $i++;

                if (isset($ds[ 'date' ])) {
                    $ds[ 'date' ] = date('d.m.Y', $ds[ 'date' ]);
                }
                if (isset($ds[ 'galleryID' ])) {
                    $ds[ 'count' ] =
                        mysqli_num_rows(
                            safe_query(
                                "SELECT
                                    `picID`
                                FROM
                                    `" . PREFIX . "plugins_gallery_pictures`
                                WHERE
                                    `galleryID` = '" . (int)$ds[ 'galleryID' ] . "'"
                            )
                        );
                }

                if (isset($ds[ 'count' ])) {

                    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
                    $db = mysqli_fetch_array($settings);



                if ( $ds['count'] == 0 ) {    
                    $picture = '<img style="width: 120px" src="./includes/plugins/gallery/icons/no-image.jpg">';
                }else{
                    $randomPic = $galclass->randomPic($ds[ 'galleryID' ]);
                    $picture = '<a href="index.php?site=gallery&amp;picID='.$randomPic.'">
                    <img class="img-fluid gallery" src="/includes/plugins/gallery/images/thumb/'.$randomPic.'.jpg" alt="Thumbnail"></a>';
                }
                
                if ( $ds['count'] == 0 ) {    
                    $gallery = '<strong>'.$ds['name'].'</strong>';
                }else{
                    
                    $gallery = '<a href="index.php?site=gallery&amp;galleryID='.$ds['galleryID'].'"><strong>'.$ds['name'].'</strong></a>';
                }


                    $data_array = array();
                    $data_array['$count'] = $ds['count'];
                    $data_array['$date'] = $ds['date'];
                    $data_array['$picture'] = $picture;
                    $data_array['$gallery'] = $gallery;

                    $data_array['$lang_pictures'] = $plugin_language['pictures'];
                    $data_array['$lang_date'] = $plugin_language['date'];

                    $template = $GLOBALS["_template"]->loadTemplate("gallery","content_showlist", $data_array, $plugin_path);
                    echo $template;

                    // preventing to break Layout if number of groups is odd
                    if ($anzgroups % 2 != 0 && $i == $anzgroups) {
                        echo '<div class="col-xs-3"></div>';
                    }
                    $i++;
                } else {
                    echo '<p class="col-xs-6">' . $plugin_language[ 'no_gallery_exists' ] . '</p>';
                }
            }

            $template = $GLOBALS["_template"]->loadTemplate("gallery","content_categorys_foot", $data_array, $plugin_path);
            echo $template;
        }
    } else {
        echo generateAlert($plugin_language['no_gallery_exists'], 'alert-info');
    }
}
