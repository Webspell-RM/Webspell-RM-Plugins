<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("about_sponsor", $plugin_path);
    GLOBAL $logo,$theme_name,$themes;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);

        $title = $ds[ 'title' ];
            
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
                
        $data_array = array();
        $data_array['$title'] = $title;

        $text = $ds[ 'text' ];
    
        $translate->detectLanguages($text);
        $text = $translate->getTextByLanguage($text);
    
        $maxaboutchars = $ds[ 'aboutchars' ];
        if (empty($maxaboutchars)) {
        $maxaboutchars = 60;
        }  

        $text = preg_replace("/<div>/", "", $text);
        $text = preg_replace("/<p>/", "", $text);
        $text = preg_replace("/<strong>/", "", $text);
        $text = preg_replace("/<em>/", "", $text);
        $text = preg_replace("/<s>/", "", $text);
        $text = preg_replace("/<u>/", "", $text);
        $text = preg_replace("/<blockquote>/", "", $text);

        $text = preg_replace("//", "", substr( $text, 0, $maxaboutchars  ) ) . ' ... <div class="text-end"> <a href="index.php?site=about_us" class="btn btn-dark btn-sm">READ MORE <i class="bi bi-chevron-double-right"></i></a></div>';

        $ergebnis=safe_query("SELECT * FROM ".PREFIX."settings_themes WHERE active = 1");
        $ds=mysqli_fetch_array($ergebnis);

        $logo = '<a class="navbar-brand float-start bg img-fluid" href="index.php"><img src="/includes/themes/' . $theme_name . '/images/' . $ds['logo_pic'] . '" width="250px"  alt=""></a>';

        $sortierung = 'socialID ASC';
        $ergebnis = safe_query("SELECT * FROM `".PREFIX."settings_social_media` ORDER BY $sortierung");
        if(mysqli_num_rows($ergebnis)){
            while ($ds = mysqli_fetch_array($ergebnis)) {
        
                if ($ds[ 'twitch' ] != '') {
                    if (stristr($ds[ 'twitch' ], "https://")) {
                        $twitch = '<a class="nav-link twitch" href="' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow">Twitch</a>';//https
                    } else {
                        $twitch = '<a class="nav-link twitch" href="http://' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow">Twitch</a>';//http
                    }
                } else {
                    $twitch = '';
                }
        
                if ($ds[ 'facebook' ] != '') {
                    if (stristr($ds[ 'facebook' ], "https://")) {
                        $facebook = '<a class="nav-link facebook" href="' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow">Facebook</a>';//https
                    } else {
                        $facebook = '<a class="nav-link facebook" href="http://' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow">Facebook</a>';//http
                    }
                } else {
                    $facebook = '';
                }
        
                if ($ds[ 'twitter' ] != '') {
                    if (stristr($ds[ 'twitter' ], "https://")) {
                        $twitter = '<a class="nav-link twitter" href="' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow">Twitter</a>';//https
                    } else {
                        $twitter = '<a class="nav-link twitter" href="http://' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow">Twitter</a>';//http
                    }
                } else {
                    $twitter = '';
                }
        
                if ($ds[ 'youtube' ] != '') {
                    if (stristr($ds[ 'youtube' ], "https://")) {
                        $youtube = '<div><a class="nav-link youtube" href="' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow">YouTube</a></div>';//https
                    } else {
                        $youtube = '<div><a class="nav-link youtube" href="http://' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow">YouTube</a></div>';//http
                    }
                } else {
                    $youtube = '';
                }

                if ($ds[ 'steam' ] != '') {
                    if (stristr($ds[ 'steam' ], "https://")) {
                        $steam = '<a class="nav-link steam" href="' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow">Steam</a>';//https
                    } else {
                        $steam = '<a class="nav-link steam" href="http://' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow">Steam</a>';//http
                    }
                } else {
                    $steam = '';
                }

                if ($ds[ 'discord' ] != '') {
                    if (stristr($ds[ 'discord' ], "https://")) {
                        $discord = '<a class="nav-link discord" href="' . htmlspecialchars($ds[ 'discord' ]) . '" target="_blank" rel="nofollow">Discord</a>';//https
                    } else {
                        $discord = '<a class="nav-link discord" href="http://' . htmlspecialchars($ds[ 'discord' ]) . '" target="_blank" rel="nofollow">Discord</a>';//http
                    }
                } else {
                    $discord = '';
                }
        
                if($ds['socialID'] == 1){	
                    $data_array = array();
                    $data_array['$text'] = $text;
                    $data_array['$logo'] = $logo;
                    $data_array['$steam'] = $steam;
                    $data_array['$discord'] = $discord;
                    $data_array['$twitch'] = $twitch;
                    $data_array['$facebook'] = $facebook;
                    $data_array['$twitter'] = $twitter;
                    $data_array['$youtube'] = $youtube;
                    
                    // Prüfen ob Social gesetzt ist
                if($data_array['$twitch'] == "n_a") { $data_array['social1'] = "invisible"; } else { $data_array['social1'] = "visible"; }
                if($data_array['$facebook'] == "n_a") { $data_array['social2'] = "invisible"; } else { $data_array['social2'] = "visible"; }
                if($data_array['$twitter'] == "n_a") { $data_array['social3'] = "invisible"; } else { $data_array['social3'] = "visible"; }
                if($data_array['$youtube'] == "n_a") { $data_array['social4'] = "invisible"; } else { $data_array['social4'] = "visible"; }
                if($data_array['$steam'] == "n_a") { $data_array['social5'] = "invisible"; } else { $data_array['social5'] = "visible"; }
                if($data_array['$discord'] == "n_a") { $data_array['social6'] = "invisible"; } else { $data_array['social6'] = "visible"; }
                }
            }
        }
        $about = $GLOBALS["_template"]->loadTemplate("about_us_box","widget_content", $data_array, $plugin_path);
        echo $about;
    
} else {
        #$plugin_data= array();
        #$plugin_data['$title']=$plugin_language['title'];

    #$template = $GLOBALS["_template"]->loadTemplate("about_us_box","widget_head", $plugin_data, $plugin_path);
    #echo $template;
    echo $plugin_language[ 'no_about' ];
}
?>