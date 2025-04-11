<?php
/**
 * Game Server Masterlist Plugin für Webspell-RM
 * 
 * @version webspell-rm
 * @license GNU GENERAL PUBLIC LICENSE
 */


/**
 * Serverliste von API abrufen
 */

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

function fetchCodUoServers($version) {
    $api_url = "https://api.cod.pm/masterlist/" . str_replace('%2F', '/', htmlspecialchars($version));
    /*$context = stream_context_create(['http' => ['timeout' => 5]]);

    // JSON-Daten anzeigen
    $response = @file_get_contents($api_url, false, $context);
    if ($response === false) {
        echo "Fehler beim Abrufen der Daten";
    } else {
        echo "<pre>";
        echo json_encode(json_decode($response, true), JSON_PRETTY_PRINT); // Schöne Ausgabe der JSON-Daten
        echo "</pre>";
    }*/
    
    try {
        $response = @file_get_contents($api_url, false, $context);
        if ($response === false) {
            throw new Exception("Fehler beim Abrufen der Serverliste von der API");
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON-Fehler: " . json_last_error_msg());
        }
        
        if (!isset($data['servers']) || !is_array($data['servers'])) {
            throw new Exception("Ungültige oder leere API-Antwort.");
        }

        // Nach Spieleranzahl sortieren (absteigend)
        usort($data['servers'], function($a, $b) {
            return $b['clients'] - $a['clients'];
        });
        
        return $data['servers'];
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        return [];
    }
}
/*
function fetchCodUoServers($version) {
    $api_url = "https://api.cod.pm/masterlist/" . str_replace('%2F', '/', htmlspecialchars($version));
    
    // cURL-Initialisierung
    $ch = curl_init();
    
    // cURL Optionen setzen
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout auf 5 Sekunden setzen
    
    // Antwort von API abrufen
    $response = curl_exec($ch);
    
    // Fehlerbehandlung für cURL
    if (curl_errno($ch)) {
        error_log("cURL-Fehler: " . curl_error($ch));
        curl_close($ch);
        return [];
    }
    
    // cURL schließen
    curl_close($ch);
    
    // JSON-Antwort dekodieren
    $data = json_decode($response, true);
    
    // Überprüfen, ob die Antwort korrekt ist
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON-Fehler: " . json_last_error_msg());
        return [];
    }

    // Überprüfen, ob die 'servers' Daten vorhanden sind
    if (!isset($data['servers']) || !is_array($data['servers'])) {
        error_log("Ungültige oder leere API-Antwort.");
        return [];
    }

    // Nach Spieleranzahl sortieren (absteigend)
    usort($data['servers'], function($a, $b) {
        return $b['clients'] - $a['clients'];
    });
    
    return $data['servers'];
}*/
?>