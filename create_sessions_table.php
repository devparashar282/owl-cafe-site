<?php
require 'includes/db.php';
$pdo->exec("
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    data TEXT NOT NULL,
    last_accessed INT UNSIGNED NOT NULL
)
");
echo "Sessions table created successfully!";
