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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager();
$plugin_language = $pm->plugin_language("admin_gallery", $plugin_path);


$ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_links WHERE modulname='gallery'");
while ($db = mysqli_fetch_array($ergebnis)) {
    $accesslevel = 'is' . $db['accesslevel'] . 'admin';

    if (!$accesslevel($userID) || mb_substr(basename($_SERVER['REQUEST_URI']), 0, 15) != "admincenter.php") {
        die($plugin_language['access_denied']);
    }
}

// -- NEWS INFORMATION -- //
include_once("./includes/plugins/gallery/gallery_functions.php");
$galclass = new \webspell\Gallery;

$filepath = $plugin_path . "images/thumb/";
$filelargepath = $plugin_path . "images/large/";
$filethumbpath = $plugin_path . "images/thumb/";

if (isset($_GET['part'])) {
    $part = $_GET['part'];
} else {
    $part = '';
}
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}

if (isset($_POST['admin_settings_save'])) {
    /*port_max_img='" . ($_POST[ 'port_max_img' ]) . "',*/
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        safe_query(
            "UPDATE " . PREFIX . "plugins_gallery_settings 
                    SET 
                    maxusergalleries='" . (@$_POST['maxusergalleries'] * 1024 * 1024) . "',
                    port_max_img='',
                    groups='" . $_POST['groups'] . "',
                    gallery_per_page_row='" . $_POST['gallery_per_page_row'] . "',
                    gal_img_per_page='" . $_POST['gal_img_per_page'] . "',
                    gal_img_per_page_row='" . $_POST['gal_img_per_page_row'] . "',
                    publicadmin='" . isset($_POST['publicadmin']) . "',
                    usergalleries='" . isset($_POST['usergalleries']) . "',
                    port_img_per_page='" . $_POST['port_img_per_page'] . "'

                    WHERE gallerysetID='" . $_POST['gallerysetID'] . "'"
        );

        redirect("admincenter.php?site=admin_gallery&action=admin_gallery_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_gallery&action=admin_gallery_settings", $plugin_language['transaction_invalid'], 3);
    }
} elseif (isset($_POST['sortieren_groups'])) {
    if (isset($_POST['sortcat'])) {
        $sortcat = $_POST['sortcat'];
    } else {
        $sortcat = "";
    }

    $sortlinks = $_POST['sortlinks'];

    if (is_array($sortcat) and !empty($sortcat)) {
        foreach ($sortcat as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "plugins_gallery_groups SET sort='$sorter[1]' WHERE groupID='$sorter[0]' ");
        }
    }
    if (is_array($sortlinks)) {
        foreach ($sortlinks as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "plugins_gallery SET sort='$sorter[1]' WHERE galleryID='$sorter[0]' ");
        }
    }
} elseif (isset($_POST['saveedit_set_video'])) {
    $dir = $plugin_path . "images/";

    if (isset($_POST['name'])) $name = $_POST['name'];
    $comment = $_POST['comment'];
    $comments = $_POST['comments'];
    $picID = $_POST['picID'];
    $youtube = $_POST['youtube'];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(array('name'))) {

            if ($_POST['name']) {
                $insertname = $_POST['name'];
            } else {
                $insertname = $picture['name'];
            }

            safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_gallery_pictures` 
                    SET 
                    `name` = '" . $insertname . "',
                    `comment` = '" . $comment . "',
                    `comments` = '" . $comments . "',
                    `youtube` = '" . $youtube . "',
                    `pic_video` = '1'
                    WHERE 
                    `picID` = '" . $picID . "'"
            );
            #######################
            $id = $_POST['picID'];
        }
    }
} elseif (isset($_POST['saveedit_set_pic'])) {
    $dir = $plugin_path . "images/";
    $pictures = array();

    if (isset($_POST['name'])) $name = $_POST['name'];
    if (isset($_POST['pictures'])) $pictures = $_POST['pictures'];
    $comment = $_POST['comment'];
    $comments = $_POST['comments'];
    $picID = $_POST['picID'];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(array('name'))) {

            if ($_POST['name']) {
                $insertname = $_POST['name'];
            } else {
                $insertname = $picture['name'];
            }

            safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_gallery_pictures` 
                    SET 
                    `name` = '" . $insertname . "',
                    `comment` = '" . $comment . "',
                    `comments` = '" . $comments . "'
                    WHERE 
                    `picID` = '" . $picID . "'"
            );
            #######################
            $id = $_POST['picID'];

            $errors = array();

            $upload = new \webspell\HttpUpload('picture');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif');

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
                            $file = $id . $endung;

                            if ($_POST['name']) {
                                $insertname = $_POST['name'];
                            } else {
                                $insertname = $picture['name'];
                            }

                            if ($upload->saveAs($filelargepath . $file, true)) {
                                @chmod($file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_gallery_pictures SET name='" . $insertname . "' WHERE picID='" . $_POST['picID'] . "'"
                                );
                            }

                            #@copy($dir . 'large/' . $id . $endung);
                            $galclass->saveThumb($dir . 'large/' . $id . $endung, $dir . 'thumb/' . $id . '.jpg');
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
            ######################
        } else {
            echo $plugin_language['information_incomplete'];
        }
    } else {
        echo $plugin_language['transaction_invalid'];
    }
} elseif ($action == "edit_pic") {
    $galclass = new \webspell\Gallery;

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE picID='" . $_GET['picID'] . "'"
        )
    );

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE `galleryID` = '" . (int)$ds['galleryID'] . "'");
    while ($dw = mysqli_fetch_array($ergebnis)) {
        $name = $dw['name'];
    }

    ###########################
    $dg = safe_query(
        "SELECT
                pic_video
            FROM
                `" . PREFIX . "plugins_gallery`
            WHERE
                `galleryID` = '" . (int)$ds['galleryID'] . "'"
    );

    while ($dw = mysqli_fetch_array($dg)) {
        $media = $dw['pic_video'];
    }
    ######################## 

    if ($media == '0') {
        echo '<div class="card">
            <div class="card-header"><i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
                </div>    
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_gallery" class="white">' . $plugin_language['pic_gallery'] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery&galleryID=' . $ds['galleryID'] . '">' . $name . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_pic'] . '</li>
                </ol>
            </nav>';
    } else {
        echo '<div class="card">
            <div class="card-header"><i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
                </div>    
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_gallery" class="white">' . $plugin_language['pic_gallery'] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery&galleryID=' . $ds['galleryID'] . '">' . $name . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_video'] . '</li>
                </ol>
            </nav>';
    }
    echo '<div class="card-body">';

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE picID='" . $_GET['picID'] . "'"
        )
    );

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


    if (!empty($ds['picID'])) {
        $pic = '<img class="img-fluid img-thumbnail" style="width: 100%; max-width: 300px" src="../' . $filepath . $ds['picID'] . '.jpg" alt="">';
    } else {
        $pic = $plugin_language['no_upload'];
    }

    $videoID = $ds['youtube'];

    $preview = 'http://img.youtube.com/vi/' . $videoID . '/hqdefault.jpg';
    if (!empty($ds['youtube'])) {
        $video = '<img src="' . $preview . '" alt="Movie Preview" class="img-fluid" />';
    } else {
        #$video = $plugin_language[ 'no_upload' ];
    }


    $comments = '<option value="0">' . $plugin_language['no_comments'] . '</option>
                     <option value="1">' . $plugin_language['user_comments'] . '</option>
                     <option value="2">' . $plugin_language['visitor_comments'] . '</option>';
    $comments =
        str_replace(
            'value="' . $ds['comments'] . '"',
            'value="' . $ds['comments'] . '" selected="selected"',
            $comments
        );

    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery" enctype="multipart/form-data">';
    if ($media == '0') {
        echo '<div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['picture'] . ':</label>
        <div class="col-sm-8">
            <span class="text-muted small"><em>' . $pic . '</em></span>
        </div>
    </div>';
    } else {
        echo '<div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['video'] . ':</label></label>
        <div class="col-sm-8">
                   ' . $video . '
                </div>
            </div>';
    }
    if ($media == '0') {
        echo '<div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['pic_upload_info'] . ':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="btn btn-info" name="picture" type="file" size="40" /></em></span>
        </div>
    </div>';
    } else {
        echo '<div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['youtubecode'] . ':</label>
        <div class="col-sm-8">
                    <input type="youtube" name="youtube" class="form-control" id="input-url" value="' . getinput($ds['youtube']) . '">
                </div>
            </div>';
    }


    echo '<div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['pic_name'] . ':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" size="60" maxlength="255" value="' . getinput($ds['name']) . '" /></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['comment'] . ':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="comment" size="60" maxlength="255" value="' . getinput($ds['comment']) . '" /></em></span>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['comments'] . ':</label>
        <div class="col-sm-3"><span class="text-muted small"><em>
            <select class="form-select" name="comments">' . $comments . '</select></em></span>
        </div>
    </div>';

    if ($media == '0') {
        echo '<div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="picID" value="' . $ds['picID'] . '" />
            <button class="btn btn-warning" type="submit" name="saveedit_set_pic"  />' . $plugin_language['edit_pic'] . '</button>
        </div>
    </div>';
    } else {
        echo '<div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="picID" value="' . $ds['picID'] . '" />
            <button class="btn btn-warning" type="submit" name="saveedit_set_video"  />' . $plugin_language['edit_video'] . '</button>
        </div>
    </div>';
    }
    echo '</form>
