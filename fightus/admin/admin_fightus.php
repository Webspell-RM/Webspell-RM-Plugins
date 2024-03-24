<?php
/*#################################################################\
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
\##################################################################*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("admin_fightus", $plugin_path);


if (!isuseradmin($userID) || mb_substr(basename($_SERVER['REQUEST_URI']), 0, 15) != "admincenter.php") {
    die($_language->module['access_denied']);
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

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

        /*$ergebnis = safe_query("SELECT upID FROM " . PREFIX . "upcoming WHERE squad='$squadID'");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            safe_query("DELETE FROM " . PREFIX . "upcoming_announce WHERE upID='$ds[upID]'");
        }
        safe_query("DELETE FROM " . PREFIX . "upcoming WHERE squad='$squadID' ");
        
        $ergebnis = safe_query("SELECT cwID FROM " . PREFIX . "clanwars WHERE squad='$squadID'");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            safe_query("DELETE FROM " . PREFIX . "comments WHERE type='cw' AND parentID='$ds[cwID]'");
        }
        safe_query("DELETE FROM " . PREFIX . "clanwars WHERE squad='$squadID' ");
        $filepath = "../images/squadicons/";
        if (file_exists($filepath . $squadID . '.gif')) {
            unlink($filepath . $squadID . '.gif');
        }
        if (file_exists($filepath . $squadID . '.jpg')) {
            unlink($filepath . $squadID . '.jpg');
        }
        if (file_exists($filepath . $squadID . '.png')) {
            unlink($filepath . $squadID . '.png');
        }
        if (file_exists($filepath . $squadID . '_small.gif')) {
            unlink($filepath . $squadID . '_small.gif');
        }
        if (file_exists($filepath . $squadID . '_small.jpg')) {
            unlink($filepath . $squadID . '_small.jpg');
        }
        if (file_exists($filepath . $squadID . '_small.png')) {
            unlink($filepath . $squadID . '_small.png');
        }*/
    } else {
        echo $plugin_language['transaction_invalid'];
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
    } else {
        echo $plugin_language['transaction_invalid'];
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


            if(isset($_POST['spielart'])){ $spielart = $_POST['spielart']; }else{ $spielart = ''; }
            if(isset($_POST['gametype'])){ $gametype = $_POST['gametype']; }else{ $gametype = ''; }
            if(isset($_POST['matchtype'])){ $matchtype = $_POST['matchtype']; }else{ $matchtype = ''; }

            $squadID = $id;		
            if(!$_POST['spielart']==""){
              safe_query("DELETE FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE clanID='$squadID'");
              if(is_array($spielart)) {
	        foreach($spielart as $id) {
                  safe_query("INSERT INTO ".PREFIX."plugins_fight_us_spieleranzahl (name, clanID) values ('$id','$squadID' ) ");
	        }
              }
            }
	
            if(!$_POST['matchtype']==""){
              safe_query("DELETE FROM ".PREFIX."plugins_fight_us_matchtype WHERE clanID='$squadID'");
              if(is_array($matchtype)) {
                foreach($matchtype as $id2) {
                  safe_query("INSERT INTO ".PREFIX."plugins_fight_us_matchtype (name, clanID) values ('$id2','$squadID' ) ");
                }
              }
            }
	
            if(!$_POST['gametype']==''){
              safe_query("DELETE FROM ".PREFIX."plugins_fight_us_gametype WHERE clanID='$squadID'");
              if(is_array($gametype)) {
                foreach($gametype as $id1) {
                  safe_query("INSERT INTO ".PREFIX."plugins_fight_us_gametype (name, clanID) values ('$id1','".$_POST['squadID']."' ) ");
                }
              }
            } 

            $filepath = "../images/squadicons/";

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
            if(isset($_POST['spielart'])){ $spielart = $_POST['spielart']; }else{ $spielart = ''; }
            if(isset($_POST['gametype'])){ $gametype = $_POST['gametype']; }else{ $gametype = ''; }
            if(isset($_POST['matchtype'])){ $matchtype = $_POST['matchtype']; }else{ $matchtype = ''; }

            $squadID = $_POST['squadID'];		
            if(!$_POST['spielart']==""){
              safe_query("DELETE FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE clanID='$squadID'");
              if(is_array($spielart)) {
	        foreach($spielart as $id) {
                  safe_query("INSERT INTO ".PREFIX."plugins_fight_us_spieleranzahl (name, clanID) values ('$id','$squadID' ) ");
	        }
              }
            }
	
            if(!$_POST['matchtype']==""){
              safe_query("DELETE FROM ".PREFIX."plugins_fight_us_matchtype WHERE clanID='$squadID'");
              if(is_array($matchtype)) {
                foreach($matchtype as $id2) {
                  safe_query("INSERT INTO ".PREFIX."plugins_fight_us_matchtype (name, clanID) values ('$id2','$squadID' ) ");
                }
              }
            }
	
            if(!$_POST['gametype']==''){
              safe_query("DELETE FROM ".PREFIX."plugins_fight_us_gametype WHERE clanID='$squadID'");
              if(is_array($gametype)) {
                foreach($gametype as $id1) {
                  safe_query("INSERT INTO ".PREFIX."plugins_fight_us_gametype (name, clanID) values ('$id1','".$_POST['squadID']."' ) ");
                }
              }
            } 

            $games = implode(";", $_POST['games']);
            safe_query(
                "UPDATE " . PREFIX . "plugins_squads SET gamesquad='" . $_POST['gamesquad'] . "', games='" . $games .
                "', name='" . $_POST['name'] . "', info='" . $_POST['message'] . "' WHERE squadID='" .
                $_POST['squadID'] . "' "
            );
            $filepath = "../images/squadicons/";
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
  echo'
    <div class="card">
      <div class="card-header">
        <i class="bi bi-people-fill"></i> '.$plugin_language['squads'].'
      </div>
      <div class="card-body">
        <a href="admincenter.php?site=admin_fightus" class="white">'.$plugin_language['squads'].'</a> &raquo; '.$plugin_language['add_squad'].'<br><br>
  ';
  $filepath = "../images/squadicons/";
  $sql = safe_query("SELECT * FROM " . PREFIX . "plugins_squads ORDER BY name");
  $games = '<select class="form-control" name="games[]">';
  while ($db = mysqli_fetch_array($sql)) {
    $games .= '<option value="' . htmlspecialchars($db['name']) . '">' . htmlspecialchars($db['name']) .'</option>';
  }
  $games .= '</select>';
  $CAPCLASS = new \webspell\Captcha;
  $CAPCLASS->createTransaction();
  $hash = $CAPCLASS->getHash();

  echo '
        <form method="post" id="post" name="post" action="admincenter.php?site=admin_fightus" enctype="multipart/form-data" onsubmit="return chkFormular();">
        <div class="row">
          <div class="col-md-6">
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['icon_upload'].'</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em><input class="btn btn-info" name="icon" type="file" size="40" /></em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['icon_upload_small'].'</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em><input class="btn btn-info" name="icon_small" type="file" size="40" /> <small>('.$plugin_language['icon_upload_info'].')</small></em></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['squad_name'].'</label>
                <div class="col-md-9">
                  <span><em><input class="form-control" type="text" name="name" /></em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['squad_type'].'</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em><p class="form-control-static"><input onclick="document.getElementById(\'games\').style.display = \'block\'" type="radio" name="gamesquad" value="1" checked="checked" /> '.$plugin_language['gaming_squad'].' &nbsp; <input onclick="document.getElementById(\'games\').style.display = \'none\'" type="radio" name="gamesquad" value="0" /> '.$plugin_language['non_gaming_squad'].'</p></em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['game'].'</label>
                <div class="col-md-9">
                  <span><em>'.$games.'</em></span>
                </div>
              </div>
            </div>
          </div>
        </div>  
          <div class="col-md-12">
            <div class="row bt">
              <div class="form-group row">
                <div class="col-md-4">
                  '.$plugin_language['matchtinfo'].'
                  '.$plugin_language['infosmall'].'
                  <select name="matchtype[]" multiple size="10" class="form-control">';
                    $erb = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_matchtype WHERE clanID='' ORDER by name");
                    while($dmz = mysqli_fetch_array($erb)) {
                      echo'<option value="'.$dmz['name'].'">'.$dmz['name'].'</option>';
                    }
                  echo'</select>
                </div>
                <div class="col-md-4">
                  '.$plugin_language['gametinfo'].'
                  '.$plugin_language['infosmall'].'
                  <select name="gametype[]" multiple size="10" class="form-control">';
                    $erb = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_gametype WHERE clanID=''");
                    while($dmz=mysqli_fetch_array($erb)) {
                      echo'<option value="'.$dmz['name'].'">'.$dmz['name'].'</option>';  
                    }
                  echo'</select>
                </div>
                <div class="col-md-4">
                  '.$plugin_language['spieltinfo'].'
                  '.$plugin_language['infosmall'].'
                  <select name="spielart[]" multiple size="10" class="form-control">';
                    $erg = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE clanID='' ORDER by name");
                    while($dmz = mysqli_fetch_array($erg)) {
                      echo'<option value="'.$dmz['name'].'">'.$dmz['name'].'</option>';  
                    }
                  echo'</select>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="row bt">
              <div class="col-md-12">'.$plugin_language['squad_info'].':</div>
              <div class="form-group">
                <div class="col-sm-12">
                  '.$plugin_language['info'].'<br>
                  <textarea class="ckeditor" id="ckeditor" rows="5" cols="" name="message" style="width: 100%;"></textarea>
                </div>
              </div>
              <div class="form-group">
              <div class="col-sm-12"><br>
                <input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-success" type="submit" name="save" />'.$plugin_language['add_squad'].'</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  ';
} elseif ($action == "edit") {
  echo '
    <div class="card">
      <div class="card-header">
        <i class="bi bi-people-fill"></i> '.$plugin_language['squads'].'
      </div>
      <div class="card-body">
        <a href="admincenter.php?site=admin_fightus" class="white">' . $plugin_language['squads'] .
          '</a> &raquo; '. $plugin_language['edit_squad'] .'<br><br>
  ';
  $squadID = (int) $_GET['squadID'];
  $filepath = "../images/squadicons/";

  $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='$squadID'");
  $ds = mysqli_fetch_array($ergebnis);

  $games_array = explode(";", $ds['games']);
  $sql = safe_query("SELECT * FROM " . PREFIX . "plugins_squads ORDER BY name");
  $games = '<select class="form-control" name="games[]">';
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
    $type = '<input onclick="document.getElementById(\'games\').style.display = \'block\'"
      type="radio" name="gamesquad" value="1" checked="checked" /> ' . $plugin_language['gaming_squad'] . ' &nbsp;
      <input onclick="document.getElementById(\'games\').style.display = \'none\'" type="radio"
        name="gamesquad" value="0" /> ' . $plugin_language['non_gaming_squad'];
      $display = 'block';
  } else {
    $type = '
      <input onclick="document.getElementById(\'games\').style.display = \'block\'" type="radio"
        name="gamesquad" value="1" /> ' . $plugin_language['gaming_squad'] . ' &nbsp;
      <input onclick="document.getElementById(\'games\').style.display = \'none\'" type="radio"
        name="gamesquad" value="0" checked="checked" /> ' . $plugin_language['non_gaming_squad'];
      $display = 'none';
  }

  if (!empty($ds['icon'])) {
    $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="' . $filepath . $ds['icon'] . '" alt="">';
  } else {
    $pic = $plugin_language['no_icon'];
  }
  if (!empty($ds['icon_small'])) {
    $pic_small = '<img class="img-thumbnail" style="width: 100%; max-width: 200px" src="' . $filepath . $ds['icon_small'] . '" alt="">';
  } else {
    $pic_small = $plugin_language['no_icon'];
  }

  $CAPCLASS = new \webspell\Captcha;
  $CAPCLASS->createTransaction();
  $hash = $CAPCLASS->getHash();

  echo '
        <form method="post" id="post" name="post" action="admincenter.php?site=admin_fightus" enctype="multipart/form-data" onsubmit="return chkFormular();">
        <div class="row">
          <div class="col-md-6">
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['current_icon'].':</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em>'.$pic.'</em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['current_icon_small'].':</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em>'.$pic_small.'</em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['icon_upload'].':</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em><input class="btn btn-info" name="icon" type="file" size="40" /></em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['icon_upload_small'].':</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em><input class="btn btn-info" name="icon_small" type="file" size="40" /> <small>('.$plugin_language['icon_upload_info'].')</small></em></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['squad_name'].':</label>
                <div class="col-md-9">
                  <span><em><input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" /></em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['squad_type'].':</label>
                <div class="col-md-9">
                  <span class="pull-right text-muted small"><em>'.$type.'</em></span>
                </div>
              </div>
            </div>
            <div class="row bt">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$plugin_language['game'].':</label>
                  <div class="col-md-9">
                    <span><em>'.$games.'</em></span>
                  </div>
              </div>
            </div>
          </div>
        </div>
          <div class="col-md-12">
            <div class="row bt">
              <div class="form-group row">
                <div class="col-md-4">
                  '.$plugin_language['matchtinfo'].'
                  '.$plugin_language['infosmall'].'
                  <select name="matchtype[]" multiple size="10" class="form-control">';
                    $erb = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_matchtype WHERE clanID=''");
                    while($dmz = mysqli_fetch_array($erb)) {
                      $get = mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_matchtype WHERE clanID='".$squadID."' AND name='".$dmz['name']."'"));
                      if($get) echo'<option value="'.$dmz['name'].'" selected="selected">'.$dmz['name'].'</option>';
                      else echo'<option value="'.$dmz['name'].'">'.$dmz['name'].'</option>';
                    }
                  echo'</select>
                </div>
                <div class="col-md-4">
                  '.$plugin_language['gametinfo'].'
                  '.$plugin_language['infosmall'].'
                  <select name="gametype[]" multiple size="10" class="form-control">';
                    $get = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_gametype WHERE clanID='".$squadID."'"));
                    $erb = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_gametype WHERE clanID=''");
                    while($dmz = mysqli_fetch_array($erb)) {
                      $get = mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_gametype WHERE clanID='".$squadID."' AND name='".$dmz['name']."'"));
                      if($get) echo'<option value="'.$dmz['name'].'" selected="selected">'.$dmz['name'].'</option>';
                      else echo'<option value="'.$dmz['name'].'">'.$dmz['name'].'</option>';  
                    }
                  echo'</select>
                </div>
                <div class="col-md-4">
                  '.$plugin_language['spieltinfo'].'
                  '.$plugin_language['infosmall'].'
                  <select name="spielart[]" multiple size="10" class="form-control">';
                    $get = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE clanID='".$squadID."'"));
                    $erg = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE clanID='' ORDER by name");
                    while($dmz = mysqli_fetch_array($erg)) {
                      $get = mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE clanID='".$squadID."' AND name='".$dmz['name']."'"));
                      if($get) echo'<option value="'.$dmz['name'].'" selected="selected">'.$dmz['name'].'</option>';
                      else echo'<option value="'.$dmz['name'].'">'.$dmz['name'].'</option>';  
                    }
                  echo'</select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row bt">
              <div class="col-md-12">'.$plugin_language['squad_info'].':</div>
              <div class="form-group">
                <div class="col-sm-12">
                  '.$plugin_language['info'].'<br>
                  <textarea class="ckeditor" id="ckeditor" rows="5" cols="" name="message" style="width: 100%;">'.getinput($ds['info']).'</textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12"><br>
                  <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="squadID" value="'.getforminput($squadID).'" /><button class="btn btn-success" type="submit" name="saveedit" />'.$plugin_language['edit_squad'].'</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  ';
} elseif ($action == "") {

  echo'<div class="card">

<div class="card-header">
                            <i class="bi bi-people-fill"></i> '.$plugin_language['squads'].'
                        </div>

<div class="card-body">';

    echo'
    <a href="admincenter.php?site=admin_fightus" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'fightus_squads' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_gametype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_maps" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_maps' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_matchtype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_spieleranzahl' ] . '</a>
    <br /><br />'; 

	echo'<a href="admincenter.php?site=admin_fightus&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_squad' ] . '</a><br /><br />';

	echo'<form method="post" action="admincenter.php?site=admin_fightus">
  <table class="table table-striped">
