<?php
global $str,$modulname,$version;
$modulname='streams';
$version='0.1';
$str='Streams';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_streams` (
  `streamID` int(11) NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `displayed` varchar(1) NOT NULL DEFAULT '0',
  `widget_displayed` varchar(1) NOT NULL DEFAULT '0',
  `media_widget_displayed` varchar(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL,
  PRIMARY KEY  (`streamID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_streams_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_streams_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Streams', 'streams', '{[de]}Mit diesem Plugin können die Streams von Twitch auf der Webseite angezeigt werden.{[en]}With this plugin, Twitch streams can be displayed on the website.{[it]}Con questo plugin è possibile visualizzare gli stream di Twitch sul sito web.', 'admin_streams', 1, 'T-Seven', 'https://webspell-rm.de', 'streams', '', '0.1', 'includes/plugins/streams/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'streams', 'Streams Content', 'widget_streams_content', '3'),
('', 'streams', 'Streams Sidebar', 'widget_stream_sidebar', '4')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 11, '{[de]}Streams{[en]}Streams{[it]}Stream', 'streams', 'admincenter.php?site=admin_streams', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 4, '{[de]}Streams{[en]}Streams{[it]}Stream', 'streams', 'index.php?site=streams', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>