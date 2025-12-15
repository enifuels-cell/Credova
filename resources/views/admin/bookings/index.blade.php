<x-app-layout>
    @section('title', 'Manage Bookings')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Bookings Management</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Confirmed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Cancelled</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by booking #, property, or tenant..."
                            class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <select name="status" class="border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800 py-2 px-4">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $booking->booking_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('properties.show', $booking->property) }}" class="text-emerald-600 hover:text-emerald-700">
                                        {{ Str::limit($booking->property->title, 30) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $booking->tenant->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $booking->lease_duration_months }} months
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱{{ number_format($booking->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $booking->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status == 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                            class="text-emerald-600 hover:text-emerald-700">View</a>
                                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
