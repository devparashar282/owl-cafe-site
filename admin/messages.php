<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$message = '';

// Handle Delete Message
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        $pdo->prepare("DELETE FROM contact WHERE id = ?")->execute([$id]);
        $message = "<div class='alert alert-success'>Message deleted.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to delete message.</div>";
    }
}

// Fetch Messages
try {
    $stmt = $pdo->query("SELECT * FROM contact ORDER BY created_at DESC");
    $messages = $stmt->fetchAll();
} catch(PDOException $e) {
    $messages = [];
}
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Contact Messages</h2>
    </div>

    <?= $message ?>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="15%">Date</th>
                        <th width="20%">Name</th>
                        <th width="20%">Email</th>
                        <th width="35%">Message</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($messages)): ?>
                    <tr><td colspan="5" class="text-center py-4 theme-text-muted">No messages found.</td></tr>
                    <?php else: foreach($messages as $msg): ?>
                    <tr>
                        <td><small class="theme-text-muted"><?= date('d M Y, h:i A', strtotime($msg['created_at'])) ?></small></td>
                        <td class="fw-bold"><?= htmlspecialchars($msg['name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="text-golden text-decoration-none"><?= htmlspecialchars($msg['email']) ?></a></td>
                        <td><p class="mb-0 small" style="white-space: pre-wrap;"><?= htmlspecialchars($msg['message']) ?></p></td>
                        <td>
                            <a href="?delete_id=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
