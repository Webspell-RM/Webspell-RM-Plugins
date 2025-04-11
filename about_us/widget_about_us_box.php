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

// Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("about_sponsor", $plugin_path);
    
GLOBAL $logo, $theme_name, $themes;

// Über "about_us"-Daten abfragen
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);

    // Titel übersetzen
    $title = $ds['title'];
    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($title);
    $title = $translate->getTextByLanguage($title);

    // Text übersetzen
    $text = $ds['text'];
    $translate->detectLanguages($text);
    $text = $translate->getTextByLanguage($text);

    // Maximale Zeichenanzahl aus den Einstellungen holen
    $maxaboutchars = !empty($ds['aboutchars']) ? $ds['aboutchars'] : 200;  // Default auf 200, wenn leer

    // HTML-Tags entfernen
    $text = preg_replace("/<(div|p|strong|em|s|u|blockquote|img|span)[^>]*>/", "", $text);

    // Text kürzen und "Weiterlesen"-Link hinzufügen
    $text = substr($text, 0, $maxaboutchars) . ' ... <div class="text-end"> <a href="index.php?site=about_us" class="btn btn-dark btn-sm">READ MORE <i class="bi bi-chevron-double-right"></i></a></div>';

    // Logo abfragen
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."settings_expansion WHERE active = 1");
    $ds = mysqli_fetch_array($ergebnis);
    $logo = '<a class="navbar-brand float-start bg img-fluid" href="index.php"><img src="/includes/expansion/' . $ds['modulname'] . '/images/' . $ds['logo_pic'] . '" width="250px" alt=""></a>';

    // Social Media Links abfragen
    $socialLinks = [];
    $sortierung = 'socialID ASC';
    $ergebnis = safe_query("SELECT * FROM `".PREFIX."settings_social_media` ORDER BY $sortierung");
    if (mysqli_num_rows($ergebnis)) {
        while ($ds = mysqli_fetch_array($ergebnis)) {
            // Jede Social Media URL prüfen und einen Link erstellen, falls vorhanden
            foreach (['twitch', 'facebook', 'twitter', 'youtube', 'steam', 'discord'] as $social) {
                if (!empty($ds[$social])) {
                    $url = (stristr($ds[$social], "https://")) ? $ds[$social] : "http://" . $ds[$social];
                    $socialLinks[$social] = '<a class="nav-link ' . $social . '" href="' . htmlspecialchars($url) . '" target="_blank" rel="nofollow">' . ucfirst($social) . '</a>';
                } else {
                    $socialLinks[$social] = '';
                }
            }

            // Sichtbarkeit der Social Links prüfen
            foreach ($socialLinks as $key => $link) {
                $data_array['social' . substr($key, -1)] = $link === '' ? 'invisible' : 'visible';
            }
        }
    }

    // Template-Daten vorbereiten
    $data_array = array();
    $data_array['$text'] = $text;
    $data_array['$logo'] = $logo;
    $data_array['$steam'] = $socialLinks['steam'];
    $data_array['$discord'] = $socialLinks['discord'];
    $data_array['$twitch'] = $socialLinks['twitch'];
    $data_array['$facebook'] = $socialLinks['facebook'];
    $data_array['$twitter'] = $socialLinks['twitter'];
    $data_array['$youtube'] = $socialLinks['youtube'];

    // Template für den Inhalt anzeigen
    $about = $GLOBALS["_template"]->loadTemplate("about_us_box", "widget_content", $data_array, $plugin_path);
    echo $about;

} else {
    echo $plugin_language['no_about'];
}
?>