<thead>
    <tr>
      <th><b>'.$plugin_language['squad_name'].'</b></th>
      <th class="hidden-xs"><b>'.$plugin_language['squad_type'].'</b></th>
      <th class="hidden-xs"><b>'.$plugin_language['squad_info'].'</b></th>
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
            
    
            echo '<tr>
        <td><a href="../index.php?site=squads&amp;squadID='.$db['squadID'].'" target="_blank">'.getinput($db['name']).'</a></td>
        <td class="hidden-xs">'.$type.'</td>
        <td class="hidden-xs">'.$info.'</td>
        <td><a href="admincenter.php?site=admin_fightus&amp;action=edit&amp;squadID='.$db['squadID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_fightus&amp;delete=true&amp;squadID='.$db['squadID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'squads' ] . '</h5>
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
        <td><select name="sort[]">';

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
      
      $i++;
		}
	}
	
  echo'<tr>
      <td colspan="5" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <input class="btn btn-primary" type="submit" name="sortieren" value="'.$plugin_language['to_sort'].'" /></td>
    </tr>
  </table>
  </form></div></div>';


}



/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>admin_fightus_gametype<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/


/*????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\
|                      admin_fightus_gametype                       |
\------------------------------------------------------------------*/



    if(isset($_GET['action'])){
  $getaction = $_GET['action'];
}else{
  $getaction = '';
}

