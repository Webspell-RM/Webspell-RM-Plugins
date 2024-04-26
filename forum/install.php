<?php
global $userID,$_database,$add_database_install,$add_database_insert,$add_plugin_manager_two;
global $str,$str_two,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname,$str_two;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "Forum";                       // name of the plugin
$modulname               =   "forum";                       // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euch das Forum anzeigen lassen.{[en]}With this plugin you can display the forum.";// description of the plugin
$navi_name               =   "{[de]}Forum{[en]}Forum{[it]}Forum";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_forum";                 // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "forum,forum_topic";                       // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/forum/";     // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_forum_sidebar";        // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Forum Sidebar";               // widget_name (visible as module/box)
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
$navi_link               =   "forum";                       // navigation link file (index.php?site=...)
$catID                   =   "7";                           // dashboard_navigation category
$dashnavi_link           =   "admin_forum";                 // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################

##### Install für Plugin und Module ###################################################################################################
$str_two                     =   "Forum Topic";             // name of the plugin
$modulname_two               =   "forum_topic";             // name to uninstall
$info_two                    =   "Bestandteil vom Forum!!!";// description of the plugin
$navi_name_two               =   "";                        // name of the Webside Navigation / Dashboard Navigation
$admin_file_two              =   "";                        // administration file
$activate_two                =   "1";                       // plugin activate 1 yes | 0 no
$author_two                  =   "";                        // author
$website_two                 =   "";                        // authors website
$index_link_two              =   "";                        // index file (without extension, also no .php)
$hiddenfiles_two             =   "";                        // hiddenfiles (background working, no display anywhere)
$version_two                 =   "";                        // current version, visit authors website for updates, fixes, ..
$path_two                    =   "";                        // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link_two1            =   "";                        // widget_file (visible as module/box)
$widget_link_two2            =   "";                        // widget_file (visible as module/box)
$widget_link_two3            =   "";                        // widget_file (visible as module/box)
$widgetname_two1             =   "";                        // widget_name (visible as module/box)
$widgetname_two2             =   "";                        // widget_name (visible as module/box)
$widgetname_two3             =   "";                        // widget_name (visible as module/box)
##### Modul Setting activate yes/no ###################################################################################################
$head_activated_two          =   "0";                       //Modul activate 1 yes | 0 no 
$content_head_activated_two  =   "0";                       //Modul activate 1 yes | 0 no 
$content_foot_activated_two  =   "0";                       //Modul activate 1 yes | 0 no 
$head_section_activated_two  =   "0";                       //Modul activate 1 yes | 0 no 
$foot_section_activated_two  =   "0";                       //Modul activate 1 yes | 0 no 
$modul_deactivated_two       =   "0";                       //Modul activate 1 yes | 0 no
$modul_display_two           =   "0";                       //Modul activate 1 yes | 0 no
$full_activated_two          =   "0";                       //Modul activate 1 yes | 0 no
$plugin_settings_two         =   "0";                       //Modulsetting activate 1 yes | 0 no 
$plugin_module_two           =   "0";                       //Modulsetting activate 1 yes | 0 no 
$plugin_widget_two           =   "0";                       //Modulsetting activate 1 yes | 0 no 
$widget1_two                 =   "0";                       //Modulsetting activate 1 yes | 0 no 
$widget2_two                 =   "0";                       //Modulsetting activate 1 yes | 0 no 
$widget3_two                 =   "0";                       //Modulsetting activate 1 yes | 0 no 
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_announcements` (
  `announceID` int(11) NOT NULL AUTO_INCREMENT,
  `boardID` int(11) NOT NULL DEFAULT '0',
  `readgrps` text COLLATE utf8_unicode_ci NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  `topic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `announcement` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`announceID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_boards` (
  `boardID` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `readgrps` text COLLATE utf8_unicode_ci NOT NULL,
  `writegrps` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(2) NOT NULL DEFAULT '0',
  `topics` int(11) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`boardID`)
) AUTO_INCREMENT=3
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_forum_boards` (`boardID`, `category`, `name`, `info`, `readgrps`, `writegrps`, `sort`, `topics`, `posts`) VALUES
(1, 1, 'Main Board', 'The general public board', '', '', 1, 0, 0),
(2, 2, 'Main Board', 'The general intern board', '1', '', 2, 0, 0)");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_categories` (
  `catID` int(11) NOT NULL AUTO_INCREMENT,
  `readgrps` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catID`)
) AUTO_INCREMENT=3
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_forum_categories` (`catID`, `readgrps`, `name`, `info`, `sort`) VALUES 
(1, '', 'Public Boards', '', 1),
(2, '1', 'Intern Boards', '', 2)");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_notify` (
  `notifyID` int(11) NOT NULL AUTO_INCREMENT,
  `topicID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notifyID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_posts` (
  `postID` int(11) NOT NULL AUTO_INCREMENT,
  `boardID` int(11) NOT NULL DEFAULT '0',
  `topicID` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  `poster` int(11) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `thank` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`postID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_topics` (
  `topicID` int(11) NOT NULL AUTO_INCREMENT,
  `boardID` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `readgrps` text COLLATE utf8_unicode_ci NOT NULL,
  `writegrps` text COLLATE utf8_unicode_ci NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  `topic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lastdate` int(14) NOT NULL DEFAULT '0',
  `lastposter` int(11) NOT NULL DEFAULT '0',
  `lastpostID` int(11) NOT NULL DEFAULT '0',
  `replys` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `closed` int(1) NOT NULL DEFAULT '0',
  `moveID` int(11) NOT NULL DEFAULT '0',
  `sticky` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`topicID`),
  KEY `lastdate` (`lastdate`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_poll` (
  `topicID` int(11) NOT NULL AUTO_INCREMENT,
  `enddate` bigint(20) NOT NULL,
  `poll` int(1) NOT NULL,
  `title` char(80) CHARACTER SET latin1 NOT NULL,
  `value1` char(50) CHARACTER SET latin1 NOT NULL,
  `value2` char(50) CHARACTER SET latin1 NOT NULL,
  `value3` char(50) CHARACTER SET latin1 NOT NULL,
  `value4` char(50) CHARACTER SET latin1 NOT NULL,
  `value5` char(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`topicID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_votes` (
  `voteID` int(11) NOT NULL AUTO_INCREMENT,
  `topicID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  `value1` int(1) NOT NULL,
  `value2` int(1) NOT NULL,
  `value3` int(1) NOT NULL,
  `value4` int(1) NOT NULL,
  `value5` int(1) NOT NULL,
  PRIMARY KEY (`voteID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

##################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_posts_spam` (
  `postID` int(11) NOT NULL AUTO_INCREMENT,
  `boardID` int(11) NOT NULL DEFAULT '0',
  `topicID` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  `poster` int(11) NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`postID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_topics_spam` (
  `topicID` int(11) NOT NULL AUTO_INCREMENT,
  `boardID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `date` int(14) NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sticky` int(1) NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`topicID`),
  KEY `date` (`date`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_ranks` (
  `rankID` int(11) NOT NULL AUTO_INCREMENT,
  `rank` varchar(255) NOT NULL default '',
  `pic` varchar(255) NOT NULL default '',
  `postmin` int(11) NOT NULL default '0',
  `postmax` int(11) NOT NULL default '0',
  `special` int(1) NULL DEFAULT '0',
  PRIMARY KEY  (`rankID`)
) AUTO_INCREMENT=16
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_forum_ranks` (`rankID`, `rank`, `pic`, `postmin`, `postmax`, `special`) VALUES 
  (1, 'Rank 0', 'rank0.png', 0, 9, 0),
  (2, 'Rank 1', 'rank1.png', 10, 29, 0),    
  (3, 'Rank 2', 'rank2.png', 30, 49, 0),
  (4, 'Rank 3', 'rank3.png', 50, 69, 0),
  (5, 'Rank 4', 'rank4.png', 70, 89, 0),
  (6, 'Rank 5', 'rank5.png', 90, 119, 0),
  (7, 'Rank 6', 'rank6.png', 100, 299, 0),
  (8, 'Rank 7', 'rank7.png', 300, 599, 0),
  (9, 'Rank 8', 'rank8.png', 600, 899, 0),
  (10, 'Rank 9', 'rank9.png', 900, 1299, 0),
  (11, 'Rank 10', 'rank10.png', 1300, 1599, 0),
  (12, 'Rank 11', 'rank11.png', 1600, 1999, 0),
  (13, 'Rank 12', 'rank12.png', 2000, 2147483647, 0),
  (14, 'Administrator', 'admin.png', 0, 0, 1),
  (15, 'Moderator', 'moderator.png', 0, 0, 1)");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_groups` (
  `fgrID` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '0',
  PRIMARY KEY  (`fgrID`)
  ) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_forum_groups` ( `fgrID` , `name` ) VALUES ('1', 'Intern board users')");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_moderators` (
  `modID` int(11) NOT NULL AUTO_INCREMENT,
  `boardID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`modID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_user_forum_groups` (
  `usfgID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `1` int(1) NOT NULL,
  PRIMARY KEY (`usfgID`)
) AUTO_INCREMENT=2
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_forum_user_forum_groups` (`usfgID`, `userID`, `1`) VALUES
(1, 1, 1)");


add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_user_forum_groups` (
  `usfgID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `1` int(1) NOT NULL,
  PRIMARY KEY (`usfgID`)
) AUTO_INCREMENT=2
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_user_forum_groups` (`usfgID`, `userID`, `1`) VALUES
(1, 1, 1)");


################   

#Prüft ob die Kategorie vorhanden ist
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_module WHERE modulname='$modulname_two'"));
if (@$dx[ 'modulname' ] != $modulname_two) {
add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."settings_module` (`pluginID`, `name`, `modulname`, `themes_modulname`, `activate`, `sidebar`, `head_activated`, `content_head_activated`, `content_foot_activated`, `head_section_activated`, `foot_section_activated`, `modul_display`, `full_activated`, `plugin_settings`, `plugin_module`, `plugin_widget`, `widget1`, `widget2`, `widget3`) VALUES ('', '$str_two', '$modulname_two', 'default', '1', 'activated', '$head_activated_two', '$content_head_activated_two', '$content_foot_activated_two', '$head_section_activated_two', '$foot_section_activated_two', '$modul_display_two', '$full_activated_two', '$plugin_settings_two', '$plugin_module_two', '$plugin_widget_two', '$widget1_two', '$widget2_two', '$widget3_two')");
} else {
} 

#Prüft ob die Kategorie vorhanden ist
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='$modulname_two'"));
if (@$dx[ 'modulname' ] != $modulname_two) {
  
add_plugin_manager_two($add_plugin_manager_two = "INSERT IGNORE INTO `".PREFIX."settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `widgetname1`, `widgetname2`, `widgetname3`, `widget_link1`, `widget_link2`, `widget_link3`, `modul_display`) VALUES ('', '$str_two', '$modulname_two', '$info_two', '$admin_file_two', '$activate_two', '$author_two', '$website_two', '$index_link_two', '$hiddenfiles_two', '$version_two', '$path_two', '$widgetname_two1', '$widgetname_two2', '$widgetname_two3', '$widget_link_two1', '$widget_link_two2', '$widget_link_two3', '$modul_display_two')");
} else {
}
get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
  
 ?>