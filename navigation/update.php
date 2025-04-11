<?php
global $str,$modulname,$version;
$modulname='navigation';
$version='0.3';
$str='Navigation';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Navigation', 'navigation', '{[de]}Mit diesem Plugin könnt ihr euch die Navigation anzeigen lassen.{[en]}With this plugin you can display navigation.{[it]}Con questo plugin puoi visualizzare la Barra di navigazione predefinita.', '', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.3', 'includes/plugins/navigation/', 1, 1, 0, 0, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'navigation', 'Navigation', 'widget_navigation', 2)");

## NAVIGATION #####################################################################################################################################

## Datei delete ###################################################################################################################################

$datei_name = '../includes/plugins/navigation/css/styles.css';
 if (@file_exists($datei_name) == true) {
	 if (@unlink($datei_name) == true) {
	 echo '<div class="alert alert-success" role="alert">Die Datei: '.$datei_name.' wurde erfolgreich gelöscht.</div>';
	 } else {
	 echo '<div class="alert alert-danger" role="alert">Die Datei: '.$datei_name.' konnte nicht gelöscht werden!</div>';
	 }
 } else {
 	echo '<div class="alert alert-warning" role="alert">Die Datei: '.$datei_name.' ist nicht vorhanden!</div>';
 }

echo "</div></div>";
	
 ?>