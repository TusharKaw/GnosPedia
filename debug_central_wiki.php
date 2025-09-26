<?php

require_once __DIR__ . '/includes/WebStart.php';

use MediaWiki\WikiMap\WikiMap;

echo "=== Wiki Configuration Debug ===\n";
echo "Current DB name: " . $wgDBname . "\n";
echo "CreateWiki Global Wiki setting: " . $wgCreateWikiGlobalWiki . "\n";
echo "Current Wiki ID: " . WikiMap::getCurrentWikiId() . "\n";

// Check virtual domains mapping
if (isset($wgVirtualDomainsMapping)) {
    echo "\nVirtual Domains Mapping:\n";
    foreach ($wgVirtualDomainsMapping as $domain => $config) {
        echo "  $domain => " . $config['db'] . "\n";
    }
} else {
    echo "No virtual domains mapping set\n";
}

// Try to get the central wiki ID from CreateWiki
try {
    $services = MediaWiki\MediaWikiServices::getInstance();
    $databaseUtils = $services->get('CreateWikiDatabaseUtils');
    $centralWikiID = $databaseUtils->getCentralWikiID();
    echo "\nCentral Wiki ID from CreateWiki: " . $centralWikiID . "\n";
    echo "Is current wiki central? " . ($databaseUtils->isCurrentWikiCentral() ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "\nError getting CreateWiki info: " . $e->getMessage() . "\n";
}