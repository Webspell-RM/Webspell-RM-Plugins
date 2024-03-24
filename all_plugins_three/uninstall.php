<?php


$name = "news_manager";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","news_manager");
DeleteData("navigation_dashboard_links","modulname","news_manager");
DeleteData("navigation_website_sub","modulname","news_manager");
DeleteData("navigation_website_sub","modulname","news_archive");
DeleteData("settings_module","modulname","news_manager");
DeleteData("settings_widgets","modulname","news_manager");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_rubrics");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_comments");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_news_comments_recomment");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/news_manager");

$name = "newsletter";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","newsletter");
DeleteData("navigation_dashboard_links","modulname","newsletter");
DeleteData("navigation_website_sub","modulname","newsletter");
DeleteData("settings_module","modulname","newsletter");
DeleteData("settings_widgets","modulname","newsletter");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_newsletter");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/newsletter");

$name = "nor_box";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","nor_box");
DeleteData("settings_module","modulname","nor_box");
DeleteData("settings_widgets","modulname","nor_box");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/nor_box");

$name = "parallax";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","parallax");
DeleteData("navigation_dashboard_links","modulname","parallax");
DeleteData("navigation_website_sub","modulname","parallax");
DeleteData("settings_module","modulname","parallax");
DeleteData("settings_widgets","modulname","parallax");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_parallax");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/parallax_header");

$name = "partners";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","partners");
DeleteData("navigation_dashboard_links","modulname","partners");
DeleteData("navigation_website_sub","modulname","partners");
DeleteData("settings_module","modulname","partners");
DeleteData("settings_widgets","modulname","partners");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_partners");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_partners_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/partners");

$name = "picupdate";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","picupdate");
DeleteData("navigation_dashboard_links","modulname","picupdate");
DeleteData("navigation_website_sub","modulname","picupdate");
DeleteData("settings_module","modulname","picupdate");
DeleteData("settings_widgets","modulname","picupdate");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_pic_update");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/picupdate");

$name = "planning";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","planning");
DeleteData("navigation_dashboard_links","modulname","planning");
DeleteData("navigation_website_sub","modulname","planning");
DeleteData("settings_module","modulname","planning");
DeleteData("settings_widgets","modulname","planning");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_planning");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/planning");

$name = "polls";
$name2 = "polls_votes";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","polls");
DeleteData("navigation_dashboard_links","modulname","polls");
DeleteData("navigation_website_sub","modulname","polls");
DeleteData("settings_module","modulname","polls");
DeleteData("settings_widgets","modulname","polls");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_polls");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_polls_votes");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_polls_comments");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/polls");

$name = "projectlist";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","projectlist");
DeleteData("navigation_dashboard_links","modulname","projectlist");
DeleteData("navigation_website_sub","modulname","projectlist");
DeleteData("settings_module","modulname","projectlist");
DeleteData("settings_widgets","modulname","projectlist");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_projectlist");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_projectlist_categories");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_projectlist_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/projectlist");

$name = "portfolio";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","portfolio");
DeleteData("navigation_dashboard_links","modulname","portfolio");
DeleteData("navigation_website_sub","modulname","portfolio");
DeleteData("settings_module","modulname","portfolio");
DeleteData("settings_widgets","modulname","portfolio");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_portfolio");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_portfolio_categories");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/portfolio");

$name = "projectslider";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","projectslider");
DeleteData("navigation_dashboard_links","modulname","projectslider");
DeleteData("navigation_website_sub","modulname","projectslider");
DeleteData("settings_module","modulname","projectslider");
DeleteData("settings_widgets","modulname","projectslider");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_projectslider");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/projectslider");

$name = "search";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","search");
DeleteData("navigation_dashboard_links","modulname","search");
DeleteData("navigation_website_sub","modulname","search");
DeleteData("settings_module","modulname","search");
DeleteData("settings_widgets","modulname","search");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/search");

$name = "server_rules";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","server_rules");
DeleteData("navigation_dashboard_links","modulname","server_rules");
DeleteData("navigation_website_sub","modulname","server_rules");
DeleteData("settings_module","modulname","server_rules");
DeleteData("settings_widgets","modulname","server_rules");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_server_rules");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_server_rules_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/server_rules");

$name = "servers";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","servers");
DeleteData("navigation_dashboard_links","modulname","servers");
DeleteData("navigation_website_sub","modulname","servers");
DeleteData("settings_module","modulname","servers");
DeleteData("settings_widgets","modulname","servers");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_servers");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_servers_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/servers");

$name = "shoutbox";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","shoutbox");
DeleteData("navigation_dashboard_links","modulname","shoutbox");
DeleteData("navigation_website_sub","modulname","shoutbox");
DeleteData("settings_module","modulname","shoutbox");
DeleteData("settings_widgets","modulname","shoutbox");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_shoutbox");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_shoutbox_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/shoutbox");

$name = "socialmedia";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","socialmedia");
DeleteData("navigation_dashboard_links","modulname","socialmedia");
DeleteData("navigation_website_sub","modulname","socialmedia");
DeleteData("settings_module","modulname","socialmedia");
DeleteData("settings_widgets","modulname","socialmedia");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_socialmedia");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/socialmedia");

$name = "sponsors";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","sponsors");
DeleteData("navigation_dashboard_links","modulname","sponsors");
DeleteData("navigation_website_sub","modulname","sponsors");
DeleteData("settings_module","modulname","sponsors");
DeleteData("settings_widgets","modulname","sponsors");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_sponsors");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_sponsors_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/sponsors");

