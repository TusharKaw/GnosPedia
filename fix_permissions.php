<?php
// Fix syntax in LocalSettings.php
$localSettingsPath = '/var/www/html/LocalSettings.php';

// Read the current content
$content = file_get_contents($localSettingsPath);

// Fix the syntax for $wgGroupPermissions
$content = str_replace(
    [
        "\$wgGroupPermissions[*][createwiki] = true;",
        "\$wgGroupPermissions[*][managewiki] = true;"
    ],
    [
        "\$wgGroupPermissions['*']['createwiki'] = true;",
        "\$wgGroupPermissions['*']['managewiki'] = true;"
    ],
    $content
);

// Fix the $wgCacheEpoch line to use proper quotes
$content = preg_replace(
    '/\$wgCacheEpoch = max\( \$wgCacheEpoch, gmdate\( YmdHis, time\(\) \) \);/',
    '\$wgCacheEpoch = max( \$wgCacheEpoch, gmdate( "YmdHis", time() ) );',
    $content
);

// Write the fixed content back to the file
file_put_contents($localSettingsPath, $content);

echo "LocalSettings.php has been fixed.\n";
?>
