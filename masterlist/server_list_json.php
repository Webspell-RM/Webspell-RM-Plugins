<?php
header('Content-Type: application/json');
require_once 'fetch_servers.php';

echo json_encode($servers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>