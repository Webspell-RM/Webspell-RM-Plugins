<?php
global $str,$modulname,$modulname_2,$version;
$modulname='news_manager';
$modulname_2='news_archive';
$version='0.1';
$str='News Manager';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_manager` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_manager_rubrics` (
  `rubricID` int(11) NOT NULL AUTO_INCREMENT,
  `rubric` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `displayed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rubricID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_manager_settings` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_news_manager_settings` (`newssetID`, `admin_news`, `news`, `newsarchiv`, `headlines`, `newschars`, `headlineschars`, `topnewschars`, `feedback`, `switchen`) VALUES (1, 5, 3, 10, 4, 700, 200, 200, 5, 12)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_manager_comments` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_manager_comments_recomment` (
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

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_news_manager_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_news_manager_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'News Manager', 'news_manager', '{[de]}Mit diesem Plugin könnt ihr euch eure News anzeigen lassen.{[en]}With this plugin you can display your news.{[it]}Con questo plugin puoi visualizzare le tue notizie.', 'admin_news_manager', 1, 'T-Seven', 'https://webspell-rm.de', 'news_manager,news_comments,news_contents', '', '0.1', 'includes/plugins/news_manager/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'news_manager', 'News Content', 'widget_news_content', 3),
('', 'news_manager', 'News Headlines', 'widget_news_headlines', 3),
('', 'news_manager', 'News Headlines 2', 'widget_news_headlines_2', 3),
('', 'news_manager', 'Breaking News Content', 'widget_breaking_news_content', 3)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 7, '{[de]}News{[en]}News{[it]}Notizie', 'news_manager', 'admincenter.php?site=admin_news_manager', 'page', 1)");

#####################################################
$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 1, '{[de]}News{[en]}News{[it]}Notizie', 'news_manager', 'index.php?site=news_manager', 1, 1, 'default')");

$transaction .= add_insert_navigation_2("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 1, '{[de]}News Archive{[en]}News Archive{[it]}Archivio Notizie', 'news_archive', 'index.php?site=news_manager&action=news_archive', 1, 1, 'default')");

#######################################################################################################################################

echo "</div></div>";
  
 ?>