</div></div>';
} elseif ($action == "delete_set") {

    $galclass = new \webspell\Gallery;

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `galleryID`
            FROM
                `" . PREFIX . "plugins_gallery_pictures`
            WHERE
                `picID` = '" . (int)$_GET['picID'] . "'"
        )
    );

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                    `galleryID`
                FROM
                    `" . PREFIX . "plugins_gallery_pictures`
                WHERE `picID` = '" . (int)$_GET['picID'] . "'"
        )
    );

    $dir = './includes/plugins/gallery/images/';

    //delete thumb

    @unlink($dir . 'thumb/' . $_GET['picID'] . '.jpg');

    //delete original

    if (file_exists($dir . 'large/' . $_GET['picID'] . '.jpg')) {
        @unlink($dir . 'large/' . $_GET['picID'] . '.jpg');
    } elseif (file_exists($dir . 'large/' . $_GET['picID'] . '.gif')) {
        @unlink($dir . 'large/' . $_GET['picID'] . '.gif');
    } elseif (file_exists($dir . 'large/' . $_GET['picID'] . '.webp')) {
        @unlink($dir . 'large/' . $_GET['picID'] . '.webp');
    } elseif (file_exists($dir . 'large/' . $_GET['picID'] . '.avif')) {
        @unlink($dir . 'large/' . $_GET['picID'] . '.avif');
    } else {
        @unlink($dir . 'large/' . $_GET['picID'] . '.png');
    }

    //delete database entry

    safe_query(
        "DELETE FROM
                `" . PREFIX . "plugins_gallery_pictures`
            WHERE
                `picID` = '" . (int)$_GET['picID'] . "'"
    );

    echo $plugin_language['delete_pic'];
    redirect('admincenter.php?site=admin_gallery&galleryID=' . $_GET['galleryID'] . '', "", 2);
    return false;
}

