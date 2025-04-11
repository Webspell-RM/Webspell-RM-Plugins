<?php
global $str,$modulname,$version;
$modulname='clanwars';
$version='0.1';
$str='Clanwars';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_clanwars` (
  `cwID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `time` int(14) NOT NULL DEFAULT '0',
  `squad` int(11) NOT NULL DEFAULT '0',
  `game` varchar(255) NOT NULL DEFAULT '',
  `league` varchar(255) NOT NULL DEFAULT '',
  `leaguehp` varchar(255) NOT NULL DEFAULT '',
  `opponent` varchar(255) NOT NULL DEFAULT '',
  `opptag` varchar(255) NOT NULL DEFAULT '',
  `oppcountry` char(2) NOT NULL DEFAULT '',
  `opphp` varchar(255) NOT NULL DEFAULT '',
  `opplogo` varchar(255) NOT NULL,
  `maps` varchar(255) NOT NULL DEFAULT '',
  `hometeam` varchar(255) NOT NULL DEFAULT '',
  `oppteam` varchar(255) NOT NULL DEFAULT '',
  `server` varchar(255) NOT NULL DEFAULT '',
  `hltv` varchar(255) NOT NULL,
  `homescore` text COLLATE utf8_unicode_ci,
  `oppscore` text COLLATE utf8_unicode_ci,
  `screens` text NOT NULL,
  `report` text NOT NULL,
  `comments` int(1) NOT NULL DEFAULT '0',
  `linkpage` varchar(255) NOT NULL DEFAULT '',
  `displayed` int(1) NOT NULL DEFAULT '1',
  `ani_title` varchar(255) NOT NULL,
  PRIMARY KEY (`cwID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_clanwars_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_clanwars_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Clanwars', 'clanwars', '{[de]}Mit diesem Plugin könnt ihr euer Clanwars zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your clanwars to websites.{[it]}Con questo plugin è possibile mostrare le Guerre del Clan sul sit web.', 'admin_clanwars', 1, 'T-Seven', 'https://webspell-rm.de', 'clanwars,admin_clanwars,clanwars_details,clanwar_result', '', '0.1', 'includes/plugins/clanwars/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'clanwars', 'Clanwars Sidebar', 'widget_clanwars_sidebar', 4),
('', 'clanwars', 'Clanwars Content', 'widget_clanwars_content', 3)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Clanwars{[en]}Clanwars{[pl]}Clanwars{[it]}Guerre del Clan', 'clanwars', 'admincenter.php?site=admin_clanwars', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Clanwars{[en]}Clanwars{[pl]}Clanwars{[it]}Guerre del Clan', 'clanwars', 'index.php?site=clanwars', 1, 1, 'default')");

$transaction .= add_insert_navigation_2("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Clanwars Statistiken{[en]}Clanwars Statistics{[pl]}Clanwars{[it]}Guerre del Clan Statistica', 'clanwars', 'index.php?site=clanwars&action=clanwar_result', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>