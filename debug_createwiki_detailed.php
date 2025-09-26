<?php

// Set up MediaWiki environment
require_once __DIR__ . '/includes/WebStart.php';

echo "=== CreateWiki Central Wiki Debug ===\n\n";

// Basic configuration
echo "1. Basic Configuration:\n";
echo "   Current \$wgDBname: " . $wgDBname . "\n";
echo "   \$wgCreateWikiGlobalWiki: " . $wgCreateWikiGlobalWiki . "\n";
echo "   \$wgVirtualDomainsMapping: " . print_r($wgVirtualDomainsMapping, true) . "\n";

// Check database files
echo "2. Database Files:\n";
$dbFiles = [
    'my_wiki.sqlite' => 'data/my_wiki.sqlite',
    'my_wiki_main.sqlite' => 'data/my_wiki_main.sqlite'
];

foreach ($dbFiles as $name => $path) {
    echo "   $name: " . (file_exists($path) ? "EXISTS" : "MISSING") . "\n";
    if (file_exists($path)) {
        echo "     Size: " . filesize($path) . " bytes\n";
    }
}

// Check cw_wikis table
echo "\n3. CreateWiki Database Content:\n";
try {
    $dbr = MediaWiki\MediaWikiServices::getInstance()
        ->getDBLoadBalancer()
        ->getConnection(DB_REPLICA, [], 'virtual-createwiki');
    
    $result = $dbr->select('cw_wikis', '*', [], __METHOD__);
    echo "   cw_wikis table contents:\n";
    foreach ($result as $row) {
        echo "     wiki_dbname: {$row->wiki_dbname}\n";
        echo "     wiki_sitename: {$row->wiki_sitename}\n";
        echo "     wiki_language: {$row->wiki_language}\n";
    }
} catch (Exception $e) {
    echo "   Error accessing cw_wikis: " . $e->getMessage() . "\n";
}

// Check CreateWiki services
echo "\n4. CreateWiki Services:\n";
try {
    $services = MediaWiki\MediaWikiServices::getInstance();
    
    if ($services->hasService('CreateWikiDatabaseUtils')) {
        $dbUtils = $services->get('CreateWikiDatabaseUtils');
        echo "   CreateWikiDatabaseUtils service: AVAILABLE\n";
        
        try {
            $centralWikiID = $dbUtils->getCentralWikiID();
            echo "   Central Wiki ID: $centralWikiID\n";
            
            $isCentral = $dbUtils->isCurrentWikiCentral();
            echo "   Is Current Wiki Central: " . ($isCentral ? "YES" : "NO") . "\n";
            
        } catch (Exception $e) {
            echo "   Error calling CreateWiki methods: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   CreateWikiDatabaseUtils service: NOT AVAILABLE\n";
    }
} catch (Exception $e) {
    echo "   Error accessing services: " . $e->getMessage() . "\n";
}

// Check WikiMap
echo "\n5. WikiMap Information:\n";
try {
    $currentWikiId = WikiMap::getCurrentWikiId();
    echo "   Current Wiki ID: $currentWikiId\n";
    
    $currentDbDomain = WikiMap::getCurrentWikiDbDomain();
    echo "   Current DB Domain: " . $currentDbDomain->getId() . "\n";
    echo "   Current DB Domain Database: " . $currentDbDomain->getDatabase() . "\n";
    
} catch (Exception $e) {
    echo "   Error accessing WikiMap: " . $e->getMessage() . "\n";
}

echo "\n=== End Debug ===\n";