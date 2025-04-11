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
\__________________________________________________________________*/
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("carousel", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='carousel'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}
 
$filepath = $plugin_path."images/";
$filepathvid = $plugin_path."videos/";
 
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

$ani_height_pic = '
<option value="100vh">100vh</option>
<option value="75vh">75vh</option>
<option value="50vh">50vh</option>
<option value="25vh">25vh</option>
';

$ani = '<optgroup label="Attention Seekers">
          <!--<option value="bounce">bounce</option>-->
          <option value="flash">flash</option>
          <!--<option value="pulse">pulse</option>-->
          <!--<option value="rubberBand">rubberBand</option>-->
          <!--<option value="shake">shake</option>-->
          <!--<option value="swing">swing</option>-->
          <!--<option value="tada">tada</option>-->
          <!--<option value="wobble">wobble</option>-->
          <!--<option value="jello">jello</option>-->
          <!--<option value="heartBeat">heartBeat</option>-->
        </optgroup>

        <optgroup label="Bouncing Entrances">
          <option value="bounceIn">bounceIn</option>
          <option value="bounceInDown">bounceInDown</option>
          <option value="bounceInLeft">bounceInLeft</option>
          <option value="bounceInRight">bounceInRight</option>
          <option value="bounceInUp">bounceInUp</option>
        </optgroup>

        <optgroup label="Bouncing Exits">
          <option value="bounceOut">bounceOut</option>
          <option value="bounceOutDown">bounceOutDown</option>
          <option value="bounceOutLeft">bounceOutLeft</option>
          <option value="bounceOutRight">bounceOutRight</option>
          <option value="bounceOutUp">bounceOutUp</option>
        </optgroup>

        <optgroup label="Fading Entrances">
          <option value="fadeIn">fadeIn</option>
          <option value="fadeInDown">fadeInDown</option>
          <option value="fadeInDownBig">fadeInDownBig</option>
          <option value="fadeInLeft">fadeInLeft</option>
          <option value="fadeInLeftBig">fadeInLeftBig</option>
          <option value="fadeInRight">fadeInRight</option>
          <option value="fadeInRightBig">fadeInRightBig</option>
          <option value="fadeInUp">fadeInUp</option>
          <option value="fadeInUpBig">fadeInUpBig</option>
        </optgroup>

        <optgroup label="Fading Exits">
          <option value="fadeOut">fadeOut</option>
          <option value="fadeOutDown">fadeOutDown</option>
          <option value="fadeOutDownBig">fadeOutDownBig</option>
          <option value="fadeOutLeft">fadeOutLeft</option>
          <option value="fadeOutLeftBig">fadeOutLeftBig</option>
          <option value="fadeOutRight">fadeOutRight</option>
          <option value="fadeOutRightBig">fadeOutRightBig</option>
          <option value="fadeOutUp">fadeOutUp</option>
          <option value="fadeOutUpBig">fadeOutUpBig</option>
        </optgroup>

        <optgroup label="Flippers">
          <!--<option value="flip">flip</option>-->
          <option value="flipInX">flipInX</option>
          <option value="flipInY">flipInY</option>
          <option value="flipOutX">flipOutX</option>
          <option value="flipOutY">flipOutY</option>
        </optgroup>

        <optgroup label="Lightspeed">
          <option value="lightSpeedIn">lightSpeedIn</option>
          <option value="lightSpeedOut">lightSpeedOut</option>
        </optgroup>

        <optgroup label="Rotating Entrances">
          <option value="rotateIn">rotateIn</option>
          <option value="rotateInDownLeft">rotateInDownLeft</option>
          <option value="rotateInDownRight">rotateInDownRight</option>
          <option value="rotateInUpLeft">rotateInUpLeft</option>
          <option value="rotateInUpRight">rotateInUpRight</option>
        </optgroup>

        <optgroup label="Rotating Exits">
          <option value="rotateOut">rotateOut</option>
          <option value="rotateOutDownLeft">rotateOutDownLeft</option>
          <option value="rotateOutDownRight">rotateOutDownRight</option>
          <option value="rotateOutUpLeft">rotateOutUpLeft</option>
          <option value="rotateOutUpRight">rotateOutUpRight</option>
        </optgroup>

        <optgroup label="Sliding Entrances">
          <option value="slideInUp">slideInUp</option>
          <option value="slideInDown">slideInDown</option>
          <option value="slideInLeft">slideInLeft</option>
          <option value="slideInRight">slideInRight</option>

        </optgroup>
        <!--<optgroup label="Sliding Exits">-->
          <!--<option value="slideOutUp">slideOutUp</option>-->
          <!--<option value="slideOutDown">slideOutDown</option>-->
          <!--<option value="slideOutLeft">slideOutLeft</option>-->
          <!--<option value="slideOutRight">slideOutRight</option>-->
          
        <!--</optgroup>-->
        
        <optgroup label="Zoom Entrances">
          <option value="zoomIn">zoomIn</option>
          <option value="zoomInDown">zoomInDown</option>
          <option value="zoomInLeft">zoomInLeft</option>
          <option value="zoomInRight">zoomInRight</option>
          <option value="zoomInUp">zoomInUp</option>
        </optgroup>
        
        <optgroup label="Zoom Exits">
          <!--<option value="zoomOut">zoomOut</option>-->
          <option value="zoomOutDown">zoomOutDown</option>
          <option value="zoomOutLeft">zoomOutLeft</option>
          <option value="zoomOutRight">zoomOutRight</option>
          <option value="zoomOutUp">zoomOutUp</option>
        </optgroup>

        <optgroup label="Specials">
          <option value="hinge">hinge</option>
          <option value="jackInTheBox">jackInTheBox</option>
          <option value="rollIn">rollIn</option>
          <!--<option value="rollOut">rollOut</option>-->
        </optgroup>';
 

