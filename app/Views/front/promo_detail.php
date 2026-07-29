<?= $this->include('front/layout/header') ?>

<main class="flex-grow bg-surface-container-lowest py-10">
    <div class="max-w-4xl mx-auto px-4 md:px-0">
        
        <div class="mb-6">
            <a href="<?= base_url('/') ?>" class="text-primary font-medium hover:underline flex items-center gap-1 w-fit">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Home
            </a>
        </div>

        <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <!-- FIX: Removed the duplicate 'uploads/ads/' since it's already in $promo->image_path -->
            <?php if (!empty($promo->image_path)): ?>
                <div class="w-full h-64 md:h-96">
                    <img src="<?= base_url(esc($promo->image_path)) ?>" alt="<?= esc($promo->title) ?>" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            
            <div class="p-8">
                <h1 class="text-3xl font-bold text-on-background mb-4"><?= esc($promo->title) ?></h1>
                
                <div class="prose max-w-none text-on-surface-variant text-base leading-relaxed">
                    <?= nl2br(esc($promo->description)) ?>
                </div>
            </div>
        </div>

    </div>
</main>

<?= $this->include('front/layout/footer') ?>