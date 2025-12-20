<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get comprehensive analytics for a host
     */
    public function getHostAnalytics(int $hostId, string $period = '30days'): array
    {
        $dateRange = $this->getDateRange($period);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        
        return [
            'overview' => $this->getOverviewStats($hostId, $startDate, $endDate),
            'revenue' => $this->getRevenueAnalytics($hostId, $startDate, $endDate, $period),
            'bookings' => $this->getBookingAnalytics($hostId, $startDate, $endDate, $period),
            'properties' => $this->getPropertyAnalytics($hostId),
            'reviews' => $this->getReviewAnalytics($hostId, $startDate, $endDate),
            'occupancy' => $this->getOccupancyAnalytics($hostId, $startDate, $endDate, $period),
            'guest_insights' => $this->getGuestInsights($hostId, $startDate, $endDate),
            'performance' => $this->getPerformanceMetrics($hostId, $startDate, $endDate),
        ];
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats(int $hostId, Carbon $startDate, Carbon $endDate): array
    {
        $properties = Property::where('user_id', $hostId)->get();
        $propertyIds = $properties->pluck('id');

        $bookings = Booking::whereIn('property_id', $propertyIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $completedBookings = $bookings->where('status', 'completed');
        $revenue = $completedBookings->sum('total_price');
        
        // Calculate previous period for comparison
        $previousStart = $startDate->copy()->subtract($endDate->diffInDays($startDate), 'days');
        $previousBookings = Booking::whereIn('property_id', $propertyIds)
            ->whereBetween('created_at', [$previousStart, $startDate])
            ->get();
        
        $previousRevenue = $previousBookings->where('status', 'completed')->sum('total_price');
        $revenueChange = $previousRevenue > 0 ? (($revenue - $previousRevenue) / $previousRevenue) * 100 : 0;
        
        return [
            'total_revenue' => $revenue,
            'revenue_change' => round($revenueChange, 2),
            'total_bookings' => $bookings->count(),
            'booking_change' => $bookings->count() - $previousBookings->count(),
            'completed_bookings' => $completedBookings->count(),
            'active_properties' => $properties->where('is_active', true)->count(),
            'total_properties' => $properties->count(),
            'average_booking_value' => $completedBookings->count() > 0 ? $revenue / $completedBookings->count() : 0,
            'cancellation_rate' => $bookings->count() > 0 ? ($bookings->where('status', 'cancelled')->count() / $bookings->count()) * 100 : 0,
        ];
    }

    /**
     * Get revenue analytics with trends
     */
    private function getRevenueAnalytics(int $hostId, Carbon $startDate, Carbon $endDate, string $period): array
    {
        $properties = Property::where('user_id', $hostId)->pluck('id');
        
        // Get revenue by time period
        $interval = $this->getChartInterval($period);
        $revenueData = Booking::whereIn('property_id', $properties)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, ?) as period, SUM(total_price) as revenue, COUNT(*) as bookings", [$interval])
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Revenue by property
        $revenueByProperty = Booking::whereIn('property_id', $properties)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('properties', 'bookings.property_id', '=', 'properties.id')
            ->selectRaw('properties.title, SUM(bookings.total_price) as revenue, COUNT(bookings.id) as bookings')
            ->groupBy('properties.id', 'properties.title')
            ->orderByDesc('revenue')
            ->get();

        // Revenue breakdown
        $totalRevenue = $revenueData->sum('revenue');
        $breakdown = Booking::whereIn('property_id', $properties)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                SUM(total_price - cleaning_fee - service_fee - taxes) as accommodation_revenue,
                SUM(cleaning_fee) as cleaning_revenue,
                SUM(service_fee) as service_revenue,
                SUM(taxes) as tax_revenue
            ')
            ->first();

        return [
            'timeline' => $revenueData,
            'by_property' => $revenueByProperty,
            'breakdown' => $breakdown,
            'total' => $totalRevenue,
            'average_daily' => $totalRevenue / max(1, $endDate->diffInDays($startDate)),
        ];
    }

    /**
     * Get booking analytics
     */
    private function getBookingAnalytics(int $hostId, Carbon $startDate, Carbon $endDate, string $period): array
    {
        $properties = Property::where('user_id', $hostId)->pluck('id');
        
        // Booking trends
        $interval = $this->getChartInterval($period);
        $bookingTrends = Booking::whereIn('property_id', $properties)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, ?) as period, COUNT(*) as count, status", [$interval])
            ->groupBy('period', 'status')
            ->orderBy('period')
            ->get()
            ->groupBy('period');

        // Booking sources
        $bookingSources = Booking::whereIn('property_id', $properties)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('booking_source, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('booking_source')
            ->get();

        // Lead time analysis
        $leadTimes = Booking::whereIn('property_id', $properties)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATEDIFF(start_date, created_at) as lead_time_days')
            ->get()
            ->map(fn($booking) => $booking->lead_time_days)
            ->filter(fn($days) => $days >= 0);

        $averageLeadTime = $leadTimes->avg();
        
        // Guest count analysis
        $guestAnalysis = Booking::whereIn('property_id', $properties)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('guest_count, COUNT(*) as count')
            ->groupBy('guest_count')
            ->orderBy('guest_count')
            ->get();

        return [
            'trends' => $bookingTrends,
            'sources' => $bookingSources,
            'average_lead_time' => round($averageLeadTime, 1),
            'lead_time_distribution' => [
                'same_day' => $leadTimes->filter(fn($days) => $days == 0)->count(),
                'within_week' => $leadTimes->filter(fn($days) => $days <= 7)->count(),
                'within_month' => $leadTimes->filter(fn($days) => $days <= 30)->count(),
                'over_month' => $leadTimes->filter(fn($days) => $days > 30)->count(),
            ],
            'guest_distribution' => $guestAnalysis,
        ];
    }

    /**
     * Get property performance analytics
     */
    private function getPropertyAnalytics(int $hostId): array
    {
        $properties = Property::where('user_id', $hostId)
            ->withCount(['bookings', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->get();

        $propertyPerformance = $properties->map(function ($property) {
            $completedBookings = $property->bookings()->where('status', 'completed')->count();
            $totalRevenue = $property->bookings()->where('status', 'completed')->sum('total_price');
            $averageRating = $property->reviews_avg_rating ?? 0;
            
            return [
                'id' => $property->id,
                'title' => $property->title,
                'total_bookings' => $property->bookings_count,
                'completed_bookings' => $completedBookings,
                'total_revenue' => $totalRevenue,
                'average_revenue_per_booking' => $completedBookings > 0 ? $totalRevenue / $completedBookings : 0,
                'total_reviews' => $property->reviews_count,
                'average_rating' => round($averageRating, 2),
                'is_active' => $property->is_active,
                'price_per_night' => $property->price_per_night,
            ];
        });

        return [
            'properties' => $propertyPerformance,
            'top_performers' => $propertyPerformance->sortByDesc('total_revenue')->take(5),
            'best_rated' => $propertyPerformance->where('total_reviews', '>', 0)->sortByDesc('average_rating')->take(5),
        ];
    }

    /**
     * Get review analytics
     */
    private function getReviewAnalytics(int $hostId, Carbon $startDate, Carbon $endDate): array
    {
        $properties = Property::where('user_id', $hostId)->pluck('id');
        
        $reviews = Review::whereIn('property_id', $properties)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($reviews->isEmpty()) {
            return [
                'total_reviews' => 0,
                'average_rating' => 0,
                'rating_distribution' => [],
                'category_averages' => [],
                'recent_reviews' => [],
            ];
        }

        $ratingDistribution = $reviews->groupBy('rating')->map->count();
        
        return [
            'total_reviews' => $reviews->count(),
            'average_rating' => round($reviews->avg('rating'), 2),
            'rating_distribution' => $ratingDistribution,
            'category_averages' => [
                'cleanliness' => round($reviews->avg('cleanliness_rating'), 2),
                'communication' => round($reviews->avg('communication_rating'), 2),
                'location' => round($reviews->avg('location_rating'), 2),
                'value' => round($reviews->avg('value_rating'), 2),
            ],
            'recent_reviews' => $reviews->sortByDesc('created_at')->take(5)->load(['guest', 'property']),
            'response_rate' => $reviews->count() > 0 ? ($reviews->whereNotNull('host_response')->count() / $reviews->count()) * 100 : 0,
        ];
    }

    /**
     * Get occupancy analytics
     */
    private function getOccupancyAnalytics(int $hostId, Carbon $startDate, Carbon $endDate, string $period): array
    {
        $properties = Property::where('user_id', $hostId)->get();
        $totalProperties = $properties->count();
        
        if ($totalProperties === 0) {
            return ['overall_occupancy' => 0, 'by_property' => []];
        }

        $totalDays = $endDate->diffInDays($startDate);
        $totalPossibleNights = $totalProperties * $totalDays;
        
        $bookedNights = Booking::whereIn('property_id', $properties->pluck('id'))
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->get()
            ->sum(function ($booking) use ($startDate, $endDate) {
                $bookingStart = max($booking->start_date, $startDate);
                $bookingEnd = min($booking->end_date, $endDate);
                return $bookingStart->diffInDays($bookingEnd);
            });

        $overallOccupancy = $totalPossibleNights > 0 ? ($bookedNights / $totalPossibleNights) * 100 : 0;

        // Occupancy by property
        $occupancyByProperty = $properties->map(function ($property) use ($startDate, $endDate, $totalDays) {
            $propertyBookedNights = Booking::where('property_id', $property->id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                })
                ->get()
                ->sum(function ($booking) use ($startDate, $endDate) {
                    $bookingStart = max($booking->start_date, $startDate);
                    $bookingEnd = min($booking->end_date, $endDate);
                    return $bookingStart->diffInDays($bookingEnd);
                });

            $occupancyRate = $totalDays > 0 ? ($propertyBookedNights / $totalDays) * 100 : 0;

            return [
                'property_id' => $property->id,
                'property_title' => $property->title,
                'booked_nights' => $propertyBookedNights,
                'total_nights' => $totalDays,
                'occupancy_rate' => round($occupancyRate, 2),
            ];
        });

        return [
            'overall_occupancy' => round($overallOccupancy, 2),
            'by_property' => $occupancyByProperty,
            'total_booked_nights' => $bookedNights,
            'total_possible_nights' => $totalPossibleNights,
        ];
    }

    /**
     * Get guest insights
     */
    private function getGuestInsights(int $hostId, Carbon $startDate, Carbon $endDate): array
    {
        $properties = Property::where('user_id', $hostId)->pluck('id');
        
        $bookings = Booking::whereIn('property_id', $properties)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->get();

        // Repeat guests
        $guestCounts = $bookings->groupBy('user_id')->map->count();
        $repeatGuests = $guestCounts->filter(fn($count) => $count > 1)->count();
        $repeatRate = $guestCounts->count() > 0 ? ($repeatGuests / $guestCounts->count()) * 100 : 0;

        // Booking duration analysis
        $durations = $bookings->map(fn($booking) => $booking->start_date->diffInDays($booking->end_date));
        $averageDuration = $durations->avg();

        // Guest preferences
        $preferences = [
            'has_pets' => $bookings->where('has_pets', true)->count(),
            'accessibility_needs' => $bookings->whereNotNull('accessibility_needs')->count(),
            'average_guest_count' => $bookings->avg('guest_count'),
        ];

        return [
            'total_unique_guests' => $guestCounts->count(),
            'repeat_guests' => $repeatGuests,
            'repeat_rate' => round($repeatRate, 2),
            'average_stay_duration' => round($averageDuration, 1),
            'duration_distribution' => [
                'short_stay' => $durations->filter(fn($d) => $d <= 2)->count(), // 1-2 nights
                'medium_stay' => $durations->filter(fn($d) => $d >= 3 && $d <= 7)->count(), // 3-7 nights
                'long_stay' => $durations->filter(fn($d) => $d > 7)->count(), // 7+ nights
            ],
            'preferences' => $preferences,
        ];
    }

    /**
     * Get performance metrics and benchmarks
     */
    private function getPerformanceMetrics(int $hostId, Carbon $startDate, Carbon $endDate): array
    {
        $properties = Property::where('user_id', $hostId)->pluck('id');
        
        $bookings = Booking::whereIn('property_id', $properties)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Response time (time to accept/confirm bookings)
        $confirmationTimes = $bookings->whereNotNull('confirmed_at')
            ->map(function ($booking) {
                return $booking->created_at->diffInHours($booking->confirmed_at);
            })
            ->filter(fn($hours) => $hours >= 0);

        $averageResponseTime = $confirmationTimes->avg();

        // Conversion rate (confirmed vs pending/cancelled)
        $totalBookings = $bookings->count();
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();
        $conversionRate = $totalBookings > 0 ? ($confirmedBookings / $totalBookings) * 100 : 0;

        return [
            'average_response_time_hours' => round($averageResponseTime, 2),
            'conversion_rate' => round($conversionRate, 2),
            'cancellation_rate' => $totalBookings > 0 ? ($bookings->where('status', 'cancelled')->count() / $totalBookings) * 100 : 0,
            'completion_rate' => $totalBookings > 0 ? ($bookings->where('status', 'completed')->count() / $totalBookings) * 100 : 0,
        ];
    }

    /**
     * Get date range based on period
     */
    private function getDateRange(string $period): array
    {
        return match($period) {
            '7days' => [
                'start' => Carbon::now()->subDays(7),
                'end' => Carbon::now(),
            ],
            '30days' => [
                'start' => Carbon::now()->subDays(30),
                'end' => Carbon::now(),
            ],
            '90days' => [
                'start' => Carbon::now()->subDays(90),
                'end' => Carbon::now(),
            ],
            'year' => [
                'start' => Carbon::now()->subYear(),
                'end' => Carbon::now(),
            ],
            'month' => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ],
            default => [
                'start' => Carbon::now()->subDays(30),
                'end' => Carbon::now(),
            ],
        };
    }

    /**
     * Get chart interval based on period
     */
    private function getChartInterval(string $period): string
    {
        return match($period) {
            '7days' => '%Y-%m-%d', // Daily
            '30days' => '%Y-%m-%d', // Daily
            '90days' => '%Y-%u', // Weekly
            'year' => '%Y-%m', // Monthly
            'month' => '%Y-%m-%d', // Daily
            default => '%Y-%m-%d',
        };
    }
}
