<section class="relative h-[80vh] md:h-[90vh] flex items-center justify-center text-white">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://placehold.co/1920x1080/0d1321/e5e7eb?text=Cagayan+de+Oro+City');">
        <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
    </div>
    <div class="relative z-10 text-center max-w-4xl px-4">
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4">Find your exclusive stay in Cagayan de Oro.</h1>
        <p class="text-lg md:text-xl font-medium opacity-90 mb-8">
            Discover hand-picked properties for your perfect visit.
        </p>

        <form action="<?php echo e(route('properties.search')); ?>" method="GET" class="bg-white p-2 md:p-4 rounded-full shadow-2xl flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
            <?php echo csrf_field(); ?>
            <div class="flex items-center w-full md:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <input type="text" name="location" placeholder="Location" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none" value="Cagayan de Oro"/>
            </div>
            <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                <input type="text" name="dates" placeholder="Add Dates" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none"/>
            </div>
            <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <input type="number" name="guests" placeholder="Guests" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none" min="1" value="2"/>
            </div>
            <button type="submit" class="w-full md:w-auto bg-teal-500 text-white font-bold px-8 py-3 rounded-full hover:bg-teal-600 transition-colors shadow-md flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="md:hidden"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <span class="md:hidden ml-2">Search</span>
            </button>
        </form>
    </div>
</section>
<?php /**PATH C:\Users\Administrator\homygo\resources\views/partials/hero.blade.php ENDPATH**/ ?>