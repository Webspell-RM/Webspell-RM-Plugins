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
    $plugin_language = $pm->plugin_language("videos", $plugin_path);

	$alle=safe_query("SELECT * FROM ".PREFIX."plugins_videos");

	$data_array = array();
	$data_array['$title'] = $plugin_language[ 'sidebar_media' ];
	$data_array['$subtitle']='Videos';
	$template = $GLOBALS["_template"]->loadTemplate("videos","widget_title_head", $data_array, $plugin_path);
   	echo $template;

   	

$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_videos");
if(mysqli_num_rows($ergebnis)) {

	$template = $GLOBALS["_template"]->loadTemplate("videos","widget_sidebar_head", $data_array, $plugin_path);
   	echo $template;
   	
	$gesamt = mysqli_num_rows($alle);
	$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_videos WHERE widget_displayed = '1' ORDER BY sort");

	$n=1;
	while($ds=mysqli_fetch_array($ergebnis)) {
		echo'<ul class="list-group list-group-flush">';
			$videoID = $ds['youtube'];

			$preview = 'https://img.youtube.com/vi/'.$videoID.'/hqdefault.jpg';
			
			$videoname = $ds['videoname'];
			$videoscatID = $ds['videoscatID'];
			$videosID = $ds['videosID'];
			
			if(mb_strlen($videoname) > 14) {
				$videoname = mb_substr($videoname, 0, 14);
				$videoname .= '...';
			}		
			
			$data_array = array();
			$data_array['$videoname'] = $videoname;
			$data_array['$videosID'] = $videosID;
			$data_array['$videoscatID'] = $videoscatID;
			$data_array['$preview'] = $preview;

	        $template = $GLOBALS["_template"]->loadTemplate("videos","widget_sidebar_content", $data_array, $plugin_path);
	    	echo $template;
	    echo '</ul>'; 
		$n++;

	}

		$data_array = array();
		$template = $GLOBALS["_template"]->loadTemplate("videos","widget_sidebar_foot", $data_array, $plugin_path);
    	echo $template;

} else {
	echo $plugin_language['no_banners'];
}