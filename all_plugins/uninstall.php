<?php


$name = "about_us";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","about_us");
DeleteData("navigation_dashboard_links","modulname","about_us");
DeleteData("navigation_website_sub","modulname","about_us");
DeleteData("settings_module","modulname","about_us");
DeleteData("settings_widgets","modulname","about_us");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_about_us");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/about_us");

$name = "about_box";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","about_box");
DeleteData("navigation_dashboard_links","modulname","about_box");
DeleteData("settings_module","modulname","about_box");
DeleteData("settings_widgets","modulname","about_box");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/about_box");

$name = "articles";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","articles");
DeleteData("navigation_dashboard_links","modulname","articles");
DeleteData("navigation_website_sub","modulname","articles");
DeleteData("settings_module","modulname","articles");
DeleteData("settings_widgets","modulname","articles");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_articles"); 
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_articles_categories"); 
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_articles_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_articles_comments"); 
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/articles");

$name = "awards";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","awards");
DeleteData("navigation_dashboard_links","modulname","awards");
DeleteData("navigation_website_sub","modulname","awards");
DeleteData("settings_module","modulname","awards");
DeleteData("settings_widgets","modulname","awards");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_awards");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/awards");

$name = "awaylist";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","awaylist");
DeleteData("navigation_dashboard_links","modulname","awaylist");
DeleteData("navigation_website_sub","modulname","awaylist");
DeleteData("settings_module","modulname","awaylist");
DeleteData("settings_widgets","modulname","awaylist");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_awaylist");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/awaylist");

$name = "bannerrotation";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","bannerrotation");
DeleteData("navigation_dashboard_links","modulname","bannerrotation");
DeleteData("settings_module","modulname","bannerrotation");
DeleteData("settings_widgets","modulname","bannerrotation");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_bannerrotation");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/bannerrotation");

$name = "blog";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","blog");
DeleteData("navigation_dashboard_links","modulname","blog");
DeleteData("navigation_website_sub","modulname","blog");
DeleteData("settings_module","modulname","blog");
DeleteData("settings_widgets","modulname","blog");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_blog");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_blog_settings"); 
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_blog_comments");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/blog");

$name = "breaking_news";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","breaking_news");
DeleteData("navigation_dashboard_links","modulname","breaking_news");
DeleteData("settings_module","modulname","breaking_news");
DeleteData("settings_widgets","modulname","breaking_news");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_breaking_news");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/breaking_news");

$name = "calendar";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","calendar");
DeleteData("navigation_dashboard_links","modulname","calendar");
DeleteData("navigation_website_sub","modulname","calendar");
DeleteData("settings_module","modulname","calendar");
DeleteData("settings_widgets","modulname","calendar");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_upcoming");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_upcoming_announce");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/calendar");

$name = "candidature";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","candidature");
DeleteData("navigation_dashboard_links","modulname","candidature");
DeleteData("navigation_website_sub","modulname","candidature");
DeleteData("settings_module","modulname","candidature");
DeleteData("settings_widgets","modulname","candidature");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_candidature");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/candidature");

$name = "carousel";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","carousel");
DeleteData("navigation_dashboard_links","modulname","carousel");
DeleteData("settings_module","modulname","carousel");
DeleteData("settings_widgets","modulname","carousel");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_carousel");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/carousel");

$name = "cashbox";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","cashbox");
DeleteData("navigation_dashboard_links","modulname","cashbox");
DeleteData("navigation_website_sub","modulname","cashbox");
DeleteData("settings_module","modulname","cashbox");
DeleteData("settings_widgets","modulname","cashbox");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_cashbox");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_cashbox_payed");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/cashbox");

$name = "clan_rules";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","clan_rules");
DeleteData("navigation_dashboard_links","modulname","clan_rules");
DeleteData("navigation_website_sub","modulname","clan_rules");
DeleteData("settings_module","modulname","clan_rules");
DeleteData("settings_widgets","modulname","clan_rules");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_clan_rules");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_clan_rules_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/clan_rules");

$name = "clanwars";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","clanwars");
DeleteData("navigation_dashboard_links","modulname","clanwars");
DeleteData("navigation_website_sub","modulname","clanwars");
DeleteData("navigation_website_sub","modulname","clanwar_result");
DeleteData("settings_module","modulname","clanwars");
DeleteData("settings_widgets","modulname","clanwars");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_clanwars");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/clanwars");

$name = "counter";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","counter");
DeleteData("navigation_dashboard_links","modulname","counter");
DeleteData("navigation_website_sub","modulname","counter");
DeleteData("settings_module","modulname","counter");
DeleteData("settings_widgets","modulname","counter");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/counter");

$name = "cup";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","cup");
DeleteData("navigation_dashboard_links","modulname","cup");
DeleteData("navigation_website_sub","modulname","cup");
DeleteData("settings_module","modulname","cup");
DeleteData("settings_widgets","modulname","cup");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_cup_teams");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_cup_config");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/cup");

// default Plugin//

