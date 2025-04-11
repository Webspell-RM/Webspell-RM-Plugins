<?php
global $str,$modulname,$version;
$modulname='servers';
$version='0.2';
$str='Servers';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_servers` (
  `serverID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `port` varchar(255) NOT NULL,
  `qport` varchar(255) NOT NULL,
  `tsadress` varchar(255) NOT NULL,
  `provider` int(1) NOT NULL DEFAULT 0,
  `game` varchar(50) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` int(1) NOT NULL,
  `ts_displayed` int(1) NOT NULL,
  `ts3_displayed` int(1) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`serverID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_servers_settings` (
  `serverssetID` int(11) NOT NULL AUTO_INCREMENT,
  `servers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serverssetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_servers_settings` (`serverssetID`, `servers`) VALUES (1, 5);");
 
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_servers_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_servers_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Servers', 'servers', '{[de]}Mit diesem Plugin könnt ihr eure Server anzeigen lassen.{[en]}With this plugin you can display your servers.{[it]}Con questo plugin puoi visualizzare i tuoi server.', 'admin_servers', 1, 'T-Seven', 'https://webspell-rm.de', 'servers', '', '0.2', 'includes/plugins/servers/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'servers', 'Servers Sidebar', 'widget_servers_sidebar', 4),
('', 'servers', 'Gametracker TS Sidebar', 'widget_gametracker_ts_sidebar', 4),
('', 'servers', 'Gametracker Server Sidebar', 'widget_gametracker_server_sidebar', 4),
('', 'servers', 'TS3-Viewer Content', 'widget_ts3viewer_content', 3),
('', 'servers', 'TS-Viewer Sidebar', 'widget_tsviewer_sidebar', 4)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 10, '{[de]}Server{[en]}Servers{[it]}Server', 'servers', 'admincenter.php?site=admin_servers', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 3, '{[de]}Server{[en]}Servers{[it]}Server', 'servers', 'index.php?site=servers', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>