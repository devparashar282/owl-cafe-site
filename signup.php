<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// Basic signup logic
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->rowCount() > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$name, $email, $phone, $password])) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>

<section class="theme-bg-sec" style="min-height: 100vh; padding-top: 150px; padding-bottom: 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6" data-aos="zoom-in">
                <div class="glass-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="<?= $base_url ?>assets/images/logo.jpg" alt="Owl Cafe Logo" style="height: 120px; width: 120px; object-fit: contain;" class="mb-3 rounded-circle shadow-sm">
                        <h2 class="text-gradient">Create Account</h2>
                        <p class="theme-text-muted">Join the Owl Cafe luxury experience</p>
                    </div>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger bg-transparent border-danger text-danger py-2"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success bg-transparent border-success text-success py-2"><?= $success ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label text-golden">Full Name</label>
                                <input type="text" name="name" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Phone Number</label>
                                <input type="tel" name="phone" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-golden">Email Address</label>
                                <input type="email" name="email" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Password</label>
                                <input type="password" name="password" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Confirm Password</label>
                                <input type="password" name="cpassword" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                        </div>
                        <button type="submit" name="signup" class="btn btn-premium w-100 py-3 mt-4 mb-4">Sign Up</button>
                    </form>
                    
                    <p class="text-center theme-text-muted mb-0">Already have an account? <a href="<?= $base_url ?>login.php" class="text-golden fw-bold">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
