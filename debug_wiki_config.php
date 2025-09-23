<?php

require_once __DIR__ . '/includes/WebStart.php';

use MediaWiki\WikiMap\WikiMap;

echo "Current Wiki ID: " . WikiMap::getCurrentWikiId() . "\n";
echo "Database Name: " . $wgDBname . "\n";
echo "Database Type: " . $wgDBtype . "\n";
echo "SQLite Data Dir: " . $wgSQLiteDataDir . "\n";

// Check virtual domains mapping
if (isset($wgVirtualDomainsMapping)) {
    echo "Virtual Domains Mapping:\n";
    print_r($wgVirtualDomainsMapping);
} else {
    echo "No virtual domains mapping set\n";
}

// Try to get a connection to the virtual domain
try {
    $services = MediaWiki\MediaWikiServices::getInstance();
    $dbProvider = $services->getConnectionProvider();
    $db = $dbProvider->getReplicaDatabase('virtual-createwiki');
    echo "Successfully connected to virtual-createwiki\n";
    echo "Database name from connection: " . $db->getDBname() . "\n";
    
    // Check if cw_wikis table exists
    try {
        $result = $db->select('cw_wikis', '1', [], __METHOD__, ['LIMIT' => 1]);
        echo "cw_wikis table exists: Yes\n";
    } catch (Exception $e) {
        echo "cw_wikis table exists: No - " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error connecting to virtual-createwiki: " . $e->getMessage() . "\n";
}