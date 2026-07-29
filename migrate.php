<?php
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'mysql-38be2b5a-devparashar282-ca5.aivencloud.com';
$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 13951;
$user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'avnadmin';
$pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';
$db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'defaultdb';

$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$sql = file_get_contents('godaddy_database_export_utf8.sql');
// Remove UTF-8 BOM if present
if (strpos($sql, "\xEF\xBB\xBF") === 0) {
    $sql = substr($sql, 3);
}

if ($mysqli->multi_query($sql)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
    echo "Database successfully migrated!\n";
} else {
    echo "Migration failed: " . $mysqli->error;
}
$mysqli->close();
?>
