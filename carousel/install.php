<?php
global $str,$modulname,$version;
$modulname='carousel';
$version='0.3';
$str='Carusel';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel` (
  `carouselID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ani_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ani_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `ani_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `carousel_pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `carousel_vid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `time_pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '1',
  `displayed` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`carouselID`)
) AUTO_INCREMENT=4
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");
        
$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_carousel` (`carouselID`, `title`, `ani_title`, `link`, `ani_link`, `description`, `ani_description`, `carousel_pic`, `carousel_vid`, `time_pic`, `sort`, `displayed`) VALUES
(1, 'The Best <span>Games</span> Out There', 'rollIn', 'https://www.webspell-rm.de', 'fadeInRight', 'The Bootstrap Carousel in Webspell? No way?! Yes we did it!', 'fadeInUp', '1.jpg','', '5', 1, '1'),
(2, 'The Best <span>Games</span> Out There', 'fadeInDown', 'https://www.webspell-rm.de', 'fadeInRight', 'The Bootstrap Carousel in Webspell? No way?! Yes we did it!', 'fadeInLeft', '2.jpg','', '5', 1, '1'),
(3, 'The Best <span>Games</span> Out There', 'fadeInUp', 'https://www.webspell-rm.de', 'fadeInDown', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada\nlorem maximus mauris scelerisque, at rutrum nulla dictum. Ut ac ligula sapien.\nSuspendisse cursus faucibus finibus.', 'fadeInRight', '3.jpg','', '5', 1, '1'),
(4, 'The Best <span>Games</span> Out There', 'fadeInRightBig', 'https://www.webspell-rm.de', 'fadeInLeft', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada lorem maximus mauris scelerisque, at rutrum nulla dictum. Ut ac ligula sapien. Suspendisse cursus faucibus finibus.', 'fadeInUp', '4.jpg','', '5', 1, '1'),
(5, 'Call of Duty® <span>Black Ops 4</span>', 'fadeInRightBig', 'https://www.callofduty.com/it/blackops4/pc', 'fadeInLeft', 'https://www.callofduty.com/it/blackops4/pc', 'fadeInUp', '','5.mp4', '13', 1, '1')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel_parallax` (
  `parallaxID` int(11) NOT NULL AUTO_INCREMENT,
  `parallax_pic` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`parallaxID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_carousel_parallax` (`parallaxID`, `parallax_pic`, `text`) VALUES
(1, 'parallax.jpg', 'parallax')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel_sticky` (
  `stickyID` int(11) NOT NULL AUTO_INCREMENT,
  `sticky_pic` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`stickyID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_carousel_sticky` (`stickyID`, `sticky_pic`, `title`, `description`, `link`) VALUES
(1, 'sticky.jpg', 'The Best <span>Games</span> Out There', 'The Bootstrap Carousel in Webspell? No way?! Yes we did it!', 'https://www.webspell-rm.de')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel_agency` (
  `agencyID` int(11) NOT NULL AUTO_INCREMENT,
  `agency_pic` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`agencyID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_carousel_agency` (`agencyID`, `agency_pic`, `title`, `description`, `link`) VALUES
(1, 'agency.jpg', 'The Best <span>Games</span> Out There', 'The Bootstrap Carousel in Webspell? No way?! Yes we did it!', 'https://www.webspell-rm.de')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel_settings` (
  `carouselID` int(11) NOT NULL AUTO_INCREMENT,
  `carousel_height` varchar(255) NOT NULL DEFAULT '0',
  `parallax_height` varchar(255) NOT NULL DEFAULT '0',
  `sticky_height` varchar(255) NOT NULL DEFAULT '0',
  `agency_height` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`carouselID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_carousel_settings` (`carouselID`, `carousel_height`, `parallax_height`, `sticky_height`, `agency_height`) VALUES
(1, '75vh', '75vh', '75vh', '75vh')");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Carousel', 'carousel', '{[de]}Mit diesem Plugin könnt ihr ein Carousel in die Webseite einbinden.{[en]}With this plugin you can integrate a carousel into your website.{[it]}Con questo plugin puoi integrare un carosello nel sito web.', 'admin_carousel', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/carousel/', 1, 1, 0, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'carousel', 'Sticky Header', 'widget_sticky_header', 1),

('', 'carousel', 'Carousel Crossfade', 'widget_carousel_crossfade', 3),
('', 'carousel', 'Carousel Only', 'widget_carousel_only', 3),
('', 'carousel', 'Parallax Header', 'widget_parallax_header', 3),
('', 'carousel', 'Agency Header', 'widget_agency_header', 3)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 9, '{[de]}Carousel{[en]}Carousel{[it]}Carosello Immagini', 'carousel', 'admincenter.php?site=admin_carousel', 'page', 1)");

#######################################################################################################################################

echo "</div></div>";
	
 ?>