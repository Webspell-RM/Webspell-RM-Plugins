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
	$plugin_language = $pm->plugin_language("history", $plugin_path);

    $_language->readModule('history');

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['history'];
    $plugin_data['$subtitle']='History';
    
    $template = $GLOBALS["_template"]->loadTemplate("history","head", $plugin_data, $plugin_path);
    echo $template;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_history");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);

        $title = $ds[ 'title' ];
            
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
                
        $text = $ds[ 'text' ];
    
        $translate->detectLanguages($text);
        $text = $translate->getTextByLanguage($text);
    
        $data_array = array();
        $data_array['$title'] = $title;
        $data_array['$text'] = $text;

    
        $template = $GLOBALS["_template"]->loadTemplate("history","content", $data_array, $plugin_path);
        echo $template;
                
} else {
        echo ($plugin_language[ 'no_history' ]);
}

