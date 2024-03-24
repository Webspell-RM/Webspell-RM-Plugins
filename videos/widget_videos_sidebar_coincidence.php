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


    $data_array = array();
    $data_array['$title'] = $plugin_language[ 'sidebar_media' ];
    $data_array['$subtitle']='Videos';

    $template = $GLOBALS["_template"]->loadTemplate("videos","widget_title_head", $data_array, $plugin_path);
    echo $template;

$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_videos");
if(mysqli_num_rows($ergebnis)) {    

    //get banner
    $allbanner = safe_query("SELECT * FROM " . PREFIX . "plugins_videos WHERE displayed='1' ORDER BY RAND() LIMIT 0,1");
    $total = mysqli_num_rows($allbanner);
    if ($total) {
        $template = $GLOBALS["_template"]->loadTemplate("videos","widget_sidebar_head", $data_array, $plugin_path);
        echo $template;
        
        echo'<ul class="list-group list-group-flush">';

    	$db = mysqli_fetch_array($allbanner);
        $videoID = $db['youtube'];
        $videosID = $db['videosID'];

        $preview = 'https://img.youtube.com/vi/'.$videoID.'/hqdefault.jpg';

        $videoname = $db['videoname'];

        $data_array = array();
        $data_array['$videoname'] = $videoname;
        $data_array['$preview'] = $preview;
        $data_array['$videosID'] = $videosID;

        $template = $GLOBALS["_template"]->loadTemplate("videos","widget_sidebar_content", $data_array, $plugin_path);
        echo $template;
        echo'</ul>';
        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("videos","widget_sidebar_foot", $data_array, $plugin_path);
        echo $template;
    }

unset($link);

} else {
    echo $plugin_language['no_banners'];
}
?>