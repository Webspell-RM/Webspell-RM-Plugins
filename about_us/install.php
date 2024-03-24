<?php
global $userID,$_database,$add_database_install,$add_database_insert,$add_plugin_manager,$add_navigation,$add_dashboard_navigation,$add_module_install,$str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}About{[en]}About{[it]}Chi Siamo";// name of the plugin
$modulname               =   "about_us";                		// name to uninstall
$info                    =   "{[de]}Dieses Widget zeigt allgemeine Informationen (kleiner Lebenslauf) über Sie auf Ihrer Webspell-RM-Seite an.{[en]}This widget will show general information (small resume) About You on your Webspell-RM site.{[it]}Questo widget mostrerà informazioni generali (piccolo curriculum) su di te sul tuo sito Webspell-RM.";// description of the plugin
$navi_name               =   "{[de]}About{[en]}About{[it]}Chi Siamo";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_about_us";           		// administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "about_us";   									// index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/about_us/";	// plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_about_sidebar";     		// widget_file (visible as module/box)
$widget_link2            =   "widget_about_content";     		// widget_file (visible as module/box)
$widget_link3            =   "widget_about_sidebar_verdux";	// widget_file (visible as module/box)
$widgetname1             =   "About Sidebar";            		// widget_name (visible as module/box)
$widgetname2             =   "About Content";            		// widget_name (visible as module/box)
$widgetname3             =   "About Sidebar Verdux";     		// widget_name (visible as module/box)
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
$widget2 				 				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget3  							 =   "1";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID           			 =   "2";              							// navigation category
$navi_link               =   "about_us";                 		// navigation link file (index.php?site=...)
$catID           				 =   "4";              							// dashboard_navigation category
$dashnavi_link           =   "admin_about_us";           		// dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);		
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_about_us` (
	`aboutID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `aboutchars` varchar(255) NOT NULL,
   PRIMARY KEY (`aboutID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>