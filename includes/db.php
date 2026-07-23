<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbHost = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: getenv('MYSQL_DATABASE') ?: 'owl_cafe';
$dbUser = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: '';
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

try {
     $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (\PDOException $e) {
     if (getenv('VERCEL') || getenv('VERCEL_URL') || getenv('APP_URL')) {
         echo '<!DOCTYPE html><html><head><title>Database Setup Required</title>';
         echo '<style>body { font-family: system-ui, -apple-system, sans-serif; background: #1a1a1a; color: #fff; text-align: center; padding: 50px; }';
         echo 'h1 { color: #d4af37; } .box { background: #2a2a2a; border-radius: 10px; padding: 30px; max-width: 600px; margin: 0 auto; border: 1px solid #444; }';
         echo '</style></head><body><div class="box">';
         echo '<h1>🛠️ Database Setup Required</h1>';
         echo '<p>Your Owl Cafe website has been successfully deployed to Vercel!</p>';
         echo '<p style="color: #ff6b6b;">However, we cannot connect to your MySQL Database.</p>';
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
?>
