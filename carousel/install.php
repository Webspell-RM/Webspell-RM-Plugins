<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Slideshow{[en]}Carousel{[it]}Carosello Immagini";                    // name of the plugin
$modulname               =   "carousel";                    // name to uninstall
$info                    =   "{[de]}Webspell-RM Carousel ist das leistungsstärkste und benutzerfreundlichste Webspell-RM Carousel-Plugin zum Erstellen schöner Karussells mit Bildern. Mit diesem Plugin können Sie einfach Bilder hochladen und auswählen. Es ist vollständig reaktionsschnell, hochgradig anpassbar und funktioniert reibungslos auf iPhone, iPad, Android, Firefox, Chrome, Safari, Opera und Edge.{[en]}Webspell-RM Carousel is the most powerful and easy-to-use Webspell-RM Carousel plugin to create beautiful carousels with images. With this plugin you can easily upload and select images. It`s fully responsive, highly customizable, and works seamlessly on iPhone, iPad, Android, Firefox, Chrome, Safari, Opera, and Edge.{[it]}Webspell-RM Carousel è il plug-in Webspell-RM Carousel più potente e facile da usare per creare splendidi caroselli con immagini. Con questo plugin puoi caricare e selezionare facilmente le immagini. È completamente reattivo, altamente personalizzabile e funziona perfettamente su iPhone, iPad, Android, Firefox, Chrome, Safari, Opera ed Edge.";   // description of the plugin
$navi_name               =   "{[de]}Slideshow{[en]}Carousel{[it]}Carosello Immagini";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_carousel";              // administration file
$activate                =   "1";                           // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                     // author
$website                 =   "https://webspell-rm.de";      // authors website
$index_link              =   "";                            // index file (without extension, also no .php)
$hiddenfiles             =   "";                            // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                         // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/carousel/";  // plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_carousel_only";        // widget_file (visible as module/box)
$widget_link2            =   "widget_parallax_header";      // widget_file (visible as module/box)
$widget_link3            =   "widget_sticky_header";        // widget_file (visible as module/box)
$widgetname1             =   "Carousel Only";               // widget_name (visible as module/box)
$widgetname2             =   "Parallax Header";             // widget_name (visible as module/box)
$widgetname3             =   "Sticky Header";               // widget_name (visible as module/box)
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
$plugin_widget           =   "1";                           //Modulsetting activate 1 yes | 0 no
$widget1                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "";                            // navigation category
$navi_link               =   "";                            // navigation link file (index.php?site=...)
$catID                   =   "9";                           // dashboard_navigation category
$dashnavi_link           =   "admin_carousel";              // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel` (
  `carouselID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ani_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ani_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `ani_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `carousel_pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `carousel_vid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `time_pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '1',
  `displayed` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`carouselID`)
) AUTO_INCREMENT=4
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

safe_query("ALTER TABLE `".PREFIX."plugins_carousel` ADD COLUMN IF NOT EXISTS  carousel_vid varchar(255) NOT NULL DEFAULT '' AFTER `carousel_pic`");
safe_query("ALTER TABLE `".PREFIX."plugins_carousel` ADD COLUMN IF NOT EXISTS  time_pic varchar(255) NOT NULL DEFAULT '' AFTER `carousel_vid`");
        
add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_carousel` (`carouselID`, `title`, `ani_title`, `link`, `ani_link`, `description`, `ani_description`, `carousel_pic`, `carousel_vid`, `time_pic`, `sort`, `displayed`) VALUES
(1, 'The Best <span>Games</span> Out There', 'rollIn', 'http://webspell-rm.de/', 'fadeInRight', 'The Bootstrap Carousel in Webspell? No way?! Yes we did it!', 'fadeInUp', '1.jpg','', '5', 1, '1'),
(2, 'The Best <span>Games</span> Out There', 'fadeInDown', 'http://webspell-rm.de/', 'fadeInRight', 'The Bootstrap Carousel in Webspell? No way?! Yes we did it!', 'fadeInLeft', '2.jpg','', '5', 1, '1'),
(3, 'The Best <span>Games</span> Out There', 'fadeInUp', '', 'fadeInDown', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada\nlorem maximus mauris scelerisque, at rutrum nulla dictum. Ut ac ligula sapien.\nSuspendisse cursus faucibus finibus.', 'fadeInRight', '3.jpg','', '5', 1, '1'),
(4, 'The Best <span>Games</span> Out There', 'fadeInRightBig', 'http://', 'fadeInLeft', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada lorem maximus mauris scelerisque, at rutrum nulla dictum. Ut ac ligula sapien. Suspendisse cursus faucibus finibus.', 'fadeInUp', '4.jpg','', '5', 1, '1'),
(5, 'Call of Duty® <span>Black Ops 4</span>', 'fadeInRightBig', 'https://www.callofduty.com/it/blackops4/pc', 'fadeInLeft', 'https://www.callofduty.com/it/blackops4/pc', 'fadeInUp', '','5.mp4', '13', 1, '1')");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel_parallax` (
  `parallaxID` int(11) NOT NULL AUTO_INCREMENT,
  `parallax_pic` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`parallaxID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel_sticky` (
  `stickyID` int(11) NOT NULL AUTO_INCREMENT,
  `sticky_pic` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`stickyID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_carousel_settings` (
  `carouselID` int(11) NOT NULL AUTO_INCREMENT,
  `carousel_height` varchar(255) NOT NULL DEFAULT '0',
  `parallax_height` varchar(255) NOT NULL DEFAULT '0',
  `sticky_height` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`carouselID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_carousel_settings` (`carouselID`, `carousel_height`, `parallax_height`, `sticky_height`) VALUES
(1, '100vh', '100vh', '100vh')");

get_add_module_install ();
get_add_plugin_manager();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>