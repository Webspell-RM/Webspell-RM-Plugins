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
    $plugin_language = $pm->plugin_language("server", $plugin_path);
	


    $filepath = $plugin_path."images/";

    $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_servers WHERE ts3_displayed= '1'"));
    
if (@$ds['ts3_displayed'] == 1) {
    $name=$ds['name'];
    $ip=$ds['ip'];
    $port=$ds['port'];
    $qport=$ds['qport'];

    date_default_timezone_set("Europe/London");
    require_once("libraries/TeamSpeak3/TeamSpeak3.php");
    TeamSpeak3::init();
     
    header('Content-Type: text/html; charset=utf8');
     
    $status = "<span style='color:red' >offline</span>";
    $count = 0;
    $max = 0;
     
    try {
        $ts3 = TeamSpeak3::factory("serverquery://". $ip .":". $qport ."/?server_port=".$port);
        $status = $ts3->getProperty("virtualserver_status");
        $count = $ts3->getProperty("virtualserver_clientsonline") - $ts3->getProperty("virtualserver_queryclientsonline");
        $max = $ts3->getProperty("virtualserver_maxclients");
    }
    catch (Exception $e) {
        #echo '<div style="background-color:red; color:white; display:block; font-weight:bold;">QueryError: ' . $e->getCode() . ' ' . $e->getMessage() . '</div>';
    }

    $data_array = array();
    $data_array['$name'] = $name;
    $data_array['$ip'] = $ip;
    $data_array['$port'] = $port;
    $data_array['$status'] = $status;
    $data_array['$count'] = $count;
    $data_array['$max'] = $max;
    $data_array['$lang_connect'] = $plugin_language[ 'connect' ];
    $data_array['$lang_conn_stat'] = $plugin_language[ 'conn_stat' ];

    $filepath = $plugin_path."images/";
    $data_array['$img_str'] = '<img src="' . $filepath . 'ts-icon.png" alt="" title="Teamspeak">';

    $template = $GLOBALS["_template"]->loadTemplate("ts3viewer","content", $data_array, $plugin_path);
    echo $template;
}else{


    $data_array = array();
    $data_array['$name'] = 'Servername';
    $data_array['$ip'] = '127.0.0.1';
    $data_array['$port'] = '8888';
    $data_array['$status'] = "<span style='color:red' >offline</span>";
    $data_array['$count'] = '';
    $data_array['$max'] = '0';
    $data_array['$lang_connect'] = $plugin_language[ 'connect' ];
    $data_array['$lang_conn_stat'] = $plugin_language[ 'conn_stat' ];

    $filepath = $plugin_path."images/";
    $data_array['$img_str'] = '<img src="' . $filepath . 'ts-icon.png" alt="" title="Teamspeak">';

    $template = $GLOBALS["_template"]->loadTemplate("ts3viewer","content", $data_array, $plugin_path);
    echo $template;




}

 ?>
