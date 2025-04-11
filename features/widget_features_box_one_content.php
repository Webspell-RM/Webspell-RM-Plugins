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

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("features", $plugin_path);

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_features_box_one");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);


        $title_one = $ds[ 'title_one' ];
        
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title_one);
        $title_one = $translate->getTextByLanguage($title_one);

        $text_one = $ds[ 'text_one' ];
        $translate->detectLanguages($text_one);
        $text_one = $translate->getTextByLanguage($text_one);

        $title_two = $ds[ 'title_two' ];
        $translate->detectLanguages($title_two);
        $title_two = $translate->getTextByLanguage($title_two);

        $text_two = $ds[ 'text_two' ];
        $translate->detectLanguages($text_two);
        $text_two = $translate->getTextByLanguage($text_two);

        $title_three = $ds[ 'title_three' ];
        $translate->detectLanguages($title_three);
        $title_three = $translate->getTextByLanguage($title_three);

        $text_three = $ds[ 'text_three' ];
        $translate->detectLanguages($text_three);
        $text_three = $translate->getTextByLanguage($text_three);

        $title_four = $ds[ 'title_four' ];
        $translate->detectLanguages($title_four);
        $title_four = $translate->getTextByLanguage($title_four);

        $text_four = $ds[ 'text_four' ];
        $translate->detectLanguages($text_four);
        $text_four = $translate->getTextByLanguage($text_four);

        $title_five = $ds[ 'title_five' ];
        $translate->detectLanguages($title_five);
        $title_five = $translate->getTextByLanguage($title_five);

        $text_five = $ds[ 'text_five' ];
        $translate->detectLanguages($text_five);
        $text_five = $translate->getTextByLanguage($text_five);

        $title_six = $ds[ 'title_six' ];
        $translate->detectLanguages($title_six);
        $title_six = $translate->getTextByLanguage($title_six);

        $text_six = $ds[ 'text_six' ];
        $translate->detectLanguages($text_six);
        $text_six = $translate->getTextByLanguage($text_six);

        $maxfeaturesboxchars = $ds[ 'features_box_chars' ];
        if (empty($maxfeaturesboxchars)) {
        $maxfeaturesboxchars = 160;
        }  
        if (mb_strlen($text_one) > $maxfeaturesboxchars) {
        $text_one = mb_substr($text_one, 0, $maxfeaturesboxchars);
        $text_one .= '...';
        }
        if (mb_strlen($text_two) > $maxfeaturesboxchars) {
        $text_two = mb_substr($text_two, 0, $maxfeaturesboxchars);
        $text_two .= '...';
        }
        if (mb_strlen($text_three) > $maxfeaturesboxchars) {
        $text_three = mb_substr($text_three, 0, $maxfeaturesboxchars);
        $text_three .= '...';
        }
        if (mb_strlen($text_four) > $maxfeaturesboxchars) {
        $text_four = mb_substr($text_four, 0, $maxfeaturesboxchars);
        $text_four .= '...';
        }
        if (mb_strlen($text_five) > $maxfeaturesboxchars) {
        $text_five = mb_substr($text_five, 0, $maxfeaturesboxchars);
        $text_five .= '...';
        }
        if (mb_strlen($text_six) > $maxfeaturesboxchars) {
        $text_six = mb_substr($text_six, 0, $maxfeaturesboxchars);
        $text_six .= '...';
        }
        
        $data_array = array();

        $data_array['$title_one'] = $title_one;
        $data_array['$text_one'] = $text_one;

        $data_array['$title_two'] = $title_two;
        $data_array['$text_two'] = $text_two;

        $data_array['$title_three'] = $title_three;
        $data_array['$text_three'] = $text_three;

        $data_array['$title_four'] = $title_four;
        $data_array['$text_four'] = $text_four;

        $data_array['$title_five'] = $title_five;
        $data_array['$text_five'] = $text_five;

        $data_array['$title_six'] = $title_six;
        $data_array['$text_six'] = $text_six;

        $data_array['$our_services'] = $plugin_language['our_services'];

    $template = $GLOBALS["_template"]->loadTemplate("features","box_one_content", $data_array, $plugin_path);
    echo $template;

} else {
        
    echo $plugin_language[ 'features' ];
}