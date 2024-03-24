<?php
#$name = "news_manager";
$name = $_GET[ 'modulname' ];
$name2 = 'news_manager_archive';
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname",$name);
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name2);
DeleteData("settings_module","modulname",$name);
DeleteData("settings_widgets","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_rubrics");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_comments");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_comments_recomment");
?>