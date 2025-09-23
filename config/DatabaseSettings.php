<?php
/**
 * GnosPedia Wiki Farm - DatabaseSettings.php
 * This file contains database configuration for multiple wikis
 */

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

# Base database configuration
$wgDBtype = 'sqlite';
$wgSQLiteDataDir = __DIR__ . '/../cache/wikis';

# Ensure the database directory exists
if (!is_dir($wgSQLiteDataDir)) {
    mkdir($wgSQLiteDataDir, 0755, true);
}

# Database configuration for wiki farm
$wgDBservers = [
    [
        'type' => 'sqlite',
        'dbname' => $wgDBname,
        'tablePrefix' => '',
        'flags' => DBO_DEFAULT,
        'load' => 1,
    ],
];

# Shared database for user accounts across wikis
$wgSharedDB = 'main_wiki';
$wgSharedTables = [
    'user',
    'user_properties',
    'user_groups',
    'interwiki',
    'iwlinks'
];

# Database caching
$wgMemCachedServers = [];
$wgMainCacheType = CACHE_ACCEL;
$wgSessionCacheType = CACHE_DB;

# Object caching
$wgObjectCaches['sqlite'] = [
    'class' => 'SqlBagOStuff',
    'server' => [
        'type' => 'sqlite',
        'dbname' => 'wikicache',
        'tablePrefix' => '',
        'variables' => [ 'synchronous' => 'NORMAL' ],
    ],
    'loggroup' => 'SQLBagOStuff',
];
$wgMainCacheType = 'sqlite';

# Job queue settings
$wgJobTypeConf['default'] = [
    'class' => 'JobQueueDB',
    'order' => 'random',
    'claimTTL' => 3600,
    'readOnlyReason' => false,
];