<?php

namespace App\Console\Commands;

use App\Models\SavedSearch;
use App\Mail\SavedSearchAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSavedSearchAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:send-saved-search {--force : Send alerts regardless of frequency}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email alerts for saved searches with new matching properties';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting saved search alerts...');

        $force = $this->option('force');
        
        // Get saved searches that need alerts
        $savedSearches = SavedSearch::query()
            ->with('user')
            ->where('email_alerts', true)
            ->where('is_active', true)
            ->when(!$force, function($query) {
                // Only get searches that are due for alerts based on frequency
                $query->where(function($q) {
                    $q->whereNull('last_alert_sent')
                      ->orWhere('last_alert_sent', '<=', now()->subDay()); // Daily minimum
                });
            })
            ->get();

        if ($savedSearches->isEmpty()) {
            $this->info('No saved searches need alerts at this time.');
            return 0;
        }

        $this->info("Found {$savedSearches->count()} saved searches that need alerts.");

        $alertsSent = 0;
        $errorsCount = 0;

        foreach ($savedSearches as $savedSearch) {
            try {
                // Check if alert should be sent based on frequency
                if (!$force && !$savedSearch->shouldSendAlert()) {
                    $this->line("Skipping '{$savedSearch->name}' - not due for alert yet");
                    continue;
                }

                $this->line("Processing saved search: {$savedSearch->name} (User: {$savedSearch->user->email})");

                // Execute the search to get current results
                $properties = $savedSearch->executeSearch();
                
                // For demonstration, we'll consider all results as "new"
                // In a real implementation, you'd track which properties were sent before
                $newCount = $properties->count();

                if ($properties->count() > 0 || $force) {
                    // Send the email
                    Mail::to($savedSearch->user->email)->send(
                        new SavedSearchAlert($savedSearch, $properties, $newCount)
                    );

                    // Mark alert as sent
                    $savedSearch->markAlertSent();

                    $this->info("✓ Alert sent for '{$savedSearch->name}' - {$properties->count()} properties");
                    $alertsSent++;
                } else {
                    $this->line("- No properties found for '{$savedSearch->name}', skipping alert");
                }

            } catch (\Exception $e) {
                $this->error("✗ Failed to send alert for '{$savedSearch->name}': " . $e->getMessage());
                $errorsCount++;
            }
        }

        $this->newLine();
        $this->info("Alerts processing completed!");
        $this->info("✓ Alerts sent: {$alertsSent}");
        
        if ($errorsCount > 0) {
            $this->warn("✗ Errors: {$errorsCount}");
        }

        return 0;
    }
}