if (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_carousel SET sort='$sorter[1]' WHERE carouselID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_carousel", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $title = $_POST[ 'title' ];
    $link = $_POST[ 'link' ];
    $description = $_POST[ 'description' ];
    $time_pic = $_POST[ 'time_pic' ];
    if (isset($_POST[ 'displayed' ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }
 
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_carousel` (title, link, time_pic, description, displayed, sort) values ('".$title."', '".$link."', '".$time_pic."', '".$description."', '".intval($displayed)."','1')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();

        if (isset($_FILES['carousel_pic'])) {
 
        $upload = new \webspell\HttpUpload('carousel_pic');
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
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel SET carousel_pic='" . $file . "' WHERE carouselID='" . $id . "'"
                            );
                        }
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
} else {
}
        
        if (isset($_FILES['carousel_vid'])) {

        $upload = new \webspell\HttpUpload('carousel_vid'); // Modifica del nome del campo per il caricamento del file video
        if ($upload->hasFile()) {
         if ($upload->hasError() === false) {
            $mime_types = array('video/mp4', 'video/mpeg'); // Aggiunta dei tipi MIME dei video MP4 e MPG
    
            if ($upload->supportedMimeType($mime_types)) {
                $file_extension = pathinfo($upload->getFileName(), PATHINFO_EXTENSION); // Ottieni l'estensione del file
    
                $file = $id . '.' . $file_extension; // Modifica del nome del file utilizzando l'estensione originale del video
    
                if ($upload->saveAs($filepathvid . $file, true)) {
                    @chmod($filepathvid . $file, $new_chmod); // Correzione del percorso del file per impostare i permessi
                    safe_query(
                        "UPDATE " . PREFIX . "plugins_carousel SET carousel_vid='" . $file . "' WHERE carouselID='" . $id . "'"
                    );
                }
            } else {
                $errors[] = $plugin_language['unsupported_video_type']; // Messaggio di errore per tipo di video non supportato
            }
        } else {
            $errors[] = $upload->translateError(); // Messaggio di errore generico sul caricamento del file
        }
    }
} else {
}
    
    if (count($errors)) {
        $errors = array_unique($errors);
        echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
    } else {
        redirect("admincenter.php?site=admin_carousel&action=admin_carousel_pic", "", 0);
    }

    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $title = $_POST[ "title" ];
    $ani_title = $_POST[ "ani_title" ];
    $ani_link = $_POST[ "ani_link" ];
    $ani_description = $_POST[ "ani_description" ];
    $link = $_POST[ "link" ];
    $description = $_POST[ "description" ];
    $time_pic = $_POST[ 'time_pic' ];
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        
        safe_query(
            "UPDATE " . PREFIX . "plugins_carousel SET title='" . $title . "', time_pic='" . $time_pic . "', ani_title='" . $ani_title . "', link='" . $link . "', ani_link='" . $ani_link . "', description='" . $description .
            "', ani_description='" . $ani_description . "', displayed='" . $displayed . "' WHERE carouselID='" .
            $_POST[ "carouselID" ] . "'"
        );
 
        $id = $_POST[ 'carouselID' ];
 
        $errors = array();
        
        if (isset($_FILES['carousel_pic'])) {
        
        $upload = new \webspell\HttpUpload('carousel_pic');
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
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel SET carousel_pic='" . $file . "' WHERE carouselID='" . $id . "'"
                            );
                        }
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
    } else {
}
        
        if (isset($_FILES['carousel_vid'])) {
        
        $upload = new \webspell\HttpUpload('carousel_vid'); // Modifica del nome del campo per il caricamento del file video
       if ($upload->hasFile()) {
        if ($upload->hasError() === false) {
            $mime_types = array('video/mp4', 'video/mpeg'); // Aggiunta dei tipi MIME dei video MP4 e MPG
    
            if ($upload->supportedMimeType($mime_types)) {
                $file_extension = pathinfo($upload->getFileName(), PATHINFO_EXTENSION); // Ottieni l'estensione del file
    
                $file = $id . '.' . $file_extension; // Modifica del nome del file utilizzando l'estensione originale del video
    
                if ($upload->saveAs($filepathvid . $file, true)) {
                    @chmod($filepathvid . $file, $new_chmod); // Correzione del percorso del file per impostare i permessi
                    safe_query(
                        "UPDATE " . PREFIX . "plugins_carousel SET carousel_vid='" . $file . "' WHERE carouselID='" . $id . "'"
                    );
                }
            } else {
                $errors[] = $plugin_language['unsupported_video_type']; // Messaggio di errore per tipo di video non supportato
            }
        } else {
            $errors[] = $upload->translateError(); // Messaggio di errore generico sul caricamento del file
        }
    }
  } else {
}
    if (count($errors)) {
        $errors = array_unique($errors);
        echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
    } else {
        redirect("admincenter.php?site=admin_carousel&action=admin_carousel_pic", "", 0);
    }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif (isset($_POST[ 'carousel_settings_save' ])) {  

   
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_carousel_settings
            SET
                
                carousel_height='" . $_POST[ 'carousel_height' ] . "',
                parallax_height='" . $_POST[ 'parallax_height' ] . "',
                sticky_height='" . $_POST[ 'sticky_height' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_carousel&action=admin_carousel_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_carousel&action=admin_carousel_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }

} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel WHERE carouselID='" . $_GET[ "carouselID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_carousel WHERE carouselID='" . $_GET[ "carouselID" ] . "'")) {
            @unlink($filepath.$data['carousel_pic']);
            @unlink($filepathvid.$data['carousel_vid']);
            redirect("admincenter.php?site=admin_carousel&action=admin_carousel_pic", "", 0);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_carousel_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif (isset($_GET[ "delete_parallax" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_parallax WHERE parallaxID='" . $_GET[ "parallaxID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_carousel_parallax WHERE parallaxID='" . $_GET[ "parallaxID" ] . "'")) {
            @unlink($filepath.$data['parallax_pic']);
            redirect("admincenter.php?site=admin_carousel&action=admin_parallax_pic", "", 0);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_parallax_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_parallax_pic", "", 0);
    }
} elseif (isset($_POST[ "parallax_save" ])) {
 
    #$height = $_POST[ 'height' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_carousel_parallax` (text) values ('parallax')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('parallax_pic');
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
                        $file = 'parallax'.$endung;

                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel_parallax SET parallax_pic='" . $file . "' WHERE parallaxID='" . $id . "'"
                            );
                        }
 
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_parallax_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_parallax_pic", "", 0);
    }
} elseif (isset($_POST[ "saveedit_parallax" ])) {
    #$height = $_POST[ "height" ];
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
 
        #safe_query(
        #    "UPDATE " . PREFIX . "plugins_parallax SET height='" . $height . "' WHERE headerID='" . $_POST[ "headerID" ] . "'");
 
        $id = $_POST[ 'parallaxID' ];
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('parallax_pic');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
 
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation = getimagesize($upload->getTempFile());
 
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
                        $file = 'parallax'.$endung;
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel_parallax SET parallax_pic='" . $file . "' WHERE parallaxID='" . $id . "'"
                            );
                        }
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_parallax_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_parallax_pic", "", 0);
    }

} elseif (isset($_GET[ "delete_sticky" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_sticky WHERE stickyID='" . $_GET[ "stickyID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_carousel_sticky WHERE stickyID='" . $_GET[ "stickyID" ] . "'")) {
            @unlink($filepath.$data['sticky_pic']);
            redirect("admincenter.php?site=admin_carousel&action=admin_sticky_pic", "", 0);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_sticky_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_sticky_pic", "", 0);
    }
} elseif (isset($_POST[ "sticky_save" ])) {
 
    $title = $_POST[ "title" ];
    $link = $_POST[ "link" ];
    $description = $_POST[ "description" ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_carousel_sticky` (title, description, link) values ('".$title."', '".$description."', '".$link."')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('sticky_pic');
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
                        $file = 'sticky'.$endung;

                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel_sticky SET sticky_pic='" . $file . "' WHERE stickyID='" . $id . "'"
                            );
                        }
 
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_sticky_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_sticky_pic", "", 0);
    }
} elseif (isset($_POST[ "saveedit_sticky" ])) {
    $title = $_POST[ "title" ];
    $link = $_POST[ "link" ];
    $description = $_POST[ "description" ];
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
 
        safe_query(
            "UPDATE " . PREFIX . "plugins_carousel_sticky SET title='" . $title . "', description='" . $description . "', link='" . $link . "' WHERE stickyID='" . $_POST[ "stickyID" ] . "'");
 
        $id = $_POST[ 'stickyID' ];
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('sticky_pic');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
 
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation = getimagesize($upload->getTempFile());
 
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
                        $file = 'sticky'.$endung;
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel_sticky SET sticky_pic='" . $file . "' WHERE stickyID='" . $id . "'"
                            );
                        }
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_sticky_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_sticky_pic", "", 0);
    }    
#}


##########################

} elseif (isset($_GET[ "delete_agency" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_agency WHERE agencyID='" . $_GET[ "agencyID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_carousel_agency WHERE agencyID='" . $_GET[ "agencyID" ] . "'")) {
            @unlink($filepath.$data['agency_pic']);
            redirect("admincenter.php?site=admin_carousel&action=admin_agency_pic", "", 0);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_agency_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_agency_pic", "", 0);
    }
} elseif (isset($_POST[ "agency_save" ])) {
 
    $title = $_POST[ "title" ];
    $link = $_POST[ "link" ];
    $description = $_POST[ "description" ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_carousel_agency` (title, description, link) values ('".$title."', '".$description."', '".$link."')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('agency_pic');
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
                        $file = 'agency'.$endung;

                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel_agency SET agency_pic='" . $file . "' WHERE agencyID='" . $id . "'"
                            );
                        }
 
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_agency_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_agency_pic", "", 0);
    }
} elseif (isset($_POST[ "saveedit_agency" ])) {
    $title = $_POST[ "title" ];
    $link = $_POST[ "link" ];
    $description = $_POST[ "description" ];
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
 
        safe_query(
            "UPDATE " . PREFIX . "plugins_carousel_agency SET title='" . $title . "', description='" . $description . "', link='" . $link . "' WHERE agencyID='" . $_POST[ "agencyID" ] . "'");
 
        $id = $_POST[ 'agencyID' ];
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('agency_pic');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
 
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation = getimagesize($upload->getTempFile());
 
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
                        $file = 'agency'.$endung;
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_carousel_agency SET agency_pic='" . $file . "' WHERE agencyID='" . $id . "'"
                            );
                        }
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_carousel&action=admin_agency_pic", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_carousel&action=admin_agency_pic", "", 0);
    }    
}

