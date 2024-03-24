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
	$plugin_language = $pm->plugin_language("reddit", $plugin_path);

	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['title'];
    $plugin_data['$subtitle']='reddit';
    $template = $GLOBALS["_template"]->loadTemplate("reddit","widget_head", $plugin_data, $plugin_path);
    echo $template;

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_reddit WHERE (redditID <> '') AND displayedw = '1' ORDER BY RAND() LIMIT 1");
    echo'<div class="card"><div class="row" style="margin-right:0;margin-left:0;">';
    while($ds = mysqli_fetch_array($sql)) {
        $reddit_name = $ds['reddit_name'];
        $reddit_id = $ds['reddit_id'];
        $reddit_title = $ds['reddit_title'];
        $dataid = $reddit_name . "/comments/" . $reddit_id;
        $reddit_height = $ds['reddit_height'];

        if(isset($_COOKIE['im_reddit'])) {
            $reddit = '<div class="col-sm d-flex justify-content-center" style="margin-top: 6px;padding: inherit;">
            <div class="d-flex justify-content-center" data-title="reddit" style="height: '.$reddit_height.'px; width: 100%;margin: 0px 5px 5px 5px;">
            <div data-service="reddit" data-id="'.$dataid.'" data-title="Reddit" style="border-radius: var(--bs-border-radius);" data-widget></div></div></div>';
            } else {
            $reddit = '<div class="col-sm d-flex justify-content-center" style="margin-top: 6px;padding: inherit;">
            <div class="d-flex justify-content-center" data-title="reddit" style="height: '.$reddit_height.'px; width: 100%;margin: 0px 5px 5px 5px;">
            <div data-service="reddit" data-id="'.$dataid.'" data-title="Reddit" style="border-radius: var(--bs-border-radius);" data-widget></div></div></div>';
        }          

        $data_array = array();
        $data_array['$reddit'] = $reddit;
        $template = $GLOBALS["_template"]->loadTemplate("reddit","content", $data_array, $plugin_path);
        echo $template;
    }
    echo'</div></div>';
?>