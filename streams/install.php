<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Streams";                  	// name of the plugin
$modulname               =   "streams";                  	// name to uninstall
$info				             =   "{[de]}Mit diesem Plugin können die Streams von Twitch, Youtube und Dailymotion auf der Webseite angezeigt werden. Jedoch ist in der aktuellen Version 1.0 nur das hinzufügen von Twitch Streams möglich.{[en]}With this plugin, streams from Twitch, YouTube and Dailymotion can be displayed on the website. However, in the current version 1.0 it is only possible to add Twitch streams.{[de]}Con questo plugin è possibile visualizzare sul sito web gli streaming di Twitch, YouTube e Dailymotion. Tuttavia, nell\'attuale versione 1.0 è possibile aggiungere solo flussi Twitch.";				// description of the plugin
$navi_name               =   "{[de]}Streams{[en]}Streams{[it]}Stream"; 	// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_streams";            	  // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "streams,admin_streams"; 		  // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/streams/";	  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_streams_content";     	// widget_file (visible as module/box)
$widget_link2            =   "";     						            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Streams Content";            	// widget_name (visible as module/box)
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
$plugin_settings  			 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_module  				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget  				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1  							 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2 				 				 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3  							 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "4";                           // navigation category
$navi_link               =   "streams";                     // navigation link file (index.php?site=...)
$catID                   =   "11";                          // dashboard_navigation category
$dashnavi_link           =   "admin_streams";               // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_streams` (
  `streamID` int(11) NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `displayed` varchar(1) NOT NULL DEFAULT '0',
  `widget_displayed` varchar(1) NOT NULL DEFAULT '0',
  `media_widget_displayed` varchar(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL,
  PRIMARY KEY  (`streamID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>