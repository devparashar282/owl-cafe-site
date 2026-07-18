<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once '../includes/media.php';

$message = '';

// Handle Delete
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("SELECT image FROM movie_nights WHERE id = ?");
        $stmt->execute([$id]);
        $existingImage = $stmt->fetch();
        if ($existingImage && isset($existingImage['image'])) {
            media_delete_stored_asset($existingImage['image']);
        }
        $pdo->prepare("DELETE FROM movie_nights WHERE id = ?")->execute([$id]);
        $message = "<div class='alert alert-success'>Movie Night deleted successfully.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to delete movie night.</div>";
    }
}

// Handle Status Toggle
if(isset($_GET['toggle_status_id'])) {
    $id = $_GET['toggle_status_id'];
    $new_status = $_GET['status'] == 'Active' ? 'Inactive' : 'Active';
    try {
        $pdo->prepare("UPDATE movie_nights SET status = ? WHERE id = ?")->execute([$new_status, $id]);
        $message = "<div class='alert alert-success'>Movie Night status updated successfully.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to update status.</div>";
    }
}

// Handle Add Movie Night
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_movie'])) {
    $title = $_POST['title'];
    $movie_date = $_POST['movie_date'];
    $movie_time = $_POST['movie_time'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    
    $storedImage = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $storedImage = media_store_uploaded_file($_FILES['image'], 'owl-cafe/movies');
    }
    
    if ($storedImage) {
        try {
            $stmt = $pdo->prepare("INSERT INTO movie_nights (title, movie_date, movie_time, description, image, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $movie_date, $movie_time, $description, $storedImage, $status]);
            $message = "<div class='alert alert-success'>Movie Night added successfully.</div>";
        } catch(PDOException $e) {
            $message = "<div class='alert alert-danger'>Failed to add movie night.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Please upload a valid image for the movie poster.</div>";
    }
}

// Fetch Movie Nights
try {
    $movies = $pdo->query("SELECT * FROM movie_nights ORDER BY movie_date DESC")->fetchAll();
} catch(PDOException $e) {
    $movies = [];
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-film me-2"></i>Manage Movie Nights</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMovieModal">
            <i class="fas fa-plus me-1"></i> Add Movie Night
        </button>
    </div>

    <?= $message ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($movies as $movie): ?>
                        <tr>
                            <td>
                                <img src="<?= media_get_asset_url($movie['image']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="rounded" style="width: 80px; height: 120px; object-fit: cover;">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($movie['title']) ?></td>
                            <td>
                                <?= date('d M Y', strtotime($movie['movie_date'])) ?><br>
                                <small class="text-muted"><i class="far fa-clock"></i> <?= date('h:i A', strtotime($movie['movie_time'])) ?></small>
                            </td>
                            <td>
                                <a href="?toggle_status_id=<?= $movie['id'] ?>&status=<?= $movie['status'] ?>" class="badge <?= $movie['status'] == 'Active' ? 'bg-success' : 'bg-secondary' ?> text-decoration-none">
                                    <?= $movie['status'] ?> <i class="fas fa-sync-alt ms-1" style="font-size:10px;"></i>
                                </a>
                            </td>
                            <td>
                                <a href="?delete_id=<?= $movie['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this movie night?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($movies)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No movie nights scheduled yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Movie Modal -->
<div class="modal fade" id="addMovieModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule New Movie Night</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-3">
                        <div class="col-md-12">
                            <label class="form-label">Movie Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="movie_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time</label>
                            <input type="time" name="movie_time" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Movie Poster (Image)</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Inactive">Inactive (Hidden)</option>
                                <option value="Active">Active (Visible to public)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description (What's special about this night?)</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_movie" class="btn btn-primary">Save Movie Night</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
