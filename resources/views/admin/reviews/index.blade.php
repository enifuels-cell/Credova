<x-app-layout>
    @section('title', 'Manage Reviews')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Reviews Management</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Total Reviews</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div>
                        <select name="is_approved" class="border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Reviews</option>
                            <option value="1" {{ request('is_approved') == '1' ? 'selected' : '' }}>Approved</option>
                            <option value="0" {{ request('is_approved') == '0' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        Filter
                    </button>
                    @if(request('is_approved') !== null)
                        <a href="{{ route('admin.reviews.index') }}" class="text-gray-600 hover:text-gray-800 py-2 px-4">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Reviews List -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                @forelse($reviews as $review)
                    <div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition {{ !$review->is_approved ? 'bg-yellow-50' : '' }}">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-semibold text-gray-800">{{ $review->user->name }}</h3>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    Property: <a href="{{ route('properties.show', $review->property) }}" class="text-emerald-600 hover:text-emerald-700">
                                        {{ Str::limit($review->property->title, 40) }}
                                    </a>
                                </p>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($review->comment, 200) }}</p>
                                @endif
                                <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex md:flex-col gap-2">
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50">
                                            Approve
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.reviews.unapprove', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Unapprove
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No reviews found.
                    </div>
                @endforelse
            </div>

            @if($reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
