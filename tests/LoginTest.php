<?php

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_contains_csrf_token()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('_token');
    }

    public function test_login_with_valid_credentials()
    {
        // Create a test user
        $user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }
}
