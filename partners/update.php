<?php
global $str,$modulname,$version;
$modulname='partners';
$version='0.1';
$str='Partner';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_partners` (
  `partnerID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `date` int(14) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `displayed` varchar(255) NOT NULL DEFAULT '1',
  `hits` int(11) DEFAULT '0',
  PRIMARY KEY (`partnerID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
  
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_partners_settings` (
  `partnerssetID` int(11) NOT NULL AUTO_INCREMENT,
  `partners` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`partnerssetID`)
) AUTO_INCREMENT=2
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_partners_settings` (`partnerssetID`, `partners`) VALUES (1, 5)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_partners_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_partners_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Partner', 'partners', '{[de]}Mit diesem Plugin könnt ihr eure Partner mit Slider und Page anzeigen lassen.{[en]}With this plugin you can display your partners with slider and page.{[it]}Con questo plugin puoi visualizzare i tuoi partner con slider e pagina.', 'admin_partners', 1, 'T-Seven', 'https://webspell-rm.de', 'partners', '', '0.1', 'includes/plugins/partners/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'partners', 'Partners Content', 'widget_partners_content', 3),
('', 'partners', 'Partners Sidebar', 'widget_partners_sidebar', 4)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 12, '{[de]}Partner{[en]}Partners{[it]}Partner', 'partners', 'admincenter.php?site=admin_partners', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 4, '{[de]}Partner{[en]}Partners{[it]}Partner', 'partners', 'index.php?site=partners', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>