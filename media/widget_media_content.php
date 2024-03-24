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
    $plugin_language = $pm->plugin_language("media", $plugin_path);

$_language->readModule('media');

        $data_array = array();
        

        $template = $GLOBALS["_template"]->loadTemplate("media","head", $data_array, $plugin_path);
        echo $template;

    $ergebnis = safe_query("SELECT * FROM `".PREFIX."plugins_videos` WHERE media_widget_displayed = '1' ORDER BY sort");
    if(mysqli_num_rows($ergebnis)){
        while ($ds = mysqli_fetch_array($ergebnis)) {
        $videoID = $ds['youtube'];    

        $youtube = '<div data-service="youtube" data-id="'.$videoID.'" data-autoscale data-youtube></div>';    

        $data_array = array();
        $data_array['$youtube'] = $youtube;

        $template = $GLOBALS["_template"]->loadTemplate("media","youtube", $data_array, $plugin_path);
        echo $template;
        }
    }

        $template = $GLOBALS["_template"]->loadTemplate("media","content", array(), $plugin_path);
        echo $template;

    $ergebnis = safe_query("SELECT * FROM `".PREFIX."plugins_streams` WHERE media_widget_displayed = '1' AND provider = '1' ORDER BY `sort`");
    if(mysqli_num_rows($ergebnis)){
        echo'<div class="row">';
        while ($dx = mysqli_fetch_array($ergebnis)) {
        
        #$link = $dx['link'];

        $channel = $dx['link'];

            echo'<div class="col-md-6">
            <div data-service="twitch" data-id="channel='.$channel.'" data-title="Twitch channel stream" data-widget></div>
            </div>  ';

        $data_array = array();
        #$data_array['$link'] = $link;
            
        $template = $GLOBALS["_template"]->loadTemplate("media","streams", $data_array, $plugin_path);
        echo $template;
        }
        echo'</div>';
    }

    $ergebnis = safe_query("SELECT * FROM `".PREFIX."plugins_streams` WHERE media_widget_displayed = '1' AND provider = '0' ORDER BY `sort`");
    if(mysqli_num_rows($ergebnis)){
        echo'<div class="row">';
        while ($dx = mysqli_fetch_array($ergebnis)) {
        
        #$link = $dx['link'];

        $video = $dx['link'];

            echo'<div class="col-md-6">
            <div data-service="twitch" data-id="video='.$video.'" data-title="Twitch channel stream" data-widget></div>
            </div>  ';

        $data_array = array();
        #$data_array['$link'] = $link;
            
        $template = $GLOBALS["_template"]->loadTemplate("media","videos", $data_array, $plugin_path);
        echo $template;
        }
        echo'</div>';
    }


        $data_array = array();
       
        $template = $GLOBALS["_template"]->loadTemplate("media","foot", $data_array, $plugin_path);
        echo $template;

