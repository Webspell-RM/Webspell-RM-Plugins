<?php
global $userID,$_database,$add_database_install,$add_database_insert;
global $str,$modulname,$info,$navi_name,$admin_file,$activate,$author,$website,$index_link,$hiddenfiles,$version,$path,$widget_link1,$widget_link2,$widget_link3,$widgetname1,$widgetname2,$widgetname3,$head_activated,$content_head_activated,$content_foot_activated,$head_section_activated,$foot_section_activated,$modul_deactivated,$modul_display,$full_activated,$plugin_settings,$plugin_module,$plugin_widget,$widget1,$widget2,$widget3,$mnavID,$navi_link,$catID,$dashnavi_link,$themes_modulname;
##### Install für Plugin und Module ###################################################################################################
$str                     =   "{[de]}Features{[en]}Features{[it]}Funzionalità";                    	// name of the plugin
$modulname               =   "features";                    	// name to uninstall
$info		             =   "{[de]}Mit diesem Plugin könnt ihr eure Features anzeigen lassen.{[en]}With this plugin you can display your Features.{[it]}Con questo plugin puoi visualizzare la Funzionalità.";// description of the plugin
$navi_name               =   "{[de]}Features{[en]}Features{[it]}Funzionalità";// name of the Webside Navigation / Dashboard Navigation
$admin_file              =   "admin_features_one";           	// administration file
$activate                =   "1";                             // plugin activate 1 yes | 0 no
$author                  =   "T-Seven";                       // author
$website                 =   "https://webspell-rm.de";        // authors website
$index_link              =   "";     						              // index file (without extension, also no .php)
$hiddenfiles             =   "";                              // hiddenfiles (background working, no display anywhere)
$version                 =   "0.1";                           // current version, visit authors website for updates, fixes, ..
$path                    =   "includes/plugins/features/";  	// plugin files location
##### Widget Setting ##################################################################################################################
$widget_link1            =   "widget_features_one_content"; // widget_file (visible as module/box)
$widget_link2            =   "";     	                      // widget_file (visible as module/box)
$widget_link3            =   "";                            // widget_file (visible as module/box)
$widgetname1             =   "Features One Content";        // widget_name (visible as module/box)
$widgetname2             =   "";            	              // widget_name (visible as module/box)
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
$plugin_widget           =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget1                 =   "1";                           //Modulsetting activate 1 yes | 0 no 
$widget2                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
$widget3                 =   "0";                           //Modulsetting activate 1 yes | 0 no 
##### Navigation Link #################################################################################################################
$mnavID                  =   "";                            // navigation category
$navi_link               =   "";                            // navigation link file (index.php?site=...)
$catID                   =   "7";                           // dashboard_navigation category
$dashnavi_link           =   "admin_features_one";          // dashboard_navigation link file  (admincenter.php?site==...)
$themes_modulname        =   "default";
#######################################################################################################################################
if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }
$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($str);
$str = $translate->getTextByLanguage($str);   
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################

add_database_install($add_database_install = "CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_features` (
  `featuresID` int(11) NOT NULL AUTO_INCREMENT,
  `title_one` varchar(255) NOT NULL DEFAULT '',
  `text_one` text NOT NULL,
  `title_two` varchar(255) NOT NULL,
  `text_two` text NOT NULL,
  `title_three` varchar(255) NOT NULL,
  `text_three` text NOT NULL,
  `title_four` varchar(255) NOT NULL,
  `text_four` text NOT NULL,
  `title_five` varchar(255) NOT NULL,
  `text_five` text NOT NULL,
  `title_six` varchar(255) NOT NULL,
  `text_six` text NOT NULL,
  `features_box_chars` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '160',
  PRIMARY KEY (`featuresID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

add_database_install($add_database_install = "INSERT IGNORE INTO `".PREFIX."plugins_features` (`featuresID`, `title_one`, `text_one`, `title_two`, `text_two`, `title_three`, `text_three`, `title_four`, `text_four`, `title_five`, `text_five`, `title_six`, `text_six`, `features_box_chars`) VALUES
(1, '{[en]}FULL responsive{[it]}PIENAMENTE reattivo{[de]}VOLLSTÄNDIG reaktionsfähig', '{[en]}The new version was adjusted with bootstrap so that it\'s possible to display your website perfect on any device. Test it now..{[it]}La nuova versione è stata adattata con bootstrap in modo che sia possibile visualizzare il tuo sito web perfettamente su qualsiasi dispositivo. Provalo adesso..{[de]}Die neue Version wurde mit Bootstrap angepasst, sodass eine perfekte Darstellung Ihrer Website auf jedem Gerät möglich ist. Testen Sie es jetzt..', '{[en]}Add-on & mods{[de]}Add-on und Mods{[it]}Componenti aggiuntivi e mod', '{[en]}With the Add-ons and modifications you can get your own individual system. Whether a navigation addon or a recaptcha mod, or, or, or.. {[it]}Con i componenti aggiuntivi e le modifiche puoi ottenere il tuo sistema personalizzato. Che si tratti di un componente aggiuntivo di navigazione o di un mod recaptcha, o, o, o..{[de]}Mit den Add-ons und Modifikationen erhalten Sie Ihr individuelles System. Ob ein Navigations-Addon oder ein Recaptcha-Mod, oder, oder, oder.. ', '{[en]}Community{[de]}Gemeinschaft{[it]}Comunità', '{[en]}If you have issues or problems. The community about Webspell-RM can help to solve a lots of problems.{[it]}Se hai problemi o problemi. La comunità su Webspell-RM può aiutare a risolvere molti problemi.{[de]}Wenn Sie Probleme oder Probleme haben. Die Community zu Webspell-RM kann bei der Lösung vieler Probleme helfen.', '{[en]}Plugin-Installer{[de]}Plugin-Installer{[it]}Installatore di plug-in', '{[en]}The new version was adjusted with bootstrap so that it\'s possible to display your website perfect on any device. Test it now..{[it]}La nuova versione è stata adattata con bootstrap in modo che sia possibile visualizzare il tuo sito web perfettamente su qualsiasi dispositivo. Provalo adesso..{[de]}Die neue Version wurde mit Bootstrap angepasst, sodass eine perfekte Darstellung Ihrer Website auf jedem Gerät möglich ist. Testen Sie es jetzt..', '{[de]}Vorlageninstallationsprogramm{[en]}Template-Installer{[it]}Programma di installazione dei modelli', '{[en]}With the Add-ons and modifications you can get your own individual system. Whether a navigation addon or a recaptcha mod, or, or, or.. {[it]}Con i componenti aggiuntivi e le modifiche puoi ottenere il tuo sistema personalizzato. Che si tratti di un componente aggiuntivo di navigazione o di un mod recaptcha, o, o, o..{[de]}Mit den Add-ons und Modifikationen erhalten Sie Ihr individuelles System. Ob ein Navigations-Addon oder ein Recaptcha-Mod, oder, oder, oder.. ', '{[en]}Community{[de]}Gemeinschaft{[it]}Comunità', '{[en]}If you have issues or problems. The community about Webspell-RM can help to solve a lots of problems.{[it]}Se hai problemi o problemi. La comunità su Webspell-RM può aiutare a risolvere molti problemi.{[de]}Wenn Sie Probleme oder Probleme haben. Die Community zu Webspell-RM kann bei der Lösung vieler Probleme helfen.', '100')");

get_add_module_install ();
get_add_plugin_manager();
get_add_dashboard_navigation ();

#######################################################################################################################################

echo "</div></div>";
	
 ?>