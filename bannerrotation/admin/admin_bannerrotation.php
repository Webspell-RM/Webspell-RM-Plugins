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
$plugin_language = $pm->plugin_language("bannerrotation", $plugin_path);

if (!ispageadmin($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}

$filepath = $plugin_path."images/";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}

if ($action == "add") {
    echo '<div class="card">
  <div class="card-header">
                            <i class="bi bi-arrow-clockwise" style="font-size: 1rem;"></i> '.$plugin_language['bannerrotation'].'
                        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_bannerrotation" class="white">' . $plugin_language[ 'bannerrotation' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_banner'].'</li>
  </ol>
</nav>
                        <div class="card-body">';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
	
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_bannerrotation" enctype="multipart/form-data">
  <div class="row">

<div class="col-md-6">

  
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="bannername" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="bannerurl" size="60" value="http://" /></em></span>
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
</div>

<div class="col-md-12">

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_banner'].'</button>
    </div>
  </div>
  </div>
  </form></div>
  </div>';
} elseif($action=="edit") {

  echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-arrow-clockwise" style="font-size: 1rem;"> '.$plugin_language['bannerrotation'].'
                        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_bannerrotation" class="white">' . $plugin_language[ 'bannerrotation' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_banner'].'</li>
  </ol>
</nav>
                        <div class="card-body">';
  
	$ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                *
            FROM
                " . PREFIX . "plugins_bannerrotation
            WHERE
                bannerID='" . (int) $_GET["bannerID"] . "'"
        )
    );
    
    if (!empty($ds[ 'banner' ])) {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%;" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }

    if ($ds['displayed'] == '1') {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked">';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1">';
    }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_bannerrotation" enctype="multipart/form-data">
  <input type="hidden" name="bannerID" value="'.$ds['bannerID'].'" />

  <div class="row">

<div class="col-md-6">

   <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="bannername" size="60" maxlength="255" value="'.getinput($ds['bannername']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['banner_url'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="bannerurl" value="'.getinput($ds['bannerurl']).'" /></em></span>
    </div>
  </div>


  </div>

<div class="col-md-6">

 <div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['present_banner'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$pic.'</em></span>
    </div>
  </div>
    <div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['banner_upload'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /></em></span>
    </div>
  </div>

  </div>

   
<div class="col-md-12">
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      '.$displayed.'
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="saveedit"  />'.$plugin_language['edit_banner'].'</button>
    </div>
  </div>

  </div>
  </form></div>
  </div>';
} elseif (isset($_POST["save"])) {
    $bannername = $_POST["bannername"];
    $bannerurl = $_POST["bannerurl"];
    if (isset($_POST["displayed"])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }

    $upload = new \webspell\HttpUpload('banner');

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if ($bannername && $bannerurl) {
            if (!$bannerurl) {
                $bannerurl = 'http://' . $bannerurl;
            }

            safe_query(
                "INSERT INTO
                        `" . PREFIX . "plugins_bannerrotation` (
                            `bannerID`,
                            `bannername`,
                            `bannerurl`,
                            `displayed`,
                            `date`
                        )
                        values(
                            '',
                            '" . $bannername . "',
                            '" . $bannerurl . "',
                            '" . $displayed . "',
                            '" . time() . "'
                        )"
            );

            $id = mysqli_insert_id($_database);

            $errors = array();

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
                                    "UPDATE
                                        `" . PREFIX . "plugins_bannerrotation`
                                    SET
                                        `banner` = '" . $file . "'
                                    WHERE
                                        `bannerID` = '" . $id . "'"
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
                echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
            } else {
                redirect("admincenter.php?site=admin_bannerrotation", "", 0);
            }
        } else {
            echo generateErrorBox($plugin_language['fill_correctly']);
            redirect("admincenter.php?site=admin_bannerrotation", "", 0);
        }
    } else {
        echo generateErrorBox($plugin_language['transaction_invalid']);
        redirect("admincenter.php?site=admin_bannerrotation", "", 0);
    }
} elseif (isset($_POST["saveedit"])) {
    $bannername = $_POST["bannername"];
    $bannerurl = $_POST["bannerurl"];
    if (isset($_POST["displayed"])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if ($bannername && $bannerurl) {
            
            safe_query(
                "UPDATE
                            `" . PREFIX . "plugins_bannerrotation`
                        SET
                            `bannername` = '" . $bannername . "',
                            `bannerurl` = '" . $bannerurl . "',
                            `displayed` = '" . $displayed . "'
                        WHERE
                            `bannerID` = '" . (int) $_POST["bannerID"] . "'"
            );

            $errors = array();
            
            $upload = new \webspell\HttpUpload('banner');

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
                            $file = (int) $_POST["bannerID"] . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE
                                        `" . PREFIX . "plugins_bannerrotation`
                                    SET
                                        `banner` = '" . $file . "'
                                    WHERE
                                        `bannerID` = '" . (int) $_POST["bannerID"] . "'"
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
                echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
            } else {
                redirect("admincenter.php?site=admin_bannerrotation", "", 0);
            }
        } else {
            echo generateErrorBox($plugin_language['fill_correctly']);
            redirect("admincenter.php?site=admin_bannerrotation", "", 0);
        }
    } else {
        echo generateErrorBox($plugin_language['transaction_invalid']);
        redirect("admincenter.php?site=admin_bannerrotation", "", 0);
    }
} elseif (isset($_GET["delete"])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
        if (safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_bannerrotation`
                WHERE
                `bannerID` = '" . (int) $_GET["bannerID"] . "'"
        )
        ) {
            if (file_exists($filepath . $_GET["bannerID"] . '.jpg')) {
                unlink($filepath . $_GET["bannerID"] . '.jpg');
            }
            if (file_exists($filepath . $_GET["bannerID"] . '.gif')) {
                unlink($filepath . $_GET["bannerID"] . '.gif');
            }
            if (file_exists($filepath . $_GET["bannerID"] . '.png')) {
                unlink($filepath . $_GET["bannerID"] . '.png');
            }
            redirect("admincenter.php?site=admin_bannerrotation", "", 0);
        } else {
            redirect("admincenter.php?site=admin_bannerrotation", "", 0);
        }
    } else {
        echo $plugin_language['transaction_invalid'];
        redirect("admincenter.php?site=admin_bannerrotation", "", 0);
    }
} else {

  echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-arrow-clockwise" style="font-size: 1rem;"> ' . $plugin_language[ 'bannerrotation' ] . '
        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_bannerrotation" class="white">' . $plugin_language[ 'bannerrotation' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">new & edit</li>
  </ol>
</nav>

<div class="card-body">


<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_bannerrotation&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_banner' ] . '</a>
    </div>
  </div>';  

  echo'<form method="post" action="admincenter.php?site=admin_bannerrotation">
  <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['banner'].'</b></th>
      <th><b>'.$plugin_language['banner_url'].'</b></th>
      <th><b>'.$plugin_language['clicks'].'</b></th>
      <th><b>'.$plugin_language['is_displayed'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
    </thead>';
  
  $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM `" . PREFIX . "plugins_bannerrotation` ORDER BY `bannerID`");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            if ($ds['displayed'] == 1) {
                $displayed = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>';
            } else {
                $displayed = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';
            }

            if (!$ds['bannerurl']) {
                $ds['bannerurl'] = 'http://' . $ds['bannerurl'];
            }

            $bannerurl = '<a href="' . getinput($ds['bannerurl']) . '" target="_blank">' .
                            getinput($ds['bannerurl']) .'</a>';


            $days = round((time() - $ds['date']) / (60 * 60 * 24));
            if ($days) {
                $perday = round($ds['hits'] / $days, 2);
            } else {
                $perday = $ds['hits'];
            }

            echo '<tr>
        <td>'.getinput($ds['bannername']).'</td>
        <td>'.$bannerurl.'</td>
        <td>'.$ds['hits'].' ('.$perday.')</td>
        <td>'.$displayed.'</td>
        <td><a href="admincenter.php?site=admin_bannerrotation&amp;action=edit&amp;bannerID='.$ds['bannerID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_bannerrotation&amp;delete=true&amp;bannerID='.$ds['bannerID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'bannerrotation' ] . '</h5>
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
      </tr>';
      
      $i++;
		}
        
	}
  else echo'<tr><td class="td1" colspan="5">'.$plugin_language['no_entries'].'</td></tr>';
	
  echo '</table></form>';
}
echo '</div></div>';
?>