if ($part == "groups") {
    if (isset($_POST['save'])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            if (checkforempty(array('name'))) {
                safe_query(
                    "INSERT INTO " . PREFIX . "plugins_gallery_groups ( name, sort ) values( '" . $_POST['name'] . "', '1' ) "
                );
            } else {
                echo $plugin_language['information_incomplete'];
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    } elseif (isset($_POST['saveedit'])) {
        if (isset($_POST["displayed_cat"])) {
            $displayed_cat = 1;
        } else {
            $displayed_cat = 0;
        }

        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            if (checkforempty(array('name'))) {
                safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery_groups 
                    SET 
                    name='" . $_POST['name'] . "',
                    displayed_cat='" . $displayed_cat . "'

                    WHERE groupID='" . $_POST['groupID'] . "'"
                );
            } else {
                echo $plugin_language['information_incomplete'];
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    } elseif (isset($_POST['sort'])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            if (isset($_POST['sortlist'])) {
                if (is_array($_POST['sortlist'])) {
                    foreach ($_POST['sortlist'] as $sortstring) {
                        $sorter = explode("-", $sortstring);
                        safe_query(
                            "UPDATE " . PREFIX . "plugins_gallery_groups
                            SET sort='$sorter[1]'
                            WHERE groupID='$sorter[0]' "
                        );
                    }
                }
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    } elseif (isset($_GET['delete'])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
            $db_result = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $_GET['groupID'] . "'");
            $any = mysqli_num_rows($db_result);
            if ($any) {
                echo $plugin_language['galleries_available'] . '<br /><br />';
            } else {
                safe_query("DELETE FROM " . PREFIX . "plugins_gallery_groups WHERE groupID='" . $_GET['groupID'] . "'");
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    }

    if ($action == "add") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        echo '<div class="card">
                <div class="card-header">
                    <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
                </div>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&part=groups">' . $plugin_language['groups'] . '</a></li>
                    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['add_group'] . '</li>
                  </ol>
                </nav>

        <div class="card-body">';


        echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=groups">
            <div class="mb-3 row">
                <label class="col-sm-2 control-label">' . $plugin_language['group_name'] . ':</label>
                <div class="col-sm-8"><span class="text-muted small"><em>
                    <input class="form-control" type="text" name="name" size="60" /></em></span>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-offset-2 col-sm-8">
        	       <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                    <button class="btn btn-success" type="submit" name="save" />' . $plugin_language['add_group'] . '</button>
                </div>
            </div>
        </form>
            </div>
        </div>';
    } elseif ($action == "edit_group") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE groupID='" . $_GET['groupID'] . "'");
        $ds = mysqli_fetch_array($ergebnis);

        if ($ds['displayed_cat'] == 1) {
            $displayed_cat = '<input class="form-check-input" type="checkbox" name="displayed_cat" value="1" checked="checked" />';
        } else {
            $displayed_cat = '<input class="form-check-input" type="checkbox" name="displayed_cat" value="1" />';
        }


        echo '<div class="card">
                <div class="card-header">
                    <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
                </div>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&part=groups">' . $plugin_language['groups'] . '</a></li>
                    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_group'] . '</li>
                  </ol>
                </nav>

        <div class="card-body">';


        echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=groups">
        	<div class="mb-3 row">
                <label class="col-sm-2 control-label">' . $plugin_language['group_name'] . ':</label>
                <div class="col-sm-8"><span class="text-muted small"><em>
                    <input class="form-control" type="text" name="name" value="' . getinput($ds['name']) . '" /></em></span>
                </div>
            </div>';
        if ($ds['intern'] == 1) {

            echo '<div class="mb-3 row">
            <label class="col-sm-2 control-label">' . $plugin_language['is_displayed'] . ' Gallery:</label>
            <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
                ' . $displayed_cat . '
            </div>
        </div>';
        }
        echo '<div class="mb-3 row">
            <div class="col-sm-offset-2 col-sm-8">
    	       <input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="groupID" value="' . $ds['groupID'] . '" />
                <button class="btn btn-success" type="submit" name="saveedit" />' . $plugin_language['edit_group'] . '</button>
            </div>
        </div>
        </form>
        </div>
        </div>';
    } else {
        echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
            <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['groups'] . '</li>
          </ol>
        </nav>

        <div class="card-body">

        <div class="mb-3 row">
            <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
            <div class="col-md-8">
                <a href="admincenter.php?site=admin_gallery&amp;part=groups&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language['new_group'] . '</a>
            </div>
        </div>';


        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups ORDER BY sort");

        echo '<form method="post" name="ws_gallery" action="admincenter.php?site=admin_gallery&amp;part=groups">
    <table class="table table-striped">
        <thead>
            <th><b>' . $plugin_language['group_name'] . '</b></th>
            <th>' . $plugin_language['is_displayed'] . ' <b>Gallery</b></th>
            <th><b>' . $plugin_language['actions'] . '</b></th>
            <th><b>' . $plugin_language['sort'] . '</b></th>
        </thead>';

        $n = 1;
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        while ($ds = mysqli_fetch_array($ergebnis)) {

            $ds['displayed_cat'] == 1 ?
                $displayed_cat = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>' :
                $displayed_cat = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';

            $list = '<select name="sortlist[]">';
            $counter = mysqli_num_rows($ergebnis);
            for ($i = 1; $i <= $counter; $i++) {
                $list .= '<option value="' . $ds['groupID'] . '-' . $i . '">' . $i . '</option>';
            }
            $list .= '</select>';
            $list = str_replace(
                'value="' . $ds['groupID'] . '-' . $ds['sort'] . '"',
                'value="' . $ds['groupID'] . '-' . $ds['sort'] . '" selected="selected"',
                $list
            );
            echo '<tr>
        <td>' . $ds['name'] . '</td>
        <td>
            ' . $displayed_cat . '
            </td>
        <td><a href="admincenter.php?site=admin_gallery&amp;part=groups&amp;action=edit_group&amp;groupID=' . $ds['groupID'] . '" class="btn btn-warning" type="button">' . $plugin_language['edit'] . '</a>';
            if ($ds['intern'] == 0) {
                echo ''; #Interne Kategorie nicht löschbar
            } else {

                echo '<!-- Button trigger modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;part=groups&amp;delete=true&amp;groupID=' . $ds['groupID'] . '&amp;captcha_hash=' . $hash . '">
            ' . $plugin_language['delete'] . '
            </button>
            <!-- Button trigger modal END-->

            <!-- Modal -->
            <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language['pic_gallery'] . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
                  </div>
                  <div class="modal-body"><p>' . $plugin_language['really_delete_group'] . '</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language['close'] . '</button>
                    <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal END --> ';
            }
            echo '</td>
        <td>' . $list . '</td>
		 	</tr>';
            $n++;
        }
        echo '<tr>
      <td class="td_head" colspan="3" align="right"><input type="hidden" name="captcha_hash" value="' . $hash . '" /><button class="btn btn-primary" type="submit" name="sort" />' . $plugin_language['to_sort'] . '</button></td>
      </tr>
    </table>
    </form></div></div>';
    }
} elseif ($part == "gallerys") {
    if (isset($_POST['save'])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            if (checkforempty(array('name'))) {
                safe_query(
                    "INSERT INTO " . PREFIX . "plugins_gallery ( name, date, groupID, pic_video )
                    values( '" . $_POST['name'] . "', '" . time() . "', '" . $_POST['group'] . "', '" . $_POST['pic_video'] . "' ) "
                );
                $id = mysqli_insert_id($_database);
            } else {
                echo $plugin_language['information_incomplete'];
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    } elseif (isset($_POST['saveedit'])) {
        $text = $_POST["message"];

        if (isset($_POST["displayed_gal"])) {
            $displayed_gal = 1;
        } else {
            $displayed_gal = 0;
        }
        if (isset($_POST["displayed_port"])) {
            $displayed_port = 1;
        } else {
            $displayed_port = 0;
        }
        if (isset($_POST["pic_video"])) {
            $pic_video = 1;
        } else {
            $pic_video = 0;
        }

        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            if (checkforempty(array('name'))) {
                if (!isset($_POST['group'])) {
                    $_POST['group'] = 0;
                }
                safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery 
                    SET 
                    name='" . $_POST['name'] . "', 
                    groupID='" . $_POST['group'] . "',
                    text='" . $text . "', 
                    displayed_gal='" . $displayed_gal . "', 
                    displayed_port='" . $displayed_port . "',
                    pic_video='" . $pic_video . "'
                    WHERE galleryID='" . $_POST['galleryID'] . "'"
                );

                safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery_pictures SET 
                    displayed_gal='" . $displayed_gal . "', 
                    displayed_port='" . $displayed_port . "'
                    WHERE galleryID='" . $_POST['galleryID'] . "'"
                );
            } else {
                echo $plugin_language['information_incomplete'];
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    } elseif (isset($_POST['saveftp'])) {
        $dir = './includes/plugins/gallery/images/';
        $dir_up = './includes/plugins/gallery/images/pic_update/';

        if ($_POST['galleryID'] == "1") {
            $displayed_gal = 0;
            $displayed_port = 0;
        } else {
            $displayed_gal = 1;
            $displayed_port = 1;
        }

        $pictures = array();
        if (isset($_POST['comment'])) $comment = $_POST['comment'];
        if (isset($_POST['name'])) $name = $_POST['name'];
        if (isset($_POST['pictures'])) $pictures = $_POST['pictures'];
        $i = 0;
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            foreach ($pictures as $picture) {
                $typ = getimagesize($dir_up . $picture);
                switch ($typ[2]) {
                    case 1:
                        $typ = '.gif';
                        break;
                    case 2:
                        $typ = '.jpg';
                        break;
                    case 3:
                        $typ = '.png';
                        break;
                    case 18:
                        $typ = '.webp';
                        break;
                    case 19:
                        $typ = '.avif';
                        break;
                }
                if ($name[$i]) $insertname = $name[$i];
                else $insertname = $picture;
                safe_query(
                    "INSERT INTO " . PREFIX .
                        "plugins_gallery_pictures ( galleryID, name, comment, dateupl, comments, displayed_gal, displayed_port) VALUES ('" . $_POST['galleryID'] .
                        "', '" . $insertname . "', '" . $comment[$i] . "', '" . time() . "', '" . $_POST['comments'] . "', '" . $displayed_gal . "', '" . $displayed_port . "' )"
                );
                $insertid = mysqli_insert_id($_database);
                copy($dir_up . $picture, $dir . 'large/' . $insertid . $typ);
                $galclass->saveThumb($dir . 'large/' . $insertid . $typ, $dir . 'thumb/' . $insertid . '.jpg');
                @unlink($dir_up . $picture);
                $i++;
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
        ####################
    } elseif (isset($_POST['saveform_video'])) {

        if ($_POST['galleryID'] == "1") {
            $displayed_gal = 0;
            $displayed_port = 0;
        } else {
            $displayed_gal = 1;
            $displayed_port = 1;
        }


        if ($_POST['name']) {
            $insertname = $_POST['name'];
        } else {
            $insertname = 'Video';
        }

        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            if (checkforempty(array('name'))) {
                safe_query(
                    "INSERT INTO " . PREFIX . "plugins_gallery_pictures (
                                    galleryID,
                                    name,
                                    comment,
                                    dateupl,
                                    comments,
                                    youtube,
                                    displayed_gal,
                                    displayed_port,
                                    pic_video

                                ) VALUES (
                                    '" . $_POST['galleryID'] . "',
                                    '" . $insertname . "',
                                    '" . $_POST['comment'] . "',
                                    '" . time() . "',
                                    '" . $_POST['comments'] . "',
                                    '" . $_POST['youtube'] . "',
                                    '" . $displayed_gal . "',
                                    '" . $displayed_port . "',
                                    '1'
                                )"
                );
                $id = mysqli_insert_id($_database);
            } else {
                echo $plugin_language['information_incomplete'];
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
        ##############
    } elseif (isset($_POST['saveform_pic'])) {

        if ($_POST['galleryID'] == "1") {
            $displayed_gal = 0;
            $displayed_port = 0;
        } else {
            $displayed_gal = 1;
            $displayed_port = 1;
        }


        $dir = './includes/plugins/gallery/images/';
        $picture = $_FILES['picture'];
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('picture');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif');
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

                            if ($_POST['name']) {
                                $insertname = $_POST['name'];
                            } else {
                                $insertname = $picture['name'];
                            }

                            safe_query(
                                "INSERT INTO " . PREFIX . "plugins_gallery_pictures (
                                    galleryID,
                                    name,
                                    comment,
                                    dateupl,
                                    comments,
                                    displayed_gal,
                                    displayed_port

                                ) VALUES (
                                    '" . $_POST['galleryID'] . "',
                                    '" . $insertname . "',
                                    '" . $_POST['comment'] . "',
                                    '" . time() . "',
                                    '" . $_POST['comments'] . "',
                                    '" . $displayed_gal . "',
                                    '" . $displayed_port . "'
                                )"
                            );

                            $insertid = mysqli_insert_id($_database);

                            $filepath = $dir . 'large/';
                            $file = $insertid . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                $galclass->saveThumb($filepath . $file, $dir . 'thumb/' . $insertid . '.jpg');
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
            if (isset($error)) {
                if (count($errors)) {
                    $errors = array_unique($errors);
                    echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
                }
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    } elseif (isset($_GET['delete'])) {
        //SQL
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
            if (safe_query("DELETE FROM " . PREFIX . "plugins_gallery WHERE galleryID='" . $_GET['galleryID'] . "'")) {
                //FILES
                $ergebnis = safe_query(
                    "SELECT picID FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID='" .
                        $_GET['galleryID'] . "'"
                );
                while ($ds = mysqli_fetch_array($ergebnis)) {
                    @unlink('../images/thumb/' . $ds['picID'] . '.jpg'); //thumbnails
                    $path = '../images/large/';
                    if (file_exists($path . $ds['picID'] . '.jpg')) {
                        $path = $path . $ds['picID'] . '.jpg';
                    } elseif (file_exists($path . $ds['picID'] . '.png')) {
                        $path = $path . $ds['picID'] . '.png';
                    } elseif (file_exists($path . $ds['picID'] . '.webp')) {
                        $path = $path . $ds['picID'] . '.webp';
                    } elseif (file_exists($path . $ds['picID'] . '.avif')) {
                        $path = $path . $ds['picID'] . '.avif';
                    } else {
                        $path = $path . $ds['picID'] . '.gif';
                    }
                    @unlink($path); //large
                    #safe_query(
                    #    "DELETE FROM " . PREFIX . "comments WHERE parentID='" . $ds[ 'picID' ] .
                    #    "' AND type='ga'"
                    #);
                }
                safe_query("DELETE FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID='" . $_GET['galleryID'] . "'");
            }
        } else {
            echo $plugin_language['transaction_invalid'];
        }
    }

    if ($action == "add") {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE intern= '1'");
        $any = mysqli_num_rows($ergebnis);
        if ($any) {
            $groups = '<select class="form-select" name="group">';
            while ($ds = mysqli_fetch_array($ergebnis)) {
                $groups .= '<option value="' . $ds['groupID'] . '">' . getinput($ds['name']) . '</option>';
            }
            $groups .= '</select>';
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

            echo '<script>
    <!--
    function chkFormular() {
        if (document.getElementById("name").value == "") {
            alert("' . $plugin_language["lang_you_have_to_name"] . '");
            document.getElementById("name").focus();
            return false;
        }
    }
    -->
    </script>';

            echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&amp;part=gallerys">' . $plugin_language['gallery'] . '</a></li>
            <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['add_gallery'] . '</li>
          </ol>
        </nav>

    <div class="card-body">';

            echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload" onsubmit="return chkFormular();" enctype="multipart/form-data">

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['group'] . ':</label>
        <div class="col-sm-8">
            <span class="text-muted small"><em>' . $groups . '</em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['gallery_name'] . ':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" id="name" size="60" /></em></span>
        </div>
    </div>';
            $media = $_GET['pic_video'];
            if ($media == '0') {
                echo '<div class="mb-3 row">
            <label class="col-sm-2 control-label">' . $plugin_language['pic_upload'] . ':</label>
            <div class="col-sm-8"><span class="text-muted small"><em>
                <select class="form-select" name="upload">
                    <option value="ftp">' . $plugin_language['ftp'] . '</option>
                    <option value="form">' . $plugin_language['formular'] . '</option>
                </select></em></span>
            </div>
        </div>';
            } else {
                echo '<div class="mb-3 row">
            <label class="col-sm-2 control-label">' . $plugin_language['pic_upload'] . ':</label>
            <div class="col-sm-8"><span class="text-muted small"><em>
                <select class="form-select" name="upload">
                    <option value="form">' . $plugin_language['formular'] . '</option>
                </select></em></span>
            </div>
        </div>';
            }
            echo '<div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="pic_video" value="' . $_GET['pic_video'] . '" />
            <input class="btn btn-success" type="submit" name="save" value="' . $plugin_language['add_gallery'] . '" />
        </div>
    </div>
   </form>
	    <br /><span class="text-muted small"><em>' . $plugin_language['ftp_info'] . ' "' . $hp_url . '/includes/plugins/gallery/images"</em></span></div></div>';
        } else {
            echo '<br />' . $plugin_language['need_group'];
        }
    } elseif ($action == "edit") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups");
        $groups = '<select class="form-select" name="group">';
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $groups .= '<option value="' . $ds['groupID'] . '">' . getinput($ds['name']) . '</option>';
        }
        $groups .= '</select>';
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE galleryID='" . $_GET['galleryID'] . "'");
        $ds = mysqli_fetch_array($ergebnis);
        $groups = str_replace(
            'value="' . $ds['groupID'] . '"',
            'value="' . $ds['groupID'] . '" selected="selected"',
            $groups
        );

        if ($ds['displayed_gal'] == 1) {
            $displayed_gal = '<input class="form-check-input" type="checkbox" name="displayed_gal" value="1" checked="checked" />';
        } else {
            $displayed_gal = '<input class="form-check-input" type="checkbox" name="displayed_gal" value="1" />';
        }

        if ($ds['displayed_port'] == 1) {
            $displayed_port = '<input class="form-check-input" type="checkbox" name="displayed_port" value="1" checked="checked" />';
        } else {
            $displayed_port = '<input class="form-check-input" type="checkbox" name="displayed_port" value="1" />';
        }

        if ($ds['pic_video'] == 1) {
            $pic_video = '<input class="form-check-input" type="checkbox" name="pic_video" value="1" checked="checked" />';
        } else {
            $pic_video = '<input class="form-check-input" type="checkbox" name="pic_video" value="1" />';
        }

        echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&amp;part=gallerys">' . $plugin_language['gallery'] . '</a></li>
            <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_gallery'] . '</li>
          </ol>
        </nav>

        <div class="card-body">';

        echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys">

    ';
        if ($ds['userID'] != 0) echo '
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['usergallery_of'] . ':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <a href="../index.php?site=profile&amp;id=' . $userID . '" target="_blank">' . getnickname($ds['userID']) . '</a></em></span>
        </div>
    </div>';
        else echo '
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['group'] . ':</label>
        <div class="col-sm-8">
            <span class="text-muted small"><em>' . $groups . '</em></span>
        </div>
    </div>';
        echo '
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['gallery_name'] . ':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" value="' . getinput($ds['name']) . '" /></em></span>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['description'] . ':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <textarea class="ckeditor" id="ckeditor" rows="10" cols="" name="message" style="width: 100%;">' . getinput($ds['text']) . '</textarea></em></span>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['is_displayed'] . ' Gallery:</label>
        <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
            ' . $displayed_gal . '
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['is_displayed'] . ' Portfolio:</label>
        <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
            ' . $displayed_port . '
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language['pictures'] . ' / ' . $plugin_language['videos'] . ':</label>
        <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
            ' . $pic_video . '
        </div>
    </div>



    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="galleryID" value="' . $ds['galleryID'] . '" />
            <input class="btn btn-success" type="submit" name="saveedit" value="' . $plugin_language['edit_gallery'] . '" />
        </div>
    </div>

    </form></div></div>';
    } elseif ($action == "upload") {
        echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery&part=gallerys">' . $plugin_language['gallery'] . '</a></li>
            <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['upload'] . '</li>
          </ol>
        </nav>

        <div class="card-body">';

        $dir = './includes/plugins/gallery/images/pic_update/';
        if (isset($_POST['upload'])) {
            $upload_type = $_POST['upload'];
        } elseif (isset($_GET['upload'])) {
            $upload_type = $_GET['upload'];
        } else {
            $upload_type = null;
        }
        if (isset($_POST['galleryID'])) {
            $id = $_POST['galleryID'];
        } elseif (isset($_GET['galleryID'])) {
            $id = $_GET['galleryID'];
        }
        if ($upload_type == "ftp") {
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

            echo '<script>
    <!--
    function chkFormular() {
        if (document.getElementById("no_pic").value == "") {
            alert("' . $plugin_language["lang_you_have_to_no_pic"] . '");
            document.getElementById("no_pic").focus();
            return false;
        }
    }
    -->
    </script>';

            echo '<form method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys" onsubmit="return chkFormular();" enctype="multipart/form-data">
            <table width="100%" border="0" cellspacing="1" cellpadding="3">
                <tr>
                  <td>';
            $pics = array();
            $picdir = opendir($dir);
            while (false !== ($file = readdir($picdir))) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir . $file)) {
                        if ($info = getimagesize($dir . $file)) {
                            if ($info[2] == 1 || $info[2] == 2 || $info[2] == 3 || $info[2] == 18 || $info[2] == 19) {
                                $pics[] = $file;
                            }
                        }
                    }
                }
            }

            closedir($picdir);
            natcasesort($pics);
            reset($pics);

            echo '<!--<form method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys" onsubmit="return chkFormular();" enctype="multipart/form-data">-->
		      <table width="100%" border="0" cellspacing="1" cellpadding="3">
		        <tr>
		          <td>';

            $pics = array();
            $picdir = opendir($dir);
            while (false !== ($file = readdir($picdir))) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir . $file)) {
                        if ($info = getimagesize($dir . $file)) {
                            if ($info[2] == 1 or $info[2] == 2 || $info[2] == 3 || $info[2] == 18 || $info[2] == 19) {
                                $pics[] = $file;
                            }
                        }
                    }
                }
            }

            closedir($picdir);
            natcasesort($pics);
            reset($pics);

            echo '<table class="table">
		        <tr>
		          <td><b>' . $plugin_language['actions'] . '</b></td>
		          <td><b>' . $plugin_language['filename'] . '</b></td>
		          <td><b>' . $plugin_language['name'] . '</b></td>
		          <td><b>' . $plugin_language['comment'] . '</b></td>
		        </tr>';


            foreach ($pics as $val) {
                if (is_file($dir . $val)) {
                    echo '<tr>
                    <td><input class="form-check-input" type="checkbox" value="' . $val . '" name="pictures[]" id="pictures" checked="checked" />&nbsp;<img class="img-fluid img-thumbnail" style="width: 114px" src="../includes/plugins/gallery/images/pic_update/' . $val . '" alt=""></td>
                    <td><a href=".' . $dir . $val . '" target="_blank" name="picture" id="picture">' . $val . '</a></td>
                    <td><input class="form-control" type="text" name="name[]" size="40" /></td>
                    <td><input class="form-control" type="text" name="comment[]" size="40" /></td>
                  </tr>';
                }
            }

            if (!empty($pics)) {
                $only = '</table></td>
                  </tr>
                  <tr>
                    <td><br /><b>' . $plugin_language['visitor_comments'] . '</b> &nbsp;
                    <select class="form-select" name="comments">
                      <option value="0">' . $plugin_language['disable_comments'] . '</option>
                      <option value="1">' . $plugin_language['enable_user_comments'] . '</option>
                      <option value="2" selected="selected">' . $plugin_language['enable_visitor_comments'] . '</option>
                    </select></td>
                  </tr>';
            } else {
                $only = '</table></td>
                  </tr>
                  <tr>
                    <td><br /><input class="form-control" type="text" name="no_pic" id="no_pic"  size="60"  disabled/></td>
                  </tr>';
            }

            echo '' . $only . '
                  
		          <tr>
		            <td>(' . $plugin_language['ftp_info'] . ')<br /><input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="galleryID" value="' . $id . '" />
		            <input class="btn btn-primary" type="submit" name="saveftp" value="' . $plugin_language['upload'] . '" /></td>
		          </tr>
		        </table>
		        </form></div></div>';
        } elseif ($upload_type == "form") {
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

            echo '<script>
    <!--
    function chkFormular() {
        if (document.getElementById("picture").value == "") {
            alert("' . $plugin_language["lang_you_have_to_picture"] . '");
            document.getElementById("picture").focus();
            return false;
        }
    }
    -->
    </script>';

            echo '<form method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys" enctype="multipart/form-data" onsubmit="return chkFormular();" enctype="multipart/form-data">
		<table class="table">
        <tr>
          <td><b>' . $plugin_language['name'] . '</b></td>
          <td><input class="form-control" type="text" name="name" id="name" size="60" /></td>
        </tr>
        <tr>
          <td><b>' . $plugin_language['comment'] . '</b></td>
          <td><input class="form-control" type="text" name="comment" size="60" maxlength="255" /></td>
        </tr>
        <tr>
          <td><b>' . $plugin_language['visitor_comments'] . '</b></td>
          <td><select class="form-select" name="comments">
            <option value="0">' . $plugin_language['disable_comments'] . '</option>
            <option value="1">' . $plugin_language['enable_user_comments'] . '</option>
            <option value="2" selected="selected">' . $plugin_language['enable_visitor_comments'] . '</option>
          </select></td>
        </tr>';

            ###########################
            $dg = safe_query(
                "SELECT
                pic_video
            FROM
                `" . PREFIX . "plugins_gallery`
            WHERE
                `galleryID` = '" . (int)$id . "'"
            );

            while ($dw = mysqli_fetch_array($dg)) {
                $media = $dw['pic_video'];
            }
            ######################
            if ($media == '0') {
                echo '<tr>
              <td><b>' . $plugin_language['picture'] . '</b></td>
              <td><input class="btn btn-info" name="picture" type="file" id="picture" size="40" /></td>
            </tr>
            <tr>
              <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="galleryID" value="' . $id . '" /></td>
              <td><input class="btn btn-primary" type="submit" name="saveform_pic" value="' . $plugin_language['add_pic'] . '" /></td>
            </tr>';
            } else {
                echo '<tr>
              <td><b>' . $plugin_language['youtubecode'] . '</b></td>
              <td><input class="form-control" type="text" name="youtube" size="60" maxlength="255" /></td>
            </tr>
            <tr>
              <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="galleryID" value="' . $id . '" /></td>
              <td><input class="btn btn-primary" type="submit" name="saveform_video" value="' . $plugin_language['add_video'] . '" /></td>
            </tr>';
            }
            echo '</table>
      </form></div></div>';
        }
    } elseif ($part == "gallerys") {

        echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
            <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['gallery'] . '</li>
          </ol>
        </nav>

        <div class="card-body">

            <div class="mb-3 row">
                <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
                <div class="col-md-8">
                    <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=add&amp;pic_video=0" class="btn btn-primary" type="button">' . $plugin_language['new_gallery_pic'] . '</a>
                    <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=add&amp;pic_video=1" class="btn btn-primary" type="button">' . $plugin_language['new_gallery_video'] . '</a>
                </div>
            </div>';

        echo '<form method="post" name="ws_gallery" action="admincenter.php?site=admin_gallery&amp;part=gallerys">
		<table class="table">
            <thead>
                <th style="width: 10%"><b>' . $plugin_language['gallery_name'] . '</b></th>
                <th></th>
                <th style="width: 15%" align="center">' . $plugin_language['is_displayed'] . ' <b>Gallery</b></th>
                <th style="width: 15%" align="center">' . $plugin_language['is_displayed'] . ' <b>Portfolio</b></th>
                <th style="width: 15%" align="center">' . $plugin_language['is_displayed'] . ' <b>Intern</b></th>
                <th style="width: 15%" align="center"><b>' . $plugin_language['actions'] . '</b></th>
            </thead>';

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE intern= '1' ORDER BY sort");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $name = $ds['name'];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($name);
            $name = $translate->getTextByLanguage($name);

            echo '<tr>
                    <td class="table-secondary" colspan="6">' . $plugin_language['group'] . ': <b>' . $name . '</b></td>
                </tr>';
            $galleries = safe_query(
                "SELECT * FROM " . PREFIX .
                    "plugins_gallery WHERE groupID='$ds[groupID]' AND userID='0' ORDER BY sort"
            );

            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();
            $i = 1;
            while ($db = mysqli_fetch_array($galleries)) {

                $db['displayed_gal'] == 1 ?
                    $displayed_gal = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>' :
                    $displayed_gal = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';

                $db['displayed_port'] == 1 ?
                    $displayed_port = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>' :
                    $displayed_port = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';

                $ds['intern'] == 0 ?
                    $intern = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>' :
                    $intern = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';

                echo '<tr>
                <!--<td><a href="../index.php?site=gallery&amp;galleryID=' . $db['galleryID'] . '" target="_blank">' . getinput($db['name']) . '</a></td>-->
                <td><a href="admincenter.php?site=admin_gallery&galleryID=' . $db['galleryID'] . '">' . getinput($db['name']) . '</a></td>';

                $media = $db['pic_video'];
                if ($media == '0') {
                    echo '<td align="center">
                <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload&amp;upload=form&amp;galleryID=' . $db['galleryID'] . '" class="btn btn-primary" type="button" data-toggle="tooltip" data-html="true" title="' . $plugin_language['tooltip_7'] . '" >' . $plugin_language['add_img'] . ' (' . $plugin_language['per_form'] . ')</a>

                <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload&amp;upload=ftp&amp;galleryID=' . $db['galleryID'] . '" class="btn btn-primary" type="button" data-toggle="tooltip" data-html="true" title="' . $plugin_language['tooltip_8'] . '" >' . $plugin_language['add_img'] . ' (' . $plugin_language['per_ftp'] . ')</a>   

                </td>';
                } else {
                    echo '<td align="center">
                <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload&amp;upload=form&amp;galleryID=' . $db['galleryID'] . '" class="btn btn-primary" type="button" data-toggle="tooltip" data-html="true" title="' . $plugin_language['tooltip_7'] . '" >' . $plugin_language['add_video'] . ' (' . $plugin_language['per_form'] . ')</a>
                </td>';
                }

                echo '<td>
            ' . $displayed_gal . '
            </td>
            
        <td>
        ' . $displayed_port . '
        </td>
        <td>
        ' . $intern . '
        </td>
        <td><a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=edit&amp;galleryID=' . $db['galleryID'] . '" class="btn btn-warning" type="button">' . $plugin_language['edit_gallery'] . '</a>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;delete=true&amp;galleryID=' . $db['galleryID'] . '&amp;captcha_hash=' . $hash . '">
        ' . $plugin_language['delete'] . '
        </button>
        <!-- Button trigger modal END-->

        <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language['pic_gallery'] . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
              </div>
              <div class="modal-body"><p>' . $plugin_language['really_delete_gallery'] . '</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language['close'] . '</button>
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
        echo '</table></form></div></div><br />';

        #########################################


        echo '<div class="card">
            <div class="card-header">
                <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery_intern'] . '
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                ' . $plugin_language['interne_info'] . '
                </div>';



        echo '<form method="post" name="ws_gallery" action="admincenter.php?site=admin_gallery&amp;part=gallerys">
        <table class="table">
            <thead>
                <th style="width: 10%"><b>' . $plugin_language['gallery_name'] . '</b></th>
                <th></th>
                <th style="width: 15%" align="center">' . $plugin_language['is_displayed'] . ' <b>Gallery</b></th>
                <th style="width: 15%" align="center">' . $plugin_language['is_displayed'] . ' <b>Portfolio</b></th>
                <th style="width: 15%" align="center">' . $plugin_language['is_displayed'] . ' <b>Intern</b></th>
                <th style="width: 15%" align="center"></th>
            </thead>';

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE intern= '0' ORDER BY sort");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $name = $ds['name'];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($name);
            $name = $translate->getTextByLanguage($name);

            echo '<tr>
                    <td class="table-secondary" colspan="6">' . $plugin_language['group'] . ': <b>' . $name . '</b></td>
                </tr>';
            $galleries = safe_query(
                "SELECT * FROM " . PREFIX .
                    "plugins_gallery WHERE groupID='$ds[groupID]' AND userID='0' ORDER BY sort"
            );

            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();
            $i = 1;
            while ($db = mysqli_fetch_array($galleries)) {

                $db['displayed_gal'] == 1 ?
                    $displayed_gal = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>' :
                    $displayed_gal = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';

                $db['displayed_port'] == 1 ?
                    $displayed_port = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>' :
                    $displayed_port = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';

                $ds['intern'] == 0 ?
                    $intern = '<font color="green"><b>' . $plugin_language['yes'] . '</b></font>' :
                    $intern = '<font color="red"><b>' . $plugin_language['no'] . '</b></font>';

                echo '<tr>
          <!--<td><a href="../index.php?site=gallery&amp;galleryID=' . $db['galleryID'] . '" target="_blank">' . getinput($db['name']) . '</a></td>-->
          <td><a href="admincenter.php?site=admin_gallery&galleryID=' . $db['galleryID'] . '">' . getinput($db['name']) . '</a></td>
          <td align="center">

          
          <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload&amp;upload=form&amp;galleryID=' . $db['galleryID'] . '" class="btn btn-primary" type="button" data-toggle="tooltip" data-html="true" title="' . $plugin_language['tooltip_7'] . '" >' . $plugin_language['add_img'] . ' (' . $plugin_language['per_form'] . ')</a>


         <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload&amp;upload=ftp&amp;galleryID=' . $db['galleryID'] . '" class="btn btn-primary" type="button" data-toggle="tooltip" data-html="true" title="' . $plugin_language['tooltip_8'] . '" >' . $plugin_language['add_img'] . ' (' . $plugin_language['per_ftp'] . ')</a>   

        </td>
            
        <td>
            ' . $displayed_gal . '
            </td>
            
        <td>
        ' . $displayed_port . '
        </td>
        <td>
        ' . $intern . '
        </td>
        <td><td>';
                echo '</tr>';

                $i++;
            }
        }
        echo '</table></form></div></div><br />';

        ################################ 
        echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['usergalleries'] . '
        </div>
                        <div class="card-body">';

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE userID!='0'");

        echo '<form method="post" name="ws_gallery" action="admincenter.php?site=admin_gallery&amp;part=gallerys">
    <table class="table table-striped">
        <thead>
            <th style="width: 30%"><b>' . $plugin_language['gallery_name'] . '</b></th>
            <th style="width: 54%"><b>' . $plugin_language['usergallery_of'] . '</b></th>
            <th><b>' . $plugin_language['actions'] . '</b></th>
        </thead>';

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        $i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
            echo '<tr>
        <td><a href="../index.php?site=gallery&amp;galleryID=' . $ds['galleryID'] . '" target="_blank">' . getinput($ds['name']) . '</a></td>
        <td><a href="../index.php?site=profile&amp;id=' . $userID . '" target="_blank">' . getnickname($ds['userID']) . '</a></td>
        <td><a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=edit&amp;galleryID=' . $ds['galleryID'] . '" class="btn btn-warning" type="button">' . $plugin_language['edit_gallery'] . '</a>

        <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;delete=true&amp;galleryID=' . $ds['galleryID'] . '&amp;captcha_hash=' . $hash . '">
            ' . $plugin_language['delete'] . '
            </button>
            <!-- Button trigger modal END-->

             <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language['gallery'] . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
              </div>
              <div class="modal-body"><p>' . $plugin_language['really_delete_gallery'] . '</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language['close'] . '</button>
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
        echo '</table></form>';
    }
} elseif ($action == "admin_gallery_settings") {

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
    $dx = mysqli_fetch_array($settings);

    $da = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
    if (@$da['modulname'] != 'squads') {

        $usergalleries = '<div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle" style="font-size: 1rem; color: red;"></i> ' . $plugin_language['no_plugin_squads'] . '</div>';
        $maxusergalleries = '<div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle" style="font-size: 1rem; color: red;"></i> ' . $plugin_language['no_plugin_squads'] . '</div>';
    } else {
        if ($dx['usergalleries']) {
            $usergalleries = '<input class="form-check-input" type="checkbox" name="usergalleries" value="1" checked="checked" />';
        } else {
            $usergalleries = '<input class="form-check-input" type="checkbox" name="usergalleries" value="1" />';
        }

        $maxusergalleries = '<input class="form-control" type="text" name="maxusergalleries" value="' . getinput($dx['maxusergalleries'] / (1024 * 1024)) . '" size="35"  />';
    }

    if ($dx['publicadmin']) {
        $publicadmin = '<input class="form-check-input" type="checkbox" name="publicadmin" value="1" checked="checked" />';
    } else {
        $publicadmin = '<input class="form-check-input" type="checkbox" name="publicadmin" value="1" />';
    }

    $gallery_per_page_row = '
                    <option value="6">' . $plugin_language['gal_2'] . '</option>
                    <option value="4">' . $plugin_language['gal_3'] . '</option>
                    <option value="3">' . $plugin_language['gal_4'] . '</option>
                    <option value="2">' . $plugin_language['gal_6'] . '</option>
                    <option value="1">' . $plugin_language['gal_12'] . '</option>';
    $gallery_per_page_row =
        str_replace(
            'value="' . $dx['gallery_per_page_row'] . '"',
            'value="' . $dx['gallery_per_page_row'] . '" selected="selected"',
            $gallery_per_page_row
        );


    $gal_img_per_page_row = '
                    <option value="6">' . $plugin_language['pic_2'] . '</option>
                    <option value="4">' . $plugin_language['pic_3'] . '</option>
                    <option value="3">' . $plugin_language['pic_4'] . '</option>
                    <option value="2">' . $plugin_language['pic_6'] . '</option>
                    <option value="1">' . $plugin_language['pic_12'] . '</option>';
    $gal_img_per_page_row =
        str_replace(
            'value="' . $dx['gal_img_per_page_row'] . '"',
            'value="' . $dx['gal_img_per_page_row'] . '" selected="selected"',
            $gal_img_per_page_row
        );

    $port_img_per_page = '
                    <option value="6">' . $plugin_language['pic_2'] . '</option>
                    <option value="4">' . $plugin_language['pic_3'] . '</option>
                    <option value="3">' . $plugin_language['pic_4'] . '</option>
                    <option value="2">' . $plugin_language['pic_6'] . '</option>
                    <option value="1">' . $plugin_language['pic_12'] . '</option>';
    $port_img_per_page =
        str_replace(
            'value="' . $dx['port_img_per_page'] . '"',
            'value="' . $dx['port_img_per_page'] . '" selected="selected"',
            $port_img_per_page
        );


    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '<form method="post" action="admincenter.php?site=admin_gallery&action=admin_gallery_settings">

    <div class="card">
        <div class="card-header">
            <i class="bi bi-paragraph"></i> ' . $plugin_language['pic_gallery'] . '</div>
                        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
                    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['title'] . '</li>
                </ol>
            </nav> 
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                    <div class="row bt"><b>' . $plugin_language['gallery'] . '</b></div>
                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['groups_cat'] . '
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="' . $plugin_language['tooltip_6'] . '">
                                <input class="form-control" name="groups" type="text" value="' . getinput($dx['groups']) . '" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['groups_gal_side'] . '
                            </div>

                            <div class="col-md-6">
                                <span class="text-muted small"><em>
                                    <select class="form-select" name="gallery_per_page_row">' . $gallery_per_page_row . '</select></em>
                                </span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['groups_img_side'] . '
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="' . $plugin_language['tooltip_12'] . '">
                                <input class="form-control" name="gal_img_per_page" type="text" value="' . getinput($dx['gal_img_per_page']) . '" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['groups_img_side_row'] . '
                            </div>

                            <div class="col-md-6">
                            <span class="text-muted small"><em>
                                    <select class="form-select" name="gal_img_per_page_row">' . $gal_img_per_page_row . '</select></em>
                                </span>
                            </div>
                        </div>
                        <div class="row bt"><b>' . $plugin_language['portfolio'] . '</b></div>
                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['portfolio_side'] . '
                            </div>

                            <div class="col-md-6">
                            Noch nocht einstellbar!
                                <!--<span class="pull-right text-muted small"><em data-toggle="tooltip" title="' . $plugin_language['tooltip_10'] . '"><input class="form-control" type="text" name="port_max_img" value="' . getinput($dx['port_max_img']) . '" size="35"  /></em></span>-->
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['portfolio_side_row'] . '
                            </div>

                            <div class="col-md-6">
                                <span class="text-muted small"><em>
                                    <select class="form-select" name="port_img_per_page">' . $port_img_per_page . '</select></em>
                                </span>
                            </div>
                        </div>

                                                
                        
                    </div>

                    <div class="col-md-6">
                    <div class="row bt"><b>' . $plugin_language['usergalleries'] . '</b></div>
                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['allow_usergalleries'] . ':
                            </div>

                            <div class="col-md-6 form-check form-switch" style="padding: 0px 43px;">
                                <span data-toggle="tooltip" title="' . $plugin_language['tooltip_4'] . '">' . $usergalleries . '</span>
                            </div>
                        </div>                        

                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['public_admin'] . ':
                            </div>

                            <div class="col-md-6 form-check form-switch" style="padding: 0px 43px;">
                                <span data-toggle="tooltip" title="' . $plugin_language['tooltip_5'] . '">' . $publicadmin . '</span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                ' . $plugin_language['space_user'] . ':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="' . $plugin_language['tooltip_3'] . '">' . $maxusergalleries . '</span></em>
                            </div>
                        </div>  
                        
                    </div>

                <br>
                <div class="mb-3 row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                        <input type="hidden" name="gallerysetID" value="' . $dx['gallerysetID'] . '" />
                        
                        <button class="btn btn-warning" type="submit" name="admin_settings_save">' . $plugin_language['update'] . '</button>
                    </div>
                </div>

            </div>
        </div>
    </form>';
} elseif (isset($_GET['galleryID'])) {

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE galleryID=" . $_GET['galleryID'] . ""));
    $name = $dx['name'];

    ###########################
    $dg = safe_query(
        "SELECT
                pic_video
            FROM
                `" . PREFIX . "plugins_gallery`
            WHERE
                `galleryID` = '" . (int)$_GET['galleryID'] . "'"
    );

    while ($dw = mysqli_fetch_array($dg)) {
        $media = $dw['pic_video'];
    }
    ######################## 

    if ($media == '0') {
        echo '<div class="card">
        <div class="card-header"><i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
        </div>    
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_gallery" class="white">' . $plugin_language['pic_gallery'] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery&galleryID=' . $_GET['galleryID'] . '">' . $name . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_pic'] . '</li>
                </ol>
            </nav>';
    } else {
        echo '<div class="card">
        <div class="card-header"><i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
                </div>    
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_gallery" class="white">' . $plugin_language['pic_gallery'] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery&galleryID=' . $_GET['galleryID'] . '">' . $name . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language['edit_video'] . '</li>
                </ol>
            </nav>';
    }

    echo '<div class="card-body">
        <form method="post" action="admincenter.php?site=admin_gallery">';

    echo '<form method="post" action="admincenter.php?site=admin_pic_update">
    <table id="plugini" class="table table-striped table-bordered" style="width:100%">
    <thead>';

    ###########################
    $ergebnis = safe_query("SELECT picID,name,galleryID,youtube FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID='" . $_GET['galleryID'] . "' ORDER BY sort");
    $dg = safe_query(
        "SELECT
                    *
                FROM
                    `" . PREFIX . "plugins_gallery`
                WHERE
                    `galleryID` = '" . (int)$_GET['galleryID'] . "'"
    );

    while ($dw = mysqli_fetch_array($dg)) {
        $media = $dw['pic_video'];
    }

    if ($media == '0') {
        echo '
          <th style="width: 2%"><b>ID</b></th>
          <th style="width: 8%"><b>' . $plugin_language['pic_name'] . '</b></th>
          <th style="width: 20%"><b>' . $plugin_language['picture'] . '</b></th>
          <th><b>' . $plugin_language['pattern'] . '</b></th>
          <th style="width: 15%"><b>' . $plugin_language['actions'] . '</b></th>
        </thead>
        </thead><tbody>';
    } else {
        echo '
          <th style="width: 2%"><b>ID</b></th>
          <th style="width: 25%"><b>' . $plugin_language['pic_name'] . '</b></th>
          <th style="width: 25%"><b>' . $plugin_language['picture'] . '</b></th>
          <th></th>
          <th style="width: 15%"><b>' . $plugin_language['actions'] . '</b></th>
        </thead>
        </thead><tbody>';
    }
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $ergebnis = safe_query("SELECT picID,name,galleryID,youtube FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID='" . $_GET['galleryID'] . "' ORDER BY sort");
    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }


            ###########################
            $dg = safe_query(
                "SELECT
                    *
                FROM
                    `" . PREFIX . "plugins_gallery`
                WHERE
                    `galleryID` = '" . (int)$_GET['galleryID'] . "'"
            );

            while ($dw = mysqli_fetch_array($dg)) {
                $media = $dw['pic_video'];
            }

            ######################

            $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings"));
            $hpurl = $dx['hpurl'];

            $galleryID = $ds['galleryID'];

            $picID = "." . $galclass->getThumbFile($ds['picID']);

            $name = $ds['name'];

            if (file_exists('includes/plugins/gallery/images/large/' . $ds['picID'] . '.jpg')) {
                $pic = 'includes/plugins/gallery/images/large/' . $ds['picID'] . '.jpg"';
            } elseif (file_exists('.includes/plugins/gallery/images/large/' . $ds['picID'] . '.jpeg')) {
                $pic = 'includes/plugins/gallery/images/large/' . $ds['picID'] . '.jpeg';
            } elseif (file_exists('.includes/plugins/gallery/images/large/' . $ds['picID'] . '.png')) {
                $pic = 'includes/plugins/gallery/images/large/' . $ds['picID'] . '.png';
            } elseif (file_exists('.includes/plugins/gallery/images/large/' . $ds['picID'] . '.gif')) {
                $pic = 'includes/plugins/gallery/images/large/' . $ds['picID'] . '.gif';
            } elseif (file_exists('.includes/plugins/gallery/images/large/' . $ds['picID'] . '.webp')) {
                $pic = 'includes/plugins/gallery/images/large/' . $ds['picID'] . '.webp';
            } elseif (file_exists('.includes/plugins/gallery/images/large/' . $ds['picID'] . '.avif')) {
                $pic = 'includes/plugins/gallery/images/large/' . $ds['picID'] . '.avif';
            } else {
                $pic = '/includes/plugins/gallery/images/no-image.jpg';
            }

            $videoID = $ds['youtube'];

            $preview = 'http://img.youtube.com/vi/' . $videoID . '/hqdefault.jpg';
            if (!empty($ds['youtube'])) {
                $video = '<img src="' . $preview . '" alt="Movie Preview" class="img-fluid" />';
            } else {
                $video = $plugin_language['no_upload'];
            }

            if ($media == '0') {
                echo '<tr>
                <td><b>' . $ds['picID'] . '</b> </td>
                <td class="' . $td . '">' . $name . '</td>
                <td class="' . $td . '"><img class="i1mg-fluid gallery_pix" style="width: 300px;" src="' . $picID . '" alt="' . $name . '"></a></td>

                <td>
                    <table class="table table-bordered">
                        <tr>
                            <td style="width: 105px">HTML:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><input class="form-control" type="text" name="addpic" size="70" value="&lt;img src=&quot;' . $dx['hpurl'] . '/' . $pic . '&quot;&gt;"></td>
                        </tr><tr>
                            <td>cheditor code:&nbsp;</td>
                            <td><input class="form-control" type="text" name="addpic" size="70" value="/' . $pic . '"></td>
                        </tr><tr>
                            <td>Link:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><input class="form-control" type="text" name="addpic" size="70" value="' . $dx['hpurl'] . '/' . $pic . '"></td>

                        </tr>
                    </table>        
                </td>';
            } else {
                echo '<tr>
                <td><b>' . $ds['picID'] . '</b> </td>
                <td class="' . $td . '">' . $name . '</td>
                <td class="' . $td . '">' . $video . '</td>
                <td class="' . $td . '"></td>';
            }

            echo '<td class="' . $td . '">
            <a href="admincenter.php?site=admin_gallery&amp;action=edit_pic&amp;picID=' . $ds['picID'] . '" class="btn btn-warning"
                            type="button">' . $plugin_language['edit'] . '</a>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;action=delete_set&amp;picID=' . $ds['picID'] . '&amp;galleryID=' . $galleryID . '&amp;captcha_hash=' . $hash . '">
                                ' . $plugin_language['delete'] . '
                            </button>
        <!-- Button trigger modal END-->

        </td>

        <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language['name'] . '</h5>
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

    </tr>';
            $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="6">' . $plugin_language['no_entries'] . '</td></tr>';
    }

    echo '</table>
