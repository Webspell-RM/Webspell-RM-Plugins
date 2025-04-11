<?php 
global $str,$modulname,$version;
$modulname='useraward';
$version='0.1';
$str='User Award';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_useraward` (
  `uwID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11),
  `awardID` int(11),
  PRIMARY KEY (`uwID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_useraward_settings` (
  `allaward` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_useraward_settings` (`allaward`) VALUES ('1')");


$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_useraward_list` (
  `uawardID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `info` text,
  `awardrequire` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `awardrequirepoints` int(6) DEFAULT NULL,
PRIMARY KEY (`uawardID`)
) AUTO_INCREMENT=27
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

        
$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_useraward_list` (`uawardID`, `name`, `info`, `awardrequire`, `image`, `awardrequirepoints`) VALUES
(1, 'Comments', '{[de]}F&uuml;r mindestens 100 Kommentare{[en]}For at least 100 comments{[it]}Per almeno 100 Commenti', '100', 'community1.png', 0),
(2, 'Comments', '{[de]}F&uuml;r mindestens 250 Kommentare{[en]}For at least 250 comments{[it]}Per almeno 250 Commenti', '250', 'community2.png', 0),
(3, 'Comments', '{[de]}F&uuml;r mindestens 500 Kommentare{[en]}For at least 500 comments{[it]}Per almeno 500 Commenti', '500', 'community3.png', 0),
(4, 'Forum', '{[de]}F&uuml;r mindestens 100 Forenposts{[en]}For at least 100 forum posts{[it]}Per almeno 100 post nel Forum', '100', 'forum1.png', 0),
(5, 'Forum', '{[de]}F&uuml;r mindestens 250 Forenposts{[en]}For at least 250 forum posts{[it]}Per almeno 250 post nel Forum', '250', 'forum2.png', 0),
(6, 'Forum', '{[de]}F&uuml;r mindestens 500 Forenposts{[en]}For at least 500 forum posts{[it]}Per almeno 500 post nel Forum', '500', 'forum3.png', 0),
(7, 'Member', '{[de]}F&uuml;r mindestens 6 Monate Mitgliedschaft{[en]}For a minimum of 6 months membership{[it]}Utente da un Minimo di 6 Mesi', '180', 'member1.png', 0),
(8, 'Member', '{[de]}F&uuml;r mindestens 1 Jahr Mitgliedschaft{[en]}For a minimum of 1 year membership{[it]}Utente da un Minimo di 1 Anno', '365', 'member2.png', 0),
(9, 'Member', '{[de]}F&uuml;r mindestens 2 Jahre Mitgliedschaft{[en]}For a minimum of 2 years membership{[it]}Utente da un Minimo di 2 Anni', '730', 'member3.png', 0),
(10, 'Member', '{[de]}F&uuml;r mindestens 5 Jahre Mitgliedschaft{[en]}For a minimum of 5 years membership{[it]}Utente da un Minimo di 5 Anni', '1825', 'member4.png', 0),
(11, 'News', '{[de]}F&uuml;r mindestens 25 Newsbeitr&auml;ge{[en]}For at least 25 news posts{[it]}Per almeno 25 post di Notizie', '25', 'news1.png', 0),
(12, 'News', '{[de]}F&uuml;r mindestens 50 Newsbeitr&auml;ge{[en]}For at least 50 news posts{[it]}Per almeno 50 post di Notizie', '50', 'news2.png', 0),
(13, 'News', '{[de]}F&uuml;r mindestens 100 Newsbeitr&auml;ge{[en]}For at least 100 news posts{[it]}Per almeno 100 post di Notizie', '100', 'news3.png', 0),
(14, 'Messages', '{[de]}F&uuml;r mindestens 100 gesendete Private Nachrichten{[en]}For at least 100 private messages sent{[it]}Per almeno 100 Messaggi Privati inviati', '100', 'pm1.png', 0),
(15, 'Messages', '{[de]}F&uuml;r mindestens 250 gesendete Private Nachrichten{[en]}For at least 250 private messages sent{[it]}Per almeno 250 Messaggi Privati inviati', '250', 'pm2.png', 0),
(16, 'Messages', '{[de]}F&uuml;r mindestens 500 gesendete Private Nachrichten{[en]}For at least 500 private messages sent{[it]}Per almeno 500 Messaggi Privati inviati', '500', 'pm3.png', 0),
(17, 'Wars', '{[de]}F&uuml;r 5 gespielte Wars{[en]}For 5 wars played{[it]}Per 5 Guerre Disputate', '5', 'war1.png', 0),
(18, 'Wars', '{[de]}F&uuml;r 10 gespielte Wars{[en]}For 10 wars played{[it]}Per 10 Guerre Disputate', '10', 'war2.png', 0),
(19, 'Wars', '{[de]}F&uuml;r 25 gespielte Wars{[en]}For 25 wars played{[it]}Per 25 Guerre Disputate', '25', 'war3.png', 0),
(20, 'Wars', '{[de]}F&uuml;r 50 gespielte Wars{[en]}For 50 wars played{[it]}Per 50 Guerre Disputate', '50', 'war4.png', 0),
(21, 'Wars', '{[de]}F&uuml;r 75 gespielte Wars{[en]}For 75 wars played{[it]}Per 75 Guerre Disputate', '75', 'war5.png', 0),
(22, 'Wars', '{[de]}F&uuml;r 100 gespielte Wars{[en]}For 100 wars played{[it]}Per 100 Guerre Disputate', '100', 'war6.png', 0),
(23, 'Wars', '{[de]}F&uuml;r 150 gespielte Wars{[en]}For 150 wars played{[it]}Per 150 Guerre Disputate', '150', 'war7.png', 0),
(24, 'Wars', '{[de]}F&uuml;r 200 gespielte Wars{[en]}For 200 wars played{[it]}Per 200 Guerre Disputate', '200', 'war8.png', 0),
(25, 'Wars', '{[de]}F&uuml;r 300 gespielte Wars{[en]}For 300 wars played{[it]}Per 300 Guerre Disputate', '300', 'war9.png', 0),
(26, 'Wars', '{[de]}F&uuml;r 500 gespielte Wars{[en]}For 500 wars played{[it]}Per 500 Guerre Disputate', '500', 'war10.png', 0)");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_useraward_settings_widgets` (
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

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_useraward_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'User Award', 'useraward', '{[de]}Mit diesem Plugin hast du ein UserAwardSystem.{[en]}With this plugin you can display the UserAwardSystem.{[it]}Con questo plugin puoi visualizzare Premi Utenti.', 'admin_user_awards', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/useraward/', 1, 1, 0, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 4, '{[de]}User Award{[en]}User Award{[it]}Premi Utenti', 'useraward', 'admincenter.php?site=admin_user_awards', 'page', 1)");

#######################################################################################################################################
echo "</div></div>";

  
 ?>