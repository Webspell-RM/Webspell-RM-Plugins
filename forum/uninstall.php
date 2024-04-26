<?php
$name = $_GET[ 'modulname' ];
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","forum_topic");
DeleteData("settings_plugins","modulname",$name);
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("settings_module","modulname",$name);
DeleteData("settings_module","modulname","forum_topic");
DeleteData("settings_widgets","modulname",$name);
DeleteData("navigation_website_sub","themes_modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_announcements");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_boards");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_categories");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_notify");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_posts");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_topics");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_poll");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_votes");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_groups");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_moderators");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_posts_spam");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_ranks");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_topics_spam"); 
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."_user_forum_groups");
?>