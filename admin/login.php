<?php
session_start();
require_once '../includes/db.php';

$error = '';

// If already logged in, redirect to dashboard
if(isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | OWL CAFE</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-black: #0a0a0a;
            --secondary-black: #141414;
            --golden-accent: #d4af37;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--primary-black);
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: var(--secondary-black);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 15px;
            padding: 3rem 2rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .form-control {
            background-color: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            padding: 0.8rem 1rem;
        }
        .form-control:focus {
            background-color: rgba(255,255,255,0.1);
            border-color: var(--golden-accent);
            box-shadow: none;
            color: white;
        }
        .btn-premium {
            background: linear-gradient(135deg, #d4af37 0%, #b5952f 100%);
            color: #0a0a0a;
            font-weight: 600;
            border: none;
            padding: 0.8rem;
            width: 100%;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="login-card text-center">
            <img src="../assets/images/logo.jpg" alt="Owl Cafe Logo" style="height: 100px; width: 100px; object-fit: contain;" class="mb-3 rounded-circle shadow-sm">
            <h2 class="mb-4" style="color: var(--golden-accent); letter-spacing: 2px;">Admin Portal</h2>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger py-2"><?= $error ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3 text-start">
                    <label for="username" class="form-label text-muted">Admin Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-color: rgba(255,255,255,0.1);"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control border-start-0" id="username" name="username" placeholder="Owlcafe@gmail" required>
                    </div>
                </div>
                <div class="mb-4 text-start">
                    <label for="password" class="form-label text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-color: rgba(255,255,255,0.1);"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-premium mt-2">Login to Dashboard</button>
            </form>
            <div class="mt-4">
                <a href="../index.php" class="text-muted text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> Back to Main Site</a>
            </div>
        </div>
    </div>

</body>
</html>
