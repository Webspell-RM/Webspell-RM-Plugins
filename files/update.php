<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Files";                       // name of the plugin
$modulname               =   "files";                       // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Files anzeigen lassen.{[en]}With this plugin you can display your files.{[it]}Con questo plugin puoi visualizzare i tuoi file. ";// description of the plugin
$navi_name               =   "{[de]}Download{[en]}Download{[it]}Download";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_files";                 // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "admin_files,files,files_rating";     // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/files/";     // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_files_sidebar";        // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Files Sidebar";               // widget_name (visible as module/box)
$widgetname2             =   "";                            // widget_name (visible as module/box)
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
$mnavID                  =   "4";                           // navigation category
$navi_link               =   "files";                       // navigation link file (index.php?site=...)
$catID                   =   "12";                          // dashboard_navigation category
$dashnavi_link           =   "admin_files";                 // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
# Versions-Nummer wird upgedatet
safe_query("UPDATE `".PREFIX."settings_plugins` SET version = '$version' WHERE `modulname` = '$modulname'");
            
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_files_categories` (
  `filecatID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `subcatID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`filecatID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_files` (
  `fileID` int(11) NOT NULL AUTO_INCREMENT,
  `filecatID` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  `update` int(14) NOT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `filesize` varchar(255) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `file` varchar(255) NOT NULL DEFAULT '',
  `mirrors` text NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `accesslevel` int(1) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `poster` int(11) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`fileID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");  

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_files_settings` (
 `filessetID` int(11) NOT NULL AUTO_INCREMENT,
  `sc_files` int(1) NOT NULL,
  PRIMARY KEY (`filessetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");  

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_files_settings` (`filessetID`, `sc_files`) VALUES (1, 1);");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
    
 ?>