$name = "discord";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","discord");
DeleteData("navigation_dashboard_links","modulname","discord");
DeleteData("navigation_website_sub","modulname","discord");
DeleteData("settings_module","modulname","discord");
DeleteData("settings_widgets","modulname","discord");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_discord");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/discord");

$name = "facebook";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","facebook");
DeleteData("navigation_dashboard_links","modulname","facebook");
DeleteData("navigation_website_sub","modulname","facebook");
DeleteData("settings_module","modulname","facebook");
DeleteData("settings_widgets","modulname","facebook");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_facebook");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_facebook_content");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/facebook");

$name = "facts";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","facts");
DeleteData("navigation_dashboard_links","modulname","facts");
DeleteData("navigation_website_sub","modulname","facts");
DeleteData("settings_module","modulname","facts");
DeleteData("settings_widgets","modulname","facts");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_facts");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/facts");

$name = "faq";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","faq");
DeleteData("navigation_dashboard_links","modulname","faq");
DeleteData("navigation_website_sub","modulname","faq");
DeleteData("settings_module","modulname","faq");
DeleteData("settings_widgets","modulname","faq");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_faq");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_faq_categories");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/faq");

$name = "features";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","features");
DeleteData("navigation_dashboard_links","modulname","features");
DeleteData("navigation_website_sub","modulname","features");
DeleteData("settings_module","modulname","features");
DeleteData("settings_widgets","modulname","features");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_features");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/features");

$name = "features_box";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","features_box");
DeleteData("navigation_dashboard_links","modulname","features_box");
DeleteData("navigation_website_sub","modulname","features_box");
DeleteData("settings_module","modulname","features_box");
DeleteData("settings_widgets","modulname","features_box");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_features_box");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/features_box");

$name = "fightus";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","fightus");
DeleteData("settings_plugins","modulname","fightus_maps");
DeleteData("settings_plugins","modulname","fightus_spieleranzahl");
DeleteData("settings_plugins","modulname","fightus_matchtype");
DeleteData("settings_plugins","modulname","fightus_gametype");

DeleteData("navigation_dashboard_links","modulname","fightus");
DeleteData("navigation_website_sub","modulname","fightus");
DeleteData("settings_module","modulname","fightus");
DeleteData("settings_widgets","modulname","fightus");

safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_matchtype");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_gametype");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_spieleranzahl");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_challenge");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_fight_us_maps");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/fightus");

$name = "files";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","files");
DeleteData("navigation_dashboard_links","modulname","files");
DeleteData("navigation_website_sub","modulname","files");
DeleteData("settings_module","modulname","files");
DeleteData("settings_widgets","modulname","files");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_files");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_files_categories");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_files_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/files");

$name = "footer";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","footer");
DeleteData("navigation_dashboard_links","modulname","footer");
DeleteData("navigation_website_sub","modulname","footer");
DeleteData("settings_module","modulname","footer");
DeleteData("settings_widgets","modulname","footer");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_footer");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_footer_target");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/footer");

$name = "forum";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","forum");
DeleteData("settings_plugins","modulname","forum_topic");
DeleteData("navigation_dashboard_links","modulname","forum");
DeleteData("navigation_website_sub","modulname","forum");
DeleteData("settings_module","modulname","forum");
DeleteData("settings_module","modulname","forum_topic");
DeleteData("settings_widgets","modulname","forum");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_announcements");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_boards");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_categories");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_notify");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_posts");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_topics");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_poll");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_votes");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_groups");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_moderators");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_posts_spam");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_ranks");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_topics_spam");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_forum_user_forum_groups");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/forum");



$name = "all_plugins";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","all_plugins");
DeleteData("navigation_dashboard_links","modulname","all_plugins");
DeleteData("settings_module","modulname","all_plugins");
DeleteData("settings_widgets","modulname","all_plugins");
#DeleteFolderFiles("all_plugins");




/**
    * Loescht Dateien und Ordner innerhalb eines Ordners
    * 
    * @param string $file Pfad zum Ordner, welcher geloescht werden soll
    * @return nix
    */
    function DeleteFolderFiles($file) { 

        // Dateiberechtigung auf Vollzugriff stellen
        chmod($file,0777); 

        // Pruefen ob es ein Ordner ist
        if (is_dir($file)) { 

            // Resource oeffnen
            $resource = opendir($file); 

            // Rekursiv durch den Ordner durchgehen
            while($filename = readdir($resource)) { 

                // uebergeordnete, welche zur Navigation dienen, werden ignoriert
                if ($filename != "." && $filename != "..") { 

                    // Datei innerhalb des Ordners loeschen
                    DeleteFolderFiles($file."/".$filename); 
                } 
            } 

            // Resource schliessen
            closedir($resource); 

            // Ordner loeschen
            rmdir($file); 
        } else { 
            // Wenn es sich nicht um einen Ordner handelt -> Datei loeschen
            unlink($file); 
        }
    } 

    // Funktion ausfueren
    // Hierbei wird am besten der absolute Pfad des zu loeschenden Ordners angegeben
    DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/$name"); 


?>