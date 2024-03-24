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
# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("textslider", $plugin_path);

#$title = $plugin_language[ 'title' ]; #sc_datei Info

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='textslider'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}
 
$filepath = $plugin_path."images/";
 
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

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
 
if ($action == "add") {

  $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_textslider"
        )
    );

  $ani_title = str_replace('value="' . $ds['ani_title'] . '"', 'value="' . $ds['ani_title'] . '" selected="selected"', $ani); 
    $ani_link = str_replace('value="' . $ds['ani_link'] . '"', 'value="' . $ds['ani_link'] . '" selected="selected"', $ani);  
    $ani_description = str_replace('value="' . $ds['ani_description'] . '"', 'value="' . $ds['ani_description'] . '" selected="selected"', $ani);  

    echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'carousel' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_textslider">' . $plugin_language[ 'carousel' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_carousel' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_textslider" enctype="multipart/form-data">
   <div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_pic'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="btn btn-info" name="carousel_pic" type="file" size="40" /> <small>(' . $plugin_language[ 'carousel_upload_info' ] . ')</small></em></span>
    </div>
  </div>
  
  <div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
    </div>
  </div>
   <div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="form-control" id="description" rows="5" cols="" name="description" style="width: 100%;"></textarea></em></span>
    </div>
  </div>

   <div class="form-group">
        <label for="ani_title" class="col-lg-2 control-label">'.$plugin_language['title-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_title" name="ani_title" class="form-control">'.$ani_title.'</select>
        </div>
    </div>

    <div class="form-group">
        <label for="ani_link" class="col-lg-2 control-label">'.$plugin_language['link-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_link" name="ani_link" class="form-control">'.$ani_link.'</select>
        </div>
    </div>

    <div class="form-group">
        <label for="ani_description" class="col-lg-2 control-label">'.$plugin_language['description-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_description" name="ani_description" class="form-control">'.$ani_description.'</select>
        </div>
    </div>

