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
$plugin_language = $pm->plugin_language("games", $plugin_path);

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_links WHERE modulname='ac_games'");
while ($db = mysqli_fetch_array($ergebnis)) {
  $accesslevel = 'is' . $db['accesslevel'] . 'admin';

  if (!$accesslevel($userID) || mb_substr(basename($_SERVER['REQUEST_URI']), 0, 15) != "admincenter.php") {
    die($plugin_language['access_denied']);
  }
}

$filepath = $plugin_path . "images/games/";

if (isset($_GET['action'])) {
  $action = $_GET['action'];
} else {
  $action = '';
}

if ($action == "add") {
  $CAPCLASS = new \webspell\Captcha;
  $CAPCLASS->createTransaction();
  $hash = $CAPCLASS->getHash();
  echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-controller"></i> ' . $plugin_language['games'] . '
        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_games_pic">' . $plugin_language['games'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['add_game'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

  echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_games_pic" enctype="multipart/form-data">
	<div class="mb-3 row">
    <label class="col-md-2 control-label">' . $plugin_language['game_icon'] . ':</label>
    <div class="col-md-8">
      <input class="btn btn-info" name="icon" class="form-control-file" type="file" size="40" />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-md-2 control-label">' . $plugin_language['game_name'] . ':</label>
    <div class="col-md-8">
      <input class="form-control" type="text" name="name" maxlength="255" />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-md-2 control-label">' . $plugin_language['game_tag'] . ':</label>
    <div class="col-md-8">
      <input class="form-control" type="text" name="tag" size="7" />
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-md-offset-2 col-md-10">
		<input type="hidden" name="captcha_hash" value="' . $hash . '" />
		<button class="btn btn-success" type="submit" name="save"  /><i class="bi bi-save"></i> ' . $plugin_language['add'] . '</button>
    </div>
  </div>
  </form>
  </div>
  </div>';
} elseif ($action == "edit") {
  $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_games_pic WHERE gameID='" . $_GET["gameID"] . "'"));

  if (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.jpg')) {
    $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.jpg" alt="">';
  } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.jpeg')) {
    $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.jpeg" alt="">';
  } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.png')) {
    $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.png" alt="">';
  } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.gif')) {
    $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.gif" alt="">';
  } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.avif')) {
    $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.avif" alt="">';
  } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.webp')) {
    $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.webp" alt="">';
  } else {
    $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/no-image.jpg" alt="">';
  }

  $CAPCLASS = new \webspell\Captcha;
  $CAPCLASS->createTransaction();
  $hash = $CAPCLASS->getHash();

  echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-controller"></i> ' . $plugin_language['games'] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_games_pic">' . $plugin_language['games'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_game'] . '</li>
  </ol>
</nav>
     <div class="card-body">
