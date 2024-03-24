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
$plugin_language = $pm->plugin_language("lastlogin", $plugin_path);
$title = $plugin_language[ 'title' ]; #sc_datei Info  

if (!ispageadmin($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) !== "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}

$farbe ='';
$two_days = 2; // CLASS SUCCESS
$four_days = 4; // CLASS WARNING

$seven_days = 7; // CLASS WARNING
$fourteen_days = 14; // CLASS WARNING
$thirty_days = 30; // CLASS WARNING
$three_months_days = 90;
$half_a_year_days = 183; // CLASS WARNING
$a_year_days = 365; // CLASS WARNING

// Datenabfrage und Ausgabe Team Mitglieder
$abfrage = safe_query(" SELECT DISTINCT u.lastlogin, u.nickname, u.userID, s.activity, s.squadID  
FROM " . PREFIX . "user u LEFT JOIN " . PREFIX . "plugins_squads_members s ON s.userID=u.userID WHERE s.squadID > 0 ORDER BY u.lastlogin DESC");

// Datenabfrage und Ausgabe aller Registrierten Benutzer
$abfrageMember = safe_query(" SELECT DISTINCT u.lastlogin, u.nickname, u.userID, s.activity 
FROM " . PREFIX . "user u LEFT JOIN " . PREFIX . "plugins_squads_members s ON s.userID=u.userID WHERE u.userID > 0 ORDER BY u.lastlogin DESC");

$number = 1;

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

  echo'<div class="card">
    <div class="card-header"> <i class="bi bi-person-fills"></i> '.$plugin_language[ 'lastlogin_activity_control' ].'
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_lastlogin">' . $plugin_language[ 'title' ].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>
                        <div class="card-body">

            <div class="col-md-6">    
                
            </div>
            <div class="col-md-6">
                <form method="post" name="post" action="admincenter.php?site=admin_lastlogin" enctype="multipart/form-data">
                    <div class="input-group">
                        <select class="form-select" name="user">
                            <option value="0">'.$plugin_language['lastlogin_select_members'].'</option>
                            <option value="1">'.$plugin_language['lastlogin_all_squad_users'].'</option>
                            <option value="2">'.$plugin_language['lastlogin_all_users'].'</option>
                        </select>
                        <span class="input-group-btn">
                            <input name="post" type="submit" value="'.$plugin_language[ 'lastlogin_submit' ].'" class="btn btn-danger">
                        </span>
                    </div>
                </form>
            </div>
        </div>';

if (isset($_POST['user'])) {
    if ($_POST['user'] === "1") {
        $user = $abfrage;
    } else {
        $user = $abfrageMember;
    } 
} else { 
    $user = $abfrageMember;
}

echo '<div class="table-responsive"><div class="card-body">
        <table id="plugini" class="table table-striped table-bordered">
    
<thead>


      <tr>
            <th style="width: 9%;">'.$plugin_language[ 'lastlogin_number' ].':</th>
            <th>'.$plugin_language[ 'lastlogin_id' ].':</th>
            <th>'.$plugin_language[ 'lastlogin_member' ].':</th>';
            
            if ($user === $abfrage){
                echo '<th>'.$plugin_language[ 'lastlogin_squad' ].':</th>';
            }            

      echo '<th>'.$plugin_language[ 'lastlogin_lastlogin' ].':</th>
            <th>'.$plugin_language[ 'lastlogin_in_days' ].':</th>
            <th>'.$plugin_language[ 'lastlogin_activity' ].':</th>
            </tr></thead>
          <tbody>';

foreach($user as $item) {
           
    $name = getinput($item[ 'nickname' ]);
    $login = date("d.m.Y", getinput($item[ 'lastlogin' ]));
    $zeit = date("d.m.Y", time());
    $yesterday = date("d.m.Y", time()-3600*24);
    $userID = getinput($item['userID']);
    $nick = '<a href="admincenter.php?site=users&action=profile&page=1&type=ASC&sort=nickname&search=&id='.$userID.'" target="_blank">'.$name.'</a>';
    $activity = getinput($item['activity']);
    if (isset($item['squadID'])) {
        $squadname = getsquadname($item['squadID']);
    }

    if($login == $zeit) { 
        $tage = $plugin_language[ 'lastlogin_today' ]; 
    } elseif($login == $yesterday) { 
        $tage = $plugin_language[ 'lastlogin_yesterday' ]; 
    } else { 
        $zwischen = time() - getinput($item[ 'lastlogin' ]);
        $tage = $zwischen/(3600*24);
        $tage = round($tage);
        $tage = ''.$plugin_language[ 'lastlogin_before' ].' <b>'.$tage.'</b> '.$plugin_language[ 'lastlogin_days' ].'';
        $farbe = $zwischen/(3600*24);
        $farbe = round($farbe);
    }

    if($activity == 1) { 
        $aktiv = $plugin_language[ 'lastlogin_activ' ]; 
        $bgaktiv = 'class="table-success"';
    } elseif($activity == 0) {  
        $aktiv = $plugin_language[ 'lastlogin_inactiv' ];
        $bgaktiv = 'class="table-danger"';
    } else {  
        $bgaktiv = '';
    }

    if($farbe <= $two_days) {
        $bgday = 'class="table-success"';
    } elseif($farbe <= $four_days) { 
        $bgday = 'class="table-warning"';
    } elseif($farbe <= $seven_days) { 
        $bgday = 'class="table-danger"';
    } elseif($farbe <= $fourteen_days) { 
        $bgday = 'class="table-info"';
    } elseif($farbe <= $thirty_days) { 
        $bgday = 'class="table-primary"';
    } elseif($farbe <= $three_months_days) { 
        $bgday = 'class="table-light"';
    } elseif($farbe <= $half_a_year_days) { 
        $bgday = 'class="table-secondary"';
    } else { 
        $bgday = 'class="table-dark"';
    }

    echo '
        <tr>
            <td align="center">'.$number++.'</td>
            <td>'.$userID.'</td>
            <td>'.$nick.'</td>';
                
            if ($user === $abfrage){
                echo '<td>'.$squadname.'</td>';
            }

    echo'   <td><b>'.$plugin_language[ 'lastlogin_day' ].':</b> '.$zuletztonline = date("d.m.Y", getinput($item[ 'lastlogin' ])).' | <b>'.$plugin_language[ 'lastlogin_clock' ].':</b> '.$zuletztonline = date("H:i", getinput($item[ 'lastlogin' ])).'</td>
            <td '.$bgday.'>'.$tage.'</td>
            <td '.$bgaktiv.' align="center">
        ';
                if($activity == 1) { 
                    echo ''.$aktiv.'';
                } elseif($activity == 0) { 
                    echo ''.$aktiv.'';
                }

    echo '</td></tr>';
}
echo '</table></div>
        </div></div>';