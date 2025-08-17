<?php
// Simple script to check if Scribunto extension files exist
$extensionPath = __DIR__ . '/extensions/Scribunto';

function checkFile($path, $description) {
    echo "Checking $description... ";
    if (file_exists($path)) {
        echo "✅ Found\n";
        return true;
    } else {
        echo "❌ Missing\n";
        return false;
    }
}

echo "Checking Scribunto extension files...\n";
checkFile("$extensionPath/Scribunto.php", "Scribunto main file");
checkFile("$extensionPath/includes/Scribunto.php", "Scribunto includes file");
checkFile("$extensionPath/includes/engines/LuaSandbox/LuaSandboxEngine.php", "LuaSandbox engine");

// Check for required binaries
echo "\nChecking for required binaries...\n";
$luajit = trim(shell_exec('which luajit 2>/dev/null') ?: '');
echo "LuaJIT: " . ($luajit ? "✅ $luajit" : "❌ Not found") . "\n";

// Check PHP modules
echo "\nChecking PHP modules...\n";
$modules = ['lua', 'ffi', 'json'];
foreach ($modules as $module) {
    echo "$module: " . (extension_loaded($module) ? '✅ Loaded' : '❌ Not loaded') . "\n";
}
