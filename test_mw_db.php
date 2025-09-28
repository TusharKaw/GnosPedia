<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/WebStart.php';

$lb = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
$db = $lb->getConnection(DB_PRIMARY);

try {
    // Test query using MediaWiki's database abstraction
    $result = $db->newSelectQueryBuilder()
        ->select('*')
        ->from('revtag')
        ->limit(5)
        ->caller(__METHOD__)
        ->fetchResultSet();
    
    echo "<h2>Success! Found " . $result->numRows() . " rows in revtag table:</h2>\n";
    
    foreach ($result as $row) {
        echo "<pre>";
        print_r($row);
        echo "</pre>\n";
    }
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>\n";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>\n";
    
    echo "<h2>Database connection info:</h2>\n";
    echo "<pre>DB Type: " . $db->getType() . "</pre>\n";
    echo "<pre>DB Name: " . $db->getDBname() . "</pre>\n";
    echo "<pre>DB Server: " . $db->getServer() . "</pre>\n";
    
    echo "<h2>Available tables:</h2>\n";
    $tables = $db->listTables();
    echo "<pre>" . implode("\n", $tables) . "</pre>\n";
}

// Show the current database configuration
echo "<h2>Current Database Configuration:</h2>\n";
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
echo "</pre>\n";
?>
