<?php
global $str,$modulname,$version;
$modulname='clan_rules';
$version='0.1';
$str='Clan Rules';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_clan_rules` (
  `clan_rulesID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `poster` int(11) NOT NULL,
  `date` int(14) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `displayed` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`clan_rulesID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_clan_rules_settings` (
  `clan_rulessetID` int(11) NOT NULL AUTO_INCREMENT,
  `clan_rules` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`clan_rulessetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_clan_rules_settings` (`clan_rulessetID`, `clan_rules`) VALUES (1, 5)"); 

#######################################################################################################################################

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_clan_rules_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_clan_rules_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Clan Rules', 'clan_rules', '{[de]}Mit diesem Plugin könnt ihr eure Clan Regeln anzeigen lassen.{[en]}With this plugin it is possible to show the Clan Rules on the website.{[it]}Con questo plugin è possibile mostrare le Regole del Clan sul sito web', 'admin_clan_rules', 1, 'T-Seven', 'https://webspell-rm.de', 'clan_rules', '', '0.1', 'includes/plugins/clan_rules/', 1, 1, 1, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Clan Regeln{[en]}Clan Rules{[it]}Regole del Clan', 'clan_rules', 'admincenter.php?site=admin_clan_rules', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Clan Regeln{[en]}Clan Rules{[it]}Regole del Clan', 'clan_rules', 'index.php?site=clan_rules', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>