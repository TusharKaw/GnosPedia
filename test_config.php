<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing MediaWiki configuration...\n";

try {
    define('MEDIAWIKI', true);
    $IP = __DIR__;
    
    echo "Loading LocalSettings.php...\n";
    require_once __DIR__ . '/LocalSettings.php';
    
    echo "Configuration loaded successfully!\n";
    echo "Database name: " . $wgDBname . "\n";
    echo "Site name: " . $wgSitename . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}