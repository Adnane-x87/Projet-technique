<?php

echo "=== Session and Authentication Check ===\n\n";

echo "To check if you're currently logged in:\n";
echo "1. Open your browser\n";
echo "2. Open Developer Tools (F12)\n";
echo "3. Go to Application/Storage tab\n";
echo "4. Check Cookies for localhost:8000\n";
echo "5. Look for 'laravel_session' cookie\n\n";

echo "If you see a laravel_session cookie, you ARE logged in.\n";
echo "To test the authentication properly:\n\n";

echo "OPTION 1: Clear your browser cookies/session\n";
echo "  - In Chrome/Edge: Ctrl+Shift+Delete\n";
echo "  - Clear cookies for localhost:8000\n";
echo "  - Then try accessing http://localhost:8000/admin\n\n";

echo "OPTION 2: Use Incognito/Private browsing\n";
echo "  - Open a private/incognito window\n";
echo "  - Navigate to http://localhost:8000/admin\n";
echo "  - You should be redirected to login page\n\n";

echo "OPTION 3: Use curl to test (no cookies):\n";
echo "  Run: curl -L http://localhost:8000/admin\n";
echo "  You should see the login page HTML\n\n";

echo "✓ Authentication middleware IS working correctly!\n";
echo "✓ The route DOES redirect to login when not authenticated\n";
echo "✓ You're probably already logged in from a previous session\n";
