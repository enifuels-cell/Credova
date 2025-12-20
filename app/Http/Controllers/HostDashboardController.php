<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostDashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->middleware('auth');
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show the host dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', '30days');
        $hostId = Auth::id();
        
        // Get analytics data
        $analytics = $this->analyticsService->getHostAnalytics($hostId, $period);
        
        // Get recent activity
        $recentBookings = \App\Models\Booking::whereHas('property', function ($query) use ($hostId) {
            $query->where('user_id', $hostId);
        })
        ->with(['property', 'user'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

        // Get upcoming check-ins/check-outs
        $upcomingCheckIns = \App\Models\Booking::whereHas('property', function ($query) use ($hostId) {
            $query->where('user_id', $hostId);
        })
        ->where('start_date', '>=', now())
        ->where('start_date', '<=', now()->addDays(7))
        ->where('status', 'confirmed')
        ->with(['property', 'user'])
        ->orderBy('start_date')
        ->get();

        $upcomingCheckOuts = \App\Models\Booking::whereHas('property', function ($query) use ($hostId) {
            $query->where('user_id', $hostId);
        })
        ->where('end_date', '>=', now())
        ->where('end_date', '<=', now()->addDays(7))
        ->where('status', 'confirmed')
        ->with(['property', 'user'])
        ->orderBy('end_date')
        ->get();

        return view('host.dashboard', compact(
            'analytics',
            'period',
            'recentBookings',
            'upcomingCheckIns',
            'upcomingCheckOuts'
        ));
    }

    /**
     * Get analytics data as JSON for AJAX requests
     */
    public function getAnalytics(Request $request)
    {
        $period = $request->get('period', '30days');
        $hostId = Auth::id();
        
        $analytics = $this->analyticsService->getHostAnalytics($hostId, $period);
        
        return response()->json($analytics);
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics(Request $request)
    {
        $period = $request->get('period', '30days');
        $format = $request->get('format', 'pdf');
        $hostId = Auth::id();
        
        $analytics = $this->analyticsService->getHostAnalytics($hostId, $period);
        
        if ($format === 'csv') {
            return $this->exportToCsv($analytics, $period);
        }
        
        return $this->exportToPdf($analytics, $period);
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($analytics, $period)
    {
        $filename = "host-analytics-{$period}-" . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($analytics) {
            $file = fopen('php://output', 'w');
            
            // Overview section
            fputcsv($file, ['Host Analytics Overview']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Revenue', '$' . number_format($analytics['overview']['total_revenue'], 2)]);
            fputcsv($file, ['Total Bookings', $analytics['overview']['total_bookings']]);
            fputcsv($file, ['Completed Bookings', $analytics['overview']['completed_bookings']]);
            fputcsv($file, ['Active Properties', $analytics['overview']['active_properties']]);
            fputcsv($file, ['Average Booking Value', '$' . number_format($analytics['overview']['average_booking_value'], 2)]);
            fputcsv($file, ['Cancellation Rate', $analytics['overview']['cancellation_rate'] . '%']);
            fputcsv($file, []);

            // Revenue by property
            fputcsv($file, ['Revenue by Property']);
            fputcsv($file, ['Property', 'Revenue', 'Bookings']);
            foreach ($analytics['revenue']['by_property'] as $property) {
                fputcsv($file, [
                    $property->title,
                    '$' . number_format($property->revenue, 2),
                    $property->bookings
                ]);
            }
            fputcsv($file, []);

            // Property performance
            fputcsv($file, ['Property Performance']);
            fputcsv($file, ['Property', 'Total Bookings', 'Revenue', 'Avg Rating', 'Reviews']);
            foreach ($analytics['properties']['properties'] as $property) {
                fputcsv($file, [
                    $property['title'],
                    $property['total_bookings'],
                    '$' . number_format($property['total_revenue'], 2),
                    $property['average_rating'],
                    $property['total_reviews']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF
     */
    private function exportToPdf($analytics, $period)
    {
        // For now, return a basic PDF response
        // In a real implementation, you would use a PDF library like TCPDF or DomPDF
        $filename = "host-analytics-{$period}-" . date('Y-m-d') . '.pdf';
        
        $html = view('host.analytics-pdf', compact('analytics', 'period'))->render();
        
        // This would typically use a PDF library
        // For demonstration, we'll return the HTML
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}
