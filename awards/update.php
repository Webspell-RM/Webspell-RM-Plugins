<?php 
global $str,$modulname,$version;
$modulname='awards';
$version='0.1';
$str='Awards';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_awards` (
  `awardID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `squadID` int(11) NOT NULL DEFAULT '0',
  `award` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(255) NOT NULL,
  `rang` int(11) NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `liga` varchar(255) NOT NULL DEFAULT '',
  `steam` varchar(255) NOT NULL DEFAULT '',
  `twitch` varchar(255) NOT NULL DEFAULT '',
  `youtube` varchar(255) NOT NULL DEFAULT '',
  `instagram` varchar(255) NOT NULL DEFAULT '',
  `facebook` varchar(255) NOT NULL DEFAULT '',
  `twitter` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`awardID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_awards_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_awards_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Awards', 'awards', '{[de]}Mit diesem Plugin könnt ihr eure Awards mit Slider und Page anzeigen lassen.{[en]}With this plugin you can display your awards with slider and page. {[it]}Con questo plugin puoi visualizzare i tuoi Premi con il dispositivo di scorrimento e la pagina.', 'admin_awards', 1, 'T-Seven', 'https://webspell-rm.de', 'awards', '', '0.1', 'includes/plugins/awards/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'awards', 'Awards Content', 'widget_awards_content', 3)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Awards{[en]}Awards{[it]}Premi', 'awards', 'admincenter.php?site=admin_awards', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Awards{[en]}Awards{[it]}Premi', 'awards', 'index.php?site=awards', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>