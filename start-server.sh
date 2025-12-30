#!/bin/bash

# VISHVAVIRAT SECURITY - Local Server Starter
# This script starts a local web server for testing

echo "🚀 Starting VISHVAVIRAT SECURITY Website..."
echo ""
echo "📂 Project Directory: $(pwd)"
echo ""

# Check if port 8000 is already in use
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null ; then
    echo "⚠️  Port 8000 is already in use. Trying port 8080..."
    PORT=8080
else
    PORT=8000
fi

echo "🌐 Server will start on: http://localhost:$PORT"
echo ""
echo "📱 To test on mobile (same WiFi):"
LOCAL_IP=$(ifconfig | grep "inet " | grep -v 127.0.0.1 | awk '{print $2}' | head -1)
echo "   http://$LOCAL_IP:$PORT"
echo ""
echo "✅ Pages you can test:"
echo "   http://localhost:$PORT/index.html"
echo "   http://localhost:$PORT/services/personal-bouncer.html"
echo ""
echo "⏹️  Press Ctrl+C to stop the server"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Try Python3 first, then Python2, then PHP
if command -v python3 &> /dev/null; then
    echo "🐍 Starting Python 3 server..."
    python3 -m http.server $PORT
elif command -v python &> /dev/null; then
    echo "🐍 Starting Python 2 server..."
    python -m SimpleHTTPServer $PORT
elif command -v php &> /dev/null; then
    echo "🐘 Starting PHP server..."
    php -S localhost:$PORT
else
    echo "❌ Error: No suitable server found (Python or PHP required)"
    echo "Please install Python or PHP"
    exit 1
fi
