<?= $this->include('front/layout/header') ?>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-10 min-h-[calc(100vh-80px)]">
    <div class="mb-8">
        <h1 class="font-headline-lg text-[32px] font-bold text-on-background">Real Estate in <?= esc($state->name) ?></h1>
        <p class="text-on-surface-variant mt-2">Explore properties and market statistics across the region.</p>
    </div>

    <!-- City Statistics Block -->
    <?php if(!empty($cityStats)): ?>
    <div class="bg-surface border border-outline-variant rounded-lg p-6 mb-10 shadow-sm">
        <h2 class="font-headline-md text-[20px] font-bold text-primary mb-4">Market Overview by City</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach($cityStats as $stat): ?>
                <div class="p-4 bg-surface-container-lowest border border-outline-variant rounded flex flex-col">
                    <span class="font-bold text-on-surface text-[16px]"><?= esc($stat->city_name) ?></span>
                    <span class="text-on-surface-variant text-[14px] mt-1"><?= $stat->property_count ?> Properties</span>
                    <span class="text-primary font-bold mt-2">Avg: Rp <?= number_format($stat->avg_price, 0, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Property Grid -->
    <h2 class="font-headline-md text-[24px] font-bold text-on-background mb-6">Available Properties</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if(!empty($properties)): foreach($properties as $property): ?>
            <article class="bg-surface border border-outline-variant rounded overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                <a href="<?= base_url('property/' . $property['id']) ?>" class="h-48 bg-surface-container-high block relative">
                    <img alt="<?= esc($property['title']) ?>" class="w-full h-full object-cover" src="<?= esc($property['image_path'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80') ?>"/>
                    <div class="absolute top-2 left-2 bg-secondary-container text-on-secondary-container text-[10px] font-bold px-2 py-1 rounded uppercase">
                        <?= esc($property['listing_type']) ?>
                    </div>
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <a href="<?= base_url('property/' . $property['id']) ?>" class="font-bold text-[16px] text-on-surface hover:text-primary line-clamp-2"><?= esc($property['title']) ?></a>
                    <span class="font-bold text-primary-container mt-2">Rp <?= number_format($property['tax_price'], 0, ',', '.') ?></span>
                </div>
            </article>
        <?php endforeach; else: ?>
            <p class="col-span-full text-center text-on-surface-variant py-8">No properties found in this region yet.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($pager)) : ?>
        <div class="mt-8">
            <?= $pager->links('default', 'tailwind_pagination') ?>
        </div>
    <?php endif ?>
</main>

<?= $this->include('front/layout/footer') ?>