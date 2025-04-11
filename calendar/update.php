<?php
global $str,$modulname,$version;
$modulname='calendar';
$version='0.3';
$str='Calendar';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_calendar` (
  `upID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `squad` int(11) NOT NULL DEFAULT '0',
  `opponent` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `opptag` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `opphp` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `maps` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gametype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `matchtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `spielanzahl` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `server` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `league` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `leaguehp` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `warinfo` text COLLATE utf8_unicode_ci NOT NULL,
  `short` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `enddate` int(14) NOT NULL DEFAULT '0',
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `locationhp` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dateinfo` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`upID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_calendar_announce` (
  `annID` int(11) NOT NULL AUTO_INCREMENT,
  `upID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`annID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_calendar_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_calendar_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Calendar', 'calendar', '{[de]}Mit diesem Plugin könnt ihr euer Kalender zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your calendar to websites.{[it]}Con questo plugin è possibile mostrare il Calendario sul sito web.', 'admin_calendar', 1, 'T-Seven', 'https://webspell-rm.de', 'calendar', '', '0.3', 'includes/plugins/calendar/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'calendar', 'Calendar Sidebar', 'widget_calendar_sidebar', 4),
('', 'calendar', 'Upcoming Sidebar', 'widget_upcoming_sidebar', 4)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Kalender{[en]}Calendar{[it]}Calendario', 'calendar', 'admincenter.php?site=admin_calendar', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 1, '{[de]}Kalender{[en]}Calendar{[it]}Calendario', 'calendar', 'index.php?site=calendar', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>