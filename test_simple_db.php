<?php
// Simple database test script
$dbPath = __DIR__ . '/data/azwiki.sqlite';

try {
    // Connect to SQLite database
    $db = new SQLite3($dbPath);
    $db->enableExceptions(true);
    
    // Test query
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");
    
    echo "<h2>Tables in database:</h2>\n";
    echo "<ul>\n";
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<li>" . htmlspecialchars($row['name']) . "</li>\n";
    }
    echo "</ul>\n";
    
    // Check if revtag table exists
    $result = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='revtag';");
    $row = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($row) {
        echo "<h2>revtag table schema:</h2>\n";
        echo "<pre>" . htmlspecialchars($row['sql']) . "</pre>\n";
        
        // Try to select from revtag
        $result = $db->query("SELECT * FROM revtag LIMIT 5;");
        $count = 0;
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $count++;
            echo "<h3>Row $count:</h3>\n";
            echo "<pre>";
            print_r($row);
            echo "</pre>\n";
        }
        
        if ($count === 0) {
            echo "<p>No rows found in revtag table.</p>\n";
        }
    } else {
        echo "<p>revtag table does not exist in the database.</p>\n";
    }
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>\n";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>\n";
}

echo "<h2>Database file info:</h2>\n";
echo "<pre>";
echo "Path: " . htmlspecialchars($dbPath) . "\n";
echo "Exists: " . (file_exists($dbPath) ? 'Yes' : 'No') . "\n";
if (file_exists($dbPath)) {
    echo "Size: " . filesize($dbPath) . " bytes\n";
    echo "Writable: " . (is_writable($dbPath) ? 'Yes' : 'No') . "\n";
    echo "Permissions: " . substr(sprintf('%o', fileperms($dbPath)), -4) . "\n";
}
echo "</pre>\n";
?>
