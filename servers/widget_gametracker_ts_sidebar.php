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


    $data_array = array();
    $data_array['$title']=$plugin_language['ts-title'];
    $data_array['$subtitle']='Gametracker TS';

    $template = $GLOBALS["_template"]->loadTemplate("servers","head", $data_array, $plugin_path);
    echo $template;

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers WHERE provider = '1' AND displayed = '1'");

  if (mysqli_num_rows($ergebnis)) {
    $template = $GLOBALS["_template"]->loadTemplate("servers","gametracker_head_content", $data_array, $plugin_path);
    echo $template;
    
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {

    $id=$ds['ip'];
    $port=$ds['port'];

    if(isset($_COOKIE['im_server'])) {

        $pic='<p align="center"><div class="">
            <div data-service="server"
            data-id="'.$id.':'.$port.'"
            style="height: 541px;"
            data-widget></div></div></p>';
        } else {
            $pic = '<div data-service="server" data-id="'.$id.':'.$port.'" data-width="380" data-title="Gametracker" style="height: 240px; width: 328px;" data-autoscale data-widget></div>';
        }

        $data_array = array();
        $data_array['$pic'] = $pic;
    
        $template = $GLOBALS["_template"]->loadTemplate("servers","gametracker_content", $data_array, $plugin_path);
        echo $template;
        $n++;
    }
   
    $template = $GLOBALS["_template"]->loadTemplate("servers","gametracker_foot_content", $data_array, $plugin_path);
    echo $template;  
    
} else {
    
    echo $plugin_language['no_server'];
}