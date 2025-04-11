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
$plugin_language = $pm->plugin_language("squads", $plugin_path);

$data_array['$title'] = $plugin_language['title'];
$data_array['$subtitle'] = 'Squads';

$template = $GLOBALS["_template"]->loadTemplate("squads", "title", $data_array, $plugin_path);
echo $template;


if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}


if ($action == "show") {

    if (isset($_POST['squadID'])) {
        $onesquadonly = 'WHERE squadID="' . (int)$_POST['squadID'] . '"';
        $visible = "block";
    } elseif (isset($_GET['squadID'])) {
        $onesquadonly = 'WHERE squadID="' . (int)$_GET['squadID'] . '"';
        $visible = "block";
    } else {
        $visible = "none";
        $onesquadonly = '';
    }

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_squads " . $onesquadonly . " ORDER BY sort");
    if (mysqli_num_rows($ergebnis)) {
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $anzmembers = mysqli_num_rows(
                safe_query(
                    "SELECT
                        sqmID
                    FROM
                        " . PREFIX . "plugins_squads_members
                    WHERE squadID='" . $ds['squadID'] . "'"
                )
            );

            $name = '<b>' . $ds['name'] . '</b>';

            if ($ds['icon']) {
                $icon = '/includes/plugins/squads/images/squadicons/' . $ds['icon'] . '';
            } else {
                $icon = '/includes/plugins/squads/images/squadicons/no-image.jpg';
            }

            $info = !empty($ds['info']) ? generateAlert($ds['info'], 'alert-info') : '';
            $squadID = $ds['squadID'];
            $details = str_replace('%squadID%', $squadID, $plugin_language['show_details']);

            if ($ds['gamesquad']) {
                $results = '<a href="index.php?site=clanwars&amp;action=showonly&amp;id=' . $squadID .
                    '&amp;sort=date&amp;only=squad" class="btn btn-primary">' . $plugin_language['results'] .
                    '</a>';
                $awards = '<a href="index.php?site=awards&amp;action=showsquad&amp;squadID=' . $squadID .
                    '&amp;page=1" class="btn btn-primary">' . $plugin_language['awards'] . '</a>';
                $challenge =
                    '<a href="index.php?site=challenge" class="btn btn-primary">' . $plugin_language['challenge'] .
                    '</a>';
            } else {
                $results = '';
                $awards = '';
                $challenge = '';
            }

            if ($anzmembers == 1) {
                $anzmembers = $anzmembers . ' ' . $plugin_language['member'];
            } else {
                $anzmembers = $anzmembers . ' ' . $plugin_language['members'];
            }

            $data_array = array();
            $data_array['$squadID'] = $squadID;
            $data_array['$icon'] = $icon;
            $data_array['$name'] = $name;
            $data_array['$anzmembers'] = $anzmembers;
            $data_array['$results'] = $results;
            $data_array['$awards'] = $awards;
            $data_array['$challenge'] = $challenge;
            $data_array['$info'] = $info;

            $template = $GLOBALS["_template"]->loadTemplate("squads", "head_head", $data_array, $plugin_path);
            echo $template;

            $member =
                safe_query(
                    "SELECT
                        *
                    FROM
                        " . PREFIX . "plugins_squads_members s, " . PREFIX . "user u
                    WHERE
                        s.squadID='" . $ds['squadID'] . "'
                    AND
                        s.userID = u.userID
                    ORDER BY
                        sort"
                );

            $data_array['$squads_nickname'] = $plugin_language['nickname'];
            $data_array['$squads_position'] = $plugin_language['position'];
            $data_array['$squads_contact'] = $plugin_language['contact'];
            $data_array['$squads_activity'] = $plugin_language['activity'];

            $template = $GLOBALS["_template"]->loadTemplate("squads", "head", $data_array, $plugin_path);
            echo $template;


            $i = 1;
            while ($dm = mysqli_fetch_array($member)) {

                $nickname = strip_tags(stripslashes($dm['nickname']));
                $profilid = $dm['userID'];

                $squadID = $ds['squadID'];

                if ($dm['userdescription']) {
                    $userdescription = $dm['userdescription'];
                } else {
                    $userdescription = $plugin_language['no_description'];
                }

                if ($dm['discord']) {
                    $discord = $dm['discord'];
                } else {
                    $discord = 'n/a';
                }

                if ($dm['facebook']) {
                    $facebook = $dm['facebook'];
                } else {
                    $facebook = 'n/a';
                }

                if ($dm['youtube']) {
                    $youtube = $dm['youtube'];
                } else {
                    $youtube = 'n/a';
                }

                if ($dm['steam']) {
                    $steam = $dm['steam'];
                } else {
                    $steam = 'n/a';
                }

                if ($dm['twitch']) {
                    $twitch = $dm['twitch'];
                } else {
                    $twitch = 'n/a';
                }

                if (getemailhide($dm['userID'])) {
                    $email = '';
                } else {
                    $email = '<a class="badge bg-success" style="font-size:12px;" href="mailto:' . mail_protect($dm['email']) . '" data-toggle="tooltip" data-placement="top" title="email"><i class="bi bi-envelope" title="email"></i> email</a>';
                }

                $pm = '';


                if ($loggedin && $userID) {
                    $pm = '<a class="badge bg-success" style="font-size:12px;" href="index.php?site=messenger&amp;action=touser&amp;touser=' . $dm['userID'] . '" data-toggle="tooltip" data-placement="top" title="message"><i class="bi bi-messenger"></i>  Message</a>';
                }

                if (isonline($dm['userID']) == "offline") {
                    $statuspic = '<span class="badge bg-danger">' . $plugin_language['offline'] . '</span>';
                } else {
                    $statuspic = '<span class="badge bg-success">' . $plugin_language['online'] . '</span>';
                }

                $firstname = strip_tags($dm['firstname']);
                $lastname = strip_tags($dm['lastname']);
                $town = strip_tags($dm['town']);
                if ($dm['activity']) {
                    $activity = '<span class="badge bg-success">' . $plugin_language['active'] . '</span>';
                } else {
                    $activity = '<span class="badge bg-warning">' . $plugin_language['inactive'] . '</span>';
                }

                $position = $dm['position'];

                if (file_exists("images/userpics/" . $profilid . ".jpg")) {
                    $userpic = $profilid . ".jpg";
                    $pic_info = $dm['nickname'] . " userpicture";
                } elseif (file_exists("images/userpics/" . $profilid . ".gif")) {
                    $userpic = $profilid . ".gif";
                    $pic_info = $dm['nickname'] . " userpicture";
                } elseif (file_exists("images/userpics/" . $profilid . ".png")) {
                    $userpic = $profilid . ".png";
                    $pic_info = $dm['nickname'] . " userpicture";
                } else {
                    $userpic = "nouserpic.png";
                    $pic_info = "no userpic available!";
                }

                if (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpg")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpg";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpeg")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpeg";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.png")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.png";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.gif")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.gif";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.avif")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.avif";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.webp")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.webp";
                } else {
                    $pic = "/includes/plugins/squads/images/squadicons/no-image.jpg";
                }

                $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_squads_settings");
                $dn = mysqli_fetch_array($settings);

                $data_array = array();
                $data_array['$squadID'] = $squadID;
                $data_array['$userpic'] = $userpic;
                $data_array['$profilid'] = $profilid;
                $data_array['$nickname'] = $nickname;
                $data_array['$statuspic'] = $statuspic;
                $data_array['$position'] = $position;
                $data_array['$email'] = $email;
                $data_array['$pm'] = $pm;
                $data_array['$pic'] = $pic;
                $data_array['$discord'] = $discord;
                $data_array['$facebook'] = $facebook;
                $data_array['$youtube'] = $youtube;
                $data_array['$steam'] = $steam;
                $data_array['$twitch'] = $twitch;
                $data_array['$activity'] = $activity;
                $data_array['$firstname'] = $firstname;
                $data_array['$nickname'] = $nickname;
                $data_array['$lastname'] = $lastname;
                $data_array['$position'] = $position;
                $data_array['$activity'] = $activity;
                $data_array['$myclanname'] = $myclanname;
                $data_array['$town'] = $town;
                $data_array['$memberID'] = $dm['userID'];
                $data_array['$userpic'] = $userpic;
                $data_array['$userdescription'] = $userdescription;

                $data_array['$squads_info'] = $plugin_language['info'];
                $data_array['$squads_position'] = $plugin_language['position'];
                $data_array['$squads_status'] = $plugin_language['status'];
                $data_array['$squads_contact'] = $plugin_language['contact'];
                $data_array['$squads_town'] = $plugin_language['town'];
                $data_array['$squads_nickname'] = $plugin_language['nickname'];
                $data_array['$seeprofile'] = $plugin_language['profile'];

                if (!empty(@$dn['squads'] == 1) !== false) {

                    $template = $GLOBALS["_template"]->loadTemplate("squads", "content_team_one", $data_array, $plugin_path);
                    echo $template;
                } else {


                    $template = $GLOBALS["_template"]->loadTemplate("squads", "content_team_two", $data_array, $plugin_path);
                    echo $template;
                }

                $i++;
            }

            $data_array = array();
            $data_array['$details'] = $details;

            $template = $GLOBALS["_template"]->loadTemplate("squads", "content_foot", $data_array, $plugin_path);
            echo $template;
        }
        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("squads", "foot", $data_array, $plugin_path);
        echo $template;
    } else {
        echo ($plugin_language['no_entries']);
    }
} else {
    /*===================================*/

    if (isset($_POST['squadID'])) {
        $onesquadonly = 'WHERE squadID="' . (int)$_POST['squadID'] . '"';
        $visible = "block";
    } elseif (isset($_GET['squadID'])) {
        $onesquadonly = 'WHERE squadID="' . (int)$_GET['squadID'] . '"';
        $visible = "block";
    } else {
        $visible = "none";
        $onesquadonly = '';
    }

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_squads " . $onesquadonly . " ORDER BY sort");
    if (mysqli_num_rows($ergebnis)) {
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $anzmembers = mysqli_num_rows(
                safe_query(
                    "SELECT
                        sqmID
                    FROM
                        " . PREFIX . "plugins_squads_members
                    WHERE squadID='" . $ds['squadID'] . "'"
                )
            );

            $name = '<b>' . $ds['name'] . '</b>';

            if ($ds['icon']) {
                $icon = '/includes/plugins/squads/images/squadicons/' . $ds['icon'] . '';
            } else {
                $icon = '/includes/plugins/squads/images/squadicons/no-image.jpg';
            }

            $info = !empty($ds['info']) ? generateAlert($ds['info'], 'alert-info') : '';
            $squadID = $ds['squadID'];
            $details = str_replace('%squadID%', $squadID, $plugin_language['show_details']);

            if ($ds['gamesquad']) {
                $results = '<a href="index.php?site=clanwars&amp;action=showonly&amp;id=' . $squadID .
                    '&amp;sort=date&amp;only=squad" class="btn btn-primary">' . $plugin_language['results'] .
                    '</a>';
                $awards = '<a href="index.php?site=awards&amp;action=showsquad&amp;squadID=' . $squadID .
                    '&amp;page=1" class="btn btn-primary">' . $plugin_language['awards'] . '</a>';
                $challenge =
                    '<a href="index.php?site=challenge" class="btn btn-primary">' . $plugin_language['challenge'] .
                    '</a>';
            } else {
                $results = '';
                $awards = '';
                $challenge = '';
            }

            if ($anzmembers == 1) {
                $anzmembers = $anzmembers . ' ' . $plugin_language['member'];
            } else {
                $anzmembers = $anzmembers . ' ' . $plugin_language['members'];
            }

            $data_array = array();
            $data_array['$squadID'] = $squadID;
            $data_array['$icon'] = $icon;
            $data_array['$name'] = $name;
            $data_array['$anzmembers'] = $anzmembers;
            $data_array['$results'] = $results;
            $data_array['$awards'] = $awards;
            $data_array['$challenge'] = $challenge;
            $data_array['$info'] = $info;

            $template = $GLOBALS["_template"]->loadTemplate("squads", "head_head", $data_array, $plugin_path);
            echo $template;

            $member =
                safe_query(
                    "SELECT
                        *
                    FROM
                        " . PREFIX . "plugins_squads_members s, " . PREFIX . "user u
                    WHERE
                        s.squadID='" . $ds['squadID'] . "'
                    AND
                        s.userID = u.userID
                    ORDER BY
                        sort"
                );

            $data_array['$squads_nickname'] = $plugin_language['nickname'];
            $data_array['$squads_position'] = $plugin_language['position'];
            $data_array['$squads_contact'] = $plugin_language['contact'];
            $data_array['$squads_activity'] = $plugin_language['activity'];

            $template = $GLOBALS["_template"]->loadTemplate("squads", "head", $data_array, $plugin_path);
            echo $template;


            $i = 1;
            while ($dm = mysqli_fetch_array($member)) {

                $nickname = strip_tags(stripslashes($dm['nickname']));
                $profilid = $dm['userID'];

                $squadID = $ds['squadID'];

                if ($dm['userdescription']) {
                    $userdescription = $dm['userdescription'];
                } else {
                    $userdescription = $plugin_language['no_description'];
                }

                if ($dm['discord']) {
                    $discord = $dm['discord'];
                } else {
                    $discord = 'n/a';
                }

                if ($dm['facebook']) {
                    $facebook = $dm['facebook'];
                } else {
                    $facebook = 'n/a';
                }

                if ($dm['youtube']) {
                    $youtube = $dm['youtube'];
                } else {
                    $youtube = 'n/a';
                }

                if ($dm['steam']) {
                    $steam = $dm['steam'];
                } else {
                    $steam = 'n/a';
                }

                if ($dm['twitch']) {
                    $twitch = $dm['twitch'];
                } else {
                    $twitch = 'n/a';
                }

                if (getemailhide($dm['userID'])) {
                    $email = '';
                } else {
                    $email = '<a class="badge bg-success" style="font-size:12px;" href="mailto:' . mail_protect($dm['email']) . '" data-toggle="tooltip" data-placement="top" title="email"><i class="bi bi-envelope" title="email"></i> email</a>';
                }

                $pm = '';


                if ($loggedin && $userID) {
                    $pm = '<a class="badge bg-success" style="font-size:12px;" href="index.php?site=messenger&amp;action=touser&amp;touser=' . $dm['userID'] . '" data-toggle="tooltip" data-placement="top" title="message"><i class="bi bi-messenger"></i>  Message</a>';
                }

                if (isonline($dm['userID']) == "offline") {
                    $statuspic = '<span class="badge bg-danger">' . $plugin_language['offline'] . '</span>';
                } else {
                    $statuspic = '<span class="badge bg-success">' . $plugin_language['online'] . '</span>';
                }

                $firstname = strip_tags($dm['firstname']);
                $lastname = strip_tags($dm['lastname']);
                $town = strip_tags($dm['town']);
                if ($dm['activity']) {
                    $activity = '<span class="badge bg-success">' . $plugin_language['active'] . '</span>';
                } else {
                    $activity = '<span class="badge bg-warning">' . $plugin_language['inactive'] . '</span>';
                }

                $position = $dm['position'];

                if (file_exists("images/userpics/" . $profilid . ".jpg")) {
                    $userpic = $profilid . ".jpg";
                    $pic_info = $dm['nickname'] . " userpicture";
                } elseif (file_exists("images/userpics/" . $profilid . ".gif")) {
                    $userpic = $profilid . ".gif";
                    $pic_info = $dm['nickname'] . " userpicture";
                } elseif (file_exists("images/userpics/" . $profilid . ".png")) {
                    $userpic = $profilid . ".png";
                    $pic_info = $dm['nickname'] . " userpicture";
                } else {
                    $userpic = "nouserpic.png";
                    $pic_info = "no userpic available!";
                }

                $squadID = $dm['squadID'];

                if (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpg")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpg";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpeg")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.jpeg";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.png")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.png";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.gif")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.gif";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.avif")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.avif";
                } elseif (file_exists("./includes/plugins/squads/images/squadicons/" . $squadID . "_small.webp")) {
                    $pic = "/includes/plugins/squads/images/squadicons/" . $squadID . "_small.webp";
                } else {
                    $pic = "/includes/plugins/squads/images/squadicons/no-image.jpg";
                }

                $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_squads_settings");
                $dn = mysqli_fetch_array($settings);

                $data_array = array();
                $data_array['$squadID'] = $squadID;
                $data_array['$userpic'] = $userpic;
                $data_array['$profilid'] = $profilid;
                $data_array['$nickname'] = $nickname;
                $data_array['$statuspic'] = $statuspic;
                $data_array['$position'] = $position;
                $data_array['$email'] = $email;
                $data_array['$pm'] = $pm;
                $data_array['$discord'] = $discord;
                $data_array['$facebook'] = $facebook;
                $data_array['$youtube'] = $youtube;
                $data_array['$steam'] = $steam;
                $data_array['$twitch'] = $twitch;
                $data_array['$activity'] = $activity;
                $data_array['$firstname'] = $firstname;
                $data_array['$nickname'] = $nickname;
                $data_array['$lastname'] = $lastname;
                $data_array['$position'] = $position;
                $data_array['$activity'] = $activity;
                $data_array['$pic'] = $pic;
                $data_array['$myclanname'] = $myclanname;
                $data_array['$town'] = $town;
                $data_array['$memberID'] = $dm['userID'];
                $data_array['$userpic'] = $userpic;
                $data_array['$userdescription'] = $userdescription;

                $data_array['$squads_info'] = $plugin_language['info'];
                $data_array['$squads_position'] = $plugin_language['position'];
                $data_array['$squads_status'] = $plugin_language['status'];
                $data_array['$squads_contact'] = $plugin_language['contact'];
                $data_array['$squads_town'] = $plugin_language['town'];
                $data_array['$squads_nickname'] = $plugin_language['nickname'];
                $data_array['$seeprofile'] = $plugin_language['profile'];

                if (!empty(@$dn['squads'] == 1) !== false) {

                    $template = $GLOBALS["_template"]->loadTemplate("squads", "content_team_one", $data_array, $plugin_path);
                    echo $template;
                } else {

                    $template = $GLOBALS["_template"]->loadTemplate("squads", "content_team_two", $data_array, $plugin_path);
                    echo $template;
                }

                $i++;
            }

            $data_array = array();
            $data_array['$details'] = $details;

            $template = $GLOBALS["_template"]->loadTemplate("squads", "content_foot", $data_array, $plugin_path);
            echo $template;
        }
        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("squads", "foot", $data_array, $plugin_path);
        echo $template;
    } else {
        echo ($plugin_language['no_entries']);
        echo '</div>';
    }
}
