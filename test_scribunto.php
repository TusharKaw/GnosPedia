<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/WebStart.php';

if (!extension_loaded('lua')) {
    die("Lua extension is not loaded. Please install it using: brew install php-lua\n");
}

echo "PHP version: " . phpversion() . "\n";
echo "Lua extension loaded: " . (extension_loaded('lua') ? 'Yes' : 'No') . "\n";

if (class_exists('Scribunto_LuaEngine')) {
    echo "Scribunto_LuaEngine class exists\n";
    
    try {
        $engine = new Scribunto_LuaStandaloneEngine([
            'luaPath' => '/opt/homebrew/bin/luajit',
            'memoryLimit' => 500000000,
            'cpuLimit' => 30,
        ]);
        
        $result = $engine->loadString('return "Hello from Lua!"')->call();
        echo "Lua test result: " . $result . "\n";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
} else {
    echo "Scribunto_LuaEngine class not found. The Scribunto extension may not be properly installed.\n";
}