if ($action == "add_vid") {

  $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_carousel"
        )
    );

    @$ani_title = str_replace('value="' . $ds['ani_title'] . '"', 'value="' . $ds['ani_title'] . '" selected="selected"', $ani); 
    @$ani_link = str_replace('value="' . $ds['ani_link'] . '"', 'value="' . $ds['ani_link'] . '" selected="selected"', $ani);  
    @$ani_description = str_replace('value="' . $ds['ani_description'] . '"', 'value="' . $ds['ani_description'] . '" selected="selected"', $ani);
    echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_carousel_pic">' . $plugin_language[ 'carousel' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_carousel_vid' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_carousel_pic" enctype="multipart/form-data">

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_vid'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="btn btn-info" name="carousel_vid" type="file" size="40" /> <small>(' . $plugin_language[ 'carousel_upload_info_vid' ] . ')</small></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
    </div>
  </div>
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="mceNoEditor form-control" id="description" rows="5" cols="" name="description" style="width: 100%;"></textarea></em></span>
    </div>
  </div>

   <div class="mb-3 row">
        <label for="ani_title" class="col-lg-2 control-label">'.$plugin_language['title-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_title" name="ani_title" class="form-select">'.$ani_title.'</select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="ani_link" class="col-lg-2 control-label">'.$plugin_language['link-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_link" name="ani_link" class="form-select">'.$ani_link.'</select>
        </div>
    </div>


    <div class="mb-3 row">
        <label for="ani_description" class="col-lg-2 control-label">'.$plugin_language['description-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_description" name="ani_description" class="form-select">'.$ani_description.'</select>
        </div>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
  <label class="col-sm-2 control-label">'.$plugin_language['carousel_time'].':</label>
    <div class="col-lg-3"><span class="text-muted small"><em>
      <input class="form-control" type="number" name="time_pic" size="20" maxlength="25" value="5"/></em></span>
    </div>
    <label class="col-sm-2 control-label">'.$plugin_language['time_info'].':</label>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_carousel'].'</button>
    </div>
  </div>
