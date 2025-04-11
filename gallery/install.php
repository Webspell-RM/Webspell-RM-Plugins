<?php 
global $str,$modulname,$version;
$modulname='gallery';
$version='0.1';
$str='Gallery';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery` (
  `galleryID` int(11) NOT NULL AUTO_INCREMENT,
  `groupID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` int(14) NOT NULL,
  `text` text NOT NULL,
  `displayed_gal` int(1) NOT NULL DEFAULT '1',
  `displayed_port` int(1) NOT NULL DEFAULT '1',
  `pic_video` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`galleryID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_gallery` (`galleryID`, `groupID`, `userID`, `name`, `date`, `text`, `displayed_gal`, `displayed_port`, `pic_video`, `sort`) VALUES (1, 1, 0, 'Gallery intern', 1734808941, '', 0, 0, 0, 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_groups` (
  `groupID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `intern` int(1) NOT NULL DEFAULT 1,
  `displayed_cat` int(1) NOT NULL DEFAULT 1,
  `sort` int(11) NOT NULL,
  PRIMARY KEY ( `groupID` )
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_gallery_groups` (`groupID`, `name`, `intern`, `displayed_cat`, `sort`) VALUES
(1, 'Intern', 0, 0, 1)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_pictures` (
  `picID` int(11) NOT NULL AUTO_INCREMENT,
  `galleryID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `youtube` varchar(255) NOT NULL,
  `banner` int(11) NOT NULL,
  `dateupl` int(14) NOT NULL,
  `comment` text NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `comments` int(1) NOT NULL DEFAULT 0,
  `votes` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `displayed_gal` int(1) NOT NULL DEFAULT 1,
  `displayed_port` int(1) NOT NULL DEFAULT 1,
  `pic_video` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL,
  PRIMARY KEY ( `picID` )
 ) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_settings` (
  `gallerysetID` int(11) NOT NULL AUTO_INCREMENT,
  `publicadmin` int(11) NOT NULL,
  `usergalleries` int(11) NOT NULL DEFAULT 0,
  `maxusergalleries` int(11) NOT NULL,
  `groups` int(11) NOT NULL,
  `gallery_per_page_row` int(11) NOT NULL DEFAULT 4,
  `gal_img_per_page` int(11) NOT NULL DEFAULT 4,
  `gal_img_per_page_row` int(11) NOT NULL DEFAULT 4,
  `port_max_img` int(11) NOT NULL DEFAULT 8,
  `port_img_per_page` int(11) NOT NULL DEFAULT 4,
  PRIMARY KEY (`gallerysetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_gallery_settings` (`gallerysetID`, `publicadmin`, `usergalleries`, `maxusergalleries`, `groups`, `gallery_per_page_row`, `gal_img_per_page`, `gal_img_per_page_row`, `port_max_img`, `port_img_per_page`) VALUES
(1, 1, 1, 20971520, 2, 2, 20, 2, 0, 2)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) NOT NULL DEFAULT '0',
  `type` char(2) NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `date` int(14) NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`commentID`),
  KEY `parentID` (`parentID`),
  KEY `type` (`type`),
  KEY `date` (`date`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci"); 

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_gallery_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_gallery_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 0, 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_usergallery_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_usergallery_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 0, 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_portfolio_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_portfolio_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Gallery', 'gallery', '{[de]}Mit diesem Plugin könnt ihr eure die Mediathek anzeigen lassen.{[en]}With this plugin you can display your media library.{[it]}Con questo plugin puoi visualizzare la tua libreria multimediale.', 'admin_gallery', 1, 'T-Seven', 'https://webspell-rm.de', 'gallery,gallery_rating,usergallery,gallery_comments', '', '0.1', 'includes/plugins/gallery/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'gallery', 'Gallery Sidebar', 'widget_gallery_sidebar', 4),
('', 'gallery', 'Gallery Content', 'widget_gallery_content', 3),
('', 'gallery', 'Portfolio Content', 'widget_portfolio_content', 3)");

$transaction .= add_insert_plugin_2("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Portfolio', 'portfolio', '{[de]}Mit diesem Plugin könnt ihr Portfolio anzeigen lassen.{[en]}With this plugin you can display your portfolio.{[it]}Con questo plugin puoi visualizzare il tuo portfolio.', '', 1, 'T-Seven', 'https://webspell-rm.de', 'portfolio', '', '0.1', 'includes/plugins/gallery/', 1, 1, 1, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 8, '{[de]}Mediathek{[en]}Media Library{[it]}Biblioteca multimediale', 'gallery', 'admincenter.php?site=admin_gallery', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 4, '{[de]}Mediathek{[en]}Media Library{[it]}Biblioteca multimediale', 'gallery', 'index.php?site=gallery', 1, 1, 'default')");

$transaction .= add_insert_navigation_2("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 4, '{[de]}Portfolio{[en]}Portfolio{[it]}Portfolio', 'gallery', 'index.php?site=portfolio', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>