<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Navigation Sticky";    				// name of the plugin
$modulname               =   "navigation_sticky";    				// name to uninstall
$info		             =   "{[de]}Mit diesem Plugin könnt ihr euch die Navigation Sticky Navbar anzeigen lassen.{[en]}With this plugin you can display the navigation sticky navbar.{[it]}Con questo plugin puoi visualizzare la barra di navigazione fissa.";// description of the plugin
$navi_name               =   "{[de]}Sticky Navi Header{[en]}Sticky Navi Header";                            // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_navigation_sticky";     // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "";     												// index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                       	// current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/navigation_sticky/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_navigation_sticky";		// widget_file (visible as module/box)
$widget_link2            =   "";     												// widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Navigation Sticky";    				// widget_name (visible as module/box)
$widgetname2             =   "";            								// widget_name (visible as module/box)
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
$plugin_module           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "1";                           //Modulsetting activate 1 yes | 0 no
$widget1                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "";                           	// navigation category
$navi_link               =   "";                 						// navigation link file (index.php?site=...)
$catID                   =   "9";                           // dashboard_navigation category
$dashnavi_link           =   "admin_navigation_sticky";     // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }    
      
  echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_navigation_sticky` (
  `headerID` int(11) NOT NULL AUTO_INCREMENT,
  `header_pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `height` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `head_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`headerID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_navigation_sticky` (`headerID`, `header_pic`, `height`, `head_text`, `text`, `link`) VALUES
(1, 'header-bg.jpg', '100vh', 'Webspell-RM sticky navbar', 'Create a sticky top navigation bar with the Navigation Sticky Navbar.', 'https://webspell-rm.de')");

get_add_module_install ();
get_add_plugin_manager();
#get_add_navigation();
get_add_dashboard_navigation ();	


#######################################################################################################################################

echo "</div></div>";
	
 ?>