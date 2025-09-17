<?php
# Farm-specific settings for subdomain wikis
# This file is loaded for each subdomain wiki

# Get the wiki ID from MediaWikiFarm
$wikiID = $wgMediaWikiFarmWikiID ?? 'unknown';

# Set wiki-specific database
$wgDBname = $wikiID . '_wiki';
$wgDBtype = 'sqlite';
$wgSQLiteDataDir = __DIR__ . '/../cache/wikis';

# Set wiki-specific site name
$wgSitename = ucfirst($wikiID) . ' Wiki';
$wgMetaNamespace = ucfirst($wikiID);

# Set wiki-specific upload directory
$wgUploadDirectory = __DIR__ . "/../images/$wikiID";
$wgUploadPath = "/images/$wikiID";

# Create upload directory if it doesn't exist
if (!is_dir($wgUploadDirectory)) {
    mkdir($wgUploadDirectory, 0755, true);
}

# Wiki-specific settings
$wgLogo = "$wgResourceBasePath/resources/assets/logo-$wikiID.png";

# Enable CreateWiki and ManageWiki for subdomain wikis
wfLoadExtension('CreateWiki');
wfLoadExtension('ManageWiki');

# Basic CreateWiki configuration for subdomain wikis
$wgCreateWikiDatabase = 'sqlite';
$wgCreateWikiSQLiteDataDir = __DIR__ . '/../cache/wikis';
$wgCreateWikiGlobalWiki = 'main';

# ManageWiki configuration for subdomain wikis
$wgManageWiki = [
    'core' => true,
    'extensions' => true,
    'namespaces' => true,
    'permissions' => true,
    'settings' => true,
];

# Permissions for subdomain wikis
$wgGroupPermissions['user']['createwiki'] = false; // Only main wiki can create new wikis
$wgGroupPermissions['sysop']['managewiki'] = true;
$wgGroupPermissions['bureaucrat']['managewiki'] = true;