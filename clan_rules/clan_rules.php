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
	$plugin_language = $pm->plugin_language("clan_rules", $plugin_path);


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}


if($action=="show"){
    $clan_rulesID = $_GET['clan_rulesID'];
    if(isset($clan_rulesID)){


	#title
	$_language->readModule('clan_rules');
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['clan_rules'];
    $plugin_data['$subtitle']='Clan Rules';

    $template = $GLOBALS["_template"]->loadTemplate("clan_rules","head", $plugin_data, $plugin_path);
    echo $template;


	$get=safe_query("SELECT * FROM ".PREFIX."plugins_clan_rules WHERE clan_rulesID='".$clan_rulesID."' LIMIT 0,1");
        $ds=mysqli_fetch_array($get);

 
        $clan_rulesID = $ds[ 'clan_rulesID' ];


		$poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '">
            <strong>' . getnickname($ds[ 'poster' ]) . '</strong>
        </a>';

			$title = $ds[ 'title' ];
			$text = $ds[ 'text' ];
    
    		$translate = new multiLanguage(detectCurrentLanguage());
    		$translate->detectLanguages($title);
    		$title = $translate->getTextByLanguage($title);
    		$translate->detectLanguages($text);
    		$text = $translate->getTextByLanguage($text);
    
    		$data_array = array();
			$data_array['$title'] = $title;
			$data_array['$text'] = $text;
			$data_array['$date'] = $date;
        	$data_array['$poster'] = $poster;
        	$data_array['$info']= $plugin_language[ 'info' ];
			$data_array['$stand']= $plugin_language[ 'stand' ];

        $clan_rules = $GLOBALS["_template"]->loadTemplate("clan_rules","content_area", $data_array, $plugin_path);
        echo $clan_rules;
		
	} else {
		echo $plugin_language['no_clan_rules'];
	} 
	$data_array = array();
    	$template = $GLOBALS["_template"]->loadTemplate("clan_rules","foot", $data_array, $plugin_path);
    	echo $template;   	



} else {

if ($action == "") {

if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1;    	

	#title
	$_language->readModule('clan_rules');
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['clan_rules'];
    $plugin_data['$subtitle']='Clan Rules';

    
	$template = $GLOBALS["_template"]->loadTemplate("clan_rules","head", $plugin_data, $plugin_path);
    echo $template;

	$alle=safe_query("SELECT clan_rulesID FROM ".PREFIX."plugins_clan_rules WHERE displayed = '1'");
  	$gesamt = mysqli_num_rows($alle);
  	$pages=1;

  
  	$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_clan_rules_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'clan_rules' ];
        if (empty($max)) {
        $max = 1;
        }
 

  	for ($n=$max; $n<=$gesamt; $n+=$max) {
    	if($gesamt>$n) $pages++;
  	}


  	if($pages>1) $page_link = makepagelink("index.php?site=clan_rules", $page, $pages);
    	else $page_link='';

  	if ($page == "1") {
    	$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_clan_rules WHERE displayed = '1' ORDER BY `sort` LIMIT 0,$max");
    	$n=1;
  	}
  	else {
    	$start=$page*$max-$max;
   		$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_clan_rules WHERE displayed = '1' ORDER BY `sort` LIMIT $start,$max");
    	$n = ($gesamt+1)-$page*$max+$max;
  	} 



$ds = safe_query("SELECT * FROM `" . PREFIX . "plugins_clan_rules` ORDER BY `sort`");
	$anzcats = mysqli_num_rows($ds);
    if ($anzcats) {

	$n=1;

        while ($ds = mysqli_fetch_array($ergebnis)) {
        	$poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '">
            <strong>' . getnickname($ds[ 'poster' ]) . '</strong>
        </a>';

			$title = $ds[ 'title' ];
			$text = $ds[ 'text' ];
    
    		$translate = new multiLanguage(detectCurrentLanguage());
    		$translate->detectLanguages($title);
    		$title = $translate->getTextByLanguage($title);
    		$translate->detectLanguages($text);
    		$text = $translate->getTextByLanguage($text);

    		
    		$data_array = array();
			$data_array['$title'] = $title;
			$data_array['$text'] = $text;
        	$data_array['$date'] = $date;
        	$data_array['$poster'] = $poster;
        	$data_array['$info']= $plugin_language[ 'info' ];
			$data_array['$stand']= $plugin_language[ 'stand' ];
		
        $clan_rules = $GLOBALS["_template"]->loadTemplate("clan_rules","content_area", $data_array, $plugin_path);
        echo $clan_rules;
        $n++;
        
        }

		 } else {
        echo $plugin_language['no_clan_rules'];
   
	
  }
  
  echo'<br>';
   if($pages>1) echo $page_link;

}
}

?>