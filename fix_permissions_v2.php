<?php
// Fix syntax in LocalSettings.php
$localSettingsPath = '/var/www/html/LocalSettings.php';

// Read the current content
$content = file_get_contents($localSettingsPath);

// Fix the syntax for $wgGroupPermissions with 'user' group
$content = str_replace(
    [
        "\$wgGroupPermissions[user][createwiki]",
        "\$wgGroupPermissions[user][managewiki]"
    ],
    [
        "\$wgGroupPermissions['user']['createwiki']",
        "\$wgGroupPermissions['user']['managewiki']"
    ],
    $content
);

// Write the fixed content back to the file
file_put_contents($localSettingsPath, $content);

echo "LocalSettings.php has been fixed.\n";
?>
