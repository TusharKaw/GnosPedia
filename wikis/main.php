<?php
// Main wiki page
$host = $_SERVER['HTTP_HOST'] ?? 'unknown';
?>
<!DOCTYPE html>
<html>
<head>
    <title>GnosPedia Wiki Farm</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; line-height: 1.6; color: #333; }
        .header { background: #3366cc; color: white; padding: 20px 0; text-align: center; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .wiki-list { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .wiki-card { background: #f8f9fa; border-radius: 5px; padding: 20px; width: calc(33% - 20px); 
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .create-wiki { background: #f0f7ff; border: 2px dashed #3366cc; text-align: center; display: flex;
                      flex-direction: column; justify-content: center; align-items: center; }
        .btn { display: inline-block; background: #3366cc; color: white; padding: 10px 15px; 
              border-radius: 4px; text-decoration: none; margin-top: 10px; }
        h1, h2, h3 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>GnosPedia Wiki Farm</h1>
        <p>Create and manage your own wikis</p>
    </div>
    
    <div class="container">
        <h2>Welcome to GnosPedia</h2>
        <p>This is a wiki farm where you can create and manage multiple wikis using subdomains.</p>
        <p>Current host: <?php echo htmlspecialchars($host); ?></p>
        
        <h3>Available Wikis</h3>
        <div class="wiki-list">
            <div class="wiki-card">
                <h3>Main Wiki</h3>
                <p>The primary wiki for general information</p>
                <a href="http://localhost:4000/" class="btn">Visit Wiki</a>
            </div>
            
            <div class="wiki-card">
                <h3>Sample Wiki</h3>
                <p>A sample wiki demonstrating the farm functionality</p>
                <a href="http://sample.localhost:4000/" class="btn">Visit Wiki</a>
            </div>
            
            <div class="wiki-card create-wiki">
                <h3>Create a New Wiki</h3>
                <p>Start your own wiki with a custom subdomain</p>
                <a href="#" class="btn" onclick="createWiki(); return false;">Create Wiki</a>
            </div>
        </div>
    </div>
    
    <script>
        function createWiki() {
            const wikiName = prompt("Enter a name for your wiki (lowercase, no spaces):");
            if (wikiName && /^[a-z0-9-]+$/.test(wikiName)) {
                alert(`Your wiki will be available at: http://${wikiName}.localhost:4000/`);
                window.location.href = `http://${wikiName}.localhost:4000/`;
            } else if (wikiName) {
                alert("Wiki name must contain only lowercase letters, numbers, and hyphens.");
            }
        }
    </script>
</body>
</html>