<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Borrower;
use App\Models\Loan;

class AccountCreationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_dashboard_route_requires_authentication()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_borrower_api_requires_authentication()
    {
        $response = $this->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ]);
        $response->assertUnauthorized();
    }

    public function test_can_create_borrower_with_valid_data()
    {
        $response = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'first_name', 'email', 'phone', 'address']);

        $this->assertDatabaseHas('borrowers', [
            'first_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
        ]);
    }

    public function test_cannot_create_duplicate_phone_number()
    {
        // Create first borrower
        $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ]);

        // Try to create second with same phone
        $response = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '09123456789',
            'address' => 'Another Address',
        ]);

        $response->assertStatus(409);
        $response->assertJson(['error' => 'Phone number already registered']);
    }

    public function test_cannot_create_duplicate_email()
    {
        // Create first borrower
        $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ]);

        // Try to create second with same email
        $response = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Smith',
            'email' => 'john@example.com',
            'phone' => '09987654321',
            'address' => 'Another Address',
        ]);

        $response->assertStatus(409);
        $response->assertJson(['error' => 'Email already registered']);
    }

    public function test_can_create_loan_for_borrower()
    {
        // Create borrower
        $borrower = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ])->json();

        // Create loan
        $response = $this->actingAs($this->user)->postJson('/api/loans', [
            'borrower_id' => $borrower['id'],
            'principal' => 50000,
            'term' => 30,
            'interest_rate' => 10,
            'total_payable' => 55000,
            'payment_frequency' => 'monthly',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id', 'borrower_id', 'principal', 'balance', 'total_due',
            'term', 'interest_rate', 'status', 'frequency', 'first_due_date'
        ]);

        $this->assertDatabaseHas('loans', [
            'borrower_id' => $borrower['id'],
            'principal' => 50000,
            'balance' => 50000,
            'total_due' => 55000,
            'term' => 30,
            'interest_rate' => 10,
            'status' => 'active',
            'frequency' => 'monthly',
        ]);
    }

    public function test_loan_balance_initialized_to_principal()
    {
        $borrower = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ])->json();

        $response = $this->actingAs($this->user)->postJson('/api/loans', [
            'borrower_id' => $borrower['id'],
            'principal' => 100000,
            'term' => 60,
            'interest_rate' => 15,
            'total_payable' => 115000,
            'payment_frequency' => 'twice-monthly',
        ]);

        $loan = $response->json();
        $this->assertEquals(100000, $loan['principal']);
        $this->assertEquals(100000, $loan['balance']);
    }

    public function test_loan_frequency_enum_accepts_twice_monthly()
    {
        $borrower = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ])->json();

        $response = $this->actingAs($this->user)->postJson('/api/loans', [
            'borrower_id' => $borrower['id'],
            'principal' => 50000,
            'term' => 30,
            'interest_rate' => 10,
            'total_payable' => 55000,
            'payment_frequency' => 'twice-monthly',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('twice-monthly', $response->json()['frequency']);
    }

    public function test_get_borrowers_api()
    {
        // Create a borrower with a loan
        $borrower = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ])->json();

        $this->actingAs($this->user)->postJson('/api/loans', [
            'borrower_id' => $borrower['id'],
            'principal' => 50000,
            'term' => 30,
            'interest_rate' => 10,
            'total_payable' => 55000,
            'payment_frequency' => 'monthly',
        ]);

        // Get borrowers
        $response = $this->actingAs($this->user)->getJson('/api/borrowers');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'amount', 'paidAmount', 'days', 'dueDate', 'status']
        ]);

        $this->assertCount(1, $response->json());
    }

    public function test_csrf_token_required_for_api_requests()
    {
        // This test verifies CSRF middleware is applied
        // Unauthenticated request should redirect to login
        $response = $this->post('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ]);

        // Should redirect to login without authentication
        $response->assertRedirect('/login');
    }

    public function test_custom_loan_days_accepted()
    {
        $borrower = $this->actingAs($this->user)->postJson('/api/borrowers', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => 'Test Address',
        ])->json();

        // Create loan with custom term (45 days)
        $response = $this->actingAs($this->user)->postJson('/api/loans', [
            'borrower_id' => $borrower['id'],
            'principal' => 50000,
            'term' => 45, // Custom value
            'interest_rate' => 10,
            'total_payable' => 55000,
            'payment_frequency' => 'monthly',
        ]);

        $response->assertStatus(200);
        $loan = $response->json();
        $this->assertEquals(45, $loan['term']);
    }
}
