<?php
/**
 * GnosPedia Wiki Farm - LocalSettings.php
 * Starting with basic configuration, then adding farm support
 */

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

# MediaWikiFarm will handle subdomain routing automatically
# Load base configuration first
require_once __DIR__ . '/config/LocalSettings.php';

# Load the farm extensions (enabling in correct order)
wfLoadExtension('MediaWikiFarm');
wfLoadExtension('CreateWiki');
wfLoadExtension('ManageWiki');
wfLoadExtension('GnosPediaTheme');

# MediaWikiFarm configuration
$wgMediaWikiFarmConfigDir = __DIR__ . '/config';
$wgMediaWikiFarmCacheDir = __DIR__ . '/cache/farm';
$wgMediaWikiFarmSyslog = true;
$wgMediaWikiFarmLogFile = __DIR__ . '/cache/farm.log';

# MediaWikiFarm will handle CreateWiki configuration automatically

# MediaWikiFarm will handle ManageWiki configuration automatically

# Enhanced main page for main wiki
$wgMainPageIsDomainRoot = true;

# Add a simple "Start a Wiki" link to the main page
$wgHooks['SkinTemplateNavigation::Universal'][] = function($sktemplate, &$links) {
    $links['namespaces']['start-wiki'] = [
        'text' => 'Start a Wiki',
        'href' => '/index.php?title=Special:CreateAccount&returnto=Start+a+Wiki',
        'class' => 'gnospedia-start-wiki-link'
    ];
};

# Enhanced upload settings
$wgEnableUploads = true;
$wgUseImageMagick = false; // Use GD instead for simplicity
$wgGenerateThumbnailOnParse = true;
$wgAllowExternalImages = true;
$wgAllowImageTag = true;

# File upload restrictions
$wgFileExtensions = [
    'png', 'gif', 'jpg', 'jpeg', 'webp', 'svg',
    'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
    'txt', 'rtf', 'odt', 'ods', 'odp',
    'mp3', 'wav', 'ogg', 'mp4', 'webm', 'ogv'
];

$wgMaxUploadSize = 50 * 1024 * 1024; // 50MB

# Performance optimizations
$wgMainCacheType = CACHE_ACCEL;
$wgMemCachedServers = [];
$wgUseLocalMessageCache = true;
$wgCacheDirectory = __DIR__ . '/cache';

# Security enhancements
$wgAllowUserJs = false;
$wgAllowUserCss = true;
$wgRestrictDisplayTitle = false;
$wgRawHtml = false;

# Add custom CSS for GnosPedia styling
$wgHooks['BeforePageDisplay'][] = function($out, $skin) {
    $out->addInlineStyle('
        .gnospedia-start-wiki-link a {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 20px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 12px !important;
            letter-spacing: 0.5px !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
        }
        
        .gnospedia-start-wiki-link a:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3) !important;
        }
        
        .mw-body h1.firstHeading {
            color: #667eea;
            border-bottom: 2px solid #764ba2;
            padding-bottom: 0.5em;
        }
        
        .mw-body a {
            color: #667eea;
        }
        
        .mw-body a:hover {
            color: #764ba2;
        }
        
        #mw-head {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .mw-body {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 1em;
            padding: 2em;
        }
    ');
};