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
    $plugin_language = $pm->plugin_language("calendar", $plugin_path);

    $ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='breaking_news'");
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

if (isset($_GET['month'])) {
        $month = (int)$_GET['month'];
    } else {
        $month = date("m");
    }

    if (isset($_GET['year'])) {
        $year = (int)$_GET['year'];
    } else {
        $year = date("Y");
    }

    if (isset($_GET['tag'])) {
        $tag = (int)$_GET['tag'];
    } else {
        $tag = date("d");
    }


        $plugin_data= array();
        $plugin_data['$calendar']=$plugin_language['calendar'];

    
        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","head", $plugin_data, $plugin_path);
        echo $template;

        if (isclanwarsadmin($userID)) {
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

$db = mysqli_fetch_assoc(safe_query("SELECT * FROM " . PREFIX . "settings"));

            if ($db[ 'birthday' ] == '1') {
        $birthday = '<input class="form-check-input" id="activeactive" type="radio" name="radio1" value="birthday" checked="checked" />';
    } else {
        $birthday = '<input class="form-check-input" id="birthday" type="radio" name="radio1" value="birthday">';
    }




        echo '<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-7">
      <a href="admincenter.php?site=admin_calendar&amp;action=addwar" class="btn btn-primary">' . $plugin_language['add_clanwar'] . '</a>
      <a href="admincenter.php?site=admin_calendar&amp;action=adddate" class="btn btn-primary">' . $plugin_language['add_event'] . ' </a>
      <a href="admincenter.php?site=admin_calendar&amp;action=addtrain" class="btn btn-primary">' . $plugin_language['add_train'] . ' </a>
    </div>
  </div>

 
            <form class="form-horizontal" method="post" action="admincenter.php?site=admin_calendar" enctype="multipart/form-data">
            <div class="mb-3 row">
    <label class="col-md-2 control-label text-left">' . $plugin_language['show birthday'] . ':</label>
    <div class="col-md-7 form-check form-switch" style="padding: 0px 43px;">
'.$birthday.'&nbsp;&nbsp;
          <input type="hidden" name="captcha_hash" value="'.$hash.'" />
            <button class="btn btn-success" type="submit" name="save_birthday"  />' . $plugin_language['edit'] . '</button>
            
            </div>
        </form><br><br>';
        }
/* define calendar functions */

/* beginn processing file */

