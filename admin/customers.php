<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch all customers
try {
    $stmt = $pdo->query("SELECT id, name, email, phone, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch(PDOException $e) {
    $users = [];
}
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Customer Directory</h2>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($users)): ?>
                    <tr><td colspan="6" class="text-center py-4 theme-text-muted">No customers registered yet.</td></tr>
                    <?php else: foreach($users as $user): ?>
                    <tr>
                        <td class="fw-bold text-muted">#<?= $user['id'] ?></td>
                        <td>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=random&color=fff" class="rounded-circle" width="35" alt="Avatar">
                        </td>
                        <td class="fw-bold"><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><span class="badge bg-secondary"><?= date('d M Y', strtotime($user['created_at'])) ?></span></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
