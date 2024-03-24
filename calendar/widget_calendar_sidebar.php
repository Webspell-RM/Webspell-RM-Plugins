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

        $plugin_data= array();
        $plugin_data['$title']=$plugin_language['calendar'];
        $plugin_data['$subtitle']='Calendar';
    
        $template = $GLOBALS["_template"]->loadTemplate("calendar","head", $plugin_data, $plugin_path);
        echo $template;


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
    <div class="text-center">

  <h5> '.$plugin_language[strtolower(date("M", $first_day))] . ' ' . $temp_yr . ' </h5>';

    echo '<a name="event"></a><table class="table table-striped-columns">
    <tr>
    <tr>
        <td width="14%" align="center">' . $plugin_language['mo'] . '</td>
        <td width="14%" align="center">' . $plugin_language['di'] . '</td>
        <td width="14%" align="center">' . $plugin_language['mi'] . '</td>
        <td width="14%" align="center">' . $plugin_language['do'] . '</td>
        <td width="14%" align="center">' . $plugin_language['fr'] . '</td>
        <td width="14%" align="center">' . $plugin_language['sa'] . '</td>
        <td width="14%" align="center">' . $plugin_language['so'] . '</td>
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
                            $begin = getformattime($ds['date']);
                            $termin .=
                                '<a class="badge bg-success text-white" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event" data-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="' . $begin . ' Event: ' . $ds['short'] . '"><i class="bi bi-calendar-event"></i></a><br>';
                        }
                    } elseif ($ds['type'] == "t") {
                        if (($start_date <= $ds['date'] && $end_date >= $ds['date'])
                            || ($start_date >= $ds['date'] && $end_date <= $ds['enddate'])
                            || ($start_date <= $ds['enddate'] && $end_date >= $ds['enddate'])
                        ) {
                            $begin = getformattime($ds['date']);
                            $termin .= '<a class="badge bg-danger text-white" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '#event" data-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="' . $begin . ' Training"><i class="bi bi-joystick"></i></a><br>';    
                        }
                    } else {
                        if ($ds['date'] >= $start_date && $ds['date'] <= $end_date) {
                            $begin = getformattime($ds['date']);
                            $termin .=
                                '<a class="badge bg-info text-dark" style="width: 100%" href="index.php?site=calendar&amp;tag=' . $t . '&amp;month=' . $month . '&amp;year=' .
                                $year . '" data-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="' . $begin . ' Clanwar to ' . $ds['opptag'] . '"><i class="bi bi-controller"></i></a><br>';
                        }
                    }
                }
            } else {
                $termin = "<br><br>";
            }
            // DB ABRUF ENDE

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
                    $termin.='<a class="badge bg-warning text-dark" href="index.php?site=profile&amp;id='.$dc['userID'].'" data-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.getnickname($dc['userID']).' '.$birthdayyears.'"><i class="bi bi-cake2"></i></a>
                            <br />';
                } else {
                    $termin = '';
                }
                }
              }
            }

            //If date is today, highlight it
            if (($t == date("j")) && ($month == date("n")) && ($year == date("Y"))) {
                echo '<td class="calendar_today" valign="top"><b style="color: #dc3545">' . $t . '</b> ' . $termin . '</td>';
            } else {
                //  If the date is absent ie after 31, print space
                if ($t === ' ') {
                    echo '<td class="calendar_date31" valign="top">&nbsp;</td>';
                } else {
                    echo
                        '<td valign="top">' . $t . ' ' . $termin . '</td>';
                }
            }
        }
        if (($row + 1) != ($end + 1)) {
            echo '</tr><tr>';
        } else {
            echo '</tr>';
        }
    }
    echo '
    </table></div></div></div>';