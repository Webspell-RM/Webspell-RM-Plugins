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
    $plugin_data['$title']=$plugin_language['sc_title'];
    $plugin_data['$subtitle']='Counter';
    $template = $GLOBALS["_template"]->loadTemplate("counter","sc_head_stats", $plugin_data, $plugin_path);
    echo $template;

$date = getformatdate(time());
$dateyesterday = getformatdate(time() - (24 * 3600));
$datemonth = date(".m.Y", time());

$ergebnis = safe_query("SELECT hits FROM " . PREFIX . "counter");
$ds = mysqli_fetch_array($ergebnis);
$us = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "user"));
$us = $us[ 0 ];

$total = $ds[ 'hits' ];
$dt = mysqli_fetch_array(safe_query("SELECT count FROM " . PREFIX . "counter_stats WHERE dates='$date'"));
if(!empty($dt[ 'count' ])){
    $today = $dt[ 'count' ];
} else {
    $today = 0;
}

$dy = mysqli_fetch_array(safe_query("SELECT count FROM " . PREFIX . "counter_stats WHERE dates='$dateyesterday'"));
if(!empty($dy[ 'count' ])){
    $yesterday = $dy[ 'count' ];
} else {
    $yesterday = 0;
}

$month = 0;
$monthquery = safe_query("SELECT count FROM " . PREFIX . "counter_stats WHERE dates LIKE '%$datemonth'");
while ($dm = mysqli_fetch_array($monthquery)) {
    $month = $month + $dm[ 'count' ];
}

$guests = mysqli_fetch_array(safe_query("SELECT COUNT(*) FROM " . PREFIX . "whoisonline WHERE userID=''"));
$user = mysqli_fetch_array(safe_query("SELECT COUNT(*) FROM " . PREFIX . "whoisonline WHERE ip=''"));
$useronline = $guests[ 0 ] + $user[ 0 ];

if ($user[ 0 ] == 1) {
    $user_on = 1;
    $user_on_text = $plugin_language[ 'user' ];
} else {
    $user_on = $user[ 0 ];
    $user_on_text = $plugin_language[ 'users' ];
}
if ($guests[ 0 ] == 1) {
    $guests_on = 1;
    $guests_on_text = $plugin_language[ 'guest' ];
} else {
    $guests_on = $guests[ 0 ];
    $guests_on_text = $plugin_language[ 'guests' ];
}

$data_array = array();
$data_array['$today'] = $today;
$data_array['$yesterday'] = $yesterday;
$data_array['$month'] = $month;
$data_array['$total'] = $total;
$data_array['$us'] = $us;
$data_array['$user_on'] = $user_on;
$data_array['$user_on_text'] = $user_on_text;
$data_array['$guests_on'] = $guests_on;
$data_array['$guests_on_text'] = $guests_on_text;

$data_array['$lang_visits'] = $plugin_language['visits'];
$data_array['$lang_today'] = $plugin_language['today'];
$data_array['$lang_yesterday'] = $plugin_language['yesterday'];
$data_array['$lang_month'] = $plugin_language['month'];
$data_array['$lang_visits_total'] = $plugin_language['visits_total'];
$data_array['$lang_registered_users'] = $plugin_language['registered_users'];
$data_array['$lang_statistic'] = $plugin_language['statistic'];

$template = $GLOBALS["_template"]->loadTemplate("counter","sc_stats", $data_array, $plugin_path);
echo $template;
?>
