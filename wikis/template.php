<?php
// Template for new wikis
$host = $_SERVER['HTTP_HOST'] ?? 'unknown';
$wikiName = preg_match('/^([a-z0-9-]+)\.localhost/', $host, $matches) ? ucfirst($matches[1]) : 'New';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $wikiName; ?> Wiki - GnosPedia</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; line-height: 1.6; color: #333; }
        .header { background: #3366cc; color: white; padding: 20px 0; text-align: center; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .content { background: #f8f9fa; border-radius: 5px; padding: 20px; margin-top: 20px; 
                  box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .sidebar { background: #f0f7ff; border-radius: 5px; padding: 20px; margin-top: 20px; 
                  box-shadow: 0 2px 4px rgba(0,0,0,0.1); float: right; width: 250px; }
        .btn { display: inline-block; background: #3366cc; color: white; padding: 8px 12px; 
              border-radius: 4px; text-decoration: none; }
        .footer { margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo $wikiName; ?> Wiki</h1>
        <p>Part of the GnosPedia Wiki Farm</p>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <h3>Navigation</h3>
            <ul>
                <li><a href="#">Main Page</a></li>
                <li><a href="#">Recent Changes</a></li>
                <li><a href="#">Community Portal</a></li>
                <li><a href="#">Help</a></li>
            </ul>
            
            <h3>Tools</h3>
            <ul>
                <li><a href="#">What links here</a></li>
                <li><a href="#">Special pages</a></li>
                <li><a href="#">Page information</a></li>
            </ul>
            
            <p><a href="http://localhost:4000/" class="btn">Wiki Farm Home</a></p>
        </div>
        
        <div class="content">
            <h2>Welcome to the <?php echo $wikiName; ?> Wiki</h2>
            <p>Your new wiki has been created successfully!</p>
            <p>Current host: <?php echo htmlspecialchars($host); ?></p>
            
            <h3>Getting Started</h3>
            <p>This is your new wiki in the GnosPedia Wiki Farm. You can start adding content right away.</p>
            
            <h3>Next Steps</h3>
            <ul>
                <li>Customize your wiki's appearance</li>
                <li>Create your first article</li>
                <li>Invite contributors</li>
                <li>Set up categories and templates</li>
            </ul>
        </div>
        
        <div style="clear: both;"></div>
        
        <div class="footer">
            <p>This wiki is part of the GnosPedia Wiki Farm. Each subdomain hosts its own wiki.</p>
        </div>
    </div>
</body>
</html>