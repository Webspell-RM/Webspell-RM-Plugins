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
    $plugin_language = $pm->plugin_language("linkus", $plugin_path);

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['link_us'];
    $plugin_data['$subtitle']='Link us';
    
        $template = $GLOBALS["_template"]->loadTemplate("linkus","head", $plugin_data, $plugin_path);
        echo $template;

    $filepath = $plugin_path."images/";
    $filepath2 = $plugin_path."images/";
    
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_linkus WHERE displayed = '1' ORDER BY sort");
    if (mysqli_num_rows($ergebnis)) {
        $i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $title = $ds['title'];
            $banner = '<img src="' . $filepath . $ds['banner_pic'] . '" class="img-fluid">';
            $code =
                '&lt;a href=&quot;' . $hp_url . '&quot;&gt;&lt;img src=&quot;' . $hp_url .'/'. $filepath2 .
                $ds['banner_pic'] . '&quot; alt=&quot;' . $myclanname . '&quot;&gt;&lt;/a&gt;';

            $bbcode = '[url='.$hp_url.'][img='.$hp_url.$filepath2.$ds['banner_pic'].'][/url]';

            $data_array = array();
            $data_array['$title'] = $title;
            $data_array['$banner'] = $banner;
            $data_array['$code'] = $code;
            $data_array['$bbcode'] = $bbcode;
            $data_array['$use_following_code']=$plugin_language['use_following_code'];
            
            $template = $GLOBALS["_template"]->loadTemplate("linkus","content", $data_array, $plugin_path);
            echo $template;
            $i++;
        }
    } else {
        echo $plugin_language['no_banner'];
    }

