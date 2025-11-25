<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginCsrfTest extends TestCase
{
    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $this->assertStringContainsString('_token', $response->getContent());
    }

    public function test_login_with_csrf_token()
    {
        // Create a test user
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Get the login page first to establish session
        $getResponse = $this->get('/login');
        $getResponse->assertStatus(200);

        // Extract CSRF token from response if possible
        $content = $getResponse->getContent();
        $this->assertStringContainsString('name="_token"', $content);

        // Now post login - Laravel testing handles CSRF automatically
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Should redirect on successful login (302)
        $response->assertStatus(302);
        $this->assertAuthenticated();
    }
}

