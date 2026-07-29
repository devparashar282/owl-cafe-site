<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once '../includes/media.php';

$message = '';

// Handle Image Delete
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $existingImage = $stmt->fetch();
        if ($existingImage && isset($existingImage['image'])) {
            media_delete_stored_asset($existingImage['image']);
        }
        $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([$id]);
        $message = "<div class='alert alert-success'>Image deleted successfully.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to delete image.</div>";
    }
}

// Handle Image Upload
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image'])) {
    $category = $_POST['category'];
    
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $storedImage = media_store_uploaded_file($_FILES['image'], 'owl-cafe/gallery');

        if($storedImage) {
            $stmt = $pdo->prepare("INSERT INTO gallery (image, category) VALUES (?, ?)");
            $stmt->execute([$storedImage, $category]);
            $message = "<div class='alert alert-success'>Image uploaded successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to store uploaded image. Check Cloudinary env vars or local permissions.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please select a valid image file.</div>";
    }
}

// Fetch Gallery Images
$images = $pdo->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Gallery Management</h2>
    </div>

    <?= $message ?>

    <!-- Upload Section -->
    <div class="admin-card mb-5">
        <h5 class="theme-text mb-4">Upload New Photo</h5>
        <form action="" method="POST" enctype="multipart/form-data" class="row align-items-end">
            <div class="col-md-5 mb-3 mb-md-0">
                <label class="form-label theme-text-muted">Select Photo</label>
                <input type="file" name="image" class="form-control bg-transparent theme-text theme-border" accept="image/*" required>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label theme-text-muted">Category</label>
                <select name="category" class="form-select bg-transparent theme-text theme-border" required>
                    <option class="text-dark" value="Cafe Interior">Cafe Interior</option>
                    <option class="text-dark" value="Cafe Exterior">Cafe Exterior</option>
                    <option class="text-dark" value="Coffee">Coffee</option>
                    <option class="text-dark" value="Food">Food</option>
                    <option class="text-dark" value="Events">Events</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" name="upload_image" class="btn btn-premium w-100"><i class="fas fa-upload me-2"></i>Upload</button>
            </div>
        </form>
    </div>

    <!-- Gallery Grid -->
    <h5 class="theme-text mb-4">Current Gallery</h5>
    <div class="row gy-4">
        <?php if(empty($images)): ?>
            <div class="col-12"><p class="theme-text-muted text-center py-5 admin-card">No photos in gallery yet.</p></div>
        <?php else: foreach($images as $img): ?>
        <div class="col-md-3 col-sm-6">
            <div class="card bg-transparent border-0 h-100 position-relative group">
                <img src="<?= htmlspecialchars(media_resolve_src($img['image'], $base_url)) ?>" class="card-img-top rounded shadow-sm object-fit-cover" height="200" alt="Gallery">
                <div class="card-body px-0 pt-2 pb-0 d-flex justify-content-between align-items-center">
                    <span class="badge bg-secondary"><?= htmlspecialchars($img['category']) ?></span>
                    <a href="?delete_id=<?= $img['id'] ?>" class="text-danger" onclick="return confirm('Delete this photo?');" title="Delete"><i class="fas fa-trash"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