</form></div></div>';
} elseif ($action == "") {

    echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery'] . '
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">' . $plugin_language['pic_gallery'] . '</a></li>
            <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
          </ol>
        </nav>

        <div class="card-body">
        <form method="post" action="admincenter.php?site=admin_gallery">
        <div class="mb-3 row">
            <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
            <div class="col-md-8">
                <a href="admincenter.php?site=admin_gallery&part=gallerys" class="btn btn-primary" type="button">' . $plugin_language['new_gallery_pic'] . '</a>
                <a href="admincenter.php?site=admin_gallery&part=groups" class="btn btn-primary" type="button">' . $plugin_language['groups'] . '</a>
                <a href="admincenter.php?site=admin_gallery&action=admin_gallery_settings" class="btn btn-primary" type="button">' . $plugin_language['title'] . '</a>
            </div>
        </div>';

    echo '<table class="table">
    <thead>
      <th style="width: 70%"><b>' . $plugin_language['pic_gallery'] . '</b></th>
      <th style="width: 30%"><b>' . $plugin_language['actions'] . '</b></th>
      <th><b>' . $plugin_language['sort'] . '</b></th>
    </thead>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE intern= '1' ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(groupID) as cnt FROM `" . PREFIX . "plugins_gallery_groups`"));
    $anz = $tmp['cnt'];

    while ($ds = mysqli_fetch_array($ergebnis)) {

        $name = $ds['name'];

        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($name);
        $name = $translate->getTextByLanguage($name);

        $list = '<select name="sortcat[]">';
        for ($j = 1; $j <= $anz; $j++) {
            $list .= '<option value="' . $ds['groupID'] . '-' . $j . '">' . $j . '</option>';
        }
        $list .= '</select>';
        $list = str_replace(
            'value="' . $ds['groupID'] . '-' . $ds['sort'] . '"',
            'value="' . $ds['groupID'] . '-' . $ds['sort'] . '" selected="selected"',
            $list
        );

        echo '<thead>
                    <th class="table-secondary">' . $plugin_language['group'] . ': <b>' . $name . '</b></th><th class="table-secondary"></th>
                    <th class="table-secondary">' . $list . '</th>
                </thead>';

        $galleries = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $ds['groupID'] . "' AND userID='0' ORDER BY sort");
        $tmp = mysqli_fetch_assoc(
            safe_query("SELECT count(groupID) as cnt FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $ds['groupID'] . "'")
        );
        $anzlinks = $tmp['cnt'];

        $i = 1;
        while ($db = mysqli_fetch_array($galleries)) {

            $linklist = '<select name="sortlinks[]">';
            for ($j = 1; $j <= $anzlinks; $j++) {
                $linklist .= '<option value="' . $db['galleryID'] . '-' . $j . '">' . $j . '</option>';
            }
            $linklist .= '</select>';
            $linklist = str_replace(
                'value="' . $db['galleryID'] . '-' . $db['sort'] . '"',
                'value="' . $db['galleryID'] . '-' . $db['sort'] . '" selected="selected"',
                $linklist
            );

            $media = $db['pic_video'];
            if ($media == '0') {
                echo '<tr>
                        <td>&nbsp;&nbsp;- ' . getinput($db['name']) . '</td>
                        <td><a class="btn btn-warning" type="button" href="admincenter.php?site=admin_gallery&galleryID=' . $db['galleryID'] . '">' . $plugin_language['edit_pic'] . '</a></td>
                        <td>' . $linklist . '</td>
                    </tr>';
            } else {
                echo '<tr>
                        <td>&nbsp;&nbsp;- ' . getinput($db['name']) . '</td>
                        <td><a class="btn btn-warning" type="button" href="admincenter.php?site=admin_gallery&galleryID=' . $db['galleryID'] . '">' . $plugin_language['edit_video'] . '</a></td>
                        <td>' . $linklist . '</td>
                    </tr>';
            }
        }
    }
    echo '<tr>
      <td colspan="3" align="right"><input type="hidden" name="captcha_hash" value="' . $hash . '" />
      <button class="btn btn-primary" type="submit" name="sortieren_groups" />' . $plugin_language['to_sort'] . '</button></td>
    </tr>
  </table>
  </form>';
    echo '</div></div>';


    ########################
    echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language['pic_gallery_intern'] . '
        </div>
        <div class="card-body">
            <div class="alert alert-info" role="alert">
                ' . $plugin_language['interne_info'] . '
            </div>';

    echo '<table class="table">
        <thead>
          <th style="width: 70%"><b>' . $plugin_language['pic_gallery'] . '</b></th>
          <th style="width: 30%"><b>' . $plugin_language['actions'] . '</b></th>
          <th><b>' . $plugin_language['sort'] . '</b></th>
        </thead>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE intern= '0' ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(groupID) as cnt FROM `" . PREFIX . "plugins_gallery_groups`"));
    $anz = $tmp['cnt'];

    while ($ds = mysqli_fetch_array($ergebnis)) {

        $name = $ds['name'];

        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($name);
        $name = $translate->getTextByLanguage($name);

        $list = '<select name="sortcat[]">';
        for ($j = 1; $j <= $anz; $j++) {
            $list .= '<option value="' . $ds['groupID'] . '-' . $j . '">' . $j . '</option>';
        }
        $list .= '</select>';
        $list = str_replace(
            'value="' . $ds['groupID'] . '-' . $ds['sort'] . '"',
            'value="' . $ds['groupID'] . '-' . $ds['sort'] . '" selected="selected"',
            $list
        );

        echo '<thead>
                    <th class="table-secondary">' . $plugin_language['group'] . ': <b>' . $name . '</b></th><th class="table-secondary"></th>
                    <th class="table-secondary">' . $list . '</th>
                </thead>';

        $galleries = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $ds['groupID'] . "' AND userID='0' ORDER BY sort");
        $tmp = mysqli_fetch_assoc(
            safe_query("SELECT count(groupID) as cnt FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $ds['groupID'] . "'")
        );
        $anzlinks = $tmp['cnt'];

        $i = 1;
        while ($db = mysqli_fetch_array($galleries)) {

            $linklist = '<select name="sortlinks[]">';
            for ($j = 1; $j <= $anzlinks; $j++) {
                $linklist .= '<option value="' . $db['galleryID'] . '-' . $j . '">' . $j . '</option>';
            }
            $linklist .= '</select>';
            $linklist = str_replace(
                'value="' . $db['galleryID'] . '-' . $db['sort'] . '"',
                'value="' . $db['galleryID'] . '-' . $db['sort'] . '" selected="selected"',
                $linklist
            );

            echo '<tr>
                        <td>&nbsp;&nbsp;- ' . getinput($db['name']) . '</td>
                        <td><a class="btn btn-warning" type="button" href="admincenter.php?site=admin_gallery&galleryID=' . $db['galleryID'] . '">' . $plugin_language['edit_pic'] . '</a></td>
                        <td>' . $linklist . '</td>
                    </tr>';
        }
    }
    echo '<tr>
      <td colspan="3" align="right"><input type="hidden" name="captcha_hash" value="' . $hash . '" />
      <button class="btn btn-primary" type="submit" name="sortieren_groups" />' . $plugin_language['to_sort'] . '</button></td>
    </tr>
  </table>
  </form>';

    echo '</div></div>';
}
