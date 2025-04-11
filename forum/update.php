<?php
global $str,$modulname,$modulname_2,$version;
$modulname='forum';
$modulname_2='forum_topic';
$version='0.2';
$str='Forum';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_announcements` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_boards` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_forum_boards` (`boardID`, `category`, `name`, `info`, `readgrps`, `writegrps`, `sort`, `topics`, `posts`) VALUES
(1, 1, 'Main Board', 'The general public board', '', '', 1, 0, 0),
(2, 2, 'Main Board', 'The general intern board', '1', '', 2, 0, 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_categories` (
  `catID` int(11) NOT NULL AUTO_INCREMENT,
  `readgrps` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catID`)
) AUTO_INCREMENT=3
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_forum_categories` (`catID`, `readgrps`, `name`, `info`, `sort`) VALUES 
(1, '', 'Public Boards', '', 1),
(2, '1', 'Intern Boards', '', 2)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_notify` (
  `notifyID` int(11) NOT NULL AUTO_INCREMENT,
  `topicID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notifyID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_posts` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_topics` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_poll` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_votes` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_posts_spam` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_topics_spam` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_ranks` (
  `rankID` int(11) NOT NULL AUTO_INCREMENT,
  `rank` varchar(255) NOT NULL default '',
  `pic` varchar(255) NOT NULL default '',
  `postmin` int(11) NOT NULL default '0',
  `postmax` int(11) NOT NULL default '0',
  `special` int(1) NULL DEFAULT '0',
  PRIMARY KEY  (`rankID`)
) AUTO_INCREMENT=16
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_forum_ranks` (`rankID`, `rank`, `pic`, `postmin`, `postmax`, `special`) VALUES 
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


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_groups` (
  `fgrID` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '0',
  PRIMARY KEY  (`fgrID`)
  ) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_forum_groups` ( `fgrID` , `name` ) VALUES ('1', 'Intern board users')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_moderators` (
  `modID` int(11) NOT NULL AUTO_INCREMENT,
  `boardID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`modID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_forum_settings_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `modulname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `themes_modulname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `widgetname` varchar(255) NOT NULL DEFAULT '',
  `widgetdatei` varchar(255) NOT NULL DEFAULT '',
  `activated` int(1) DEFAULT 1,
  `sort` int(11) DEFAULT 1,
PRIMARY KEY (`id`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_forum_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Forum', 'forum', '{[de]}Mit diesem Plugin könnt ihr euch das Forum anzeigen lassen.{[en]}With this plugin you can display the forum.', 'admin_forum', 1, 'T-Seven', 'https://webspell-rm.de', 'forum,forum_topic', '', '0.2', 'includes/plugins/forum/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'forum', 'Forum Sidebar', 'widget_forum_sidebar', 4)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 7, '{[de]}Forum{[en]}Forum{[it]}Forum', 'forum', 'admincenter.php?site=admin_forum', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 3, '{[de]}Forum{[en]}Forum{[it]}Forum', 'forum', 'index.php?site=forum', 1, 1, 'default')");

#######################################################################################################################################

echo "</div></div>";
  
 ?>