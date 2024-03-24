<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Artikel{[en]}Articles{[it]}Articoli";// name of the plugin
$modulname               =   "articles";                    // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr eure Articles anzeigen lassen.{[en]}With this plugin you can display your articles.{[it]}Con questo plugin è possibile mostrare gli Articoli sul sito web.";// description of the plugin
$navi_name               =   "{[de]}Artikel{[en]}Articles{[it]}Articoli";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_articles";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "articles,articles_rating,articles_comments"; // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/articles/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_articles_sidebar";     // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Articles Sidebar";            // widget_name (visible as module/box)
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
$mnavID                  =   "3";                           // navigation category
$navi_link               =   "articles";                    // navigation link file (index.php?site=...)
$catID                   =   "7";                           // dashboard_navigation category
$dashnavi_link           =   "admin_articles";              // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

#@info: database
add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_articles_categories` (
  `articlecatID` int(11) NOT NULL AUTO_INCREMENT,
  `articlecatname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`articlecatID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_articles` (
  `articleID` int(11) NOT NULL AUTO_INCREMENT,
  `articlecatID` int(11) NOT NULL DEFAULT '0',
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  `poster` int(11) NOT NULL DEFAULT '0',
  `displayed` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `comments` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`articleID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_articles_comments` (
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

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_articles_settings` (
  `articlessetID` int(11) NOT NULL AUTO_INCREMENT,
  `articles` int(11) NOT NULL,
  `articleschars` int(11) NOT NULL,
  PRIMARY KEY (`articlessetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_articles_settings` (`articlessetID`, `articles`, `articleschars`) VALUES
(1, 4, '300')");

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>