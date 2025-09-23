<?php

require_once __DIR__ . '/includes/WebStart.php';

try {
    $services = MediaWiki\MediaWikiServices::getInstance();
    $connectionProvider = $services->getConnectionProvider();
    
    echo "Testing virtual database connections...\n";
    
    // Test main database
    echo "Testing main database connection...\n";
    $mainDb = $connectionProvider->getReplicaDatabase();
    echo "Main DB name: " . $mainDb->getDBname() . "\n";
    
    // Test virtual-createwiki
    echo "Testing virtual-createwiki connection...\n";
    try {
        $virtualDb = $connectionProvider->getReplicaDatabase('virtual-createwiki');
        echo "Virtual DB name: " . $virtualDb->getDBname() . "\n";
        
        // Try to query cw_wikis
        $result = $virtualDb->select('cw_wikis', 'COUNT(*) as count', [], __METHOD__);
        $row = $result->fetchObject();
        echo "cw_wikis table has " . $row->count . " rows\n";
        
    } catch (Exception $e) {
        echo "Error with virtual-createwiki: " . $e->getMessage() . "\n";
    }
    
    // Test virtual-createwiki-central
    echo "Testing virtual-createwiki-central connection...\n";
    try {
        $centralDb = $connectionProvider->getReplicaDatabase('virtual-createwiki-central');
        echo "Central DB name: " . $centralDb->getDBname() . "\n";
        
        // Try to query cw_wikis
        $result = $centralDb->select('cw_wikis', 'COUNT(*) as count', [], __METHOD__);
        $row = $result->fetchObject();
        echo "cw_wikis table has " . $row->count . " rows\n";
        
    } catch (Exception $e) {
        echo "Error with virtual-createwiki-central: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
}