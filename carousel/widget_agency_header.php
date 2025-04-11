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
$plugin_language = $pm->plugin_language("carousel", $plugin_path);

GLOBAL $theme_name;

    $filepath = $plugin_path."images/";

    $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_settings"));
    
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_agency");
    if (mysqli_num_rows($ergebnis)) {
        $i = 1;
        while ($db = mysqli_fetch_array($ergebnis)) {
            $agency_pic = '' . $filepath . $db['agency_pic'] . '';
            $agency_height = $ds['agency_height'];
            $description = $db['description'];
            $link = $db[ 'link' ];
            $title = $db[ 'title' ];

            if ($db[ 'link' ] != '') {
                if (stristr($db[ 'link' ], "https://")) {
                    $link = '<a data-aos="fade-up" data-aos-delay="200" href="$link" class="btn-get-started scrollto">'.$plugin_language['read_more'].'</a>';//https
                } else {
                    $link = '<a data-aos="fade-up" data-aos-delay="200" href="$link" class="btn-get-started scrollto">'.$plugin_language['read_more'].'</a>';//http
                }
            } else {
                $link = '';
            }

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
        
            $data_array = array();
            $data_array['$agency_pic'] = $agency_pic;
            $data_array['$agency_height'] = $agency_height;
            $data_array['$title'] = $title;
            $data_array['$link'] = $link;
            $data_array['$description'] = $description;
            $data_array['$theme_name'] = $theme_name;
                
            $template = $GLOBALS["_template"]->loadTemplate("agency_header","content", $data_array, $plugin_path);
            echo $template;
            $i++;
        }
    } else {
        echo'<div class="alert alert-danger" role="alert">'.$plugin_language['no_header'].'</div>';
}