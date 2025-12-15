<x-app-layout>
    @section('title', 'My Reviews')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">My Reviews</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($reviews->count() > 0)
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="md:flex">
                                <div class="md:flex-shrink-0">
                                    @if($review->property->images->first())
                                        <img class="h-48 w-full md:w-48 object-cover"
                                            src="{{ asset('storage/' . $review->property->images->first()->image_path) }}"
                                            alt="{{ $review->property->title }}">
                                    @else
                                        <div class="h-48 w-full md:w-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-4xl">🏠</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6 flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-lg text-gray-800">
                                            {{ $review->property->title }}
                                        </h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $review->is_approved ? 'Approved' : 'Pending Approval' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        📍 {{ $review->property->address }}, {{ $review->property->barangay->name ?? '' }}
                                    </p>
                                    <div class="flex items-center mb-3">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-gray-600 mb-2">{{ Str::limit($review->comment, 200) }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">
                                        Reviewed on {{ $review->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($reviews->hasPages())
                    <div class="mt-8">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No reviews yet</h3>
                    <p class="mt-2 text-gray-500">Complete a booking to leave a review!</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
