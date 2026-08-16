<?= $this->include('front/layout/header') ?>

<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<style>
    .favorite-btn.saved .material-symbols-outlined { font-variation-settings: 'FILL' 1; color: #e11d48; }
</style>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-10 min-h-[calc(100vh-80px)]">
    <div class="mb-8">
        <h1 class="font-headline-lg text-[32px] font-bold text-on-background"><?= lang('Front.prop_real_estate_in') ?> <?= esc($state->name) ?></h1>
        <p class="text-on-surface-variant mt-2"><?= lang('Front.prop_region_sub') ?></p>
    </div>

    <!-- City Statistics Block -->
    <?php if(!empty($cityStats)): ?>
    <div class="bg-surface border border-outline-variant rounded-lg p-6 mb-10 shadow-sm">
        <h2 class="font-headline-md text-[20px] font-bold text-primary mb-4"><?= lang('Front.prop_market_overview') ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach($cityStats as $stat): ?>
                <div class="p-4 bg-surface-container-lowest border border-outline-variant rounded flex flex-col">
                    <span class="font-bold text-on-surface text-[16px]"><?= esc($stat->city_name) ?></span>
                    <span class="text-on-surface-variant text-[14px] mt-1"><?= $stat->property_count ?> <?= lang('Front.prop_properties_count') ?></span>
                    <span class="text-primary font-bold mt-2"><?= lang('Front.prop_avg_price') ?> Rp <?= number_format($stat->avg_price, 0, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Property Grid -->
    <h2 class="font-headline-md text-[24px] font-bold text-on-background mb-6"><?= lang('Front.prop_available') ?></h2>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if(!empty($properties)): foreach($properties as $property): ?>
            <?php 
                $cSlug = url_title(strtolower($property['city_name'] ?? 'indonesia'), '-', true);
                $tSlug = url_title(strtolower($property['title']), '-', true);
                $seoUrl = base_url("property/{$cSlug}/{$tSlug}-{$property['id']}");
            ?>
            <article class="bg-surface border border-outline-variant rounded overflow-hidden hover:shadow-md transition-shadow flex flex-col relative">
                <button onclick="toggleFavorite(<?= $property['id'] ?>, this)" class="favorite-btn absolute top-3 right-3 z-30 bg-surface/90 backdrop-blur-sm text-outline hover:text-error p-2 rounded-full shadow-md transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">favorite</span>
                </button>

                <a href="<?= $seoUrl ?>" class="h-48 bg-surface-container-high block relative">
                    <?php 
                        $imgPath = trim($property['image_path'] ?? $property['image'] ?? '');
                        $imgSrc = 'https://placehold.co/800x600/e2e8f0/8492a6?text=Property+Image';
                        if (!empty($imgPath)) {
                            $imgSrc = (strpos($imgPath, 'http') === 0) ? esc($imgPath) : base_url(esc($imgPath));
                        }
                    ?>
                    <img alt="<?= esc($property['title']) ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" src="<?= $imgSrc ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x600/e2e8f0/8492a6?text=No+Image+Available';"/>
                    <div class="absolute top-2 left-2 bg-secondary-container text-on-secondary-container text-[10px] font-bold px-2 py-1 rounded uppercase">
                        <?= esc($property['listing_type']) ?>
                    </div>
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <a href="<?= $seoUrl ?>" class="font-bold text-[16px] text-on-surface hover:text-primary line-clamp-2"><?= esc($property['title']) ?></a>
                    <span class="font-bold text-primary-container mt-2">Rp <?= number_format($property['tax_price'], 0, ',', '.') ?></span>
                </div>
            </article>
        <?php endforeach; else: ?>
            <p class="col-span-full text-center text-on-surface-variant py-8"><?= lang('Front.prop_no_region') ?></p>
        <?php endif; ?>
    </div>

    <?php if (isset($pager)) : ?>
        <div class="mt-8">
            <?= $pager->links('default', 'tailwind_pagination') ?>
        </div>
    <?php endif ?>
</main>

<?= $this->include('components/login_modal') ?>
<?= $this->include('front/layout/footer') ?>

<script>
    function toggleFavorite(propertyId, btnElement) {
        const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
        const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');

        fetch('<?= base_url('property/toggle-save') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfName]: csrfHash
            },
            body: JSON.stringify({ property_id: propertyId })
        })
        .then(response => {
            if (response.status === 401) {
                if(typeof openAuthModal === 'function') {
                    openAuthModal();
                } else {
                    window.location.href = '<?= base_url('login') ?>';
                }
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                if (data.action === 'added') {
                    btnElement.classList.add('saved');
                } else {
                    btnElement.classList.remove('saved');
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>