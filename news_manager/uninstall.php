<?php
#$name = "news_manager";
$name = $_GET[ 'modulname' ];
$name2 = 'news_archive';
// Name Tabelle | Where Klause | ID name
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name2);
DeleteData("settings_plugins","modulname",$name);
DeleteData("settings_plugins_widget","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_rubrics");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_comments");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_comments_recomment");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_settings_widgets");
?>