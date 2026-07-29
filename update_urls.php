<?php
require_once __DIR__ . '/includes/db.php';

$stmt = $pdo->query("SELECT id, name FROM menu");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($items as $item) {
    $id = $item['id'];
    $name = urlencode("Delicious high quality " . $item['name'] . " food photography, restaurant plate, centered, appealing");
    $url = "https://image.pollinations.ai/prompt/" . $name . "?width=400&height=300&nologo=true";
    
    $updateStmt = $pdo->prepare("UPDATE menu SET image = ? WHERE id = ?");
    $updateStmt->execute([$url, $id]);
}

echo "Database updated successfully with " . count($items) . " AI image URLs!";
?>
