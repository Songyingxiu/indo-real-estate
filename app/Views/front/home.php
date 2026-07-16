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
                <form action="<?= base_url('search') ?>" method="GET" class="flex flex-col gap-4">
                    
                    <input type="hidden" name="listing_type" id="home_listing_type" value="Sale">

                    <div class="flex gap-2 border-b border-outline-variant pb-2">
                        <button type="button" onclick="document.getElementById('home_listing_type').value='Sale'; this.classList.add('border-primary', 'text-primary'); this.classList.remove('border-transparent', 'text-on-surface-variant'); this.nextElementSibling.classList.add('border-transparent', 'text-on-surface-variant'); this.nextElementSibling.classList.remove('border-primary', 'text-primary');" class="font-label-md text-[14px] px-4 py-2 border-b-2 border-primary text-primary transition-colors">For Sale</button>
                        
                        <button type="button" onclick="document.getElementById('home_listing_type').value='Rent'; this.classList.add('border-primary', 'text-primary'); this.classList.remove('border-transparent', 'text-on-surface-variant'); this.previousElementSibling.classList.add('border-transparent', 'text-on-surface-variant'); this.previousElementSibling.classList.remove('border-primary', 'text-primary');" class="font-label-md text-[14px] px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors">For Rent</button>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 relative">
                        <div class="relative flex-grow">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                            
                            <input name="q" id="searchInput" autocomplete="off" class="w-full pl-10 pr-4 py-3 rounded border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-body-md text-[16px] text-on-surface" placeholder="Search by Keyword, Address, or ID..." type="text">
                            
                            <div id="suggestDropdown" class="absolute left-0 right-0 top-full mt-1 bg-surface border border-outline-variant rounded shadow-lg z-50 hidden max-h-60 overflow-y-auto"></div>
                        </div>
                        <button type="submit" class="bg-primary text-on-primary font-label-md text-[14px] font-semibold px-6 py-3 rounded hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap">
                            Search Properties
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-4 md:px-10 py-16">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-background mb-2">Popular Listings</h2>
                <p class="text-on-surface-variant font-body-lg">Trending properties currently highly sought after.</p>
            </div>
            <a href="<?= base_url('search') ?>" class="flex items-center gap-1 text-primary font-label-md text-[14px] font-semibold hover:underline">
                View All <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($featuredProperties)): ?>
                <?php foreach ($featuredProperties as $property): ?>
                    <article class="property-card bg-surface border border-outline-variant rounded flex flex-col overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <a href="<?= base_url('property/' . $property->id) ?>" class="relative h-64 overflow-hidden rounded-t-lg bg-surface-container-high block group">
                            <?php $imgSrc = !empty($property->image_path) ? base_url(esc($property->image_path)) : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'; ?>
                            <img alt="<?= esc($property->title) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= $imgSrc ?>">
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
                <p class="col-span-3 text-center text-on-surface-variant py-10">No popular properties currently available.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="bg-surface-container-lowest py-16 border-y border-outline-variant/50">
        <div class="max-w-[1280px] mx-auto px-4 md:px-10">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-background mb-2">Newly Listed</h2>
                    <p class="text-on-surface-variant font-body-lg">Be the first to check out these fresh properties on the market.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($newestProperties)): ?>
                    <?php foreach ($newestProperties as $property): ?>
                        <article class="property-card bg-surface border border-outline-variant rounded flex flex-col overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <a href="<?= base_url('property/' . $property->id) ?>" class="relative h-64 overflow-hidden rounded-t-lg bg-surface-container-high block group">
                                <div class="absolute top-4 left-4 z-10 bg-secondary text-on-secondary text-xs font-bold px-3 py-1 rounded shadow-md uppercase tracking-wider">New</div>
                                <?php $imgSrc = !empty($property->image_path) ? base_url(esc($property->image_path)) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'; ?>
                                <img alt="<?= esc($property->title) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= $imgSrc ?>">
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
                    <p class="col-span-3 text-center text-on-surface-variant py-10">No new properties currently available.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-4 md:px-10 py-16">
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <h2 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-background mb-3">Real Estate Tips & Guides</h2>
            <p class="text-on-surface-variant font-body-lg">Expert advice, market insights, and guides to help you navigate buying, selling, and renting properties.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (!empty($tips)): ?>
                <?php foreach ($tips as $tip): ?>
                    <article class="bg-surface rounded-xl border border-outline-variant flex flex-col hover:shadow-lg transition-shadow duration-300 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Guide</span>
                            <span class="text-xs text-on-surface-variant font-semibold tracking-wide">
                                <?= date('F j, Y', strtotime($tip->published_at ?? $tip->created_at ?? 'now')) ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-on-background mb-3 leading-tight hover:text-primary transition-colors">
                            <a href="<?= base_url('page/' . $tip->slug) ?>">
                                <?= esc($tip->title) ?>
                            </a>
                        </h3>
                        <p class="text-on-surface-variant text-sm mb-6 flex-grow line-clamp-3">
                            <?= esc(substr(strip_tags($tip->content_body), 0, 120)) ?>...
                        </p>
                        <a href="<?= base_url('page/' . $tip->slug) ?>" class="text-primary font-bold text-sm flex items-center gap-1 group mt-auto w-fit">
                            Read Article 
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 bg-surface-container-lowest p-8 text-center rounded border border-outline-variant">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/50 mb-2">article</span>
                    <h3 class="text-lg font-bold text-on-surface mb-1">More Guides Coming Soon</h3>
                    <p class="text-on-surface-variant text-sm">Our experts are currently writing more helpful tips. Check back later!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const suggestDropdown = document.getElementById('suggestDropdown');
    let timeout = null;

    if (!searchInput || !suggestDropdown) return;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();

        if (query.length < 2) {
            suggestDropdown.classList.add('hidden');
            return;
        }

        timeout = setTimeout(() => {
            fetch(`<?= base_url('api/suggest') ?>?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestDropdown.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'px-4 py-2 hover:bg-surface-container-low cursor-pointer flex items-center justify-between border-b border-outline-variant/30 last:border-0';
                            
                            let icon = 'real_estate_agent';
                            if (item.category === 'Location' || item.category === 'Region') icon = 'location_on';
                            
                            div.innerHTML = `
                                <div class="flex items-center gap-2 text-on-surface">
                                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant">${icon}</span>
                                    <span class="font-body-md text-[14px]">${item.text}</span>
                                </div>
                                <span class="text-[10px] bg-surface-container px-2 py-0.5 rounded text-on-surface-variant uppercase font-bold">${item.category}</span>
                            `;
                            
                            div.addEventListener('click', () => {
                                searchInput.value = item.text;
                                suggestDropdown.classList.add('hidden');
                                searchInput.closest('form').submit();
                            });
                            suggestDropdown.appendChild(div);
                        });
                        suggestDropdown.classList.remove('hidden');
                    } else {
                        suggestDropdown.classList.add('hidden');
                    }
                })
                .catch(err => console.error('Error:', err));
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestDropdown.contains(e.target)) {
            suggestDropdown.classList.add('hidden');
        }
    });
});
</script>

<?= $this->include('front/layout/footer') ?>