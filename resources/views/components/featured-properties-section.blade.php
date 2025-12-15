@if(isset($featuredProperties) && $featuredProperties->count() > 0)
<section class="py-10 sm:py-12 md:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 sm:mb-12 gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Featured Properties</h2>
                <p class="text-gray-600 text-sm sm:text-base">Handpicked properties just for you</p>
            </div>
            <a href="{{ route('properties.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium text-base touch-target px-4 py-2 -mx-4 sm:mx-0 rounded-lg hover:bg-emerald-50 transition">
                View All
                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="properties-grid">
            @foreach(($featuredProperties ?? collect())->take(4) as $property)
                @include('components.property-card', ['property' => $property])
            @endforeach
        </div>
    </div>
</section>
@endif
