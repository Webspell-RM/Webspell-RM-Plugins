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
    $plugin_language = $pm->plugin_language("planning", $plugin_path);

    $data_array = array();
    $data_array['$title']=$plugin_language['planning'];
    $data_array['$subtitle']='Planung';
    $template_content = $GLOBALS["_template"]->loadTemplate("planning","title", $data_array, $plugin_path);
    echo $template_content;

// get projects
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_planning");
//if projects found
if (mysqli_num_rows($ergebnis)) {

    $data_array = array();
    $template_content = $GLOBALS["_template"]->loadTemplate("planning","head", $data_array, $plugin_path);
    echo $template_content;


	$result = safe_query("SELECT * FROM " . PREFIX . "plugins_planning ORDER by `date`");
	$x = 1;
	while ($ds = mysqli_fetch_array($result)) {

	$planID = $ds[ 'planID' ];
	$name = $ds[ 'name' ];
	$date = getformatdate($ds[ 'date' ]);
	$progress = $ds[ 'progress' ];
	$link = $ds[ 'link' ];

			$translate = new multiLanguage(detectCurrentLanguage());
    		$translate->detectLanguages($name);
    		$name = $translate->getTextByLanguage($name);

	$data_array = array();
	$data_array['$planID'] = $planID;
	$data_array['$name'] = $name;
	$data_array['$date'] = $date;
	$data_array['$progress'] = $progress;
	$data_array['$link'] = $link;

	$data_array['$releasedate']=$plugin_language['releasedate'];
	$template_content = $GLOBALS["_template"]->loadTemplate("planning","content", $data_array, $plugin_path);
	echo $template_content;
	$x++;
	}
    $data_array = array();
    $template_content = $GLOBALS["_template"]->loadTemplate("planning","foot", $data_array, $plugin_path);
    echo $template_content;
} else {
	echo '' . $plugin_language[ 'no_entries' ] . '';
}

?>