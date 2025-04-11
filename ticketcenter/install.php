<?php
global $str,$modulname,$version;
$modulname='ticketcenter';
$version='0.1';
$str='Ticketcenter';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_ticketcenter_categories` (
		`ticketcatID` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`subcatID` int(11) NOT NULL,
		 PRIMARY KEY (`ticketcatID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_ticketcenter` (	
		`ticketID` int(11) NOT NULL AUTO_INCREMENT,
		`masterticketID` int(11) NOT NULL,
		`ticketcatID` int(11) NOT NULL,
		`admin` int(11) NOT NULL,
		`ticketstatus` int(1) NOT NULL,
		`date` int(14) NOT NULL,
		`poster` int(11) NOT NULL,
		`ticketname` varchar(255) NOT NULL,
		`ticketinfo` text NOT NULL,
		`priority` int(1) NOT NULL,
		`notify` int(1) NOT NULL,
		`userarchiv` int(1) NOT NULL,
		`adminarchiv` int(11) NOT NULL,
		PRIMARY KEY (`ticketID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");        

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_ticketcenter_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_ticketcenter_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Ticketcenter', 'ticketcenter', '{[de]}Mit diesem Plugin könnt ihr eure Ticketcenter anzeigen lassen.{[en]}With this plugin you can display your ticketcenter.{[it]}Con questo plugin è possibile mostrare gli Articoli sul sito web.', 'admin_ticketcategorys', 1, 'T-Seven', 'https://webspell-rm.de', 'ticketcenter', '', '0.1', 'includes/plugins/ticketcenter/', 1, 1, 1, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 7, '{[de]}Ticketcenter{[en]}Ticketcenter{[it]}Ticket di Supporto', 'ticketcenter', 'admincenter.php?site=admin_ticketcategorys', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 1, '{[de]}Ticketcenter{[en]}Ticketcenter{[it]}Ticket di Supporto', 'ticketcenter', 'index.php?site=ticketcenter', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>