if ($action === "savewar") {
    
    if (!isclanwarsadmin($userID)) {
        die($plugin_language['no_access']);
    }

    $date = strtotime($_POST['date']);

    $squad = $_POST['squad'];
    $opponent = $_POST['opponent'];
    $opptag = $_POST['opptag'];
    $opphp = $_POST['opphp'];
    $maps = $_POST['maps'];
    $matchtype = $_POST['matchtype'];
    $spielanzahl = $_POST['spielanzahl'];
    $gametype = $_POST['gametype'];
    $server = $_POST['server'];
    $league = $_POST['league'];
    $leaguehp = $_POST['leaguehp'];
    $warinfo = $_POST['message'];
    $chID = $_POST['chID'];
    if (isset($_POST['messages'])) {
        $messages = true;
    } else {
        $messages = false;
    }

    safe_query(
        "INSERT INTO
            " . PREFIX . "plugins_upcoming (
                `date`,
                `type`,
                `squad`,
                `opponent`,
                `opptag`,
                `opphp`,
                `maps`,
                `matchtype`,
                `spielanzahl`,
                `gametype`,
                `server`,
                `league`,
                `leaguehp`,
                `warinfo`
            )
            values (
                '" . $date . "',
                'c',
                '" . $squad . "',
                '" . $opponent . "',
                '" . $opptag . "',
                '" . $opphp . "',
                '" . $maps . "',
                '" . $matchtype . "',
                '" . $spielanzahl . "',
                '" . $gametype . "',
                '" . $server . "',
                '" . $league . "',
                '" . $leaguehp . "',
                '" . $warinfo . "'
            )"
    );

    if (isset($chID) && $chID > 0) {
        safe_query("DELETE FROM " . PREFIX . "plugins_fight_us_challenge WHERE chID='" . $chID . "'");
    }

    if ($messages) {
        $replace = array(
            '%date%' => getformatdate($date),
            '%opp_hp%' => $opphp,
            '%opponent%' => $opponent,
            '%league_hp%' => $leaguehp,
            '%league%' => $league,
            '%warinfo%' => $warinfo
        );
        #$ergebnis = safe_query("SELECT userID FROM " . PREFIX . "plugins_squads_members WHERE squadID='$squad'");
        #while ($ds = mysqli_fetch_array($ergebnis)) {
           # $id = $ds['userID'];


$ergebnis=safe_query("SELECT userID FROM ".PREFIX."plugins_squads_members WHERE squadID='".$squad."'");
    while($ds=mysqli_fetch_array($ergebnis)) {
      $touser[]=$ds['userID'];
    }

       
    #if(isset($touser[0]) != "") {

    $fromuser = $GLOBALS[ 'ip' ]; 
      foreach($touser as $id) {










            $title = $plugin_language['clanwar_message_title'];
            $message = ''.$plugin_language[ 'hello' ].' '.getnickname($id).',<br>'.$plugin_language['clanwar_message'].'';
            $message = str_replace(array_keys($replace), array_values($replace), $message);
            sendmessage($id, $title, $message);
        }
    }
#}
    header(
        "Location: admincenter.php?site=admin_calendar&tag=" .
        date("j", $date) . "&month=" .
        date("n", $date) . "&year=" .
        date("Y", $date)
    );
} elseif ($action === "delete") {
    
    if (!isclanwarsadmin($userID)) {
        die($plugin_language['no_access']);
    }
    $upID = $_GET['upID'];

    safe_query("DELETE FROM " . PREFIX . "plugins_upcoming WHERE upID='$upID'");
    safe_query("DELETE FROM " . PREFIX . "plugins_upcoming_announce WHERE upID='$upID'");
    header("Location: admincenter.php?site=admin_calendar");

} elseif (isset($_POST[ 'save_birthday' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
               
        if(@$_POST['radio1']=="birthday") {
        $active = 1;
        $deactivated = 0;
         
        } else {
        $active = 0;
        $deactivated = 0;
        }
        
        safe_query(
            "UPDATE
                `" . PREFIX . "settings`
            SET
                
                `birthday` = '" . $active . "'"
        );

        redirect("admincenter.php?site=admin_calendar", "", 0);
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }

} elseif ($action === "saveannounce") {
    
    if (!isclanmember($userID)) {
        die($plugin_language['no_access']);
    }

    $ds = mysqli_fetch_assoc(
        safe_query(
            "SELECT date FROM " . PREFIX . "plugins_upcoming WHERE upID=" . (int)$_POST['upID'] . " AND date>" . time()
        )
    );
    if (isset($ds['date'])) {
        $tag = date('d', $ds['date']);
        $month = date('m', $ds['date']);
        $year = date('y', $ds['date']);

        $ergebnis = safe_query(
            "SELECT
                annID
            FROM
                " . PREFIX . "plugins_upcoming_announce
            WHERE
                upID='" . (int)$_POST['upID'] . "'
            AND
                userID='" . (int)$userID."'"
        );

        if (mysqli_num_rows($ergebnis)) {
            $ds = mysqli_fetch_array($ergebnis);
            safe_query(
                "UPDATE
                    " . PREFIX . "plugins_upcoming_announce
                SET
                    status='" . $_POST['status'] . "'
                WHERE
                    annID='" . $ds['annID'] . "'"
            );
        } else {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_upcoming_announce (
                        `upID`,
                        `userID`,
                        `status`
                    )
                values (
                    '" . (int)$_POST['upID'] . "',
                    '$userID',
                    '" . $_POST['status'] . "'
                ) "
            );
        }
        header("Location: admincenter.php?site=admin_calendar&tag=$tag&month=$month&year=$year");
    } else {
        header("Location: admincenter.php?site=admin_calendar");
    }
} elseif ($action === "saveeditdate") {
    
    if (!isclanwarsadmin($userID)) {
        die($plugin_language['no_access']);
    }

    $date_start = strtotime($_POST['date-start']);
    $date_end = strtotime($_POST['date-end']);

    safe_query(
        "UPDATE
            " . PREFIX . "plugins_upcoming
        SET
            date='$date_start',
            enddate='$date_end',
            short='" . $_POST['short'] . "',
            title='" . $_POST['title'] . "',
            location='" . $_POST['location'] . "',
            locationhp='" . $_POST['locationhp'] . "',
            dateinfo='" . $_POST['message'] . "'
        WHERE
            upID='" . (int)$_POST['upID']."'"
    );

    header(
        "Location: admincenter.php?site=admin_calendar&tag=" .
        date("j", $date_start) . "&month=" .
        date("n", $date_start) . "&year=" .
        date("Y", $date_start)
    );
} elseif ($action === "savedate") {
    
    if (!isclanwarsadmin($userID)) {
        die($plugin_language['no_access']);
    }

    $date_start = strtotime($_POST['date-start']);
    $date_end = strtotime($_POST['date-end']);

    safe_query(
        "INSERT INTO
            " . PREFIX . "plugins_upcoming (
                `date`,
                `type`,
                `enddate`,
                `short`,
                `title`,
                `location`,
                `locationhp`,
                `dateinfo`
            )
            values (
                '$date_start',
                'd',
                '" . $date_end . "',
                '" . $_POST['short'] . "',
                '" . $_POST['title'] . "',
                '" . $_POST['location'] . "',
                '" . $_POST['locationhp'] . "',
                '" . $_POST['message'] . "'
            )"
    );
    redirect(
        "admincenter.php?site=admin_calendar&amp;tag=" .
        date("j", $date_start) . "&amp;month=" .
        date("n", $date_start) . "&amp;year=" .
        date("Y", $date_start),
        "",
        0
    );
} elseif ($action === "savetrain") {
    
    if (!isclanwarsadmin($userID)) {
        die($plugin_language['no_access']);
    }

    $date_start = strtotime($_POST['date-start']);
    $date_end = strtotime($_POST['date-end']);

    safe_query(
        "INSERT INTO
            " . PREFIX . "plugins_upcoming (
                `date`,
                `type`,
                `enddate`,
                `short`,
                `server`,
                `dateinfo`
            )
            values (
                '$date_start',
                't',
                '" . $date_end . "',
                '" . $_POST['short'] . "',
                '" . $_POST['server'] . "',
                '" . $_POST['message'] . "'
            )"
    );
    redirect(
        "admincenter.php?site=admin_calendar&amp;tag=" .
        date("j", $date_start) . "&amp;month=" .
        date("n", $date_start) . "&amp;year=" .
        date("Y", $date_start),
        "",
        0
    );
} elseif ($action === "saveedittrain") {
    
    if (!isclanwarsadmin($userID)) {
        die($plugin_language['no_access']);
    }

    $date_start = strtotime($_POST['date-start']);
    $date_end = strtotime($_POST['date-end']);

    safe_query(
        "UPDATE
            " . PREFIX . "plugins_upcoming
        SET
            date='$date_start',
            enddate='$date_end',
            short='" . $_POST['short'] . "',
            server='" . $_POST['server'] . "',
            dateinfo='" . $_POST['message'] . "'
        WHERE
            upID='" . (int)$_POST['upID']."'"
    );

    header(
        "Location: admincenter.php?site=admin_calendar&tag=" .
        date("j", $date_start) . "&month=" .
        date("n", $date_start) . "&year=" .
        date("Y", $date_start)
    );
} elseif ($action === "saveeditwar") {
    
    if (!isclanwarsadmin($userID)) {
        die($plugin_language['no_access']);
    }

    $upID = $_POST['upID'];

    $date = strtotime($_POST['date']);
    $squad = $_POST['squad'];
    $opponent = $_POST['opponent'];
    $opptag = $_POST['opptag'];
    $opphp = $_POST['opphp'];
    $maps = $_POST['maps'];
    $matchtype = $_POST['matchtype'];
    $spielanzahl = $_POST['spielanzahl'];
    $gametype = $_POST['gametype'];
    $server = $_POST['server'];
    $league = $_POST['league'];
    $leaguehp = $_POST['leaguehp'];
    $warinfo = $_POST['message'];

    safe_query(
        "UPDATE
            " . PREFIX . "plugins_upcoming
        SET
            `date` = '$date',
            `type` = 'c',
            `squad` = '$squad',
            `opponent` = '$opponent',
            `opptag` = '$opptag',
            `opphp` = '$opphp',
            `maps` = '$maps',
            `matchtype` = '$matchtype',
            `spielanzahl` = '$spielanzahl',
            `gametype` = '$gametype',
            `server` = '$server',
            `league` = '$league',
            `leaguehp` = '$leaguehp',
            `warinfo` = '$warinfo'
        WHERE
            `upID` = '$upID' "
    );

    header(
        "Location: admincenter.php?site=admin_calendar&tag=" .
        date("j", $date) . "&month=" .
        date("n", $date) . "&year=" .
        date("Y", $date)
    );
} elseif ($action === "addwar") {
    
    if (isclanwarsadmin($userID)) {
        $squads = getgamesquads();

        $opphp = "https://";

        $chID = 0;

        $date = date("d.m.Y H:i");

        if (isset($_GET['chID'])) {
            $chID = (int)$_GET['chID'];
            $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_fight_us_challenge WHERE chID='" . $chID . "'");
            $ds = mysqli_fetch_array($ergebnis);
            $date = date("d.m.Y H:i", $ds['cwdate']);

            $squads = str_replace(" selected=\"selected\"", "", $squads);
            $squads = str_replace(
                '<option value="' . $ds['squadID'] . '">',
                '<option value="' . $ds['squadID'] . '" selected="selected">',
                $squads
            );

            $map = $ds['map'];
            $matchtype = $ds['matchtype'];
            $spielanzahl = $ds['spielanzahl'];
            $gametype = $ds['gametype'];
            $server = $ds['server'];
            $opponent = $ds['opponent'];
            $league = $ds['league'];
            $info = $ds['info'];
            $opphp = $ds['opphp'];
            $opptag = $ds['opponents'];

        } else {
            $map = '';
            $matchtype = '';
            $spielanzahl = '';
            $gametype = '';
            $server = '';
            $opponent = '';
            $league = '';
            $info = '';
			$opptag = '';
        }

        $data_array = array();
		$data_array['$opptag'] = $opptag;
        $data_array['$date'] = $date;
        $data_array['$squads'] = $squads;
        $data_array['$opponent'] = $opponent;
        $data_array['$opphp'] = $opphp;
        $data_array['$server'] = $server;
        $data_array['$info'] = $info;
        $data_array['$chID'] = $chID;
        $data_array['$userID'] = $userID;
        $data_array['$league'] = $league;

        $data_array['$map'] = $map;
        $data_array['$matchtype'] = $matchtype;
        $data_array['$spielanzahl'] = $spielanzahl;
        $data_array['$gametype'] = $gametype;


        $data_array['$lang_new_war']=$plugin_language['new_war'];
        $data_array['$lang_date_time']=$plugin_language['date_time'];
        $data_array['$lang_squad']=$plugin_language['squad'];
        $data_array['$lang_league']=$plugin_language['league'];
        $data_array['$lang_leaguehp']=$plugin_language['leaguehp'];
        $data_array['$lang_opponent']=$plugin_language['opponent'];
        $data_array['$lang_opponenttag']=$plugin_language['opponenttag'];
        $data_array['$lang_opponenthp']=$plugin_language['opponenthp'];
        $data_array['$lang_maps']=$plugin_language['maps'];
        $data_array['$lang_server']=$plugin_language['server'];
        $data_array['$lang_information']=$plugin_language['information'];
        $data_array['$lang_save_war']=$plugin_language['save_war'];
        $data_array['$lang_send_message']=$plugin_language['send_message'];

        $data_array['$lang_gametype'] = $plugin_language['gametype'];
        $data_array['$lang_number_of_players'] = $plugin_language['number_of_players'];
        $data_array['$lang_matchtype'] = $plugin_language['matchtype'];


        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","war_new", $data_array, $plugin_path);
        echo $template;
        
    } else {
        redirect('admincenter.php?site=admin_calendar', $plugin_language['no_access']);
    }
} elseif ($action === "editwar") {
    $_language->readModule('calendar');
    if (isclanwarsadmin($userID)) {
        
        $upID = $_GET['upID'];
        $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_upcoming WHERE upID='$upID'"));

        $date = date("d.m.Y H:i", $ds['date']);

        $squads = getgamesquads();
        $squads = str_replace(
            'value="' . $ds['squad'] . '"',
            'value="' . $ds['squad'] . '" selected="selected"',
            $squads
        );
        $league = htmlspecialchars($ds['league']);
        $leaguehp = htmlspecialchars($ds['leaguehp']);
        $opponent = htmlspecialchars($ds['opponent']);
        $opptag = htmlspecialchars($ds['opptag']);
        $opphp = htmlspecialchars($ds['opphp']);
        $server = htmlspecialchars($ds['server']);
        $warinfo = htmlspecialchars($ds['warinfo']);

        $maps = htmlspecialchars($ds['maps']);
        $matchtype = htmlspecialchars($ds['matchtype']);
        $spielanzahl = htmlspecialchars($ds['spielanzahl']);
        $gametype = htmlspecialchars($ds['gametype']);
        

        $data_array = array();
        $data_array['$date'] = $date;
        $data_array['$squads'] = $squads;
        $data_array['$league'] = $league;
        $data_array['$leaguehp'] = $leaguehp;
        $data_array['$opponent'] = $opponent;
        $data_array['$opptag'] = $opptag;
        $data_array['$opphp'] = $opphp;
        $data_array['$server'] = $server;
        $data_array['$warinfo'] = $warinfo;
        $data_array['$upID'] = $upID;

        $data_array['$maps'] = $maps;
        $data_array['$matchtype'] = $matchtype;
        $data_array['$spielanzahl'] = $spielanzahl;
        $data_array['$gametype'] = $gametype;
        
        $data_array['$lang_edit_war']=$plugin_language['editwar'];
        $data_array['$lang_date_time']=$plugin_language['date_time'];
        $data_array['$lang_squad']=$plugin_language['squad'];
        $data_array['$lang_league']=$plugin_language['league'];
        $data_array['$lang_leaguehp']=$plugin_language['leaguehp'];
        $data_array['$lang_opponent']=$plugin_language['opponent'];
        $data_array['$lang_opponenttag']=$plugin_language['opponenttag'];
        $data_array['$lang_opponenthp']=$plugin_language['opponenthp'];
        $data_array['$lang_maps']=$plugin_language['maps'];
        $data_array['$lang_server']=$plugin_language['server'];
        $data_array['$lang_information']=$plugin_language['information'];
        $data_array['$lang_updatewar']=$plugin_language['updatewar'];

        $data_array['$lang_gametype'] = $plugin_language['gametype'];
        $data_array['$lang_number_of_players'] = $plugin_language['number_of_players'];
        $data_array['$lang_matchtype'] = $plugin_language['matchtype'];

        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","war_edit", $data_array, $plugin_path);
        echo $template;
    } else {
        redirect('admincenter.php?site=admin_calendar', $plugin_language['no_access']);
    }
} elseif ($action === "adddate") {
    $_language->readModule('calendar');
    if (isclanwarsadmin($userID)) {
        
        $date = date("d.m.Y H:i");

        $squads = getgamesquads();
        
        $data_array = array();
        $data_array['$date'] = $date;
        
        $data_array['$lang_new_date']=$plugin_language['new_date'];
        $data_array['$lang_start_datetime']=$plugin_language['start_datetime'];
        $data_array['$lang_end_datetime']=$plugin_language['end_datetime'];
        $data_array['$lang_shorttitle']=$plugin_language['shorttitle'];
        $data_array['$lang_displayed_in']=$plugin_language['displayed_in'];
        $data_array['$lang_longtitle']=$plugin_language['longtitle'];
        $data_array['$lang_location']=$plugin_language['location'];
        $data_array['$lang_homepage']=$plugin_language['homepage'];
        $data_array['$lang_information']=$plugin_language['information'];
        $data_array['$lang_save_event']=$plugin_language['save_event'];

        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","date_new", $data_array, $plugin_path);
        echo $template;
        
    } else {
        redirect('admincenter.php?site=admin_calendar', $plugin_language['no_access']);
    }
} elseif ($action === "editdate") {
    $_language->readModule('calendar');
    if (isclanwarsadmin($userID)) {
        
        $upID = $_GET['upID'];
        $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_upcoming WHERE upID='$upID'"));

        $date_start = date("d.m.Y H:i", $ds['date']);
        $date_end = date("d.m.Y H:i", $ds['enddate']);

        $short = htmlspecialchars($ds['short']);
        $title = htmlspecialchars($ds['title']);
        $location = htmlspecialchars($ds['location']);
        $locationhp = htmlspecialchars($ds['locationhp']);
        $dateinfo = htmlspecialchars($ds['dateinfo']);

        $data_array = array();
        $data_array['$date_start'] = $date_start;
        $data_array['$date_end'] = $date_end;
        $data_array['$short'] = $short;
        $data_array['$title'] = $title;
        $data_array['$location'] = $location;
        $data_array['$locationhp'] = $locationhp;
        $data_array['$dateinfo'] = $dateinfo;
        $data_array['$upID'] = $upID;

        $data_array['$lang_editevent']=$plugin_language['editevent'];
        $data_array['$lang_start_datetime']=$plugin_language['start_datetime'];
        $data_array['$lang_end_datetime']=$plugin_language['end_datetime'];
        $data_array['$lang_shorttitle']=$plugin_language['shorttitle'];
        $data_array['$lang_displayed_in']=$plugin_language['displayed_in'];
        $data_array['$lang_longtitle']=$plugin_language['longtitle'];
        $data_array['$lang_location']=$plugin_language['location'];
        $data_array['$lang_homepage']=$plugin_language['homepage'];
        $data_array['$lang_information']=$plugin_language['information'];
        $data_array['$lang_updateevent']=$plugin_language['updateevent'];

        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","date_edit", $data_array, $plugin_path);
        echo $template;
        
    } else {
        redirect('admincenter.php?site=admin_calendar', $plugin_language['no_access']);
    }
} elseif ($action === "addtrain") {
    $_language->readModule('calendar');
    if (isclanwarsadmin($userID)) {
        
        $date = date("d.m.Y H:i");

        $squads = getgamesquads();
        
        $data_array = array();
        $data_array['$date'] = $date;
        
        $data_array['$lang_new_date']=$plugin_language['new_date'];
        $data_array['$lang_start_datetime']=$plugin_language['start_datetime'];
        $data_array['$lang_end_datetime']=$plugin_language['end_datetime'];
        $data_array['$lang_shorttitle']=$plugin_language['shorttitle'];
        $data_array['$lang_displayed_in']=$plugin_language['displayed_in'];
        $data_array['$lang_longtitle']=$plugin_language['longtitle'];
        $data_array['$lang_server']=$plugin_language['server'];
        $data_array['$lang_information']=$plugin_language['information'];
        $data_array['$lang_save_event']=$plugin_language['save_event'];
        $data_array['$lang_addtrain']=$plugin_language['add_train'];

        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar_train","new", $data_array, $plugin_path);
        echo $template;
        
    } else {
        redirect('admincenter.php?site=admin_calendar', $plugin_language['no_access']);
    }
} elseif ($action === "edittrain") {
    $_language->readModule('calendar');
    if (isclanwarsadmin($userID)) {
        
        $upID = $_GET['upID'];
        $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_upcoming WHERE upID='$upID'"));

        $date_start = date("d.m.Y H:i", $ds['date']);
        $date_end = date("d.m.Y H:i", $ds['enddate']);

        $short = htmlspecialchars($ds['short']);
        $server = htmlspecialchars($ds['server']);
        $dateinfo = htmlspecialchars($ds['dateinfo']);

        $data_array = array();
        $data_array['$date_start'] = $date_start;
        $data_array['$date_end'] = $date_end;
        $data_array['$short'] = $short;
        $data_array['$server'] = $server;
        $data_array['$dateinfo'] = $dateinfo;
        $data_array['$upID'] = $upID;

        $data_array['$lang_start_datetime']=$plugin_language['start_datetime'];
        $data_array['$lang_end_datetime']=$plugin_language['end_datetime'];
        $data_array['$lang_shorttitle']=$plugin_language['shorttitle'];
        $data_array['$lang_displayed_in']=$plugin_language['displayed_in'];
        $data_array['$lang_server']=$plugin_language['server'];
        $data_array['$lang_information']=$plugin_language['information'];
        $data_array['$lang_updateevent']=$plugin_language['updateevent'];
        $data_array['$lang_edittrain']=$plugin_language['edit_train'];
        $data_array['$lang_updatetrain']=$plugin_language['updatetrain'];


        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar_train","edit", $data_array, $plugin_path);
        echo $template;
        
    } else {
        redirect('admincenter.php?site=admin_calendar', $plugin_language['no_access']);
    }
} elseif ($action === "announce" && isclanmember($userID)) {
    $_language->readModule('calendar');

    if (isset($_GET['upID'])) {
        $upID = (int)$_GET['upID'];

        $data_array = array();
        $data_array['$upID'] = $upID;

        $data_array['$lang_announce_to']=$plugin_language['announce_to'];
        $data_array['$lang_yes']=$plugin_language['yes'];
        $data_array['$lang_no']=$plugin_language['no'];
        $data_array['$lang_perhaps']=$plugin_language['perhaps'];
        $data_array['$lang_save_announcement']=$plugin_language['save_announcement'];

        $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","upcomingannounce", $data_array, $plugin_path);
        echo $template;
    }
} else {
    $_language->readModule('calendar');

    

    if (isset($_GET['month'])) {
        $month = (int)$_GET['month'];
    } else {
        $month = date("m");
    }

    if (isset($_GET['year'])) {
        $year = (int)$_GET['year'];
    } else {
        $year = date("Y");
    }

    if (isset($_GET['tag'])) {
        $tag = (int)$_GET['tag'];
    } else {
        $tag = date("d");
    }

    global $dates, $first_day, $start_day, $_language;

    $first_day = mktime(0, 0, 0, $month, 1, $year);
    $start_day = date("w", $first_day);
    if ($start_day == 0) {
        $start_day = 7;
    }
    $res = getdate($first_day);
    $month_name = $res["month"];
    $no_days_in_month = date("t", $first_day);

    //If month's first day does not start with first Sunday, fill table cell with a space
    for ($i = 1; $i <= $start_day; $i++) {
        $dates[1][$i] = " ";
    }

    $row = 1;
    $col = $start_day;
    $num = 1;
    while ($num <= 31) {
        if ($num > $no_days_in_month) {
            break;
        } else {
            $dates[$row][$col] = $num;
            if (($col + 1) > 7) {
                $row++;
                $col = 1;
            } else {
                $col++;
            }

            $num++;
        }
    }

    $mon_num = date("n", $first_day);
    $temp_yr = $next_yr = $prev_yr = $year;

    $prev = $mon_num - 1;
    if ($prev < 10) {
        $prev = "0" . $prev;
    }
    $next = $mon_num + 1;
    if ($next < 10) {
        $next = "0" . $next;
    }

    //If January is currently displayed, month previous is December of previous year
    if ($mon_num == 1) {
        $prev_yr = $year - 1;
        $prev = 12;
    }

    //If December is currently displayed, month next is January of next year
    if ($mon_num == 12) {
        $next_yr = $year + 1;
        $next = 1;
    }

    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("calendar", $plugin_path);

    echo '<div class="card">
    <div class="card-body">
    <!--<table class="table">
        <tr>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=01">' .
                $plugin_language['jan'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=02">' .
                $plugin_language['feb'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=03">' .
                $plugin_language['mar'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=04">' .
                $plugin_language['apr'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=05">' .
                $plugin_language['may'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=06">' .
                $plugin_language['jun'] . '</a></td>
        </tr>
        <tr>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=07">' .
                $plugin_language['jul'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=08">' .
                $plugin_language['aug'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=09">' .
                $plugin_language['sep'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=10">' .
                $plugin_language['oct'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=11">' .
                $plugin_language['nov'] . '</a></td>
            <td align="center"><a class="category btn btn-primary btn-sm" href="admincenter.php?site=admin_calendar&amp;month=12">' .
                $plugin_language['dec'] . '</a></td>
        </tr>
    </table>-->';

    echo '<a name="event"></a><table class="table table-bordered">
    <tr>
        <th class="text-center">
            <!--<a class="titlelink" href="admincenter.php?site=admin_calendar&amp;month=' . $prev . '&amp;year=' . $prev_yr .
            '">&laquo; ' .
            mb_substr($plugin_language[strtolower(date('M', mktime(0, 0, 0, $prev, 1, $prev_yr)))], 0, 3) . '</a>-->
            <a class="titlelink" href="admincenter.php?site=admin_calendar&amp;month=' . $prev . '&amp;year=' . $prev_yr . '"><i class="bi bi-arrow-left-circle" style="font-size: 2rem;"></i></a>
        </th>
        <th class="text-center" colspan="5"><h5>' .
            $plugin_language[strtolower(date("M", $first_day))] . ' ' . $temp_yr . '</h5>
        </th>
        <th class="text-center">
            <!--<a class="titlelink" href="admincenter.php?site=admin_calendar&amp;month=' . $next . '&amp;year=' . $next_yr . '">' .
                        mb_substr($plugin_language[strtolower(date('M', mktime(0, 0, 0, $next, 1, $next_yr)))], 0, 3) .
            ' &raquo;</a>-->
            <a class="titlelink" href="admincenter.php?site=admin_calendar&amp;month=' . $next . '&amp;year=' . $next_yr . '"><i class="bi bi-arrow-right-circle" style="font-size: 2rem;"></i></a>
        </th>
    </tr>
    <tr>
        <td width="14%" align="center">' . $plugin_language['mon'] . '</td>
        <td width="14%" align="center">' . $plugin_language['tue'] . '</td>
        <td width="14%" align="center">' . $plugin_language['wed'] . '</td>
        <td width="14%" align="center">' . $plugin_language['thu'] . '</td>
        <td width="14%" align="center">' . $plugin_language['fri'] . '</td>
        <td width="14%" align="center">' . $plugin_language['sat'] . '</td>
        <td width="16%" align="center">' . $plugin_language['sun'] . '</td>
    </tr>
    <tr>';

    $days = date("t", mktime(0, 0, 0, $month, 1, $year)); //days of selected month
    switch ($days) {
        case 28:
            $end = ($start_day > 1) ? 5 : 4;
            break;
        case 29:
            $end = 5;
            break;
        case 30:
            $end = ($start_day == 7) ? 6 : 5;
            break;
        case 31:
            $end = ($start_day > 5) ? 6 : 5;
            break;
        default:
            $end = 6;
    }
    $count = 0;
    for ($row = 1; $row <= $end; $row++) {
        for ($col = 1; $col <= 7; $col++) {
            if (!isset($dates[$row][$col])) {
                $dates[$row][$col] = " ";
            }
            if (!strcmp($dates[$row][$col], " ")) {
                $count++;
            }

            $t = $dates[$row][$col];
            if ($t < 10) {
                $tag = "0$t";
            } else {
                $tag = $t;
            }

            // DATENBANK ABRUF
            $start_date = mktime(0, 0, 0, $month, (int)$t, $year);
            $end_date = mktime(23, 59, 59, $month, (int)$t, $year);

            unset($termin);

            $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_upcoming");
            $anz = mysqli_num_rows($ergebnis);
            if ($anz) {
                $termin = '';
                while ($ds = mysqli_fetch_array($ergebnis)) {
                    if ($ds['type'] == "d") {
                        if (($start_date <= $ds['date'] && $end_date >= $ds['date'])
                            || ($start_date >= $ds['date'] && $end_date <= $ds['enddate'])
                            || ($start_date <= $ds['enddate'] && $end_date >= $ds['enddate'])
                        ) {
                            $termin .=
                                '<a class="badge text-bg-success" style="width: 100%" href="admincenter.php?site=admin_calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event"><i class="bi bi-calendar-event"></i> ' . $plugin_language['headtrain'] . ': ' . $ds['short'] . '</a><br>';
                        }
                    } elseif ($ds['type'] == "t") {
                        if (($start_date <= $ds['date'] && $end_date >= $ds['date'])
                            || ($start_date >= $ds['date'] && $end_date <= $ds['enddate'])
                            || ($start_date <= $ds['enddate'] && $end_date >= $ds['enddate'])
                        ) {
                            $termin .=
                                '<a class="badge text-bg-danger" style="width: 100%" href="admincenter.php?site=admin_calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event"><i class="bi bi-joystick"></i> ' . $plugin_language['headtrain'] . '</a><br>';
                        }
                    } else {
                        if ($ds['date'] >= $start_date && $ds['date'] <= $end_date) {
                            $begin = getformattime($ds['date']);
                            $termin .=
                                '<a class="badge text-bg-info" style="width: 100%" href="admincenter.php?site=admin_calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '"><i class="bi bi-controller"></i> ' . $begin . ' ' . $plugin_language['clanwar_to'] . ' ' . $ds['opptag'] . '</a><br>';
                        }
                    }
                }
            } else {
                $termin = "<br><br>";
            }
            //Geburtstage
            $ergebnisgeb = safe_query("SELECT * FROM " . PREFIX . "user");
            $anz3 = mysqli_num_rows($ergebnisgeb);
            if($anz3) {
              while ($dc = mysqli_fetch_array($ergebnisgeb)) {
                $geb = explode("-",$dc['birthday']);
                $start = mktime(0, 0, 0, (int)$geb['1'], (int)$geb['2'], (int)$year);
                $end2 = mktime(23, 59, 59, (int)$geb['1'], (int)$geb['2'], (int)$year);


                $res =
                  safe_query(
                    "SELECT
                      TIMESTAMPDIFF(YEAR, birthday, NOW()) AS age
                    FROM
                      " . PREFIX . "user
                    WHERE
                      userID = '" . (int)$dc['userID']."'"
                  );
                $cur = mysqli_fetch_array($res);
                $birthdayyears = "(".(int)$cur[ 'age' ]." ".$plugin_language['years'].")";

                if(($start_date<=$start && $end_date>=$start) || ($start_date>=$start && $end_date<=$end2) || ($start_date<=$end2 && $end_date>=$end2)) { 

                $settings = safe_query("SELECT * FROM " . PREFIX . "settings");
                $db = mysqli_fetch_array($settings);

                if ($db[ 'birthday' ] == '1') {
                     $termin.='<a class="badge bg-warning text-dark" style="width: 100%" href="index.php?site=profile&amp;id='.$dc['userID'].'"><i class="bi bi-cake2"></i> '.getnickname($dc['userID']).' '.$birthdayyears.'</a>
                            <br />';
                } else {
                    $termin = '';
                }
                }
              }
            }
            
            // DB ABRUF ENDE

            //If date is today, highlight it
            if (($t == date("j")) && ($month == date("n")) && ($year == date("Y"))) {
                echo '<td height="40" valign="top" bgcolor="#999999"><span class="badge text-bg-danger">' . $t . '</span><br>' . $termin . '</td>';
            } else {
                //  If the date is absent ie after 31, print space
                if ($t === ' ') {
                    echo '<td height="40" bgcolor="#e9e9e9" valign="top">&nbsp;</td>';
                } else {
                    echo
                        '<td height="40" valign="top">' . $t . '<br>' . $termin . '</td>';
                }
            }
        }
        if (($row + 1) != ($end + 1)) {
            echo '</tr><tr>';
        } else {
            echo '</tr>';
        }
    }
    echo '<tr>
            <td colspan="7" align="center">
                <a class="category" href="admincenter.php?site=admin_calendar#event">
                    <strong>' . $plugin_language['today_events'] . '</strong>
                </a>
            </td>
        </tr>
    </table><br><br></div></div>';

    if (isset($_GET['month'])) {
        $month = (int)$_GET['month'];
    } else {
        $month = date("m");
    }

    if (isset($_GET['year'])) {
        $year = (int)$_GET['year'];
    } else {
        $year = date("Y");
    }

    if (isset($_GET['tag'])) {
        $tag = (int)$_GET['tag'];
    } else {
        $tag = date("d");
    }

    global $wincolor;
    global $loosecolor;
    global $drawcolor;
    global $userID;
    global $_language;

    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("calendar", $plugin_path);
    $_language->readModule('calendar');

    $start_date = mktime(0, 0, 0, $month, $tag, $year);
    $end_date = mktime(23, 59, 59, $month, $tag, $year);
    unset($termin);

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_upcoming");
    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if ($ds['type'] == "c") {
                if ($ds['date'] >= $start_date && $ds['date'] <= $end_date) {
                    $date = getformatdate($ds['date']);
                    $time = getformattime($ds['date']);
                    $squad = getsquadname($ds['squad']);
                    $cwtitle = $plugin_language['clanwardetails'];

                    $opponent = ' <a href="' . $ds['opphp'] . '" target="_blank">' .
                        $ds['opptag'] . ' / ' . $ds['opponent'] . '</a>';
                    $maps = $ds['maps'];
                    $matchtype = $ds['matchtype'];
                    $spielanzahl = $ds['spielanzahl'];
                    $gametype = $ds['gametype'];
                    $server = $ds['server'];
                    $league = '<a href="' . $ds['leaguehp'] . '" target="_blank">' . $ds['league'] .
                        '</a>';
                    if (isclanmember($userID)) {
                        $warinfo = $ds['warinfo'];
                    } else {
                        $warinfo = $plugin_language['you_have_to_be_clanmember'];
                    }
                    $players = "";
                    $announce = "";
                    $adminaction = '';
                    if (isclanmember($userID) || isanyadmin($userID)) {
                        $anmeldung =
                            safe_query(
                                "SELECT * FROM " . PREFIX . "plugins_upcoming_announce WHERE upID='" . $ds['upID'] . "'"
                            );
                        if (mysqli_num_rows($anmeldung)) {
                            $i = 1;
                            while ($da = mysqli_fetch_array($anmeldung)) {
                                if ($da['status'] == "y") {
                                    $fontcolor = 'btn btn-success btn-sm';
                                } elseif ($da['status'] == "n") {
                                    $fontcolor = 'btn btn-danger btn-sm';
                                } else {
                                    $fontcolor = 'btn btn-warning btn-sm';
                                }

                                if ($i > 1) {
                                    $players .= ', <a href="index.php?site=profile&amp;id=' . $da['userID'] .
                                        '"><font class="' . $fontcolor . '">' . getnickname($da['userID']) .
                                        '</font></a>';
                                } else {
                                    $players .= '<a href="index.php?site=profile&amp;id=' . $da['userID'] .
                                        '"><font class="' . $fontcolor . '">' . getnickname($da['userID']) .
                                        '</font></a>';
                                }
                                $i++;
                            }
                        } else {
                            $players = $plugin_language['no_announced'];
                        }

                        if (issquadmember($userID, $ds['squad']) && $ds['date'] > time()) { 
                            $announce
                                = '<a class="btn btn-primary" href="admincenter.php?site=admin_calendar&amp;action=announce&amp;upID=' .
                                $ds['upID'] . '">' .
                                $plugin_language['announce_here'] . '
                                </a>';
                        } else {
                            $announce = "";
                        }

                        if (isclanwarsadmin($userID)) {
                            $CAPCLASS = new \webspell\Captcha;
                            $CAPCLASS->createTransaction();
                            $hash = $CAPCLASS->getHash();
                            $adminaction = '<div class="text-end">
                                <a href="admincenter.php?site=admin_calendar&amp;action=editwar&amp;upID=' . $ds['upID'] . '"
                                    class="btn btn-warning">
                                    ' . $plugin_language['edit'] . '
                                </a>

                                <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_calendar&amp;action=delete&amp;upID=' . $ds[ 'upID' ] . '&amp;captcha_hash=' . $hash . '">
                                    ' . $plugin_language['delete'] . '
                                    </button>
                                    <!-- Button trigger modal END-->
                                
                                     <!-- Modal -->
                                <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">'.$cwtitle.'</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
                                      </div>
                                      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language['close'] . '</button>
                                        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- Modal END -->
                              </div>';
                        } else {
                            $adminaction = '';
                        }
                    } else {
                        $players = $plugin_language['access_member'];
                    }

                    $data_array = array();
                    $data_array['$cwtitle'] = $cwtitle;
                    $data_array['$date'] = $date;
                    $data_array['$time'] = $time;
                    $data_array['$squad'] = $squad;
                    $data_array['$opponent'] = $opponent;
                    $data_array['$league'] = $league;
                    $data_array['$server'] = $server;
                    $data_array['$warinfo'] = $warinfo;
                    $data_array['$announce'] = $announce;
                    $data_array['$players'] = $players;
                    $data_array['$adminaction'] = $adminaction;

                    $data_array['$maps'] = $maps;
                    $data_array['$matchtype'] = $matchtype;
                    $data_array['$spielanzahl'] = $spielanzahl;
                    $data_array['$gametype'] = $gametype;
                    
                    $data_array['$lang_clanwardetails']=$plugin_language['clanwardetails'];
                    $data_array['$lang_date_time']=$plugin_language['date_time'];
                    $data_array['$lang_squad']=$plugin_language['squad'];
                    $data_array['$lang_opponent']=$plugin_language['opponent'];
                    $data_array['$lang_league']=$plugin_language['league'];
                    $data_array['$lang_maps']=$plugin_language['maps'];
                    $data_array['$lang_server']=$plugin_language['server'];
                    $data_array['$lang_information']=$plugin_language['information'];
                    $data_array['$lang_announcements']=$plugin_language['announcements'];
                    $data_array['$lang_status']=$plugin_language['status'];
                    $data_array['$lang_yes']=$plugin_language['yes'];
                    $data_array['$lang_no']=$plugin_language['no'];
                    $data_array['$lang_perhaps']=$plugin_language['perhaps'];

                    $data_array['$lang_gametype'] = $plugin_language['gametype'];
                    $data_array['$lang_number_of_players'] = $plugin_language['number_of_players'];
                    $data_array['$lang_matchtype'] = $plugin_language['matchtype'];
        
                    
                    $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","war_details", $data_array, $plugin_path);
                    echo $template;
                    
                }
            } elseif ($ds['type'] == "t") {
                if (($start_date <= $ds['date'] && $end_date >= $ds['date'])
                    || ($start_date >= $ds['date'] && $end_date <= $ds['enddate'])
                    || ($start_date <= $ds['enddate'] && $end_date >= $ds['enddate'])
                ) {
                    $date = getformatdate($ds['date']);
                    $time = getformattime($ds['date']);
                    $enddate = getformatdate($ds['enddate']);
                    $endtime = getformattime($ds['enddate']);
                    $short = $ds['short'];
                    $server = $ds['server'];
                    $dateinfo = $ds['dateinfo'];
                    $players = "";

                    if (isclanmember($userID)) {
                        $anmeldung =
                            safe_query(
                                "SELECT * FROM " . PREFIX . "plugins_upcoming_announce WHERE upID='" . (int)$ds['upID'] . "'"
                            );
                        if (mysqli_num_rows($anmeldung)) {
                            $i = 1;
                            while ($da = mysqli_fetch_array($anmeldung)) {
                                if ($da['status'] == "y") {
                                    $fontcolor = 'btn btn-success btn-sm';
                                } elseif ($da['status'] == "n") {
                                    $fontcolor = 'btn btn-danger btn-sm';
                                } else {
                                    $fontcolor = 'btn btn-warning btn-sm';
                                }

                                if ($i > 1) {
                                    $players .= ', <a href="index.php?site=profile&amp;id=' . $da['userID'] . '">
                                        <span class="' . $fontcolor . '">
                                            ' . getnickname($da['userID']) . '
                                        </span>
                                    </a>';
                                } else {
                                    $players .= '<a href="index.php?site=profile&amp;id=' . $da['userID'] . '">
                                        <span class="' . $fontcolor . '">
                                            ' . getnickname($da['userID']) .
                                        '</span>
                                    </a>';
                                }
                                $i++;
                            }
                        } else {
                            $players = $plugin_language['no_announced'];
                        }

                        if (isclanmember($userID) && $ds['date'] > time()) {
                            $announce = '<a class="btn btn-primary" href="admincenter.php?site=admin_calendar&amp;action=announce&amp;upID=' .
                                $ds['upID'] . '">' . $plugin_language['announce_here'] . '</a>';
                        } else {
                            $announce = '';
                        }

                        if (isclanwarsadmin($userID)) {
                            $CAPCLASS = new \webspell\Captcha;
                            $CAPCLASS->createTransaction();
                            $hash = $CAPCLASS->getHash();
                            $adminaction = '<div class="text-end">
                                <a class="btn btn-warning" href="admincenter.php?site=admin_calendar&amp;action=edittrain&amp;upID=' .
                                $ds['upID'] . '">' .
                                $plugin_language['edit'] . '
                                </a>
                                <!-- Button trigger modal -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_calendar&amp;action=delete&amp;upID=' . $ds[ 'upID' ] . '&amp;captcha_hash=' . $hash . '">
                            ' . $plugin_language['delete'] . '
                            </button>
                                                        <!-- Button trigger modal END-->
                        
                             <!-- Modal -->
                        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">'.$short.'</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
                              </div>
                              <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language['close'] . '</button>
                                <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Modal END -->
                              </div>';
                        } else {
                            $adminaction = '';
                        }
                    } else {
                        $players = $plugin_language['access_member'];
                        $announce = '';
                        $adminaction = '';
                    }

                    $data_array = array();
                    $data_array['$short'] = $short;
                    $data_array['$date'] = $date;
                    $data_array['$time'] = $time;
                    $data_array['$enddate'] = $enddate;
                    $data_array['$endtime'] = $endtime;
                    $data_array['$server'] = $server;
                    $data_array['$dateinfo'] = $dateinfo;
                    $data_array['$announce'] = $announce;
                    $data_array['$players'] = $players;
                    $data_array['$adminaction'] = $adminaction;

                    $data_array['$lang_eventdetails']=$plugin_language['eventdetails'];
                    $data_array['$lang_start_datetime']=$plugin_language['start_datetime'];
                    $data_array['$lang_end_datetime']=$plugin_language['end_datetime'];
                    $data_array['$lang_server']=$plugin_language['server'];
                    $data_array['$lang_information']=$plugin_language['information'];
                    $data_array['$lang_announcements']=$plugin_language['announcements'];
                    $data_array['$lang_status']=$plugin_language['status'];
                    $data_array['$lang_yes']=$plugin_language['yes'];
                    $data_array['$lang_no']=$plugin_language['no'];
                    $data_array['$lang_perhaps']=$plugin_language['perhaps'];
                    $data_array['$lang_headtrain']=$plugin_language['headtrain'];


                    $template = $GLOBALS["_template"]->loadTemplate("admin_calendar_train","details", $data_array, $plugin_path);
                    echo $template;
                }
            } else {
                if (($start_date <= $ds['date'] && $end_date >= $ds['date'])
                    || ($start_date >= $ds['date'] && $end_date <= $ds['enddate'])
                    || ($start_date <= $ds['enddate'] && $end_date >= $ds['enddate'])
                ) {
                    $date = getformatdate($ds['date']);
                    $time = getformattime($ds['date']);
                    $enddate = getformatdate($ds['enddate']);
                    $endtime = getformattime($ds['enddate']);
                    $title = $ds['title'];
                    $location =
                        '<a href="' . $ds['locationhp'] . '" target="_blank">' . $ds['location'] .
                        '</a>';
                    $dateinfo = $ds['dateinfo'];
                    $dateinfo = $dateinfo; 
                    #$dateinfo = $ds['upID'];
                    $players = "";

                    if (isclanmember($userID)) {
                        $anmeldung =
                            safe_query(
                                "SELECT * FROM " . PREFIX . "plugins_upcoming_announce WHERE upID='" . (int)$ds['upID'] . "'"
                            );
                        if (mysqli_num_rows($anmeldung)) {
                            $i = 1;
                            while ($da = mysqli_fetch_array($anmeldung)) {
                                if ($da['status'] == "y") {
                                    $fontcolor = 'btn btn-success btn-sm';
                                } elseif ($da['status'] == "n") {
                                    $fontcolor = 'btn btn-danger btn-sm';
                                } else {
                                    $fontcolor = 'btn btn-warning btn-sm';
                                }

                                if ($i > 1) {
                                    $players .= ', <a href="index.php?site=profile&amp;id=' . $da['userID'] . '">
                                        <span class="' . $fontcolor . '">
                                            ' . getnickname($da['userID']) . '
                                        </span>
                                    </a>';
                                } else {
                                    $players .= '<a href="index.php?site=profile&amp;id=' . $da['userID'] . '">
                                        <span class="' . $fontcolor . '">
                                            ' . getnickname($da['userID']) .
                                        '</span>
                                    </a>';
                                }
                                $i++;
                            }
                        } else {
                            $players = $plugin_language['no_announced'];
                        }

                        if (isclanmember($userID) && $ds['date'] > time()) {
                            $announce = '<a class="btn btn-primary" href="admincenter.php?site=admin_calendar&amp;action=announce&amp;upID=' .
                                $ds['upID'] . '">' . $plugin_language['announce_here'] . '</a>';
                        } else {
                            $announce = '';
                        }

                        if (isclanwarsadmin($userID)) {
                            $CAPCLASS = new \webspell\Captcha;
                            $CAPCLASS->createTransaction();
                            $hash = $CAPCLASS->getHash();
                            $adminaction = '<div class="text-end">
                                <a class="btn btn-warning" href="admincenter.php?site=admin_calendar&amp;action=editdate&amp;upID=' .
                                $ds['upID'] . '">' .
                                $plugin_language['edit'] . '
                                </a>
                                        <!-- Button trigger modal -->
                               <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_calendar&amp;action=delete&amp;upID=' . $ds[ 'upID' ] . '&amp;captcha_hash=' . $hash . '">
                               ' . $plugin_language['delete'] . '
                               </button>
                               <!-- Button trigger modal END-->
                           
                                <!-- Modal -->
                           <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                             <div class="modal-dialog">
                               <div class="modal-content">
                                 <div class="modal-header">
                                   <h5 class="modal-title" id="exampleModalLabel">'.$title.'</h5>
                                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
                                 </div>
                                 <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
                                 </div>
                                 <div class="modal-footer">
                                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language['close'] . '</button>
                                   <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                                 </div>
                               </div>
                             </div>
                           </div>
                           <!-- Modal END -->
                                </div>';
                        } else {
                            $adminaction = '';
                        }
                    } else {
                        $players = $plugin_language['access_member'];
                        $announce = '';
                        $adminaction = '';
                    }

                    $data_array = array();
                    $data_array['$title'] = $title;
                    $data_array['$date'] = $date;
                    $data_array['$time'] = $time;
                    $data_array['$enddate'] = $enddate;
                    $data_array['$endtime'] = $endtime;
                    $data_array['$location'] = $location;
                    $data_array['$dateinfo'] = $dateinfo;
                    $data_array['$announce'] = $announce;
                    $data_array['$players'] = $players;
                    $data_array['$adminaction'] = $adminaction;

                    $data_array['$lang_eventdetails']=$plugin_language['eventdetails'];
                    $data_array['$lang_start_datetime']=$plugin_language['start_datetime'];
                    $data_array['$lang_end_datetime']=$plugin_language['end_datetime'];
                    $data_array['$lang_location']=$plugin_language['location'];
                    $data_array['$lang_information']=$plugin_language['information'];
                    $data_array['$lang_announcements']=$plugin_language['announcements'];
                    $data_array['$lang_status']=$plugin_language['status'];
                    $data_array['$lang_yes']=$plugin_language['yes'];
                    $data_array['$lang_no']=$plugin_language['no'];
                    $data_array['$lang_perhaps']=$plugin_language['perhaps'];

                    $template = $GLOBALS["_template"]->loadTemplate("admin_calendar","date_details", $data_array, $plugin_path);
                    echo $template;
                }
            }
        }
    } else {
        echo $plugin_language['no_entries']; 
    }

}
