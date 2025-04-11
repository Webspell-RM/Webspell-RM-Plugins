<?php
/**
 * Game Server Masterlist Plugin für Webspell-RM
 * 
 * @version webspell-rm
 * @license GNU GENERAL PUBLIC LICENSE
 */

$_language->readModule('gameserver_masterlist', false, true);

// Funktion zum Umwandeln von Farbcodes in HTML
function convertColorCodes($text) {
    $colors = [
        '^1' => '<span style="color:red">',
        '^2' => '<span style="color:green">',
        '^3' => '<span style="color:yellow">',
        '^4' => '<span style="color:blue">',
        '^5' => '<span style="color:cyan">',
        '^6' => '<span style="color:magenta">',
        '^7' => '<span style="color:white">',
        '^8' => '<span style="color:orange">',
        '^9' => '<span style="color:gray">'
    ];
    
    foreach ($colors as $code => $html) {
        $text = str_replace($code, $html, $text);
    }
    
    return $text . str_repeat('</span>', substr_count($text, '<span'));
}

// Spielversion aus der Auswahl abrufen
$selected_version = isset($_GET['version']) ? str_replace('%2F', '/', $_GET['version']) : 'coduo/1.51';

// Funktion zum Abrufen der Serverliste von der API
function fetchCodUoServers($version) {
    $api_url = "https://api.cod.pm/masterlist/" . str_replace('%2F', '/', htmlspecialchars($version));
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    
    try {
        $response = @file_get_contents($api_url, false, $context);
        if ($response === false) {
            throw new Exception("Fehler beim Abrufen der Serverliste von der API");
        }
        
        error_log("API Antwort (Serverliste): " . $response);
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON-Fehler: " . json_last_error_msg());
        }
        
        if (!isset($data['servers']) || !is_array($data['servers'])) {
            throw new Exception("Ungültige oder leere API-Antwort: " . print_r($data, true));
        }
        
        // Server nach Anzahl der Spieler sortieren (absteigend)
        usort($data['servers'], function($a, $b) {
            return $b['clients'] - $a['clients'];
        });
        
        echo '<h2>Serverliste</h2>';
        echo '<div class="row gx-0">
    <h1 class="visually-hidden">Call of Duty 1 guides and resources. Game server (master) lists for Call of Duty 1 through Call of Duty 4.</h1>
    <p class="h1 display-4">Servers on ' . htmlspecialchars($version) . '</p>
</div>';
        echo '<form method="GET">';
        echo '<input type="hidden" name="site" value="admin_masterlist">';
        echo '<label for="version">Version:</label>';
        echo '<select name="version" class="form-select border border-dark" style="width: 6rem;" aria-label="serverversion" onchange="this.form.submit()">';
        
        // Versionen definieren
        $versions = [
            "COD 1" => ["cod1/1.1", "cod1/1.2", "cod1/1.3", "cod1/1.4", "cod1/1.5"],
            "COD UO" => ["coduo/1.41", "coduo/1.51"],
            "COD 2" => ["cod2/1.0", "cod2/1.2", "cod2/1.3"],
            "COD 4" => ["cod4/1.0", "cod4/1.7", "cod4/1.8"]
        ];
        
        // Optionen durchlaufen und den selected Wert hinzufügen
        foreach ($versions as $label => $options) {
            echo "<optgroup label=\"$label\">";
            foreach ($options as $option) {
                $selected = ($option === str_replace('%2F', '/', htmlspecialchars($version))) ? 'selected' : ''; // urlencode anwenden
                echo "<option value=\"$option\" $selected>" . explode('/', $option)[1] . "</option>";
            }
            echo "</optgroup>";
        }
        
        echo '</select>';
        echo '</form><br>';
        
        echo '<table class="table table-striped table-bordered table-dark">';
        echo '<thead class="thead-dark">';
        echo '<tr><th>+</th><th>Servername</th><th>IP</th><th>Port</th><th>Spiel</th><th>Map</th><th>Spieler</th><th>Status</th></tr>';
        echo '</thead><tbody>';
        
        // Ausgabe der Serverinformationen
        foreach ($data['servers'] as $server) {
            $status = ($server['clients'] > 0 || $server['sv_maxclients'] > 0) ? 'Online' : 'Offline';
            $statusBadge = $status === 'Online' ? '<span class="badge bg-success">Online</span>' : '<span class="badge bg-danger">Offline</span>';
            
            echo '<tr data-bs-toggle="collapse" data-bs-target="#server-' . htmlspecialchars($server['id']) . '">';
            echo '<td> + </td>';
            echo '<td>' . convertColorCodes(htmlspecialchars($server['sv_hostname'])) . '</td>';
            echo '<td>' . htmlspecialchars($server['ip']) . '</td>';
            echo '<td>' . htmlspecialchars($server['port']) . '</td>';
            echo '<td>' . htmlspecialchars($server['game']) . '</td>';
            echo '<td>' . htmlspecialchars($server['mapname']) . '</td>';
            echo '<td>' . htmlspecialchars($server['clients']) . '/' . htmlspecialchars($server['sv_maxclients']) . '</td>';
            echo '<td>' . $statusBadge . '</td>';
            echo '</tr>';
            echo '<tr id="server-' . htmlspecialchars($server['id']) . '" class="collapse">';
            echo '<td colspan="8">Weitere Informationen...

            ' . htmlspecialchars($server['mapname']) . '
            </td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        
        return $data['servers'];
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        return [];
    }
}

// Serverliste aktualisieren
$servers = fetchCodUoServers($selected_version);
?>
