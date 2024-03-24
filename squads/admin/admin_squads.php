<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("squads", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='squads'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

$filepath = $plugin_path."images/squadicons/";

if (isset($_GET['delete'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
        $squadID = $_GET['squadID'];
        $ergebnis = safe_query("SELECT userID FROM " . PREFIX . "plugins_squads_members WHERE squadID='$squadID'");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $squads = mysqli_num_rows(
                safe_query(
                    "SELECT userID FROM " . PREFIX . "plugins_squads_members WHERE userID='$ds[userID]'"
                )
            );
            if ($squads < 2 && !issuperadmin($ds['userID'])) {
                safe_query("DELETE FROM " . PREFIX . "user_groups WHERE userID='$ds[userID]'");
            }
        }
        safe_query("DELETE FROM " . PREFIX . "plugins_squads_members WHERE squadID='$squadID' ");
        safe_query("DELETE FROM " . PREFIX . "plugins_squads WHERE squadID='$squadID' ");
        redirect("admincenter.php?site=admin_squads", "", 0);        
    } else {
        redirect("admincenter.php?site=admin_squads", $plugin_language[ 'transaction_invalid' ], 3);
    }
}

if (isset($_GET['del_member'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $id = $_GET[ 'id' ];
        $squadID = $_GET[ 'squadID' ];
        $squads = mysqli_num_rows(safe_query("SELECT userID FROM " . PREFIX . "plugins_squads_members WHERE userID='$id'"));
        if ($squads < 2 && !issuperadmin($id)) {
            safe_query("DELETE FROM " . PREFIX . "user_groups WHERE userID='$id'");
        }

        safe_query("DELETE FROM " . PREFIX . "plugins_squads_members WHERE userID='$id' AND squadID='$squadID'");
        redirect("admincenter.php?site=admin_squads", "", 0);
    } else {
        redirect("admincenter.php?site=admin_squads", $plugin_language[ 'transaction_invalid' ], 3);
    }
}

if (isset($_POST['sortieren'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        $sort = $_POST['sort'];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_squads SET sort='$sorter[1]' WHERE squadID='$sorter[0]' ");
            }
        }

        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_squads_members SET sort='$sorter[1]' WHERE sqmID='$sorter[0]' ");
            }
        }

    } else {
        echo $plugin_language['transaction_invalid'];
    }
}

if (isset($_POST[ 'squads_settings_save' ])) {  

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_squads_settings
            SET
                squads='" . $_POST[ 'squads' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_squads", "", 0);
    } else {
        redirect("admincenter.php?site=admin_squads", $plugin_language[ 'transaction_invalid' ], 3);
    }
}

if (isset($_POST['save'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(array('name'))) {
            $games = implode(";", $_POST['games']);
            safe_query(
                "INSERT INTO " . PREFIX . "plugins_squads ( gamesquad, games, name, info, sort ) VALUES ( '" .
                $_POST['gamesquad'] . "', '" . $games . "', '" . $_POST['name'] . "', '" . $_POST['message'] .
                "', '1' )"
            );

            $id = mysqli_insert_id($_database);

            $errors = array();

            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('icon');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
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
                            $file = $id . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_squads SET icon='" . $file . "' WHERE squadID='" . $id . "'"
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

            $upload = new \webspell\HttpUpload('icon_small');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
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
                            $file = $id . '_small' . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_squads SET icon_small='" . $file .
                                    "' WHERE squadID='" . $id . "'"
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
            }
        } else {
            echo $plugin_language['information_incomplete'];
        }
    } else {
        echo $plugin_language['transaction_invalid'];
    }
}

if (isset($_POST['saveedit'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(array('name'))) {
            $games = implode(";", $_POST['games']);
            safe_query(
                "UPDATE " . PREFIX . "plugins_squads SET gamesquad='" . $_POST['gamesquad'] . "', games='" . $games .
                "', name='" . $_POST['name'] . "', info='" . $_POST['message'] . "' WHERE squadID='" .
                $_POST['squadID'] . "' "
            );
            
            $id = $_POST['squadID'];

            $errors = array();

            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('icon');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
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
                            $file = $id . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_squads SET icon='" . $file . "' WHERE squadID='" . $id . "'"
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

            $upload = new \webspell\HttpUpload('icon_small');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
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
                            $file = $id . '_small' . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_squads SET icon_small='" . $file .
                                    "' WHERE squadID='" . $id . "'"
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
            }
        } else {
            echo $plugin_language['information_incomplete'];
        }
    } else {
        echo $plugin_language['transaction_invalid'];
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}

