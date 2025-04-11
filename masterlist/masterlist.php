<?php
/**
 * Game Server Masterlist Plugin für Webspell-RM
 * 
 * @version webspell-rm 2.1.6
 * @license GNU GENERAL PUBLIC LICENSE
 */

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$_lang = $pm->plugin_language("masterlist", $plugin_path);

$data_array = array();
$data_array['$title'] = 'Masterlist';
$data_array['$subtitle'] = 'Game Server Übersicht';
$template = $GLOBALS["_template"]->loadTemplate("masterlist", "head", $data_array, $plugin_path);
echo $template;

// Spielversion aus der Auswahl abrufen
$selected_version = isset($_GET['version']) ? str_replace('%2F', '/', $_GET['version']) : 'cod/1.1';

// API-Aufruf zur Serverliste
require_once 'fetch_servers.php';
$servers = fetchCodUoServers($selected_version);

// Versionen definieren
$versions = [
    "COD 1" => ["cod1/1.1", "cod1/1.2", "cod1/1.3", "cod1/1.4", "cod1/1.5"],
    "COD UO" => ["coduo/1.41", "coduo/1.51"],
    "COD 2" => ["cod2/1.0", "cod2/1.2", "cod2/1.3"],
    "COD 4" => ["cod4/1.0", "cod4/1.7", "cod4/1.8"]
];

// Anzeige der aktuellen Version 
$selected_version = isset($_GET['version']) ? str_replace('%2F', '/', htmlspecialchars($_GET['version'])) : 'coduo/1.51';

/* CoD 1 funktioniert noch nicht
$select_setting = '

<form method="GET">
<input type="hidden" name="site" value="masterlist">
    <label for="version">Version:</label>
    <select name="version" class="form-select border border-dark" onchange="this.form.submit()">
        <optgroup label="COD 1">
            <option value="cod1/1.1" ' . ($selected_version == 'cod1/1.1' ? 'selected' : '') . '>1.1</option>
            <option value="cod1/1.2" ' . ($selected_version == 'cod1/1.2' ? 'selected' : '') . '>1.2</option>
            <option value="cod1/1.3" ' . ($selected_version == 'cod1/1.3' ? 'selected' : '') . '>1.3</option>
            <option value="cod1/1.4" ' . ($selected_version == 'cod1/1.4' ? 'selected' : '') . '>1.4</option>
            <option value="cod1/1.5" ' . ($selected_version == 'cod1/1.5' ? 'selected' : '') . '>1.5</option>
        </optgroup>
        <optgroup label="COD UO">
            <option value="coduo/1.41" ' . ($selected_version == 'coduo/1.41' ? 'selected' : '') . '>1.41</option>
            <option value="coduo/1.51" ' . ($selected_version == 'coduo/1.51' ? 'selected' : '') . '>1.51</option>
        </optgroup>
        <optgroup label="COD 2">
            <option value="cod2/1.0" ' . ($selected_version == 'cod2/1.0' ? 'selected' : '') . '>1.0</option>
            <option value="cod2/1.2" ' . ($selected_version == 'cod2/1.2' ? 'selected' : '') . '>1.2</option>
            <option value="cod2/1.3" ' . ($selected_version == 'cod2/1.3' ? 'selected' : '') . '>1.3</option>
        </optgroup>
        <optgroup label="COD 4">
            <option value="cod4/1.0" ' . ($selected_version == 'cod4/1.0' ? 'selected' : '') . '>1.0</option>
            <option value="cod4/1.7" ' . ($selected_version == 'cod4/1.7' ? 'selected' : '') . '>1.7</option>
            <option value="cod4/1.8" ' . ($selected_version == 'cod4/1.8' ? 'selected' : '') . '>1.8</option>
        </optgroup>
    </select>
</form>
';*/

$select_setting = '

