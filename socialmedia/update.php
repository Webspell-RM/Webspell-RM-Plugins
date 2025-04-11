<?php
global $str,$modulname,$version;
$modulname='socialmedia';
$version='0.1';
$str='Social Media';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_socialmedia_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_socialmedia_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## DISCORD #####################################################################################################################################
$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_discord_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_discord_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## FACEBOOK #####################################################################################################################################

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_facebook` (
`facebookID` int(11) NOT NULL AUTO_INCREMENT,
  `fb1_activ` int(11) NOT NULL,
  `fb2_activ` int(11) NOT NULL,
  `fb3_activ` int(11) NOT NULL,
  `fb4_activ` int(11) NOT NULL,
   PRIMARY KEY (`facebookID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_facebook` (`facebookID`, `fb1_activ`, `fb2_activ`, `fb3_activ`, `fb4_activ`) VALUES (1, 0, 1, 0, 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_facebook_content` (
  `fbID` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_name` varchar(255) NOT NULL,
  `facebook_id` varchar(255) NOT NULL,
  `facebook_title` varchar(255) NOT NULL,
  `facebook_height` varchar(255) NOT NULL,
  `displayed` int(1) NOT NULL,
  `sort` int(11) NOT NULL,
PRIMARY KEY (`fbID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_facebook_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_facebook_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Social Media', 'socialmedia', '{[de]}Mit diesem Plugin könnt ihr eure Social Media Links anzeigen lassen.{[en]}With this plugin you can display your social media links.{[it]}Con questo plugin puoi visualizzare i tuoi link dei social media.', 'admin_socialmedia', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/socialmedia/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'socialmedia', 'Follow Us Content', 'widget_follow_us_content', 3),
('', 'socialmedia', 'Social Media Content', 'widget_socialmedia_content', 3),
('', 'socialmedia', 'Social Media Sidebar', 'widget_social-sidebar_content', 4),
('', 'socialmedia', 'Discord Sidebar', 'widget_discord_sidebar', 4),
('', 'socialmedia', 'Facebook Sidebar', 'widget_facebook_sidebar', 4),
('', 'socialmedia', 'Facebook Sidebar Verdux', 'widget_facebook_sidebar_verdux', 4)");

$transaction .= add_insert_plugin_2("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Discord', 'discord', '{[de]}Mit diesem Plugin könnt ihr eure Social Media Links anzeigen lassen. Ein Teil vom Social Media Plugin!{[en]}With this plugin you can display your social media links. Part of the social media plugin!{[it]}Con questo plugin puoi visualizzare i tuoi link dei social media.Parte del plug-in dei social media!', '', 1, 'T-Seven', 'https://webspell-rm.de', 'discord', '', '0.1', 'includes/plugins/socialmedia/', 1, 1, 1, 0, 'deactivated')");

$transaction .= add_insert_plugin_3("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Facebook', 'facebook', '{[de]}Mit diesem Plugin könnt ihr eure Social Media Links anzeigen lassen. Ein Teil vom Social Media Plugin!{[en]}With this plugin you can display your social media links. Part of the social media plugin!{[it]}Con questo plugin puoi visualizzare i tuoi link dei social media. Parte del plug-in dei social media!', '', 1, 'T-Seven', 'https://webspell-rm.de', 'facebook', '', '0.1', 'includes/plugins/socialmedia/', 1, 1, 1, 0, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 11, '{[de]}Social Media{[en]}Social Media{[it]}Social Media', 'socialmedia', 'admincenter.php?site=admin_socialmedia', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 5, '{[de]}Discord{[en]}Discord{[it]}Discord', 'socialmedia', 'index.php?site=discord', 1, 1, 'default')");

$transaction .= add_insert_navigation_2("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 5, '{[de]}Facebook{[en]}Facebook{[it]}Facebook', 'socialmedia', 'index.php?site=facebook', 1, 1, 'default')");

#######################################################################################################################################

echo "</div></div>";
	
 ?>