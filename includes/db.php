<?php
// session_start moved to bottom
$dbHost = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1';
$dbPort = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
$dbName = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?: getenv('MYSQL_DATABASE') ?: 'owl_cafe';
$dbUser = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$dbPass = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: '';
$charset = 'utf8mb4';

$databaseUrl = getenv('DATABASE_URL');
if ($databaseUrl) {
    $parts = parse_url($databaseUrl);
    if ($parts !== false) {
        $dbHost = $parts['host'] ?? $dbHost;
        $dbPort = $parts['port'] ?? $dbPort;
        $dbName = isset($parts['path']) ? ltrim($parts['path'], '/') : $dbName;
        $dbUser = $parts['user'] ?? $dbUser;
        $dbPass = $parts['pass'] ?? $dbPass;
    }
}

$dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
     $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (\PDOException $e) {
     if ($e->getCode() == 1049 || strpos($e->getMessage(), 'Unknown database') !== false) {
         try {
             $dsnNoDb = "mysql:host=$dbHost;port=$dbPort;charset=$charset";
             $pdoTemp = new PDO($dsnNoDb, $dbUser, $dbPass, $options);
             $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
             $pdoTemp = null;
             $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
         } catch (\PDOException $e2) {
             $e = $e2;
         }
     }
     
     if (!isset($pdo)) {
         if (getenv('VERCEL') || getenv('VERCEL_URL') || getenv('APP_URL')) {
         $isAivenInternal = (strpos($dbHost, '.i.aivencloud.com') !== false || strpos($e->getMessage(), '.i.aivencloud.com') !== false);
         echo '<!DOCTYPE html><html><head><title>Database Setup Required</title>';
         echo '<style>body { font-family: system-ui, -apple-system, sans-serif; background: #1a1a1a; color: #fff; text-align: center; padding: 50px; }';
         echo 'h1 { color: #d4af37; } .box { background: #2a2a2a; border-radius: 10px; padding: 30px; max-width: 600px; margin: 0 auto; border: 1px solid #444; }';
         echo '</style></head><body><div class="box">';
         echo '<h1>🛠️ Database Setup Required</h1>';
         echo '<p>Your Owl Cafe website has been successfully deployed to Vercel!</p>';
         echo '<p style="color: #ff6b6b;">However, we cannot connect to your MySQL Database.</p>';
         echo '<p style="color: #ffaa00; background: #333; padding: 10px; border-radius: 5px; font-family: monospace;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
         if ($isAivenInternal) {
             echo '<div style="background: #3e1b1b; border: 2px solid #ff4d4d; border-radius: 8px; padding: 15px; margin: 15px 0; text-align: left;">';
             echo '<h3 style="color: #ff4d4d; margin-top: 0;">🚨 Aiven Internal Hostname Detected!</h3>';
             echo '<p style="color: #ffd1d1; margin-bottom: 10px;">You are using <code>' . htmlspecialchars($dbHost) . '</code> which contains <b>.i.aivencloud.com</b>.</p>';
             echo '<p style="color: #fff;">In Aiven, hostnames containing <b>.i.</b> are <i>Internal VPC hostnames</i> and cannot be reached from outside Aiven\'s private network (such as Vercel).</p>';
             echo '<p style="color: #d4af37; font-weight: bold; margin-bottom: 5px;">How to fix in Aiven:</p>';
             echo '<ol style="color: #fff; margin-top: 5px; padding-left: 20px;">';
             echo '<li>Open your <a href="https://console.aiven.io/" style="color: #4da6ff;" target="_blank">Aiven Console</a> and select your MySQL service.</li>';
             echo '<li>In <b>Overview &gt; Connection Information</b>, switch from <b>Internal</b> to <b>Service URI / Public Internet</b>.</li>';
             echo '<li>Copy the Public Hostname (it looks like <code>mysql-xxxx.aivencloud.com</code> without <b>.i.</b>).</li>';
             echo '<li>Update <b>DB_HOST</b> in Vercel <b>Settings &gt; Environment Variables</b> and redeploy!</li>';
             echo '</ol>';
             echo '</div>';
         }
         echo '<p>Since Vercel only hosts the website code, you need to provide a remote database URL.</p>';
         echo '<hr style="border-color: #444; margin: 20px 0;">';
         echo '<h3 style="text-align: left; color: #d4af37;">How to fix this:</h3>';
         echo '<div style="text-align: left;">';
         echo '1. Go to your <a href="https://vercel.com/dashboard" style="color: #4da6ff;">Vercel Dashboard</a><br>';
         echo '2. Go to <b>Settings &gt; Environment Variables</b><br>';
         echo '3. Add your remote MySQL credentials:<br>';
         echo '&nbsp;&nbsp; - <b>DB_HOST</b><br>';
         echo '&nbsp;&nbsp; - <b>DB_USER</b><br>';
         echo '&nbsp;&nbsp; - <b>DB_PASS</b><br>';
         echo '&nbsp;&nbsp; - <b>DB_NAME</b><br>';
         echo '<br>If you don\'t have a remote database yet, you can create a free one on <a href="https://aiven.io/mysql" style="color: #4da6ff;">Aiven</a> or <a href="https://planetscale.com/" style="color: #4da6ff;">PlanetScale</a>.';
         echo '</div></div></body></html>';
         exit;
     } else {
         throw new \PDOException($e->getMessage(), (int)$e->getCode());
     }
     }
}

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/';
$scriptDir = str_replace('\\', '/', dirname($scriptName));
if ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '') {
    $base_url = '/';
} elseif (basename($scriptDir) === 'admin') {
    $parentDir = str_replace('\\', '/', dirname($scriptDir));
    $base_url = $parentDir === '/' ? '/' : rtrim($parentDir, '/') . '/';
} else {
    $base_url = rtrim($scriptDir, '/') . '/';
}

$appUrl = getenv('APP_URL') ?: getenv('VERCEL_URL');
if ($appUrl && !preg_match('#^https?://#i', $appUrl)) {
    $appUrl = 'https://' . $appUrl;
}

$site_host = $_SERVER['HTTP_HOST'] ?? getenv('APP_HOST') ?? '';
$site_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$site_origin = $appUrl ?: ($site_host !== '' ? $site_scheme . '://' . $site_host : '');

require_once __DIR__ . '/session_handler.php';

if (!function_exists('ensureEssentialTables')) {
    function ensureEssentialTables($pdo) {
        if (!$pdo) return;
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) UNIQUE NOT NULL,
                `phone` VARCHAR(20),
                `password` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            $pdo->exec("CREATE TABLE IF NOT EXISTS `admin` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(50) UNIQUE NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            $stmt = $pdo->query("SELECT COUNT(*) FROM admin");
            if ($stmt && $stmt->fetchColumn() == 0) {
                $defaultPass = password_hash('admin123', PASSWORD_DEFAULT);
                $insertStmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
                $insertStmt->execute(['admin', $defaultPass]);
            }
        } catch (\Throwable $e) {
            // Silently ignore if read-only or error
        }
    }
}
ensureEssentialTables($pdo);

if (session_status() === PHP_SESSION_NONE) {
    try {
        session_set_save_handler(new DatabaseSessionHandler($pdo), true);
        @session_start();
    } catch (\Throwable $e) {
        @session_start();
    }
}
?>