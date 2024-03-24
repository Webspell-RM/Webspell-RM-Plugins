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
    $plugin_language = $pm->plugin_language("whoisonline", $plugin_path);

// WHO IS ONLINE

$_language->readModule('whoisonline');

$plugin_data= array();
$plugin_data['$title']=$plugin_language['whoisonline'];
$plugin_data['$subtitle']='Who is online';
$template = $GLOBALS["_template"]->loadTemplate("whoisonline","title", $plugin_data, $plugin_path);
echo $template;

$result_guests = safe_query("SELECT * FROM " . PREFIX . "whoisonline WHERE userID=''");
$guests = mysqli_num_rows($result_guests);
$result_user = safe_query("SELECT * FROM " . PREFIX . "whoisonline WHERE ip=''");
$user = mysqli_num_rows($result_user);
$useronline = $guests + $user;
if ($user == 1) {
    $user_on = '<strong>1</strong> ' . $plugin_language[ 'registered_user' ];
} else {
    $user_on = '<strong>' . $user . '</strong> ' . $plugin_language[ 'registered_users' ];
}

if ($guests == 1) {
    $guests_on = '<strong>1</strong> ' . $plugin_language[ 'guest' ];
} else {
    $guests_on = '<strong>' . $guests . '</strong> ' . $plugin_language[ 'guests' ];
}

$online = $plugin_language[ 'now_online' ] . ' ' . $user_on . ' ' . $plugin_language[ 'and' ] . ' ' . $guests_on;
$sort = 'time';
if (isset($_GET[ 'sort' ])) {
    if ($_GET[ 'sort' ] == 'nickname') {
        $sort = 'nickname';
    }
}

$type = 'DESC';
if (isset($_GET[ 'type' ])) {
    if ($_GET[ 'type' ] == 'ASC') {
        $type = 'ASC';
    }
}

if ($type == "ASC") {
    $sorter =
        '<a href="index.php?site=whoisonline&amp;sort=' . $sort . '&amp;type=DESC">' . $plugin_language[ 'sort' ] . '
        </a> <i class="bi bi-arrow-down"></i>';
} else {
    $sorter =
        '<a href="index.php?site=whoisonline&amp;sort=' . $sort . '&amp;type=ASC">' . $plugin_language[ 'sort' ] . '
        </a> <i class="bi bi-arrow-up"></i>';
}


$data_array = array();
$data_array['$sorter'] = $sorter;
$data_array['$online'] = $online;
$data_array['$type'] = $type;

$data_array['$title_is']=$plugin_language['title_is'];
$data_array['$nickname']=$plugin_language['nickname'];
$data_array['$contact']=$plugin_language['contact'];
$data_array['$status']=$plugin_language['status'];
$data_array['$communication']=$plugin_language['communication'];
$data_array['$n/a']=$plugin_language['n/a'];
$data_array['$email']=$plugin_language['email'];
$data_array['$homepage']=$plugin_language['homepage'];
    

$template = $GLOBALS["_template"]->loadTemplate("whoisonline","head", $data_array, $plugin_path);
echo $template;


$ergebnis = safe_query(
    "SELECT
        w.*,
        u.nickname
    FROM
        " . PREFIX . "whoisonline w
    LEFT JOIN
        " . PREFIX . "user u
    ON
        u.userID = w.userID
    ORDER BY
        $sort $type"
);

