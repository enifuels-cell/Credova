<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Borrower;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks temporarily
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables in foreign-key-safe order
        Payment::truncate();
        Loan::truncate();
        Borrower::truncate();

        // Enable foreign key checks again
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get the demo user
        $demoUser = User::where('email', 'user@example.com')->first();

        // Create demo borrower assigned to demo user
        $borrower = Borrower::create([
            'first_name' => 'Juan',
            'last_name'  => 'Dela Cruz',
            'phone'      => '09171234567',
            'email'      => 'juan@example.com',
            'address'    => 'Cebu City',
            'user_id'    => $demoUser->id ?? null
        ]);

        // Create demo loan
        $loan = Loan::create([
            'borrower_id'    => $borrower->id,
            'principal'      => 10000,
            'total_due'      => 11000,
            'balance'        => 11000,
            'interest_rate'  => 10,
            'issued_at'      => Carbon::now()->subMonths(3)->toDateString(),
            'first_due_date' => Carbon::now()->subDays(40)->toDateString(),
            'frequency'      => 'monthly',
            'term'           => 3,
            'status'         => 'active',
            'notes'          => 'Demo loan'
        ]);

        // Create demo payment
        Payment::create([
            'loan_id' => $loan->id,
            'amount'  => 2000,
            'paid_at' => Carbon::now()->subDays(20)->toDateString(),
            'method'  => 'cash',
            'notes'   => 'Partial payment'
        ]);
    }
}
