<?php
global $str,$modulname,$version;
$modulname='fightus';
$version='0.3';
$str='Fight Us';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fightus_challenge` (
  `chID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(14) NOT NULL DEFAULT '0',
  `cwdate` int(14) NOT NULL DEFAULT '0',
  `squadID` varchar(255) NOT NULL DEFAULT '',
  `opponent` varchar(255) NOT NULL DEFAULT '',
  `opphp` varchar(255) NOT NULL DEFAULT '',
  `league` varchar(255) NOT NULL DEFAULT '',
  `map` varchar(255) NOT NULL DEFAULT '',
  `server` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `gametype` varchar(255) NOT NULL,
  `matchtype` varchar(255) NOT NULL,
  `spielanzahl` varchar(255) NOT NULL,
  `messager` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `opponents` varchar(255) NOT NULL,
  PRIMARY KEY (`chID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fightus_gametype` (
  `gametypeID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `abkuerzung` varchar(100) NOT NULL DEFAULT '',
  `clanID` int(11) NOT NULL,
  PRIMARY KEY (`gametypeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_fightus_gametype` (`gametypeID`, `name`, `abkuerzung`, `clanID`) VALUES
(1, 'Search & Destroy', 's&d', 0),
(2, 'Team - Deathmatch', 'tdm', 0)");


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fightus_matchtype` (
  `matchtypeID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `clanID` int(11) NOT NULL,
  PRIMARY KEY (`matchtypeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_fightus_matchtype` (`matchtypeID`, `name`, `clanID`) VALUES 
(1, 'CB - War', 0),
(2, 'ESL - War', 0),
(3, 'Clanwar', 0),
(4, 'Funwar', 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fightus_spieleranzahl` (
  `spielanzahlID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `clanID` int(11) NOT NULL,
  PRIMARY KEY (`spielanzahlID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_fightus_spieleranzahl` (`spielanzahlID`, `name`, `clanID`) VALUES 
(1, '1on1', 0),
(2, '2on2', 0),
(3, '3on3', 0),
(4, '4on4', 0),
(5, '5on5', 0),
(6, '6on6', 0),
(7, 'mehr', 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fightus_maps` (
  `mapID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `game` varchar(255) NOT NULL,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`mapID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_fightus_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_fightus_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Articles', 'fightus', '{[de]}Mit diesem Plugin könnt ihr habt ihr ein Herausforderungsmodul.{[en]}With this plugin you can have a challenge module.{[it]}Con questo plugin puoi avere un modulo di sfida.', 'admin_fightus', 1, 'T-Seven', 'https://webspell-rm.de', 'fightus', '', '0.3', 'includes/plugins/fightus/', 1, 1, 1, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Fightus{[en]}Fightus{[it]}Sfide', 'fightus', 'admincenter.php?site=admin_fightus', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Fightus{[en]}Fightus{[it]}Sfide', 'fightus', 'index.php?site=fightus', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>