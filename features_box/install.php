<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Features Box{[en]}Features Box{[it]}Box Funzionalità";                  // name of the plugin
$modulname               =   "features_box";                  // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Features anzeigen lassen.{[en]}With this plugin you can display your Features.{[it]}Con questo plugin puoi visualizzare il Box Funzionalità.";// description of the plugin
$navi_name               =   "{[de]}Features Box{[en]}Features Box{[it]}Box Funzionalità";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_features_box";            // administration file
$activate                =   "1";                             // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                       // author
$website                 =   "https://webspell-rm.de";        // authors website
$index_link              =   "";     						              // index file (without extension, also no .php)
$hiddenfiles             =   "";                              // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                           // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/features_box/";// plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_features_box_one_content";   // widget_file (visible as module/box)
$widget_link2            =   "widget_features_box_two_content";   // widget_file (visible as module/box)
$widget_link3            =   "widget_features_box_three_content"; // widget_file (visible as module/box)
$widgetname1             =   "Features One Box Content";          // widget_name (visible as module/box)
$widgetname2             =   "Features Two Box Content";          // widget_name (visible as module/box)
$widgetname3             =   "Features Three Box Content";        // widget_name (visible as module/box)
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
$plugin_module  		     =   "0";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget  		     =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1  				       =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2 				         =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget3  				       =   "1";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "";                            // navigation category
$navi_link               =   "";                            // navigation link file (index.php?site=...)
$catID                   =   "7";                           // dashboard_navigation category
$dashnavi_link           =   "admin_features_box";          // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_features_box` (
  `featuresID` int(11) NOT NULL AUTO_INCREMENT,
  `title_one` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title_small_one` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text_one` text COLLATE utf8_unicode_ci NOT NULL,
  `title_two` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_small_two` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text_two` text COLLATE utf8_unicode_ci NOT NULL,
  `title_three` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_small_three` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text_three` text COLLATE utf8_unicode_ci NOT NULL,
  `features_box_chars` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '160',
  PRIMARY KEY (`featuresID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_features_box` (`featuresID`, `title_one`, `title_small_one`, `text_one`, `title_two`, `title_small_two`, `text_two`, `title_three`, `title_small_three`, `text_three`, `features_box_chars`) VALUES
(1, 'FULL responsive', 'Looks awesome on any device', 'The new version was adjusted with bootstrap so that it\'s possible to display your website perfect on any device. Test it now..', 'add-on & mods', 'expand your system', 'With the Add-ons and modifications you can get your own individual system. Whether a navigation addon or a recaptcha mod, or, or, or.. ', 'Community', 'helping each other', 'If you have issues or problems. The community about Webspell-RM can help to solve a lots of problems.', '160')");

get_add_module_install ();
get_add_plugin_manager();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>