<?php
global $str,$modulname,$version;
$modulname='facts';
$version='0.1';
$str='Facts';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Facts & Media', 'facts', '{[de]}Mit diesem Plugin könnt ihr interessante Fakten und Details animiert darstellen.{[en]}With this plugin you can animated interesting facts and details.{[it]}Con questo plugin puoi animare fatti e dettagli interessanti.', '', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/facts/', 1, 1, 0, 1, 'deactivated')");

$transaction .= add_insert_plugins_widget("INSERT IGNORE INTO `" . PREFIX . "settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
('', 'facts', 'Facts Content', 'widget_facts_content', 3),
('', 'facts', 'Media Content', 'widget_media_content', 3)");

#$str                     =   "{[de]}Fakten{[en]}Facts{[it]}Fatti";   

#######################################################################################################################################

echo "</div></div>";
  
 ?>