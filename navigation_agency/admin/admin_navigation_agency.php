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
$plugin_language = $pm->plugin_language("navigation_agency", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='navigation_agency'");
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

$ani = '
<option value="100vh">100vh</option>
<option value="75vh">75vh</option>
<option value="50vh">50vh</option>
<option value="25vh">25vh</option>
';
 
if ($action == "add") {

    @$ani_height = str_replace('value="' . $ds['height'] . '"', 'value="' . $ds['height'] . '" selected="selected"', $ani); 

    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-box-arrow-up-right"></i> ' . $plugin_language[ 'title' ] . '
    </div>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_navigation_agency" class="white">' . $plugin_language[ 'title' ] . '</a></li>
        <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'new_header' ] . '</li>
    </ol>
    </nav>
    <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_navigation_agency" enctype="multipart/form-data">
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="btn btn-info" name="header_pic" type="file" size="40" /> <small>(' . $plugin_language[ 'header_upload_info' ] . ')</small></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['size'].':</label>
    <div class="col-lg-3">
        <select id="ani_height" name="height" class="form-select">'.$ani_height.'</select>
        <span class="text-muted small"><em><small>' . $plugin_language[ 'size_info' ] . '</small></em></span>
    </div>
</div>
    <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['new_header'].'</button>
    </div>
  </div>
</div>
</form>
</div></div>';
} elseif ($action == "edit") {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-box-arrow-up-right"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_navigation_agency" class="white">' . $plugin_language[ 'title' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_header' ] . '</li>
  </ol>
</nav>
<div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_navigation_agency WHERE headerID='" . intval($_GET['headerID']) ."'"
        )
    );
    if (!empty($ds[ 'header_pic' ])) {
        $pic = '<img class="img-thumbnail" src="../' . $filepath . $ds[ 'header_pic' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }
    $height = $ds[ 'height' ];
    @$ani_height = str_replace('value="' . $ds['height'] . '"', 'value="' . $ds['height'] . '" selected="selected"', $ani);
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_navigation_agency" enctype="multipart/form-data">
<input type="hidden" name="headerID" value="' . $ds['headerID'] . '" />
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['header_upload_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="header_pic" type="file" size="40" /></em></span>
    </div>
</div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['size'].':</label>
    <div class="col-lg-3">
        <select id="ani_height" name="height" class="form-select" value="'.$height.'">'.$ani_height.'</select>
        <span class="text-muted small"><em><small>' . $plugin_language[ 'size_info' ] . '</small></em></span>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_header'].'</button>
    </div>
  </div>
</form>
</div></div>';
} elseif (isset($_POST[ "save" ])) {
 
    $height = $_POST[ 'height' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_navigation_agency` (height) values ('$height')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('header_pic');
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
                        $file = 'header-bg'.$endung;
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_navigation_agency SET header_pic='" . $file . "' WHERE headerID='" . $id . "'"
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
            redirect("admincenter.php?site=admin_navigation_agency", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_navigation_agency", "", 0);
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $height = $_POST[ "height" ];
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
 
        safe_query(
            "UPDATE " . PREFIX . "plugins_navigation_agency SET height='" . $height . "' WHERE headerID='" . $_POST[ "headerID" ] . "'");
 
        $id = $_POST[ 'headerID' ];
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('header_pic');
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
                        $file = 'header-bg'.$endung;
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_navigation_agency SET header_pic='" . $file . "' WHERE headerID='" . $id . "'"
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
            redirect("admincenter.php?site=admin_navigation_agency", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_navigation_agency", "", 0);
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_navigation_agency WHERE headerID='" . $_GET[ "headerID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_navigation_agency WHERE headerID='" . $_GET[ "headerID" ] . "'")) {
            @unlink($filepath.$data['header_pic']);
            redirect("admincenter.php?site=admin_navigation_agency", "", 0);
        } else {
            redirect("admincenter.php?site=admin_navigation_agency", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
        redirect("admincenter.php?site=admin_navigation_agency", "", 0);
    }
} else {
    echo '<div class="card">
    <div class="card-header">
        <i class="bi bi-box-arrow-up-right"></i> ' . $plugin_language[ 'title' ] . '
    </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_navigation_agency" class="white">' . $plugin_language[ 'title' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
  </ol>
</nav>
<div class="card-body">

<div class="mb-3 row">';

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_navigation_agency");
  $ds = mysqli_fetch_array($ergebnis);

if (isset($ds['headerID']) ? isset($ds['headerID']) : 0) {  
    $add = "";
} else {
    $add = '<label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8"><a href="admincenter.php?site=admin_navigation_agency&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_header' ] . '</a></div>';
}
      echo' '.$add.'
    
  </div>';
 
    echo '<form method="post" action="admincenter.php?site=admin_navigation_agency">
    <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['header'].'</b></th>
      <th><b>'.$plugin_language['size'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_navigation_agency");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
           
            echo '<tr>
           <td class="' . $td . ' col-5"><img class="img-thumbnail" align="center" src="../' . $filepath . $ds[ 'header_pic' ] . '" alt="{img}" /></td>
           <td class="' . $td . ' col-5">
            <div class="mb-3 row">
                <div class="col-auto">
                <input class="form-control" type="text" value="' . $ds[ 'height' ] .'" aria-label="Disabled input example" disabled readonly>
            </div>
           </td>
           <td class="' . $td . ' col-2"><a href="admincenter.php?site=admin_navigation_agency&amp;action=edit&amp;headerID=' . $ds[ 'headerID' ] .
                '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

      </td>
</tr>';
            $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="6">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }
 
    echo '
</table>
</form></div></div>';
}
?>