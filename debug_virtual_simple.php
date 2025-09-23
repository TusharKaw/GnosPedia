<?php

// Simple test without loading full MediaWiki
$wgDBtype = "sqlite";
$wgDBname = "my_wiki_main";
$wgSQLiteDataDir = "cache/wikis";

echo "Database configuration:\n";
echo "Type: $wgDBtype\n";
echo "Name: $wgDBname\n";
echo "Directory: $wgSQLiteDataDir\n";

// Check if the database file exists
$dbFile = "$wgSQLiteDataDir/$wgDBname.sqlite";
echo "Database file: $dbFile\n";
echo "File exists: " . (file_exists($dbFile) ? 'Yes' : 'No') . "\n";

if (file_exists($dbFile)) {
    // Try to connect directly with SQLite
    try {
        $pdo = new PDO("sqlite:$dbFile");
        echo "Direct SQLite connection: Success\n";
        
        // Check if cw_wikis table exists
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='cw_wikis'");
        $result = $stmt->fetch();
        echo "cw_wikis table exists: " . ($result ? 'Yes' : 'No') . "\n";
        
        if ($result) {
            // Count rows in cw_wikis
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM cw_wikis");
            $row = $stmt->fetch();
            echo "cw_wikis row count: " . $row['count'] . "\n";
        }
        
    } catch (Exception $e) {
        echo "Direct SQLite connection error: " . $e->getMessage() . "\n";
    }
}

// Check virtual domain mapping
echo "\nVirtual domain mapping:\n";
if (isset($wgVirtualDomainsMapping)) {
    print_r($wgVirtualDomainsMapping);
} else {
    echo "No virtual domain mapping set\n";
}