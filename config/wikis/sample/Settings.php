<?php
/**
 * Sample Wiki Settings
 */

# Wiki name and basic settings
$wgSitename = "Sample Wiki";
$wgMetaNamespace = "Sample_Wiki";

# Disable CreateWiki and ManageWiki to avoid database errors
$wgCreateWikiDisableManageWiki = true;
$wgCreateWikiDisableCreateWiki = true;

# Basic permissions
$wgGroupPermissions['*']['edit'] = false;
$wgGroupPermissions['user']['edit'] = true;

# Theme settings
$wgDefaultSkin = 'vector';
$wgGnosPediaThemeSettings = [
    'logo' => '/images/sample-logo.png',
    'primaryColor' => '#3366cc',
    'accentColor' => '#ff6600'
];

# Enable basic extensions
wfLoadExtension('ParserFunctions');
wfLoadExtension('Cite');
wfLoadExtension('WikiEditor');
wfLoadExtension('VisualEditor');

# Custom namespaces
define("NS_SAMPLE", 3000);
define("NS_SAMPLE_TALK", 3001);
$wgExtraNamespaces[NS_SAMPLE] = "Sample";
$wgExtraNamespaces[NS_SAMPLE_TALK] = "Sample_talk";

# Direct database configuration to bypass CreateWiki
$wgDBname = "my_wiki_sample";