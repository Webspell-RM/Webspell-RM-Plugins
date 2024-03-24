<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Planning{[en]}Planning{[it]}Pianificazione";                    // name of the plugin
$modulname               =   "planning";                    // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euer Planning zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your planning to websites.{[it]}Con questo plugin puoi mostrare la tua pianificazione ai siti Web.";// description of the plugin
$navi_name               =   "{[de]}Planning{[en]}Planning{[it]}Pianificazione";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_planning";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "planning,admin_planning";     // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/planning/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_planning_sidebar";     // widget_file (visible as module/box)
$widget_link2            =   "";     						            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Planning Sidebar";            // widget_name (visible as module/box)
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
$plugin_settings  			 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_module  				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget  				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1  							 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2 				 				 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3  							 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "6";                           // navigation category
$navi_link               =   "planning";                 		// navigation link file (index.php?site=...)
$catID                   =   "8";                           // dashboard_navigation category
$dashnavi_link           =   "admin_planning";              // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

#@info: database
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_planning` (
  `planID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` int(14) NOT NULL DEFAULT '0',
  `progress` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`planID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>