if(isset($_POST['fightus_gametype_save'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("
      INSERT INTO 
        ".PREFIX."plugins_fight_us_gametype ( 
          name,
          abkuerzung 
        ) values ( 
          '".mysqli_real_escape_string($_database, $_POST['name'])."', 
          '".mysqli_real_escape_string($_database, $_POST['abkuerzung'])."' 
        ) 
    ");
    header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_gametype");
  } else {
    echo $plugin_language['transaction_invalid'];
  }
}
elseif(isset($_POST['fightus_gametype_saveedit'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    if(intval($_POST['gametypeID'])){
      safe_query("
        UPDATE ".PREFIX."plugins_fight_us_gametype SET 
          name='".mysqli_real_escape_string($_database, $_POST['name'])."',
          abkuerzung='".mysqli_real_escape_string($_database, $_POST['abkuerzung'])."' 
        WHERE 
          gametypeID='".intval($_POST['gametypeID'])."'
      ");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_gametype");
    }else{

    }
  } else {
    echo $plugin_language[ 'transaction_invalid' ];
  }
}
elseif(isset($_GET['fightus_gametype_delete'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    if(intval($_GET['gametypeID'])){
      safe_query("DELETE FROM ".PREFIX."plugins_fight_us_gametype WHERE gametypeID='".intval($_GET['gametypeID'])."'");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_gametype");
    }else{

    }
  } else {
    echo $plugin_language[ 'transaction_invalid' ];
  }

}
elseif($getaction == 'admin_fightus_gametype_add') {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'
      <form class="form-horizontal" method="post" id="post" name="action" action="admincenter.php?site=admin_fightus&action=admin_fightus_gametype">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['newgamet'].'
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype">'.$plugin_language['gamet'].'</a> &raquo; '.$plugin_language['new'].'<br /><br />
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" size="30" value="" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['nameshort'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="abkuerzung" size="50" maxlength="50" value="" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                <button class="btn btn-success" type="submit" name="fightus_gametype_save"  />'.$plugin_language['save'].'</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    ';
}
elseif($getaction == 'admin_fightus_gametype_edit') {
  if(intval($_GET['gametypeID'])){
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $gametypeID =  intval($_GET['gametypeID']);
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_gametype WHERE gametypeID='".$gametypeID."'");
    $ds=mysqli_fetch_array($ergebnis);
    
    echo'
      <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_fightus&action=admin_fightus_gametype">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['gamet'].' '.$plugin_language['edit1'].'
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype">'.$plugin_language['gamet'].'</a> &raquo; '.$plugin_language['edit1'].'<br /><br />
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" size="30" value="'.$ds['name'].'" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['nameshort'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="abkuerzung" size="50" maxlength="50" value="'.$ds['abkuerzung'].'" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
              <input type="hidden" name="captcha_hash" value="'.$hash.'" />
              <input type="hidden" name="gametypeID" value="'.$gametypeID.'" />
                <button class="btn btn-success" type="submit" name="fightus_gametype_saveedit"  />'.$plugin_language['edit2'].'</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    ';     
  }
} elseif ($action == "admin_fightus_gametype") {  
    # Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("admin_fightus", $plugin_path);
  echo'
    <div class="card">
      <div class="card-header">
        <i class="bi bi-newspaper"></i> '.$plugin_language['gamet'].'
      </div>
      <div class="card-body">';
        echo'
    <a href="admincenter.php?site=admin_fightus" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_squads' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'fightus_gametype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_maps" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_maps' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_matchtype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_spieleranzahl' ] . '</a>
    <br /><br />'; 
          echo'<a type="button" class="btn btn-primary" href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype_add">' . $plugin_language[ 'fightus_gametype' ] . ' '.$plugin_language[ 'add' ].'</a> <br /><br />';
        echo'<table class="table table-striped">
          <thead>
            <th><b>'.$plugin_language['nameshort2'].'</b></th>
            <th><b>'.$plugin_language['name'].'</b></th>
            <th><b>'.$plugin_language['option'].'</b></th>
          </thead>
          <tbody>
  ';

  $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_gametype WHERE clanID='0' ORDER BY name");
  while($ds=mysqli_fetch_array($ergebnis)) {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'
            <tr>
              <td>'.$ds['abkuerzung'].'</td>
              <td>'.$ds['name'].'</td>
              <td>
                <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype_edit&amp;gametypeID='.$ds['gametypeID'].'" class="btn btn-warning" type="button">'.$plugin_language['edit1'].'</a>

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_fightus&fightus_gametype_delete=true&gametypeID='.$ds['gametypeID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'gamet' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_gametype'] . '</p>
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
  echo'
            </tbody>
          </table>
        </div>
      </div>
  ';




/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>admin_fightus_maps<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/


/*????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\
|                      admin_fightus_maps                       |
\------------------------------------------------------------------*/


}

if(isset($_POST['fightus_maps_save'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    $name = $_POST['name'];
    $error_true='';
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_maps WHERE name = '".$name."' ");
    $num = mysqli_num_rows($ergebnis);
    if($num) $error_true[]='<div>'.$plugin_language['duplimap'].'</div>';

    if(is_array($error_true)){
      echo'
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['newmap'].'
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus_maps">'.$plugin_language['map'].'</a> &raquo; '.$plugin_language['new'].'<br /><br />
            <div class="form-group">
              <div class="alert alert-danger" role="alert">';
                foreach($error_true as $err){
                  echo $err;
                }
              echo'</div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <button class="btn btn-success" type="submit" onClick="javascript:history.back()" />'.$plugin_language['back'].'</button>
              </div>
            </div>
          </div>
        </div>

      ';
    } else {
      safe_query("INSERT INTO ".PREFIX."plugins_fight_us_maps ( name ) values( '".$name."')");
      $id=mysqli_insert_id($_database);
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_maps");
    }
  } else {
    echo $plugin_language['transaction_invalid'];
  }
} elseif(isset($_POST['fightus_maps_saveedit'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    if(intval($_POST['mapID'])){
      $name = $_POST['name'];
      safe_query("
        UPDATE ".PREFIX."plugins_fight_us_maps SET 
          name='".mysqli_escape_string($_database, $_POST['name'])."'
        WHERE 
          mapID='".intval($_POST['mapID'])."'
      ");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_maps");
    } else {
    }
  } else {
    echo $plugin_language['transaction_invalid'];
  }
}
elseif(isset($_GET['fightus_maps_delete'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    if(intval($_GET['mapID'])){
      safe_query("DELETE FROM ".PREFIX."plugins_fight_us_maps WHERE mapID='".$_GET['mapID']."'");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_maps");
    }
  } else {
    echo $plugin_language['transaction_invalid'];
  }
} elseif($action=="admin_fightus_maps_add") {
  $CAPCLASS = new \webspell\Captcha;
  $CAPCLASS->createTransaction();
  $hash = $CAPCLASS->getHash();

  echo'
    <form class="form-horizontal" method="post" id="post" name="action" action="admincenter.php?site=admin_fightus&action=admin_fightus_maps_add">
      <div class="card">
        <div class="card-header">
          <i class="bi bi-globe"></i> '.$plugin_language['newmap'].'
        </div>
        <div class="card-body">
          <a href="admincenter.php?site=admin_fightus_maps">'.$plugin_language['map'].'</a> &raquo; '.$plugin_language['new'].'<br /><br />
          <div class="form-group">
            <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="name" size="30" value="" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input type="hidden" name="captcha_hash" value="'.$hash.'" />
              <button class="btn btn-success" type="submit" name="fightus_maps_save"  />'.$plugin_language['save'].'</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  ';
} elseif($action=="admin_fightus_maps_edit") {
  if(intval($_GET['mapID'])){
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $mapID=intval($_GET['mapID']);
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_maps WHERE mapID='".$mapID."'");
    $ds=mysqli_fetch_array($ergebnis);
    echo'
      <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_fightus&action=admin_fightus_maps_edit">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['map'].' '.$plugin_language['edit1'].'
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus_maps">'.$plugin_language['map'].'</a> &raquo; '.$plugin_language['edit1'].'<br /><br />
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" size="30" value="'.$ds['name'].'" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                <input type="hidden" name="mapID" value="'.$mapID.'">
                <button class="btn btn-success" type="submit" name="fightus_maps_saveedit"  />'.$plugin_language['edit2'].'</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    ';
  }
} elseif ($action == "admin_fightus_maps") {  
  
    echo'
      <div class="card">
        <div class="card-header">
          <i class="bi bi-newspaper"></i> '.$plugin_language['map'].'
        </div>
        <div class="card-body">';
        echo'
    <a href="admincenter.php?site=admin_fightus" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_squads' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_gametype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_maps" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'fightus_maps' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_matchtype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_spieleranzahl' ] . '</a>
    <br /><br />'; 
          echo'<a type="button" class="btn btn-primary" href="admincenter.php?site=admin_fightus&action=admin_fightus_maps_add">'.$plugin_language['map'].' '.$plugin_language['add'].'</a><br /><br />';

          $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_maps");
  if(mysqli_num_rows($ergebnis)){
          echo'<table class="table table-striped">
            <thead>
              <th><b>'.$plugin_language['name'].'</b></th>
              <th><b>'.$plugin_language['option'].'</b></th>
            </thead>
            <tbody>
    ';
    while($ds = mysqli_fetch_array($ergebnis)) {
      $CAPCLASS = new \webspell\Captcha;
      $CAPCLASS->createTransaction();
      $hash = $CAPCLASS->getHash();

      echo'
        <tr>
          <td><b>'.$ds['name'].'</b></td>
          <td>
            <a href="admincenter.php?site=admin_fightus&action=admin_fightus_maps_edit&amp;mapID='.$ds['mapID'].'" class="hidden-xs hidden-sm btn btn-warning" type="button">'.$plugin_language['edit1'].'</a>
            
            <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_fightus&action=admin_fightus_maps&fightus_maps_delete=true&mapID='.$ds['mapID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'map' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_map'] . '</p>
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
    echo'</table>';
  } else {
    $message = ''.$plugin_language['nomaps'].'';
  }    

}



/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>admin_fightus_maps<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/


/*????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\
|                      admin_fightus_maps                       |
\------------------------------------------------------------------*/


if(isset($_POST['fightus_matchtype_save'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("
      INSERT INTO 
        ".PREFIX."plugins_fight_us_matchtype ( 
          name
        ) values ( 
          '".mysqli_real_escape_string($_database, $_POST['name'])."'
        ) 
    ");
    header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_matchtype");
  } else {
    echo $plugin_language[ 'transaction_invalid' ];
  }
}
elseif(isset($_POST['fightus_matchtype_saveedit'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    if(intval($_POST['matchtypeID'])){
      safe_query("
        UPDATE ".PREFIX."plugins_fight_us_matchtype SET 
          name='".mysqli_real_escape_string($_database, $_POST['name'])."' 
        WHERE 
          matchtypeID='".intval($_POST['matchtypeID'])."'
      ");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_matchtype");
    }else{

    }
  } else {
    echo $plugin_language[ 'transaction_invalid' ];
  }
}
elseif(isset($_GET['fightus_matchtype_delete'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    if(intval($_GET['matchtypeID'])){
      safe_query("DELETE FROM ".PREFIX."plugins_fight_us_matchtype WHERE matchtypeID='".intval($_GET['matchtypeID'])."'");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_matchtype");
    }else{

    }
  } else {
    echo $plugin_language[ 'transaction_invalid' ];
  }

}
elseif($getaction == 'admin_fightus_matchtype_add') {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'
      <form class="form-horizontal" method="post" id="post" name="action" action="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['newmatcht'].' 
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus_matchtype">'.$plugin_language['matcht'].'</a> &raquo; '.$plugin_language['new'].'<br /><br />
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['new'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" size="30" value="" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                <button class="btn btn-success" type="submit" name="fightus_matchtype_save"  />'.$plugin_language['save'].'</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    ';
}
elseif($getaction == 'admin_fightus_matchtype_edit') {
  if(intval($_GET['matchtypeID'])){
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $matchtypeID =  intval($_GET['matchtypeID']);
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_matchtype WHERE matchtypeID='".$matchtypeID."'");
    $ds=mysqli_fetch_array($ergebnis);
    
    echo'
      <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['matcht'].' '.$plugin_language['edit1'].'
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus_matchtype">'.$plugin_language['matcht'].'</a> &raquo; '.$plugin_language['edit1'].'<br /><br />
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" size="30" value="'.$ds['name'].'" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                <input type="hidden" name="matchtypeID" value="'.$matchtypeID.'">
                <button class="btn btn-success" type="submit" name="fightus_matchtype_saveedit"  />'.$plugin_language['edit2'].'</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    ';     
  }
} elseif ($action == "admin_fightus_matchtype") {  
  echo'
    <div class="card">
      <div class="card-header">
        <i class="bi bi-newspaper"></i> '.$plugin_language['matcht'].'
      </div>
      <div class="card-body">';
        echo'
    <a href="admincenter.php?site=admin_fightus" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_squads' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_gametype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_maps" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_maps' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'fightus_matchtype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_spieleranzahl' ] . '</a>
    <br /><br />'; 
          echo'<a type="button" class="btn btn-primary" href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype_add">'.$plugin_language['matcht'].' '.$plugin_language['add'].'</a> <br /><br />';

        echo'<table class="table table-striped">
          <thead>
            <th><b>'.$plugin_language['name'].'</b></th>
            <th><b>'.$plugin_language['option'].'</b></th>
          </thead>
          <tbody>
  ';

  $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_matchtype WHERE clanID='0' ORDER BY name");
  while($ds=mysqli_fetch_array($ergebnis)) {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'
            <tr>
              <td>'.$ds['name'].'</td>
              <td>
                <a href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype_edit&amp;matchtypeID='.$ds['matchtypeID'].'" class="hidden-xs hidden-sm btn btn-warning" type="button">'.$plugin_language['edit1'].'</a>
                
                <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype&fightus_matchtype_delete=true&matchtypeID='.$ds['matchtypeID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'matcht' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_matchtype'] . '</p>
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
  echo'
            </tbody>
          </table>
        </div>
      </div>
  ';

}



/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>admin_fightus_spieleranzahl<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/


/*????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\
|                      admin_fightus_spieleranzahl                  |
\------------------------------------------------------------------*/


if(isset($_POST['fightus_spieleranzahl_save'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("
      INSERT INTO 
        ".PREFIX."plugins_fight_us_spieleranzahl ( 
          name
        ) values ( 
          '".mysqli_real_escape_string($_database, $_POST['name'])."'
        ) 
    ");
    header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl");
  } else {
    echo $plugin_language['transaction_invalid'];
  }
}
elseif(isset($_POST['fightus_spieleranzahl_saveedit'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    if(intval($_POST['spielanzahlID'])){
      safe_query("
        UPDATE ".PREFIX."plugins_fight_us_spieleranzahl SET 
          name='".mysqli_real_escape_string($_database, $_POST['name'])."' 
        WHERE 
          spielanzahlID='".intval($_POST['spielanzahlID'])."'
      ");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl");
    }else{

    }
  } else {
    echo $plugin_language['transaction_invalid'];
  }
}
elseif(isset($_GET['fightus_spieleranzahl_delete'])) {
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    if(intval($_GET['spielanzahlID'])){
      safe_query("DELETE FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE spielanzahlID='".intval($_GET['spielanzahlID'])."'");
      header("Location: admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl");
    }else{

    }
  } else {
    echo $plugin_language[ 'transaction_invalid' ];
  }

}
elseif($getaction == 'admin_fightus_spieleranzahl_add') {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'
      <form class="form-horizontal" method="post" id="post" name="action" action="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['newspielt'].'
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl">'.$plugin_language['spielt'].'</a> &raquo; '.$plugin_language['new'].'<br /><br />
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" size="30" value="" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                <button class="btn btn-success" type="submit" name="fightus_spieleranzahl_save"  />'.$plugin_language['save'].'</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    ';
}
elseif($getaction == 'admin_fightus_spieleranzahl_edit') {
  if(intval($_GET['spielanzahlID'])){
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $spielanzahlID =  intval($_GET['spielanzahlID']);
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE spielanzahlID='".$spielanzahlID."'");
    $ds=mysqli_fetch_array($ergebnis);
    
    echo'
      <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-globe"></i> '.$plugin_language['spielt'].' '.$plugin_language['edit1'].'
          </div>
          <div class="card-body">
            <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl">'.$plugin_language['spielt'].'</a> &raquo; '.$plugin_language['edit1'].'<br /><br />
            <div class="form-group">
              <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" size="30" value="'.$ds['name'].'" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                <input type="hidden" name="spielanzahlID" value="'.$spielanzahlID.'">
                <button class="btn btn-success" type="submit" name="fightus_spieleranzahl_saveedit"  />'.$plugin_language['edit2'].'</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    ';     
  }
} elseif ($action == "admin_fightus_spieleranzahl") {  
  echo'
    <div class="card">
      <div class="card-header">
        <i class="bi bi-newspaper"></i> '.$plugin_language['spielt'].'
      </div>
      <div class="card-body">';
        echo'
    <a href="admincenter.php?site=admin_fightus" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_squads' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_gametype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_gametype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_maps" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_maps' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_matchtype" class="btn btn-primary" type="button">' . $plugin_language[ 'fightus_matchtype' ] . '</a>
    <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'fightus_spieleranzahl' ] . '</a>
    <br /><br />'; 
          echo'<a type="button" class="btn btn-primary" href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl_add">'.$plugin_language['spielt'].' '.$plugin_language['add'].'</a><br /><br />'; 
       echo'<table class="table table-striped">
          <thead>
            <th><b>'.$plugin_language['name'].'</b></th>
            <th><b>'.$plugin_language['option'].'</b></th>
          </thead>
          <tbody>
  ';

  $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_spieleranzahl WHERE clanID='0' ORDER BY name");
  while($ds=mysqli_fetch_array($ergebnis)) {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'
            <tr>
              <td>'.$ds['name'].'</td>
              <td>
                <a href="admincenter.php?site=admin_fightus&action=admin_fightus_spieleranzahl_edit&amp;spielanzahlID='.$ds['spielanzahlID'].'" class="btn btn-warning" type="button">'.$plugin_language['edit2'].'</a>
                <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admin_fightus&action=admin_fightus_spieleranzahl&fightus_spieleranzahl_delete=true&spielanzahlID='.$ds['spielanzahlID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'spielt' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_spielanzahl'] . '</p>
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
            </tr>
    ';
  }
  echo'
            </tbody>
          </table>
        </div>
      </div>
  ';

}

?>