<form method="GET">
<input type="hidden" name="site" value="masterlist">
    <label for="version">Version:</label>
    <select name="version" class="form-select border border-dark" onchange="this.form.submit()">
        <optgroup label="COD UO">
            <option value="coduo/1.41" ' . ($selected_version == 'coduo/1.41' ? 'selected' : '') . '>1.41</option>
            <option value="coduo/1.51" ' . ($selected_version == 'coduo/1.51' ? 'selected' : '') . '>1.51</option>
        </optgroup>
        <optgroup label="COD 2">
            <option value="cod2/1.0" ' . ($selected_version == 'cod2/1.0' ? 'selected' : '') . '>1.0</option>
            <option value="cod2/1.2" ' . ($selected_version == 'cod2/1.2' ? 'selected' : '') . '>1.2</option>
            <option value="cod2/1.3" ' . ($selected_version == 'cod2/1.3' ? 'selected' : '') . '>1.3</option>
        </optgroup>
        <optgroup label="COD 4">
            <option value="cod4/1.0" ' . ($selected_version == 'cod4/1.0' ? 'selected' : '') . '>1.0</option>
            <option value="cod4/1.7" ' . ($selected_version == 'cod4/1.7' ? 'selected' : '') . '>1.7</option>
            <option value="cod4/1.8" ' . ($selected_version == 'cod4/1.8' ? 'selected' : '') . '>1.8</option>
        </optgroup>
    </select>
</form>
';


// Daten für das Template vorbereiten
// Serverliste generieren
$server_html = "";
foreach ($servers as $index => $server) {
    $server_id = "server-" . $index; // Eindeutige ID für Bootstrap Collapse
    $status_badge = ($server['clients'] > 0) 
    ? '<span class="badge bg-success d-inline-block text-center" style="width: 150px;">Player online</span>' 
    : '<span class="badge bg-danger d-inline-block text-center" style="width: 150px;">No Player online</span>';
    $server_name = convertColorCodes($server['sv_hostname']); // Farbcodes umwandeln


    if (!empty($server['playerinfo']) && is_array($server['playerinfo'])) {
    $player_details = [];
    $score_details = [];  // Initialisierung notwendig!
    $ping_details = [];   // Initialisierung notwendig!
    
    foreach ($server['playerinfo'] as $player) {
        $name = htmlspecialchars($player['name'] ?? "Unbekannter Spieler");
        $score = htmlspecialchars($player['score'] ?? "N/A");  
        $ping = htmlspecialchars($player['ping'] ?? "N/A");  
        
        $player_details[] = $name;
        $score_details[] = $score;
        $ping_details[] = $ping;
    }
    
    $spieler = implode("<br>", $player_details); 
    $score = implode("<br>", $score_details); 
    $ping = implode("<br>", $ping_details); 
} else {
    $spieler = "Keine Spieler gefunden.";
    $score = "N/A";
    $ping = "N/A";
}

    $name = convertColorCodes($spieler); // Farbcodes umwandeln
$map_image_path = "includes/plugins/masterlist/images/coduo/custom/" . htmlspecialchars($server['mapname']) . ".png";

// Prüfen, ob die Datei existiert und ein gültiges Bild ist
if (file_exists($map_image_path) && @getimagesize($map_image_path)) {
    $map_image_url = $map_image_path;
} else {
    $map_image_url = "includes/plugins/masterlist/images/default_map.png"; // Fallback-Bild
}

    $server_html .= "
    <tr class='server-row' data-bs-toggle='collapse' data-bs-target='#{$server_id}'>
        <td> + </td>
        <td>{$server_name}</td>
        <td>{$server['ip']}</td>
        <td>{$server['port']}</td>
        <td>{$server['game']}</td>
        <td>{$server['mapname']}</td>
        <td>{$server['clients']}/{$server['sv_maxclients']}</td>
        <td>$status_badge</td>
    </tr>
    <tr id='{$server_id}' class='collapse server-details'>
        <td colspan='8'>
            <table class='table table-striped table-dark'>
                <tr>
                    <td width='50%'>     
                        <img src='" . $map_image_url . "' alt='" . htmlspecialchars($server['mapname']) . "' style='width: 450px; height: auto;'><br>
                        Weitere Informationen:<br>
                        Map: " . htmlspecialchars($server['mapname']) . "<br>
                        Gametype: " . htmlspecialchars($server['g_gametype']) . "</td> 
                    <td>
                        <table class='table table-dark'>
                            <thead>
                                <tr>
                                    <th>Spielernamen:</th>
                                    <th>Score</th>
                                    <th>Ping</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>" . $name . "</td>
                                <td>" . $score . "</td>
                                <td>" . $ping . "</td>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>";
}



$data_array['$servers'] = $server_html;
$data_array['$selected_version'] = $selected_version;
$data_array['$select_setting'] = $select_setting;

$template = $GLOBALS["_template"]->loadTemplate("masterlist", "content", $data_array, $plugin_path);
echo $template;


?>