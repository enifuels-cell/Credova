<x-app-layout>
    @section('title', 'Landlord Dashboard')

    <div class="py-4 sm:py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 sm:mb-8">Landlord Dashboard</h1>

            <!-- Stats Cards - Refactored Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
                @php
                    $stats = [
                        [
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                            'value' => $totalProperties,
                            'label' => 'Total Properties',
                            'bg' => 'bg-emerald-100',
                        ],
                        [
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                            'value' => $activeProperties,
                            'label' => 'Active Listings',
                            'bg' => 'bg-green-100',
                        ],
                        [
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>',
                            'value' => $pendingInquiries,
                            'label' => 'Pending Inquiries',
                            'bg' => 'bg-yellow-100',
                        ],
                        [
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>',
                            'value' => number_format($totalViews),
                            'label' => 'Total Views',
                            'bg' => 'bg-blue-100',
                        ],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center">
                        <div class="p-2.5 sm:p-3 {{ $stat['bg'] }} rounded-xl mb-2 sm:mb-0">
                            {!! $stat['icon'] !!}
                        </div>
                        <div class="sm:ml-4">
                            <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stat['value'] }}</p>
                            <p class="text-gray-500 text-xs sm:text-sm">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions - Mobile Friendly -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('landlord.properties.create') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-4 px-4 rounded-xl transition flex items-center justify-center touch-target text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Property
                    </a>
                    <a href="{{ route('landlord.properties.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-800 font-semibold py-4 px-4 rounded-xl transition flex items-center justify-center touch-target text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Manage Properties
                    </a>
                    <a href="{{ route('landlord.inquiries.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-800 font-semibold py-4 px-4 rounded-xl transition flex items-center justify-center touch-target text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        View Inquiries
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <!-- Recent Inquiries -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Inquiries</h2>
                        <a href="{{ route('landlord.inquiries.index') }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium touch-target py-1">View All</a>
                    </div>
                    @forelse($recentInquiries as $inquiry)
                        <div class="border-b border-gray-100 py-4 last:border-0">
                            <div class="flex justify-between items-start gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-800 text-base">{{ $inquiry->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ Str::limit($inquiry->property->title ?? 'Property', 30) }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0
                                    {{ $inquiry->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($inquiry->message, 80) }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">No inquiries yet</p>
                        </div>
                    @endforelse
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Bookings</h2>
                    </div>
                    @forelse($recentBookings as $booking)
                        <div class="border-b border-gray-100 py-4 last:border-0">
                            <div class="flex justify-between items-start gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-800 text-base">{{ $booking->tenant->name ?? 'Tenant' }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ Str::limit($booking->property->title ?? 'Property', 30) }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0
                                    {{ $booking->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                {{ $booking->start_date->format('M d, Y') }} - {{ $booking->formatted_total }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">No bookings yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
