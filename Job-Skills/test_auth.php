<?php

// Simple test to check admin route access without authentication
echo "Testing admin route authentication...\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/admin");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
echo "Redirect URL: " . ($redirectUrl ?: "None") . "\n\n";

if ($httpCode === 302 || $httpCode === 301) {
    // Check if redirecting to login
    if (strpos($response, 'login') !== false) {
        echo "✓ GOOD: Route redirects to login page\n";
    } else {
        echo "✗ BAD: Route redirects but not to login\n";
    }
} elseif ($httpCode === 200) {
    echo "✗ BAD: Route is accessible without authentication!\n";
    echo "This is a security issue.\n";
} else {
    echo "? UNKNOWN: Unexpected response code\n";
}

echo "\nResponse headers:\n";
echo substr($response, 0, 500);
