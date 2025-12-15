<x-app-layout>
    @section('title', 'My Favorites')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">My Favorite Properties</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($favorites->count() > 0)
                <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                    <div class="properties-grid">
                        @foreach($favorites as $favorite)
                            @if($favorite->property)
                                <div class="relative">
                                    <x-property-card :property="$favorite->property" />
                                    <form action="{{ route('favorites.toggle', $favorite->property) }}" method="POST"
                                        class="absolute top-4 right-4 z-10">
                                        @csrf
                                        <button type="submit"
                                            class="bg-white rounded-full p-2 shadow-lg text-red-500 hover:text-red-600 transition"
                                            title="Remove from favorites">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                @if($favorites->hasPages())
                    <div class="mt-8">
                        {{ $favorites->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No favorites yet</h3>
                    <p class="mt-2 text-gray-500">Start browsing properties and add them to your favorites!</p>
                    <div class="mt-6">
                        <a href="{{ route('properties.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Browse Properties
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
