<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Teams{[en]}Teams{[it]}Squadre";                      // name of the plugin
$modulname               =   "squads";                      // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Teams anzeigen lassen.{[en]}With this plugin you can display your teams.{[it]}Con questo plugin puoi visualizzare le tue Squadre con i Membri.";// description of the plugin
$navi_name               =   "[de]}Teams & Mitglieder{[en]}Teams & Members{[it]}Squadre & Membri";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_squads";                // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "squads";                      // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/squads/";    // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_squads_roster";        // widget_file (visible as module/box)
$widget_link2            =   "widget_squads_content";       // widget_file (visible as module/box)
$widget_link3            =   "widget_squads_sidebar";       // widget_file (visible as module/box)
$widgetname1             =   "Squads Roster Content";               // widget_name (visible as module/box)
$widgetname2             =   "Squads Content";              // widget_name (visible as module/box)
$widgetname3             =   "Squads Sidebar";              // widget_name (visible as module/box)
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
$mnavID                  =   "2";                           // navigation category
$navi_link               =   "squads";                      // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_squads";                // dashboard_navigation link file  (admincenter.php?site==...)
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

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads` (
  `squadID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesquad` int(11) NOT NULL DEFAULT 1,
  `games` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon_small` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`squadID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads_members` (
  `sqmID` int(11) NOT NULL AUTO_INCREMENT,
  `squadID` int(11) NOT NULL DEFAULT 0,
  `userID` int(11) NOT NULL DEFAULT 0,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `activity` int(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `joinmember` int(1) NOT NULL DEFAULT 0,
  `warmember` int(1) NOT NULL DEFAULT 0,
PRIMARY KEY (`sqmID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads_settings` (
  `squadssetID` int(11) NOT NULL AUTO_INCREMENT,
  `squads` int(11) NOT NULL,
  `squadschars` int(11) NOT NULL,
  PRIMARY KEY (`squadssetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_squads_settings` (`squadssetID`, `squads`, `squadschars`) VALUES
(1, 1, '')");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
    
 ?>