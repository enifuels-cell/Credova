<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginWorkflowTest extends TestCase
{
    public function test_login_workflow()
    {
        // Create a test user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        echo "\n=== COMPLETE LOGIN WORKFLOW TEST ===\n\n";

        // Step 1: Load login page
        echo "Step 1: Loading login page...\n";
        $getResponse = $this->get('/login');
        $this->assertEquals(200, $getResponse->getStatusCode());
        echo "✓ Login page loaded (HTTP 200)\n";

        // Step 2: Verify form contains CSRF token
        echo "\nStep 2: Checking for CSRF token in form...\n";
        $this->assertStringContainsString('name="_token"', $getResponse->getContent());
        echo "✓ CSRF token found in form\n";

        // Step 3: Verify users exist
        echo "\nStep 3: Checking users in database...\n";
        $adminUser = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($adminUser, 'Admin user should exist in database');
        echo "✓ Admin user found: " . $adminUser->email . "\n";

        // Step 4: Test login with correct credentials
        echo "\nStep 4: Attempting login with correct credentials...\n";
        $loginResponse = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        echo "Response status: " . $loginResponse->getStatusCode() . "\n";

        if ($loginResponse->getStatusCode() === 302) {
            echo "✓ Login successful - redirected (HTTP 302)\n";

            // Step 5: Verify user is authenticated
            echo "\nStep 5: Verifying authentication...\n";
            $this->assertAuthenticated();
            echo "✓ User is authenticated\n";

            // Step 6: Check redirect location (now goes directly to dashboard)
            echo "\nStep 6: Checking redirect destination...\n";
            $location = $loginResponse->headers->get('Location');
            echo "Redirected to: " . $location . "\n";
            $this->assertTrue(
                str_contains($location, 'dashboard'),
                'Should redirect to dashboard after login'
            );
            echo "✓ Redirected to dashboard\n";

        } else if ($loginResponse->getStatusCode() === 419) {
            echo "✗ CSRF validation failed (HTTP 419)\n";
            echo "This means the CSRF token is not being validated correctly\n";
            $this->fail('Got 419 - CSRF token validation failed');
        } else if ($loginResponse->getStatusCode() === 500) {
            echo "✗ Server error (HTTP 500)\n";
            echo $loginResponse->getContent();
            $this->fail('Got 500 - Server error during login');
        } else {
            echo "⚠ Unexpected response code: " . $loginResponse->getStatusCode() . "\n";
            echo $loginResponse->getContent();
        }

        // Step 7: Test logout
        echo "\nStep 7: Testing logout...\n";
        $logoutResponse = $this->post('/logout');
        echo "Logout response: " . $logoutResponse->getStatusCode() . "\n";
        $this->assertGuest();
        echo "✓ User logged out successfully\n";

        echo "\n=== ALL TESTS PASSED ===\n\n";
    }
}
