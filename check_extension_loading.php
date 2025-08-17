<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/WebStart.php';

// Check if Scribunto extension is loaded
$extensions = ExtensionRegistry::getInstance()->getAllThings();

echo "Checking Scribunto installation...\n";

if (isset($extensions['Scribunto'])) {
    echo "✅ Scribunto extension is installed and loaded\n";
    echo "Version: " . $extensions['Scribunto']['version'] . "\n";
    echo "Path: " . $extensions['Scribunto']['path'] . "\n";
} else {
    echo "❌ Scribunto extension is not loaded. Available extensions:\n";
    foreach ($extensions as $name => $info) {
        echo "- $name (" . ($info['path'] ?? 'no path') . ")\n";
    }
}
