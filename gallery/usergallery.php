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
$plugin_language = $pm->plugin_language("usergallery", $plugin_path);

// -- NEWS INFORMATION -- //
include_once("gallery_functions.php");

$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
$dx = mysqli_fetch_array($settings);


$usergalleries = $dx['usergalleries'];
if (empty($usergalleries)) {
    $usergalleries = 1;
}

$maxusergalleries = $dx['maxusergalleries'];
if (empty($maxusergalleries)) {
    $maxusergalleries = 1048576;
}

#$usergalleries = $ds[ 'usergalleries' ];
#$maxusergalleries = $ds[ 'maxusergalleries' ];
#$usergalleries = "1";
#$maxusergalleries = "10048576";

#$_language->readModule('usergallery');
$galclass = new \webspell\Gallery;

if ($userID) {
    if (isset($_POST['save'])) {
        if ($_POST['name']) {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_gallery (
                        `name`,
                        `date`,
                        `userID`
                    )
                    values(
                    '" . $_POST['name'] . "',
                    '" . time() . "',
                    '" . $userID . "'
                    ) "
            );
        } else {
            redirect('index.php?site=usergallery&action=add', $plugin_language['please_enter_name']);
        }
    } elseif (isset($_POST['saveedit'])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_gallery
            SET
                name='" . $_POST['name'] . "'
            WHERE
                galleryID='" . (int)$_POST['galleryID'] . "' AND
                userID='" . (int)$userID . "'"
        );
    } elseif (isset($_POST['saveform'])) {
        $dir = './includes/plugins/gallery/images/';

        $_language->readModule('formvalidation', true);

        $upload = new \webspell\HttpUpload('picture');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif');
                if ($upload->supportedMimeType($mime_types)) {
                    if (!empty($_POST['name'])) {
                        $insertname = $_POST['name'];
                    } else {
                        $insertname = $upload->getFileName();
                    }

                    $typ =  getimagesize($upload->getTempFile());

                    if (is_array($typ)) {
                        switch ($typ[2]) {
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
                            "INSERT INTO
                                " . PREFIX . "plugins_gallery_pictures (
                                    `galleryID`,
                                    `name`,
                                    `comment`,
                                    `dateupl`,
                                    `comments`,
                                    `displayed_gal`,
                                    `displayed_port`
                                )
                                VALUES (
                                    '" . (int)$_POST['galleryID'] . "',
                                    '" . $insertname . "',
                                    '" . $_POST['comment'] . "',
                                    '" . time() . "',
                                    '" . $_POST['comments'] . "',
                                    '0',
                                    '0'
                                )"
                        );

                        $insertid = mysqli_insert_id($_database);

                        $newBigFile   = $dir . 'large/' . $insertid . $endung;
                        $newThumbFile = $dir . 'thumb/' . $insertid . '.jpg';

                        if ($upload->saveAs($newBigFile)) {
                            @chmod($newBigFile, $new_chmod);
                            $galclass->saveThumb($newBigFile, $newThumbFile);

                            if (($galclass->getUserSpace($userID) + filesize($newBigFile) +
                                    filesize($newThumbFile)) > $maxusergalleries
                            ) {
                                @unlink($newBigFile);
                                @unlink($newThumbFile);
                                safe_query(
                                    "DELETE FROM " . PREFIX . "plugins_gallery_pictures WHERE picID='" . $insertid . "'"
                                );
                                echo generateErrorBox($plugin_language['no_space_left']);
                            }
                        } else {
                            safe_query("DELETE FROM " . PREFIX . "plugins_gallery_pictures WHERE picID='" . $insertid . "'");
                            @unlink($upload->getTempFile());
                        }
                    } else {
                        echo generateErrorBox($plugin_language['broken_image']);
                    }
                } else {
                    echo generateErrorBox($plugin_language['unsupported_image_type']);
                }
            }
        }
    } elseif (isset($_GET['delete'])) {
        //SQL
        if (safe_query(
            "DELETE FROM
                    " . PREFIX . "plugins_gallery
                WHERE
                    galleryID='" . (int)$_GET['galleryID'] . "' AND
                    userID='" . (int)$userID . "'"
        )) {
            //FILES
            $ergebnis =
                safe_query(
                    "SELECT
                        `picID`
                    FROM
                        " . PREFIX . "plugins_gallery_pictures
                    WHERE
                        `galleryID` = '" . (int)$_GET['galleryID'] . "'"
                );
            while ($ds = mysqli_fetch_array($ergebnis)) {
                @unlink('images/gallery/thumb/' . $ds['picID'] . '.jpg'); //thumbnails
                $path = 'images/gallery/large/';
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
                #safe_query("DELETE FROM " . PREFIX . "comments WHERE parentID='" . $ds[ 'picID' ] . "' AND type='ga'");
            }
            safe_query("DELETE FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID='" . $_GET['galleryID'] . "'");
        }
    }

    $data_array = array();
    $data_array['$title'] = $plugin_language['usergalleries'];
    $data_array['$subtitle'] = 'Usergalleries';

    $template = $GLOBALS["_template"]->loadTemplate("gallery", "user_head", $data_array, $plugin_path);
    echo $template;

    if (isset($_GET['action'])) {
        if ($_GET['action'] == "add") {

            $data_array = array();
            $data_array['$lang_gallery_name'] = $plugin_language['gallery_name'];
            $data_array['$lang_group'] = $plugin_language['group'];
            $data_array['$lang_user_gallery'] = $plugin_language['user_gallery'];
            $data_array['$lang_add_gallery'] = $plugin_language['add_gallery'];

            $template = $GLOBALS["_template"]->loadTemplate("gallery", "user_add", $data_array, $plugin_path);
            echo $template;
        } elseif ($_GET['action'] == "edit") {
            $ergebnis = safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_gallery
                WHERE
                    galleryID='" . $_GET['galleryID'] . "'AND
                    userID='" . (int)$userID . "'"
            );
            $ds = mysqli_fetch_array($ergebnis);

            $name = getinput($ds['name']);
            $galleryID = $ds['galleryID'];
            $data_array = array();
            $data_array['$name'] = $name;
            $data_array['$galleryID'] = $galleryID;

            $data_array['$lang_gallery_name'] = $plugin_language['gallery_name'];
            $data_array['$lang_group'] = $plugin_language['group'];
            $data_array['$lang_user_gallery'] = $plugin_language['user_gallery'];
            $data_array['$lang_update'] = $plugin_language['update'];

            $template = $GLOBALS["_template"]->loadTemplate("gallery", "user_edit", $data_array, $plugin_path);
            echo $template;
        } elseif ($_GET['action'] == "upload") {
            $id = (int)$_GET['galleryID'];

            $data_array = array();
            $data_array['$id'] = $id;

            $data_array['$lang_name'] = $plugin_language['name'];
            $data_array['$lang_comment'] = $plugin_language['comment'];
            $data_array['$lang_visitor_comments'] = $plugin_language['visitor_comments'];
            $data_array['$lang_disable_comments'] = $plugin_language['disable_comments'];
            $data_array['$lang_enable_user_comments'] = $plugin_language['enable_user_comments'];
            $data_array['$lang_enable_visitor_comments'] = $plugin_language['enable_visitor_comments'];
            $data_array['$lang_picture'] = $plugin_language['picture'];
            $data_array['$lang_add_picture'] = $plugin_language['add_picture'];

            $template = $GLOBALS["_template"]->loadTemplate("gallery", "user_update", $data_array, $plugin_path);
            echo $template;
        }
    } else {
        $size = $galclass->getUserSpace($userID);
        $percent = percent($size, $maxusergalleries, 0);

        if ($percent > 95) {
            $color = "text-danger";
        } else {
            $color = "text-success";
        }


        $vars = array('%spacecolor%', '%used_size%', '%available_size%');
        $repl = array($color, round($size / (1024 * 1024), 2), round($maxusergalleries / (1024 * 1024), 2));
        $space_max_in_user = str_replace($vars, $repl, $plugin_language['x_of_y_mb_in_use']);

        $data_array = array();
        $data_array['$space_max_in_user'] = $space_max_in_user;

        $data_array['$lang_new_gallery'] = $plugin_language['new_gallery'];
        $data_array['$lang_gallery_name'] = $plugin_language['gallery_name'];
        $data_array['$lang_actions'] = $plugin_language['actions'];

        $template = $GLOBALS["_template"]->loadTemplate("gallery", "user_user_head", $data_array, $plugin_path);
        echo $template;

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE userID='" . (int)$userID . "'");

        if (mysqli_num_rows($ergebnis) == 0) {
            echo '<tr>' . $plugin_language['no_galleries'] . '</td></tr>';
        }

        for ($i = 1; $ds = mysqli_fetch_array($ergebnis); $i++) {

            $name = $ds['name'];
            $galleryID = $ds['galleryID'];

            $data_array = array();
            $data_array['$galleryID'] = $galleryID;
            $data_array['$name'] = $name;

            $data_array['$lang_edit'] = $plugin_language['edit_gallery'];
            $data_array['$lang_add_img'] = $plugin_language['add_img'];
            $data_array['$lang_really_delete_gallery'] = $plugin_language['really_delete_gallery'];
            $data_array['$lang_delete'] = $plugin_language['delete_gallery'];


            $template = $GLOBALS["_template"]->loadTemplate("gallery", "user_gallery", $data_array, $plugin_path);
            echo $template;
        }

        $template = $GLOBALS["_template"]->loadTemplate("gallery", "user_foot", $data_array, $plugin_path);
        echo $template;
    }
} else {
    redirect('index.php?site=login', '', 0);
}
