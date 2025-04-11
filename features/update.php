<?php
global $str,$modulname,$version;
$modulname='features';
$version='0.1';
$str='Features';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_features_box_one` (
  `featuresID` int(11) NOT NULL AUTO_INCREMENT,
  `title_one` varchar(255) NOT NULL DEFAULT '',
  `text_one` text NOT NULL,
  `title_two` varchar(255) NOT NULL,
  `text_two` text NOT NULL,
  `title_three` varchar(255) NOT NULL,
  `text_three` text NOT NULL,
  `title_four` varchar(255) NOT NULL,
  `text_four` text NOT NULL,
  `title_five` varchar(255) NOT NULL,
  `text_five` text NOT NULL,
  `title_six` varchar(255) NOT NULL,
  `text_six` text NOT NULL,
  `features_box_chars` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '160',
  PRIMARY KEY (`featuresID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_features_box_one` (`featuresID`, `title_one`, `text_one`, `title_two`, `text_two`, `title_three`, `text_three`, `title_four`, `text_four`, `title_five`, `text_five`, `title_six`, `text_six`, `features_box_chars`) VALUES
(1, '{[en]}FULL responsive{[it]}PIENAMENTE reattivo{[de]}VOLLSTÄNDIG reaktionsfähig', '{[en]}The new version was adjusted with bootstrap so that it\'s possible to display your website perfect on any device. Test it now..{[it]}La nuova versione è stata adattata con bootstrap in modo che sia possibile visualizzare il tuo sito web perfettamente su qualsiasi dispositivo. Provalo adesso..{[de]}Die neue Version wurde mit Bootstrap angepasst, sodass eine perfekte Darstellung Ihrer Website auf jedem Gerät möglich ist. Testen Sie es jetzt..', '{[en]}Add-on & mods{[de]}Add-on und Mods{[it]}Componenti aggiuntivi e mod', '{[en]}With the Add-ons and modifications you can get your own individual system. Whether a navigation addon or a recaptcha mod, or, or, or.. {[it]}Con i componenti aggiuntivi e le modifiche puoi ottenere il tuo sistema personalizzato. Che si tratti di un componente aggiuntivo di navigazione o di un mod recaptcha, o, o, o..{[de]}Mit den Add-ons und Modifikationen erhalten Sie Ihr individuelles System. Ob ein Navigations-Addon oder ein Recaptcha-Mod, oder, oder, oder.. ', '{[en]}Community{[de]}Gemeinschaft{[it]}Comunità', '{[en]}If you have issues or problems. The community about Webspell-RM can help to solve a lots of problems.{[it]}Se hai problemi o problemi. La comunità su Webspell-RM può aiutare a risolvere molti problemi.{[de]}Wenn Sie Probleme oder Probleme haben. Die Community zu Webspell-RM kann bei der Lösung vieler Probleme helfen.', '{[en]}Plugin-Installer{[de]}Plugin-Installer{[it]}Installatore di plug-in', '{[en]}The new version was adjusted with bootstrap so that it\'s possible to display your website perfect on any device. Test it now..{[it]}La nuova versione è stata adattata con bootstrap in modo che sia possibile visualizzare il tuo sito web perfettamente su qualsiasi dispositivo. Provalo adesso..{[de]}Die neue Version wurde mit Bootstrap angepasst, sodass eine perfekte Darstellung Ihrer Website auf jedem Gerät möglich ist. Testen Sie es jetzt..', '{[de]}Vorlageninstallationsprogramm{[en]}Template-Installer{[it]}Programma di installazione dei modelli', '{[en]}With the Add-ons and modifications you can get your own individual system. Whether a navigation addon or a recaptcha mod, or, or, or.. {[it]}Con i componenti aggiuntivi e le modifiche puoi ottenere il tuo sistema personalizzato. Che si tratti di un componente aggiuntivo di navigazione o di un mod recaptcha, o, o, o..{[de]}Mit den Add-ons und Modifikationen erhalten Sie Ihr individuelles System. Ob ein Navigations-Addon oder ein Recaptcha-Mod, oder, oder, oder.. ', '{[en]}Community{[de]}Gemeinschaft{[it]}Comunità', '{[en]}If you have issues or problems. The community about Webspell-RM can help to solve a lots of problems.{[it]}Se hai problemi o problemi. La comunità su Webspell-RM può aiutare a risolvere molti problemi.{[de]}Wenn Sie Probleme oder Probleme haben. Die Community zu Webspell-RM kann bei der Lösung vieler Probleme helfen.', '100')");

##############

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_features_box_two` (
  `featuresID` int(11) NOT NULL AUTO_INCREMENT,
  `title_one` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title_small_one` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text_one` text COLLATE utf8_unicode_ci NOT NULL,
  `title_two` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_small_two` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text_two` text COLLATE utf8_unicode_ci NOT NULL,
  `title_three` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_small_three` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text_three` text COLLATE utf8_unicode_ci NOT NULL,
  `features_box_chars` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '160',
  PRIMARY KEY (`featuresID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_features_box_two` (`featuresID`, `title_one`, `title_small_one`, `text_one`, `title_two`, `title_small_two`, `text_two`, `title_three`, `title_small_three`, `text_three`, `features_box_chars`) VALUES
(1, 'FULL responsive', 'Looks awesome on any device', 'The new version was adjusted with bootstrap so that it\'s possible to display your website perfect on any device. Test it now..', 'add-on & mods', 'expand your system', 'With the Add-ons and modifications you can get your own individual system. Whether a navigation addon or a recaptcha mod, or, or, or.. ', 'Community', 'helping each other', 'If you have issues or problems. The community about Webspell-RM can help to solve a lots of problems.', '160')");
#######################

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

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Features', 'features', '{[de]}Mit diesem Plugin könnt ihr eure Features anzeigen lassen.{[en]}With this plugin you can display your Features.{[it]}Con questo plugin puoi visualizzare la Funzionalità.', 'admin_features', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/features/', 1, 1, 0, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'features', 'Features Box One Content', 'widget_features_box_one_content', 3),
('', 'features', 'Features Box Two Content', 'widget_features_box_two_content', 3),
('', 'features', 'Features Box Three Content', 'widget_features_box_three_content', 3),
('', 'features', 'Features Box Four Content', 'widget_features_box_four_content', 3)");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 7, '{[de]}Features{[en]}Features{[it]}Funzionalità', 'features', 'admincenter.php?site=admin_features', 'page', 1)");


#######################################################################################################################################

echo "</div></div>";
  
 ?>