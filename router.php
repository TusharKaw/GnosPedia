<?php
// Enhanced router for PHP built-in server to work with MediaWiki Farm
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$fullPath = __DIR__ . $path;
$host = $_SERVER['HTTP_HOST'] ?? '';

// Serve existing files (css/js/images/php endpoints like load.php, api.php, rest.php, thumb.php)
if ($path !== '/' && file_exists($fullPath)) {
    return false;
}

// Wiki farm routing based on subdomain
if (preg_match('/^([a-z0-9-]+)\.localhost:4000$/', $host, $matches)) {
    $wikiName = $matches[1];
    
    // Check if we have a dedicated PHP file for this wiki
    $wikiFile = __DIR__ . '/wikis/' . $wikiName . '.php';
    
    if (file_exists($wikiFile)) {
        // Serve the dedicated wiki file
        include $wikiFile;
        exit;
    } else {
        // Fallback to a generic wiki template
        if ($path === '/' || $path === '/index.php') {
            include __DIR__ . '/wikis/template.php';
            exit;
        }
    }
} else if ($host === 'localhost:4000') {
    // Main wiki - serve a simple main page to avoid MediaWiki errors
    if ($path === '/' || $path === '/index.php') {
        include __DIR__ . '/wikis/main.php';
        exit;
    }
}

// Otherwise route through index.php (only for API and resource requests)
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';
require __DIR__ . '/index.php';
