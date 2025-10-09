<?php
$file = '/var/www/html/LocalSettings.php';
$content = file_get_contents($file);

// Fix the syntax errors
$content = str_replace('{$wgDBname}_jobqueue', '$wgDBname . "_jobqueue"', $content);
$content = str_replace('{\$wgDBname}_jobqueue', '$wgDBname . "_jobqueue"', $content);

file_put_contents($file, $content);

echo "Syntax errors fixed.\n";
?>
