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

$pm = new plugin_manager();
$plugin_language = $pm->plugin_language("streams", $plugin_path);


    $data_array = array(); 
    $data_array['$title'] = $plugin_language[ 'streams' ];
    $data_array['$subtitle']='Streams';

    $template = $GLOBALS["_template"]->loadTemplate("streams", "widget_head", $data_array, $plugin_path);
    echo $template;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_streams WHERE displayed = '1' AND provider = '1' ORDER BY RAND() LIMIT 1");
    if (mysqli_num_rows($ergebnis)) {
        #echo'<div class="row">';
        while ($ds = mysqli_fetch_array($ergebnis)) {





            $name = $ds['link'];

            echo'<div class="col" style="padding: 0px;">
            <div data-service="twitch" data-id="channel='.$name.'" data-title="Twitch channel stream" data-widget data-placeholder></div>
            </div>  ';
        }
        #echo'</div>';

    } else {
        echo $plugin_language['no_stream'];
        echo'<div class="col-md-12"><br></div>';
    }

        $data_array = array(); 

        $template = $GLOBALS["_template"]->loadTemplate("streams", "widget_content", $data_array, $plugin_path);
        echo $template;
    #}
#} else {

    #echo '<div class="card"><div class="row" style="margin-top: 25px;margin-bottom: 0px;margin-left: 0px;margin-right: 0px"><div class="col-md text-center">Images not found<br><br></div></div></div>';
#}
