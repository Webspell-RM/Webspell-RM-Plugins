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
|        Copyright 2018-2022 by webspell-rm.de                      |
|        Userawards Addon by FIRSTBORN e.V                          |
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

echo'
  <link rel="stylesheet" type="text/css" href="../'.$plugin_path.'css/select2.min.css" />
  <link rel="stylesheet" type="text/css" href="../'.$plugin_path.'css/select2-bstheme.css">
  <script src="../'.$plugin_path.'js/select2.js"></script>
';


function getusers() {
    $nick = '';
    $get = safe_query("SELECT * FROM " . PREFIX . "user");
    while ($db=mysqli_fetch_array($get)) {
        $nick .= '<option value="'.$db['userID'].'">'.$db['nickname'].'</option>';
    }
        return $nick;
}

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("admin_user_awards", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='useraward'");
while ($db=mysqli_fetch_array($ergebnis)) {
    $accesslevel = 'is'.$db['accesslevel'].'admin';
    if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
        die($plugin_language[ 'access_denied' ]);
    }
}

$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$post = '';
if (isset($_POST['save'])) {
    $post = $_POST[ 'save' ];
}
$filepath = $plugin_path."images/userawards/";
$specialfilepath = $filepath."special/";

if($action == 'delete') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {

        $awardID = $_GET['awardID'];
        $ds = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_useraward_list WHERE uawardID='$awardID'"));

        if(file_exists($filepath.$ds['image'])) @unlink($filepath.$ds['image']);
        if(file_exists($specialfilepath.$ds['image'])) @unlink($specialfilepath.$ds['image']);

        safe_query(" DELETE FROM ".PREFIX."plugins_useraward WHERE awardID='$awardID'");
        safe_query("DELETE FROM ".PREFIX."plugins_useraward_list WHERE uawardID='$awardID' ");
        redirect('admincenter.php?site=admin_user_awards', '<div class=\'alert alert-success\' role=\'alert\'>'.$plugin_language[ 'del_okay' ].'</div>', '1');
    } else {
        #echo $plugin_language[ 'transaction_invalid' ];
        redirect('admincenter.php?site=admin_user_awards', ''.$plugin_language[ 'transaction_invalid' ].'', '1');
    }
} elseif($post == 'add') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $info = isset($_POST['info']) ? $_POST['info'] : '';
        $points = isset($_POST['points']) ? $_POST['points'] : '';
        $awardrequirepoints = isset($_POST['special']) ? $_POST['special'] : '0';

        safe_query("
            INSERT INTO ".PREFIX."plugins_useraward_list
                (name, info,awardrequirepoints,awardrequire)
            values
                ('$name', '$info', '$awardrequirepoints', '$points')
        ");
			
        $id = mysqli_insert_id($_database);	
        if($awardrequirepoints == '0') {
            $filepath = $filepath;
        } else {
            $filepath = $specialfilepath;
        }

        $errors = array();
        $_language->readModule('formvalidation', true);
        $upload = new \webspell\HttpUpload('rank');
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
                        $file = $name.$id.$endung;
                        if ($upload->saveAs($filepath . $file, true)) {
                            @chmod($filepath . $file, $new_chmod);
                            safe_query(
                                "UPDATE ".PREFIX."plugins_useraward_list SET image='".$file."' WHERE uawardID='".$id."'"
                            );
                        }
                    } else {
                        $errors[] = $plugin_language['broken_image'];
                    }
                } else {
                    $errors[] = $plugin_language['unsupported_image_type'];
                }
            } else {
                $errors[] = $upload->translateError();
            }
        }
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_lang['errors_there'], $errors);
        } else {
            redirect('admincenter.php?site=admin_user_awards', '<div class=\'alert alert-success\' role=\'alert\'>'.$plugin_language[ 'add_okay' ].'</div>', '1');
        }
    } else {
        #echo $plugin_language[ 'transaction_invalid' ];
        redirect('admincenter.php?site=admin_user_awards', ''.$plugin_language[ 'transaction_invalid' ].'', '1');
    }
} elseif($post == 'saveedit') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $info = isset($_POST['info']) ? $_POST['info'] : '';
        $points = isset($_POST['points']) ? $_POST['points'] : '';
        $awardID = isset($_POST['awardID']) ? $_POST['awardID'] : '';
        $awardrequirepoints = isset($_POST['special']) ? $_POST['special'] : '0';

        safe_query("UPDATE ".PREFIX."plugins_useraward_list SET name='$name', info='$info', awardrequirepoints='$awardrequirepoints', awardrequire='$points' WHERE uawardID='$awardID' ");

        if($awardrequirepoints == '0') {
            $filepath = $filepath;
        } else {
            $filepath = $specialfilepath;
        }

        $errors = array();
        $_language->readModule('formvalidation', true);
        $upload = new \webspell\HttpUpload('image');
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
                        $file = $name.$awardID.$endung;
                        if ($upload->saveAs($filepath . $file, true)) {
                            @chmod($filepath . $file, $new_chmod);
                            safe_query(
                                "UPDATE ".PREFIX."plugins_useraward_list SET image='".$file."' WHERE uawardID='".$awardID."'"
                            );
                        }
                    } else {
                        $errors[] = $plugin_language['broken_image'];
                    }
                } else {
                    $errors[] = $plugin_language['unsupported_image_type'];
                }
            } else {
                $errors[] = $upload->translateError();
            }
        }
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect('admincenter.php?site=admin_user_awards', '<div class=\'alert alert-success\' role=\'alert\'>'.$plugin_language[ 'edit_okay' ].'</div>', '1');
        }
    } else {
        #echo $plugin_language[ 'transaction_invalid' ];
        redirect('admincenter.php?site=admin_user_awards', ''.$plugin_language[ 'transaction_invalid' ].'', '1');
    }
} elseif ($action == 'uwdelete') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $uwID = isset($_GET['uwID']) ? $_GET['uwID'] : '';
        $awardID = isset($_GET['awardID']) ? $_GET['awardID'] : '';
        safe_query(" DELETE FROM ".PREFIX."plugins_useraward WHERE uwID='$uwID' ");
        redirect('admincenter.php?site=admin_user_awards&action=userawarding&awardID='.$awardID.'', '<div class=\'alert alert-success\' role=\'alert\'>'.$plugin_language[ 'del_okay' ].'</div>', '3');
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif ($post == 'uwsave') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

        $awardID = isset($_POST['awardID']) ? $_POST['awardID'] : '';
        $user = isset($_POST['user']) ? $_POST['user'] : '';
  
        safe_query("
            INSERT INTO ".PREFIX."plugins_useraward 
                ( userID, awardID )
            values
              ( '$user', '$awardID' )
        ");
        $id = mysqli_insert_id($_database);			 
        redirect('admincenter.php?site=admin_user_awards&action=userawarding&awardID='.$awardID.'', '<div class=\'alert alert-success\' role=\'alert\'>'.getnickname($user).' '.$plugin_language[ 'special_okay' ].'</div>', '1');
    } else {
        #echo $plugin_language[ 'transaction_invalid' ];
        redirect('admincenter.php?site=admin_user_awards', ''.$plugin_language[ 'transaction_invalid' ].'', '1');
    }
} elseif ($action == 'enableall') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE ".PREFIX."plugins_useraward_settings SET allaward='1'"
        );
        redirect('admincenter.php?site=admin_user_awards', '<div class=\'alert alert-success\' role=\'alert\'>OK - Bitte warten... || OK - Please wait...</div>', '1');
    } else {
        #echo $plugin_language[ 'transaction_invalid' ];
        redirect('admincenter.php?site=admin_user_awards', ''.$plugin_language[ 'transaction_invalid' ].'', '1');
    }
} elseif ($action == 'disableall') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE ".PREFIX."plugins_useraward_settings SET allaward='0'"
        );
        redirect('admincenter.php?site=admin_user_awards', '<div class=\'alert alert-success\' role=\'alert\'>OK - Bitte warten... || OK - Please wait...</div>', '1');
    } else {
        #echo $plugin_language[ 'transaction_invalid' ];
        redirect('admincenter.php?site=admin_user_awards', ''.$plugin_language[ 'transaction_invalid' ].'', '1');
    }
} elseif ($action == 'refresh') {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        include('./includes/plugins/useraward/functions.php');
        redirect('admincenter.php?site=admin_user_awards', '', '1');
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect('admincenter.php?site=admin_user_awards', '', '1');

    }
} elseif($action == 'uwadd') {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $awardID = isset($_GET['awardID']) ? $_GET['awardID'] : '';
    echo'
        <div class="card">
            <div class="card-header">
                <i class="fas fa-award"></i> '.$plugin_language[ 'awardgive' ].'
            </div>
            <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_user_awards">User Awards</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav> 
                <form method="post" action="admincenter.php?site=admin_user_awards" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Award:</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="text" name="award" size="30" value="'.$awardID.'" class="form-control" disabled>
                                        </em>
                                    </span>
                                </div>
                            </div>                  
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">User-ID:</label>
                                <div class="col-sm-8">
                                    <select name="user" class="locationMultiple form-control">
                                        '.getusers().'
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label"><input class="btn btn-success" type="submit" name="save" value="'.$plugin_language[ 'giveuser' ].'"></label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="hidden" name="save" value="uwsave">
                                            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                                            <input type="hidden" name="awardID" value="'.$awardID.'">
                                        </em>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    ';
} elseif($action == 'add') {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'
        <div class="card">
            <div class="card-header">
                <i class="fas fa-award"></i> '.$plugin_language[ 'addaward' ].'
            </div>
            <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_user_awards">User Awards</a></li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
                </ol>
            </nav>
                <form method="post" action="admincenter.php?site=admin_user_awards" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Award-Name:</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="text" name="name" size="30" value="" class="form-control">
                                        </em>
                                    </span>
                                </div>
                            </div> 
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Award Info:</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="text" name="info" size="30" value="" class="form-control">
                                        </em>
                                    </span>
                                </div>
                            </div> 
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Award '.$plugin_language[ 'awardpic' ].':</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input class="form-control" name="rank" type="file">
                                        </em>
                                    </span>
                                </div>
                            </div> 
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Special Award ?</label>
                                <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
                                    <span class="text-muted small">
                                        <em>
                                            <input class="form-check-input" type="checkbox" name="special" value="-1" />
                                        </em>
                                    </span>
                                </div>
                            </div> 
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">'.$plugin_language[ 'points' ].'</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="text" name="points" size="30" value="" class="form-control">
                                        </em>
                                    </span>
                                </div>
                            </div> 
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label"><input class="btn btn-success" type="submit" name="save" value="'.$plugin_language[ 'save' ].'"></label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                                            <input type="hidden" name="save" value="add">
                                        </em>
                                    </span>
                                </div>
                            </div> 
                        </div>
                    </div>
                </form>
            </div>
    ';
} elseif($action == 'edit') {
    $awardID = isset($_GET['awardID']) ? $_GET['awardID'] : '';
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_useraward_list WHERE uawardID='$awardID'");
    $ds=mysqli_fetch_array($ergebnis);

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $saward = '';
    $filepath = $filepath;
    if($ds['awardrequirepoints'] == '-1') {
        $saward = 'checked=\'checked\'';
        $filepath = $specialfilepath;
    }	

    echo'
        <div class="card">
            <div class="card-header">
                <i class="fas fa-award"></i> '.$plugin_language[ 'editaward' ].'
            </div>
            <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_user_awards">User Awards</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
                <form method="post" action="admincenter.php?site=admin_user_awards" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Award-Name:</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="text" name="name" size="30" value="'.$ds['name'].'" class="form-control">
                                        </em>
                                    </span>
                                </div>
                            </div>                  
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Award Info:</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="text" name="info" size="30" value="'.$ds['info'].'" class="form-control">
                                        </em>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">'.$plugin_language[ 'nowpic' ].':</label>
                                <div class="col-sm-8">                                    
                                    <img src="../'.$filepath.$ds['image'].'" height="100" border="0" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Award-Bild:</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input class="form-control" name="image" type="file">
                                        </em>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">Special Award ?</label>
                                <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
                                    <span class="text-muted small">
                                        <em>
                                            <input class="form-check-input" type="checkbox" name="special" value="-1" '.$saward.' />
                                        </em>
                                    </span>
                                </div>
                            </div> 
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label">'.$plugin_language[ 'points' ].'</label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="text" name="points" size="30" value="'.$ds['awardrequire'].'" class="form-control">
                                        </em>
                                    </span>
                                </div>
                            </div> 
                            <div class="mb-3 row">
                                <label class="col-sm-4 control-label"><input class="btn btn-success" type="submit" name="save" value="'.$plugin_language['update'].'"></label>
                                <div class="col-sm-8">
                                    <span class="text-muted small">
                                        <em>
                                            <input type="hidden" name="save" value="saveedit">
                                            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                                            <input type="hidden" name="awardID" value="'.$awardID.'">
                                        </em>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    ';
} elseif($action == 'userawarding') {
    $awardID = isset($_GET['awardID']) ? $_GET['awardID'] : '';
    $ds = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_useraward_list WHERE uawardID = '$awardID' ORDER BY name"));
    echo'
        <div class="card">
            <div class="card-header">
                <i class="fas fa-award"></i> '.$plugin_language[ 'special_award' ].'
            </div>
            <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_user_awards">User Awards</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                <h2><img src="../'.$specialfilepath.$ds['image'].'" /> '.$ds['name'].'</h2>
                <a href="admincenter.php?site=admin_user_awards&action=userawarding&action=uwadd&awardID='.$awardID.'" class="btn btn-primary" type="button"><i class="fas fa-plus"></i> '.$plugin_language['addspecialaward'].'</a><br><br>
                <table id="plugini" class="table table-bordered table-striped">
                    <thead>
                        <th class="title">User:</th>
                        <th class="title">'.$plugin_language['actions'].':</th>
                    </thead>
    ';
			 
    $opponents=safe_query("SELECT * FROM ".PREFIX."plugins_useraward WHERE awardID='$awardID' ORDER BY userID");
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while($db=mysqli_fetch_array($opponents)) {
        echo'
                    <tbody>
                        <tr>
                            <td><b>'.getnickname($db['userID']).'</b></td>
                            <td>
                                <!--<input class="btn btn-danger" type="button" 
                                onclick="MM_confirm(\''.$plugin_language['really_delete'].'\', \'admincenter.php?site=admin_user_awards&action=uwdelete&uwID='.$db['uwID'].'&awardID='.$db['awardID'].'&amp;captcha_hash='.$hash.'\')"
                                value="'.$plugin_language['delete'].'" />-->

                                 <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_user_awards&action=uwdelete&uwID='.$db['uwID'].'&awardID='.$db['awardID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'add_award' ] . '</h5>
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
                        </tr>
                    </tbody>
        ';
    }
    echo'
                </table>
            </div>
        </div>
    ';
} else {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();



    $translate = new multiLanguage(detectCurrentLanguage());

    $ds1 = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_useraward_settings"));
    if($ds1['allaward'] == '0'){
        $status='<i class="bi bi-x-circle" title="'.$plugin_language['show'].'"></i>';
    } else {
        $status='<i class="bi bi-check" title="'.$plugin_language['notshow'].'"></i>';
    }

    if($ds1['allaward'] == '0'){
        $aktion='
            <a class="btn btn-primary" type="button" href="admincenter.php?site=admin_user_awards&amp;action=enableall&amp;captcha_hash='.$hash.'" title=""><i class="bi bi-check"></i>'.$plugin_language['showallmedals'].'</a>
            <a class="btn btn-primary" type="button" href="admincenter.php?site=admin_user_awards&amp;action=refresh&amp;captcha_hash='.$hash.'" title="">'.$plugin_language['refreshusers'].'</a> 
        ';
    } else {
        $aktion='
            <a class="btn btn-primary" type="button" href="admincenter.php?site=admin_user_awards&amp;action=disableall&amp;captcha_hash='.$hash.'" title=""><i class="bi bi-times-circle"></i>'.$plugin_language['shownotallmedals'].'</a>  
            <a class="btn btn-primary" type="button" href="admincenter.php?site=admin_user_awards&amp;action=refresh&amp;captcha_hash='.$hash.'" title="">'.$plugin_language['refreshusers'].'</a> 
        ';
    }

    echo'
        <div class="card">
            <div class="card-header">
                <i class="fas fa-award"></i> User Awards
            </div>
            <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_user_awards">User Awards</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                <a href="admincenter.php?site=admin_user_awards&action=add" class="btn btn-primary" type="button"><i class="fas fa-plus"></i> '.$plugin_language['add_award'].'</a>
                '.$aktion.'	<br><br>
                <table id="plugini" class="table table-bordered table-striped dataTable">
                    <thead>
                        <th class="title">'.$plugin_language['awardpic'].':</th>
                        <th class="title">Award:</th>
                        <th class="title">Info:</th>
                        <th class="title">'.$plugin_language['points'].':</th>
                        <th class="title">'.$plugin_language[ 'actions' ].':</th>
                    </thead>
    ';
    $opponentss = safe_query("SELECT * FROM ".PREFIX."plugins_useraward_list WHERE awardrequirepoints = '-1' ORDER BY name");
    while($dbs=mysqli_fetch_array($opponentss)) {
        $awardID = $dbs['uawardID'];
        $translate->detectLanguages($dbs['name']);
        $namelg = $translate->getTextByLanguage($dbs['name']);
        $translate->detectLanguages($dbs['info']);
        $infolg = $translate->getTextByLanguage($dbs['info']);
	
        echo'
                    <tr>
                        <td><img src="../'.$specialfilepath.$dbs['image'].'"></td>
                        <td><b>'.$namelg.'</b></td>
                        <td>'.$infolg.'</td>
                        <td>'.$dbs['awardrequire'].'</td>
                        <td>
                            <a href="admincenter.php?site=admin_user_awards&action=edit&awardID='.$dbs['uawardID'].'" class="btn btn-warning" type="button"><i class="fas fa-edit"></i> '.$plugin_language['formedit'].'</a>
                            <a href="admincenter.php?site=admin_user_awards&action=userawarding&awardID='.$dbs['uawardID'].'" class="btn btn-info" type="button">Special Award</a>
                            

                            <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_user_awards&action=delete&awardID='.$dbs['uawardID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'add_award' ] . '</h5>
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
                    </tr>
        ';
    }
			 
    $opponents=safe_query("SELECT * FROM ".PREFIX."plugins_useraward_list WHERE awardrequirepoints != '-1' ORDER BY name");
    while($db=mysqli_fetch_array($opponents)) {
        $awardID = $db['uawardID'];
        $del = '';
        $translate->detectLanguages($db['info']);
        $infolg = $translate->getTextByLanguage($db['info']);
        $translate->detectLanguages($db['name']);
        $namelg = $translate->getTextByLanguage($db['name']);

        #$_GET['uawardID']=$ds['uawardID'];
	
        if($db['name'] !== 'Comments' OR $db['name'] !== 'Forum' OR $db['name'] !== 'Member' OR $db['name'] !== 'Messages' OR $db['name'] !== 'News' OR $db['name'] !== 'Wars') {
            $del = '
                <input class="btn btn-danger" type="button" 
                onclick="MM_confirm(\''.$plugin_language['really_delete'].'\', \'admincenter.php?site=admin_user_awards&action=delete&awardID='.$db['uawardID'].'&amp;captcha_hash='.$hash.'\')"
                value="'.$plugin_language['delete'].'" />

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="'.$db['uawardID'].'">
                ' . $plugin_language['delete'] . '
                </button>
                <!-- Button trigger modal END-->


                ';
        }
        echo'
                    <tr>
                        <td><img src="../'.$filepath.$db['image'].'"></td>
                        <td><b>'.$namelg.'</b></td>
                        <td>'.$infolg.'</td>
                        <td>'.$db['awardrequire'].'</td>
                        <td>
                            <a href="admincenter.php?site=admin_user_awards&action=edit&awardID='.$db['uawardID'].'" class="btn btn-warning" type="button"><i class="fas fa-edit"></i> '.$plugin_language['formedit'].'</a>

                            

  <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_user_awards&action=delete&awardID='.$db['uawardID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->
 </td>
     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'add_award' ] . '</h5>
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


                       
                    </tr>
        ';
    }
    
    echo'
                </table>
            </div>
        </div>
    ';
}

echo'

<script>
$.fn.select2.defaults.set("theme", "bootstrap");
        $(".locationMultiple").select2({
            width: null
        })

</script>
';


?>	