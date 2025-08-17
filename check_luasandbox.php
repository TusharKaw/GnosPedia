<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if LuaSandbox extension is loaded
if (extension_loaded('luasandbox')) {
    echo "✅ LuaSandbox extension is loaded\n";
    
    // Get LuaSandbox version
    if (function_exists('luasandbox_version')) {
        echo "✅ LuaSandbox version: " . luasandbox_version() . "\n";
    } else {
        echo "❌ luasandbox_version() function not found\n";
    }
    
    // Try to create a LuaSandbox instance
    try {
        $sandbox = new LuaSandbox();
        echo "✅ Successfully created LuaSandbox instance\n";
        
        // Test a simple Lua script
        $result = $sandbox->loadString('return "Hello from LuaSandbox!"')->call();
        echo "✅ LuaSandbox test result: " . $result[0] . "\n";
        
    } catch (Exception $e) {
        echo "❌ Error creating LuaSandbox instance: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ LuaSandbox extension is not loaded\n";
    echo "Loaded extensions: " . implode(", ", get_loaded_extensions()) . "\n";
}
