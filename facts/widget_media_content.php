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

        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("media","head", $data_array, $plugin_path);
        echo $template;

        $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='gallery'"));
        if (@$dx[ 'modulname' ] == 'gallery') { 
            $ergebnis = safe_query("SELECT * FROM `".PREFIX."plugins_gallery_pictures` WHERE displayed_gal = '1' ORDER BY RAND() LIMIT 1");
            if(mysqli_num_rows($ergebnis)){
                while ($ds = mysqli_fetch_array($ergebnis)) {
                $videoID = $ds['youtube'];    

                $youtube = '<div data-service="youtube" data-id="'.$videoID.'" data-autoscale data-youtube style="height: 300px"></div>';    

                $data_array = array();
                $data_array['$youtube'] = $youtube;

                $template = $GLOBALS["_template"]->loadTemplate("media","youtube", $data_array, $plugin_path);
                echo $template;
                }
            }
        }else{}
            
        $template = $GLOBALS["_template"]->loadTemplate("media","content", array(), $plugin_path);
        echo $template;

        $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='streams'"));
        if (@$dx[ 'modulname' ] == 'streams') { 

            $ergebnis = safe_query("SELECT * FROM `".PREFIX."plugins_streams` WHERE media_widget_displayed = '1' AND provider = '1' ORDER BY `sort`");
            if(mysqli_num_rows($ergebnis)){
                echo'<div class="row">';
                while ($dx = mysqli_fetch_array($ergebnis)) {
                
                #$link = $dx['link'];

                $name = $dx['link'];

                    echo'<div class="col-6">
                    <div data-service="twitch" data-id="channel='.$name.'" data-title="Twitch channel stream" data-widget
                    data-placeholder
                    style="height: 167px"></div>
                    </div>  ';

                $data_array = array();
                #$data_array['$link'] = $link;
                    
                $template = $GLOBALS["_template"]->loadTemplate("media","streams", $data_array, $plugin_path);
                echo $template;
                }
                echo'</div>';
            }

            /*$ergebnis = safe_query("SELECT * FROM `".PREFIX."plugins_streams` WHERE media_widget_displayed = '1' AND provider = '0' ORDER BY `sort`");
            if(mysqli_num_rows($ergebnis)){
                echo'<div class="row">';
                while ($dx = mysqli_fetch_array($ergebnis)) {
                
                #$link = $dx['link'];

                $name = $dx['link'];

                    echo'<div class="col">
                    <div data-service="twitch" data-id="channel='.$name.'" data-title="Twitch channel stream" data-widget
                    data-placeholder
                    style="height: 163px"></div>
                    </div>  ';

                $data_array = array();
                #$data_array['$link'] = $link;
                    
                $template = $GLOBALS["_template"]->loadTemplate("media","videos", $data_array, $plugin_path);
                echo $template;
                }
                echo'</div>';
            }*/
        }else{}    

        $data_array = array();
       
        $template = $GLOBALS["_template"]->loadTemplate("media","foot", $data_array, $plugin_path);
        echo $template;

