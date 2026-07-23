<?php
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path if necessary, but on Vercel it's usually just /
$file = realpath(__DIR__ . '/..' . $request_uri);
$base_dir = realpath(__DIR__ . '/..');

if ($request_uri === '/' || $request_uri === '') {
    require __DIR__ . '/../index.php';
} elseif ($file && strpos($file, $base_dir) === 0 && file_exists($file) && is_file($file)) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        require $file;
    } else {
        // Serve static files if not handled by Vercel routes
        $mime_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml'
        ];
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if (isset($mime_types[$ext])) {
            header('Content-Type: ' . $mime_types[$ext]);
        }
        readfile($file);
    }
} elseif (file_exists(__DIR__ . '/..' . $request_uri . '.php')) {
    require __DIR__ . '/..' . $request_uri . '.php';
} else {
    http_response_code(404);
    echo "404 Not Found";
}
?>
