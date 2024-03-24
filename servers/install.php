<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Servers";                     // name of the plugin
$modulname               =   "servers";                     // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Server anzeigen lassen.{[en]}With this plugin you can display your servers.{[it]}Con questo plugin puoi visualizzare i tuoi server.";// description of the plugin
$navi_name               =   "{[de]}Server{[en]}Servers{[it]}Server";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_servers";               // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "servers";                     // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/servers/";   // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_servers_sidebar";      // widget_file (visible as module/box)
$widget_link2            =   "widget_gametracker_ts_sidebar";// widget_file (visible as module/box)
$widget_link3            =   "widget_gametracker_server_sidebar";// widget_file (visible as module/box)
$widgetname1             =   "Servers Sidebar";             // widget_name (visible as module/box)
$widgetname2             =   "Gametracker TS Sidebar";      // widget_name (visible as module/box)
$widgetname3             =   "Gametracker Server Sidebar";  // widget_name (visible as module/box)
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
$widget3                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "3";                           // navigation category
$navi_link               =   "servers";                     // navigation link file (index.php?site=...)
$catID                   =   "10";                          // dashboard_navigation category
$dashnavi_link           =   "admin_servers";               // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

#altes Plugin entfernt
$dir = "gametracker_server";
$name = str_replace("/", "", $dir);  
$modul_name = "gametracker_server";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname",$modul_name);
DeleteData("navigation_dashboard_links","modulname",$modul_name);
DeleteData("navigation_website_sub","modulname",$modul_name);
DeleteData("settings_module","modulname",$modul_name);
DeleteData("settings_widgets","modulname",$modul_name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$modul_name."");
@recursiveRemoveDirectory('../includes/plugins/'. $dir);

#altes Plugin entfernt
$dir = "gametracker_ts";
$name = str_replace("/", "", $dir);  
$modul_name = "gametracker_ts";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname",$modul_name);
DeleteData("navigation_dashboard_links","modulname",$modul_name);
DeleteData("navigation_website_sub","modulname",$modul_name);
DeleteData("settings_module","modulname",$modul_name);
DeleteData("settings_widgets","modulname",$modul_name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$modul_name."");
@recursiveRemoveDirectory('../includes/plugins/'. $dir);  

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_servers` (
  `serverID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `provider` int(1) NOT NULL DEFAULT '0',
  `game` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`serverID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_servers_settings` (
  `serverssetID` int(11) NOT NULL AUTO_INCREMENT,
  `servers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serverssetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_servers_settings` (`serverssetID`, `servers`) VALUES (1, 5);");
 
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>