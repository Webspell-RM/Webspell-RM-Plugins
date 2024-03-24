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



$name = "all_plugins";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","all_plugins");
DeleteData("navigation_dashboard_links","modulname","all_plugins");
DeleteData("settings_module","modulname","all_plugins");
DeleteData("settings_widgets","modulname","all_plugins");
#DeleteFolderFiles("all_plugins");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/all_plugins");

$name = "all_plugins_two";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","all_plugins_two");
DeleteData("navigation_dashboard_links","modulname","all_plugins_two");
DeleteData("settings_module","modulname","all_plugins_two");
DeleteData("settings_widgets","modulname","all_plugins_two");
#DeleteFolderFiles("all_plugins_two");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/all_plugins_two");

$name = "all_plugins_three";
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname","all_plugins_three");
DeleteData("navigation_dashboard_links","modulname","all_plugins_three");
DeleteData("settings_module","modulname","all_plugins_three");
DeleteData("settings_widgets","modulname","all_plugins_three");
#DeleteFolderFiles("all_plugins");
DeleteFolderFiles($_SERVER["DOCUMENT_ROOT"] . "/includes/plugins/all_plugins_three");




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