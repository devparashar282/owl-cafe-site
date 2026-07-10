<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// Basic login logic
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // First check if the user is an admin
    $stmtAdmin = $pdo->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmtAdmin->execute([$email]);
    $admin = $stmtAdmin->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        echo "<script>window.location.href='admin/index.php';</script>";
        exit;
    }
    
    // If not admin, check regular users
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<section class="theme-bg-sec" style="min-height: 100vh; padding-top: 150px; padding-bottom: 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5" data-aos="zoom-in">
                <div class="glass-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="<?= $base_url ?>assets/images/logo.jpg" alt="Owl Cafe Logo" style="height: 120px; width: 120px; object-fit: contain;" class="mb-3 rounded-circle shadow-sm">
                        <h2 class="text-gradient">Welcome Back</h2>
                        <p class="theme-text-muted">Login to your Owl Cafe account</p>
                    </div>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger bg-transparent border-danger text-danger py-2"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label class="form-label text-golden">Email Address</label>
                            <input type="email" name="email" class="form-control bg-transparent theme-border theme-text py-3" required>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label text-golden">Password</label>
                                <a href="#" class="text-golden small">Forgot Password?</a>
                            </div>
                            <input type="password" name="password" class="form-control bg-transparent theme-border theme-text py-3" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-premium w-100 py-3 mb-4">Login</button>
                    </form>
                    
                    <p class="text-center theme-text-muted mb-0">Don't have an account? <a href="<?= $base_url ?>signup.php" class="text-golden fw-bold">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
