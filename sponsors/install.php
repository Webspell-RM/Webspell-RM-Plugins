<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Sponsoren{[en]}Sponsors{[it]}Sponsor";                	  // name of the plugin
$modulname               =   "sponsors";                  	// name to uninstall
$info	                   =   "{[de]}Mit diesem Plugin könnt ihr eure Sponsoren anzeigen lassen.{[en]}With this plugin you can display your sponsors.{[it]}Con questo plugin puoi visualizzare i tuoi sponsor.";// description of the plugin
$navi_name               =   "{[de]}Sponsoren{[en]}Sponsors{[it]}Sponsor";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_sponsors";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "sponsors,admin_sponsors"; 	  // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.2";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/sponsors/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_sponsors_sidebar";     // widget_file (visible as module/box)
$widget_link2            =   "widget_sponsors_content_one"; // widget_file (visible as module/box)
$widget_link3            =   "widget_sponsors_content_two"; // widget_file (visible as module/box)
$widgetname1             =   "Sponsors Sidebar";            // widget_name (visible as module/box)
$widgetname2             =   "Sponsors Content One";        // widget_name (visible as module/box)
$widgetname3             =   "Sponsors Content Two";        // widget_name (visible as module/box)
##### Modul Setting activate yes/no ###################################################################################################
$head_activated          =   "0";                           // Modul activate 1 yes | 0 no 
$content_head_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$content_foot_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$head_section_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$foot_section_activated  =   "0";                           // Modul activate 1 yes | 0 no 
$modul_deactivated       =   "0";                           // Modul activate 1 yes | 0 no
$modul_display           =   "1";                           // Modul activate 1 yes | 0 no
$full_activated          =   "0";                           // Modul activate 1 yes | 0 no
$plugin_settings         =   "1";                           // Modulsetting activate 1 yes | 0 no 
$plugin_module           =   "1";                           // Modulsetting activate 1 yes | 0 no 
$plugin_widget           =   "1";                           // Modulsetting activate 1 yes | 0 no 
$widget1                 =   "1";                           // Modulsetting activate 1 yes | 0 no 
$widget2                 =   "1";                           // Modulsetting activate 1 yes | 0 no 
$widget3                 =   "1";                           // Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "4";                           // navigation category
$navi_link               =   "sponsors";                    // navigation link file (index.php?site=...)
$catID                   =   "12";                          // dashboard_navigation category
$dashnavi_link           =   "admin_sponsors";              // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_sponsors` (
  `sponsorID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `banner` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '1',
  `banner_small` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `displayed` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `mainsponsor` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `hits` int(11) DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sponsorID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_sponsors_settings` (
  `sponsorssetID` int(11) NOT NULL AUTO_INCREMENT,
  `sponsors` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sponsorssetID`)
) AUTO_INCREMENT=2
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_sponsors` (`sponsorID`, `name`, `url`, `info`, `banner`, `sort`, `banner_small`, `displayed`, `mainsponsor`, `hits`, `date`) VALUES
(1, 'Sponsor 1', 'https://www.webspell-rm.de', 'Hallo. Ich bin ein kleiner Blindtext. Und zwar schon so lange ich denken kann. Es war nicht leicht zu verstehen, was es bedeutet, ein blinder Text zu sein: Man ergibt keinen Sinn. Wirklich keinen Sinn. Man wird zusammenhangslos eingeschoben und rumgedreht &ndash; und oftmals gar nicht erst gelesen. Aber bin ich allein deshalb ein schlechterer Text als andere? Na gut, ich werde nie in den Bestsellerlisten stehen. Aber andere Texte schaffen das auch nicht. Und darum st&ouml;rt es mich nicht besonders blind zu sein. Und sollten Sie diese Zeilen noch immer lesen, so habe ich als kleiner Blindtext etwas geschafft, wovon all die richtigen und wichtigen Texte meist nur tr&auml;umen.', '1.png', 1, '1_small.png', '1', '0', 0, 1692376041),
(2, 'Sponsor 2', 'https://www.webspell-rm.de', 'Hallo. Ich bin ein kleiner Blindtext. Und zwar schon so lange ich denken kann. Es war nicht leicht zu verstehen, was es bedeutet, ein blinder Text zu sein: Man ergibt keinen Sinn. Wirklich keinen Sinn. Man wird zusammenhangslos eingeschoben und rumgedreht &ndash; und oftmals gar nicht erst gelesen. Aber bin ich allein deshalb ein schlechterer Text als andere? Na gut, ich werde nie in den Bestsellerlisten stehen. Aber andere Texte schaffen das auch nicht. Und darum st&ouml;rt es mich nicht besonders blind zu sein. Und sollten Sie diese Zeilen noch immer lesen, so habe ich als kleiner Blindtext etwas geschafft, wovon all die richtigen und wichtigen Texte meist nur tr&auml;umen.', '2.png', 1, '2_small.png', '1', '0', 0, 1692376062),
(3, 'Sponsor 3', 'https://www.webspell-rm.de', 'Hallo. Ich bin ein kleiner Blindtext. Und zwar schon so lange ich denken kann. Es war nicht leicht zu verstehen, was es bedeutet, ein blinder Text zu sein: Man ergibt keinen Sinn. Wirklich keinen Sinn. Man wird zusammenhangslos eingeschoben und rumgedreht &ndash; und oftmals gar nicht erst gelesen. Aber bin ich allein deshalb ein schlechterer Text als andere? Na gut, ich werde nie in den Bestsellerlisten stehen. Aber andere Texte schaffen das auch nicht. Und darum st&ouml;rt es mich nicht besonders blind zu sein. Und sollten Sie diese Zeilen noch immer lesen, so habe ich als kleiner Blindtext etwas geschafft, wovon all die richtigen und wichtigen Texte meist nur tr&auml;umen.', '3.png', 1, '3_small.png', '1', '0', 0, 1692376084),
(4, 'Sponsor 4', 'https://www.webspell-rm.de', 'Hallo. Ich bin ein kleiner Blindtext. Und zwar schon so lange ich denken kann. Es war nicht leicht zu verstehen, was es bedeutet, ein blinder Text zu sein: Man ergibt keinen Sinn. Wirklich keinen Sinn. Man wird zusammenhangslos eingeschoben und rumgedreht &ndash; und oftmals gar nicht erst gelesen. Aber bin ich allein deshalb ein schlechterer Text als andere? Na gut, ich werde nie in den Bestsellerlisten stehen. Aber andere Texte schaffen das auch nicht. Und darum st&ouml;rt es mich nicht besonders blind zu sein. Und sollten Sie diese Zeilen noch immer lesen, so habe ich als kleiner Blindtext etwas geschafft, wovon all die richtigen und wichtigen Texte meist nur tr&auml;umen.', '4.png', 1, '4_small.png', '1', '0', 0, 1692376106),
(5, 'Sponsor 5', 'https://www.webspell-rm.de', 'Hallo. Ich bin ein kleiner Blindtext. Und zwar schon so lange ich denken kann. Es war nicht leicht zu verstehen, was es bedeutet, ein blinder Text zu sein: Man ergibt keinen Sinn. Wirklich keinen Sinn. Man wird zusammenhangslos eingeschoben und rumgedreht &ndash; und oftmals gar nicht erst gelesen. Aber bin ich allein deshalb ein schlechterer Text als andere? Na gut, ich werde nie in den Bestsellerlisten stehen. Aber andere Texte schaffen das auch nicht. Und darum st&ouml;rt es mich nicht besonders blind zu sein. Und sollten Sie diese Zeilen noch immer lesen, so habe ich als kleiner Blindtext etwas geschafft, wovon all die richtigen und wichtigen Texte meist nur tr&auml;umen.', '5.png', 1, '5_small.png', '1', '0', 0, 1692376126)"); 


add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_sponsors_settings` (`sponsorssetID`, `sponsors`) VALUES (1, 5)");  

get_add_module_install ();
get_add_plugin_manager();
get_add_navigation();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>