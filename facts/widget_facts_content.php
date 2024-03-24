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
    $plugin_language = $pm->plugin_language("facts", $plugin_path);

    // -- FILES INFORMATION -- //
include_once("facts_functions.php");

$_language->readModule('facts');


$dt = mysqli_fetch_array(safe_query("SELECT sum(topics), sum(posts) FROM " . PREFIX . "plugins_forum_boards"));
    $topics = $dt[ 0 ];
    $posts = $dt[ 1 ];

$fileQry = safe_query("SELECT * FROM `" . PREFIX . "plugins_files`");
        $totalfiles = mysqli_num_rows($fileQry);
        if ($totalfiles) {
            $hddspace = 0;
            $traffic = 0;
            // total traffic caused by downloads
            while ($file = mysqli_fetch_array($fileQry)) {
                $filesize = $file[ 'filesize' ];
                $fileload = $file[ 'downloads' ];
                $hddspace += $filesize;
                $traffic += $filesize * $fileload;
            }
            $traffic = detectfactsfilesize($traffic);
            $hddspace = detectfactsfilesize($hddspace);
        }

$ergebnis = safe_query("SELECT hits FROM " . PREFIX . "counter");
$ds = mysqli_fetch_array($ergebnis);
$us = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "user"));
$us = $us[ 0 ];

$user = mysqli_fetch_array(safe_query("SELECT COUNT(*) FROM " . PREFIX . "whoisonline WHERE ip=''"));
$useronline = $user[ 0 ];

if ($user[ 0 ] == 1) {
    $user_on = 1;
    $user_on_text = $plugin_language[ 'user' ];
} else {
    $user_on = $user[ 0 ];
    $user_on_text = $plugin_language[ 'users' ];
}


        $data_array = array();
        $data_array['$us'] = $us;
        $data_array['$user_on'] = $user_on;
        $data_array['$user_on_text'] = $user_on_text;
        @$data_array['$totalfiles'] = $totalfiles;
        $data_array['$posts'] = $posts;
        $data_array['$posts_in']=$plugin_language['posts_in'];
        $data_array['$files']=$plugin_language['files'];
        $data_array['$registered_users']=$plugin_language['registered_users'];

    $template = $GLOBALS["_template"]->loadTemplate("facts","content", $data_array, $plugin_path);
    echo $template;
?>
