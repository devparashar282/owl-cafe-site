<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once '../includes/media.php';

$message = '';

// Handle Delete
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("SELECT image FROM offers WHERE id = ?");
        $stmt->execute([$id]);
        $existingImage = $stmt->fetch();
        if ($existingImage && isset($existingImage['image'])) {
            media_delete_stored_asset($existingImage['image']);
        }
        $pdo->prepare("DELETE FROM offers WHERE id = ?")->execute([$id]);
        $message = "<div class='alert alert-success'>Offer deleted successfully.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to delete offer.</div>";
    }
}

// Handle Status Toggle
if(isset($_GET['toggle_status_id'])) {
    $id = $_GET['toggle_status_id'];
    $new_status = $_GET['status'] == 'Active' ? 'Inactive' : 'Active';
    try {
        $pdo->prepare("UPDATE offers SET status = ? WHERE id = ?")->execute([$new_status, $id]);
        $message = "<div class='alert alert-success'>Offer status updated successfully.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to update status.</div>";
    }
}

// Handle Add Offer
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_offer'])) {
    $title = $_POST['title'];
    $status = $_POST['status'];
    
    $storedImage = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $storedImage = media_store_uploaded_file($_FILES['image'], 'owl-cafe/offers');
    }
    
    if ($storedImage) {
        try {
            $stmt = $pdo->prepare("INSERT INTO offers (title, image, status) VALUES (?, ?, ?)");
            $stmt->execute([$title, $storedImage, $status]);
            $message = "<div class='alert alert-success'>Offer added successfully.</div>";
        } catch(PDOException $e) {
            $message = "<div class='alert alert-danger'>Failed to add offer.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Please upload a valid image for the offer.</div>";
    }
}

// Fetch Offers
try {
    $offers = $pdo->query("SELECT * FROM offers ORDER BY created_at DESC")->fetchAll();
} catch(PDOException $e) {
    $offers = [];
}
?>

<!-- Content Wrapper -->
<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text"><i class="fas fa-tags me-2"></i>Manage Daily Offers</h2>
        <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addOfferModal">
            <i class="fas fa-plus me-1"></i> Add New Offer
        </button>
    </div>

    <?= $message ?>

    <div class="admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="theme-text">Poster Image</th>
                            <th class="theme-text">Offer Title</th>
                            <th class="theme-text">Added On</th>
                            <th class="theme-text">Status</th>
                            <th class="theme-text">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($offers as $offer): ?>
                        <tr>
                            <td>
                                <img src="<?= media_resolve_src($offer['image'], '../') ?>" alt="<?= htmlspecialchars($offer['title']) ?>" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            </td>
                            <td class="fw-bold theme-text"><?= htmlspecialchars($offer['title']) ?></td>
                            <td class="theme-text"><?= date('d M Y', strtotime($offer['created_at'])) ?></td>
                            <td>
                                <a href="?toggle_status_id=<?= $offer['id'] ?>&status=<?= $offer['status'] ?>" class="badge <?= $offer['status'] == 'Active' ? 'bg-success' : 'bg-secondary' ?> text-decoration-none">
                                    <?= $offer['status'] ?> <i class="fas fa-sync-alt ms-1" style="font-size:10px;"></i>
                                </a>
                            </td>
                            <td>
                                <a href="?delete_id=<?= $offer['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this offer?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($offers)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 theme-text-muted">No offers added yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Offer Modal -->
<div class="modal fade" id="addOfferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Special Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Offer Title (e.g. Chill Vibes Combo)</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Offer Poster (Image)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Active">Active (Visible on Homepage)</option>
                            <option value="Inactive">Inactive (Hidden)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_offer" class="btn btn-primary">Save Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
