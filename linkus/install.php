<?php 
global $str,$modulname,$version;
$modulname='linkus';
$version='0.1';
$str='Link us';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_linkus` (
  `bannerID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `banner_pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL,
  `displayed` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bannerID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_linkus_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_linkus_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Link Us', 'linkus', '{[de]}Mit diesem Plugin könnt ihr euer Gästebuch anzeigen lassen.{[en]}With this plugin you can display your guest book.{[it]}Con questo plugin puoi visualizzare il tuo libro degli ospiti.', 'admin_linkus', 1, 'T-Seven', 'https://webspell-rm.de', 'linkus', '', '0.1', 'includes/plugins/linkus/', 1, 1, 1, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 12, '{[de]}Link Us{[en]}Link Us{[it]}I Nostri Link', 'linkus', 'admincenter.php?site=admin_linkus', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 4, '{[de]}Link Us{[en]}Link Us{[it]}I Nostri Link', 'linkus', 'index.php?site=linkus', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>