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
	$plugin_language = $pm->plugin_language("tiktok", $plugin_path);

	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['title'];
    $plugin_data['$subtitle']='tiktok';
    $template = $GLOBALS["_template"]->loadTemplate("tiktok","widget_head", $plugin_data, $plugin_path);
    echo $template;

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_tiktok WHERE displayedh = '1' ORDER BY sort");
#if(mysqli_num_rows($sql)) {
    echo'<div class="card"><div class="row">';
    while($ds = mysqli_fetch_array($sql)) {
        $tiktok_name = $ds['tiktok_name'];
        $tiktok_id = $ds['tiktok_id'];
        $tiktok_title = $ds['tiktok_title'];
        //$tiktok_height = $ds['tiktok_height'];


        if(isset($_COOKIE['im_tiktok'])) {
            $tiktok = '<div class="col d-flex justify-content-center" style="height: 340px;width: 220px;padding: inherit;">
            <div data-title="tiktok" style="height: 770px; transform: scale(0.4);margin-top: -215px;margin-bottom: -15px;">
            <div style="width: 312px;" data-service="tiktok" data-id="'.$tiktok_id.'" data-title="Tiktok" data-widget></div></div></div>';
       } else {
           $tiktok = '<div class="col d-flex justify-content-center" style="height: 340px;width: 220px;padding: inherit;">
            <div data-title="tiktok" style="height: 770px; transform: scale(0.4);margin-top: -215px;margin-bottom: -15px;">
            <div style="width: 312px;" data-service="tiktok" data-id="'.$tiktok_id.'" data-title="Tiktok" data-widget></div></div></div>';
       }        

        $data_array = array();
        $data_array['$tiktok'] = $tiktok;
        $template = $GLOBALS["_template"]->loadTemplate("tiktok","content", $data_array, $plugin_path);
        echo $template;
    }
    echo'</div></div>';

?>