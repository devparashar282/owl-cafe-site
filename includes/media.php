<?php

function media_is_remote_url($value): bool
{
    return is_string($value) && preg_match('#^https?://#i', $value) === 1;
}

function media_cloudinary_configured(): bool
{
    return (getenv('CLOUDINARY_CLOUD_NAME') ?: '') !== ''
        && (getenv('CLOUDINARY_API_KEY') ?: '') !== ''
        && (getenv('CLOUDINARY_API_SECRET') ?: '') !== '';
}

function media_running_on_vercel(): bool
{
    return (getenv('VERCEL') ?: '') !== '' || (getenv('VERCEL_URL') ?: '') !== '';
}

function media_resolve_src(?string $value, string $baseUrl, string $fallback = '', string $localSubdir = ''): string
{
    $value = trim((string) $value);

    if ($value === '') {
        return $fallback !== '' ? $fallback : $baseUrl . 'assets/images/premium_coffee_1783449279091.png';
    }

    if (media_is_remote_url($value)) {
        return $value;
    }

    $value = ltrim($value, '/');
    if (strpos($value, 'assets/images/') === 0) {
        return $baseUrl . $value;
    }

    if (strpos($value, 'menu/') === 0 || strpos($value, 'gallery/') === 0) {
        return $baseUrl . 'assets/images/' . $value;
    }

    if ($localSubdir !== '') {
        return $baseUrl . 'assets/images/' . trim($localSubdir, '/') . '/' . $value;
    }

    return $baseUrl . 'assets/images/' . $value;
}

function media_fetch_binary(string $url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $data !== false && $data !== '') {
        return $data;
    }

    return false;
}

function media_cloudinary_public_id_from_url(string $url): ?string
{
    $path = parse_url($url, PHP_URL_PATH);
    if (!$path) {
        return null;
    }

    if (!preg_match('#/upload/(?:v\d+/)?(.+)\.[A-Za-z0-9]+$#', $path, $matches)) {
        return null;
    }

    return $matches[1] ?: null;
}

function media_upload_to_cloudinary(string $filePath, string $originalName, string $folder)
{
    if (!media_cloudinary_configured() || !is_readable($filePath)) {
        return false;
    }

    $cloudName = getenv('CLOUDINARY_CLOUD_NAME');
    $apiKey = getenv('CLOUDINARY_API_KEY');
    $apiSecret = getenv('CLOUDINARY_API_SECRET');

    $publicIdBase = pathinfo($originalName, PATHINFO_FILENAME);
    $publicId = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $publicIdBase);
    $timestamp = time();

    $authParams = [
        'folder' => $folder,
        'public_id' => $publicId,
        'timestamp' => $timestamp,
    ];
    ksort($authParams);
    $signature = sha1(http_build_query($authParams) . $apiSecret);

    $mimeType = function_exists('mime_content_type') ? mime_content_type($filePath) : 'application/octet-stream';
    if (!class_exists('CURLFile')) {
        return false;
    }

    $postFields = [
        'file' => new CURLFile($filePath, $mimeType ?: 'application/octet-stream', $originalName),
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'folder' => $folder,
        'public_id' => $publicId,
        'signature' => $signature,
    ];

    $ch = curl_init("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $httpCode < 200 || $httpCode >= 300) {
        return false;
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded) || empty($decoded['secure_url'])) {
        return false;
    }

    return $decoded;
}

function media_store_binary(string $binary, string $originalName, string $cloudFolder, string $localSubdir = '')
{
    if (media_cloudinary_configured()) {
        $tempFile = tempnam(sys_get_temp_dir(), 'owlcafe_');
        if ($tempFile !== false) {
            file_put_contents($tempFile, $binary);
            $uploaded = media_upload_to_cloudinary($tempFile, $originalName, $cloudFolder);
            @unlink($tempFile);

            if (is_array($uploaded) && !empty($uploaded['secure_url'])) {
                return $uploaded['secure_url'];
            }
        }
    }

    if (media_running_on_vercel()) {
        return false;
    }

    $targetDir = dirname(__DIR__) . '/assets/images/' . trim($localSubdir, '/');
    if ($localSubdir === '') {
        $targetDir = dirname(__DIR__) . '/assets/images';
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $safeBase = preg_replace('/[^A-Za-z0-9_\-]+/', '_', pathinfo($originalName, PATHINFO_FILENAME));
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $ext = $ext !== '' ? $ext : 'jpg';
    $filename = $safeBase . '_' . time() . '.' . $ext;
    $targetPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

    if (file_put_contents($targetPath, $binary) === false) {
        return false;
    }

    return $localSubdir !== '' ? trim($localSubdir, '/') . '/' . $filename : $filename;
}

function media_store_uploaded_file(array $file, string $cloudFolder, string $localSubdir = '')
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $originalName = $file['name'] ?? 'image.jpg';

    if (media_cloudinary_configured()) {
        $uploaded = media_upload_to_cloudinary($file['tmp_name'], $originalName, $cloudFolder);
        if (is_array($uploaded) && !empty($uploaded['secure_url'])) {
            return $uploaded['secure_url'];
        }
    }

    if (media_running_on_vercel()) {
        return false;
    }

    $targetDir = dirname(__DIR__) . '/assets/images/' . trim($localSubdir, '/');
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $safeBase = preg_replace('/[^A-Za-z0-9_\-]+/', '_', pathinfo($originalName, PATHINFO_FILENAME));
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $ext = $ext !== '' ? $ext : 'jpg';
    $filename = $safeBase . '_' . time() . '.' . $ext;
    $targetPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return false;
    }

    return trim($localSubdir, '/') . '/' . $filename;
}

function media_delete_stored_asset(?string $storedValue): void
{
    $storedValue = trim((string) $storedValue);
    if ($storedValue === '') {
        return;
    }

    if (media_is_remote_url($storedValue)) {
        $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: '';
        $apiKey = getenv('CLOUDINARY_API_KEY') ?: '';
        $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: '';
        $publicId = media_cloudinary_public_id_from_url($storedValue);

        if ($cloudName !== '' && $apiKey !== '' && $apiSecret !== '' && $publicId) {
            $timestamp = time();
            $authParams = [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];
            ksort($authParams);
            $signature = sha1(http_build_query($authParams) . $apiSecret);

            $postFields = [
                'public_id' => $publicId,
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ];

            $ch = curl_init("https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy");
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 20,
            ]);
            curl_exec($ch);
            curl_close($ch);
        }

        return;
    }

    $localPath = dirname(__DIR__) . '/assets/images/' . ltrim($storedValue, '/');
    if (is_file($localPath)) {
        @unlink($localPath);
    }
}