</form>
</div></div>';
} elseif ($action == "add_pic") {


  $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_carousel"
        )
    );

    @$ani_title = str_replace('value="' . $ds['ani_title'] . '"', 'value="' . $ds['ani_title'] . '" selected="selected"', $ani); 
    @$ani_link = str_replace('value="' . $ds['ani_link'] . '"', 'value="' . $ds['ani_link'] . '" selected="selected"', $ani);  
    @$ani_description = str_replace('value="' . $ds['ani_description'] . '"', 'value="' . $ds['ani_description'] . '" selected="selected"', $ani);
    echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_carousel_pic">' . $plugin_language[ 'carousel' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_carousel_pic' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_carousel_pic" enctype="multipart/form-data">
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_pic'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="btn btn-info" name="carousel_pic" type="file" size="40" /> <small>(' . $plugin_language[ 'carousel_upload_info' ] . ')</small></em></span>
    </div>
  </div>
  
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
    </div>
  </div>
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="mceNoEditor form-control" id="description" rows="5" cols="" name="description" style="width: 100%;"></textarea></em></span>
    </div>
  </div>
   <div class="mb-3 row">
        <label for="ani_title" class="col-lg-2 control-label">'.$plugin_language['title-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_title" name="ani_title" class="form-select">'.$ani_title.'</select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="ani_link" class="col-lg-2 control-label">'.$plugin_language['link-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_link" name="ani_link" class="form-select">'.$ani_link.'</select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="ani_description" class="col-lg-2 control-label">'.$plugin_language['description-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_description" name="ani_description" class="form-select">'.$ani_description.'</select>
        </div>
    </div>
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
  <label class="col-sm-2 control-label">'.$plugin_language['carousel_time'].':</label>
    <div class="col-lg-3"><span class="text-muted small"><em>
      <input class="form-control" type="number" name="time_pic" size="20" maxlength="25" value="5"/></em></span>
    </div>
    <label class="col-sm-2 control-label">'.$plugin_language['time_info'].':</label>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_carousel'].'</button>
    </div>
  </div>
