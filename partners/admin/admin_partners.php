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
$plugin_language = $pm->plugin_language("partners", $plugin_path);

$title = $plugin_language[ 'title' ]; #sc_datei Info

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='partners'");
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

if (isset($_GET[ 'delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $partnerID = (int)$_GET[ 'partnerID' ];
        safe_query("DELETE FROM " . PREFIX . "plugins_partners WHERE partnerID='" . $partnerID . "' ");
        $filepath = "../images/partners/";
        if (file_exists($filepath . $partnerID . '.gif')) {
            unlink($filepath . $partnerID . '.gif');
        }
        if (file_exists($filepath . $partnerID . '.jpg')) {
            unlink($filepath . $partnerID . '.jpg');
        }
        if (file_exists($filepath . $partnerID . '.png')) {
            unlink($filepath . $partnerID . '.png');
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        foreach ($sort as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "plugins_partners SET sort='".$sorter[1]."' WHERE partnerID='".$sorter[0]."' ");
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'save' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $name = $_POST[ 'name' ];
        $url = $_POST[ 'url' ];
        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }
        $facebook = $_POST[ "facebook" ];
        $twitter = $_POST[ "twitter" ];
        $info = $_POST[ "message" ];

        if (stristr($url, 'http://') || stristr($url, 'https://')) {
        } else {
            $url = 'http://' . $url;
        }
        if (!stristr($facebook, 'http://') && !stristr($facebook, 'https://')) {
            if($facebook != ''){
                $facebook = 'http://' . $facebook;
            }
        }

        if (!stristr($twitter, 'http://') && !stristr($twitter, 'https://')) {
            if($twitter != ''){
                $twitter = 'http://' . $twitter;
            }
        }

        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_partners` (
                    `name`,
                    `url`,
                    `facebook`,
                    `twitter`,
                    `displayed`,
                    `date`,
                    `info`,
                    `sort`
                )
                VALUES (
                    '$name',
                    '$url',
                    '$facebook',
                    '$twitter',
                    '" . $displayed . "',
                    '" . time() . "',
                    '".$info."',
                    '1'
                )"
        );
        $id = mysqli_insert_id($_database);

        $filepath = $plugin_path."images/";

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation',true, true);
		
        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1001 && $imageInformation[1] < 501) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_partners
                                    SET banner='" . $file . "' WHERE partnerID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1000, 500));
						}
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo  generateErrorBox($upload->translateError());
            }
        }
    } else {
        echo  $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $name = $_POST[ 'name' ];
        $url = $_POST[ 'url' ];
        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }
        $info = $_POST[ "message" ];
        $facebook = $_POST[ "facebook" ];
        $twitter = $_POST[ "twitter" ];

        $partnerID = (int)$_POST[ 'partnerID' ];
        $id = $partnerID;

        if (!stristr($facebook, 'http://') && !stristr($facebook, 'https://')) {
            if($facebook != ''){
                $facebook = 'http://' . $facebook;
            }
        }

        if (!stristr($twitter, 'http://') && !stristr($twitter, 'https://')) {
            if($twitter != ''){
                $twitter = 'http://' . $twitter;
            }
        }

        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_partners`
            SET
                `name` = '" . $name . "',
                `url` = '" . $url . "',
                `facebook` = '" . $facebook . "',
                `twitter` = '" . $twitter . "',
                `info` = '" . $info . "',
                `displayed` = '" . $displayed . "'
            WHERE
                `partnerID` = '" . $partnerID . "'"
        );

        $filepath = $plugin_path."/images/";

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true, true);

        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1001 && $imageInformation[1] < 501) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_partners
                                    SET banner='" . $file . "' WHERE partnerID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1000, 500));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo generateErrorBox($upload->translateError());
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif (isset($_POST[ 'partners_settings_save' ])) {  

   
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_partners_settings
            SET
                
                partners='" . $_POST[ 'partners' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_partners&action=admin_partners_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_partners&action=admin_partners_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "add") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
	
echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-person-vcard"></i> '.$plugin_language['partners'].'</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_partners">' . $plugin_language[ 'partners' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_partner'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';


	echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_partners" enctype="multipart/form-data">

     <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['partner_name'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="name" size="60" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 1000x500)</small></em></span>
    </div>
  </div>
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['homepage_url'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="url" size="60" value="http://" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">Facebook</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="facebook" size="60" value="http://" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">Twitter</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="twitter" size="60" value="http://" /></em></span>
    </div>
  </div>
    <div class="mb-3 row">
   <label class="col-sm-2 control-label">Info:</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" ></textarea></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_partner'].'</button>
    </div>
  </div>
</form>
</div>
  </div>';
} elseif ($action == "edit") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  
echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-person-vcard"></i> '.$plugin_language['partners'].'</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_partners">' . $plugin_language[ 'partners' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_partner'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

  
  $partnerID = $_GET[ 'partnerID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_partners WHERE partnerID='$partnerID'");
    $ds = mysqli_fetch_array($ergebnis);

    if (!empty($ds[ 'banner' ])) {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }

    if ($ds[ 'displayed' ] == '1') {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }
  
	echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_partners" enctype="multipart/form-data">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['current_banner'].':</label>
    <div class="col-sm-10">
      '.$pic.'
    </div>
  </div>
	<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['partner_name'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="name" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 1000x500)</small></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['homepage_url'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="url" value="'.getinput($ds['url']).'" /></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">Facebook</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="facebook" size="60" value="'.getinput($ds['facebook']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">Twitter</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="twitter" size="60" value="'.getinput($ds['twitter']).'" /></em></span>
    </div>
  </div>
   
<div class="mb-3 row">
   <label class="col-sm-2 control-label">Info:</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.getinput($ds['info']).'</textarea></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
     '.$displayed.'
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="partnerID" value="'.$partnerID.'" />
		<button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_partner'].'</button>
    </div>
  </div>
</form>
</div>
  </div>';




} elseif ($action == "admin_partners_settings") {

 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_partners_settings");
    $ds = mysqli_fetch_array($settings);

    
  $maxshownpartners = $ds[ 'partners' ];
if (empty($maxshownpartners)) {
    $maxshownpartners = 10;
}


    

    

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'    <form method="post" action="admincenter.php?site=admin_partners&action=admin_partners_settings">
        <div class="card">
            <div class="card-header">
                '.$plugin_language[ 'settings' ].'
            </div>

            <div class="card-body">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_partners">' . $plugin_language[ 'partners' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_partners&action=admin_partners_settings">' . $plugin_language[ 'settings' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  

                
                <div class="row">
                    <div class="col-md-6">
                        

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_partners'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip' ].'"><input class="form-control" type="text" name="partners" value="'.$ds['partners'].'" size="35"></em></span>
                            </div>
                        </div>

                        

                        
                    </div>

                    <div class="col-md-6">
                        
                    </div>
               </div>
                <br>
                <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-primary" type="submit" name="partners_settings_save">'.$plugin_language['update'].'</button>
    </div>
  </div>

 </div>
            </div>
       
        
    </form>';

} elseif ($action == "") {
	
echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-person-vcard"></i> '.$plugin_language['partners'].'</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_partners">' . $plugin_language[ 'partners' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_partners&amp;action=add" class="btn btn-primary">' . $plugin_language[ 'new_partner' ] . '</a>
      <a href="admincenter.php?site=admin_partners&action=admin_partners_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>';


	echo'<form method="post" action="admincenter.php?site=admin_partners">
  <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['partners'].'</b></th>
      <th><b>'.$plugin_language['clicks'].'</b></th>
      <th><b>'.$plugin_language['is_displayed'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

	$partners = safe_query("SELECT * FROM " . PREFIX . "plugins_partners ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(partnerID) as cnt FROM " . PREFIX . "plugins_partners"));
    $anzpartners = $tmp[ 'cnt' ];
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $CAPCLASS->createTransaction();
    $hash_2 = $CAPCLASS->getHash();

    $i = 1;
    while ($db = mysqli_fetch_array($partners)) {
        if ($i % 2) {
            $td = 'td1';
        } else {
            $td = 'td2';
        }

        $db[ 'displayed' ] == 1 ? $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

        $days = round((time() - $db[ 'date' ]) / (60 * 60 * 24));
        if ($days) {
            $perday = round($db[ 'hits' ] / $days, 2);
        } else {
            $perday = $db[ 'hits' ];
        }

        
        echo '<tr>
      <td><a href="'.getinput($db['url']).'" target="_blank">'.getinput($db['name']).'</a></td>
      <td>'.$db['hits'].' ('.$perday.')</td>
      <td>'.$displayed.'</td>
      <td><a href="admincenter.php?site=admin_partners&amp;action=edit&amp;partnerID='.$db['partnerID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>


      <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_partners&amp;delete=true&amp;partnerID='.$db['partnerID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'partners' ] . '</h5>
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
      <td>
      <select name="sort[]">';

        for ($j = 1; $j <= $anzpartners; $j++) {
            if ($db[ 'sort' ] == $j) {
                echo '<option value="' . $db[ 'partnerID' ] . '-' . $j . '" selected="selected">' . $j . '</option>';
            } else {
                echo '<option value="' . $db[ 'partnerID' ] . '-' . $j . '">' . $j . '</option>';
            }
        }

        echo '</select>
      </td>
    </tr>';
    $i++;
         
	}
	echo'
<tr class="td_head">
      <td colspan="5" align="right"><input type="hidden" name="captcha_hash" value="'.$hash_2.'" /><button class="btn btn-primary" type="submit" name="sortieren" />'.$plugin_language['to_sort'].'</button></td>
    </tr>
  </table>
  </form></div></div>';
}
echo '';
?>
