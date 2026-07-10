<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$message = '';

// Handle Settings Update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    $site_name = $_POST['site_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    
    try {
        $stmt = $pdo->prepare("UPDATE settings SET site_name=?, phone=?, email=?, address=?, facebook=?, instagram=? WHERE id=1");
        $stmt->execute([$site_name, $phone, $email, $address, $facebook, $instagram]);
        $message = "<div class='alert alert-success'>Settings updated successfully.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to update settings.</div>";
    }
}

// Fetch current settings
try {
    $stmt = $pdo->query("SELECT * FROM settings WHERE id=1");
    $settings = $stmt->fetch();
} catch(PDOException $e) {
    $settings = [];
}
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Global Settings</h2>
    </div>

    <?= $message ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card">
                <form action="" method="POST">
                    
                    <h5 class="theme-text mb-4 border-bottom pb-2" style="border-color: var(--border-color) !important;">General Information</h5>
                    
                    <div class="mb-3">
                        <label class="form-label theme-text-muted">Site Name</label>
                        <input type="text" name="site_name" class="form-control bg-transparent theme-text theme-border" value="<?= htmlspecialchars($settings['site_name'] ?? 'OWL CAFE') ?>" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label theme-text-muted">Contact Email</label>
                            <input type="email" name="email" class="form-control bg-transparent theme-text theme-border" value="<?= htmlspecialchars($settings['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label theme-text-muted">Contact Phone</label>
                            <input type="text" name="phone" class="form-control bg-transparent theme-text theme-border" value="<?= htmlspecialchars($settings['phone'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label theme-text-muted">Physical Address</label>
                        <textarea name="address" class="form-control bg-transparent theme-text theme-border" rows="3" required><?= htmlspecialchars($settings['address'] ?? '') ?></textarea>
                    </div>

                    <h5 class="theme-text mb-4 border-bottom pb-2 mt-5" style="border-color: var(--border-color) !important;">Social Media Links</h5>
                    
                    <div class="mb-3">
                        <label class="form-label theme-text-muted"><i class="fab fa-facebook text-primary me-2"></i>Facebook URL</label>
                        <input type="url" name="facebook" class="form-control bg-transparent theme-text theme-border" value="<?= htmlspecialchars($settings['facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label theme-text-muted"><i class="fab fa-instagram text-danger me-2"></i>Instagram URL</label>
                        <input type="url" name="instagram" class="form-control bg-transparent theme-text theme-border" value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" name="update_settings" class="btn btn-premium px-5">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
