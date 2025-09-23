<?php
// Simple sample wiki page
$host = $_SERVER['HTTP_HOST'] ?? 'unknown';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sample Wiki</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        h1 { color: #3366cc; }
        .content { max-width: 800px; margin: 0 auto; }
        .card { background: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="content">
        <h1>Sample Wiki</h1>
        
        <div class="card">
            <h2>Welcome to the Sample Wiki</h2>
            <p>This is a simple sample wiki page for GnosPedia.</p>
            <p>Current host: <?php echo htmlspecialchars($host); ?></p>
        </div>
        
        <div class="card">
            <h2>Wiki Features</h2>
            <ul>
                <li>Sample content</li>
                <li>Demonstration pages</li>
                <li>Example templates</li>
            </ul>
        </div>
    </div>
</body>
</html>