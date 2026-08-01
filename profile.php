<?php
require_once __DIR__ . '/includes/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Fetch user data
try {
    $stmt = $pdo->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $error = "Error fetching profile information.";
}

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    if (!empty($name) && !empty($phone)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $user_id]);
            $success = "Profile updated successfully!";
            $user['name'] = $name;
            $user['phone'] = $phone;
        } catch (PDOException $e) {
            $error = "Failed to update profile.";
        }
    } else {
        $error = "Name and Phone cannot be empty.";
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="section-padding theme-bg-sec" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="text-gradient mb-3">My Profile</h1>
        <p class="theme-text-muted max-w-2xl mx-auto">Manage your personal information and account settings.</p>
    </div>
</section>

<!-- Profile Content -->
<section class="section-padding" style="min-height: 50vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="glass-card p-5" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=d4af37&color=000&size=100" class="rounded-circle shadow mb-3" alt="Avatar">
                        <h3 class="theme-text mb-1"><?= htmlspecialchars($user['name']) ?></h3>
                        <p class="text-golden small"><i class="fas fa-crown me-1"></i> Premium Member Since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                    </div>

                    <form action="profile.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label theme-text-muted small text-uppercase fw-bold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent theme-border text-golden"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control bg-transparent theme-border theme-text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label theme-text-muted small text-uppercase fw-bold">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent theme-border text-golden"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control bg-transparent theme-border theme-text" value="<?= htmlspecialchars($user['email']) ?>" readonly disabled title="Email cannot be changed">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label theme-text-muted small text-uppercase fw-bold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent theme-border text-golden"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control bg-transparent theme-border theme-text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" name="update_profile" class="btn btn-premium btn-lg">Update Profile</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
