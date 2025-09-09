<?php
// Simple router for PHP built-in server to work with MediaWiki
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$fullPath = __DIR__ . $path;

// Serve existing files (css/js/images/php endpoints like load.php, api.php, rest.php, thumb.php)
if ($path !== '/' && file_exists($fullPath)) {
    return false;
}

// Otherwise route everything through index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';
require __DIR__ . '/index.php';
