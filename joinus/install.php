<?php
global $str,$modulname,$version;
$modulname='joinus';
$version='0.1';
$str='Join us';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_joinus` (
  `joinusID` int(11) NOT NULL AUTO_INCREMENT,
  `show` int(11) NOT NULL DEFAULT '0',
  `terms_of_use` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`joinusID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_joinus` (`joinusID`, `show`, `terms_of_use`, `title`, `text`) VALUES (1, 1, 1, '{[de]}Werde noch heute ein Mitglied!{[en]}Become a member today!{[it]}Diventa un membro oggi!', '{[de]}Die Webspell-RM Community suchen stetig nach neuen Mitgliedern. Werde ein Teil von einer professionellen und gro&szlig;en Organisation. Werde noch heute ein Webspell-RM Mitglied! {[en]} The Webspell-RM community is constantly looking for new members. Become a part of a professional and large organization. Become a Webspell-RM member today! {[it]} La comunità Webspell-RM è costantemente alla ricerca di nuovi membri. Entra a far parte di un\'organizzazione professionale e di grandi dimensioni. Diventa un membro di Webspell-RM oggi!')");
 
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_joinus_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_joinus_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Join us', 'joinus', '{[de]}Mit diesem Plugin könnt ihr das Joinus anzeigen lassen.{[en]}With this plugin you can display the Joinus.{[it]}Con questo plugin puoi visualizzare il pulsante Unisciti a noi.', 'admin_joinus', 1, 'T-Seven', 'https://webspell-rm.de', 'joinus', '', '0.1', 'includes/plugins/joinus/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'joinus', 'Join Us Content', 'widget_joinus_content', '3')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Mitglied werden{[en]}Join Us{[it]}Unisciti a noi', 'joinus', 'admincenter.php?site=admin_joinus', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 6, '{[de]}Mitglied werden{[en]}Join Us{[it]}Unisciti a noi', 'joinus', 'index.php?site=joinus', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>