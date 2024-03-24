<?php
$name = "gallery";
$name1 = "usergallery";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins","modulname",$name1);
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("settings_module","modulname",$name);
DeleteData("settings_module","modulname",$name1);
DeleteData("settings_widgets","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_groups");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_pictures");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_comments");
?>