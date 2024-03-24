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
    $plugin_language = $pm->plugin_language("userlist", $plugin_path);

$data_array = array();
$data_array['$title']=$plugin_language[ 'registered_users' ];
$data_array['$subtitle']='Userlist';
$template = $GLOBALS["_template"]->loadTemplate("userlist","head", $data_array, $plugin_path);
echo $template;

function clear($text)
{
    return str_replace(
        "javascript:",
        "",
        strip_tags($text)
    );
}

$alle = safe_query("SELECT userID FROM " . PREFIX . "user");
$gesamt = mysqli_num_rows($alle);
  $pages=1;

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_userlist");
        $ds = mysqli_fetch_array($settings);

    
        $maxusers = $ds[ 'users_list' ];
        if (empty($maxusers)) {
        $maxusers = 10;
        }

        for ($n=$maxusers; $n<=$gesamt; $n+=$maxusers) {
    if($gesamt>$n) $pages++;
  }

if (isset($_GET[ 'page' ])) {
    $page = (int)$_GET[ 'page' ];
} else {
    $page = 1;
}
$sort = "nickname";
if (isset($_GET[ 'sort' ])) {
    if ($_GET[ 'sort' ] === 'nickname' ||
        $_GET[ 'sort' ] === 'lastlogin' ||
        $_GET[ 'sort' ] === 'registerdate' ||
        $_GET[ 'sort' ] === 'homepage'
    ) {
        $sort = $_GET[ 'sort' ];
    }
}

$type = "ASC";
if (isset($_GET[ 'type' ])) {
    if (($_GET[ 'type' ] == 'ASC') || ($_GET[ 'type' ] == 'DESC')) {
        $type = $_GET[ 'type' ];
    }
}

if ($pages > 1) {
    $page_link = makepagelink("index.php?site=userlist&amp;sort=$sort&amp;type=$type", $page, $pages);
} else {
    $page_link = '';
}

if ($page == "1") {
    $ergebnis =
        safe_query(
            "SELECT
                *
            FROM
                " . PREFIX . "user
            ORDER BY
                " . $sort . " " . $type . "
            LIMIT 0," . (int)$maxusers
        );
    if ($type == "DESC") {
        $n = $gesamt;
    } else {
        $n = 1;
    }
} else {

$start = $page * $maxusers - $maxusers;
    $ergebnis =
        safe_query(
            "SELECT
                *
            FROM
                " . PREFIX . "user
            ORDER BY
                " . $sort . " " . $type . "
            LIMIT " . $start . "," . (int)$maxusers
        );
    if ($type == "DESC") {
        $n = ($gesamt) - $page * $maxusers + $maxusers;
    } else {
        $n = ($gesamt + 1) - $page * $maxusers + $maxusers;
    }
}