</form>
</div></div>';
} elseif ($action == "edit") {
    echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_carousel_pic">' . $plugin_language[ 'carousel' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_breadcrumb' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_carousel WHERE carouselID='" . intval($_GET['carouselID']) ."'"
        )
    );

    
    if (!empty($ds[ 'carousel_pic' ])) {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepath . $ds[ 'carousel_pic' ] . '" alt="">';
        $pic_current = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['current_pic'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
    </div>
  </div>';
    
    $pic_upload = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_upload_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="carousel_pic" type="file" size="40" /></em></span>
    </div>
  </div>';
    
    } else {
        $pic = $plugin_language[ 'no_upload' ];
        $pic_current = '';
        $pic_upload = '';
    }
    if (!empty($ds['carousel_vid']) || !empty($ds['carousel_pic'])) {
    if (!empty($ds[ 'carousel_vid' ])) {
        $vid = '<video autoplay="autoplay" loop muted playsInline class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepathvid . $ds[ 'carousel_vid' ] . '" type="video/mp4"></video>';
        $vid_current = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['current_vid'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$vid.'</em></span>
    </div>
  </div>';
       $vid_upload = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_upload_info_vid'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="carousel_vid" type="file" size="40" /></em></span>
    </div>
  </div>';
    } else {
        $vid = $plugin_language[ 'no_upload_vid' ];
        $vid_current = '';
        $vid_upload = '';
    }
    } else {
    $vid = '<video autoplay="autoplay" loop muted playsInline class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepathvid . $ds[ 'carousel_vid' ] . '" type="video/mp4"></video>';
        $vid_current = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['current_vid'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$vid.'</em></span>
    </div>
  </div>';
       $vid_upload = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_upload_info_vid'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="carousel_vid" type="file" size="40" /></em></span>
    </div>
  </div>';
  $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepath . $ds[ 'carousel_pic' ] . '" alt="">';
        $pic_current = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['current_pic'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
    </div>
  </div>';
    
    $pic_upload = '<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_upload_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="carousel_pic" type="file" size="40" /></em></span>
    </div>
  </div>';
    }
 
    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }
    
    $ani_title = str_replace('value="' . $ds['ani_title'] . '"', 'value="' . $ds['ani_title'] . '" selected="selected"', $ani); 
    $ani_link = str_replace('value="' . $ds['ani_link'] . '"', 'value="' . $ds['ani_link'] . '" selected="selected"', $ani);  
    $ani_description = str_replace('value="' . $ds['ani_description'] . '"', 'value="' . $ds['ani_description'] . '" selected="selected"', $ani);     
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_carousel_pic" enctype="multipart/form-data">
<input type="hidden" name="carouselID" value="' . $ds['carouselID'] . '" />
'.$pic_current.'
'.$vid_current.'
'.$pic_upload.'
'.$vid_upload.'
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></em></span>
    </div>    
  </div> 
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60" value="' . getinput($ds[ 'link' ]) . '" /></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="mceNoEditor form-control" id="description" rows="5" cols="" name="description" style="width: 100%;">' . getinput($ds[ 'description' ]) .
        '</textarea></em></span>
    </div>
  </div>
    <div class="mb-3 row">
        <label for="ani_title" class="col-lg-2 control-label">'.$plugin_language['title-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_title" name="ani_title" class="form-select">'.$ani_title.'</select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="ani_link" class="col-lg-2 control-label">'.$plugin_language['link-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_link" name="ani_link" class="form-select">'.$ani_link.'</select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="ani_description" class="col-lg-2 control-label">'.$plugin_language['description-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_description" name="ani_description" class="form-select">'.$ani_description.'</select>
        </div>
    </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      '.$displayed.'
    </div>
  </div>  
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_time'].':</label>
    <div class="col-lg-3"><span class="text-muted small"><em>
      <input class="form-control" type="number" name="time_pic" size="60" value="' . getinput($ds[ 'time_pic' ]) . '" /></em></span>
    </div>
        <label class="col-sm-2 control-label">'.$plugin_language['time_info'].':</label>
  </div>   
<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_carousel'].'</button>
    </div>
  </div>
</form>
</div></div>'; 

} elseif ($action == "add_parallax") {

    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>    
        <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_parallax_pic">' . $plugin_language[ 'parallax' ] . '</a></li>
        <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
    </ol>
    </nav>
    <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_parallax_pic" enctype="multipart/form-data">
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="btn btn-info" name="parallax_pic" type="file" size="40" /> <small>(' . $plugin_language[ 'header_upload_info' ] . ')</small></em></span>
    </div>
  </div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="parallax_save"  />'.$plugin_language['new_header'].'</button>
    </div>
  </div>
</div>
</form>
</div></div>';
} elseif ($action == "edit_parallax") {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_parallax_pic">' . $plugin_language[ 'parallax' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
  </ol>
</nav>
<div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_carousel_parallax WHERE parallaxID='" . intval($_GET['parallaxID']) ."'"
        )
    );
    if (!empty($ds[ 'parallax_pic' ])) {
        $pic = '<img class="img-thumbnail" src="../' . $filepath . $ds[ 'parallax_pic' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }
    
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_parallax_pic" enctype="multipart/form-data">
<input type="hidden" name="parallaxID" value="' . $ds['parallaxID'] . '" />
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header_upload_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="parallax_pic" type="file" size="40" /></em></span>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-warning" type="submit" name="saveedit_parallax"  />'.$plugin_language['edit'].'</button>
    </div>
  </div>
</form>
</div></div>';


} elseif ($action == "add_sticky") {

    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>    
        <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_sticky_pic">' . $plugin_language[ 'sticky' ] . '</a></li>
        <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
    </ol>
    </nav>
    <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_sticky_pic" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="btn btn-info" name="sticky_pic" type="file" size="40" /> <small>(' . $plugin_language[ 'header_upload_info' ] . ')</small></em></span>
        </div>
    </div>
    
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
        </div>
    </div>
    
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <textarea class="mceNoEditor form-control" id="description" rows="5" cols="" name="description" style="width: 100%;"></textarea></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="link" size="60" maxlength="255" /></em></span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
            <button class="btn btn-success" type="submit" name="sticky_save"  />'.$plugin_language['new_header'].'</button>
        </div>
    </div>

</div>
</form>
</div></div>';
} elseif ($action == "edit_sticky") {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_sticky_pic">' . $plugin_language[ 'sticky' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
  </ol>
</nav>
<div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_carousel_sticky WHERE stickyID='" . intval($_GET['stickyID']) ."'"
        )
    );
    if (!empty($ds[ 'sticky_pic' ])) {
        $pic = '<img class="img-thumbnail" src="../' . $filepath . $ds[ 'sticky_pic' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_sticky_pic" enctype="multipart/form-data">
<input type="hidden" name="stickyID" value="' . $ds['stickyID'] . '" />
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header_upload_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="sticky_pic" type="file" size="40" /></em></span>
    </div>
</div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></em></span>
    </div>    
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="mceNoEditor form-control" id="description" rows="5" cols="" name="description" style="width: 100%;">' . getinput($ds[ 'description' ]) .
        '</textarea></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60" value="' . getinput($ds[ 'link' ]) . '" /></em></span>
    </div>
  </div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-warning" type="submit" name="saveedit_sticky"  />'.$plugin_language['edit'].'</button>
    </div>
  </div>
</form>
</div></div>';


#} 

######################################################

} elseif ($action == "add_agency") {

    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>    
        <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_agency_pic">' . $plugin_language[ 'agency' ] . '</a></li>
        <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
    </ol>
    </nav>
    <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_agency_pic" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="btn btn-info" name="agency_pic" type="file" size="40" /> <small>(' . $plugin_language[ 'header_upload_info' ] . ')</small></em></span>
        </div>
    </div>
    
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
        </div>
    </div>
    
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <textarea class="mceNoEditor form-control" id="description" rows="5" cols="" name="description" style="width: 100%;"></textarea></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="link" size="60" maxlength="255" /></em></span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
            <button class="btn btn-success" type="submit" name="agency_save"  />'.$plugin_language['new_header'].'</button>
        </div>
    </div>

</div>
</form>
</div></div>';
} elseif ($action == "edit_agency") {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_agency_pic">' . $plugin_language[ 'agency' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
  </ol>
</nav>
<div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_carousel_agency WHERE agencyID='" . intval($_GET['agencyID']) ."'"
        )
    );
    if (!empty($ds[ 'agency_pic' ])) {
        $pic = '<img class="img-thumbnail" src="../' . $filepath . $ds[ 'agency_pic' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_carousel&action=admin_agency_pic" enctype="multipart/form-data">
<input type="hidden" name="agencyID" value="' . $ds['agencyID'] . '" />
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header_upload_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="agency_pic" type="file" size="40" /></em></span>
    </div>
</div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></em></span>
    </div>    
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="mceNoEditor form-control" id="description" rows="5" cols="" name="description" style="width: 100%;">' . getinput($ds[ 'description' ]) .
        '</textarea></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60" value="' . getinput($ds[ 'link' ]) . '" /></em></span>
    </div>
  </div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-warning" type="submit" name="saveedit_agency"  />'.$plugin_language['edit'].'</button>
    </div>
  </div>
</form>
</div></div>';


} 
#carousel_parallax =================================================

elseif ($action == "admin_parallax_pic") {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'parallax' ] . '</li>
  </ol>
</nav>
<div class="card-body">

<div class="mb-3 row">';

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_parallax");
  $ds = mysqli_fetch_array($ergebnis);

if (isset($ds['parallaxID']) ? isset($ds['parallaxID']) : 0) {  
    $add = "";
} else {
    $add = '<label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8"><a href="admincenter.php?site=admin_carousel&amp;action=add_parallax" class="btn btn-primary" type="button">' . $plugin_language[ 'new_header' ] . '</a></div>';
}
      echo' '.$add.'
    
  </div>';
 
    echo '<form method="post" action="admincenter.php?site=admin_carousel&action=admin_parallax_pic">
    <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['header'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_parallax");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($dx = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
           
            echo '<tr>
           <td class="' . $td . ' col-5"><img class="img-thumbnail" align="center" src="../' . $filepath . $dx[ 'parallax_pic' ] . '" alt="{img}" /></td>
           
           <td class="' . $td . ' col-2"><a href="admincenter.php?site=admin_carousel&amp;action=edit_parallax&amp;parallaxID=' . $dx[ 'parallaxID' ] .
                '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_carousel&amp;delete_parallax=true&amp;parallaxID=' . $ds[ 'parallaxID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'title' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->



      </td>
</tr>';
            $i++;
        }
    
 
    echo '
</table>
</form></div></div>';


}




#carousel_Sticky =================================================

}elseif ($action == "admin_sticky_pic") {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'sticky' ] . '</li>
  </ol>
