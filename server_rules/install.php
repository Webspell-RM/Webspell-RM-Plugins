<?php
global $str,$modulname,$version;
$modulname='server_rules';
$version='0.1';
$str='Server Rules';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_server_rules` (
  `server_rulesID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `poster` int(11) NOT NULL,
  `date` int(14) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `displayed` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`server_rulesID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_server_rules_settings` (
  `server_rulessetID` int(11) NOT NULL AUTO_INCREMENT,
  `server_rules` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`server_rulessetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_server_rules_settings` (`server_rulessetID`, `server_rules`) VALUES (1, 5)");  

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_server_rules_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_server_rules_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Server Rules', 'server_rules', '{[de]}Mit diesem Plugin könnt ihr eure Server Rules anzeigen lassen.{[en]}With this plugin you can display your server rules.{[it]}Con questo plugin è possibile mostrare le Regole del Server sul sito web.', 'admin_server_rules', 1, 'T-Seven', 'https://webspell-rm.de', 'server_rules', '', '0.1', 'includes/plugins/server_rules/', 1, 1, 1, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 10, '{[de]}Server Regeln{[en]}Server Rules{[it]}Regole Server', 'server_rules', 'admincenter.php?site=admin_server_rules', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 3, '{[de]}Server Regeln{[en]}Server Rules{[it]}Regole Server', 'server_rules', 'index.php?site=server_rules', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>