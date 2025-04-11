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
	$plugin_language = $pm->plugin_language("socialmedia", $plugin_path);

	#$plugin_data= array();
    #$plugin_data['$title']=$plugin_language['title'];
    #$sc_facebook = $GLOBALS["_template"]->loadTemplate("sc_facebook","head", $plugin_data, $plugin_path);
    #echo $sc_facebook;

if(isset($_COOKIE['im_facebook'])) { 

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "settings_social_media");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);
    
$sql = safe_query("SELECT * FROM ".PREFIX."plugins_facebook WHERE fb1_activ = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
           $facebook = $ds['facebook'];
            $data_array = array();
            $data_array['$facebook'] = $facebook;
        $template = $GLOBALS["_template"]->loadTemplate("facebook","verdux_widget_content_fb1", $data_array, $plugin_path);
        echo $template;
    }
}

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_facebook WHERE fb2_activ = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
            $facebook = $ds['facebook'];
            $data_array = array();
            $data_array['$facebook'] = $facebook;
        $template = $GLOBALS["_template"]->loadTemplate("facebook","verdux_widget_content_fb2", $data_array, $plugin_path);
        echo $template;
    }
}

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_facebook WHERE fb3_activ = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
            $facebook = $ds['facebook'];
            $data_array = array();
            $data_array['$facebook'] = $facebook;
        $template = $GLOBALS["_template"]->loadTemplate("facebook","verdux_widget_content_fb3", $data_array, $plugin_path);
        echo $template;
    }
}

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_facebook WHERE fb4_activ = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
            $facebook = $ds['facebook'];
            $data_array = array();
            $data_array['$facebook'] = $facebook;
        $template = $GLOBALS["_template"]->loadTemplate("facebook","verdux_widget_content_fb4", $data_array, $plugin_path);
        echo $template;
    }
}


    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("facebook","verdux_widget_foot_foot", $data_array, $plugin_path);
    echo $template;
}

} else {
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "settings_social_media");
    if (mysqli_num_rows($ergebnis)) {
        $ds = mysqli_fetch_array($ergebnis);
        $facebook = $ds['facebook'];
        
        echo'<div class="card">
        <div style="margin: 6px;height: 250px">

        <div class="fb-page" data-service="facebook"
         data-id="href='.$facebook.'"
         data-title="Facebook">
         <!--<div class="fb-page" data-href="'.$facebook.'" data-width="380" data-hide-cover="false" data-show-facepile="false" data-show-posts="false"></div>-->

         </div></div></div>';
    }
}
?>