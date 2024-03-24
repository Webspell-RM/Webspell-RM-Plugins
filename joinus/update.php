<?php
global $userID,$_database,$add_database_install,$add_database_insert,$add_two_navigation;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Mitglied werden{[en]}Join Us{[it]}Unisciti a noi";                     // name of the plugin
$modulname               =   "joinus";                      // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr das Joinus anzeigen lassen.{[en]}With this plugin you can display the Joinus.{[it]}Con questo plugin puoi visualizzare il pulsante Unisciti a noi.";// description of the plugin
$navi_name               =   "{[de]}Mitglied werden{[en]}Join Us{[it]}Unisciti a noi";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_joinus";                 // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "joinus";                      // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/joinus/";    // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_joinus_content";       // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Join Us Content";              // widget_name (visible as module/box)
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
$mnavID                  =   "6";                           // navigation category
$navi_link               =   "joinus";                      // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_joinus";                // dashboard_navigation link file  (admincenter.php?site==...)
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
   
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_join_us` (
  `joinusID` int(11) NOT NULL AUTO_INCREMENT,
  `show` int(11) NOT NULL DEFAULT '0',
  `terms_of_use` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`joinusID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_join_us` (`joinusID`, `show`, `terms_of_use`, `title`, `text`) VALUES (1, 1, 1, '{[de]}Werde noch heute ein Mitglied!{[en]}Become a member today!{[it]}Diventa un membro oggi!', '{[de]}Die Webspell-RM Community suchen stetig nach neuen Mitgliedern. Werde ein Teil von einer professionellen und gro&szlig;en Organisation. Werde noch heute ein Webspell-RM Mitglied! {[en]} The Webspell-RM community is constantly looking for new members. Become a part of a professional and large organization. Become a Webspell-RM member today! {[it]} La comunità Webspell-RM è costantemente alla ricerca di nuovi membri. Entra a far parte di un\'organizzazione professionale e di grandi dimensioni. Diventa un membro di Webspell-RM oggi!')");
 
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>