<?php


$name = "gallery";
$name1 = "usergallery";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","gallery");
DeleteData("settings_plugins","modulname","usergallery");
DeleteData("navigation_dashboard_links","modulname","gallery");
DeleteData("navigation_website_sub","modulname","gallery");
DeleteData("settings_module","modulname","gallery");
DeleteData("settings_module","modulname","usergallery");
DeleteData("settings_widgets","modulname","gallery");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_gallery");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_gallery_groups");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_gallery_pictures");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_gallery_settings");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_gallery_comments");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/gallery");

$name = "game_server";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","game_server");
DeleteData("navigation_dashboard_links","modulname","game_server");
DeleteData("navigation_website_sub","modulname","game_server");
DeleteData("settings_module","modulname","game_server");
DeleteData("settings_widgets","modulname","game_server");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_game_server");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/game_server");

$name = "games_pic";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","games_pic");
DeleteData("navigation_dashboard_links","modulname","games_pic");
DeleteData("navigation_website_sub","modulname","games_pic");
DeleteData("settings_module","modulname","games_pic");
DeleteData("settings_widgets","modulname","games_pic");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_games_pic");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/games_pic");

$name = "gametracker_server";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","gametracker_server");
DeleteData("navigation_dashboard_links","modulname","gametracker_server");
DeleteData("navigation_website_sub","modulname","gametracker_server");
DeleteData("settings_module","modulname","gametracker_server");
DeleteData("settings_widgets","modulname","gametracker_server");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_gametracker_server");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/gametracker_server");

$name = "gametracker_ts";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","gametracker_ts");
DeleteData("navigation_dashboard_links","modulname","gametracker_ts");
DeleteData("navigation_website_sub","modulname","gametracker_ts");
DeleteData("settings_module","modulname","gametracker_ts");
DeleteData("settings_widgets","modulname","gametracker_ts");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_gametracker_ts");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/gametracker_ts");

$name = "guestbook";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","guestbook");
DeleteData("navigation_dashboard_links","modulname","guestbook");
DeleteData("navigation_website_sub","modulname","guestbook");
DeleteData("settings_module","modulname","guestbook");
DeleteData("settings_widgets","modulname","guestbook");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_guestbook");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/guestbook");

$name = "history";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","history");
DeleteData("navigation_dashboard_links","modulname","history");
DeleteData("navigation_website_sub","modulname","history");
DeleteData("settings_module","modulname","history");
DeleteData("settings_widgets","modulname","history");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_history");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/history");

$name = "instagram";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","instagram");
DeleteData("navigation_dashboard_links","modulname","instagram");
DeleteData("navigation_website_sub","modulname","instagram");
DeleteData("settings_module","modulname","instagram");
DeleteData("settings_widgets","modulname","instagram");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_instagram");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/instagram");

$name = "joinus";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","joinus");
DeleteData("navigation_dashboard_links","modulname","joinus");
DeleteData("navigation_website_sub","modulname","joinus");
DeleteData("settings_module","modulname","joinus");
DeleteData("settings_widgets","modulname","joinus");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_join_us");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/joinus");

$name = "lastlogin";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","lastlogin");
DeleteData("navigation_dashboard_links","modulname","lastlogin");
DeleteData("navigation_website_sub","modulname","lastlogin");
DeleteData("settings_module","modulname","lastlogin");
DeleteData("settings_widgets","modulname","lastlogin");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/lastlogin");

$name = "links";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","links");
DeleteData("navigation_dashboard_links","modulname","links");
DeleteData("navigation_website_sub","modulname","links");
DeleteData("settings_module","modulname","links");
DeleteData("settings_widgets","modulname","links");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_links");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_links_categories");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_links_settings");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/links");

$name = "linkus";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","linkus");
DeleteData("navigation_dashboard_links","modulname","linkus");
DeleteData("navigation_website_sub","modulname","linkus");
DeleteData("settings_module","modulname","linkus");
DeleteData("settings_widgets","modulname","linkus");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_linkus");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/linkus");

$name = "mc_status";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","mc_status");
DeleteData("navigation_dashboard_links","modulname","mc_status");
DeleteData("navigation_website_sub","modulname","mc_status");
DeleteData("settings_module","modulname","mc_status");
DeleteData("settings_widgets","modulname","mc_status");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_mc_status");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/mc_status");

$name = "media";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","media");
DeleteData("settings_module","modulname","media");
DeleteData("settings_widgets","modulname","media");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/media");

$name = "memberslist";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","memberslist");
DeleteData("navigation_dashboard_links","modulname","memberslist");
DeleteData("navigation_website_sub","modulname","memberslist");
DeleteData("settings_module","modulname","memberslist");
DeleteData("settings_widgets","modulname","memberslist");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_memberslist");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/memberslist");

$name = "messenger";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","messenger");
DeleteData("settings_module","modulname","messenger");
DeleteData("settings_widgets","modulname","messenger");
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_messenger");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/messenger");

$name = "all_plugins_two";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","all_plugins_two");
DeleteData("navigation_dashboard_links","modulname","all_plugins_two");
DeleteData("settings_module","modulname","all_plugins_two");
DeleteData("settings_widgets","modulname","all_plugins_two");
#DeleteFolderFiles("all_plugins_two");




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