<section class="py-16 md:py-24 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4">
        <div class="md:grid md:grid-cols-2 md:gap-12">
            <div class="text-center md:text-left mb-10 md:mb-0">
                <p class="text-teal-500 text-sm font-bold uppercase mb-2">Our Promise</p>
                <h2 class="text-3xl md:text-4xl font-extrabold leading-tight">
                    A rental platform designed for our city.
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    We go beyond the typical rental experience by focusing exclusively on what makes our city great.
                </p>
            </div>
            <div class="space-y-8">
                <?php
                    $benefits = [
                        ['title' => 'Local Expertise', 'description' => 'Our team lives and breathes Cagayan de Oro, ensuring you get the best local recommendations and support.'],
                        ['title' => 'Hand-Picked Properties', 'description' => 'We don\'t just list properties; we curate them. Every home is selected for its unique charm and quality.'],
                        ['title' => 'Community Focused', 'description' => 'HomyGO is built by locals, for locals. We support our community by featuring small businesses and local hosts.'],
                    ];
                ?>
                <?php $__currentLoopData = $benefits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $benefit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500 mt-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold mb-1"><?php echo e($benefit['title']); ?></h3>
                            <p class="text-gray-600"><?php echo e($benefit['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\Administrator\homygo\resources\views/partials/why_choose_us.blade.php ENDPATH**/ ?>