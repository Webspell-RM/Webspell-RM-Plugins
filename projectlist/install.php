<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Projekt Liste{[en]}Project List{[it]}Elenco progetti";                // name of the plugin
$modulname               =   "projectlist";                  // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euer Projektliste zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your ProjectList to websites.{[it]}Con questo plugin puoi mostrare la tua Lista Progetti sul sito web.";// description of the plugin
$navi_name               =   "{[de]}Projekt Liste{[en]}Project List{[it]}Elenco progetti";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_projectlist";           // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "projectlist,admin_projectlist";// index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/projectlist/";// plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "";     												// widget_file (visible as module/box)
$widget_link2            =   "";     												// widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "";            								// widget_name (visible as module/box)
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
$plugin_module           =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "0";                           //Modulsetting activate 1 yes | 0 no
$widget1                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "6";                           // navigation category
$navi_link               =   "projectlist";                 // navigation link file (index.php?site=...)
$catID                   =   "8";                           // dashboard_navigation category
$dashnavi_link           =   "admin_projectlist";           // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_projectlist` (
  `projectlistID` int(11) NOT NULL AUTO_INCREMENT,
  `projectlistcatID` int(11) NOT NULL DEFAULT '0',
  `question` varchar(255) NOT NULL DEFAULT '',
  `prozent` varchar(255) NOT NULL DEFAULT '',
  `date_time` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  `answer` longtext NOT NULL,
  `poster` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '',
  `displayed` int(1) NOT NULL,
  `rating` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`projectlistID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_projectlist_categories` (
  `projectlistcatID` int(11) NOT NULL AUTO_INCREMENT,
  `projectlistcatname` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`projectlistcatID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_projectlist_settings` (
  `projectlistsetID` int(11) NOT NULL AUTO_INCREMENT,
  `projectlist` int(11) NOT NULL,
  `projectlistchars` int(11) NOT NULL,
  PRIMARY KEY (`projectlistsetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_projectlist_settings` (`projectlistsetID`, `projectlist`, `projectlistchars`) VALUES (1, 4, '300')");  
        
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>