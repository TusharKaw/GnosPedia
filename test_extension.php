<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/WebStart.php';

echo "Testing Scribunto Extension...\n";

// Check if Scribunto extension is loaded
if (ExtensionRegistry::getInstance()->isLoaded('Scribunto')) {
    echo "✅ Scribunto extension is loaded\n";
    
    // Check if LuaSandbox is available
    if (class_exists('Scribunto_LuaSandboxEngine')) {
        echo "✅ Scribunto_LuaSandboxEngine class exists\n";
        
        try {
            // Try to create a Lua engine instance
            $engine = new Scribunto_LuaSandboxEngine([
                'memoryLimit' => 500000000,
                'cpuLimit' => 30,
                'maxLangCacheSize' => 30,
            ]);
            
            echo "✅ Successfully created LuaSandboxEngine instance\n";
            
            // Try a simple Lua script
            $result = $engine->loadString('return "Hello from Lua!"')->call();
            echo "✅ Lua test result: " . $result . "\n";
            
        } catch (Exception $e) {
            echo "❌ Error creating LuaSandboxEngine: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    } else {
        echo "❌ Scribunto_LuaSandboxEngine class not found\n";
        echo "Available classes:\n";
        foreach (get_declared_classes() as $class) {
            if (strpos($class, 'Scribunto') === 0) {
                echo "- $class\n";
            }
        }
    }
} else {
    echo "❌ Scribunto extension is not loaded\n";
    echo "Loaded extensions: " . implode(", ", array_keys(ExtensionRegistry::getInstance()->getAllThings())) . "\n";
}
