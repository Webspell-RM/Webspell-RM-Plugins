<?php
$name = $_GET[ 'modulname' ];
$name_2 = 'discord';
$name_3 = 'facebook';
// Name Tabelle | Where Klause | ID name
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_dashboard_links","modulname",$name_2);
DeleteData("navigation_dashboard_links","modulname",$name_3);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name_2);
DeleteData("navigation_website_sub","modulname",$name_3);
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins","modulname",$name_2);
DeleteData("settings_plugins","modulname",$name_3);
DeleteData("settings_plugins_widget","modulname",$name);
DeleteData("settings_plugins_widget","modulname",$name_2);
DeleteData("settings_plugins_widget","modulname",$name_3);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name_2."");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name_3."");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name_3."_content");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings_widgets");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name_2."_settings_widgets");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name_3."_settings_widgets");
?>