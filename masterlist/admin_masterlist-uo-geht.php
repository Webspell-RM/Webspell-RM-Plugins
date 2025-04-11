<?php
/**
 * Game Server Masterlist Plugin für Webspell-RM
 * 
 * @version webspell-rm
 * @license GNU GENERAL PUBLIC LICENSE
 */

$_language->readModule('gameserver_masterlist', false, true);

// Datenbanktabelle erstellen, falls nicht vorhanden
safe_query("CREATE TABLE IF NOT EXISTS " . PREFIX . "gameserver_masterlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    ip VARCHAR(50) NOT NULL,
    port INT NOT NULL,
    game VARCHAR(100) NOT NULL,
    status VARCHAR(10) NOT NULL DEFAULT 'offline'
)");

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

// Funktion zum Abrufen der Serverliste von der API
function fetchCodUoServers() {
    $api_url = "https://api.cod.pm/masterlist/coduo/1.51";
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
        echo '<table class="table table-striped table-bordered">';
        echo '<thead class="thead-dark">';
        echo '<tr><th>Servername</th><th>IP</th><th>Port</th><th>Spiel</th><th>Map</th><th>Spieler</th><th>Status</th></tr>';
        echo '</thead><tbody>';
        
        foreach ($data['servers'] as $server) {
            $status = ($server['clients'] > 0 || $server['sv_maxclients'] > 0) ? 'Online' : 'Offline';
            $statusBadge = $status === 'Online' ? '<span class="badge bg-success">Online</span>' : '<span class="badge bg-danger">Offline</span>';
            
            echo '<tr>';
            echo '<td>' . convertColorCodes(htmlspecialchars($server['sv_hostname'])) . '</td>';
            echo '<td>' . htmlspecialchars($server['ip']) . '</td>';
            echo '<td>' . htmlspecialchars($server['port']) . '</td>';
            echo '<td>' . htmlspecialchars($server['game']) . '</td>';
            echo '<td>' . htmlspecialchars($server['mapname']) . '</td>';
            echo '<td>' . htmlspecialchars($server['clients']) . '/' . htmlspecialchars($server['sv_maxclients']) . '</td>';
            echo '<td>' . $statusBadge . '</td>';
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
$servers = fetchCodUoServers();
foreach ($servers as $server) {
    $ip = mysqli_real_escape_string($_database, $server['ip']);
    $port = (int)$server['port'];
    $game = mysqli_real_escape_string($_database, $server['game']);
    $servername = mysqli_real_escape_string($_database, $server['sv_hostname']);
    
    $existing = safe_query("SELECT id FROM " . PREFIX . "gameserver_masterlist WHERE ip='$ip' AND port='$port'");
    if (!$existing) {
        error_log("SQL-Fehler: " . mysqli_error($_database));
        continue;
    }
    
    if (mysqli_num_rows($existing) == 0) {
        if (!safe_query("INSERT INTO " . PREFIX . "gameserver_masterlist (name, ip, port, game, status) VALUES ('$servername', '$ip', '$port', '$game', 'offline')")) {
            error_log("SQL-Fehler beim Einfügen: " . mysqli_error($GLOBALS['mysqli']));
        }
    }
}
?>