</nav>
<div class="card-body">

<div class="mb-3 row">';

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_sticky");
  $ds = mysqli_fetch_array($ergebnis);

if (isset($ds['stickyID']) ? isset($ds['stickyID']) : 0) {  
    $add = "";
} else {
    $add = '<label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8"><a href="admincenter.php?site=admin_carousel&amp;action=add_sticky" class="btn btn-primary" type="button">' . $plugin_language[ 'new_header' ] . '</a></div>';
}
      echo' '.$add.'
    
  </div>';
 
    echo '<form method="post" action="admincenter.php?site=admin_carousel&action=admin_sticky_pic">
    <div class="table-responsive">
    <table class="table table-striped">
    <thead>
        <th><b>'.$plugin_language['name'].'</b></th>
        <th><b>'.$plugin_language['header'].'</b></th>
        <th><b>'.$plugin_language['actions'].'</b></th>
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_sticky");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($dx = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
           
            $title = $dx[ 'title' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
            
            echo '<tr>
           <td class="' . $td . '">' . $title . '</td>
           <td class="' . $td . ' col-5"><img class="img-thumbnail" align="center" src="../' . $filepath . $dx[ 'sticky_pic' ] . '" alt="{img}" /></td>
           
           <td class="' . $td . ' col-2"><a href="admincenter.php?site=admin_carousel&amp;action=edit_sticky&amp;stickyID=' . $dx[ 'stickyID' ] .
                '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_carousel&amp;delete_sticky=true&amp;stickyID=' . $dx[ 'stickyID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'title' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->



      </td>
</tr>';
            $i++;
        }
    
 
    echo '
</table></div>
</form></div></div>';


}




}elseif ($action == "admin_agency_pic") {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'agency' ] . '</li>
  </ol>
</nav>
<div class="card-body">

<div class="mb-3 row">';

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_agency");
  $ds = mysqli_fetch_array($ergebnis);

