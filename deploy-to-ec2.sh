#!/bin/bash

# GnosPedia EC2 Deployment Script
# This script deploys the complete GnosPedia wiki farm to EC2

set -e  # Exit on any error

# Configuration
EC2_HOST="13.233.126.84"
EC2_USER="ec2-user"
EC2_KEY="/Users/tusharkaw/Downloads/gnospedia.pem"
REMOTE_PATH="/var/www/html/gnospedia"
LOCAL_PATH="/Users/tusharkaw/Downloads/GnosPedia"

echo "🚀 Starting GnosPedia EC2 Deployment..."
echo "======================================"
echo "EC2 Host: $EC2_HOST"
echo "Remote Path: $REMOTE_PATH"
echo "Local Path: $LOCAL_PATH"
echo ""

# Check if key file exists
if [ ! -f "$EC2_KEY" ]; then
    echo "❌ Error: SSH key file '$EC2_KEY' not found!"
    echo "Please ensure the key file is in the current directory."
    exit 1
fi

# Set proper permissions for the key
chmod 600 "$EC2_KEY"

echo "📋 Step 1: Preparing EC2 server..."
echo "--------------------------------"

# Connect to EC2 and prepare the environment
ssh -i "$EC2_KEY" "$EC2_USER@$EC2_HOST" << 'EOF'
    echo "Installing required packages..."
    sudo yum update -y
    sudo yum install -y httpd php php-cli php-common php-mysql php-pdo php-gd php-mbstring php-xml php-curl php-zip php-sqlite3 sqlite3
    
    echo "Starting and enabling Apache..."
    sudo systemctl start httpd
    sudo systemctl enable httpd
    
    echo "Creating web directory..."
    sudo mkdir -p /var/www/html/gnospedia
    sudo chown -R apache:apache /var/www/html/gnospedia
    sudo chmod -R 755 /var/www/html/gnospedia
    
    echo "Creating necessary directories..."
    sudo mkdir -p /var/www/html/gnospedia/{cache,data,images,logs}
    sudo chown -R apache:apache /var/www/html/gnospedia/{cache,data,images,logs}
    sudo chmod -R 777 /var/www/html/gnospedia/{cache,data,images,logs}
    
    echo "Setting up Apache virtual host..."
    sudo tee /etc/httpd/conf.d/gnospedia.conf > /dev/null << 'APACHE_EOF'
<VirtualHost *:80>
    ServerName 13.233.126.84
    DocumentRoot /var/www/html/gnospedia
    
    <Directory /var/www/html/gnospedia>
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>
    
    # Enable mod_rewrite
    RewriteEngine On
    
    # Handle subdomain routing for wiki farm
    RewriteCond %{HTTP_HOST} ^([a-z0-9-]+)\.13\.233\.126\.84$ [NC]
    RewriteRule ^(.*)$ /index.php [QSA,L]
    
    # Standard MediaWiki rewrites
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php [QSA,L]
    
    ErrorLog /var/log/httpd/gnospedia_error.log
    CustomLog /var/log/httpd/gnospedia_access.log combined
</VirtualHost>
APACHE_EOF
    
    echo "Restarting Apache..."
    sudo systemctl restart httpd
    
    echo "Setting up firewall..."
    sudo firewall-cmd --permanent --add-service=http
    sudo firewall-cmd --permanent --add-service=https
    sudo firewall-cmd --reload
    
    echo "✅ EC2 server preparation complete!"
EOF

echo ""
echo "📦 Step 2: Transferring files to EC2..."
echo "-------------------------------------"

# Create a temporary directory for files to exclude
EXCLUDE_FILE=$(mktemp)
cat > "$EXCLUDE_FILE" << 'EOF'
.DS_Store
*.DS_Store
.git/
.gitignore
*.log
*.tmp
*.swp
*.swo
*~
.vscode/
.idea/
node_modules/
vendor/composer/
cache/.DS_Store
logs/.DS_Store
images/.DS_Store
extensions/.DS_Store
skins/.DS_Store
EOF

echo "Transferring core MediaWiki files..."
rsync -avz --progress --exclude-from="$EXCLUDE_FILE" \
    "$LOCAL_PATH/" \
    -e "ssh -i $EC2_KEY" \
    "$EC2_USER@$EC2_HOST:$REMOTE_PATH/"

echo ""
echo "📁 Step 3: Transferring extensions..."
echo "-----------------------------------"

# Transfer extensions directory
rsync -avz --progress --exclude=".DS_Store" \
    "$LOCAL_PATH/extensions/" \
    -e "ssh -i $EC2_KEY" \
    "$EC2_USER@$EC2_HOST:$REMOTE_PATH/extensions/"

echo ""
echo "🎨 Step 4: Transferring skins..."
echo "------------------------------"

# Transfer skins directory
rsync -avz --progress --exclude=".DS_Store" \
    "$LOCAL_PATH/skins/" \
    -e "ssh -i $EC2_KEY" \
    "$EC2_USER@$EC2_HOST:$REMOTE_PATH/skins/"

echo ""
echo "💾 Step 5: Transferring databases..."
echo "----------------------------------"

# Transfer database files
rsync -avz --progress \
    "$LOCAL_PATH/data/" \
    -e "ssh -i $EC2_KEY" \
    "$EC2_USER@$EC2_HOST:$REMOTE_PATH/data/"

