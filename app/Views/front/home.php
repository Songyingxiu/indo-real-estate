<?= $this->include('front/layout/header') ?>

<main class="flex-grow">
    <section class="relative w-full h-[600px] flex items-center justify-center bg-surface-container-highest">
        <div class="absolute inset-0 z-0">
            <img alt="Modern exterior at dusk" class="w-full h-full object-cover opacity-80" src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1920&q=80">
            <div class="absolute inset-0 bg-primary/40"></div>
        </div>
        
        <div class="relative z-10 w-full max-w-4xl px-4 md:px-0">
            <div class="text-center mb-8">
                <h1 class="font-headline-xl text-[32px] md:text-[48px] font-bold text-white mb-2 drop-shadow-md">Find Your Perfect Property</h1>
                <p class="font-body-lg text-[18px] text-white/90 drop-shadow-sm">Discover high-end real estate tailored to your lifestyle with HuniKita.</p>
            </div>
            
            <div class="bg-surface rounded-lg shadow-lg p-4 md:p-6 flex flex-col gap-4 border border-outline-variant">
                <form action="<?= base_url('search') ?>" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-grow">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                        <input name="q" class="w-full pl-10 pr-4 py-3 rounded border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-body-md text-[16px] text-on-surface" placeholder="Search by Keyword, Address, or ID..." type="text">
                    </div>
                    <button type="submit" class="bg-primary text-on-primary font-label-md text-[14px] font-semibold px-6 py-3 rounded hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap">
                        Search Properties
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-4 md:px-10 py-16">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-background mb-2">Popular Listings</h2>
            </div>
            <a href="<?= base_url('search') ?>" class="flex items-center gap-1 text-primary font-label-md text-[14px] font-semibold hover:underline">
                View All <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($featuredProperties)): ?>
                <?php foreach ($featuredProperties as $property): ?>
                    <article class="property-card bg-surface border border-outline-variant rounded flex flex-col overflow-hidden">
                        <a href="<?= base_url('property/' . $property->id) ?>" class="relative h-64 overflow-hidden rounded-t-lg bg-surface-container-high block">
                            <?php $imgSrc = !empty($property->image_path) ? base_url(esc($property->image_path)) : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'; ?>
                            <img alt="<?= esc($property->title) ?>" class="w-full h-full object-cover" src="<?= $imgSrc ?>">
                            <div class="absolute bottom-4 right-4 bg-surface/90 backdrop-blur-sm px-3 py-1 rounded border border-outline-variant shadow-sm">
                                <span class="font-headline-lg text-[20px] font-bold text-on-background">Rp <?= number_format($property->tax_price, 0, ',', '.') ?></span>
                            </div>
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-label-md text-[18px] font-semibold text-on-background mb-1 truncate">
                                <a href="<?= base_url('property/' . $property->id) ?>" class="hover:text-primary transition-colors">
                                    <?= esc($property->title) ?>
                                </a>
                            </h3>
                            <p class="font-body-md text-[14px] text-on-surface-variant mb-4 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">location_on</span> <?= esc($property->area_name) ?>
                            </p>
                            <div class="grid grid-cols-3 gap-2 border-t border-outline-variant pt-4 mt-auto">
                                <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">bed</span> <?= esc($property->bed) ?> Beds</div>
                                <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">shower</span> <?= esc($property->bath) ?> Baths</div>
                                <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">square_foot</span> <?= esc($property->usable_area) ?> m²</div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-3 text-center text-on-surface-variant">No properties currently available.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?= $this->include('front/layout/footer') ?>