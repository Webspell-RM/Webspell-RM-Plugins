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
    $plugin_language = $pm->plugin_language("ts3viewer", $plugin_path);
	


    $_language->readModule('ts3viewer');

    $filepath = $plugin_path."images/";

$ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ts3viewer"));
$ts3_name=$ds['ts3_name'];
$ts3_ip=$ds['ts3_ip'];
$ts3_port=$ds['ts3_port'];
$ts3_qport=$ds['ts3_qport'];

date_default_timezone_set("Europe/London");
require_once("libraries/TeamSpeak3/TeamSpeak3.php");
TeamSpeak3::init();
 
header('Content-Type: text/html; charset=utf8');
 
$status = "<span style='color:red' >offline</span>";
$count = 0;
$max = 0;
 
try {
    $ts3 = TeamSpeak3::factory("serverquery://". $ts3_ip .":". $ts3_qport ."/?server_port=".$ts3_port);
    $status = $ts3->getProperty("virtualserver_status");
    $count = $ts3->getProperty("virtualserver_clientsonline") - $ts3->getProperty("virtualserver_queryclientsonline");
    $max = $ts3->getProperty("virtualserver_maxclients");
}
catch (Exception $e) {
    #echo '<div style="background-color:red; color:white; display:block; font-weight:bold;">QueryError: ' . $e->getCode() . ' ' . $e->getMessage() . '</div>';
}




      $data_array = array();
      $data_array['$ts3_name'] = $ts3_name;
      $data_array['$ts3_ip'] = $ts3_ip;
      $data_array['$ts3_port'] = $ts3_port;
      $data_array['$status'] = $status;
      $data_array['$count'] = $count;
      $data_array['$max'] = $max;
      $data_array['$connect'] = $plugin_language[ 'connect' ];
      $data_array['$conn_stat'] = $plugin_language[ 'conn_stat' ];

        $filepath = $plugin_path."images/";
        $data_array['$img_str'] = '<img src="' . $filepath . 'ts-icon.png" alt="" title="Teamspeak">';

        $template = $GLOBALS["_template"]->loadTemplate("ts3viewer","content", $data_array, $plugin_path);
        echo $template;


 ?>
