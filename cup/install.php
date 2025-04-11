<?php
global $str,$modulname,$version;
$modulname='cup';
$version='0.1';
$str='Cup';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_cup_teams` (
  `cupID` int(11) NOT NULL AUTO_INCREMENT,
  `teamid` int(11) NOT NULL,
  `clantag` varchar(10) NOT NULL,
  `name` text NOT NULL,
  `gruppe` int(3) NOT NULL,
  `anordnung` int(2) NOT NULL,
  `hp` varchar(50) NOT NULL,
  `viertel` int(11) NOT NULL,
  `halb` int(11) NOT NULL,
  `finale` int(11) NOT NULL,
  `p1` int(11) NOT NULL,
  `p2` int(11) NOT NULL,
  `p3` int(11) NOT NULL,
  `eg` int(2) NOT NULL,
  `ev` int(2) NOT NULL,
  `eh` int(2) NOT NULL,
  `ef` int(2) NOT NULL,
  `ep3` int(2) NOT NULL,
  `color` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '0.png',
  PRIMARY KEY (`cupID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_cup_teams` (`cupID`, `teamid`, `clantag`, `name`, `gruppe`, `anordnung`, `hp`, `viertel`, `halb`, `finale`, `p1`, `p2`, `p3`, `eg`, `ev`, `eh`, `ef`, `ep3`, `color`, `banner`) VALUES
(1, 0, 'RM', 'Webspell RM', 1, 1, 'https://www.Webspell-RM.de', 1, 0, 0, 0, 0, 0, 10, 2, 0, 0, 9, '#a2b9bc', '1.png'),
(2, 0, 'df', 'Die Front', 1, 2, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 9, '#b2ad7f', '2.png'),
(3, 0, '-HT-', 'Harrington Team', 1, 3, 'http://unserehp.de', 1, 1, 1, 0, 0, 0, 8, 3, 10, 0, 0, '#878f99', '3.png'),
(4, 0, '=WT=', 'wilbury team 0', 1, 4, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, '#6b5b95', '4.png'),
(5, 0, 'KB', 'Kaos Bande', 2, 5, 'http://unserehp.de', 1, 1, 0, 0, 0, 0, 1, 3, 4, 0, 9, '#d6cbd3', '5.png'),
(6, 0, '#LT#', 'LazyTeam', 2, 6, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, '#eca1a6', '6.png'),
(7, 0, 'DH', 'dahirinis', 2, 7, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, '#bdcebe', '7.png'),
(8, 0, 'RC', 'Roxbury Clan', 2, 8, 'http://unserehp.de', 1, 0, 0, 0, 0, 0, 2, 1, 0, 0, 9, '#ada397', '8.png'),
(9, 0, 'TA', 'Team Austria', 3, 9, 'http://unserehp.de', 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 10, '#b9936c', '9.png'),
(10, 0, 'RF', 'Roflmao', 3, 10, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, '#dac292', '10.png'),
(11, 0, 'FC', 'flashchecker', 3, 11, 'http://unserehp.de', 1, 1, 1, 1, 0, 0, 1, 5, 13, 1, 22, '#e6e2d3', '11.png'),
(12, 0, 'DW', 'Dawutz', 3, 12, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, '#c4b7a6', '12.png'),
(13, 0, 'TW', 'Team Wax', 4, 13, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, '#92a8d1', '13.png'),
(14, 0, '=???=', 'Pretenders', 4, 14, 'http://unserehp.de', 1, 0, 0, 0, 0, 0, 2, 6, 0, 0, 10, '#034f84', '14.png'),
(15, 0, '=HH=', 'HullaHups', 4, 15, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, '#f7cac9', '15.png'),
(16, 0, 'BA', 'Black Angel Team', 4, 16, 'https://blackangelteam.net', 1, 1, 0, 0, 0, 1, 1, 8, 3, 0, 10, '#c67c16', '16.png')");

  
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_cup_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gruppe` varchar(11) NOT NULL,
  `register` varchar(11) NOT NULL,
  `turnier` varchar(11) NOT NULL,
  `preis1` varchar(50) NOT NULL,
  `preis2` varchar(50) NOT NULL,
  `preis3` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=2
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_cup_config` (`id`, `gruppe`, `register`, `turnier`, `preis1`, `preis2`, `preis3`) VALUES
(1, 'ja', 'ja', 'ja', 'Preis1', 'Preis2', 'Preis3')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_cup_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_cup_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Cup', 'cup', '{[de]}Mit diesem Plugin könnt ihr eure Cup / Tournament anzeigen lassen.{[en]}With this plugin you can display your cup / tournament.{[it]}Con questo plugin puoi visualizzare la tua coppa/torneo.', 'admin_cup', 1, 'T-Seven', 'https://webspell-rm.de', 'cup', '', '0.1', 'includes/plugins/cup/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'cup', 'Cup next Matches Content', 'widget_cup_nextmatches_content', '3')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Turnier{[en]}Tournament{[it]}Coppa/Torneo', 'cup', 'admincenter.php?site=admin_cup', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Turnier{[en]}Tournament{[it]}Coppa/Torneo', 'cup', 'index.php?site=cup', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>