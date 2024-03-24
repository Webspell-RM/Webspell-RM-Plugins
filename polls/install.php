<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Umfrage{[en]}Polls{[it]}Sondaggi";                    	  // name of the plugin
$modulname               =   "polls";                    	  // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Umfragen anzeigen lassen.{[en]}With this plugin you can have your surveys displayed.{[it]}Con questo plugin puoi visualizzare i tuoi sondaggi.";// description of the plugin
$navi_name               =   "{[de]}Umfrage{[en]}Polls{[it]}Sondaggi";	  // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_polls";              	  // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "polls,polls_comments";     		// index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/polls/";  	  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_polls_sidebar";     		// widget_file (visible as module/box)
$widget_link2            =   "";     						            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Polls Sidebar";            		// widget_name (visible as module/box)
$widgetname2             =   "";            				        // widget_name (visible as module/box)
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
$navi_link               =   "polls";                       // navigation link file (index.php?site=...)
$catID                   =   "7";                           // dashboard_navigation category
$dashnavi_link           =   "admin_polls";                 // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_polls` (
  `pollID` int(10) NOT NULL AUTO_INCREMENT,
  `aktiv` int(1) NOT NULL DEFAULT '0',
  `laufzeit` bigint(20) NOT NULL DEFAULT '0',
  `titel` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `o1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o3` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o4` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o5` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o6` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o7` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o8` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o9` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o10` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comments` int(1) NOT NULL DEFAULT 0,
  `hosts` text COLLATE utf8_unicode_ci NOT NULL,
  `intern` int(1) NOT NULL DEFAULT '0',
  `userIDs` text COLLATE utf8_unicode_ci NOT NULL,
  `published` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pollID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
  
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_polls_comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) NOT NULL DEFAULT '0',
  `type` char(2) NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `date` int(14) NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`commentID`),
  KEY `parentID` (`parentID`),
  KEY `type` (`type`),
  KEY `date` (`date`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci"); 


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_polls_votes` (
  `pollID` int(10) NOT NULL DEFAULT '0',
  `o1` int(11) NOT NULL DEFAULT '0',
  `o2` int(11) NOT NULL DEFAULT '0',
  `o3` int(11) NOT NULL DEFAULT '0',
  `o4` int(11) NOT NULL DEFAULT '0',
  `o5` int(11) NOT NULL DEFAULT '0',
  `o6` int(11) NOT NULL DEFAULT '0',
  `o7` int(11) NOT NULL DEFAULT '0',
  `o8` int(11) NOT NULL DEFAULT '0',
  `o9` int(11) NOT NULL DEFAULT '0',
  `o10` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pollID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>