$anz = mysqli_num_rows($ergebnis);
if ($anz) {

    if($type=="ASC")
    $sorter = '<a href="index.php?site=userlist&amp;page='.$page.'&amp;sort='.$sort.'&amp;type=DESC">'.$plugin_language['sort'].'</a> <i class="bi bi-arrow-down"></i>&nbsp;&nbsp;&nbsp;';
    else
    $sorter = '<a href="index.php?site=userlist&amp;page='.$page.'&amp;sort='.$sort.'&amp;type=ASC">'.$plugin_language['sort'].'</a> <i class="bi bi-arrow-up"></i>&nbsp;&nbsp;&nbsp;';
    
    
    $data_array = array();
    $data_array['$page_link'] = $page_link;
    $data_array['$gesamt'] = $gesamt;
    $data_array['$page'] = $page;
    $data_array['$sorter'] = $sorter;

    $data_array['$registered_users']=$plugin_language[ 'registered_users' ];
    $data_array['$nickname']=$plugin_language[ 'nickname' ];
    $data_array['$contact']=$plugin_language[ 'contact' ];
    $data_array['$homepage']=$plugin_language[ 'homepage' ];
    $data_array['$last_login']=$plugin_language[ 'last_login' ];
    $data_array['$registration']=$plugin_language[ 'registration' ];

    $template = $GLOBALS["_template"]->loadTemplate("userlist","header", $data_array, $plugin_path);
    echo $template;
    
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        
        $id = $ds[ 'userID' ];
        
        $nickname =
            '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><b>' . strip_tags($ds[ 'nickname' ]) .
            '</b></a>';
        
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

        if ($ds[ 'email_hide' ]) {
            $email = '<span class=""><i class="bi bi-envelope-slash"> email</i></span>';
        } else {
            $email = '<a href="mailto:' . mail_protect($ds[ 'email' ]) .
                '"><i class="bi bi-envelope" title="email"></i> email</a>';
        }

        if ($ds['homepage'] != '') {
            if (stristr($ds[ 'homepage' ], "https://")) {
                $homepage = '<a href="' . htmlspecialchars($ds[ 'homepage' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size:18px;"></i> ' . $plugin_language[ 'homepage' ] .'</a>';//https
            } else {
                $homepage = '<a href="http://' . htmlspecialchars($ds[ 'homepage' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size:18px;"></i> ' . $plugin_language[ 'homepage' ] .'</a>';//http
            }
        } else {
            $homepage = '<i class="bi bi-house-slash" style="font-size:18px;"></i><i> ' . $plugin_language[ 'homepage' ] .'</i>';
        }

        $pm = ' / <i class="bi bi-slash-circle"> '.$plugin_language['message'].'</i>';
        
        if ($loggedin && $ds[ 'userID' ] != $userID) {
            $pm = ' / <a href="index.php?site=messenger&amp;action=touser&amp;touser=' . $ds[ 'userID' ] .
                '"><i class="bi bi-messenger"></i> '.$plugin_language['message'].'</a>';            
        }

        $lastlogindate = getformatdate($ds[ 'lastlogin' ]);
        $lastlogintime = getformattime($ds[ 'lastlogin' ]);
        $registereddate = getformatdate($ds[ 'registerdate' ]);
        $status = isonline($ds[ 'userID' ]);

        if ($status == "offline") {
            $login = $lastlogindate . ' - ' . $lastlogintime;
        } else {
            $login = '<span class="badge bg-success">online</span> ' .
            $plugin_language[ 'now_on' ];
        }

        if ($getavatar = getavatar($ds['userID'])) {
            #$avatar = './images/avatars/' . $getavatar . '';
            $avatar = '<img class="img-fluid avatar_small" src="./images/avatars/' . $getavatar . '">';
        } else {
            $avatar = '';
        } 

        $current = strtotime(date("Y-m-d"));
        $date = $ds[ 'registerdate' ];

        $datediff = $date - $current;
        $difference = floor($datediff/(60*60*24));
        if($difference==0) {
            $register=$plugin_language['today'];
        }else if($difference > 1){
            $register=$plugin_language['future_date'];
        }else if($difference > 0){
            $register=$plugin_language['tomorrow'];
        }else if($difference < -1){
            $register=$plugin_language['long_back'];
            $register=$registereddate;
        }else{
            $register=$plugin_language['yesterday'];
        }  

        $data_array = array();
        $data_array['$nickname'] = $nickname;
        $data_array['$member'] = $member;
        $data_array['$email'] = $email;
        $data_array['$pm'] = $pm;
        $data_array['$homepage'] = $homepage;
        $data_array['$login'] = $login;
        $data_array['$registereddate'] = $register;
        $data_array['$avatar'] = $avatar;

        $template = $GLOBALS["_template"]->loadTemplate("userlist","content", $data_array, $plugin_path);
        echo $template;
        $n++;
    }
    $template = $GLOBALS["_template"]->loadTemplate("userlist","foot", $data_array, $plugin_path);
    echo $template;
if($pages>1) echo $page_link;
    
} else {
    echo $plugin_language[ 'no_users' ];
}
