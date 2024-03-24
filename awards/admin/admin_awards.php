<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/
# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("awards", $plugin_path);


$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='awards'");
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

if (isset($_POST[ 'save' ])) {
    
    $date = strtotime($_POST['date']);

    if (isset($_POST[ 'squad' ])) {
        $squad = $_POST[ 'squad' ];
    } else {
        $squad = 0;
    }
    $award = $_POST[ 'award' ];
    
    $rang = $_POST[ 'rang' ];
    $info = $_POST[ 'message' ];

    $homepage = $_POST[ 'homepage' ];
    $facebook = $_POST[ 'facebook' ];
    $twitter = $_POST[ 'twitter' ];
    $liga = $_POST[ 'liga' ];
    $steam = $_POST[ 'steam' ];
    $twitch = $_POST[ 'twitch' ];
    $youtube = $_POST[ 'youtube' ];
    $instagram = $_POST[ 'instagram' ];

    safe_query(
        "INSERT INTO
            `" . PREFIX . "plugins_awards` (
                `date`,
                `squadID`,
                `award`,
                `rang`,
                `info`,
                `homepage`,
                `facebook`,
                `twitter`,
                `liga`,
                `steam`,
                `twitch`,
                `youtube`,
                `instagram`
            )
            VALUES(
                '$date',
                '$squad',
                '$award',
                '$rang',
                '$info',
                '$homepage',
                '$facebook',
                '$twitter',
                '$liga',
                '$steam',
                '$twitch',
                '$youtube',
                '$instagram'
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
                                    "UPDATE " . PREFIX . "plugins_awards
                                    SET banner='" . $file . "' WHERE awardID='" . $id . "'"
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
    header("Location: admincenter.php?site=admin_awards");
} elseif (isset($_POST[ 'saveedit' ])) {
    
    $awardID = $_POST[ 'awardID' ];
    $date = strtotime($_POST['date']);
    if (isset($_POST[ 'squad' ])) {
        $squad = $_POST[ 'squad' ];
    } else {
        $squad = 0;
    }
    $award = $_POST[ 'award' ];
    $rang = $_POST[ 'rang' ];
    $info = $_POST[ 'message' ];
    $homepage = $_POST[ 'homepage' ];
    $facebook = $_POST[ "facebook" ];
    $twitter = $_POST[ "twitter" ];
    $liga = $_POST[ "liga" ];
    $steam = $_POST[ "steam" ];
    $twitch = $_POST[ "twitch" ];
    $youtube = $_POST[ "youtube" ];
    $instagram = $_POST[ "instagram" ];
    

    

    safe_query(
        "UPDATE
            `" . PREFIX . "plugins_awards`
        SET
            `date` = '$date',
            `squadID` = '$squad',
            `award` = '$award',
            `rang` = '$rang',
            `info` = '$info',
            `homepage` = '$homepage',
            `facebook` = '$facebook',
            `twitter` = '$twitter',
            `liga` = '$liga',
            `steam` = '$steam',
            `twitch` = '$twitch',
            `youtube` = '$youtube',
            `instagram` = '$instagram'
        WHERE
            awardID = '". (int)$awardID."'"
    );

        $filepath = $plugin_path."/images/";
        $id = $_POST[ 'awardID' ];
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
                                    "UPDATE " . PREFIX . "plugins_awards
                                    SET banner='" . $file . "' WHERE awardID='" . $id . "'"
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
    #}
    header("Location: admincenter.php?site=admin_awards");
} elseif (isset($_GET[ 'delete' ])) {

    $awardID = $_GET[ 'awardID' ];
    safe_query("DELETE FROM `" . PREFIX . "plugins_awards` WHERE `awardID` = '" . (int)$awardID . "'");
    
    $filepath = $plugin_path."images/";
    if (file_exists($filepath . $awardID . '.gif')) {
        @unlink($filepath . $awardID . '.gif');
    }
    if (file_exists($filepath . $awardID . '.jpg')) {
        @unlink($filepath . $awardID . '.jpg');
    }
    if (file_exists($filepath . $awardID . '.png')) {
        @unlink($filepath . $awardID . '.png');
    }
   
    
    header("Location: admincenter.php?site=admin_awards");
}


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = "";
}

if ($action == "new") {
    echo '<div class="card">
    <div class="card-header">
                            <i class="bi bi-award" style="font-size: 1rem;"></i> ' . $plugin_language[ 'awards' ] . '
                        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_awards" class="white">' . $plugin_language[ 'awards' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'new_award' ] . '</li>
  </ol>
</nav>
                        <div class="card-body">';

        $day = "";
        for ($i = 1; $i < 32; $i++) {
            if ($i == date("d", time())) {
                $day .= '<option selected="selected">' . $i . '</option>';
            } else {
                $day .= '<option>' . $i . '</option>';
            }
        }
        $month = "";
        for ($i = 1; $i < 13; $i++) {
            if ($i == date("n", time())) {
                $month .= '<option value="' . $i . '" selected="selected">' . date("M", time()) . '</option>';
            } else {
                $month .= '<option value="' . $i . '">' . date("M", mktime(0, 0, 0, $i, 1, 2000)) . '</option>';
            }
        }
        $year = "";
        for ($i = 2000; $i < 2016; $i++) {
            if ($i == date("Y", time())) {
                $year .= '<option value="' . $i . '" selected="selected">' . date("Y", time()) . '</option>';
            } else {
                $year .= '<option value="' . $i . '">' . $i . '</option>';
            }
        }
        $squads = getgamesquads();

        $data_array = array();
        $data_array['$squads'] = $squads;
        
        $data_array['$edit_award']=$plugin_language['edit_award'];
        $data_array['$lang_date']=$plugin_language['date'];
        $data_array['$lang_squad']=$plugin_language['squad'];
        $data_array['$lang_ranking']=$plugin_language['ranking'];
        $data_array['$lang_award']=$plugin_language['award'];
        $data_array['$lang_information']=$plugin_language['info'];
        $data_array['$save_award']=$plugin_language['save_award'];
        $data_array['$lang_banner']=$plugin_language['banner'];

        $data_array['$lang_homepage']=$plugin_language['homepage'];
        $data_array['$lang_facebook']=$plugin_language['facebook'];
        $data_array['$lang_twitter']=$plugin_language['twitter'];
        $data_array['$lang_liga']=$plugin_language['liga'];
        $data_array['$lang_steam']=$plugin_language['steam'];
        $data_array['$lang_twitch']=$plugin_language['twitch'];
        $data_array['$lang_youtube']=$plugin_language['youtube'];
        $data_array['$lang_instagram']=$plugin_language['instagram'];
        
        $template = $GLOBALS["_template"]->loadTemplate("awards","admin_new", $data_array, $plugin_path);
        echo $template;
    
        echo'</div></div>';
} elseif ($action == "edit") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '<div class="card">
    <div class="card-header">
                            <i class="bi bi-award" style="font-size: 1rem;"></i> ' . $plugin_language[ 'awards' ] . '
                        </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_awards" class="white">' . $plugin_language[ 'awards' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_award' ] . '</li>
  </ol>
</nav>
                        <div class="card-body">';
    $awardID = $_GET[ 'awardID' ];
    
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_awards WHERE awardID='$awardID'");
        $ds = mysqli_fetch_array($ergebnis);


        $day = "";
        for ($i = 1; $i < 32; $i++) {
            if ($i == date("d", $ds[ 'date' ])) {
                $day .= $i;
            }
        }
        $month = "";
        for ($i = 1; $i < 13; $i++) {
            if ($i == date("n", $ds[ 'date' ])) {
                $month .= date("m", $ds[ 'date' ]);
            }
        }
        $year = "";
        for ($i = 2000; $i < 2016; $i++) {
            if ($i == date("Y", $ds[ 'date' ])) {
                $year .= $i;
            }
        }

        $date = $year . '-' . $month . '-' . $day;
        $squads = getgamesquads();
        $squads = str_replace(
            'value="' . $ds[ 'squadID' ] . '"',
            'value="' . $ds[ 'squadID' ] . '" selected="selected"',
            $squads
        );
        $award = htmlspecialchars($ds[ 'award' ]);
        
        $rang = $ds[ 'rang' ];
        $info = htmlspecialchars($ds[ 'info' ]);

        $homepage = htmlspecialchars($ds[ 'homepage' ]);
        $facebook = htmlspecialchars($ds[ 'facebook' ]);
        $twitter = htmlspecialchars($ds[ 'twitter' ]);
        $liga = htmlspecialchars($ds[ 'liga' ]);
        $steam = htmlspecialchars($ds[ 'steam' ]);
        $twitch = htmlspecialchars($ds[ 'twitch' ]);
        $youtube = htmlspecialchars($ds[ 'youtube' ]);
        $instagram = htmlspecialchars($ds[ 'instagram' ]);

        $filepath = $plugin_path."images/";    
          
        if (!empty($ds[ 'banner' ])) {
            $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
        } else {
            $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
        }    

        $data_array = array();
        $data_array['$date'] = $date;
        $data_array['$squads'] = $squads;
        $data_array['$rang'] = $rang;
        $data_array['$award'] = $award;
        $data_array['$homepage'] = $homepage;
        $data_array['$facebook'] = $facebook;
        $data_array['$twitter'] = $twitter;
        $data_array['$liga'] = $liga;
        $data_array['$steam'] = $steam;
        $data_array['$twitch'] = $twitch;
        $data_array['$youtube'] = $youtube;
        $data_array['$instagram'] = $instagram;


        $data_array['$info'] = $info;
        $data_array['$awardID'] = $awardID;
        $data_array['$pic'] = $pic;

        $data_array['$edit_award']=$plugin_language['edit_award'];
        $data_array['$lang_date']=$plugin_language['date'];
        $data_array['$lang_squad']=$plugin_language['squad'];
        $data_array['$lang_ranking']=$plugin_language['ranking'];
        $data_array['$lang_award']=$plugin_language['award'];
        $data_array['$lang_information']=$plugin_language['info'];
        $data_array['$update_award']=$plugin_language['update_award'];
        $data_array['$lang_banner']=$plugin_language['banner'];

        $data_array['$lang_homepage']=$plugin_language['homepage'];
        $data_array['$lang_facebook']=$plugin_language['facebook'];
        $data_array['$lang_twitter']=$plugin_language['twitter'];
        $data_array['$lang_liga']=$plugin_language['liga'];
        $data_array['$lang_steam']=$plugin_language['steam'];
        $data_array['$lang_twitch']=$plugin_language['twitch'];
        $data_array['$lang_youtube']=$plugin_language['youtube'];
        $data_array['$lang_instagram']=$plugin_language['instagram'];
        


        $template = $GLOBALS["_template"]->loadTemplate("awards","admin_edit", $data_array, $plugin_path);
        echo $template;
        
    echo'</div></div>';
} elseif ($action == "showsquad") {
    $squadID = $_GET[ 'squadID' ];
    $page = (isset($_GET[ 'page' ])) ? $_GET[ 'page' ] : 1;
    $sort = (isset($_GET[ 'page' ])) ? $_GET[ 'page' ] : "date";
    $type = (isset($_GET[ 'type' ])) ? $_GET[ 'type' ] : "DESC";

    if (isclanwaradmin($userID) || isnewsadmin($userID)) {
        echo
            '<a href="admincenter.php?site=admin_awards&amp;action=new" class="btn btn-danger">' .
            $plugin_language[ 'new_award' ] . '</a><br><br>';
    }
    $alle = safe_query("SELECT awardID FROM " . PREFIX . "plugins_awards WHERE squadID='$squadID'");
    $gesamt = mysqli_num_rows($alle);
    $pages = 1;
    $max = $maxawards;

    $pages = ceil($gesamt / $max);

    if ($pages > 1) {
        $page_link =
            makepagelink(
                "admincenter.php?site=admin_awards&amp;action=showsquad&amp;squadID=$squadID&amp;sort=$sort&amp;type=$type",
                $page,
                $pages
            );
    } else {
        $page_link = "";
    }
    if ($page == "1") {
        $ergebnis =
            safe_query(
                "SELECT * FROM `" . PREFIX . "awards` WHERE `squadID` = '$squadID' ORDER BY $sort $type LIMIT 0,$max"
            );
        if ($type == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = $page * $max - $max;
        $ergebnis = safe_query(
            "SELECT
                *
            FROM
                `" . PREFIX . "awards`
            WHERE
                `squadID` = '$squadID'
            ORDER BY
                $sort $type
            LIMIT $start,$max"
        );
        if ($type == "DESC") {
            $n = ($gesamt) - $page * $max + $max;
        } else {
            $n = ($gesamt + 1) - $page * $max + $max;
        }
    }
    if ($gesamt) {
        if ($type == "ASC") {
            echo '<a href="admincenter.php?site=admin_awards&amp;action=showsquad&amp;squadID=' . $squadID . '&amp;page=' . $page .
                '&amp;sort=' . $sort . '&amp;type=DESC">' . $plugin_language[ 'sort' ] .
                ':</a> <i class="bi bi-chevron-down" style="font-size: 1rem;"></i>&nbsp;&nbsp;&nbsp;';
        } else {
            echo '<a href="admincenter.php?site=admin_awards&amp;action=showsquad&amp;squadID=' . $squadID . '&amp;page=' . $page .
                '&amp;sort=' . $sort . '&amp;type=ASC">' . $plugin_language[ 'sort' ] .
                ':</a> <i class="bi bi-chevron-up" style="font-size: 1rem;"></i>&nbsp;&nbsp;&nbsp;';
        }

        echo $page_link;
        echo '<br><br>';
        $headdate = '<a class="titlelink" href="admincenter.php?site=admin_awards&amp;action=showsquad&amp;squadID=' . $squadID .
            '&amp;page=' . $page . '&amp;sort=date&amp;type=' . $type . '">' . $plugin_language[ 'date' ] . ':</a>';
        $headsquad = $plugin_language[ 'squad' ] . ':';

        $data_array = array();
        $data_array['$headsquad'] = $headsquad;
        $data_array['$headdate'] = $headdate;
        $awards_head = $GLOBALS["_template"]->replaceTemplate("awards_head", $data_array);
        echo $awards_head;
        $n = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
           
            $date = getformatdate($ds[ 'date' ]);
            $squad = getsquadname($ds[ 'squadID' ]);
            $award = cleartext($ds[ 'award' ]);
            $homepage = $ds[ 'homepage' ];
            $rang = $ds[ 'rang' ];

            if (isclanwaradmin($userID) || isnewsadmin($userID)) {
                $adminaction =
                    '<a href="admincenter.php?site=admin_awards&amp;action=edit&amp;awardID=' .
                    $ds[ 'awardID' ] .'" class="btn btn-danger">'. $plugin_language[ 'edit' ] . '</a>
            <input type="button" onclick="MM_confirm(
                \'really delete this award?\',
                \'awards.php?delete=true&amp;awardID=' . $ds[ 'awardID' ] . '\'
            )" value="' . $plugin_language[ 'delete' ] . '">';
            } else {
                $adminaction = '';
            }

            $data_array = array();
            $data_array['$rang'] = $rang;
            $data_array['$awardID'] = $ds['awardID'];
            $data_array['$award'] = $award;
            $data_array['$squad'] = $squad;
            $data_array['$date'] = $date;
            $data_array['$adminaction'] = $adminaction;
            $awards_content = $GLOBALS["_template"]->replaceTemplate("awards_content", $data_array);
            echo $awards_content;

            unset($result);
            $n++;
        }
        $awards_foot = $GLOBALS["_template"]->replaceTemplate("awards_foot", array());
        echo $awards_foot;
    } else {
        echo $plugin_language[ 'no_entries' ];
    }

} else {


      echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-award" style="font-size: 1rem;"></i> ' . $plugin_language[ 'award' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_awards">' . $plugin_language[ 'award' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_awards&action=new" class="btn btn-primary">' . $plugin_language[ 'new_award' ] . '</a>
    </div>
  </div>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $page = (isset($_GET[ 'page' ])) ? (int)$_GET[ 'page' ] : 1;
    $sort = (isset($_GET[ 'sort' ]) && $_GET[ 'sort' ] == 'squadID') ? "squadID" : "date";
    $type = (isset($_GET[ 'type' ]) && $_GET[ 'type' ] == 'ASC') ? "ASC" : "DESC";

    

    $alle = safe_query("SELECT awardID FROM " . PREFIX . "plugins_awards");
    $gesamt = mysqli_num_rows($alle);

    #$maxawards = $ds[ 'awards' ];
    if (empty($maxawards)) {
        $maxawards = 10;
    }


    $pages = 1;
    $max = $maxawards;
    $pages = ceil($gesamt / $max);

    

    if ($pages > 1) {
        $page_link = makepagelink("admincenter.php?site=admin_awards&sort=$sort&type=$type", $page, $pages);
    } else {
        $page_link = '';
    }

    if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_awards ORDER BY $sort $type LIMIT 0,$max");
        if ($type == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = $page * $max - $max;
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_awards ORDER BY $sort $type LIMIT $start,$max");
        if ($type == "DESC") {
            $n = ($gesamt) - $page * $max + $max;
        } else {
            $n = ($gesamt + 1) - $page * $max + $max;
        }
    }
    if ($gesamt) {
        if ($type == "ASC") {
            echo '<a href="admincenter.php?site=admin_awards&amp;page=' . $page . '&amp;sort=' . $sort . '&amp;type=DESC">' .
                $plugin_language[ 'sort' ] . ':</a> <i class="bi bi-chevron-down" style="font-size: 1rem;"></i>';
        } else {
            echo '<a href="admincenter.php?site=admin_awards&amp;page=' . $page . '&amp;sort=' . $sort . '&amp;type=ASC">' .
                $plugin_language[ 'sort' ] . ':</a> <i class="bi bi-chevron-up style="font-size: 1rem;""></i>';
        }

        echo $page_link;
        echo '<br><br>';
        $headdate =
            '<a class="titlelink" href="admincenter.php?site=admin_awards&amp;page=' . $page . '&amp;sort=date&amp;type=' . $type .
            '">' . $plugin_language[ 'date' ] . ':</a>';
        $headsquad =
            '<a class="titlelink" href="admincenter.php?site=admin_awards&amp;page=' . $page . '&amp;sort=squadID&amp;type=' .
            $type . '">' . $plugin_language[ 'squad' ] . ':</a>';

        $data_array = array();
        $data_array['$headsquad'] = $headsquad;
        $data_array['$headdate'] = $headdate;

        $data_array['$ranking']=$plugin_language['ranking'];
        $data_array['$award']=$plugin_language['award'];

        $template = $GLOBALS["_template"]->loadTemplate("awards","admin_head", $data_array, $plugin_path);
        echo $template;

        $n = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
           
            $date = getformatdate($ds[ 'date' ]);

            $squad =
                '<a href="admincenter.php?site=members&amp;action=showsquad&amp;squadID=' . $ds[ 'squadID' ] . '&amp;page=' .
                $page . '&amp;sort=' . $sort . '&amp;type=' . $type . '">' . getsquadname($ds[ 'squadID' ]) . '</a>';
            $award = $ds[ 'award' ];
            $homepage = $ds[ 'homepage' ];
            $rang = $ds[ 'rang' ];

            
                $adminaction =
                    '<a href="admincenter.php?site=admin_awards&amp;action=edit&amp;awardID=' . $ds[ 'awardID' ] .
                    '" class="btn btn-warning">' . $plugin_language[ 'edit' ] . '</a>
                    
                    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_awards&amp;delete=true&amp;awardID='.$ds['awardID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'award' ] . '</h5>
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
                        ';
            

            $data_array = array();
            $data_array['$rang'] = $rang;
            $data_array['$awardID'] = $ds['awardID'];
            $data_array['$award'] = $award;
            $data_array['$squad'] = $squad;
            $data_array['$date'] = $date;
            $data_array['$adminaction'] = $adminaction;
            $template = $GLOBALS["_template"]->loadTemplate("awards","admin_content", $data_array, $plugin_path);
            echo $template;
            $n++;
        }
        
        $template = $GLOBALS["_template"]->loadTemplate("awards","admin_foot", $data_array, $plugin_path);
        echo $template;
    } else {
        echo $plugin_language[ 'no_entries' ];
    }
}
