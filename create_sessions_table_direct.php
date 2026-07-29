<?php
$dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'mysql-38be2b5a-devparashar282-ca5.aivencloud.com';
$dbPort = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '13951';
$dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'defaultdb';
$dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'avnadmin';
$dbPass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=$charset";
$pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$pdo->exec('
    CREATE TABLE IF NOT EXISTS sessions (
        id VARCHAR(128) NOT NULL PRIMARY KEY,
        data MEDIUMTEXT NOT NULL,
        timestamp INT(10) UNSIGNED NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
');
echo "Sessions table created successfully!";
