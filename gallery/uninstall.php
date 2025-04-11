<?php
$name = "gallery";
$name1 = "usergallery";
$name2 = "portfolio";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins","modulname",$name1);
DeleteData("settings_plugins","modulname",$name2);
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name2);

DeleteData("settings_plugins_widget","modulname",$name);

safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_groups");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_pictures");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_comments");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings_widgets");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name1."_settings_widgets");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name2."_settings_widgets");
?>