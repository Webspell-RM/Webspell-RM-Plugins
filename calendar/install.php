﻿<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Kalender{[en]}Calendar{[it]}Calendario";// name of the plugin
$modulname               =   "calendar";                    // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euer Kalender zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your calendar to websites.{[it]}Con questo plugin è possibile mostrare il Calendario sul sito web.";// description of the plugin
$navi_name               =   "{[de]}Kalender{[en]}Calendar{[it]}Calendario";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_calendar";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "calendar,admin_calendar";     // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.3";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/calendar/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_calendar_sidebar";     // widget_file (visible as module/box)
$widget_link2            =   "widget_upcoming_sidebar";     // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Calendar Sidebar";            // widget_name (visible as module/box)
$widgetname2             =   "Upcoming Sidebar";            // widget_name (visible as module/box)
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
$widget2                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "1";                           // navigation category
$navi_link               =   "calendar";                    // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_calendar";              // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_upcoming` (
  `upID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `squad` int(11) NOT NULL DEFAULT '0',
  `opponent` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `opptag` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `opphp` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `maps` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gametype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `matchtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `spielanzahl` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `server` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `league` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `leaguehp` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `warinfo` text COLLATE utf8_unicode_ci NOT NULL,
  `short` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `enddate` int(14) NOT NULL DEFAULT '0',
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `locationhp` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dateinfo` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`upID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_upcoming_announce` (
  `annID` int(11) NOT NULL AUTO_INCREMENT,
  `upID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`annID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>