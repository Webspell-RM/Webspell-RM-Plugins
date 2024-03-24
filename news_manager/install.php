<?php
global $userID,$_database,$add_database_install,$add_database_insert,$add_two_navigation;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname,$two_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}News{[en]}News{[it]}Notizie";                 // name of the plugin
$modulname               =   "news_manager";                 // name to uninstall
$info                    =   "{[de]}News Manager ist ein komplettes News-Management-Plugin für Webspell-RM. Es ermöglicht Ihnen das Hinzufügen, Verwalten und Anzeigen von Nachrichten auf Ihrer Webspell-RM-Seite, einschließlich Datumsarchiven, Nachrichtenkategorien, Nachrichtentags und mehreren Nachrichten-Widgets.{[en]}News Manager is a complete news management plugin for Webspell RM. It allows you to add, manage and view news on your Webspell RM page including date archives, news categories, news tags and multiple news widgets.{[it]}News Manager è un plug-in completo di gestione delle notizie per Webspell RM. Ti consente di aggiungere, gestire e visualizzare le notizie sulla tua pagina Webspell RM inclusi archivi di date, categorie di notizie, tag di notizie e più widget di notizie.";         // description of the plugin
$navi_name               =   "{[de]}Nachrichtenmanager{[en]}News Manager{[it]}Notizie";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_news_manager";          // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "news_manager,news_archive,news_comments,news_contents";     // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/news_manager/";// plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_news_content";         // widget_file (visible as module/box)
$widget_link2            =   "widget_news_headlines";       // widget_file (visible as module/box)
$widget_link3            =   "widget_news_headlines_2";     // widget_file (visible as module/box)
$widgetname1             =   "News Content";                // widget_name (visible as module/box)
$widgetname2             =   "News Headlines";              // widget_name (visible as module/box)
$widgetname3             =   "News Headlines 2";            // widget_name (visible as module/box)
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
$widget3                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "1";                           // navigation category
$navi_link               =   "news_manager";                // navigation link file (index.php?site=...)
$catID                   =   "7";                           // dashboard_navigation category
$dashnavi_link           =   "admin_news_manager";          // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";

$two_str                 =   "News Archive";                        // name of the plugin
$two_navi_name           =   "{[de]}News Archive{[en]}News Archive{[it]}Archivio Notizie";// name of the Webside Navigation / Dashboard Navigation
$two_navi_link           =   "news_manager&action=news_archive";    // navigation link file (index.php?site=...)
$two_modulname           =   "news_manager_archive";                // name to uninstall
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news` (
  `newsID` int(11) NOT NULL AUTO_INCREMENT,
  `rubric` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  `poster` int(11) NOT NULL DEFAULT '0',
  `headline` varchar(255) NOT NULL DEFAULT '',
  `link1` varchar(255) NOT NULL,
  `url1` varchar(255) NOT NULL DEFAULT '',
  `window1` int(11) NOT NULL DEFAULT '0',
  `link2` varchar(255) NOT NULL,
  `url2` varchar(255) NOT NULL,
  `window2` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '',
  `displayed` int(11) NOT NULL DEFAULT '0',
  `screens` text NOT NULL,
  `comments` int(1) NOT NULL DEFAULT '0',
  `recomments` int(1) NOT NULL,
  PRIMARY KEY (`newsID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_rubrics` (
  `rubricID` int(11) NOT NULL AUTO_INCREMENT,
  `rubric` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `displayed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rubricID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_settings` (
  `newssetID` int(11) NOT NULL AUTO_INCREMENT,
  `admin_news` int(11) NOT NULL DEFAULT '0',
  `news` int(11) NOT NULL DEFAULT '0',
  `newsarchiv` int(11) NOT NULL DEFAULT '0',
  `headlines` int(11) NOT NULL DEFAULT '0',
  `newschars` int(11) NOT NULL DEFAULT '0',
  `headlineschars` int(11) NOT NULL DEFAULT '0',
  `topnewschars` int(11) NOT NULL DEFAULT '0',
  `feedback` int(11) NOT NULL DEFAULT '0',
  `switchen` int(11) NOT NULL DEFAULT '12',
  PRIMARY KEY (`newssetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");  

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_news_settings` (`newssetID`, `admin_news`, `news`, `newsarchiv`, `headlines`, `newschars`, `headlineschars`, `topnewschars`, `feedback`, `switchen`) VALUES (1, 5, 3, 10, 4, 700, 200, 200, 5, 12)");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) NOT NULL DEFAULT '0',
  `type` char(2) NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `date` int(14) NOT NULL DEFAULT '0',
  `newscomments` text NOT NULL,
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`commentID`),
  KEY `parentID` (`parentID`),
  KEY `type` (`type`),
  KEY `date` (`date`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci"); 

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_comments_recomment` (
  `recoID` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `datetime` int(14) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `parentID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`recoID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci"); 

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#Prüft ob die Kategorie vorhanden ist
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "navigation_website_main WHERE mnavID='1'"));
if (@$dx[ 'mnavID' ] != '1') {
add_navigation($add_navigation = "INSERT INTO `".PREFIX."navigation_website_main` (`mnavID`, `name`, `url`, `default`, `sort`, `isdropdown`, `windows`) VALUES
(1, '{[de]}HAUPT{[en]}MAIN{[pl]}STRONA GÅÃ“WNA{[it]}PRINCIPALE', '#', 1, 1, 1, 1);");

add_two_navigation($add_two_navigation = "INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) 
          VALUES ('1','$two_navi_name', '$two_modulname', 'index.php?site=$two_navi_link', '1', '1', '$themes_modulname');"); 

} else {

add_two_navigation($add_two_navigation = "INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) 
          VALUES ('1','$two_navi_name', '$two_modulname', 'index.php?site=$two_navi_link', '1', '1', '$themes_modulname');"); 
}
# END

#######################################################################################################################################

echo "</div></div>";
  
 ?>