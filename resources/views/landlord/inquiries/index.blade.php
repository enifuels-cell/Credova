<x-app-layout>
    @section('title', 'My Inquiries')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Property Inquiries</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                <form action="{{ route('landlord.inquiries.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <select name="status" class="border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Responded</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('landlord.inquiries.index') }}" class="text-gray-600 hover:text-gray-800 py-2 px-4">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Inquiries List -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                @forelse($inquiries as $inquiry)
                    <div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition {{ $inquiry->status == 'pending' ? 'bg-yellow-50' : '' }}">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-semibold text-gray-800">{{ $inquiry->name }}</h3>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $inquiry->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $inquiry->status == 'responded' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $inquiry->status == 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($inquiry->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ $inquiry->email }} | {{ $inquiry->phone ?? 'No phone' }}</p>
                                <p class="text-sm text-gray-500 mb-2">
                                    Property: <a href="{{ route('properties.show', $inquiry->property) }}" class="text-emerald-600 hover:text-emerald-700">
                                        {{ Str::limit($inquiry->property->title ?? 'Unknown', 40) }}
                                    </a>
                                </p>
                                <div class="bg-gray-100 rounded-lg p-3 mt-3">
                                    <p class="text-gray-700 text-sm">{{ $inquiry->message }}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Received {{ $inquiry->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex gap-2">
                                @if($inquiry->status == 'pending')
                                    <form action="{{ route('landlord.inquiries.update', $inquiry) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="responded">
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                                            Mark Responded
                                        </button>
                                    </form>
                                @endif
                                @if($inquiry->status != 'closed')
                                    <form action="{{ route('landlord.inquiries.update', $inquiry) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="closed">
                                        <button type="submit"
                                            class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                                            Close
                                        </button>
                                    </form>
                                @endif
                                <a href="mailto:{{ $inquiry->email }}"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                                    Reply
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No inquiries yet</h3>
                        <p class="mt-1 text-sm text-gray-500">When tenants send inquiries about your properties, they will appear here.</p>
                    </div>
                @endforelse

                @if($inquiries->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $inquiries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
