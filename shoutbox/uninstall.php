<?php
$name = $_GET[ 'modulname' ];
// Name Tabelle | Where Klause | ID name
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins_widget","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings_widgets");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_widget_".$name."_sidebar_settings_widgets"); 
?>