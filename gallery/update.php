<?php
global $userID,$_database,$add_database_install,$add_database_insert,$add_plugin_manager_two;
global $str,$str_two,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname,$two_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Galerie{[en]}Gallery{[it]}Galleria Immagini";                     // name of the plugin
$modulname               =   "gallery";                     // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure die Galerie anzeigen lassen.{[en]}With this plugin you can display your gallery. {[it]}Con questo plugin puoi visualizzare la tua Galleria di Immagini.";// description of the plugin
$navi_name               =   "{[de]}Galerie{[en]}Gallery{[it]}Galleria Immagini";  // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_gallery";               // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "gallery,gallery_rating,usergallery,gallery_comments";// index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/gallery/";   // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_gallery_sidebar";      // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Gallery Sidebar";             // widget_name (visible as module/box)
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
$navi_link               =   "gallery";                     // navigation link file (index.php?site=...)
$catID                   =   "8";                           // dashboard_navigation category
$dashnavi_link           =   "admin_gallery";               // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################

##### Install für Plugin und Module ###################################################################################################
$str_two                     =   "Usergallery";                 // name of the plugin
$two_modulname               =   "usergallery";                 // name to uninstall
$info_two                    =   "Bestandteil von der Gallery!!!";    // description of the plugin
$navi_name_two               =   "";                            // name of the Webside Navigation / Dashboard Navigation
$admin_file_two              =   "";                            // administration file
$activate_two                =   "1";                           // plugin activate 1 yes | 0 no
$author_two                  =   "";                            // author
$website_two                 =   "";                            // authors website
$index_link_two              =   "";                            // index file (without extension, also no .php)
$hiddenfiles_two             =   "";                            // hiddenfiles (background working, no display anywhere)
$version_two                 =   "";                            // current version, visit authors website for updates, fixes, ..
$path_two                    =   "";                            // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link_two1            =   "";                            // widget_file (visible as module/box)
$widget_link_two2            =   "";                            // widget_file (visible as module/box)
$widget_link_two3            =   "";                            // widget_file (visible as module/box)
$widgetname_two1             =   "";                            // widget_name (visible as module/box)
$widgetname_two2             =   "";                            // widget_name (visible as module/box)
$widgetname_two3             =   "";                            // widget_name (visible as module/box)
##### Modul Setting activate yes/no ###################################################################################################
$head_activated_two          =   "0";                           //Modul activate 1 yes | 0 no 
$content_head_activated_two  =   "0";                           //Modul activate 1 yes | 0 no 
$content_foot_activated_two  =   "0";                           //Modul activate 1 yes | 0 no 
$head_section_activated_two  =   "0";                           //Modul activate 1 yes | 0 no 
$foot_section_activated_two  =   "0";                           //Modul activate 1 yes | 0 no 
$modul_deactivated_two       =   "0";                           //Modul activate 1 yes | 0 no
$modul_display_two           =   "0";                           //Modul activate 1 yes | 0 no
$full_activated_two          =   "0";                           //Modul activate 1 yes | 0 no
$plugin_settings_two         =   "0";                           //Modulsetting activate 1 yes | 0 no 
$plugin_module_two           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget_two           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget1_two                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget2_two                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3_two                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery` (
  `galleryID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` int(14) NOT NULL,
  `groupID` int(11) NOT NULL,
  `number_of_images` int(11) NOT NULL DEFAULT '10',
  `images_per_page` int(11) NOT NULL DEFAULT '4',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`galleryID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_groups` (
  `groupID` int(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR( 255 ) NOT NULL,
  `sort` INT NOT NULL,
  PRIMARY KEY ( `groupID` )
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_pictures` (
  `picID` int(11) NOT NULL AUTO_INCREMENT,
  `galleryID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `banner` int(11) NOT NULL,
  `dateupl` int(14) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `comments` int(1) NOT NULL DEFAULT 0,
  `votes` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY ( `picID` )
 ) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_settings` (
  `gallerysetID` int(11) NOT NULL AUTO_INCREMENT,
  `publicadmin` int(11) NOT NULL,
  `usergalleries` int(11) NOT NULL DEFAULT '0',
  `maxusergalleries` int(11) NOT NULL,
  `groups` int(11) NOT NULL,
  `images_per_page` int(11) NOT NULL DEFAULT '4',
  PRIMARY KEY (`gallerysetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_comments` (
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

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_gallery_settings` (`gallerysetID`, `publicadmin`, `usergalleries`, `maxusergalleries`, `groups`, `images_per_page`) VALUES (1, 1, 1, 20971520, 4, 4)");

#Prüft ob die Kategorie vorhanden ist
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_module WHERE modulname='$two_modulname'"));
if (@$dx[ 'modulname' ] != $two_modulname) {
  
add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."settings_module` (`pluginID`, `name`, `modulname`, `themes_modulname`, `activate`, `sidebar`, `head_activated`, `content_head_activated`, `content_foot_activated`, `head_section_activated`, `foot_section_activated`, `modul_display`, `full_activated`, `plugin_settings`, `plugin_module`, `plugin_widget`, `widget1`, `widget2`, `widget3`) VALUES ('', '$str_two', '$two_modulname', 'default', '1', 'activated', '$head_activated_two', '$content_head_activated_two', '$content_foot_activated_two', '$head_section_activated_two', '$foot_section_activated_two', '$modul_display_two', '$full_activated_two', '$plugin_settings_two', '$plugin_module_two', '$plugin_widget_two', '$widget1_two', '$widget2_two', '$widget3_two')");
} else {
} 

#Prüft ob die Kategorie vorhanden ist
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='$two_modulname'"));
if (@$dx[ 'modulname' ] != $two_modulname) {

add_plugin_manager_two($add_plugin_manager_two = "INSERT IGNORE INTO `".PREFIX."settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `widgetname1`, `widgetname2`, `widgetname3`, `widget_link1`, `widget_link2`, `widget_link3`, `modul_display`) VALUES ('', '$str_two', '$two_modulname', '$info_two', '$admin_file_two', '$activate_two', '$author_two', '$website_two', '$index_link_two', '$hiddenfiles_two', '$version_two', '$path_two', '$widgetname_two1', '$widgetname_two2', '$widgetname_two3', '$widget_link_two1', '$widget_link_two2', '$widget_link_two3', '$modul_display_two')");
} else {
}
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();  

#######################################################################################################################################

echo "</div></div>";
  
 ?>