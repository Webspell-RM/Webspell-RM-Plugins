<?php
global $str,$modulname,$version;
$modulname='topbar';
$version='0.1';
$str='topbar';
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Topbar', 'topbar', '{[de]}Mit diesem Plugin können Sie eine Symbolleiste über der Navigationsleiste anzeigen{[en]}With this Plugin you can display a Toolbar above the Navigation Bar{[it]}Con questo Plugin puoi visualizzare un Toolbar sopra alla Barra di Navigazione.', '', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/topbar/', 1, 1, 0, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'topbar', 'Topbar One', 'widget_topbar_one', 1),
('', 'topbar', 'Topbar Two', 'widget_topbar_two', 1),
('', 'topbar', 'Topbar Three Verdux', 'widget_topbar_three', 1)");

## NAVIGATION #####################################################################################################################################


echo "</div></div>";
    
 ?>