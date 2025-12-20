<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Host Analytics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }
        .section {
            background: #fff;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section h3 {
            color: #343a40;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .text-success { color: #28a745; }
        .text-warning { color: #ffc107; }
        .text-danger { color: #dc3545; }
        .text-muted { color: #6c757d; }
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Host Analytics Report</h1>
        <p class="text-muted">Period: {{ ucfirst(str_replace('days', ' Days', $period)) }}</p>
        <p class="text-muted">Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <!-- Overview Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">${{ number_format($analytics['overview']['total_revenue'], 2) }}</div>
            <div class="stat-label">Total Revenue</div>
            @if($analytics['overview']['revenue_change'] != 0)
                <div class="text-{{ $analytics['overview']['revenue_change'] > 0 ? 'success' : 'danger' }}">
                    {{ $analytics['overview']['revenue_change'] > 0 ? '+' : '' }}{{ $analytics['overview']['revenue_change'] }}%
                </div>
            @endif
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $analytics['overview']['total_bookings'] }}</div>
            <div class="stat-label">Total Bookings</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $analytics['occupancy']['overall_occupancy'] }}%</div>
            <div class="stat-label">Occupancy Rate</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $analytics['reviews']['average_rating'] }}</div>
            <div class="stat-label">Average Rating</div>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="section">
        <h3>Revenue Breakdown</h3>
        <table>
            <tr>
                <th>Type</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>Accommodation</td>
                <td>${{ number_format($analytics['revenue']['breakdown']->accommodation_revenue ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Cleaning Fees</td>
                <td>${{ number_format($analytics['revenue']['breakdown']->cleaning_revenue ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Service Fees</td>
                <td>${{ number_format($analytics['revenue']['breakdown']->service_revenue ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Taxes</td>
                <td>${{ number_format($analytics['revenue']['breakdown']->tax_revenue ?? 0, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Property Performance -->
    <div class="section">
        <h3>Property Performance</h3>
        <table>
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Bookings</th>
                    <th>Revenue</th>
                    <th>Avg Rating</th>
                    <th>Reviews</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analytics['properties']['properties'] as $property)
                <tr>
                    <td>{{ $property['title'] }}</td>
                    <td>{{ $property['total_bookings'] }}</td>
                    <td>${{ number_format($property['total_revenue'], 2) }}</td>
                    <td>{{ $property['average_rating'] }}</td>
                    <td>{{ $property['total_reviews'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Booking Sources -->
    <div class="section">
        <h3>Booking Sources</h3>
        <table>
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Bookings</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analytics['bookings']['sources'] as $source)
                <tr>
                    <td>{{ $source->booking_source ?: 'Direct' }}</td>
                    <td>{{ $source->count }}</td>
                    <td>${{ number_format($source->revenue, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Guest Insights -->
    <div class="section">
        <h3>Guest Insights</h3>
        <table>
            <tr>
                <th>Total Unique Guests</th>
                <td>{{ $analytics['guest_insights']['total_unique_guests'] }}</td>
            </tr>
            <tr>
                <th>Repeat Guests</th>
                <td>{{ $analytics['guest_insights']['repeat_guests'] }} ({{ $analytics['guest_insights']['repeat_rate'] }}%)</td>
            </tr>
            <tr>
                <th>Average Stay Duration</th>
                <td>{{ $analytics['guest_insights']['average_stay_duration'] }} nights</td>
            </tr>
            <tr>
                <th>Average Guest Count</th>
                <td>{{ round($analytics['guest_insights']['preferences']['average_guest_count'], 1) }} guests</td>
            </tr>
        </table>
    </div>

    <!-- Performance Metrics -->
    <div class="section">
        <h3>Performance Metrics</h3>
        <table>
            <tr>
                <th>Average Response Time</th>
                <td>{{ $analytics['performance']['average_response_time_hours'] }} hours</td>
            </tr>
            <tr>
                <th>Booking Conversion Rate</th>
                <td>{{ $analytics['performance']['conversion_rate'] }}%</td>
            </tr>
            <tr>
                <th>Cancellation Rate</th>
                <td>{{ $analytics['performance']['cancellation_rate'] }}%</td>
            </tr>
            <tr>
                <th>Completion Rate</th>
                <td>{{ $analytics['performance']['completion_rate'] }}%</td>
            </tr>
        </table>
    </div>

    <!-- Review Analytics -->
    <div class="section">
        <h3>Review Summary</h3>
        <table>
            <tr>
                <th>Total Reviews</th>
                <td>{{ $analytics['reviews']['total_reviews'] }}</td>
            </tr>
            <tr>
                <th>Average Rating</th>
                <td>{{ $analytics['reviews']['average_rating'] }}/5</td>
            </tr>
            <tr>
                <th>Response Rate</th>
                <td>{{ $analytics['reviews']['response_rate'] }}%</td>
            </tr>
        </table>
        
        @if(isset($analytics['reviews']['category_averages']))
        <h4>Category Ratings</h4>
        <table>
            <tr>
                <th>Cleanliness</th>
                <td>{{ $analytics['reviews']['category_averages']['cleanliness'] }}/5</td>
            </tr>
            <tr>
                <th>Communication</th>
                <td>{{ $analytics['reviews']['category_averages']['communication'] }}/5</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $analytics['reviews']['category_averages']['location'] }}/5</td>
            </tr>
            <tr>
                <th>Value</th>
                <td>{{ $analytics['reviews']['category_averages']['value'] }}/5</td>
            </tr>
        </table>
        @endif
    </div>

    <div class="footer">
        <p>This report was generated automatically by HomyGo Analytics System</p>
        <p>&copy; {{ date('Y') }} HomyGo. All rights reserved.</p>
    </div>
</body>
</html>