if ($action == "add") {

  echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-person-fill-friends"></i> '.$plugin_language['squads'].'
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_squads">' . $plugin_language['squads'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['add_squad'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

	$filepath = "../images/squadicons/";
    $sql = safe_query("SELECT * FROM " . PREFIX . "plugins_games_pic ORDER BY name");
    $games = '<select class="form-select" name="games[]">';
    while ($db = mysqli_fetch_array($sql)) {
        $games .= '<option value="' . htmlspecialchars($db['name']) . '">' . htmlspecialchars($db['name']) .
        '</option>';
    }
    $games .= '</select>';
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
  
	echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_squads" enctype="multipart/form-data"
onsubmit="return chkFormular();">
    <div class="row">
    <div class="col-md-6">


    <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['icon_upload'].':</label>
    <div class="col-md-8">
      <input class="btn btn-info" name="icon" type="file" size="40" />
    </div>
    </div>

    <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['icon_upload_small'].':</label>
    <div class="col-md-8">
      <input class="btn btn-info" name="icon_small" type="file" size="40" /><br><small>('.$plugin_language['icon_upload_info'].')</small>
    </div>
    </div>


  </div>
  <div class="col-md-6">

  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['squad_name'].':</label>
    <div class="col-md-8">
      <input class="form-control" type="text" name="name" />
    </div>
  </div>

  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['squad_type'].':</label>
    <div class="col-md-8 form-check form-switch" style="padding: 0px 43px;">
      
        <input class="form-check-input" type="radio" name="gamesquad" value="1" checked="checked" />&nbsp;&nbsp;'.$plugin_language['gaming_squad'].' <br><br> 
        <input class="form-check-input" type="radio" name="gamesquad" value="0" />&nbsp;&nbsp;'.$plugin_language['non_gaming_squad'].'
    </div>
  </div>

<div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['game'].':</label>
    <div class="col-md-8">
      '.$games.'
    </div>
  </div>

</div>
 
<div class="col-sm-12">
        '.$plugin_language['squad_info'].':
        <textarea class="ckeditor" id="ckeditor" rows="5" cols="" name="message" style="width: 100%;"></textarea>
    </div>

<div class="col-sm-12"><br>
<input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-success" type="submit" name="save" />'.$plugin_language['add_squad'].'</button>
    </div>
  </div>
  
  </form></div>
  </div>';
} elseif ($action == "edit") {
    echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-person-fill-friends"></i> '.$plugin_language['squads'].'
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_squads">' . $plugin_language['squads'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_squad'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    $squadID = (int) $_GET['squadID'];

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='$squadID'");
    $ds = mysqli_fetch_array($ergebnis);

    $games_array = explode(";", $ds['games']);
    $sql = safe_query("SELECT * FROM " . PREFIX . "plugins_games_pic ORDER BY name");
    $games = '<select class="form-select" name="games[]">';
    while ($db = mysqli_fetch_array($sql)) {
        $selected = '';
        if ($db['name'] == $ds['games']) {
            $selected = ' selected="selected"';
        }
        $games .= '<option value="' . htmlspecialchars($db['name']) . '"' . $selected . '>' .
        htmlspecialchars($db['name']) . '</option>';
    }
    $games .= '</select>';

    if ($ds['gamesquad']) {
        $type = '<input class="form-check-input" type="radio" name="gamesquad" value="1" checked="checked" />&nbsp;&nbsp;' . $plugin_language['gaming_squad'] . '  <br><br>
                 <input class="form-check-input" type="radio" name="gamesquad" value="0" />&nbsp;&nbsp;' . $plugin_language['non_gaming_squad'];
        $display = 'block';
    } else {
        $type = '<input class="form-check-input" type="radio" name="gamesquad" value="1" />&nbsp;&nbsp;' . $plugin_language['gaming_squad'] . ' <br><br>
                 <input class="form-check-input" type="radio" name="gamesquad" value="0" checked="checked" />&nbsp;&nbsp;' . $plugin_language['non_gaming_squad'];
        $display = 'none';
    }

    if (!empty($ds['icon'])) {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 200px" src="../' . $filepath . $ds['icon'] . '" alt="">';
    } else {
        #$pic = $plugin_language['no_icon'];
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 200px" src="../' . $filepath . '/no-image.jpg" alt="">';
    }
    if (!empty($ds['icon_small'])) {
        $pic_small = '<img class="img-thumbnail" style="width: 100%; max-width: 200px" src="../' . $filepath . $ds['icon_small'] . '" alt="">';
    } else {
        #$pic_small = $plugin_language['no_icon'];
        $pic_small = '<img class="img-thumbnail" style="width: 100%; max-width: 200px" src="../' . $filepath . '/no-image.jpg" alt="">';
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
  
	echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_squads" enctype="multipart/form-data"
    onsubmit="return chkFormular();">
    
	<div class="row">
<div class="col-md-6">
    <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['current_icon'].':</label>
    <div class="col-md-8">
      '.$pic.'
    </div>
  </div>
  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['current_icon'].':</label>
    <div class="col-md-8">
      '.$pic_small.'
    </div>
  </div>
  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['icon_upload'].':</label>
    <div class="col-md-8">
      <input class="btn btn-info" name="icon" type="file" size="40" />
    </div>
  </div>
  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['icon_upload_small'].':</label>
    <div class="col-md-8">
      <input class="btn btn-info" name="icon_small" type="file" size="40" /><br><small>('.$plugin_language['icon_upload_info'].')</small>
    </div>
  </div>


</div>
<div class="col-md-6">


  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['squad_name'].':</label>
    <div class="col-md-8">
      <input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" />
    </div>
  </div>

  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['squad_type'].':</label>
    <div class="col-md-8 form-check form-switch" style="padding: 0px 43px;">
      '.$type.'
    </div>
  </div>

  <div class="mb-3 row bt">
    <label class="col-md-4 control-label">'.$plugin_language['game'].':</label>
    <div class="col-md-8">
      '.$games.'
    </div>
  </div>


</div>
 
<div class="col-sm-12">
        '.$plugin_language['squad_info'].':
        <textarea class="ckeditor" id="ckeditor" rows="5" cols="" name="message" style="width: 100%;">'.getinput($ds['info']).'</textarea>
    </div>

<div class="col-sm-12"><br>
      <input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <input type="hidden" name="squadID" value="'.getforminput($squadID).'" />
      <button class="btn btn-warning" type="submit" name="saveedit" />'.$plugin_language['edit_squad'].'</button>
    </div>
  </div>
  
  </form></div>
  </div>';

} elseif ($action == "settings") {

echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-person-fill-friends"></i> '.$plugin_language['squads_members'].'
        </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_squads">' . $plugin_language[ 'squads_members' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>
        <div class="card-body">';


    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_squads_settings");
    $ds = mysqli_fetch_array($settings);

    
    
    if ($ds['squads']) {
        $type = '<div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
            <input class="form-check-input" type="radio" name="squads" value="1" checked="checked" />&nbsp;&nbsp;' . $plugin_language['squads_view_one'] . '</div>
        <div class="col-md-8">
            <div class="alert alert-success" role="alert">
                <button type="button" class="btn btn btn-info" data-toggle="popover" data-placement="top" data-img="../includes/plugins/squads/images/widget_squads_content_1.jpg" title="Widget 1" >' . $plugin_language['squads_view_one'] . '</button>
            </div>
        </div>

        <div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
            <input class="form-check-input" type="radio" name="squads" value="0" />&nbsp;&nbsp;' . $plugin_language['squads_view_two'].'
        </div>
        <div class="col-md-8">
            <div class="alert alert-success" role="alert">
            <button type="button" class="btn btn btn-info" data-toggle="popover" data-placement="top" data-img="../includes/plugins/squads/images/widget_squads_content_2.jpg" title="Widget 1" >' . $plugin_language['squads_view_two'] . '</button>                
            </div>
        </div>';
    } else {
        $type = '<div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
            <input class="form-check-input" type="radio" name="squads" value="1" />&nbsp;&nbsp;' . $plugin_language['squads_view_one'] . '</div>
        <div class="col-md-8">
            <div class="alert alert-success" role="alert">
                <button type="button" class="btn btn btn-info" data-toggle="popover" data-placement="top" data-img="../includes/plugins/squads/images/widget_squads_content_1.jpg" title="Widget 1" >' . $plugin_language['squads_view_one'] . '</button>
            </div>
        </div>
        <div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
                 <input class="form-check-input" type="radio" name="squads" value="0" checked="checked" />&nbsp;&nbsp;' . $plugin_language['squads_view_two'].'
        </div>
        <div class="col-md-8">
            <div class="alert alert-success" role="alert">
                <button type="button" class="btn btn btn-info" data-toggle="popover" data-placement="top" data-img="../includes/plugins/squads/images/widget_squads_content_2.jpg" title="Widget 1" >' . $plugin_language['squads_view_two'] . '</button>
            </div>
        </div>';
    }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'<form method="post" action="admincenter.php?site=admin_squads" enctype="multipart/form-data" onsubmit="return chkFormular();">
    <div class="row">
        <div class="col-md-3">
            '.$plugin_language['squads_view'].':
        </div>
        <div class="col-md-6 row">
            '.$type.'
        </div>
    </div>       
    <br>
    <div class="mb-3">
        <input type="hidden" name="captcha_hash" value="'.$hash.'"> 
        <button class="btn btn-primary" type="submit" name="squads_settings_save">'.$plugin_language['update'].'</button>
    </div>

</form>
</div>
</div>';

} else {

  echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-person-fill-friends"></i> '.$plugin_language['squads_members'].'
        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'squads_members' ] . '</li>
  </ol>
</nav>

<div class="card-body">

<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_squads&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_squad' ] . '</a>
      <a href="admincenter.php?site=users" class="btn btn-primary" type="button">' . $plugin_language[ 'new_users' ] . '</a>
      <a href="admincenter.php?site=admin_squads&amp;action=settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>';


   #$squads = safe_query("SELECT * FROM " . PREFIX . "plugins_squads ORDER BY sort");
   # echo '<form method="post" action="admincenter.php?site=admin_squads">';
   # while ($ds = mysqli_fetch_array($squads)) {


###########################################


	echo'<form method="post" action="admincenter.php?site=admin_squads">
  <table class="table">
<thead>
    <tr>
      <th><b>'.$plugin_language['squad_name'].'</b></th>
      <th><b>'.$plugin_language['squad_type'].'</b></th>
      <th><b>'.$plugin_language['squad_info'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </tr></thead>';

	$squads = safe_query("SELECT * FROM " . PREFIX . "plugins_squads ORDER BY sort");
    $anzsquads = mysqli_num_rows($squads);
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if ($anzsquads) {
        $i = 1;
        while ($db = mysqli_fetch_array($squads)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $games = explode(";", $db['games']);
            $games = implode(", ", $games);
            if ($games) {
                $games = "(" . $games . ")";
            }
            if ($db['gamesquad']) {
                $type = $plugin_language['gaming_squad'] . '<br /><small>' . $games . '</small>';
            } else {
                $type = $plugin_language['non_gaming_squad'];
            }

            $info = $db[ 'info' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($info);
            $info = $translate->getTextByLanguage($info);

            echo '<tr class="table-secondary">
                <td><a href="../index.php?site=squads&amp;squadID='.$db['squadID'].'" target="_blank">'.getinput($db['name']).'</a></td>
                <td class="hidden-xs">'.$type.'</td>
                <td class="hidden-xs">'.$info.'</td>
                <td><a href="admincenter.php?site=admin_squads&amp;action=edit&amp;squadID='.$db['squadID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>


            <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_squads&amp;delete=true&amp;squadID='.$db['squadID'].'&amp;captcha_hash=' . $hash . '">
                ' . $plugin_language['delete'] . '
                </button>
                <!-- Button trigger modal END-->

                <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">'.$db['name'].'</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>

      <div class="modal-body">
      <p>' . $plugin_language['really_delete'] . '</p>
      
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

                
           
               
<td align="right"><select name="sort[]">';

            for ($j = 1; $j <= $anzsquads; $j++) {
                if ($db['sort'] == $j) {
                    echo '<option value="' . $db['squadID'] . '-' . $j . '" selected="selected">' . $j . '</option>';
                } else {
                    echo '<option value="' . $db['squadID'] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
        </td>
      </tr>';


  #######################################################################



















        /*echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-person-fills"></i> ' . $ds[ 'name' ] . ' <span class="small"><em>'.$plugin_language['squads_members'].'</em></span>
        </div>
            
        <div class="card-body">
        <table class="table table-striped">    
        <thead>';*/

        $members = safe_query(
            "SELECT * FROM " . PREFIX . "plugins_squads_members WHERE squadID='" . $db[ 'squadID' ] . "' ORDER BY sort"
        );
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(squadID) as cnt
                FROM " . PREFIX . "plugins_squads_members
                WHERE squadID='" . $db[ 'squadID' ] . "'"
            )
        );
        $anzmembers = $tmp[ 'cnt' ];

        echo '<tr>
          <th>' . $plugin_language[ 'nickname' ] . ':</th>
          <th>' . $plugin_language[ 'position' ] . ':</th>
          <th>' . $plugin_language[ 'activity' ] . ':</th>
          <th>' . $plugin_language[ 'actions' ] . ':</th>
          <th>' . $plugin_language[ 'sort' ] . ':</th>
            </tr></thead>
          <tbody>';
        
        $i = 1;
        while ($dm = mysqli_fetch_array($members)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $nickname = '<a href="../index.php?site=profile&amp;id=' . $dm[ 'userID' ] . '" target="_blank">' .
                strip_tags(stripslashes(getnickname($dm[ 'userID' ]))) . '</a>';
            if ($dm[ 'activity' ]) {
                $activity = '<font color="green">' . $plugin_language[ 'active' ] . '</font>';
            } else {
                $activity = '<font color="red">' . $plugin_language[ 'inactive' ] . '</font>';
            }
            $referer = "admincenter.php?site=admin_squads";
            echo '<tr>
                <td>' . $nickname . '</td>
                <td>' . $dm[ 'position' ] . '</td>
                <td>' . $activity . '</td>
                <td>

                <a href="admincenter.php?site=user_rights&amp;action=edit&amp;id=' . $dm[ 'userID' ] . '&amp;referer=' . urlencode($referer) . '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

                <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_squads&amp;del_member=true&amp;id=' . $dm[ 'userID' ] . '&amp;squadID=' . $dm[ 'squadID' ] . '&amp;captcha_hash=' . $hash . '">
                    ' . $plugin_language['delete'] . '
                    </button>

                    <!-- Button trigger modal END-->
                   


  </td> 
<!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language['members'] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>

      <div class="modal-body">
      <p>' . $plugin_language['really_members_delete'] . '</p>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->
         
                
                    <td align="right"><select name="sort[]">';
                        for ($j = 1; $j <= $anzmembers; $j++) {
                            if ($dm[ 'sort' ] == $j) {
                                echo '<option value="' . $dm[ 'sqmID' ] . '-' . $j . '" selected="selected">' . $j . '</option>';
                            } else {
                                echo '<option value="' . $dm[ 'sqmID' ] . '-' . $j . '">' . $j . '</option>';
                            }
                        }
                        echo '</select></td>
                    </tr>';

                $i++;
        }


        #echo '<div align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
        #'" /><input type="submit" name="sortieren" class="btn btn-primary" value="' . $plugin_language[ 'to_sort' ] . '" /></div>';
    }
    echo '
        </td>
    </tr>';

       $i++;
        }
    #}
    
  echo'<tr>
      <td colspan="5" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <input class="btn btn-primary" type="submit" name="sortieren" value="'.$plugin_language['to_sort'].'" /></td>
    </tr>
  </table>
  </form>';
}