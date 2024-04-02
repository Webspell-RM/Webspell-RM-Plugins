<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Games Pic{[en]}Games Pic{[it]}Immagini Giochi";                   // name of the plugin
$modulname               =   "games_pic";                   // name to uninstall
$info                    =   "{[de]}Mit diesem Plugin könnt ihr euch die Games Pic anzeigen lassen.{[en]}With this plugin you can display Games Pic of the users.{[it]}Con questo plugin puoi visualizzare le foto dei giochi. ";// description of the plugin
$navi_name               =   "{[de]}Games Pic{[en]}Games Pic{[it]}Immagini Giochi";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_games_pic";             // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "";                            // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/games_pic/"; // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "";                            // widget_file (visible as module/box)
$widget_link2            =   "";                            // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "";                            // widget_name (visible as module/box)
$widgetname2             =   "";                            // widget_name (visible as module/box)
$widgetname3             =   "";                            // widget_name (visible as module/box)
##### Modul Setting activate yes/no ###################################################################################################
$head_activated          =   "0";                           //Modul activate 1 yes | 0 no 
$content_head_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$content_foot_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$head_section_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$foot_section_activated  =   "0";                           //Modul activate 1 yes | 0 no 
$modul_deactivated       =   "0";                           //Modul activate 1 yes | 0 no
$modul_display           =   "1";                           //Modul activate 1 yes | 0 no
$full_activated          =   "0";                           //Modul activate 1 yes | 0 no
$plugin_settings         =   "1";                           //Modulsetting activate 1 yes | 0 no 
$plugin_module           =   "0";                           //Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "0";                           //Modulsetting activate 1 yes | 0 no
$widget1                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "";                           // navigation category
$navi_link               =   "";                           // navigation link file (index.php?site=...)
$catID                   =   "4";                          // dashboard_navigation category
$dashnavi_link           =   "admin_games_pic";            // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);
echo "<div class='card'><div class='card-header'>$str Database Updation</div><div class='card-body'>";
#######################################################################################################################################
# Versions-Nummer wird upgedatet
safe_query("UPDATE `".PREFIX."settings_plugins` SET version = '$version' WHERE `modulname` = '$modulname'");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_games_pic` (
  `gameID` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`gameID`)
) AUTO_INCREMENT=52
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_games_pic` (`gameID`, `tag`, `name`) VALUES
(1, 'apex_l', 'Apex Legends'),
(2, 'ark_se', 'ARK: Survival Evolved'),
(3, 'ac', 'Assetto Corsa'),
(4, 'bf_1', 'Battlefield'),
(5, 'bf_4', 'Battlefield 4'),
(6, 'bf_5', 'Battlefield 5'),
(7, 'bd', 'Black Desert'),
(8, 'cod_mw', 'Call of Duty: Modern Warfare'),
(9, 'cod_wz', 'Call of Duty: Warzone'),
(10, 'ce', 'Conan Exiles'),
(11, 'cs_go', 'Counter-Strike: GO'),
(12, 'cs_s', 'Counter-Strike: Source'),
(13, 'dbd', 'Dead by Daylight'),
(14, 'd_2', 'Destiny 2'),
(15, 'di_3', 'Diablo III'),
(16, 'dac', 'Dota Auto Chess'),
(17, 'do_2', 'Dota 2'),
(18, 'd_ul', 'Dota Underlords'),
(19, 'teso', 'The Elder Scrolls Online'),
(20, 'f1_2020', 'F1 2020'),
(21, 'fifa_20', 'FIFA 20'),
(22, 'ff_14', 'Final Fantasy XIV'),
(23, 'fort', 'Fortnite'),
(24, 'gta_on', 'Grand Theft Auto Online'),
(25, 'gw_2', 'Guild Wars 2'),
(26, 'hs_how', 'Hearthstone: Heroes of Warcraft'),
(27, 'h_sd', 'Hunt: Showdown'),
(28, 'lol', 'League of Legends'),
(29, 'lor', 'Legends of Runeterra'),
(30, 'mc', 'Minecraft'),
(31, 'mc_d', 'Minecraft Dungeons'),
(32, 'mh_w', 'Monster Hunter: World'),
(33, 'ow', 'Overwatch'),
(34, 'poe', 'Path of Exile'),
(35, 'pd_2', 'Payday 2'),
(36, 'pubg', 'Playerunknown\'s Battlegrounds'),
(37, 'rs_s', 'Rainbow Six: Siege'),
(38, 'rd_o', 'Red Dead Online'),
(39, 'rl', 'Rocket League'),
(40, 'smi', 'Smite'),
(41, 'sc2_wol', 'StarCraft II: Wings of Liberty'),
(42, 'swbf2', 'Star Wars Battlefront II'),
(43, 'swbf1', 'Star Wars: Battlefront'),
(44, 'tf_t', 'Teamfight Tactics'),
(45, 'td2', 'The Division 2'),
(46, 'vt', 'Valorant'),
(47, 'war_f', 'Warframe'),
(48, 'wc3_roc', 'Warcraft III: Reign of Chaos'),
(49, 'wc3_ref', 'Warcraft III: Reforged'),
(50, 'wot', 'World of Tanks'),
(51, 'wow', 'World of Warcraft'),
(52, 'forzah4', 'Forza Horizon 4'),
(53, 'forzah5', 'Forza Horizon 5'),
(54, 'cod_ghosts', 'Call of Duty: Ghosts'),
(55, 'cod_1', 'Call of Duty'),
(56, 'cod_uo', 'Call of Duty: United Offensive'),
(57, 'cities_sky', 'Cities: Skylines'),
(58, 'cod_ww2', 'Call of Duty: WWII'),
(59, 'ut', 'Unreal Tournament'),
(60, 'pc_1', 'Project CARS'),
(61, 'gt_7', 'Gran Turismo 7'),
(62, 'wows', 'World of Warships'),
(63, 'mfshead', 'Need for Speed Heat'),
(64, 'nfshpr', 'Need For Speed: Hot Pursuit Remastered'),
(65, 'xcomeu', 'XCOM: Enemy Unknown'),
(66, 'nfsmw2012', 'Need for Speed: Most Wanted'),
(67, 'nfsub', 'Need for Speed: Unbound'),
(68, '7dtd', '7 Days to Die'),
(69, 'nmrih', 'No More Room in Hell'),
(70, 'pz', 'Project Zomboid'),
(71, 'vlh', 'Valheim')");

get_add_module_install ();
get_add_plugin_manager();
#get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
    
 ?>