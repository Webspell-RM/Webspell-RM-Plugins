<?php
global $str,$modulname,$version;
$modulname='tags';
$version='0.31';
$str='Tags';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Tags', 'tags', '{[de]}Mit diesem Plugin könnt ihr eure Tags anzeigen lassen.{[en]}With this plugin you can display your tags.{[it]}Con questo plugin puoi visualizzare i tag.', '', 1, 'T-Seven', 'https://webspell-rm.de', 'tags', '', '0.1', 'includes/plugins/tags/', 1, 1, 1, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'tags', 'Tags Sidebar', 'widget_tags_sidebar', '4'),
('', 'tags', 'Tags Button Sidebar', 'widget_tags_button_sidebar', '4')");

## NAVIGATION #####################################################################################################################################

#######################################################################################################################################
echo "</div></div>";

  
 ?>