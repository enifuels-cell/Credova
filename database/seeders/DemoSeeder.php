<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Borrower;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\collector;
use App\Models\CollectionAttempt;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks temporarily
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables in foreign-key-safe order
        CollectionAttempt::truncate();
        Payment::truncate();
        Loan::truncate();
        Borrower::truncate();
        collector::truncate();

        // Enable foreign key checks again
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create demo borrower
        $borrower = Borrower::create([
            'first_name' => 'Juan',
            'last_name'  => 'Dela Cruz',
            'phone'      => '09171234567',
            'email'      => 'juan@example.com',
            'address'    => 'Cebu City'
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

        // Create demo collector and link to Juan Santos collector user
        $collectorUser = \App\Models\User::where('email', 'collector@example.com')->first();
        $collector = collector::create([
            'name'  => 'Juan Santos',
            'email' => 'collector@example.com',
            'phone' => '09181234567',
            'user_id' => $collectorUser ? $collectorUser->id : null,
        ]);

        // Create demo collection attempt with borrower_id
        CollectionAttempt::create([
            'loan_id'        => $loan->id,
            'borrower_id'    => $borrower->id, // <-- fixed: required by table
            'collector_id'   => $collector->id,
            'attempted_at'   => Carbon::now()->subDays(10),
            'outcome'        => 'attempted',
            'collected_amount'=> 0,
            'notes'          => 'Borrower not at home'
        ]);
    }
}
