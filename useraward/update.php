<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}User Award{[en]}User Award{[it]}Premi Utenti";                  // name of the plugin
$modulname               =   "useraward";                    // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin hast du ein UserAwardSystem.{[en]}With this plugin you can display the UserAwardSystem.{[it]}Con questo plugin puoi visualizzare Premi Utenti.";// description of the plugin
$navi_name               =   "{[de]}User Award{[en]}User Award{[it]}Premi Utenti";  // name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_user_awards";           // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "";                            // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/useraward/"; // plugin files location
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
$plugin_module           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget1                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "";                            // navigation category
$navi_link               =   "";                            // navigation link file (index.php?site=...)
$catID                   =   "4";                           // dashboard_navigation category
$dashnavi_link           =   "admin_user_awards";           // dashboard_navigation link file  (admincenter.php?site==...)
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
DeleteData("settings_module","modulname","user_award");
DeleteData("navigation_website_sub","modulname","user_award");
DeleteData("navigation_dashboard_links","modulname","user_award");
DeleteData("settings_plugins","modulname","user_award");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_user_award` (
  `uwID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11),
  `awardID` int(11),
  PRIMARY KEY (`uwID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_user_award_settings` (
  `allaward` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_user_award_settings` (`allaward`) VALUES ('1')");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_user_award_list` (
  `uawardID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `info` text,
  `awardrequire` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `awardrequirepoints` int(6) DEFAULT NULL,
PRIMARY KEY (`uawardID`)
) AUTO_INCREMENT=27
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

        
add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_user_award_list` (`uawardID`, `name`, `info`, `awardrequire`, `image`, `awardrequirepoints`) VALUES
(1, 'Comments', '{[de]}F&uuml;r mindestens 100 Kommentare{[en]}For at least 100 comments{[it]}Per almeno 100 Commenti', '100', 'community1.png', 0),
(2, 'Comments', '{[de]}F&uuml;r mindestens 250 Kommentare{[en]}For at least 250 comments{[it]}Per almeno 250 Commenti', '250', 'community2.png', 0),
(3, 'Comments', '{[de]}F&uuml;r mindestens 500 Kommentare{[en]}For at least 500 comments{[it]}Per almeno 500 Commenti', '500', 'community3.png', 0),
(4, 'Forum', '{[de]}F&uuml;r mindestens 100 Forenposts{[en]}For at least 100 forum posts{[it]}Per almeno 100 post nel Forum', '100', 'forum1.png', 0),
(5, 'Forum', '{[de]}F&uuml;r mindestens 250 Forenposts{[en]}For at least 250 forum posts{[it]}Per almeno 250 post nel Forum', '250', 'forum2.png', 0),
(6, 'Forum', '{[de]}F&uuml;r mindestens 500 Forenposts{[en]}For at least 500 forum posts{[it]}Per almeno 500 post nel Forum', '500', 'forum3.png', 0),
(7, 'Member', '{[de]}F&uuml;r mindestens 6 Monate Mitgliedschaft{[en]}For a minimum of 6 months membership{[it]}Utente da un Minimo di 6 Mesi', '180', 'member1.png', 0),
(8, 'Member', '{[de]}F&uuml;r mindestens 1 Jahr Mitgliedschaft{[en]}For a minimum of 1 year membership{[it]}Utente da un Minimo di 1 Anno', '365', 'member2.png', 0),
(9, 'Member', '{[de]}F&uuml;r mindestens 2 Jahre Mitgliedschaft{[en]}For a minimum of 2 years membership{[it]}Utente da un Minimo di 2 Anni', '730', 'member3.png', 0),
(10, 'Member', '{[de]}F&uuml;r mindestens 5 Jahre Mitgliedschaft{[en]}For a minimum of 5 years membership{[it]}Utente da un Minimo di 5 Anni', '1825', 'member4.png', 0),
(11, 'News', '{[de]}F&uuml;r mindestens 25 Newsbeitr&auml;ge{[en]}For at least 25 news posts{[it]}Per almeno 25 post di Notizie', '25', 'news1.png', 0),
(12, 'News', '{[de]}F&uuml;r mindestens 50 Newsbeitr&auml;ge{[en]}For at least 50 news posts{[it]}Per almeno 50 post di Notizie', '50', 'news2.png', 0),
(13, 'News', '{[de]}F&uuml;r mindestens 100 Newsbeitr&auml;ge{[en]}For at least 100 news posts{[it]}Per almeno 100 post di Notizie', '100', 'news3.png', 0),
(14, 'Messages', '{[de]}F&uuml;r mindestens 100 gesendete Private Nachrichten{[en]}For at least 100 private messages sent{[it]}Per almeno 100 Messaggi Privati inviati', '100', 'pm1.png', 0),
(15, 'Messages', '{[de]}F&uuml;r mindestens 250 gesendete Private Nachrichten{[en]}For at least 250 private messages sent{[it]}Per almeno 250 Messaggi Privati inviati', '250', 'pm2.png', 0),
(16, 'Messages', '{[de]}F&uuml;r mindestens 500 gesendete Private Nachrichten{[en]}For at least 500 private messages sent{[it]}Per almeno 500 Messaggi Privati inviati', '500', 'pm3.png', 0),
(17, 'Wars', '{[de]}F&uuml;r 5 gespielte Wars{[en]}For 5 wars played{[it]}Per 5 Guerre Disputate', '5', 'war1.png', 0),
(18, 'Wars', '{[de]}F&uuml;r 10 gespielte Wars{[en]}For 10 wars played{[it]}Per 10 Guerre Disputate', '10', 'war2.png', 0),
(19, 'Wars', '{[de]}F&uuml;r 25 gespielte Wars{[en]}For 25 wars played{[it]}Per 25 Guerre Disputate', '25', 'war3.png', 0),
(20, 'Wars', '{[de]}F&uuml;r 50 gespielte Wars{[en]}For 50 wars played{[it]}Per 50 Guerre Disputate', '50', 'war4.png', 0),
(21, 'Wars', '{[de]}F&uuml;r 75 gespielte Wars{[en]}For 75 wars played{[it]}Per 75 Guerre Disputate', '75', 'war5.png', 0),
(22, 'Wars', '{[de]}F&uuml;r 100 gespielte Wars{[en]}For 100 wars played{[it]}Per 100 Guerre Disputate', '100', 'war6.png', 0),
(23, 'Wars', '{[de]}F&uuml;r 150 gespielte Wars{[en]}For 150 wars played{[it]}Per 150 Guerre Disputate', '150', 'war7.png', 0),
(24, 'Wars', '{[de]}F&uuml;r 200 gespielte Wars{[en]}For 200 wars played{[it]}Per 200 Guerre Disputate', '200', 'war8.png', 0),
(25, 'Wars', '{[de]}F&uuml;r 300 gespielte Wars{[en]}For 300 wars played{[it]}Per 300 Guerre Disputate', '300', 'war9.png', 0),
(26, 'Wars', '{[de]}F&uuml;r 500 gespielte Wars{[en]}For 500 wars played{[it]}Per 500 Guerre Disputate', '500', 'war10.png', 0)");

get_add_module_install ();
get_add_plugin_manager();
#get_add_navigation();
get_add_dashboard_navigation ();   

#######################################################################################################################################

echo "</div></div>";
  
 ?>