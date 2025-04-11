<?php
$name = $_GET[ 'modulname' ];
#$name2 = 'clanwar_result';
// Name Tabelle | Where Klause | ID name
DeleteData("navigation_dashboard_links","modulname",$name);
#DeleteData("navigation_dashboard_links","modulname",$name2);
DeleteData("navigation_website_sub","modulname",$name);
#DeleteData("navigation_website_sub","modulname",$name2);
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins_widget","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings_widgets");
#safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name2."_settings_widgets");
?>