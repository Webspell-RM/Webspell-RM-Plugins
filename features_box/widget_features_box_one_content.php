<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$plugin_language = $pm->plugin_language("features_one_box", $plugin_path);

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_features_box");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);

    	

        $title_one = $ds[ 'title_one' ];
        $title_small_one = $ds[ 'title_small_one' ];
        $text_one = $ds[ 'text_one' ];

        $title_two = $ds[ 'title_two' ];
        $title_small_two = $ds[ 'title_small_two' ];
        $text_two = $ds[ 'text_two' ];

        $title_three = $ds[ 'title_three' ];
        $title_small_three = $ds[ 'title_small_three' ];
        $text_three = $ds[ 'text_three' ];

		$maxfeaturesboxchars = $ds[ 'features_box_chars' ];
        if (empty($maxfeaturesboxchars)) {
        $maxfeaturesboxchars = 160;
        }  
        if (mb_strlen($text_one) > $maxfeaturesboxchars) {
        $text_one = mb_substr($text_one, 0, $maxfeaturesboxchars);
        $text_one .= '...';
        }
        if (mb_strlen($text_two) > $maxfeaturesboxchars) {
        $text_two = mb_substr($text_two, 0, $maxfeaturesboxchars);
        $text_two .= '...';
        }
        if (mb_strlen($text_three) > $maxfeaturesboxchars) {
        $text_three = mb_substr($text_three, 0, $maxfeaturesboxchars);
        $text_three .= '...';
        }
            
        $data_array = array();
        $data_array['$title_one'] = $title_one;
        $data_array['$title_small_one'] = $title_small_one;
        $data_array['$text_one'] = $text_one;

        $data_array['$title_two'] = $title_two;
        $data_array['$title_small_two'] = $title_small_two;
        $data_array['$text_two'] = $text_two;

        $data_array['$title_three'] = $title_three;
        $data_array['$title_small_three'] = $title_small_three;
        $data_array['$text_three'] = $text_three;

$template = $GLOBALS["_template"]->loadTemplate("features_one_box","content", $data_array, $plugin_path);
echo $template;

} else {
        
    echo $plugin_language[ 'no_about' ];
}