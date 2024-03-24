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
    $plugin_language = $pm->plugin_language("counter", $plugin_path);

    $_language->readModule('counter');

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['title'];
    $plugin_data['$subtitle']='Counter';
    $template = $GLOBALS["_template"]->loadTemplate("counter","head", $plugin_data, $plugin_path);
    echo $template;


$time = time();
$date = getformatdate($time);
$dateyesterday = getformatdate($time - (24 * 3600));
$datemonth = date(".m.Y", time());

$ergebnis = safe_query("SELECT `hits` FROM `" . PREFIX . "counter`");
$ds = mysqli_fetch_array($ergebnis);
$us = mysqli_num_rows(safe_query("SELECT `userID` FROM `" . PREFIX . "user`"));

$total = $ds[ 'hits' ];
$dt = mysqli_fetch_array(safe_query("SELECT `count` FROM `" . PREFIX . "counter_stats` WHERE `dates` = '$date'"));
if(!empty($dt[ 'count' ])){
    $today = $dt[ 'count' ];
} else {
    $today = 0;
}

$dy = mysqli_fetch_array(
    safe_query(
        "SELECT
            `count`
        FROM
            `" . PREFIX . "counter_stats`
        WHERE
            `dates`='$dateyesterday'"
    )
);

if(!empty($dy[ 'count' ])) {
    $yesterday = $dy[ 'count' ];
} else {
    $yesterday = 0;
}

$month = 0;
$month_max = 0;
$monthquery = safe_query("SELECT `count` FROM `" . PREFIX . "counter_stats` WHERE `dates` LIKE '%$datemonth'");
while ($dm = mysqli_fetch_array($monthquery)) {
    $month = $month + $dm[ 'count' ];
    if ($dm[ 'count' ] > $month_max) {
        $month_max = $dm[ 'count' ];
    }
}
if ($month == 0) {
    $month = 1;
}
$monatsstat = '';

for ($i = date("d", time()); $i > 0; $i--) {
    if (mb_strlen($i) < 2) {
        $i = "0" . $i;
    }

    $tmp = mysqli_fetch_array(
        safe_query(
            "SELECT
                `count`
            FROM
                `" . PREFIX . "counter_stats`
            WHERE
                `dates` = '" . $i . $datemonth."'"
        )
    );

    if(!empty($tmp[ 'count' ])) {
        $visits = $tmp[ 'count' ];
    } else {
        $visits = '0';
    }

    $prozent = $visits * 100 / $month_max;
    $monatsstat .= '
    <li class="list-group-item">
        <div class="row">
            <div class="col-2"> ' . $i . $datemonth . ' 
                <span class="badge bg-secondary"><span class="counter">' . $visits . '</span></span>
            </div>
            <div class="col-9">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar btn-primary" role="progressbar" aria-valuenow="' . (round($prozent)) . '" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="col-1">
            ' . (round($prozent)) . ' %
            </div>
        </div>
    </li>';
}


$tmp = mysqli_fetch_array(safe_query("SELECT `online` FROM `" . PREFIX . "counter`"));
$days_online = round((time() - $tmp[ 'online' ]) / (3600 * 24));

if (!$days_online) {
    $days_online = 1;
}
$ds = mysqli_fetch_array(safe_query("SELECT * FROM `" . PREFIX . "counter`"));
$since = getformatdate($ds[ 'online' ]);

$perday = round($total / $days_online, 2);
$perhour = round($total / $days_online / 24, 2);
$permonth = round($total / $days_online * 24, 2);

$tmp = mysqli_fetch_array(safe_query("SELECT max(count) as `MAXIMUM` FROM `" . PREFIX . "counter_stats`"));
$maxvisits = $tmp[ 'MAXIMUM' ];

$online_lasthour =
    mysqli_num_rows(safe_query("SELECT `ip` FROM `" . PREFIX . "counter_iplist` WHERE `del` > " . (time() - 3600)));
$online = mysqli_num_rows(safe_query("SELECT `time` FROM `" . PREFIX . "whoisonline`"));
$dm = mysqli_fetch_array(safe_query("SELECT `maxonline` FROM `" . PREFIX . "counter`"));
$maxonline = $dm[ 'maxonline' ];

$guests = mysqli_num_rows(safe_query("SELECT `ip` FROM `" . PREFIX . "whoisonline` WHERE `userID` = ''"));
$user = mysqli_num_rows(safe_query("SELECT `userID` FROM `" . PREFIX . "whoisonline` WHERE `ip` = ''"));
$useronline = $guests + $user;

if ($user == 1) {
    $user_on = '1 ' . $plugin_language[ 'user' ];
} else {
    $user_on = $user . ' ' . $plugin_language[ 'users' ];
}

if ($guests == 1) {
    $guests_on = '1 ' . $plugin_language[ 'guest' ];
} else {
    $guests_on = $guests . ' ' . $plugin_language[ 'guests' ];
}

