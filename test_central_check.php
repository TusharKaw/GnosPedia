<?php

// Simple test without full MediaWiki initialization
$dbPath = __DIR__ . '/data/my_wiki_main.sqlite';

if (!file_exists($dbPath)) {
    echo "Database file does not exist: $dbPath\n";
    exit(1);
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Database Content Check ===\n";
    
    // Check cw_wikis table content
    $stmt = $pdo->query("SELECT * FROM cw_wikis");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "cw_wikis table contents:\n";
    foreach ($rows as $row) {
        echo "  wiki_dbname: " . $row['wiki_dbname'] . "\n";
        echo "  wiki_sitename: " . $row['wiki_sitename'] . "\n";
        echo "  wiki_language: " . $row['wiki_language'] . "\n";
    }
    
    // Check what the current wiki should be
    echo "\nExpected current wiki: my_wiki\n";
    echo "Central wiki in database: " . ($rows[0]['wiki_dbname'] ?? 'NONE') . "\n";
    echo "Match: " . (($rows[0]['wiki_dbname'] ?? '') === 'my_wiki' ? 'YES' : 'NO') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}