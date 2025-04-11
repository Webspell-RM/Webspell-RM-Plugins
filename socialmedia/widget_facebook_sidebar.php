<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/
# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$plugin_language = $pm->plugin_language("socialmedia", $plugin_path);

	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['facebook'];
    $plugin_data['$subtitle']='Facebook';
    $template = $GLOBALS["_template"]->loadTemplate("facebook","widget_head", $plugin_data, $plugin_path);
    echo $template;


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
        $template = $GLOBALS["_template"]->loadTemplate("facebook","widget_content_fb1", $data_array, $plugin_path);
        echo $template;
    }
}

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_facebook WHERE fb2_activ = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
    	    $facebook = $ds['facebook'];

			$data_array = array();
			$data_array['$facebook'] = $facebook;
        $template = $GLOBALS["_template"]->loadTemplate("facebook","widget_content_fb2", $data_array, $plugin_path);
        echo $template;
    }
}

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_facebook WHERE fb3_activ = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
    	    $facebook = $ds['facebook'];
            $data_array = array();
            $data_array['$facebook'] = $facebook;
        $template = $GLOBALS["_template"]->loadTemplate("facebook","widget_content_fb3", $data_array, $plugin_path);
        echo $template;
    }
}

$sql = safe_query("SELECT * FROM ".PREFIX."plugins_facebook WHERE fb4_activ = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
    	    $facebook = $ds['facebook'];
            $data_array = array();
            $data_array['$facebook'] = $facebook;
        $template = $GLOBALS["_template"]->loadTemplate("facebook","widget_content_fb4", $data_array, $plugin_path);
        echo $template;
    }
}


    $data_array = array();
	$template = $GLOBALS["_template"]->loadTemplate("facebook","widget_foot", $data_array, $plugin_path);
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