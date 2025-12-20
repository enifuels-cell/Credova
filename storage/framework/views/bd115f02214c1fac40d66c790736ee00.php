<section class="py-20 bg-gray-900 text-white text-center">
    <div class="mx-auto max-w-3xl px-4">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Become a HomyGO Host</h2>
        <p class="text-lg font-medium opacity-90 mb-8">
            Share your space with a community of vetted guests. It's simple, secure, and rewarding.
        </p>
        <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('register')); ?>" class="bg-teal-500 text-white font-bold px-8 py-4 rounded-full shadow-lg hover:bg-teal-600 transition-colors inline-block">
                Start Hosting Today
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('properties.create')); ?>" class="bg-teal-500 text-white font-bold px-8 py-4 rounded-full shadow-lg hover:bg-teal-600 transition-colors inline-block">
                List Your Property
            </a>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\Users\Administrator\homygo\resources\views/partials/cta.blade.php ENDPATH**/ ?>