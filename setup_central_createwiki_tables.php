<?php

// Setup CreateWiki tables in the central database (main database)
$dbPath = __DIR__ . '/data/my_wiki.sqlite';

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Setting up CreateWiki tables in central database...\n";
    
    // Create cw_wikis table
    $sql = "CREATE TABLE IF NOT EXISTS cw_wikis (
        wiki_dbname VARCHAR(64) NOT NULL PRIMARY KEY,
        wiki_sitename VARCHAR(128) NOT NULL,
        wiki_language VARCHAR(12) NOT NULL,
        wiki_private INTEGER NOT NULL,
        wiki_creation TEXT NOT NULL,
        wiki_url TEXT NULL,
        wiki_closed INTEGER NOT NULL DEFAULT 0,
        wiki_closed_timestamp TEXT NULL,
        wiki_inactive INTEGER NOT NULL DEFAULT 0,
        wiki_inactive_timestamp TEXT NULL,
        wiki_inactive_exempt INTEGER NOT NULL DEFAULT 0,
        wiki_inactive_exempt_reason TEXT NULL,
        wiki_deleted INTEGER NOT NULL DEFAULT 0,
        wiki_deleted_timestamp TEXT NULL,
        wiki_locked INTEGER NOT NULL DEFAULT 0,
        wiki_dbcluster VARCHAR(5) DEFAULT 'c1',
        wiki_category VARCHAR(64) NOT NULL,
        wiki_experimental INTEGER NOT NULL DEFAULT 0,
        wiki_extra TEXT NULL
    )";
    $pdo->exec($sql);
    echo "Created cw_wikis table\n";
    
    // Create indexes for cw_wikis
    $pdo->exec("CREATE INDEX IF NOT EXISTS wiki_dbname_idx ON cw_wikis (wiki_dbname)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS wiki_dbcluster_idx ON cw_wikis (wiki_dbcluster)");
    
    // Create cw_requests table
    $sql = "CREATE TABLE IF NOT EXISTS cw_requests (
        cw_id INTEGER PRIMARY KEY AUTOINCREMENT,
        cw_comment TEXT DEFAULT NULL,
        cw_dbname VARCHAR(64) DEFAULT NULL,
        cw_language VARCHAR(12) NOT NULL,
        cw_private INTEGER NOT NULL DEFAULT 0,
        cw_sitename VARCHAR(128) NOT NULL,
        cw_status VARCHAR(16) DEFAULT NULL,
        cw_timestamp TEXT NOT NULL,
        cw_url VARCHAR(96) NOT NULL,
        cw_user INTEGER NOT NULL,
        cw_category VARCHAR(64) NOT NULL,
        cw_visibility INTEGER NOT NULL DEFAULT 0,
        cw_locked INTEGER NOT NULL DEFAULT 0,
        cw_bio INTEGER NOT NULL DEFAULT 0,
        cw_extra TEXT NULL
    )";
    $pdo->exec($sql);
    echo "Created cw_requests table\n";
    
    // Create indexes for cw_requests
    $pdo->exec("CREATE INDEX IF NOT EXISTS cw_status_idx ON cw_requests (cw_status)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS cw_timestamp_idx ON cw_requests (cw_timestamp)");
    
    // Create cw_comments table
    $sql = "CREATE TABLE IF NOT EXISTS cw_comments (
        cw_id INTEGER NOT NULL,
        cw_comment TEXT NOT NULL,
        cw_comment_timestamp TEXT NOT NULL,
        cw_comment_user INTEGER NOT NULL
    )";
    $pdo->exec($sql);
    echo "Created cw_comments table\n";
    
    // Create cw_history table
    $sql = "CREATE TABLE IF NOT EXISTS cw_history (
        cw_history_id INTEGER PRIMARY KEY AUTOINCREMENT,
        cw_id INTEGER NOT NULL,
        cw_history_action VARCHAR(50) NOT NULL,
        cw_history_actor INTEGER NOT NULL,
        cw_history_details TEXT NOT NULL,
        cw_history_timestamp TEXT NOT NULL
    )";
    $pdo->exec($sql);
    echo "Created cw_history table\n";
    
    // Insert the main wiki entry into cw_wikis
    $timestamp = date('YmdHis');
    $sql = "INSERT OR IGNORE INTO cw_wikis (
        wiki_dbname, wiki_sitename, wiki_language, wiki_private, 
        wiki_creation, wiki_dbcluster, wiki_category
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'my_wiki_main',
        'GnosPedia',
        'en',
        0,
        $timestamp,
        'c1',
        'general'
    ]);
    echo "Inserted main wiki entry\n";
    
    echo "CreateWiki tables setup in central database completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}