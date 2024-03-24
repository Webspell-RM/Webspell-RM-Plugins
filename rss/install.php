<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "RSS";                         // name of the plugin
$modulname               =   "rss";                         // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin können Sie rss auf der Website ansehen.{[en]}With this plugin you can view rss on the website.{[it]}Con questo plugin puoi visualizzare i file RSS sul sito web.";// description of the plugin
$navi_name               =   "{[de]}RSS{[en]}RSS{[it]}RSS"; // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_rss";                   // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven mod by McRobert";     // author
$website                 =   "https://webspell-rm.de https://www.blackangelteam.net";      // authors website
$index_link              =   "rss";                         // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/rss/";       // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_rss_sidebar";          // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "RSS Widget Sidebar";          // widget_name (visible as module/box)
$widgetname2             =   "";                            // widget_name (visible as module/box)
$widgetname3             =   "";                            // widget_name (visible as module/box)
##### Modul Setting activate yes/no ###################################################################################################
$head_activated          =   "0";                           // Modul activate 1 yes | 0 no 
$content_head_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$content_foot_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$head_section_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$foot_section_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$modul_deactivated       =   "0";                           // Modul activate 1 yes | 0 no
$modul_display           =   "1";                           // Modul activate 1 yes | 0 no
$full_activated          =   "0";                           // Modul activate 1 yes | 0 no
$plugin_settings         =   "1";                           // Modulsetting activate 1 yes | 0 no 
$plugin_module           =   "1";                           // Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "1";                           // Modulsetting activate 1 yes | 0 no 
$widget1                 =   "1";                           // Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           // Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           // Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "5";                           // navigation category
$navi_link               =   "rss";                         // navigation link file (index.php?site=...)
$catID                   =   "11";                           // dashboard_navigation category
$dashnavi_link           =   "admin_rss";                   // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_rss` (
  `rssID` int(11) NOT NULL AUTO_INCREMENT,
  `rss_name` varchar(255) NOT NULL,
  `rss_id` varchar(255) NOT NULL,
  `displayed` int(1) NOT NULL,
  `displayedw` int(1) NOT NULL,
  `rss_num` varchar(255) NOT NULL,
  `rss_height` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
   PRIMARY KEY (`rssID`)
   ) AUTO_INCREMENT=1
   DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
  
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_rss_settings` (
  `rsssetID` int(11) NOT NULL AUTO_INCREMENT,
  `rssupdown` int(11) NOT NULL,
  `rss_letters` varchar(255) NOT NULL,
  `rss_height` varchar(255) NOT NULL,
  `rss_speed` varchar(255) NOT NULL,
   PRIMARY KEY (`rsssetID`)
  ) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
  
add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_rss_settings` (`rsssetID`, `rssupdown`, `rss_letters`, `rss_height`, `rss_speed`) VALUES (1, 1, 700, 450, 5)");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_rss` (`rssID`, `rss_name`, `rss_id`, `displayed`, `displayedw`, `rss_num`, `rss_height`, `sort`) VALUES (1, 'vg247.com', 'https://www.vg247.com/feed', 1, 1, '4', '350', 1)");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>