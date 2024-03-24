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
$plugin_language = $pm->plugin_language("about_us", $plugin_path);


#$data_array = array();
#$about_sponsor = $GLOBALS["_template"]->loadTemplate("about_us","widget_content_head", $data_array, $plugin_path);
#echo $about_sponsor;


$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);
        $title = $ds[ 'title' ];
        $text = $ds[ 'text' ];
            
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
                
        $translate->detectLanguages($text);
        $text = $translate->getTextByLanguage($text);

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
        $dx = mysqli_fetch_array($settings);
    
        $maxaboutchars = $dx['aboutchars'];
        if (empty($maxaboutchars )) {
            $maxaboutchars  = 200;
        } 

        $text = preg_replace("/<div>/", "", $text);
        $text = preg_replace("/<p>/", "", $text);
        $text = preg_replace("/<strong>/", "", $text);
        $text = preg_replace("/<em>/", "", $text);
        $text = preg_replace("/<s>/", "", $text);
        $text = preg_replace("/<u>/", "", $text);
        $text = preg_replace("/<blockquote>/", "", $text);

        $text = preg_replace("//", "", substr( $text, 0, $maxaboutchars  ) ) . ' ... ';

        $data_array = array();
        $data_array['$title'] = $title;
        $data_array['$text'] = $text;

        $about = $GLOBALS["_template"]->loadTemplate("about_us_box_content","widget_content_two", $data_array, $plugin_path);
        echo $about;

        #$about_sponsor = $GLOBALS["_template"]->loadTemplate("about_us","widget_content_foot", $data_array, $plugin_path);
        #echo $about_sponsor;

} else {
    #echo '<div class="container card"';
        $plugin_data= array();
        $plugin_data['$title']=$plugin_language['title'];

    $template = $GLOBALS["_template"]->loadTemplate("about_us","widget_head", $plugin_data, $plugin_path);
    echo $template;
    echo $plugin_language[ 'no_about' ];
    #echo '</div>';
}

