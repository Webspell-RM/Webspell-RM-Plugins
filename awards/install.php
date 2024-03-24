<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Awards{[en]}Awards{[it]}Premi";// name of the plugin
$modulname               =   "awards";              		// name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Awards mit Slider und Page anzeigen lassen.{[en]}With this plugin you can display your awards with slider and page. {[it]}Con questo plugin puoi visualizzare i tuoi Premi con il dispositivo di scorrimento e la pagina.";	  	   // description of the plugin
$navi_name               =   "{[de]}Awards{[en]}Awards{[it]}Premi";	// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_awards";        		    // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "awards,admin_awards";     	  // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/awards/";  	// plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_awards_content";		    // widget_file (visible as module/box)
$widget_link2            =   "";     						            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Awards Content";      		    // widget_name (visible as module/box)
$widgetname2             =   "";            				        // widget_name (visible as module/box)
$widgetname3             =   "";                            // widget_name (visible as module/box)
##### Modul Setting activate yes/no ###################################################################################################
$head_activated          =   "0";                           //Modul activate 1 yes | 0 no 
$content_head_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$content_foot_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$head_section_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$foot_section_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$modul_deactivated       =   "0";                           //Modul activate 1 yes | 0 no
$modul_display           =   "1";                           //Modul activate 1 yes | 0 no
$full_activated          =   "0";                           //Modul activate 1 yes | 0 no
$plugin_settings         =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_module           =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "2";                           // navigation category
$navi_link               =   "awards";                      // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_awards";                // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_awards` (
  `awardID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `squadID` int(11) NOT NULL DEFAULT '0',
  `award` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(255) NOT NULL,
  `rang` int(11) NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `liga` varchar(255) NOT NULL DEFAULT '',
  `steam` varchar(255) NOT NULL DEFAULT '',
  `twitch` varchar(255) NOT NULL DEFAULT '',
  `youtube` varchar(255) NOT NULL DEFAULT '',
  `instagram` varchar(255) NOT NULL DEFAULT '',
  `facebook` varchar(255) NOT NULL DEFAULT '',
  `twitter` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`awardID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>