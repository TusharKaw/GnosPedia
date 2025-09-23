<?php
// Simple test file to verify subdomain routing
$host = $_SERVER['HTTP_HOST'] ?? 'unknown';
?>
<!DOCTYPE html>
<html>
<head>
    <title>GnosPedia Wiki Farm Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .success { color: green; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>GnosPedia Wiki Farm - Subdomain Test</h1>
    
    <h2>Host Information:</h2>
    <p class="success">Current Host: <?php echo htmlspecialchars($host); ?></p>
    
    <h2>Server Variables:</h2>
    <pre><?php print_r($_SERVER); ?></pre>
    
    <p class="info">If you see this page, your web server is correctly routing the request to this file.</p>
</body>
</html>