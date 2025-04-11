<?php 
global $str,$modulname,$version;
$modulname='polls';
$version='0.1';
$str='Polls';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_polls` (
  `pollID` int(10) NOT NULL AUTO_INCREMENT,
  `aktiv` int(1) NOT NULL DEFAULT '0',
  `laufzeit` bigint(20) NOT NULL DEFAULT '0',
  `titel` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `o1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o3` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o4` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o5` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o6` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o7` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o8` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o9` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `o10` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comments` int(1) NOT NULL DEFAULT 0,
  `hosts` text COLLATE utf8_unicode_ci NOT NULL,
  `intern` int(1) NOT NULL DEFAULT '0',
  `userIDs` text COLLATE utf8_unicode_ci NOT NULL,
  `published` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pollID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
  
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_polls_comments` (
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


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_polls_votes` (
  `pollID` int(10) NOT NULL DEFAULT '0',
  `o1` int(11) NOT NULL DEFAULT '0',
  `o2` int(11) NOT NULL DEFAULT '0',
  `o3` int(11) NOT NULL DEFAULT '0',
  `o4` int(11) NOT NULL DEFAULT '0',
  `o5` int(11) NOT NULL DEFAULT '0',
  `o6` int(11) NOT NULL DEFAULT '0',
  `o7` int(11) NOT NULL DEFAULT '0',
  `o8` int(11) NOT NULL DEFAULT '0',
  `o9` int(11) NOT NULL DEFAULT '0',
  `o10` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pollID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_polls_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_polls_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Polls', 'polls', '{[de]}Mit diesem Plugin könnt ihr eure Umfragen anzeigen lassen.{[en]}With this plugin you can have your surveys displayed.{[it]}Con questo plugin puoi visualizzare i tuoi sondaggi.', 'admin_polls', 1, 'T-Seven', 'https://webspell-rm.de', 'polls,polls_comments', '', '0.1', 'includes/plugins/polls/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'polls', 'Polls Sidebar', 'widget_polls_sidebar', 4)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 7, '{[de]}Umfrage{[en]}Polls{[it]}Sondaggi', 'polls', 'admincenter.php?site=admin_polls', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Umfrage{[en]}Polls{[it]}Sondaggi', 'polls', 'index.php?site=polls', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>