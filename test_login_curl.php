<?php
/**
 * Direct HTTP POST test to /login
 */

$url = 'http://localhost:8000/login';

echo "Testing POST to $url\n";
echo str_repeat("=", 60) . "\n\n";

// Step 1: Get the login page to extract CSRF token
echo "Step 1: Getting login page to extract CSRF token...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

// Extract CSRF token from hidden input
if (preg_match('/<input[^>]+name="_token"[^>]+value="([^"]+)"/', $response, $matches)) {
    $token = $matches[1];
    echo "CSRF Token found: " . substr($token, 0, 20) . "...\n";
} else {
    echo "ERROR: Could not find CSRF token in form!\n";
    echo "Response snippet:\n";
    echo substr($response, 0, 1000) . "\n";
    exit(1);
}

// Check for session cookie
if (preg_match('/Set-Cookie: ([^=]+)=([^;]+)/i', $response, $matches)) {
    echo "Session Cookie set: " . $matches[1] . "=" . substr($matches[2], 0, 20) . "...\n";
} else {
    echo "WARNING: No session cookie set in response\n";
}

echo "\nStep 2: Posting login credentials with CSRF token...\n";

// Step 2: POST the credentials with CSRF token
$postData = [
    'email' => 'admin@example.com',
    'password' => 'password',
    '_token' => $token
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/login");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

// Check response for errors
if ($httpCode === 419) {
    echo "\n❌ ERROR: Got 419 (Page Expired) error!\n";
    echo "\nResponse Headers:\n";
    $headerEnd = strpos($response, "\r\n\r\n");
    if ($headerEnd !== false) {
        echo substr($response, 0, $headerEnd);
    }
} else if ($httpCode === 302 || $httpCode === 301) {
    echo "✓ Redirect received (expected after successful login)\n";
    if (preg_match('/Location: ([^\r\n]+)/', $response, $matches)) {
        echo "Redirecting to: " . trim($matches[1]) . "\n";
    }
} else if ($httpCode === 200) {
    echo "✓ Got 200 response\n";
    // Check if it's the login form again (failed login) or error
    if (strpos($response, 'provided credentials do not match') !== false) {
        echo "⚠ Login failed - credentials do not match (but CSRF passed!)\n";
    }
} else {
    echo "Got unexpected HTTP code: $httpCode\n";
}

echo "\n";
