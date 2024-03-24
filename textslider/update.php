<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Text Slider{[en]}Textslider{[it]}Slider di Testo";                  // name of the plugin
$modulname               =   "textslider";                  // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euer Text Slider zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your textslider to websites.{[it]}Con questo plugin puoi mostrare le slider di testo sul sito web.";// description of the plugin
$navi_name               =   "{[de]}Text Slider{[en]}Textslider{[it]}Slider di Testo";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_textslider";            // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "";                            // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/textslider/";// plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_textslider_content";   // widget_file (visible as module/box)
$widget_link2            =   "widget_textslider_content_two";// widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Textslider Content";          // widget_name (visible as module/box)
$widgetname2             =   "Textslider Content Two";      // widget_name (visible as module/box)
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
$plugin_module           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "";                            // navigation category
$navi_link               =   "";                            // navigation link file (index.php?site=...)
$catID                   =   "9";                           // dashboard_navigation category
$dashnavi_link           =   "admin_textslider";            // dashboard_navigation link file  (admincenter.php?site==...)
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
            
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_textslider` (
   `carouselID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ani_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ani_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `ani_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `carousel_pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '1',
  `displayed` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`carouselID`)
) AUTO_INCREMENT=4
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

get_add_module_install ();
get_add_plugin_manager();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
    
 ?>