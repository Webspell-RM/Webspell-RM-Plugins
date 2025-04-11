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
    $template = $GLOBALS["_template"]->loadTemplate("facebook","head", $plugin_data, $plugin_path);
    echo $template;


#if(isset($_COOKIE['im_facebook'])) { 

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "settings_social_media");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);
    



    	    $facebook_login = $ds['facebook'];

            #$facebook='<div class="fb-page" data-tabs="timeline,events,messages" data-href="'.$facebook_login.'" data-width="680" data-hide-cover="false"></div>';



            if(isset($_COOKIE['im_facebook'])) {
            $facebook = '<div class="fb-page" data-tabs="timeline,events,messages" data-href="'.$facebook_login.'" data-width="680" data-autoscale data-widget style="height: 580px"></div>';
        } else {
            $facebook = '<div data-service="facebook" data-id="'.$facebook_login.'" data-title="Facebook" data-widget style="min-height: 250px;width: 300px"></div>';
        }  


            $data_array = array();
            $data_array['$facebook'] = $facebook;

            

        $template = $GLOBALS["_template"]->loadTemplate("facebook","content_area", $data_array, $plugin_path);
        echo $template;


    $data_array = array();
	$template = $GLOBALS["_template"]->loadTemplate("facebook","foot", $data_array, $plugin_path);
    echo $template;
}

/*
} else {
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "settings_social_media");
    if (mysqli_num_rows($ergebnis)) {
        $ds = mysqli_fetch_array($ergebnis);
        $facebook = $ds['facebook'];
        
        echo'<div class="card">
        <div class="card-body text-center" style="background-image: linear-gradient(to bottom,grey 0%,black 80%),url(/includes/plugins/socialmedia/images/facebook-logo.jpg); 
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-blend-mode:multiply;
  border-radius: var(--bs-border-radius);">

        <div class="fb-page" style="width: 580px" 
        data-service="facebook_content"
         data-id="href='.$facebook.'"
         data-title="Facebook"
          data-autoscale data-widget>

         </div></div></div>';
    }
}*/
?>