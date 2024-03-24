<?php
global $userID,$_database,$add_database_install,$add_database_insert,$add_two_navigation;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname,$two_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Clanwars{[en]}Clanwars{[pl]}Clanwars{[it]}Guerre del Clan";                    // name of the plugin
$modulname               =   "clanwars";                    // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euer Clanwars zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your clanwars to websites.{[it]}Con questo plugin è possibile mostrare le Guerre del Clan sul sit web.";// description of the plugin
$navi_name               =   "{[de]}Clanwars{[en]}Clanwars{[pl]}Clanwars{[it]}Guerre del Clan";  // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_clanwars";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "clanwars,admin_clanwars,clanwars_details,clanwar_result";       // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/clanwars/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_clanwars_sidebar";     // widget_file (visible as module/box)
$widget_link2            =   "widget_clanwars_content";     // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Clanwars Sidebar";            // widget_name (visible as module/box)
$widgetname2             =   "Clanwars Content";            // widget_name (visible as module/box)
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
$str_two                 =   "Clanwars Statistiken";        // name of the plugin
$two_modulname           =   "clanwar_result";              // name to uninstall
$two_navi_name           =   "{[de]}Clanwars Statistiken{[en]}Clanwars Statistics{[pl]}Clanwars{[it]}Guerre del Clan Statistica";// name of the Navi
$two_navi_link           =   "clanwars&action=clanwar_result";// navi link file (index.php?site=...)
#######################################################################################################################################
$mnavID                  =   "2";                           // navigation category
$navi_link               =   "clanwars";                    // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_clanwars";              // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_clanwars` (
  `cwID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `time` int(14) NOT NULL DEFAULT '0',
  `squad` int(11) NOT NULL DEFAULT '0',
  `game` varchar(255) NOT NULL DEFAULT '',
  `league` varchar(255) NOT NULL DEFAULT '',
  `leaguehp` varchar(255) NOT NULL DEFAULT '',
  `opponent` varchar(255) NOT NULL DEFAULT '',
  `opptag` varchar(255) NOT NULL DEFAULT '',
  `oppcountry` char(2) NOT NULL DEFAULT '',
  `opphp` varchar(255) NOT NULL DEFAULT '',
  `opplogo` varchar(255) NOT NULL,
  `maps` varchar(255) NOT NULL DEFAULT '',
  `hometeam` varchar(255) NOT NULL DEFAULT '',
  `oppteam` varchar(255) NOT NULL DEFAULT '',
  `server` varchar(255) NOT NULL DEFAULT '',
  `hltv` varchar(255) NOT NULL,
  `homescore` text COLLATE utf8_unicode_ci,
  `oppscore` text COLLATE utf8_unicode_ci,
  `screens` text NOT NULL,
  `report` text NOT NULL,
  `comments` int(1) NOT NULL DEFAULT '0',
  `linkpage` varchar(255) NOT NULL DEFAULT '',
  `displayed` int(1) NOT NULL DEFAULT '1',
  `ani_title` varchar(255) NOT NULL,
  PRIMARY KEY (`cwID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#Prüft ob die Kategorie vorhanden ist
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "navigation_website_main WHERE mnavID='2'"));
if (@$dx[ 'mnavID' ] != '2') {
add_navigation($add_navigation = "INSERT INTO `".PREFIX."navigation_website_main` (`mnavID`, `name`, `url`, `default`, `sort`, `isdropdown`, `windows`) VALUES
(2, '{[de]}TEAM{[en]}TEAM{[pl]}DRUÅ»YNA{[it]}TEAM', '#', 1, 2, 1, 1);");

add_two_navigation($add_two_navigation = "INSERT INTO `".PREFIX."navigation_website_sub` (`mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) 
          VALUES ('2','$two_navi_name', '$modulname', 'index.php?site=$two_navi_link', '1', '1', '$themes_modulname');"); 

} else {

add_two_navigation($add_two_navigation = "INSERT INTO `".PREFIX."navigation_website_sub` (`mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) 
          VALUES ('2','$two_navi_name', '$modulname', 'index.php?site=$two_navi_link', '1', '1', '$themes_modulname');"); 
}
# END

#######################################################################################################################################

echo "</div></div>";
  
 ?>