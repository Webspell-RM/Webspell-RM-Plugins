<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Portfolio";                    // name of the plugin
$modulname               =   "portfolio";                    // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr Portfolio anzeigen lassen.{[en]}With this plugin you can display your portfolio.{[it]}Con questo plugin puoi visualizzare il tuo portfolio. ";// description of the plugin
$navi_name               =   "{[de]}Portfolio{[en]}Portfolio{[it]}Portfolio";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_portfolio";             // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "admin_portfolio,portfolio";   // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/portfolio/"; // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_portfolio_content";    // widget_file (visible as module/box)
$widget_link2            =   "widget_portfolio_content_two";// widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Portfolio Content";           // widget_name (visible as module/box)
$widgetname2             =   "Portfolio Content Two";       // widget_name (visible as module/box)
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
$mnavID                  =   "4";                           // navigation category
$navi_link               =   "portfolio";                   // navigation link file (index.php?site=...)
$catID                   =   "8";                           // dashboard_navigation category
$dashnavi_link           =   "admin_portfolio";             // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_portfolio` (
  `portfolioID` int(11) NOT NULL AUTO_INCREMENT,
  `portfoliocatID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `effects` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `banner` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`portfolioID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_portfolio` (`portfolioID`, `portfoliocatID`, `name`, `text`, `effects`, `url`, `banner`, `sort`) VALUES
(1, 1, 'Bild1', '', '', '', '1.jpg', 0),
(2, 1, 'Bild2', '', '', '', '2.jpg', 0),
(3, 1, 'Bild3', '', '', '', '3.jpg', 0),
(4, 2, 'Bild4', '', '', '', '4.jpg', 0),
(5, 2, 'Bild5', '', '', '', '5.jpg', 0),
(6, 2, 'Bild6', '', '', '', '6.jpg', 0),
(7, 2, 'Bild7', '', '', '', '7.jpg', 0),
(8, 3, 'Bild8', '', '', '', '8.jpg', 0),
(9, 3, 'Bild9', '', '', '', '9.jpg', 0),
(10, 4, 'Bild10', '', '', '', '10.jpg', 0),
(11, 5, 'Bild11', '', '', '', '11.jpg', 0),
(12, 5, 'Bild12', '', '', '', '12.jpg', 0)");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_portfolio_categories` (
  `portfoliocatID` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`portfoliocatID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci"); 

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_portfolio_categories` (`portfoliocatID`, `catname`, `description`, `sort`) VALUES
(1, 'Category1', '', 0),
(2, 'Category2', '', 0),
(3, 'Category3', '', 0),
(4, 'Category4', '', 0),
(5, 'Category5', '', 0)");        

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();	

#######################################################################################################################################

echo "</div></div>";
	
 ?>