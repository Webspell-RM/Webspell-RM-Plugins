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
 * @copyright       2018-2025 by webspell-rm.de                                                              *
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

// Sprachdateien laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("about", $plugin_path);

// Template für Kopfbereich laden
$data_array = [];
$about_sponsor = $GLOBALS["_template"]->loadTemplate("about_sponsor", "head", $data_array, $plugin_path);
echo $about_sponsor;

// Daten aus der Datenbank holen
$plugin_languageuage->readModule('about');
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");

if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);
    $text = $ds['text'];
    $title = $ds['title'];

    // Text übersetzen
    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($title);
    $title = $translate->getTextByLanguage($title);
    $translate->detectLanguages($text);
    $text = $translate->getTextByLanguage($text);

    // Maximale Zeichenanzahl aus den Einstellungen holen
    $maxaboutchars = !empty($ds['aboutchars']) ? $ds['aboutchars'] : 200;  // Default auf 200, wenn leer

    // Entfernen von HTML-Tags
    $text = preg_replace("/<(div|p|strong|em|s|u|blockquote|img|span)[^>]*>/", "", $text);

    // Text kürzen und "Weiterlesen"-Link hinzufügen
    $text = substr($text, 0, $maxaboutchars) . ' ... <br><br><div class="text-end"><a href="index.php?site=about_us" class="btn btn-dark btn-sm">READ MORE <i class="bi bi-chevron-double-right"></i></a></div>';

    // Template-Daten vorbereiten
    $data_array = array();
    $data_array['$text'] = $text;

} else {
    $text = $plugin_language['no_about'];
}

// Template für Inhalt (Über uns) laden und anzeigen
$about_sponsor = $GLOBALS["_template"]->loadTemplate("about_sponsor", "contentabout", $data_array, $plugin_path);
echo $about_sponsor;

// Sponsoren abfragen
$mainsponsors = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors WHERE displayed = '1' AND mainsponsor = '1' ORDER BY sort");

if (mysqli_num_rows($mainsponsors)) {
    $main_title = (mysqli_num_rows($mainsponsors) == 1) ? $plugin_language['mainsponsor'] : $plugin_language['mainsponsors'];

    if ($da = mysqli_fetch_array($mainsponsors)) {
        if (!empty($da[ 'banner' ])) {
            $sponsor = '<img src="/includes/plugins/sponsors/images/' . $da[ 'banner' ] . '" alt="' . htmlspecialchars($da[ 'name' ]) . '" class="img-fluid">';
        } else {
            $sponsor = $da[ 'name' ];
        }

        $sponsorID = $da['sponsorID'];
        $url = $da['url'];
        // Entfernen von HTML-Tags
        $text = preg_replace("/<(div|p|strong|em|s|u|blockquote|img|span)[^>]*>/", "", $da['info']);

        // Text kürzen und "Weiterlesen"-Link hinzufügen
        $text = substr($text, 0, $maxaboutchars) . ' ...';

        // Template-Daten für Sponsoren vorbereiten
        $data_array = array();
        $data_array['$sponsorID'] = $sponsorID;
        $data_array['$sponsor'] = $sponsor;
        $data_array['$name'] = $da['name'];
        $data_array['$url'] = $url;
        $data_array['$info'] = $text;

        $data_array['$sponsor_title']=$plugin_language['sponsor_title'];

        // Template für Sponsoren anzeigen
        $about_sponsor = $GLOBALS["_template"]->loadTemplate("about_sponsor", "contentsponsor", $data_array, $plugin_path);
        echo $about_sponsor;
    }
}

// Template für Footer laden und anzeigen
$about_sponsor = $GLOBALS["_template"]->loadTemplate("about_sponsor", "foot", $data_array, $plugin_path);
echo $about_sponsor;
?>