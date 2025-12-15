<x-app-layout>
    @section('title', 'Admin Dashboard')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Admin Dashboard</h1>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
                            <p class="text-gray-500 text-sm">Total Users</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-emerald-100 rounded-full">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-800">{{ $totalProperties }}</p>
                            <p class="text-gray-500 text-sm">Total Properties</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-800">{{ $pendingApprovals }}</p>
                            <p class="text-gray-500 text-sm">Pending Approvals</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-800">{{ $totalInquiries }}</p>
                            <p class="text-gray-500 text-sm">Total Inquiries</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-gray-500 text-sm font-medium">Tenants</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $tenantCount }}</p>
                    <p class="text-green-500 text-sm mt-1">Active Users</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-gray-500 text-sm font-medium">Landlords</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $landlordCount }}</p>
                    <p class="text-emerald-500 text-sm mt-1">Property Owners</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-gray-500 text-sm font-medium">Administrators</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $adminCount }}</p>
                    <p class="text-blue-500 text-sm mt-1">System Admins</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.properties.index') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Manage Properties
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        Manage Users
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Users</h2>
                        <a href="{{ route('admin.users.index') }}" class="text-emerald-600 hover:text-emerald-700 text-sm">View All</a>
                    </div>
                    @forelse($recentUsers as $user)
                        <div class="border-b border-gray-100 py-3 last:border-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $user->role == 'landlord' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $user->role == 'tenant' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No users yet</p>
                    @endforelse
                </div>

                <!-- Recent Properties -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Properties</h2>
                        <a href="{{ route('admin.properties.index') }}" class="text-emerald-600 hover:text-emerald-700 text-sm">View All</a>
                    </div>
                    @forelse($recentProperties as $property)
                        <div class="border-b border-gray-100 py-3 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-800">{{ Str::limit($property->title, 30) }}</p>
                                    <p class="text-sm text-gray-500">by {{ $property->user->name ?? 'Unknown' }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $property->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $property->status == 'rented' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $property->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No properties yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
