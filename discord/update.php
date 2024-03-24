<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                      =   "Discord";                     // name of the plugin
$modulname                =   "discord";                     // name to uninstall
$info                     =   "{[de]}Mit diesem Plugin könnt ihr ein discord Fenster auf der Webseite anzeigen lassen.{[it]}Con questo plugin è possibile mostrare Discord sul sito web.";// description of the plugin
$navi_name                =   "{[de]}Discord{[en]}Discord{[it]}Discord";// name of the Webside Navigation / Dashboard Navigation
$admin_file               =   "admin_discord";               // administration file
$activate                 =   "1";                           // plugin activate 1 yes | 0 no
$author                   =   "T-Seven";                     // author
$website                  =   "https://webspell-rm.de";      // authors website
$index_link               =   "discord";                     // index file (without extension, also no .php)
$hiddenfiles              =   "";                            // hiddenfiles (background working, no display anywhere)
$version                  =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                     =   "includes/plugins/discord/";   // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1             =   "widget_discord_sidebar";      // widget_file (visible as module/box)
$widget_link2             =   "";                            // widget_file (visible as module/box)
$widget_link3             =   "";                            // widget_file (visible as module/box)
$widgetname1              =   "Discord Sidebar";             // widget_name (visible as module/box)
$widgetname2              =   "";                            // widget_name (visible as module/box)
$widgetname3              =   "";                            // widget_name (visible as module/box)
##### Modul Setting activate yes/no ###################################################################################################
$head_activated           =   "0";                           //Modul activate 1 yes | 0 no 
$content_head_activated   =   "0";                           //Modul activate 1 yes | 0 no 
$content_foot_activated   =   "0";                           //Modul activate 1 yes | 0 no 
$head_section_activated   =   "0";                           //Modul activate 1 yes | 0 no 
$foot_section_activated   =   "0";                           //Modul activate 1 yes | 0 no 
$modul_deactivated        =   "0";                           //Modul activate 1 yes | 0 no
$modul_display            =   "1";                           //Modul activate 1 yes | 0 no
$full_activated           =   "0";                           //Modul activate 1 yes | 0 no
$plugin_settings          =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_module            =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget            =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1                  =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2                  =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                  =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                   =   "5";                           // navigation category
$navi_link                =   "discord";                     // navigation link file (index.php?site=...)
$catID                    =   "11";                          // dashboard_navigation category
$dashnavi_link            =   "admin_discord";               // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname         =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
# Versions-Nummer wird upgedatet
safe_query("UPDATE `".PREFIX."settings_plugins` SET version = '$version' WHERE `modulname` = '$modulname'");
   
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
    
 ?>