while ($ds = mysqli_fetch_array($ergebnis)) {
    if ($ds[ 'ip' ] == '') {
        #$nickname = '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><strong>' . $ds[ 'nickname' ] . '</strong></a>';
        $nickname = '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '">' . getnickname($ds[ 'userID' ]) . '</a>';
        
        $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
        if (@$dx[ 'modulname' ] != 'squads') {    
            $member = '';                    
        } else {
            if (isclanmember($ds[ 'userID' ])) {
                $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
            } else {
                $member = '';
            }
        }

        if (getemailhide($ds[ 'userID' ])) {
            $email = '<i class="bi bi-envelope-slash"> email</i>';
        } else {
            $email = '<a href="mailto:' . mail_protect(getemail($ds[ 'userID' ])) . '"><i class="bi bi-envelope"></i></a>';
        }

        $dy = mysqli_fetch_array(safe_query("SELECT homepage FROM " . PREFIX . "user WHERE `userID` = " . $ds[ 'userID' ]));
        $homepage=getinput(@$dy['homepage']);
    
        if ($homepage != '') {
            if (stristr($homepage, "https://")) {
                $homepage = ' / <a href="' . htmlspecialchars($homepage) . '" target="_blank" rel="nofollow"><i class="bi bi-house"></i> ' . $plugin_language[ 'homepage' ] .'</a>';//https
            } else {
                $homepage = ' / <a href="http://' . htmlspecialchars($homepage) . '" target="_blank" rel="nofollow"><i class="bi bi-house"></i> ' . $plugin_language[ 'homepage' ] .'</a>';//http
            }
        } else {
            $homepage = ' / <i class="bi bi-house-slash"></i><i> ' . $plugin_language[ 'homepage' ] .'</i>';
        }

        $pm = ' / <i class="bi bi-slash-circle"> '.$plugin_language['communication'].'</i>';
        if ($loggedin && $ds[ 'userID' ] != $userID) {
            $pm = ' / <a href="index.php?site=messenger&amp;action=touser&amp;touser=' . $ds[ 'userID' ] . '"><i class="bi bi-messenger"></i> ' . $plugin_language[ 'communication' ] .'</a>';
            }
        
    } else {
        $nickname = $plugin_language[ 'guest' ];
        $member = "";
        $email = "";
        $homepage = "";
        $pm = "";
        
    }

    $array_watching = array(
        'about_us',
        'awards',
        'blog',
        'calendar',
        'clanwars',
        'counter',
        'demos',
        'discord',
        'files',
        'forum',
        'gallery',
        'links',
        'linkus',
        'login',
        'loginoverview',
        'members',
        'news_archive', 
        'partners',
        'polls',
        'portfolio',
        'userlist',
        'registered_users',
        'register',
        'servers',
        'mc_status',
        'sponsors',
        'squads',
        'twitter',
        'todo',
        'whoisonline',
        'newsletter',
        'search',
        'streams',
        'shoutbox',
        'videos',
        'cashbox',
        '#'
    );
    $array_reading = array('articles', 'contact', 'faq', 'wiki', 'news', 'planning', 'guestbook', 'history', 'imprint', 'privacy_policy', 'joinus', 'clan_rules', 'candidature', 'server_rules', 'ticketcenter');

    if (in_array($ds[ 'site' ], $array_watching)) {
        $status = $plugin_language[ 'is_watching_the' ] . ' <a href="index.php?site=' . $ds[ 'site' ] . '">' . $plugin_language[ $ds[ 'site' ] ] . '</a>';
    
    } elseif (in_array($ds[ 'site' ], $array_reading)) {
        $status = $plugin_language[ 'is_reading_the' ] . ' <a href="index.php?site=' . $ds[ 'site' ] . '">' . $plugin_language[ $ds[ 'site' ] ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "buddies") {
        $status = $plugin_language[ 'is_watching_his' ] . ' <a href="index.php?site=buddies">' . $plugin_language[ 'buddys' ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "clanwars_details") {
        $status = $plugin_language[ 'is_watching_details_clanwar' ];
    
    } elseif ($ds[ 'site' ] == "forum_topic") {
        $status = $plugin_language[ 'is_reading_forum' ];
    
    } elseif ($ds[ 'site' ] == "messenger") {
        $status = $plugin_language[ 'is_watching_his' ] . ' <a href="index.php?site=messenger">' . $plugin_language[ 'messenger' ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "myprofile") {
        $status = $plugin_language[ 'is_editing_his' ] . ' <a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '">' . $plugin_language[ 'profile' ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "news_comments") {
        $status = $plugin_language[ 'is_reading_newscomments' ];
    
    } elseif ($ds[ 'site' ] == "profile") {
        $status = $plugin_language[ 'is_watching_profile' ];

    } elseif ($ds[ 'site' ] == "startpage") {
        $status = $plugin_language[ 'is_watching_the' ] . ' <a href="#">' . $plugin_language[ 'startpage' ] . '</a>';

    } else {
        $status = $plugin_language[ 'is_watching_the' ] . ' <a href="#">' . $plugin_language[ 'startpage' ] . '</a>';
    }

    if ($getavatar = getavatar($ds['userID'])) {
        $avatar = '<img class="img-fluid avatar_small" src="./images/avatars/' . $getavatar . '">';
    } else {
        $avatar = '';
    } 


    $data_array = array();
    $data_array['$nickname'] = $nickname;
    $data_array['$member'] = $member;
    $data_array['$email'] = $email;
    $data_array['$pm'] = $pm;
    $data_array['$status'] = $status;
    $data_array['$avatar'] = $avatar;
    $data_array['$homepage'] = $homepage;

    $template = $GLOBALS["_template"]->loadTemplate("whoisonline","content", $data_array, $plugin_path);
    echo $template;
    
}

    $template = $GLOBALS["_template"]->loadTemplate("whoisonline","foot", $data_array, $plugin_path);
    echo $template;


// WHO WAS ONLINE

if ($type == "ASC") {
    $sorter =
        '<a href="index.php?site=whoisonline&amp;sort=' . $sort . '&amp;type=DESC">' . $plugin_language[ 'sort' ] .
        '</a> <i class="bi bi-arrow-down"></i>';
} else {
    $sorter =
        '<a href="index.php?site=whoisonline&amp;sort=' . $sort . '&amp;type=ASC">' . $plugin_language[ 'sort' ] .
        '</a> <i class="bi bi-arrow-up"></i>';
}    

$ergebnis = safe_query(
    "SELECT
        w.*,
        u.nickname
    FROM
        " . PREFIX . "whowasonline w
    LEFT JOIN
        " . PREFIX . "user u
    ON
        u.userID = w.userID
    ORDER BY
        $sort $type"
);

    $data_array = array();
    $data_array['$sorter'] = $sorter;
    $data_array['$type'] = $type;
    $data_array['$title_was']=$plugin_language['title_was'];
    $data_array['$nickname']=$plugin_language['nickname'];
    $data_array['$contact']=$plugin_language['contact'];
    $data_array['$latest_action']=$plugin_language['latest_action'];
    $data_array['$date']=$plugin_language['date'];
    $data_array['$communication']=$plugin_language['communication'];
    $data_array['$n/a']=$plugin_language['n/a'];
    $data_array['$email']=$plugin_language['email'];
    $data_array['$homepage']=$plugin_language['homepage'];

    $template = $GLOBALS["_template"]->loadTemplate("whoisonline","whowasonline_head", $data_array, $plugin_path);
    echo $template;

while ($ds = mysqli_fetch_array($ergebnis)) {
    
    $date = getformatdatetime($ds[ 'time' ]);
    $nickname = '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '">' . getnickname($ds[ 'userID' ]) . '</a>'; 
    
    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
    if (@$dx[ 'modulname' ] != 'squads') {    
        $member = '';                    
    } else {
        if (isclanmember($ds[ 'userID' ])) {
            $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
        } else {
            $member = '';
        }
    }

    if (getemailhide($ds[ 'userID' ])) {
        $email = '<i class="bi bi-envelope-slash"> email</i>';
    } else {
        $email = '<a href="mailto:' . mail_protect(getemail($ds[ 'userID' ])) . '"><i class="bi bi-envelope"></i> email</a>';
    }

    $dy = mysqli_fetch_array(safe_query("SELECT homepage FROM " . PREFIX . "user WHERE `userID` = " . $ds[ 'userID' ]));
    $homepage=getinput(@$dy['homepage']);
    
    if ($homepage != '') {
        if (stristr($homepage, "https://")) {
            $homepage = ' / <a href="' . htmlspecialchars($homepage) . '" target="_blank" rel="nofollow"><i class="bi bi-house"></i> ' . $plugin_language[ 'homepage' ] .'</a>';//https
        } else {
            $homepage = ' / <a href="http://' . htmlspecialchars($homepage) . '" target="_blank" rel="nofollow"><i class="bi bi-house"></i> ' . $plugin_language[ 'homepage' ] .'</a>';//http
        }
    } else {
        $homepage = ' / <i class="bi bi-house-slash"></i><i> ' . $plugin_language[ 'homepage' ] .'</i>';
    }

    $pm = ' / <i class="bi bi-slash-circle"> '.$plugin_language['communication'].'</i>';
    if ($loggedin && $ds[ 'userID' ] != $userID) {
            $pm = ' / <a href="index.php?site=messenger&amp;action=touser&amp;touser=' . $ds[ 'userID' ] .
                '"><i class="bi bi-messenger"></i> ' . $plugin_language[ 'communication' ] .'</a>';
    }

    $array_watching = array(
        'about_us',
        'awards',
        'blog',
        'calendar',
        'clanwars',
        'counter',
        'demos',
        'discord',
        'files',
        'forum',
        'gallery',
        'links',
        'linkus',
        'login',
        'loginoverview',
        'members',
        'news_archive', 
        'partners',
        'polls',
        'portfolio',
        'userlist',
        'registered_users',
        'register',
        'servers',
        'mc_status',
        'sponsors',
        'squads',
        'twitter',
        'todo',
        'whoisonline',
        'newsletter',
        'search',
        'streams',
        'shoutbox',
        'videos',
        'cashbox',
        '#'
    );
    $array_reading = array('articles', 'contact', 'faq', 'wiki', 'news', 'planning','squads', 'guestbook', 'history', 'imprint', 'privacy_policy', 'joinus', 'clan_rules', 'candidature', 'server_rules', 'ticketcenter');

    if (in_array($ds[ 'site' ], $array_watching)) {
        $status = $plugin_language[ 'was_watching_the' ] . ' <a href="index.php?site=' . $ds[ 'site' ] . '">' . $plugin_language[ $ds[ 'site' ] ] . '</a>';
    } elseif (in_array($ds[ 'site' ], $array_reading)) {
        $status = $plugin_language[ 'was_reading_the' ] . ' <a href="index.php?site=' . $ds[ 'site' ] . '">' . $plugin_language[ $ds[ 'site' ] ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "buddies") {
        $status = $plugin_language[ 'was_watching_his' ] . ' <a href="index.php?site=buddies">' . $plugin_language[ 'buddys' ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "clanwars_details") {
        $status = $plugin_language[ 'was_watching_details_clanwar' ];
    
    } elseif ($ds[ 'site' ] == "forum_topic") {
        $status = $plugin_language[ 'was_reading_forum' ];
    
    } elseif ($ds[ 'site' ] == "messenger") {
        $status = $plugin_language[ 'was_watching_his' ] . ' <a href="index.php?site=messenger">' . $plugin_language[ 'messenger' ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "myprofile") {
        $status = $plugin_language[ 'was_editing_his' ] . ' <a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '">' . $plugin_language[ 'profile' ] . '</a>';
    
    } elseif ($ds[ 'site' ] == "news_comments") {
        $status = $plugin_language[ 'was_reading_newscomments' ];
    
    } elseif ($ds[ 'site' ] == "profile") {
        $status = $plugin_language[ 'was_watching_profile' ];
    
    } elseif ($ds[ 'site' ] == "startpage") {
        $status = $plugin_language[ 'was_watching_the' ] . ' <a href="#">' . $plugin_language[ 'startpage' ] . '</a>';

    } else {
        $status = $plugin_language[ 'was_watching_the' ] . ' <a href="#">' . $plugin_language[ 'startpage' ] . '</a>';
    }

    if ($getavatar = getavatar($ds['userID'])) {
        $avatar = '<img class="img-fluid avatar_small" src="./images/avatars/' . $getavatar . '">';
    } else {
        $avatar = '';
    } 

    $data_array = array();
    $data_array['$nickname'] = $nickname;
    $data_array['$member'] = $member;
    $data_array['$email'] = $email;
    $data_array['$pm'] = $pm;
    $data_array['$status'] = $status;
    $data_array['$date'] = $date;
    $data_array['$avatar'] = $avatar;    
    $data_array['$homepage'] = $homepage;

    $template = $GLOBALS["_template"]->loadTemplate("whoisonline","whowasonline_content", $data_array, $plugin_path);
    echo $template;
    
}

    $template = $GLOBALS["_template"]->loadTemplate("whoisonline","whowasonline_foot", $data_array, $plugin_path);
    echo $template;
