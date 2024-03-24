<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Turnier{[en]}Tournament{[it]}Coppa/Torneo";                         // name of the plugin
$modulname               =   "cup";                         // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Cup / Tournament anzeigen lassen.{[en]}With this plugin you can display your cup / tournament.{[it]}Con questo plugin puoi visualizzare la tua coppa/torneo. ";// description of the plugin
$navi_name               =   "{[de]}Turnier{[en]}Tournament{[it]}Coppa/Torneo";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_cup";                   // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "cup,admin_cup";               // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/cup/";       // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_cup_nextmatches_content";// widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Cup next Matches Content";    // widget_name (visible as module/box)
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
$mnavID                  =   "2";                           // navigation category
$navi_link               =   "cup";                         // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_cup";                   // dashboard_navigation link file  (admincenter.php?site==...)
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
            
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_cup_teams` (
  `cupID` int(11) NOT NULL AUTO_INCREMENT,
  `teamid` int(11) NOT NULL,
  `clantag` varchar(10) NOT NULL,
  `name` text NOT NULL,
  `gruppe` int(3) NOT NULL,
  `anordnung` int(2) NOT NULL,
  `hp` varchar(50) NOT NULL,
  `viertel` int(11) NOT NULL,
  `halb` int(11) NOT NULL,
  `finale` int(11) NOT NULL,
  `p1` int(11) NOT NULL,
  `p2` int(11) NOT NULL,
  `p3` int(11) NOT NULL,
  `eg` int(2) NOT NULL,
  `ev` int(2) NOT NULL,
  `eh` int(2) NOT NULL,
  `ef` int(2) NOT NULL,
  `ep3` int(2) NOT NULL,
  `color` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '0.png',
  PRIMARY KEY (`cupID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_cup_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gruppe` varchar(11) NOT NULL,
  `register` varchar(11) NOT NULL,
  `turnier` varchar(11) NOT NULL,
  `preis1` varchar(50) NOT NULL,
  `preis2` varchar(50) NOT NULL,
  `preis3` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=2
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
    
 ?>