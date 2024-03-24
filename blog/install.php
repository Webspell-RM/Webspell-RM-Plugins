<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Blog";               			    // name of the plugin
$modulname               =   "blog";               			    // name to uninstall
$info				             =   "{[de]}Mit diesem Plugin könnt ihr eure Blogs anzeigen lassen.{[en]}With this plugin you can display your blogs. {[it]}Con questo plugin è possibile visualizzare i Blog";	  // description of the plugin
$navi_name               =   "{[de]}Blog{[en]}Blog{[it]}Blog";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_blog";              		// administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "blog,blog_comments";     		  // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/blog/";  	  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_blog_sidebar";			    // widget_file (visible as module/box)
$widget_link2            =   "widget_blog_content";     		// widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Blog Sidebar";       			    // widget_name (visible as module/box)
$widgetname2             =   "Blog Content";            		// widget_name (visible as module/box)
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
$plugin_settings  			 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_module  				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget  				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1  							 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2 				 				 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget3  							 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "3";                           // navigation category
$navi_link               =   "blog";                    		// navigation link file (index.php?site=...)
$catID                   =   "7";                         	// dashboard_navigation category
$dashnavi_link           =   "admin_blog";       						// dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_blog` ( 
	  	`blogID` INT(11) NOT NULL AUTO_INCREMENT, 
	  	`date` VARCHAR(255) NOT NULL, 
	  	`userID` VARCHAR(255) NOT NULL, 
	  	`msg` TEXT NOT NULL, 
	  	`comments` int(1) NOT NULL DEFAULT 0,
	  	`headline` VARCHAR(255) NOT NULL, 
	  	`banner` varchar(255) NOT NULL,
	  	`visits` INT(10) NOT NULL, 
	  	PRIMARY KEY (blogID)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_blog_comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) NOT NULL DEFAULT '0',
  `type` char(2) NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `date` int(14) NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`commentID`),
  KEY `parentID` (`parentID`),
  KEY `type` (`type`),
  KEY `date` (`date`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_blog_settings` (
  `blogsetID` int(11) NOT NULL AUTO_INCREMENT,
  `blog` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blogsetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_blog_settings` (`blogsetID`, `blog`) VALUES (1, 6)");      

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>