if (isset($ds['agencyID']) ? isset($ds['agencyID']) : 0) {  
    $add = "";
} else {
    $add = '<label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8"><a href="admincenter.php?site=admin_carousel&amp;action=add_agency" class="btn btn-primary" type="button">' . $plugin_language[ 'new_header' ] . '</a></div>';
}
      echo' '.$add.'
    
  </div>';
 
    echo '<form method="post" action="admincenter.php?site=admin_carousel&action=admin_agency_pic">
    <div class="table-responsive">
    <table class="table table-striped">
    <thead>
        <th><b>'.$plugin_language['name'].'</b></th>
        <th><b>'.$plugin_language['header'].'</b></th>
        <th><b>'.$plugin_language['actions'].'</b></th>
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_agency");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($dx = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
           
            $title = $dx[ 'title' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
            
            echo '<tr>
           <td class="' . $td . '">' . $title . '</td>
           <td class="' . $td . ' col-5"><img class="img-thumbnail" align="center" src="../' . $filepath . $dx[ 'agency_pic' ] . '" alt="{img}" /></td>
           
           <td class="' . $td . ' col-2"><a href="admincenter.php?site=admin_carousel&amp;action=edit_agency&amp;agencyID=' . $dx[ 'agencyID' ] .
                '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_carousel&amp;delete_agency=true&amp;agencyID=' . $dx[ 'agencyID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'title' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->



      </td>
</tr>';
            $i++;
        }
    
 
    echo '
</table></div>
</form></div></div>';


}






} elseif ($action == "admin_carousel_settings") {

 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_settings");
    $ds = mysqli_fetch_array($settings);

    $carousel_height = $ds[ 'carousel_height' ];
    $ani_carousel_height = str_replace('value="' . $ds['carousel_height'] . '"', 'value="' . $ds['carousel_height'] . '" selected="selected"', $ani_height_pic);

    $parallax_height = $ds[ 'parallax_height' ];
    $ani_parallax_height = str_replace('value="' . $ds['parallax_height'] . '"', 'value="' . $ds['parallax_height'] . '" selected="selected"', $ani_height_pic);

    $sticky_height = $ds[ 'sticky_height' ];
    $ani_sticky_height = str_replace('value="' . $ds['sticky_height'] . '"', 'value="' . $ds['sticky_height'] . '" selected="selected"', $ani_height_pic);

    $agency_height = $ds[ 'agency_height' ];
    $ani_agency_height = str_replace('value="' . $ds['agency_height'] . '"', 'value="' . $ds['agency_height'] . '" selected="selected"', $ani_height_pic);


    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'    <form method="post" action="admincenter.php?site=admin_carousel&action=admin_carousel_settings">
        <div class="card">
            <div class="card-header">
                '.$plugin_language[ 'settings' ].'
            </div> 

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel&action=admin_carousel_settings">' . $plugin_language[ 'settings' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
            <div class="card-body row">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label class="col-md-4 control-label">'.$plugin_language['carousel_size'].':</label>
                            <div class="col-md-6">
                                <select id="ani_carousel_height" name="carousel_height" class="form-select" value="'.$carousel_height.'">'.$ani_carousel_height.'</select>
                                <span class="text-muted small"><em><small>' . $plugin_language[ 'size_info' ] . '</small></em></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label class="col-md-4 control-label">'.$plugin_language['parallax_size'].':</label>
                            <div class="col-md-6">
                                <select id="ani_height" name="parallax_height" class="form-select" value="'.$parallax_height.'">'.$ani_parallax_height.'</select>
                                <span class="text-muted small"><em><small>' . $plugin_language[ 'size_info' ] . '</small></em></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                       <div class="mb-3 row">
                            <label class="col-md-4 control-label">'.$plugin_language['sticky_size'].':</label>
                            <div class="col-md-6">
                                <select id="ani_height" name="sticky_height" class="form-select" value="'.$sticky_height.'">'.$ani_sticky_height.'</select>
                                <span class="text-muted small"><em><small>' . $plugin_language[ 'size_info' ] . '</small></em></span>
                            </div>
                        </div> 
                    </div>

                    <div class="col-md-6">
                       <div class="mb-3 row">
                            <label class="col-md-4 control-label">'.$plugin_language['agency_size'].':</label>
                            <div class="col-md-6">
                                <select id="ani_height" name="sagency_height" class="form-select" value="'.$agency_height.'">'.$ani_agency_height.'</select>
                                <span class="text-muted small"><em><small>' . $plugin_language[ 'size_info' ] . '</small></em></span>
                            </div>
                        </div> 
                    </div>
               </div>
                <br>
                <div class="mb-3 row">
                    <div class="col-md-offset-2 col-md-10">
                        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                        <button class="btn btn-primary" type="submit" name="carousel_settings_save">'.$plugin_language['edit'].'</button>
                    </div>
                </div>
            </div>
        </div>
        
    </form>';

} elseif ($action == "admin_carousel_pic") {    
    echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'carousel' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">

<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_carousel&amp;action=add_pic" class="btn btn-primary" type="button">' . $plugin_language[ 'new_carousel_pic' ] . '</a>
      <a href="admincenter.php?site=admin_carousel&amp;action=add_vid" class="btn btn-primary" type="button">' . $plugin_language[ 'new_carousel_vid' ] . '</a>
    </div>
  </div>';
 
    echo '<form method="post" action="admincenter.php?site=admin_carousel">
    <div class="table-responsive">
    <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['name'].'</b></th>
      <th><b>'.$plugin_language['carousel'].'</b></th>
      <th><b>'.$plugin_language['is_displayed'].'</b></th>
      <th><b><i class="bi bi-stopwatch"></i> Sec.</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel ORDER BY sort");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
 
            $ds[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';
           
            $title = $ds[ 'title' ];
            $time_pic = $ds[ 'time_pic' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);

            if (!empty($ds[ 'carousel_pic' ])) {
                 $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 350px" align="center" src="../' . $filepath . $ds[ 'carousel_pic' ] . '"/></td>';
               } else {
                 $pic = '<video autoplay="autoplay" class="img-thumbnail" style="width: 100%; max-width: 350px" loop muted playsInline src="../' . $filepathvid . $ds[ 'carousel_vid' ] . '" type="video/mp4"></video></td>';
            }
            
            echo '<tr>
           <td class="' . $td . '">' . $title . '</td>
           <td class="' . $td . '">' . $pic . '
           <td class="' . $td . '">' . $displayed . '</td>
           <td class="' . $td . '">' . $time_pic . '</td>
           <td class="' . $td . '"><a href="admincenter.php?site=admin_carousel&amp;action=edit&amp;carouselID=' . $ds[ 'carouselID' ] .
                '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

            <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_carousel&amp;delete=true&amp;carouselID='.$ds['carouselID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'carousel' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->
                    
</td>
<td class="' . $td . '" align="center"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'carouselID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'carouselID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
        
    } else {
        echo '<tr><td class="td1" colspan="6">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }
 
    echo '<tr>
<td class="td_head" colspan="6" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><input class="btn btn-primary" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</table></div>
</form></div></div>';

} else {   
    echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_carousel">' . $plugin_language[ 'carousel_overview' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav> 
        <div class="card-body">';

echo'<div class="row text-center">
    <div class="col-sm-4">
        <div class="card">
          <div class="card-header"><i class="bi bi-film"></i> 
            ' . $plugin_language[ 'carousel' ] . ' ' . $plugin_language[ 'header' ] . '
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">' . $plugin_language[ 'carousel' ] . ' <small class="text-muted fw-light">' . $plugin_language[ 'header' ] . '</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Du möchtest eine Slidershow einsetzten?</li>
              <li>Hiermit kannst du extra Bilder und Videos hochladen</li>
              <li>und in einem Widgets dargestellen.</li>
              <li>Position auswählen.</li>
              <li>&nbsp;</li>
            </ul>
            <a class="w-100 btn btn-primary" href="admincenter.php?site=admin_carousel&amp;action=admin_carousel_pic" role="button">' . $plugin_language[ 'get_started' ] . '</a>

          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-header"><i class="bi bi-transparency"></i> 
            ' . $plugin_language[ 'parallax' ] . '  ' . $plugin_language[ 'header' ] . '
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">Parallax <small class="text-muted fw-light">' . $plugin_language[ 'header' ] . '</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Du möchtest einen Parallax Scrolling Effekt?</li>
              <li>Hiermit kannst du ein extra Bild (mit Parallaxeffekt) hochladen</li>
              <li>und in einem Widgets dargestellen.</li>
              <li>Position auswählen.</li>
              <li>&nbsp;</li>
            </ul>
            <a class="w-100 btn btn-primary" href="admincenter.php?site=admin_carousel&amp;action=admin_parallax_pic" role="button">' . $plugin_language[ 'get_started' ] . '</a>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-header"><i class="bi bi-sticky"></i> 
            ' . $plugin_language[ 'sticky' ] . ' ' . $plugin_language[ 'header' ] . '
          </div>
          <div class="card-body">

          <h1 class="card-title pricing-card-title">' . $plugin_language[ 'sticky' ] . ' <small class="text-muted fw-light">' . $plugin_language[ 'header' ] . '</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              ' . $plugin_language[ 'sticky_headers_info' ] . '
            </ul>            
            <a class="w-100 btn btn-primary" href="admincenter.php?site=admin_carousel&amp;action=admin_sticky_pic" role="button">' . $plugin_language[ 'get_started' ] . '</a>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-header"><i class="bi bi-sticky"></i> 
            ' . $plugin_language[ 'agency' ] . ' ' . $plugin_language[ 'header' ] . '
          </div>
          <div class="card-body">

          <h1 class="card-title pricing-card-title">' . $plugin_language[ 'agency' ] . ' <small class="text-muted fw-light">' . $plugin_language[ 'header' ] . '</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              ' . $plugin_language[ 'agency_headers_info' ] . '
            </ul>            
            <a class="w-100 btn btn-primary" href="admincenter.php?site=admin_carousel&amp;action=admin_agency_pic" role="button">' . $plugin_language[ 'get_started' ] . '</a>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-header"><i class="bi bi-gear"></i> 
            ' . $plugin_language[ 'settings' ] . ' ' . $plugin_language[ 'header' ] . '
          </div>
          <div class="card-body">

          <h1 class="card-title pricing-card-title">' . $plugin_language[ 'header' ] . ' <small class="text-muted fw-light">' . $plugin_language[ 'options' ] . '</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Wie hoch soll der Header angezeigt werden</li>
              <li>(Angaben in vh! Beispiel: 25vh, 50vh, 75vh, 100vh..)</li>
              <li>Carousel Header einstellbar</li>
              <li>Parallax Header einstellbar</li>
              <li>Sticky Header einstellbar</li>
              <li>Agency Header einstellbar</li>
            </ul>     
            <a class="w-100 btn btn-primary" href="admincenter.php?site=admin_carousel&action=admin_carousel_settings" role="button">' . $plugin_language[ 'get_started' ] . '</a>
          </div>
        </div>
      </div>';

echo'</div>';

        echo'</div></div>';                
}

    ?>