<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Fightus{[en]}Fightus{[it]}Sfide";                     // name of the plugin
$modulname               =   "fightus";                     // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr habt ihr ein Herausforderungsmodul.{[en]}With this plugin you can have a challenge module.{[it]}Con questo plugin puoi avere un modulo di sfida.";// description of the plugin
$navi_name               =   "{[de]}Fightus{[en]}Fightus{[it]}Sfide";  // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_fightus";               // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "fightus,admin_fightus";       // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                       // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/fightus/";   // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "";                            // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "";                            // widget_name (visible as module/box)
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
$plugin_widget           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget1                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "2";                           // navigation category
$navi_link               =   "fightus";                     // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_fightus";               // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

DeleteData("settings_plugins","modulname","fight_us");
DeleteData("settings_module","modulname","fight_us");
DeleteData("navigation_dashboard_links","modulname","fight_us");
DeleteData("navigation_website_sub","modulname","fight_us");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fight_us_challenge` (
  `chID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `cwdate` int(14) NOT NULL DEFAULT '0',
  `squadID` varchar(255) NOT NULL DEFAULT '',
  `opponent` varchar(255) NOT NULL DEFAULT '',
  `opphp` varchar(255) NOT NULL DEFAULT '',
  `league` varchar(255) NOT NULL DEFAULT '',
  `map` varchar(255) NOT NULL DEFAULT '',
  `server` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `gametype` varchar(255) NOT NULL,
  `matchtype` varchar(255) NOT NULL,
  `spielanzahl` varchar(255) NOT NULL,
  `messager` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `opponents` varchar(255) NOT NULL,
  PRIMARY KEY (`chID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fight_us_gametype` (
  `gametypeID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `abkuerzung` varchar(100) NOT NULL DEFAULT '',
  `clanID` int(11) NOT NULL,
  PRIMARY KEY (`gametypeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_fight_us_gametype` (`gametypeID`, `name`, `abkuerzung`, `clanID`) VALUES
(1, 'Search & Destroy', 's&d', 0),
(2, 'Team - Deathmatch', 'tdm', 0)");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fight_us_matchtype` (
  `matchtypeID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `clanID` int(11) NOT NULL,
  PRIMARY KEY (`matchtypeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_fight_us_matchtype` (`matchtypeID`, `name`, `clanID`) VALUES 
(1, 'CB - War', 0),
(2, 'ESL - War', 0),
(3, 'Clanwar', 0),
(4, 'Funwar', 0)");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fight_us_spieleranzahl` (
  `spielanzahlID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `clanID` int(11) NOT NULL,
  PRIMARY KEY (`spielanzahlID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_fight_us_spieleranzahl` (`spielanzahlID`, `name`, `clanID`) VALUES 
(1, '1on1', 0),
(2, '2on2', 0),
(3, '3on3', 0),
(4, '4on4', 0),
(5, '5on5', 0),
(6, '6on6', 0),
(7, 'mehr', 0)");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fight_us_maps` (
  `mapID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `game` varchar(255) NOT NULL,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`mapID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>