echo ""
echo "🖼️ Step 6: Transferring images..."
echo "-------------------------------"

# Transfer images directory
rsync -avz --progress --exclude=".DS_Store" \
    "$LOCAL_PATH/images/" \
    -e "ssh -i $EC2_KEY" \
    "$EC2_USER@$EC2_HOST:$REMOTE_PATH/images/"

echo ""
echo "⚙️ Step 7: Updating configuration for EC2..."
echo "------------------------------------------"

# Create EC2-specific LocalSettings.php
ssh -i "$EC2_KEY" "$EC2_USER@$EC2_HOST" << 'EOF'
    cd /var/www/html/gnospedia
    
    # Backup original LocalSettings.php
    cp LocalSettings.php LocalSettings.php.backup
    
    # Update LocalSettings.php for EC2 environment
    sed -i 's|/opt/homebrew/bin/convert|/usr/bin/convert|g' LocalSettings.php
    sed -i 's|$wgImageMagickConvertCommand = "/opt/homebrew/bin/convert";|$wgImageMagickConvertCommand = "/usr/bin/convert";|g' LocalSettings.php
    
    # Update paths for EC2
    sed -i 's|__DIR__ . '\''/data'\''|__DIR__ . '\''/data'\''|g' LocalSettings.php
    
    # Ensure proper permissions
    sudo chown -R apache:apache /var/www/html/gnospedia
    sudo chmod -R 755 /var/www/html/gnospedia
    sudo chmod -R 777 /var/www/html/gnospedia/{cache,data,images,logs}
    
    echo "✅ Configuration updated for EC2!"
EOF

echo ""
echo "🔧 Step 8: Installing ImageMagick..."
echo "----------------------------------"

ssh -i "$EC2_KEY" "$EC2_USER@$EC2_HOST" << 'EOF'
    echo "Installing ImageMagick..."
    sudo yum install -y ImageMagick ImageMagick-devel
    
    echo "Verifying ImageMagick installation..."
    /usr/bin/convert -version
    
    echo "✅ ImageMagick installed successfully!"
EOF

echo ""
echo "🧹 Step 9: Final cleanup and permissions..."
echo "----------------------------------------"

ssh -i "$EC2_KEY" "$EC2_USER@$EC2_HOST" << 'EOF'
    cd /var/www/html/gnospedia
    
    # Set proper ownership
    sudo chown -R apache:apache /var/www/html/gnospedia
    
    # Set proper permissions
    sudo find /var/www/html/gnospedia -type d -exec chmod 755 {} \;
    sudo find /var/www/html/gnospedia -type f -exec chmod 644 {} \;
    
    # Special permissions for writable directories
    sudo chmod -R 777 /var/www/html/gnospedia/{cache,data,images,logs}
    
    # Restart Apache
    sudo systemctl restart httpd
    
    echo "✅ Final cleanup complete!"
EOF

# Clean up temporary file
rm "$EXCLUDE_FILE"

echo ""
echo "🎉 Deployment Complete!"
echo "======================"
echo ""
echo "Your GnosPedia wiki farm is now deployed to EC2!"
echo ""
echo "📍 Access URLs:"
echo "   Main Wiki:     http://13.233.126.84"
echo "   Create Wiki:   http://13.233.126.84/index.php/Special:CreateWiki"
echo "   Manage Wiki:   http://13.233.126.84/index.php/Special:ManageWiki"
echo ""
echo "🌐 Subdomain Wikis (after creation):"
echo "   • Movies Wiki: http://movies.13.233.126.84"
echo "   • Games Wiki:  http://games.13.233.126.84"
echo "   • Books Wiki:  http://books.13.233.126.84"
echo "   • Tech Wiki:   http://tech.13.233.126.84"
echo ""
echo "📚 Extensions Deployed:"
echo "   ✅ CreateWiki       - Automated wiki creation"
echo "   ✅ ManageWiki       - Wiki management"
echo "   ✅ Evelution Skin   - Custom skin with styling"
echo "   ✅ Translate        - Translation support"
echo "   ✅ UniversalLanguageSelector - Language selection"
echo "   ✅ AbuseFilter      - Content filtering"
echo "   ✅ Echo             - Notifications"
echo "   ✅ Gadgets          - User gadgets"
echo "   ✅ And many more..."
echo ""
echo "🔧 Server Information:"
echo "   • Web Server: Apache 2.4"
echo "   • PHP Version: $(ssh -i "$EC2_KEY" "$EC2_USER@$EC2_HOST" 'php -v | head -1')"
echo "   • Database: SQLite"
echo "   • Document Root: /var/www/html/gnospedia"
echo ""
echo "📝 Next Steps:"
echo "   1. Visit http://13.233.126.84 to access your wiki farm"
echo "   2. Create a user account"
echo "   3. Create your first wiki using Special:CreateWiki"
echo "   4. Configure DNS for subdomains (optional)"
echo ""
echo "🛠️ Troubleshooting:"
echo "   • Check Apache logs: sudo tail -f /var/log/httpd/gnospedia_error.log"
echo "   • Check MediaWiki logs: sudo tail -f /var/www/html/gnospedia/logs/error.log"
echo "   • Restart Apache: sudo systemctl restart httpd"
echo ""
echo "✅ Deployment successful! Your GnosPedia wiki farm is ready to use!"
