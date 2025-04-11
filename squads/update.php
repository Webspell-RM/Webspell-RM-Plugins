<?php
global $str,$modulname,$modulname_2,$version;
$modulname='squads';
$modulname_2='games_pic';
$modulname_3='squads_memberslist';
$version='0.2';
$str='Squads';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
DeleteData("navigation_website_sub","modulname",$modulname_3);
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_games_pic` (
  `gameID` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`gameID`)
) AUTO_INCREMENT=52
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_games_pic` (`gameID`, `tag`, `name`) VALUES
(1, 'apex_l', 'Apex Legends'),
(2, 'ark_se', 'ARK: Survival Evolved'),
(3, 'ac', 'Assetto Corsa'),
(4, 'bf_1', 'Battlefield'),
(5, 'bf_4', 'Battlefield 4'),
(6, 'bf_5', 'Battlefield 5'),
(7, 'bd', 'Black Desert'),
(8, 'cod_mw', 'Call of Duty: Modern Warfare'),
(9, 'cod_wz', 'Call of Duty: Warzone'),
(10, 'ce', 'Conan Exiles'),
(11, 'cs_go', 'Counter-Strike: GO'),
(12, 'cs_s', 'Counter-Strike: Source'),
(13, 'dbd', 'Dead by Daylight'),
(14, 'd_2', 'Destiny 2'),
(15, 'di_3', 'Diablo III'),
(16, 'dac', 'Dota Auto Chess'),
(17, 'do_2', 'Dota 2'),
(18, 'd_ul', 'Dota Underlords'),
(19, 'teso', 'The Elder Scrolls Online'),
(20, 'f1_2020', 'F1 2020'),
(21, 'fifa_20', 'FIFA 20'),
(22, 'ff_14', 'Final Fantasy XIV'),
(23, 'fort', 'Fortnite'),
(24, 'gta_on', 'Grand Theft Auto Online'),
(25, 'gw_2', 'Guild Wars 2'),
(26, 'hs_how', 'Hearthstone: Heroes of Warcraft'),
(27, 'h_sd', 'Hunt: Showdown'),
(28, 'lol', 'League of Legends'),
(29, 'lor', 'Legends of Runeterra'),
(30, 'mc', 'Minecraft'),
(31, 'mc_d', 'Minecraft Dungeons'),
(32, 'mh_w', 'Monster Hunter: World'),
(33, 'ow', 'Overwatch'),
(34, 'poe', 'Path of Exile'),
(35, 'pd_2', 'Payday 2'),
(36, 'pubg', 'Playerunknown\'s Battlegrounds'),
(37, 'rs_s', 'Rainbow Six: Siege'),
(38, 'rd_o', 'Red Dead Online'),
(39, 'rl', 'Rocket League'),
(40, 'smi', 'Smite'),
(41, 'sc2_wol', 'StarCraft II: Wings of Liberty'),
(42, 'swbf2', 'Star Wars Battlefront II'),
(43, 'swbf1', 'Star Wars: Battlefront'),
(44, 'tf_t', 'Teamfight Tactics'),
(45, 'td2', 'The Division 2'),
(46, 'vt', 'Valorant'),
(47, 'war_f', 'Warframe'),
(48, 'wc3_roc', 'Warcraft III: Reign of Chaos'),
(49, 'wc3_ref', 'Warcraft III: Reforged'),
(50, 'wot', 'World of Tanks'),
(51, 'wow', 'World of Warcraft'),
(52, 'forzah4', 'Forza Horizon 4'),
(53, 'forzah5', 'Forza Horizon 5'),
(54, 'cod_ghosts', 'Call of Duty: Ghosts'),
(55, 'cod_1', 'Call of Duty'),
(56, 'cod_uo', 'Call of Duty: United Offensive'),
(57, 'cities_sky', 'Cities: Skylines'),
(58, 'cod_ww2', 'Call of Duty: WWII'),
(59, 'ut', 'Unreal Tournament'),
(60, 'pc_1', 'Project CARS'),
(61, 'gt_7', 'Gran Turismo 7'),
(62, 'wows', 'World of Warships'),
(63, 'mfshead', 'Need for Speed Heat'),
(64, 'nfshpr', 'Need For Speed: Hot Pursuit Remastered'),
(65, 'xcomeu', 'XCOM: Enemy Unknown'),
(66, 'nfsmw2012', 'Need for Speed: Most Wanted'),
(67, 'nfsub', 'Need for Speed: Unbound'),
(68, '7dtd', '7 Days to Die'),
(69, 'nmrih', 'No More Room in Hell'),
(70, 'pz', 'Project Zomboid'),
(71, 'vlh', 'Valheim'),
(72, 'ts', 'Teamspeak'),
(73, 'cod_2', 'Call of Duty 2')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads` (
  `squadID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesquad` int(11) NOT NULL DEFAULT 1,
  `games` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon_small` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`squadID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads_members` (
  `sqmID` int(11) NOT NULL AUTO_INCREMENT,
  `squadID` int(11) NOT NULL DEFAULT 0,
  `userID` int(11) NOT NULL DEFAULT 0,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `activity` int(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `joinmember` int(1) NOT NULL DEFAULT 0,
  `warmember` int(1) NOT NULL DEFAULT 0,
PRIMARY KEY (`sqmID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads_settings` (
  `squadssetID` int(11) NOT NULL AUTO_INCREMENT,
  `squads` int(11) NOT NULL,
  `squadschars` int(11) NOT NULL,
  PRIMARY KEY (`squadssetID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_squads_settings` (`squadssetID`, `squads`, `squadschars`) VALUES
(1, 1, '')");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_squads_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_squads_memberslist_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_squads_memberslist_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Squads', 'squads', '{[de]}Mit diesem Plugin könnt ihr eure Teams anzeigen lassen.{[en]}With this plugin you can display your teams.{[it]}Con questo plugin puoi visualizzare le tue Squadre con i Membri.', 'admin_squads,admin_games_pic', 1, 'T-Seven', 'https://webspell-rm.de', 'squads', '', '0.2', 'includes/plugins/squads/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'squads', 'Squads Roster Content', 'widget_squads_roster', 3),
('', 'squads', 'Squads Content', 'widget_squads_content', 3),
('', 'squads', 'Squads Sidebar', 'widget_squads_sidebar', 4),
('', 'squads', 'Squads Memberlist Content', 'widget_squads_memberslist', 3)");

$transaction .= add_insert_plugin_2("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Memberslist', 'squads_memberslist', '{[de]}Mit diesem Plugin könnt ihr Mitglieder in Teams anzeigen lassen. Ein Teil vom Squads Plugin!{[en]}This plugin lets you display members in teams. It\'s part of the Squads plugin!{[it]}Con questo plugin puoi visualizzare i membri nei team. Parte del plugin Squads!', '', 1, 'T-Seven', 'https://webspell-rm.de', 'squads_memberslist', '', '0.2', 'includes/plugins/squads/', 1, 1, 1, 0, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Squads{[en]}Squads{[it]}Squadre', 'squads', 'admincenter.php?site=admin_squads', 'page', 1)");

$transaction .= add_insert_navi_dashboard_2("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}Games Pic{[en]}Games Pic{[it]}Immagini Giochi', 'games_pic', 'admincenter.php?site=admin_games_pic', 'page', 1)");

$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Squads{[en]}Squads{[it]}Squadre', 'squads', 'index.php?site=squads', 1, 1, 'default')");

$transaction .= add_insert_navigation_2("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 2, '{[de]}Mitglieder Liste{[en]}Members List{[it]}Lista Membri', 'squads_memberslist', 'index.php?site=squads_memberslist', 1, 1, 'default')");

#######################################################################################################################################

echo "</div></div>";
  
 ?>
