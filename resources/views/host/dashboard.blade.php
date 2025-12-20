@extends('layouts.app')

@section('title', 'Host Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Host Dashboard</h1>
            <p class="text-muted">Welcome back! Here's your property performance overview.</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Period Selector -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ ucfirst(str_replace('days', ' Days', $period)) }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?period=7days">Last 7 Days</a></li>
                    <li><a class="dropdown-item" href="?period=30days">Last 30 Days</a></li>
                    <li><a class="dropdown-item" href="?period=90days">Last 90 Days</a></li>
                    <li><a class="dropdown-item" href="?period=year">Last Year</a></li>
                    <li><a class="dropdown-item" href="?period=month">This Month</a></li>
                </ul>
            </div>
            
            <!-- Export Options -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('host.dashboard.export', ['period' => $period, 'format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-2"></i>CSV
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('host.dashboard.export', ['period' => $period, 'format' => 'pdf']) }}">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($analytics['overview']['total_revenue'], 2) }}
                            </div>
                            @if($analytics['overview']['revenue_change'] != 0)
                                <div class="text-xs {{ $analytics['overview']['revenue_change'] > 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $analytics['overview']['revenue_change'] > 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($analytics['overview']['revenue_change']) }}% from previous period
                                </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Bookings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['overview']['total_bookings'] }}
                            </div>
                            @if($analytics['overview']['booking_change'] != 0)
                                <div class="text-xs {{ $analytics['overview']['booking_change'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $analytics['overview']['booking_change'] > 0 ? '+' : '' }}{{ $analytics['overview']['booking_change'] }} bookings
                                </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Occupancy Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['occupancy']['overall_occupancy'] }}%
                            </div>
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-info" role="progressbar" 
                                     style="width: {{ $analytics['occupancy']['overall_occupancy'] }}%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Avg Rating
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['reviews']['average_rating'] }}
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $analytics['reviews']['average_rating'] ? '' : '-o' }}"></i>
                                    @endfor
                                </span>
                            </div>
                            <div class="text-xs text-muted">
                                {{ $analytics['reviews']['total_reviews'] }} reviews
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Sources -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Sources</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="bookingSourcesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Performance -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Property Performance</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="propertiesTable">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Total Bookings</th>
                                    <th>Revenue</th>
                                    <th>Avg Rating</th>
                                    <th>Occupancy</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['properties']['properties'] as $property)
                                <tr>
                                    <td>{{ $property['title'] }}</td>
                                    <td>{{ $property['total_bookings'] }}</td>
                                    <td>${{ number_format($property['total_revenue'], 2) }}</td>
                                    <td>
                                        {{ $property['average_rating'] }}
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $property['average_rating'] ? '' : '-o' }} fa-sm"></i>
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $occupancy = collect($analytics['occupancy']['by_property'])->firstWhere('property_id', $property['id']);
                                        @endphp
                                        {{ $occupancy ? $occupancy['occupancy_rate'] : 0 }}%
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $property['is_active'] ? 'success' : 'secondary' }}">
                                            {{ $property['is_active'] ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('properties.show', $property['id']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('properties.edit', $property['id']) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Upcoming Events -->
    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                </div>
                <div class="card-body">
                    @if($recentBookings->count() > 0)
                        @foreach($recentBookings as $booking)
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <div class="mr-3">
                                <div class="icon-circle bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                    <i class="fas fa-calendar text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">{{ $booking->created_at->format('M j, Y') }}</div>
                                <span class="font-weight-bold">{{ $booking->property->title }}</span>
                                <div class="small">Guest: {{ $booking->user->name }}</div>
                                <div class="small text-muted">
                                    {{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold">${{ number_format($booking->total_price, 2) }}</div>
                                <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No recent bookings</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Check-ins/Check-outs -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Events</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-success mb-2">Check-ins (Next 7 Days)</h6>
                    @if($upcomingCheckIns->count() > 0)
                        @foreach($upcomingCheckIns as $booking)
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-arrow-right text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">{{ $booking->start_date->format('M j, Y') }}</div>
                                <span class="font-weight-bold">{{ $booking->property->title }}</span>
                                <div class="small">{{ $booking->user->name }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted small">No upcoming check-ins</p>
                    @endif

                    <h6 class="text-danger mb-2 mt-3">Check-outs (Next 7 Days)</h6>
                    @if($upcomingCheckOuts->count() > 0)
                        @foreach($upcomingCheckOuts as $booking)
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <div class="mr-3">
                                <div class="icon-circle bg-danger">
                                    <i class="fas fa-arrow-left text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">{{ $booking->end_date->format('M j, Y') }}</div>
                                <span class="font-weight-bold">{{ $booking->property->title }}</span>
                                <div class="small">{{ $booking->user->name }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted small">No upcoming check-outs</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-area {
    position: relative;
    height: 400px;
}

.chart-pie {
    position: relative;
    height: 400px;
}

.progress-sm {
    height: 0.5rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-500 {
    color: #858796 !important;
}

.border-start {
    border-left: 1px solid #dee2e6 !important;
}

.border-4 {
    border-width: 4px !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($analytics['revenue']['timeline']);
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.period),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(item => item.revenue),
                borderColor: 'rgb(78, 115, 223)',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Booking Sources Chart
    const sourcesCtx = document.getElementById('bookingSourcesChart').getContext('2d');
    const sourcesData = @json($analytics['bookings']['sources']);
    
    new Chart(sourcesCtx, {
        type: 'doughnut',
        data: {
            labels: sourcesData.map(item => item.booking_source || 'Direct'),
            datasets: [{
                data: sourcesData.map(item => item.count),
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796'
                ],
                hoverBackgroundColor: [
                    '#2e59d9',
                    '#17a673',
                    '#2c9faf',
                    '#f4b619',
                    '#e02424',
                    '#6c757d'
                ],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
@endsection
