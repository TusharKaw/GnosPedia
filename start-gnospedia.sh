#!/bin/bash

# GnosPedia Wiki Farm Startup Script

echo "🚀 Starting GnosPedia Wiki Farm..."
echo "=================================="

# Kill any existing PHP servers
echo "📋 Stopping existing servers..."
pkill -f "php -S" 2>/dev/null || true

# Wait a moment
sleep 2

# Start the server with router
echo "🌐 Starting MediaWiki server on http://localhost:4000..."
php -S 127.0.0.1:4000 router.php &
SERVER_PID=$!

# Wait for server to start
sleep 3

# Test if server is running
if curl -s -f -I http://localhost:4000 | grep -q "200 OK"; then
    echo "✅ Server started successfully!"
    echo ""
    echo "🎉 GnosPedia Wiki Farm is now running!"
    echo "=================================="
    echo ""
    echo "📍 Main Wiki Hub:      http://localhost:4000"
    echo "🚀 Create New Wiki:    http://localhost:4000/index.php/Special:CreateWiki"
    echo "⚙️  Manage Wikis:       http://localhost:4000/index.php/Special:ManageWiki"
    echo "👤 Create Account:     http://localhost:4000/index.php/Special:CreateAccount"
    echo "📝 Login:              http://localhost:4000/index.php/Special:UserLogin"
    echo ""
    echo "🌐 Subdomain Wikis (after creation):"
    echo "   • Movies Wiki:      http://movies.localhost:4000"
    echo "   • Games Wiki:       http://games.localhost:4000"
    echo "   • Books Wiki:       http://books.localhost:4000"
    echo "   • Tech Wiki:        http://tech.localhost:4000"
    echo ""
    echo "📚 Extensions Active:"
    echo "   ✅ MediaWikiFarm    - Subdomain routing"
    echo "   ✅ CreateWiki       - Automated wiki creation"
    echo "   ✅ ManageWiki       - Wiki management"
    echo "   ✅ GnosPediaTheme   - Custom styling"
    echo ""
    echo "📝 Note: Make sure you have added subdomain entries to /etc/hosts"
    echo "    127.0.0.1 movies.localhost"
    echo "    127.0.0.1 games.localhost"
    echo "    127.0.0.1 books.localhost"
    echo "    127.0.0.1 tech.localhost"
    echo ""
    echo "🛑 To stop the server: kill $SERVER_PID or press Ctrl+C"
    echo ""
    
    # Keep script running
    wait $SERVER_PID
else
    echo "❌ Failed to start server!"
    exit 1
fi
