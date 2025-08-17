<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/WebStart.php';

// Check if Scribunto extension is loaded
$extensions = ExtensionRegistry::getInstance()->getAllThings();

echo "Checking Scribunto installation...\n";

if (isset($extensions['Scribunto'])) {
    echo "✅ Scribunto extension is installed\n";
    echo "Version: " . $extensions['Scribunto']['version'] . "\n";
    echo "Path: " . $extensions['Scribunto']['path'] . "\n";
    
    // Check if the Scribunto directory exists
    if (is_dir($extensions['Scribunto']['path'])) {
        echo "✅ Scribunto directory exists\n";
        
        // Check if the required LuaSandbox class exists
        $luasandboxPath = $extensions['Scribunto']['path'] . '/includes/engines/LuaSandbox/LuaSandboxEngine.php';
        if (file_exists($luasandboxPath)) {
            echo "✅ LuaSandboxEngine.php found\n";
        } else {
            echo "❌ LuaSandboxEngine.php not found at: $luasandboxPath\n";
        }
    } else {
        echo "❌ Scribunto directory not found at: " . $extensions['Scribunto']['path'] . "\n";
    }
} else {
    echo "❌ Scribunto extension is not installed or not enabled\n";
    echo "Available extensions:\n";
    foreach ($extensions as $name => $info) {
        echo "- $name (" . ($info['path'] ?? 'no path') . ")\n";
    }
}
