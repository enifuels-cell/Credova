<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\RoleSeeder;

class SetupApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup {--fresh : Run fresh migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the application with all necessary data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up Homygo application...');

        if ($this->option('fresh')) {
            $this->info('Running fresh migrations...');
            $this->call('migrate:fresh');
        }

        $this->info('Seeding roles...');
        $this->call('db:seed', ['--class' => 'RoleSeeder']);

        $this->info('Creating storage link...');
        try {
            $this->call('storage:link');
        } catch (\Exception $e) {
            $this->warn('Storage link already exists or could not be created.');
        }

        $this->info('Clearing caches...');
        $this->call('optimize:clear');

        $this->info('✅ Application setup complete!');
        $this->info('You can now:');
        $this->info('- Visit http://localhost:8000 to see the application');
        $this->info('- Register as a new user (will be assigned renter role)');
        $this->info('- Create test users with: php artisan db:seed --class=TestUsersSeeder');
        
        return Command::SUCCESS;
    }
}
