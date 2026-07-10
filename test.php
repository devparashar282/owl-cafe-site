<?php
require 'includes/db.php';
$stmt = $pdo->query('SELECT name, description FROM menu LIMIT 5');
print_r($stmt->fetchAll());
?>
