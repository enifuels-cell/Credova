<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginDebugTest extends TestCase
{
    public function test_login_error_details()
    {
        // Get the login page first
        $getResponse = $this->get('/login');
        echo "\nGot login page status: " . $getResponse->getStatusCode() . "\n";

        // Post login
        try {
            $response = $this->post('/login', [
                'email' => 'admin@example.com',
                'password' => 'password',
            ]);

            echo "Response status: " . $response->getStatusCode() . "\n";

            if ($response->getStatusCode() === 500) {
                echo "\n✗ Got 500 error - SERVER ERROR\n";
                echo "\nResponse content:\n";
                $content = $response->getContent();

                // Try to extract error message from HTML
                if (preg_match('/<h1[^>]*>([^<]+)<\/h1>/i', $content, $matches)) {
                    echo "Error title: " . trim($matches[1]) . "\n";
                }

                // Show first 2000 chars
                echo substr($content, 0, 2000) . "\n";
            }
        } catch (\Exception $e) {
            echo "Exception during login: " . $e->getMessage() . "\n";
            echo "Exception trace:\n";
            echo $e->getTraceAsString() . "\n";
        }

        $this->assertTrue(true);
    }
}
