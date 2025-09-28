<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/WebStart.php';

$lb = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
$db = $lb->getConnection(DB_PRIMARY);

try {
    // Test query to check if we can access the revtag table
    $result = $db->select(
        'revtag',
        '*',
        '',
        __METHOD__,
        ['LIMIT' => 5]
    );
    
    echo "<h2>Success! Found " . $result->numRows() . " rows in revtag table:</h2>";
    
    foreach ($result as $row) {
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    
    echo "<h2>Database connection info:</h2>";
    echo "<pre>DB Type: " . $db->getType() . "</pre>";
    echo "<pre>DB Name: " . $db->getDBname() . "</pre>";
    echo "<pre>DB Server: " . $db->getServer() . "</pre>";
    echo "<pre>DB User: " . $db->getUser() . "</pre>";
    
    echo "<h2>Available tables:</h2>";
    $tables = $db->listTables();
    echo "<pre>" . implode("\n", $tables) . "</pre>";
}

// Show the current database configuration
echo "<h2>Current Database Configuration:</h2>";
$config = [
    'wgDBtype' => $GLOBALS['wgDBtype'] ?? 'Not set',
    'wgDBserver' => $GLOBALS['wgDBserver'] ?? 'Not set',
    'wgDBname' => $GLOBALS['wgDBname'] ?? 'Not set',
    'wgDBuser' => isset($GLOBALS['wgDBuser']) ? '***' : 'Not set',
    'wgDBpassword' => isset($GLOBALS['wgDBpassword']) ? '***' : 'Not set',
    'wgSQLiteDataDir' => $GLOBALS['wgSQLiteDataDir'] ?? 'Not set',
    'wgDBprefix' => $GLOBALS['wgDBprefix'] ?? 'Not set',
];

echo "<pre>";
print_r($config);
echo "</pre>";
?>