// average age of all users
$get =
    mysqli_fetch_assoc(
        safe_query(
            "SELECT
                ROUND(SUM(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%y')) / COUNT(userID)) AS
                    `avg_age`
            FROM
                " . PREFIX . "user
            WHERE
                birthday > 0"
        )
    );
$avg_age_user = $get[ 'avg_age' ];
// average age of clanmembers
$avg_age_member = 0;
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
if (@$dx[ 'modulname' ] != 'squads') {
    $get = safe_query(
    "SELECT
        SUM(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%y')) / COUNT(userID) AS `avg_age`
    FROM
        " . PREFIX . "user
    WHERE
        birthday > 0
    "
    );

} else {    
    $get = safe_query(
    "SELECT
        SUM(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(u.birthday)), '%y')) / COUNT(u.userID) AS `avg_age`
    FROM
        " . PREFIX . "plugins_squads_members m
    JOIN
        " . PREFIX . "user u ON
        u.userID = m.userID
    WHERE
        u.birthday > 0
    GROUP BY
        m.userID"
    );
}

if (mysqli_num_rows($get)) {
    while ($ds = mysqli_fetch_assoc($get)) {
        $avg_age_member += $ds[ 'avg_age' ];
    }
    $avg_age_member = ROUND($avg_age_member / mysqli_num_rows($get), 0);
}
// get oldest/youngest member
$get_young = mysqli_fetch_assoc(
    safe_query(
        "SELECT
            DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%y') AS `age`,
            `nickname`,
            `userID`
        FROM
            " . PREFIX . "user
        WHERE
            birthday
        ORDER BY
            birthday DESC
        LIMIT 0,1"
    )
);
$get_old = mysqli_fetch_assoc(
    safe_query(
        "SELECT
            DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%y') AS `age`,
            nickname,
            userID
        FROM
            " . PREFIX . "user
        WHERE
            birthday > 0
        ORDER BY
            birthday ASC
        LIMIT 0,1"
    )
);

if(!empty($get_young['age'])){
        $youngest_age = $get_young['age'];
    }else{
        $youngest_age = '';
    }

if(!empty($get_young['userID'])){
        $youngest_id = $get_young['userID'];
    }else{
        $youngest_id = '';
    }

if(!empty($get_young['nickname'])){
        $youngest_nickname = $get_young['nickname'];
    }else{
        $youngest_nickname = '';
    }

if(!empty($get_old['age'])){
        $oldest_age = $get_old['age'];
    }else{
        $oldest_age = '';
    }

if(!empty($get_old['userID'])){
        $oldest_id = $get_old['userID'];
    }else{
        $oldest_id = '';
    }

if(!empty($get_old['nickname'])){
        $oldest_nickname = $get_old['nickname'];
    }else{
        $oldest_nickname = '';
    }


$data_array = array();
$data_array['$today'] = $today;
$data_array['$yesterday'] = $yesterday;
$data_array['$month'] = $month;
$data_array['$total'] = $total;
$data_array['$days_online'] = $days_online;
$data_array['$permonth'] = $permonth;
$data_array['$perday'] = $perday;
$data_array['$perhour'] = $perhour;
$data_array['$maxvisits'] = $maxvisits;
$data_array['$online'] = $online;
$data_array['$user_on'] = $user_on;
$data_array['$guests_on'] = $guests_on;
$data_array['$maxonline'] = $maxonline;
$data_array['$online_lasthour'] = $online_lasthour;
$data_array['$avg_age_user'] = $avg_age_user;
$data_array['$avg_age_member'] = $avg_age_member;
$data_array['$youngest_age'] = $youngest_age;
$data_array['$youngest_id'] = $youngest_id;
$data_array['$youngest_nickname'] = $youngest_nickname;
$data_array['$oldest_age'] = $oldest_age;
$data_array['$oldest_id'] = $oldest_id;
$data_array['$oldest_nickname'] = $oldest_nickname;
$data_array['$us'] = $us;
$data_array['$monatsstat'] = $monatsstat;
$data_array['$since'] = $since;

$data_array['$lang_visits'] = $plugin_language['visits'];
$data_array['$lang_today'] = $plugin_language['today'];
$data_array['$lang_yesterday'] = $plugin_language['yesterday'];
$data_array['$lang_this_month'] = $plugin_language['this_month'];
$data_array['$lang_total'] = $plugin_language['total'];
$data_array['$lang_total_statistic'] = $plugin_language['total_statistic'];
$data_array['$lang_daysonline'] = $plugin_language['daysonline'];
$data_array['$lang_visits_month'] = $plugin_language['visits_month'];
$data_array['$lang_visits_day'] = $plugin_language['visits_day'];
$data_array['$lang_visits_hour'] = $plugin_language['visits_hour'];
$data_array['$lang_max_day'] = $plugin_language['max_day'];

$data_array['$lang_online'] = $plugin_language['online'];
$data_array['$lang_now'] = $plugin_language['now'];
$data_array['$lang_maximum'] = $plugin_language['maximum'];
$data_array['$lang_lasthour'] = $plugin_language['lasthour'];
$data_array['$lang_user_statistic'] = $plugin_language['user_statistic'];
$data_array['$lang_avg_age'] = $plugin_language['avg_age'];
$data_array['$lang_avg_age_clanmember'] = $plugin_language['avg_age_clanmember'];
$data_array['$lang_youngest_user'] = $plugin_language['youngest_user'];
$data_array['$lang_oldest_user'] = $plugin_language['oldest_user'];
$data_array['$lang_registered_users'] = $plugin_language['registered_users'];
$data_array['$lang_this_month'] = $plugin_language['this_month'];
$data_array['$online_since_then'] = $plugin_language['online_since_then'];

$template = $GLOBALS["_template"]->loadTemplate("counter","content_stats", $data_array, $plugin_path);
echo $template;
?>

    <script language="javascript">
        var delay = 300;
$(".progress-bar").each(function(i){
    $(this).delay( delay*i ).animate( { width: $(this).attr('aria-valuenow') + '%' }, delay );

    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: delay,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now)+'%');
        }
    });
});

  </script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.0/jquery.waypoints.min.js"></script>
