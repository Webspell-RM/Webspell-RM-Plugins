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
    $plugin_language = $pm->plugin_language("admin_about", $plugin_path);
global $get_test;

// Benutzerberechtigung prüfen
$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='aboutus'");
while ($db = mysqli_fetch_array($ergebnis)) {
    $accesslevel = 'is' . $db['accesslevel'] . 'admin';

    if (!$accesslevel($userID) || mb_substr(basename($_SERVER['REQUEST_URI']), 0, 15) != "admincenter.php") {
        die($plugin_language['access_denied']);
    }
}

if (isset($_POST['submit'])) {
    $title = htmlspecialchars($_POST["title"]);
    $text = htmlspecialchars($_POST['message']);
    $aboutchars = (int)$_POST['aboutchars']; // Überprüfen und sicherstellen, dass es eine Zahl ist

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        
        // Überprüfen, ob der Datensatz existiert und updaten oder einfügen
        if (mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_about_us"))) {
            safe_query("UPDATE " . PREFIX . "plugins_about_us SET title='" . $title . "',text='" . $text . "', aboutchars='" . $aboutchars . "' ");
        } else {
            safe_query("INSERT INTO " . PREFIX . "plugins_about_us (title, text, aboutchars) values ( '" . $title . "', '" . $text . "', '" . $aboutchars . "') ");
        }
        
        redirect("admincenter.php?site=admin_about_us", "", 1);
        echo $plugin_language['transaction_done'];
    } else {
        redirect("admincenter.php?site=admin_about_us", "", 1);
        echo $plugin_language['transaction_invalid'];
    }
} else {
    // Bestehende Daten aus der Datenbank abrufen
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
    $ds = mysqli_fetch_array($ergebnis);

    // Captcha-Hash für die Transaktion erstellen
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    // Wenn Daten existieren, diese in die Variablen einfügen, ansonsten leere Strings verwenden
    $title = !empty($ds['title']) ? $ds['title'] : '';
    $text = !empty($ds['text']) ? $ds['text'] : '';
    $aboutchars = !empty($ds['aboutchars']) ? $ds['aboutchars'] : '';

    // Template-Daten vorbereiten
    $date_array = array();
    $date_array['$hash'] = $hash;
    $date_array['$text'] = $text;
    $date_array['$title'] = $title;
    $date_array['$aboutchars'] = $aboutchars;

    #Language_array
    $date_array['$lang_about']=$plugin_language['about'];
    $date_array['$lang_title_head']=$plugin_language['title_head'];
    $date_array['$lang_description']=$plugin_language['description'];
    $date_array['$lang_max_sc']=$plugin_language['max_sc'];
    $date_array['$lang_update']=$plugin_language['update'];

    // Template laden und ausgeben
    $template = $GLOBALS["_template"]->loadTemplate("about", "admin_content", $date_array, $plugin_path);
    echo $template;
}
?>