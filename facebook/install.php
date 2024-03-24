<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Facebook";                     // name of the plugin
$modulname               =   "facebook";                     // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr ein Facebook Fenster auf der Webseite anzeigen lassen.{[en]}With this plugin you can have a Facebook window displayed on the website.{[it]}Con questo plugin puoi visualizzare una finestra Facebook sul sito web.";// description of the plugin
$navi_name               =   "{[de]}Facebook{[en]}Facebook{[it]}Facebook";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_facebook";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "facebook";                    // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/facebook/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_facebook_sidebar";     // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "widget_facebook_sidebar_verdux";// widget_file (visible as module/box)
$widgetname1             =   "Facebook Sidebar";            // widget_name (visible as module/box)
$widgetname2             =   "";                            // widget_name (visible as module/box)
$widgetname3             =   "Facebook Sidebar Verdux";     // widget_name (visible as module/box)
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
$widget3                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "5";                           // navigation category
$navi_link               =   "facebook";                    // navigation link file (index.php?site=...)
$catID                   =   "11";                          // dashboard_navigation category
$dashnavi_link           =   "admin_facebook";              // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_facebook` (
`facebookID` int(11) NOT NULL AUTO_INCREMENT,
  `fb1_activ` int(11) NOT NULL,
  `fb2_activ` int(11) NOT NULL,
  `fb3_activ` int(11) NOT NULL,
  `fb4_activ` int(11) NOT NULL,
   PRIMARY KEY (`facebookID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_facebook` (`facebookID`, `fb1_activ`, `fb2_activ`, `fb3_activ`, `fb4_activ`) VALUES (1, 0, 1, 0, 0)");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_facebook_content` (
  `fbID` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_name` varchar(255) NOT NULL,
  `facebook_id` varchar(255) NOT NULL,
  `facebook_title` varchar(255) NOT NULL,
  `facebook_height` varchar(255) NOT NULL,
  `displayed` int(1) NOT NULL,
  `sort` int(11) NOT NULL,
PRIMARY KEY (`fbID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>