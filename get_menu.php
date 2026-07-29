<?php
require_once __DIR__ . '/includes/db.php';
$stmt = $pdo->query("SELECT id, name FROM menu");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($items);
?>
