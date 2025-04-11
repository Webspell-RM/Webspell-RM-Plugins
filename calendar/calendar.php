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
    $plugin_language = $pm->plugin_language("calendar", $plugin_path);

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
        $plugin_data['$title']=$plugin_language['calendar'];
        $plugin_data['$subtitle']='Calendar';
    
        $template = $GLOBALS["_template"]->loadTemplate("calendar","head", $plugin_data, $plugin_path);
        echo $template;

        
/* define calendar functions */

/* beginn processing file */

if ($action === "saveannounce") {
    
    if (!isclanmember($userID)) {
        die($plugin_language['no_access']);
    }

    $ds = mysqli_fetch_assoc(
        safe_query(
            "SELECT date FROM " . PREFIX . "plugins_calendar WHERE upID=" . (int)$_POST['upID'] . " AND date>" . time()
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
                " . PREFIX . "plugins_calendar_announce
            WHERE
                upID='" . (int)$_POST['upID'] . "'
            AND
                userID='" . (int)$userID."'"
        );

        if (mysqli_num_rows($ergebnis)) {
            $ds = mysqli_fetch_array($ergebnis);
            safe_query(
                "UPDATE
                    " . PREFIX . "plugins_calendar_announce
                SET
                    status='" . $_POST['status'] . "'
                WHERE
                    annID='" . $ds['annID'] . "'"
            );
        } else {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_calendar_announce (
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
        header("Location: index.php?site=calendar&tag=$tag&month=$month&year=$year");
    } else {
        header("Location: index.php?site=calendar");
    }
} elseif ($action === "announce" && isclanmember($userID)) {
    
    if (isset($_GET['upID'])) {
        $upID = (int)$_GET['upID'];

        $data_array = array();
        $data_array['$upID'] = $upID;

        $data_array['$lang_announce_to']=$plugin_language['announce_to'];
        $data_array['$lang_yes']=$plugin_language['yes'];
        $data_array['$lang_no']=$plugin_language['no'];
        $data_array['$lang_perhaps']=$plugin_language['perhaps'];
        $data_array['$lang_save_announcement']=$plugin_language['save_announcement'];

        $template = $GLOBALS["_template"]->loadTemplate("calendar","upcomingannounce", $data_array, $plugin_path);
        echo $template;
    }
} else {
    
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
            <div class="card-body">';

    echo '<a name="event"></a>
            <table class="table">
            <tr>
                <th class="text-center">
                    <a class="titlelink" href="index.php?site=calendar&amp;month=' . $prev . '&amp;year=' . $prev_yr . '"><i class="bi bi-arrow-left-circle" style="font-size: 2rem;"></i></a>
                </th>
                <th class="text-center" colspan="5">
                    <h1>' . $plugin_language[strtolower(date("M", $first_day))] . ' ' . $temp_yr . '</h1>
                </th>
                <th class="text-center">
                    <a class="titlelink" href="index.php?site=calendar&amp;month=' . $next . '&amp;year=' . $next_yr . '"><i class="bi bi-arrow-right-circle" style="font-size: 2rem;"></i></a>
                </th>
            </tr>
            </table>
            <table class="table table-striped-columns">
            <thead>
            <tr>
                <th width="14%" align="center"><div class="d-lg-none">' . $plugin_language['mo'] . '</div><div class="d-none d-lg-block">' . $plugin_language['mon'] . '</div></th>
                <th width="14%" align="center"><div class="d-lg-none">' . $plugin_language['di'] . '</div><div class="d-none d-lg-block">' . $plugin_language['tue'] . '</div></th>
                <th width="14%" align="center"><div class="d-lg-none">' . $plugin_language['mi'] . '</div><div class="d-none d-lg-block">' . $plugin_language['wed'] . '</div></th>
                <th width="14%" align="center"><div class="d-lg-none">' . $plugin_language['do'] . '</div><div class="d-none d-lg-block">' . $plugin_language['thu'] . '</div></th>
                <th width="14%" align="center"><div class="d-lg-none">' . $plugin_language['fr'] . '</div><div class="d-none d-lg-block">' . $plugin_language['fri'] . '</div></th>
                <th width="14%" align="center"><div class="d-lg-none">' . $plugin_language['sa'] . '</div><div class="d-none d-lg-block">' . $plugin_language['sat'] . '</div></th>
                <th width="16%" align="center"><div class="d-lg-none">' . $plugin_language['so'] . '</div><div class="d-none d-lg-block">' . $plugin_language['sun'] . '</div></th>  
             </tr>
            </thead>
          <tbody>';

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

            $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_calendar");
            $anz = mysqli_num_rows($ergebnis);
            if ($anz) {
                $termin = '';
                while ($ds = mysqli_fetch_array($ergebnis)) {
                    if ($ds['type'] == "d") {
                        if (($start_date <= $ds['date'] && $end_date >= $ds['date'])
                            || ($start_date >= $ds['date'] && $end_date <= $ds['enddate'])
                            || ($start_date <= $ds['enddate'] && $end_date >= $ds['enddate'])
                        ) {
                            $termin .= '<div class="d-none d-lg-block"><a class="badge rounded-pill text-bg-success" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event"><i class="bi bi-calendar-event"></i> ' . $plugin_language['event'] . ' ' . $ds['short'] . '</a></div>';
                            $termin .=
                                '<div class="d-lg-none"><a class="badge rounded-pill text-bg-success" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event"><i class="bi bi-calendar-event"></i></a></div>';    
                        }
                    } elseif ($ds['type'] == "t") {
                        if (($start_date <= $ds['date'] && $end_date >= $ds['date'])
                            || ($start_date >= $ds['date'] && $end_date <= $ds['enddate'])
                            || ($start_date <= $ds['enddate'] && $end_date >= $ds['enddate'])
                        ) {
                            $termin .= '<div class="d-none d-lg-block"><a class="badge rounded-pill text-bg-danger" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event"><i class="bi bi-joystick"></i> ' . $plugin_language['training'] . ' </a></div>';

                            $termin .= '<div class="d-lg-none"><a class="badge rounded-pill text-bg-danger" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event"><i class="bi bi-joystick"></i></a></div>';    
                        }
                    } else {
                        if ($ds['date'] >= $start_date && $ds['date'] <= $end_date) {
                            $begin = getformattime($ds['date']);
                            $termin .= '

<div class="d-none d-lg-block"><a class="badge rounded-pill text-bg-info" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '"><i class="bi bi-controller"></i>  ' . $begin . ' ' . $plugin_language['clanwar_to'] . ' ' . $ds['opptag'] . '</a></div>';  

                            $termin .=
                                '
</div><div class="d-lg-none"><a class="badge rounded-pill text-bg-info" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '"><i class="bi bi-controller"></i></a></div>';                                     
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
                    $termin.='<div class="d-none d-lg-block"><a class="badge rounded-pill text-bg-warning" style="width: 100%" href="index.php?site=profile&amp;id='.$dc['userID'].'"><i class="bi bi-cake2"></i> '.getnickname($dc['userID']).' '.$birthdayyears.'</a></div>';
                    $termin.='<div class="d-lg-none"><a class="badge rounded-pill text-bg-warning" style="width: 100%" href="index.php?site=profile&amp;id='.$dc['userID'].'"><i class="bi bi-cake2"></i></a></div>';
                } else {
                    $termin = '';
                }
                }
              }
            }
            
            // DB ABRUF ENDE

            //If date is today, highlight it
            if (($t == date("j")) && ($month == date("n")) && ($year == date("Y"))) {
                #echo '<td style="width:14%;height:80px" class="calendar-day" valign="top"><span style="margin-top: 0px">' . $t . '</span> ' . $termin . '</td>';
                echo '<td height="40" valign="top" bgcolor="#999999"><span class="badge text-bg-success">' . $t . '</span><br>' . $termin . '</td>';
            } else {
                //  If the date is absent ie after 31, print space
                if ($t === ' ') {
                    echo '<td style="width:14%;height:80px" valign="top">&nbsp;</td>';
                } else {
                    echo
                        '<td style="width:14%;height:80px" valign="top">' . $t . ' ' . $termin . '</td>';
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
                <a class="category" href="index.php?site=calendar#event">
                    <strong>' . $plugin_language['today_events'] . '</strong>
                </a>
            </td>
        </tr>
    </tbody>
</table></div></div>';

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
    
    $start_date = mktime(0, 0, 0, $month, $tag, $year);
    $end_date = mktime(23, 59, 59, $month, $tag, $year);
    unset($termin);

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_calendar");
    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if ($ds['type'] == "c") {
                if ($ds['date'] >= $start_date && $ds['date'] <= $end_date) {
                    $date = getformatdate($ds['date']);
                    $time = getformattime($ds['date']);
                    $squad = getsquadname($ds['squad']);
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
                    
                    if (isclanmember($userID) || isanyadmin($userID)) {
                        $anmeldung =
                            safe_query(
                                "SELECT * FROM " . PREFIX . "plugins_calendar_announce WHERE upID='" . (int)$ds['upID'] . "'"
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

                        if (isclanmember($userID) && $ds['date'] > time()) {
                            $announce = '<a class="btn btn-primary" href="index.php?site=calendar&amp;action=announce&amp;upID=' .
                                $ds['upID'] . '">' . $plugin_language['announce_here'] . '</a>';
                        } else {
                            $announce = '';
                        }   
                        
                        } else {
                        $players = $plugin_language['access_member'];
                        $announce = '';
                        
                    }

                    $data_array = array();
                    $data_array['$date'] = $date;
                    $data_array['$time'] = $time;
                    $data_array['$squad'] = $squad;
                    $data_array['$opponent'] = $opponent;
                    $data_array['$league'] = $league;
                    $data_array['$server'] = $server;
                    $data_array['$warinfo'] = $warinfo;
                    $data_array['$announce'] = $announce;
                    $data_array['$players'] = $players;

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
        
                    
                    $template = $GLOBALS["_template"]->loadTemplate("calendar","war_details", $data_array, $plugin_path);
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
                    $dateinfo = $plugin_language['you_have_to_be_clanmember'];
                    if (isclanmember($userID)) {
                      $dateinfo = $ds['dateinfo'];
                    }

                    $players = "";

                    if (isclanmember($userID)) {
                        $anmeldung =
                            safe_query(
                                "SELECT * FROM " . PREFIX . "plugins_calendar_announce WHERE upID='" . (int)$ds['upID'] . "'"
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
                            $announce = '<a class="btn btn-primary" href="index.php?site=calendar&amp;action=announce&amp;upID=' .
                                $ds['upID'] . '">' . $plugin_language['announce_here'] . '</a>';
                        } else {
                            $announce = '';
                        }

                        if (isclanwarsadmin($userID)) {
                            $CAPCLASS = new \webspell\Captcha;
                            $CAPCLASS->createTransaction();
                            $hash = $CAPCLASS->getHash();
                            $adminaction = '<div align="right">
                                <a class="btn btn-warning" href="admincenter.php?site=admin_calendar&amp;action=edittrain&amp;upID=' .
                                $ds['upID'] . '">' .
                                $plugin_language['edit'] . '
                                </a>
                                <input class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $plugin_language[ 'really_delete' ] .
                                    '\', \'admincenter.php?site=admin_calendar&amp;action=delete&amp;upID=' . $ds[ 'upID' ] .
                                    '&amp;captcha_hash=' . $hash . '\')" value="' . $plugin_language[ 'delete' ] . '" />
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


                    $template = $GLOBALS["_template"]->loadTemplate("calendar","train_details", $data_array, $plugin_path);
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
                    $players = "";

                    if (isclanmember($userID)) {
                        $anmeldung =
                            safe_query(
                                "SELECT * FROM " . PREFIX . "plugins_calendar_announce WHERE upID='" . (int)$ds['upID'] . "'"
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
                            $announce = '<a class="btn btn-primary" href="index.php?site=calendar&amp;action=announce&amp;upID=' .
                                $ds['upID'] . '">' . $plugin_language['announce_here'] . '</a>';
                        } else {
                            $announce = '';
                        }

                        
                    } else {
                        $players = $plugin_language['access_member'];
                        $announce = '';
                        
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

                    $template = $GLOBALS["_template"]->loadTemplate("calendar","date_details", $data_array, $plugin_path);
                    echo $template;
                 }
            }
        }
    } else { # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("calendar", $plugin_path);

        echo $plugin_language['no_entries']; 
    }

}
