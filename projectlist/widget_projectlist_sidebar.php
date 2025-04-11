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
	$plugin_language = $pm->plugin_language("projectlist", $plugin_path);

	$data_array = array();
    $data_array['$title']=$plugin_language['title'];
    $data_array['$subtitle']='Project List';
    $template = $GLOBALS["_template"]->loadTemplate("projectlist","head", $data_array, $plugin_path);
    echo $template;

$qry = safe_query("SELECT * FROM ".PREFIX."plugins_projectlist WHERE projectlistcatID!=0 ORDER BY projectlistcatID DESC LIMIT 0,5");
	$anz = mysqli_num_rows($qry);
	if($anz) {

	$template = $GLOBALS["_template"]->loadTemplate("projectlist","widget_projectlist_head", array(), $plugin_path);
    echo $template;

  $n=1;
	while($ds = mysqli_fetch_array($qry)) {
		$date = date("d.m.Y", $ds['date']);
		$time = date("H:i", $ds['date']);

		$day = date("d", $ds['date']);
        $year = date("Y", $ds['date']);
        $progress = $ds[ 'prozent' ];

        $monate = array(1=>$plugin_language[ 'jan' ],
                        2=>$plugin_language[ 'feb' ],
                        3=>$plugin_language[ 'mar' ],
                        4=>$plugin_language[ 'apr' ],
                        5=>$plugin_language[ 'mar' ],
                        6=>$plugin_language[ 'jun' ],
                        7=>$plugin_language[ 'jul' ],
                        8=>$plugin_language[ 'aug' ],
                        9=>$plugin_language[ 'sep' ],
                        10=>$plugin_language[ 'oct' ],
                        11=>$plugin_language[ 'nov' ],
                        12=>$plugin_language[ 'dec' ]);

        $monat = date("n", $ds['date']);

		$question = $ds[ 'question' ];
		$answer = $ds[ 'answer' ];
		$projectlistcatID = $ds['projectlistcatID'];

		$translate = new multiLanguage(detectCurrentLanguage());
    	$translate->detectLanguages($question);
    	$question = $translate->getTextByLanguage($question);

    	$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist_settings");
        $dn = mysqli_fetch_array($settings);

        #$maxprojectlistchars = 28;
		$maxprojectlistchars = $dn['projectlistchars'];
		if(mb_strlen($question)>$maxprojectlistchars) {
			$question=mb_substr($question, 0, $maxprojectlistchars);
			$question.='...';
		}

		#$maxprojectlistchars = 110;
		$maxprojectlistchars = $dn['projectlistchars'];
        if(mb_strlen($answer)>$maxprojectlistchars) {
            $answer=mb_substr($answer, 0, $maxprojectlistchars);
            $answer.='...';
        }

		

		$title1 = '<a href="index.php?site=projectlist&action=content&projectlistID='.$projectlistcatID.'" data-toggle="tooltip" data-bs-html="true" title="
        '.$question.'<br>'.$date.'">'.$question.'</a>';

		$data_array = array();
		$data_array['$title'] = $title1;
        $data_array['$text'] = $answer;
        $data_array['$day'] = $day;
        $data_array['$date2'] = $monate[$monat];
        $data_array['$year'] = $year;
        $data_array['$progress'] = $progress;

		$data_array['$projectlistcatID'] = $projectlistcatID;
	
		$template = $GLOBALS["_template"]->loadTemplate("projectlist","widget_content", $data_array, $plugin_path);
    	echo $template;
		$n++;
	}
		$template = $GLOBALS["_template"]->loadTemplate("projectlist","widget_projectlist_foot", array(), $plugin_path);
    	echo $template;
}
else {
	echo $plugin_language[ 'no_articles' ];
}
?>
