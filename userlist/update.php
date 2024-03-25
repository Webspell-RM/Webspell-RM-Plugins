<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}User Liste{[en]}User List{[it]}Lista Utenti";                  // name of the plugin
$modulname               =   "userlist";                    // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euer Registered Users anzeigen lassen.{[en]}With this plugin you can display your registered user.{[it]}Con questo plugin puoi visualizzare la lista dei tuoi utenti registrati.";// description of the plugin
$navi_name               =   "{[de]}User Liste{[en]}User List{[it]}Lista Utenti";   // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_userlist";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "userlist";                    // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/userlist/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_lastregistered";       // widget_file (visible as module/box)
$widget_link2            =   "widget_useronline_sidebar";   // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Last Registered";             // widget_name (visible as module/box)
$widgetname2             =   "User Online Sidebar";         // widget_name (visible as module/box)
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
$widget2                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "3";                           // navigation category
$navi_link               =   "userlist";                    // navigation link file (index.php?site=...)
$catID                   =   "3";                           // dashboard_navigation category
$dashnavi_link           =   "admin_userlist";              // dashboard_navigation link file  (admincenter.php?site==...)
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
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_userlist");
DeleteData("settings_module","modulname","userrights");
DeleteData("navigation_website_sub","modulname","userrights");
DeleteData("navigation_dashboard_links","modulname","userrights");
DeleteData("settings_plugins","modulname","userrights");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_userlist` (
  `ruID` int(11) NOT NULL AUTO_INCREMENT,
  `users_list` int(11) NOT NULL DEFAULT '0',
  `users_online` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ruID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_userlist` (`ruID`, `users_list`, `users_online`) VALUES ('1', '15', '5')");

#altes Plugin entfernt
$dir_name = "useronline";
$name = str_replace("/", "", $dir_name);  
$modul_name = "useronline";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname",$modul_name);
DeleteData("navigation_dashboard_links","modulname",$modul_name);
DeleteData("navigation_website_sub","modulname",$modul_name);
DeleteData("settings_module","modulname",$modul_name);
DeleteData("settings_widgets","modulname",$modul_name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$modul_name."");
recursiveRemoveDirectory('../includes/plugins/'. $dir_name);

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>