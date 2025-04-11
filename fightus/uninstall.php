<?php
$name = $_GET[ 'modulname' ];
// Name Tabelle | Where Klause | ID name
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins_widget","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_matchtype");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_gametype");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_spieleranzahl");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_challenge");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_maps");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings_widgets");
?>