$name = "squads";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","squads");
DeleteData("navigation_dashboard_links","modulname","squads");
DeleteData("navigation_website_sub","modulname","squads");
DeleteData("settings_module","modulname","squads");
DeleteData("settings_widgets","modulname","squads");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_squads");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_squads_members");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_squads_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/squads");

$name = "streams";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","streams");
DeleteData("navigation_dashboard_links","modulname","streams");
DeleteData("navigation_website_sub","modulname","streams");
DeleteData("settings_module","modulname","streams");
DeleteData("settings_widgets","modulname","streams");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_streams");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/streams");

$name = "summary";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","summary");
DeleteData("settings_module","modulname","summary");
DeleteData("settings_widgets","modulname","summary");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/summary");

$name = "tags";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","tags");
DeleteData("settings_module","modulname","tags");
DeleteData("settings_widgets","modulname","tags");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/tags");

$name = "textslider";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","textslider");
DeleteData("navigation_dashboard_links","modulname","textslider");
DeleteData("navigation_website_sub","modulname","textslider");
DeleteData("settings_module","modulname","textslider");
DeleteData("settings_widgets","modulname","textslider");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_textslider");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/textslider");

$name = "ticketcenter";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","ticketcenter");
DeleteData("navigation_dashboard_links","modulname","ticketcenter");
DeleteData("navigation_website_sub","modulname","ticketcenter");
DeleteData("settings_module","modulname","ticketcenter");
DeleteData("settings_widgets","modulname","ticketcenter");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_tickets");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_tickets_categories");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/ticketcenter");

$name = "todo";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","todo");
DeleteData("navigation_dashboard_links","modulname","todo");
DeleteData("navigation_website_sub","modulname","todo");
DeleteData("settings_module","modulname","todo");
DeleteData("settings_widgets","modulname","todo");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_todo");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/todo");

$name = "topbar";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","topbar");
DeleteData("navigation_dashboard_links","modulname","topbar");
DeleteData("navigation_website_sub","modulname","topbar");
DeleteData("settings_module","modulname","topbar");
DeleteData("settings_widgets","modulname","topbar");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_todo");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/topbar");

$name = "ts3viewer";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","ts3viewer");
DeleteData("navigation_dashboard_links","modulname","ts3viewer");
DeleteData("navigation_website_sub","modulname","ts3viewer");
DeleteData("settings_module","modulname","ts3viewer");
DeleteData("settings_widgets","modulname","ts3viewer");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_ts3viewer");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/ts3viewer");

//ts3admin//

$name = "tsviewer";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","tsviewer");
DeleteData("navigation_dashboard_links","modulname","tsviewer");
DeleteData("navigation_website_sub","modulname","tsviewer");
DeleteData("settings_module","modulname","tsviewer");
DeleteData("settings_widgets","modulname","tsviewer");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_tsviewer");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/tsviewer");

$name = "twitter";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","twitter");
DeleteData("navigation_dashboard_links","modulname","twitter");
DeleteData("navigation_website_sub","modulname","twitter");
DeleteData("settings_module","modulname","twitter");
DeleteData("settings_widgets","modulname","twitter");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_twitter");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/twitter");

$name = "useraward";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","useraward");
DeleteData("navigation_dashboard_links","modulname","useraward");
DeleteData("navigation_website_sub","modulname","useraward");
DeleteData("settings_module","modulname","useraward");
DeleteData("settings_widgets","modulname","useraward");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_user_award");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_user_award_list");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_user_award_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/useraward");

$name = "userlist";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","userlist");
DeleteData("navigation_dashboard_links","modulname","userlist");
DeleteData("navigation_website_sub","modulname","userlist");
DeleteData("settings_module","modulname","userlist");
DeleteData("settings_widgets","modulname","userlist");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_userlist");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/userlist");

$name = "useronline";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","useronline");
DeleteData("navigation_dashboard_links","modulname","useronline");
DeleteData("navigation_website_sub","modulname","useronline");
DeleteData("settings_module","modulname","useronline");
DeleteData("settings_widgets","modulname","useronline");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_useronline");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/useronline");

$name = "userrights";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","userrights");
DeleteData("navigation_dashboard_links","modulname","userrights");
DeleteData("navigation_website_sub","modulname","userrights");
DeleteData("settings_module","modulname","userrights");
DeleteData("settings_widgets","modulname","userrights");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_userrights");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/userrights");

$name = "videos";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","videos");
DeleteData("navigation_dashboard_links","modulname","videos");
DeleteData("navigation_website_sub","modulname","videos");
DeleteData("settings_module","modulname","videos");
DeleteData("settings_widgets","modulname","videos");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_videos");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_videos_categories");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_videos_comments");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/videos");

$name = "whoisonline";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","whoisonline");
DeleteData("navigation_dashboard_links","modulname","whoisonline");
DeleteData("navigation_website_sub","modulname","whoisonline");
DeleteData("settings_module","modulname","whoisonline");
DeleteData("settings_widgets","modulname","whoisonline");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/whoisonline");


$name = "all_plugins_three";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","all_plugins_three");
DeleteData("navigation_dashboard_links","modulname","all_plugins_three");
DeleteData("settings_module","modulname","all_plugins_three");
DeleteData("settings_widgets","modulname","all_plugins_three");
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