';

  echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_games_pic" enctype="multipart/form-data">
  <input type="hidden" name="gameID" value="' . $ds['gameID'] . '" />
  
  <div class="mb-3 row">
    <label class="col-md-2 control-label">' . $plugin_language['present_icon'] . ':</label>
    <div class="col-md-8">
      <p class="form-control-static">' . $gameicon . '</p>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-md-2 control-label">' . $plugin_language['game_icon'] . ':</label>
    <div class="col-md-8">
      <input class="btn btn-info" name="icon" class="form-control-file" type="file" size="40" />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-md-2 control-label">' . $plugin_language['game_name'] . ':</label>
    <div class="col-md-8">
      <input class="form-control" type="text" name="name" maxlength="255" value="' . getinput($ds['name']) . '" />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-md-2 control-label">' . $plugin_language['game_tag'] . ':</label>
    <div class="col-md-8">
      <input class="form-control" type="text" name="tag" size="7" value="' . getinput($ds['tag']) . '" />
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-md-offset-2 col-md-10">
		<input type="hidden" name="captcha_hash" value="' . $hash . '" />
		<button class="btn btn-warning" type="submit" name="saveedit"  /><i class="bi bi-save"></i> ' . $plugin_language['edit'] . '</button>
    </div>
  </div>
  </form>
  </div>
  </div>';
} elseif (isset($_POST['save'])) {

  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {

    $icon = $_FILES["icon"];
    $name = $_POST["name"];
    $tag = $_POST["tag"];



    $id = mysqli_insert_id($_database);
    #if (checkforempty(array('name','tag'))) {
    $errors = array();

    //TODO: should be loaded from root language folder
    $_language->readModule('formvalidation', true, true);

    $errors = array();

    $upload = new \webspell\HttpUpload('icon');
    if ($upload->hasFile()) {
      if ($upload->hasError() === false) {
        $mime_types = array('image/jpeg', 'image/png', 'image/avif', 'image/webp', 'image/gif');

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
              case 18:
                $endung = '.webp';
                break;
              case 19:
                $endung = '.avif';
                break;
              default:
                $endung = '.jpg';
                break;
            }

            safe_query(
              "INSERT INTO " . PREFIX . "plugins_games_pic (
                                    name,
                                    tag
                                ) VALUES (
                                    '" . $name . "',
                                    '" . $tag . "'
                                )"
            );

            $file = $tag . '.' . $upload->getExtension();

            if ($upload->saveAs($filepath . $file, true)) {
              @chmod($filepath . $file, $new_chmod);
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

    #} else {
    #    echo $plugin_language[ 'fill_correctly' ];
    #}
  } else {
    echo $plugin_language['transaction_invalid'];
  }
  redirect("admincenter.php?site=admin_games_pic", "", 0);
} elseif (isset($_POST["saveedit"])) {
  $icon = $_FILES["icon"];
  $name = $_POST["name"];
  $tag = $_POST["tag"];
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
    #safe_query("INSERT INTO `".PREFIX."plugins_games_pic` (tag,name) values ('".$tag."','".$name."')");
    /*if (checkforempty(array('name','tag'))) {*/
    safe_query(
      "UPDATE
                    " . PREFIX . "plugins_games_pic
                SET
                    name='" . $name . "',
                    tag='" . $tag . "'
                WHERE gameID='" . $_POST["gameID"] . "'"
    );

    $errors = array();

    //TODO: should be loaded from root language folder
    $_language->readModule('formvalidation', true);

    $upload = new \webspell\HttpUpload('icon');
    if ($upload->hasFile()) {
      if ($upload->hasError() === false) {
        $mime_types = array('image/jpeg', 'image/png', 'image/avif', 'image/webp', 'image/gif');

        if ($upload->supportedMimeType($mime_types)) {
          $imageInformation =  getimagesize($upload->getTempFile());


          if (is_array($imageInformation)) {

            switch ($imageInformation[2]) {
              case 1:
                $endung = '.gif';
                break;
              case 3:
                $endung = '.png';
                break;
              case 18:
                $endung = '.webp';
                break;
              case 19:
                $endung = '.avif';
                break;
              default:
                $endung = '.jpg';
                break;
            }

            $file = $tag . '.' . $upload->getExtension();

            if ($upload->saveAs($filepath . $file, true)) {
              @chmod($filepath . $file, $new_chmod);
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
    #} else {
    #    echo $plugin_language[ 'fill_correctly' ];
    #}
  } else {
    echo $plugin_language['transaction_invalid'];
  }
  redirect("admincenter.php?site=admin_games_pic", "", 0);
} elseif (isset($_GET["delete"])) {
  $CAPCLASS = new \webspell\Captcha();
  if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
    $ds = mysqli_fetch_array(
      safe_query(
        "SELECT tag FROM " . PREFIX . "plugins_games_pic WHERE gameID='" . $_GET["gameID"] . "'"
      )
    );
    $extension = explode('.', $ds['tag']);
    safe_query("DELETE FROM " . PREFIX . "plugins_games_pic WHERE gameID='" . $_GET["gameID"] . "'");
    $file = $ds['tag'] . ".gif";
    if (file_exists($filepath . $file)) {
      unlink($filepath . $file);
    }
    redirect("admincenter.php?site=admin_games_pic", "", 0);
  } else {
    echo $plugin_language['transaction_invalid'];
  }
} else {



  if (isset($_GET['page'])) $page = (int)$_GET['page'];


  echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-controller"></i> ' . $plugin_language['games'] . '
        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['games'] . '</li>
  </ol>
</nav>

<div class="card-body">

<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_games_pic&amp;action=add" class="btn btn-primary" type="button"><i class="bi bi-plus-circle"></i> ' . $plugin_language['new_game_submit'] . '</a>
    </div>
  </div>';

  $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_games_pic ORDER BY name ASC");

  echo '<table id="plugini" class="table table-bordered table-striped dataTable">
    <thead>
      <th><b>' . $plugin_language['icons'] . '</b></th>
      <th><b>' . $plugin_language['game_name'] . '</b></th>
      <th><b>' . $plugin_language['game_tag'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
    </thead><tbody>';



  $CAPCLASS = new \webspell\Captcha;
  $CAPCLASS->createTransaction();
  $hash = $CAPCLASS->getHash();

  while ($ds = mysqli_fetch_array($ergebnis)) {

    if (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.jpg')) {
      $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.jpg" alt="">';
    } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.jpeg')) {
      $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.jpeg" alt="">';
    } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.png')) {
      $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.png" alt="">';
    } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.gif')) {
      $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.gif" alt="">';
    } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.avif')) {
      $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.avif" alt="">';
    } elseif (file_exists('./includes/plugins/squads/images/games/' . $ds['tag'] . '.webp')) {
      $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/games/' . $ds['tag'] . '.webp" alt="">';
    } else {
      $gameicon = '<img style="height: 100px" src="../includes/plugins/squads/images/no-image.jpg" alt="">';
    }

    echo '<tr>
        <th>' . $gameicon . '</th>
        <th>' . getinput($ds['name']) . '</th>
        <th>' . getinput($ds['tag']) . '</th>
        <th><a href="admincenter.php?site=admin_games_pic&amp;action=edit&amp;gameID=' . $ds['gameID'] . '" class="btn btn-warning" type="button"><i class="bi bi-pencil-square"></i> ' . $plugin_language['edit'] . '</a>

<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_games_pic&amp;delete=true&amp;gameID=' . $ds['gameID'] . '&amp;captcha_hash=' . $hash . '"><i class="bi bi-trash3"></i> 
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language['games'] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-square"></i> ' . $plugin_language['close'] . '</button>
        <a class="btn btn-danger btn-ok"><i class="bi bi-trash3"></i> ' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->

       </th>
      </tr>';
  }
  echo '</tbody></table>';
}
echo '</div></div>';
