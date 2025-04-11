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
$plugin_language = $pm->plugin_language("admin_sponsors", $plugin_path);

if (!ispageadmin($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}

$filepath = $plugin_path."/images/";

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "add") {
    
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    
    echo '<script>
    <!--
    function chkFormular() {
        if(!validbbcode(document.getElementById(\'message\').value, \'admin\')) {
           return false;
       }
   }
-->
</script>';

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-person-raised-hand"></i> '.$plugin_language['sponsors'].'</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_sponsors">' . $plugin_language[ 'sponsors' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_sponsor' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

  
  echo'<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_sponsors" enctype="multipart/form-data" onsubmit="return chkFormular();">
   <div class="row">

<div class="col-md-6">

  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['sponsor_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="name" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['sponsor_url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="url" maxlength="255" value="" /></em></span>
    </div>
  </div>


  </div>

<div class="col-md-6">

 <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_upload'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_upload_small'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="btn btn-info" name="banner_small" type="file" size="40" /> <small>('.$plugin_language['banner_upload_info'].')</small></em></span>
    </div>
  </div>

  
</div>

<div class="col-md-12"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" ></textarea></em></span><br>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
    <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['mainsponsor'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
    <input class="form-check-input" type="checkbox" name="mainsponsor" value="1" />
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
    <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_sponsor'].'</button>
    </div>
  </div>

  </div>
  </form></div>
  </div>';
} elseif ($action == "edit") {
    
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_sponsors WHERE sponsorID='" . $_GET[ "sponsorID" ] ."'"
        )
    );
    if (!empty($ds[ 'banner' ])) {
        $pic = '<img class="img-thumbnail" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="/includes/plugins/sponsors/images/no-image.jpg" alt="">';
    }
    if (!empty($ds[ 'banner_small' ])) {
        $pic_small = '<img class="img-thumbnail" src="../' . $filepath . $ds[ 'banner_small' ] . '" alt="">';
    } else {
        $pic_small = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="/includes/plugins/sponsors/images/no-image.jpg" alt="">';
    }

    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }

    if ($ds[ 'mainsponsor' ] == 1) {
        $mainsponsor = '<input class="form-check-input" type="checkbox" name="mainsponsor" value="1" checked="checked" />';
    } else {
        $mainsponsor = '<input class="form-check-input" type="checkbox" name="mainsponsor" value="1" />';
    }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    
    echo '<script>
    <!--
    function chkFormular() {
        if(!validbbcode(document.getElementById(\'message\').value, \'admin\')) {
           return false;
       }
   }
-->
</script>';
  
echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-person-raised-hand"></i> '.$plugin_language['sponsors'].'</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_sponsors">' . $plugin_language[ 'sponsors' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_sponsor' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

  echo'<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_sponsors" enctype="multipart/form-data" onsubmit="return chkFormular();"> 
  <input type="hidden" name="sponsorID" value="'.$ds['sponsorID'].'" />
     
      <div class="row">

<div class="col-md-6">

  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['sponsor_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="name" maxlength="255" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>

  </div>

<div class="col-md-6">

  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['sponsor_url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="url" maxlength="255" value="'.getinput($ds['url']).'" /></em></span>
    </div>
  </div>

  </div>

 

<div class="col-md-6">

<div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['current_banner'].':</label>
    <div class="col-sm-8">
      '.$pic.'
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['current_banner_small'].':</label>
    <div class="col-sm-8">
    '.$pic_small.'
    </div>
  </div>

   </div>

<div class="col-md-6">

  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_upload'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_upload_small'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="btn btn-info" name="banner_small" type="file" size="40" /> <small>('.$plugin_language['banner_upload_info'].')</small></em></span>
    </div>
  </div> </div>

 </div>
 <div class="col-md-12"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.getinput($ds['info']).'</textarea></em></span><br>
    </div>
  
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
    '.$displayed.'
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['mainsponsor'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
    '.$mainsponsor.'
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
    <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_sponsor'].'</button>
    </div>
  </div>
  <div><div>
  </form></div>
  </div>';
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_sponsors SET sort='$sorter[1]' WHERE sponsorID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_sponsors", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $name = $_POST[ "name" ];
    $url = $_POST[ "url" ];
    $info = $_POST[ "message" ];
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }
    if (isset($_POST[ "mainsponsor" ])) {
        $mainsponsor = 1;
    } else {
        $mainsponsor = 0;
    }

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO " . PREFIX .
            "plugins_sponsors (sponsorID, name, url, info, displayed, mainsponsor, date, sort) values('', '" . $name . "', '" .
            $url . "', '" . $info . "', '" . $displayed . "', '" . $mainsponsor . "', '" . time() . "', '1')"
        );

        $id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
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

                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_sponsors SET banner='" . $file . "' WHERE sponsorID='" . $id . "'"
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

        $upload = new \webspell\HttpUpload('banner_small');
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
                        $file = $id.'_small'.$endung;

                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_sponsors SET banner_small='" . $file . "'
                                WHERE sponsorID='" . $id . "'"
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
            redirect("admincenter.php?site=admin_sponsors", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $name = $_POST[ "name" ];
    $url = $_POST[ "url" ];
    $info = $_POST[ "message" ];
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (isset($_POST[ "mainsponsor" ])) {
        $mainsponsor = 1;
    } else {
        $mainsponsor = 0;
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (stristr($url, 'http://') || stristr($url, 'https://')) {
        } else {
            $url = 'http://' . $url;
        }

        safe_query(
            "UPDATE " . PREFIX . "plugins_sponsors SET name='" . $name . "', url='" . $url . "', info='" . $info .
            "', displayed='" . $displayed . "', mainsponsor='" . $mainsponsor . "' WHERE sponsorID='" .
            $_POST[ "sponsorID" ] . "'"
        );

        $id = $_POST[ 'sponsorID' ];

        $errors = array();

        //TODO: should be loaded from root language folder
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

                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_sponsors SET banner='" . $file . "' WHERE sponsorID='" . $id . "'"
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

        $upload = new \webspell\HttpUpload('banner_small');
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
                        $file = $id.'_small'.$endung;

                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_sponsors SET banner_small='" . $file . "' ".
                                "WHERE sponsorID='" . $id . "'"
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
            redirect("admincenter.php?site=admin_sponsors", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif (isset($_POST[ 'sponsors_settings_save' ])) {  

   
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_sponsors_settings
            SET
                
                sponsors='" . $_POST[ 'sponsors' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_sponsors&action=admin_sponsors_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_sponsors&action=admin_sponsors_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }

} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors WHERE sponsorID='" . $_GET[ "sponsorID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_sponsors WHERE sponsorID='" . $_GET[ "sponsorID" ] . "'")) {
            @unlink($filepath.$data['banner']);
            @unlink($filepath.$data['banner_small']);
            redirect("admincenter.php?site=admin_sponsors", "", 0);
        } else {
            redirect("admincenter.php?site=admin_sponsors", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }


} elseif ($action == "admin_sponsors_settings") {

 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors_settings");
    $ds = mysqli_fetch_array($settings);

    
  $maxshownsponsors = $ds[ 'sponsors' ];
if (empty($maxshownsponsors)) {
    $maxshownsponsors = 10;
}


    

    

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'    <form method="post" action="admincenter.php?site=admin_sponsors&action=admin_sponsors_settings">
        <div class="card">
            <div class="card-header">
                '.$plugin_language[ 'settings' ].'
            </div>

            <div class="card-body">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_sponsors">' . $plugin_language[ 'sponsors' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_sponsors&action=admin_sponsors_settings">' . $plugin_language[ 'settings' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  

                
                <div class="row">
                    <div class="col-md-6">
                        

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_sponsors'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip' ].'"><input class="form-control" type="text" name="sponsors" value="'.$ds['sponsors'].'" size="35"></em></span>
                            </div>
                        </div>

                        

                        
                    </div>

                    <div class="col-md-6">
                        
                    </div>
               </div>
                <br>
 <div class="mb-3 row">
 <div class="col-sm-offset-2 col-sm-10">
<input type="hidden" name="captcha_hash" value="'.$hash.'"> 
<button class="btn btn-primary" type="submit" name="sponsors_settings_save">'.$plugin_language['update'].'</button>
</div></div>

        

 </div>
            </div>
       
        
    </form>';

} elseif ($action == "") {

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-person-raised-hand"></i> '.$plugin_language['sponsors'].'</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_sponsors">' . $plugin_language[ 'sponsors' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_sponsors&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_sponsor' ] . '</a>
      <a href="admincenter.php?site=admin_sponsors&action=admin_sponsors_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>';

  echo'<form method="post" action="admincenter.php?site=admin_sponsors">
  <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['sponsor'].'</b></th>
      <th><b>'.$plugin_language['clicks'].'</b></th>
      <th><b>'.$plugin_language['is_displayed'].'</b></th>
      <th><b>'.$plugin_language['mainsponsor'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';
   
   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors ORDER BY sort");
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
            $ds[ 'mainsponsor' ] == 1 ?
            $mainsponsor = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $mainsponsor = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

            if (stristr($ds[ 'url' ], 'http://')) {
                $name = '<a href="' . getinput($ds[ 'url' ]) . '" target="_blank">' . getinput($ds[ 'name' ]) . '</a>';
            } else {
                $name = '<a href="http://' . getinput($ds[ 'url' ]) . '" target="_blank">' . getinput($ds[ 'name' ]) .
                '</a>';
            }

            $days = round((time() - $ds[ 'date' ]) / (60 * 60 * 24));
            if ($days) {
                $perday = round($ds[ 'hits' ] / $days, 2);
            } else {
                $perday = $ds[ 'hits' ];
            }
      
      echo'<tr>
        <td>'.$name.'</td>
        <td>'.$ds['hits'].' ('.$perday.')</td>
        <td>'.$displayed.'</td>
        <td>'.$mainsponsor.'</td>
        <td><a href="admincenter.php?site=admin_sponsors&amp;action=edit&amp;sponsorID='.$ds['sponsorID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

                   <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_sponsors&amp;delete=true&amp;sponsorID='.$ds['sponsorID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'sponsors' ] . '</h5>
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
        <td><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'sponsorID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'sponsorID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
        </td>
      </tr>';
      
      $i++;
    }
  
} else {
        echo '<tr><td colspan="6">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }


echo'<tr>
      <td class="td_head" colspan="6" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input class="btn btn-primary" type="submit" name="sortieren" value="'.$plugin_language['to_sort'].'" /></td>
    </tr>
  </table>
  </form>';
}
echo '</div></div>';
?>