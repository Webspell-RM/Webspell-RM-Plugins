<?php
$name = $_GET[ 'modulname' ];
// Name Tabelle | Where Klause | ID name
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins_widget","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_useraward");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_useraward_list");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_useraward_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_useraward_settings_widgets");
?>