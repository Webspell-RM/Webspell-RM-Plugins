<?php
global $str,$modulname,$version;
$modulname='projectlist';
$version='0.2';
$str='Project List';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_projectlist` (
  `projectlistID` int(11) NOT NULL AUTO_INCREMENT,
  `projectlistcatID` int(11) NOT NULL DEFAULT '0',
  `question` varchar(255) NOT NULL DEFAULT '',
  `prozent` varchar(255) NOT NULL DEFAULT '',
  `date_time` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  `answer` longtext NOT NULL,
  `poster` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '',
  `displayed` int(1) NOT NULL,
  `rating` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`projectlistID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_projectlist_categories` (
  `projectlistcatID` int(11) NOT NULL AUTO_INCREMENT,
  `projectlistcatname` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`projectlistcatID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_projectlist_settings` (
  `projectlistsetID` int(11) NOT NULL AUTO_INCREMENT,
  `projectlist` int(11) NOT NULL,
  `projectlistchars` int(11) NOT NULL,
  PRIMARY KEY (`projectlistsetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_projectlist_settings` (`projectlistsetID`, `projectlist`, `projectlistchars`) VALUES (1, 4, '70')");

#######################################################################################################################################

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_projectlist_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_projectlist_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Project List', 'projectlist', '{[de]}Mit diesem Plugin könnt ihr euer Projektliste zu Webseiten anzeigen lassen.{[en]}With this plugin you can show your ProjectList to websites.{[it]}Con questo plugin puoi mostrare la tua Lista Progetti sul sito web.', 'admin_projectlist', 1, 'T-Seven', 'https://webspell-rm.de', 'projectlist', '', '0.2', 'includes/plugins/projectlist/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'projectlist', 'Project List Sidebar', 'widget_projectlist_sidebar', 4)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 8, '{[de]}Projekt Liste{[en]}Project List{[it]}Elenco progetti', 'projectlist', 'admincenter.php?site=admin_projectlist', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 6, '{[de]}Projekt Liste{[en]}Project List{[it]}Elenco progetti', 'projectlist', 'index.php?site=projectlist', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>