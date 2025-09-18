<?php
# Main wiki settings (for localhost:4000)
# This file is loaded for the main wiki

# Main wiki database
$wgDBname = 'my_wiki';
$wgDBtype = 'sqlite';
$wgSQLiteDataDir = __DIR__ . '/../cache';

# Main wiki site name
$wgSitename = 'GnosPedia Wiki Farm';
$wgMetaNamespace = 'GnosPedia';

# Main wiki upload directory
$wgUploadDirectory = __DIR__ . '/../images/main';
$wgUploadPath = '/images/main';

# Create upload directory if it doesn't exist
if (!is_dir($wgUploadDirectory)) {
    mkdir($wgUploadDirectory, 0755, true);
}

# Main wiki logo
$wgLogo = "$wgResourceBasePath/resources/assets/gnospedia-logo.png";

# Enable all farm extensions for main wiki
wfLoadExtension('CreateWiki');
wfLoadExtension('ManageWiki');
wfLoadExtension('GnosPediaTheme');

# CreateWiki configuration for main wiki
$wgCreateWikiDatabase = 'sqlite';
$wgCreateWikiSQLiteDataDir = __DIR__ . '/../cache/wikis';
$wgCreateWikiGlobalWiki = 'main';
$wgCreateWikiEmailNotifications = false;
$wgCreateWikiUseCategories = true;
$wgCreateWikiUsePrivateWikis = true;
$wgCreateWikiUseClosedWikis = true;
$wgCreateWikiUseInactiveWikis = true;

# ManageWiki configuration for main wiki
$wgManageWiki = [
    'core' => true,
    'extensions' => true,
    'namespaces' => true,
    'permissions' => true,
    'settings' => true,
];

# Permissions for main wiki (can create new wikis)
$wgGroupPermissions['user']['createwiki'] = true;
$wgGroupPermissions['sysop']['managewiki'] = true;
$wgGroupPermissions['bureaucrat']['managewiki'] = true;

# Enhanced user rights for farm administration
$wgGroupPermissions['wikifarm-admin'] = $wgGroupPermissions['sysop'];
$wgGroupPermissions['wikifarm-admin']['managewiki'] = true;
$wgGroupPermissions['wikifarm-admin']['createwiki'] = true;
$wgGroupPermissions['wikifarm-admin']['wikifarmstats'] = true;

# Global user groups
$wgAddGroups['bureaucrat'][] = 'wikifarm-admin';
$wgRemoveGroups['bureaucrat'][] = 'wikifarm-admin';

# Wiki creation limits
$wgCreateWikiLimits = [
    'user' => 2,  // Regular users can create 2 wikis
    'autoconfirmed' => 5,  // Autoconfirmed users can create 5 wikis
    'wikifarm-admin' => 50  // Farm admins can create 50 wikis
];