<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="checkbox" name="displayed" value="1" checked="checked" /></em></span>
    </div>
  </div>
  <div class="form-group">
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
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'carousel' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_textslider">' . $plugin_language[ 'carousel' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_carousel' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_textslider WHERE carouselID='" . intval($_GET['carouselID']) ."'"
        )
    );
    if (!empty($ds[ 'carousel_pic' ])) {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepath . $ds[ 'carousel_pic' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }
 
    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input type="checkbox" name="displayed" value="1" />';
    }

    

    $ani_title = str_replace('value="' . $ds['ani_title'] . '"', 'value="' . $ds['ani_title'] . '" selected="selected"', $ani); 
    $ani_link = str_replace('value="' . $ds['ani_link'] . '"', 'value="' . $ds['ani_link'] . '" selected="selected"', $ani);  
    $ani_description = str_replace('value="' . $ds['ani_description'] . '"', 'value="' . $ds['ani_description'] . '" selected="selected"', $ani);     
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_textslider" enctype="multipart/form-data">
<input type="hidden" name="carouselID" value="' . $ds['carouselID'] . '" />
<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['current_pic'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
    </div>
  </div>
<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_upload_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="carousel_pic" type="file" size="40" /></em></span>
    </div>
  </div>
  
<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></em></span>
    </div>
    
  </div>
 
<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="link" size="60" value="' . getinput($ds[ 'link' ]) . '" /></em></span>
    </div>
  </div>
<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="form-control" id="description" rows="5" cols="" name="description" style="width: 100%;">' . getinput($ds[ 'description' ]) .
        '</textarea></em></span>
    </div>
  </div>


    <div class="form-group">
        <label for="ani_title" class="col-lg-2 control-label">'.$plugin_language['title-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_title" name="ani_title" class="form-control">'.$ani_title.'</select>
        </div>
    </div>

    <div class="form-group">
        <label for="ani_link" class="col-lg-2 control-label">'.$plugin_language['link-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_link" name="ani_link" class="form-control">'.$ani_link.'</select>
        </div>
    </div>


    <div class="form-group">
        <label for="ani_description" class="col-lg-2 control-label">'.$plugin_language['description-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_description" name="ani_description" class="form-control">'.$ani_description.'</select>
        </div>
    </div>













<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <p class="form-control-static">'.$displayed.'</p></em></span>
    </div>
  </div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="saveedit"  />'.$plugin_language['edit_carousel'].'</button>
    </div>
  </div>
</form>
</div></div>';
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_textslider SET sort='$sorter[1]' WHERE carouselID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_textslider", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $ani_title = $_POST[ "ani_title" ];
    $ani_link = $_POST[ "ani_link" ];
    $ani_description = $_POST[ "ani_description" ];
    $title = $_POST[ 'title' ];
    $link = $_POST[ 'link' ];
    $description = $_POST[ 'description' ];
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
    safe_query("INSERT INTO `".PREFIX."plugins_textslider` (title, ani_title, link, ani_link, description, ani_description, displayed, sort) values ('".$title."','".$ani_title."', '".$link."', '".$ani_link."', '".$description."', '".$ani_description."', '".intval($displayed)."','1')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();
 
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
                                "UPDATE " . PREFIX . "plugins_textslider SET carousel_pic='" . $file . "' WHERE carouselID='" . $id . "'"
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
            redirect("admincenter.php?site=admin_textslider", "", 0);
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
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        
        safe_query(
            "UPDATE " . PREFIX . "plugins_textslider SET title='" . $title . "', ani_title='" . $ani_title . "', link='" . $link . "', ani_link='" . $ani_link . "', description='" . $description .
            "', ani_description='" . $ani_description . "', displayed='" . $displayed . "' WHERE carouselID='" .
            $_POST[ "carouselID" ] . "'"
        );
 
        $id = $_POST[ 'carouselID' ];
 
        $errors = array();
 
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
                                "UPDATE " . PREFIX . "plugins_textslider SET carousel_pic='" . $file . "' WHERE carouselID='" . $id . "'"
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
            redirect("admincenter.php?site=admin_textslider", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_textslider WHERE carouselID='" . $_GET[ "carouselID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_textslider WHERE carouselID='" . $_GET[ "carouselID" ] . "'")) {
            @unlink($filepath.$data['carousel_pic']);
            redirect("admincenter.php?site=admin_textslider", "", 0);
        } else {
            redirect("admincenter.php?site=admin_textslider", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} else {
 echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-layout-wtf"></i> ' . $plugin_language[ 'carousel' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_textslider">' . $plugin_language[ 'carousel' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav> 
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_textslider&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_carousel' ] . '</a>
    </div>
  </div>';

 
    echo '<form method="post" action="admincenter.php?site=admin_textslider">
    <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['name'].'</b></th>
      <th><b>'.$plugin_language['carousel'].'</b></th>
      <th class="hidden-xs hidden-sm"><b>'.$plugin_language['is_displayed'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_textslider ORDER BY sort");
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
           
            if (stristr($ds[ 'link' ], 'http://')) {
                $title = '<a href="' . getinput($ds[ 'link' ]) . '" target="_blank">' . getinput($ds[ 'title' ]) . '</a>';
            } else {
                $title = '<a href="http://' . getinput($ds[ 'link' ]) . '" target="_blank">' . getinput($ds[ 'title' ]) .
                '</a>';
            }

            $title = $ds[ 'title' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
            
    
            #$title = toggle(htmloutput($title), 1);
            #$title = toggle($title, 1);
 
            echo '<tr>
           <td class="' . $td . '">' . $title . '</td>
           <td class="' . $td . '"><img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="../' . $filepath . $ds[ 'carousel_pic' ] . '" alt="{img}" /></td>
           <td class="' . $td . '">' . $displayed . '</td>
           <td class="' . $td . '"><a href="admincenter.php?site=admin_textslider&amp;action=edit&amp;carouselID=' . $ds[ 'carouselID' ] .
                '" class="hidden-xs hidden-sm btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_textslider&amp;delete=true&amp;carouselID=' . $ds[ 'carouselID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'carousel' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
</table>
</form></div></div>';
}

    ?>