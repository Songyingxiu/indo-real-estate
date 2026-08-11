<?= $this->include('front/layout/header') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style>
    .swiper-button-next::after,
    .swiper-button-prev::after { font-size: 24px !important; font-weight: bold; }
    .swiper-button-next, .swiper-button-prev { color: var(--color-primary, #0d6efd); background-color: rgba(255, 255, 255, 0.8); width: 44px; height: 44px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .swiper-button-next:hover, .swiper-button-prev:hover { background-color: #ffffff; }
</style>

<main class="flex-grow">
    <section class="relative w-full h-[600px] flex items-center justify-center bg-surface-container-highest">
        <div class="absolute inset-0 z-0">
            <img alt="Modern exterior at dusk" class="w-full h-full object-cover opacity-80" src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1920&q=80">
            <div class="absolute inset-0 bg-primary/40"></div>
        </div>
        
        <div class="relative z-10 w-full max-w-4xl px-4 md:px-0">
            <div class="text-center mb-8">
                <h1 class="font-headline-xl text-[32px] md:text-[48px] font-bold text-white mb-2 drop-shadow-md"><?= lang('Front.hero_title') ?></h1>
                <p class="font-body-lg text-[18px] text-white/90 drop-shadow-sm"><?= lang('Front.hero_subtitle') ?></p>
            </div>
            
            <div class="bg-surface rounded-lg shadow-lg p-4 md:p-6 flex flex-col gap-4 border border-outline-variant">
                <form id="homeSearchForm" action="<?= base_url('search/sale') ?>" method="GET" class="flex flex-col gap-4">
                    <div class="flex gap-2 border-b border-outline-variant pb-2">
                        <button type="button" onclick="document.getElementById('homeSearchForm').action='<?= base_url('search/sale') ?>'; this.classList.add('border-primary', 'text-primary'); this.classList.remove('border-transparent', 'text-on-surface-variant'); this.nextElementSibling.classList.add('border-transparent', 'text-on-surface-variant'); this.nextElementSibling.classList.remove('border-primary', 'text-primary');" class="font-label-md text-[14px] px-4 py-2 border-b-2 border-primary text-primary transition-colors"><?= lang('Front.btn_for_sale') ?></button>
                        <button type="button" onclick="document.getElementById('homeSearchForm').action='<?= base_url('search/rent') ?>'; this.classList.add('border-primary', 'text-primary'); this.classList.remove('border-transparent', 'text-on-surface-variant'); this.previousElementSibling.classList.add('border-transparent', 'text-on-surface-variant'); this.previousElementSibling.classList.remove('border-primary', 'text-primary');" class="font-label-md text-[14px] px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors"><?= lang('Front.btn_for_rent') ?></button>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 relative">
                        <div class="relative flex-grow">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                            <input name="q" id="searchInput" autocomplete="off" class="w-full pl-10 pr-4 py-3 rounded border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-body-md text-[16px] text-on-surface" placeholder="<?= lang('Front.placeholder_search') ?>" type="text">
                            <div id="suggestDropdown" class="absolute left-0 right-0 top-full mt-1 bg-surface border border-outline-variant rounded shadow-lg z-50 hidden max-h-60 overflow-y-auto"></div>
                        </div>
                        <button type="submit" class="bg-primary text-on-primary font-label-md text-[14px] font-semibold px-6 py-3 rounded hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap">
                            <?= lang('Front.btn_search') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php if (!empty($banners)): ?>
        <div class="w-full max-w-7xl mx-auto px-4 mt-8 mb-2">
            <div class="flex overflow-x-auto gap-4 snap-x pb-4 custom-scrollbar">
                <?php foreach ($banners as $banner): ?>
                    <div class="min-w-full md:min-w-[50%] lg:min-w-[33%] snap-center rounded-xl overflow-hidden shadow-md bg-surface border border-outline-variant group">
                        <a href="<?= base_url('promo/' . $banner->id) ?>" class="block w-full h-full relative">
                            <?php 
                                $adImg = $banner->image_path ?? $banner->image ?? '';
                                $adSrc = (strpos(trim($adImg), 'http') === 0) ? esc($adImg) : base_url('uploads/ads/' . esc($adImg)); 
                            ?>
                            <img src="<?= $adSrc ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x400/e2e8f0/8492a6?text=Advertisement';" alt="<?= esc($banner->title) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                                <h3 class="text-white font-bold text-lg drop-shadow-md"><?= esc($banner->title) ?></h3>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <section class="max-w-[1280px] mx-auto px-4 md:px-10 py-16">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-background mb-2"><?= lang('Front.sec_popular') ?></h2>
                <p class="text-on-surface-variant font-body-lg"><?= lang('Front.sec_popular_sub') ?></p>
            </div>
            <a href="<?= base_url('search/sale') ?>" class="flex items-center gap-1 text-primary font-label-md text-[14px] font-semibold hover:underline">
                <?= lang('Front.btn_view_all') ?> <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($featuredProperties)): ?>
                <?php foreach ($featuredProperties as $property): ?>
                    <?php 
                        $cSlug = url_title(strtolower($property->city_name ?? 'indonesia'), '-', true);
                        $tSlug = url_title(strtolower($property->title), '-', true);
                        $seoUrl = base_url("property/{$cSlug}/{$tSlug}-{$property->id}");
                    ?>
                    <article class="property-card bg-surface border border-outline-variant rounded flex flex-col overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <a href="<?= $seoUrl ?>" class="relative h-64 overflow-hidden rounded-t-lg bg-surface-container-high block group">
                            <?php 
                                $imgPath = trim($property->image_path ?? '');
                                $imgSrc = 'https://placehold.co/800x600/e2e8f0/8492a6?text=Property+Image';
                                if (!empty($imgPath)) {
                                    $imgSrc = (strpos($imgPath, 'http') === 0) ? esc($imgPath) : base_url(esc($imgPath));
                                }
                            ?>
                            <img alt="<?= esc($property->title) ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x600/e2e8f0/8492a6?text=No+Image+Available';" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= $imgSrc ?>">
                            <div class="absolute bottom-4 right-4 bg-surface/90 backdrop-blur-sm px-3 py-1 rounded border border-outline-variant shadow-sm">
                                <span class="font-headline-lg text-[20px] font-bold text-on-background">Rp <?= number_format($property->tax_price, 0, ',', '.') ?></span>
                            </div>
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-label-md text-[18px] font-semibold text-on-background mb-1 truncate">
                                <a href="<?= $seoUrl ?>" class="hover:text-primary transition-colors">
                                    <?= esc($property->title) ?>
                                </a>
                            </h3>
                            <p class="font-body-md text-[14px] text-on-surface-variant mb-4 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">location_on</span> <?= esc($property->area_name ?? $property->city_name ?? $property->address_line_1 ?? lang('Front.lbl_location_not_set')) ?>
                            </p>
                            <div class="grid grid-cols-3 gap-2 border-t border-outline-variant pt-4 mt-auto">
                                <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">bed</span> <?= esc($property->bed) ?> <?= lang('Front.lbl_beds') ?></div>
                                <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">shower</span> <?= esc($property->bath) ?> <?= lang('Front.lbl_baths') ?></div>
                                <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">square_foot</span> <?= esc($property->total_area ?? $property->total_land_area ?? 0) ?> <?= lang('Front.lbl_sqm') ?></div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-3 text-center text-on-surface-variant py-10"><?= lang('Front.lbl_no_popular') ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="bg-surface-container-lowest py-16 border-y border-outline-variant/50">
        <div class="max-w-[1280px] mx-auto px-4 md:px-10 relative">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-background mb-2"><?= lang('Front.sec_newly') ?></h2>
                    <p class="text-on-surface-variant font-body-lg"><?= lang('Front.sec_newly_sub') ?></p>
                </div>
            </div>

            <div class="swiper newlyListedSwiper !pb-14 !pt-2">
                <div class="swiper-wrapper">
                    <?php if (!empty($newestProperties)): ?>
                        <?php foreach ($newestProperties as $property): ?>
                            <?php 
                                $cSlug = url_title(strtolower($property->city_name ?? 'indonesia'), '-', true);
                                $tSlug = url_title(strtolower($property->title), '-', true);
                                $seoUrl = base_url("property/{$cSlug}/{$tSlug}-{$property->id}");
                            ?>
                            <div class="swiper-slide h-auto">
                                <article class="property-card h-full bg-surface border border-outline-variant rounded flex flex-col overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                    <a href="<?= $seoUrl ?>" class="relative h-64 overflow-hidden rounded-t-lg bg-surface-container-high block group shrink-0">
                                        <div class="absolute top-4 left-4 z-10 bg-secondary text-on-secondary text-xs font-bold px-3 py-1 rounded shadow-md uppercase tracking-wider"><?= lang('Front.lbl_new') ?></div>
                                        <?php 
                                            $imgPath = trim($property->image_path ?? '');
                                            $imgSrc = 'https://placehold.co/800x600/e2e8f0/8492a6?text=Property+Image';
                                            if (!empty($imgPath)) {
                                                $imgSrc = (strpos($imgPath, 'http') === 0) ? esc($imgPath) : base_url(esc($imgPath));
                                            }
                                        ?>
                                        <img alt="<?= esc($property->title) ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x600/e2e8f0/8492a6?text=No+Image+Available';" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= $imgSrc ?>">
                                        <div class="absolute bottom-4 right-4 bg-surface/90 backdrop-blur-sm px-3 py-1 rounded border border-outline-variant shadow-sm">
                                            <span class="font-headline-lg text-[20px] font-bold text-on-background">Rp <?= number_format($property->tax_price, 0, ',', '.') ?></span>
                                        </div>
                                    </a>
                                    <div class="p-4 flex flex-col flex-grow">
                                        <h3 class="font-label-md text-[18px] font-semibold text-on-background mb-1 truncate">
                                            <a href="<?= $seoUrl ?>" class="hover:text-primary transition-colors">
                                                <?= esc($property->title) ?>
                                            </a>
                                        </h3>
                                        <p class="font-body-md text-[14px] text-on-surface-variant mb-4 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">location_on</span> <?= esc($property->area_name ?? $property->city_name ?? $property->address_line_1 ?? lang('Front.lbl_location_not_set')) ?>
                                        </p>
                                        <div class="grid grid-cols-3 gap-2 border-t border-outline-variant pt-4 mt-auto">
                                            <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">bed</span> <?= esc($property->bed) ?> <?= lang('Front.lbl_beds') ?></div>
                                            <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">shower</span> <?= esc($property->bath) ?> <?= lang('Front.lbl_baths') ?></div>
                                            <div class="flex items-center gap-1 text-on-surface-variant text-[12px]"><span class="material-symbols-outlined text-[18px]">square_foot</span> <?= esc($property->total_area ?? $property->total_land_area ?? 0) ?> <?= lang('Front.lbl_sqm') ?></div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="w-full text-center text-on-surface-variant py-10"><?= lang('Front.lbl_no_new') ?></p>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="swiper-button-prev -left-5 md:-left-6 hidden md:flex"></div>
            <div class="swiper-button-next -right-5 md:-right-6 hidden md:flex"></div>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-4 md:px-10 py-16">
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <h2 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-background mb-3"><?= lang('Front.sec_faq') ?></h2>
            <p class="text-on-surface-variant font-body-lg"><?= lang('Front.sec_faq_sub') ?></p>
        </div>

        <div class="max-w-3xl mx-auto flex flex-col gap-4">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $faq): ?>
                    <details class="group bg-surface border border-outline-variant rounded-lg [&_summary::-webkit-details-marker]:hidden shadow-sm">
                        <summary class="flex items-center justify-between p-5 font-label-md text-[16px] text-on-background cursor-pointer hover:text-primary transition-colors">
                            <?= esc($faq->title) ?>
                            <span class="material-symbols-outlined transition duration-300 group-open:-rotate-180">expand_more</span>
                        </summary>
                        <div class="p-5 pt-0 text-on-surface-variant font-body-md border-t border-outline-variant/30 mt-2 leading-relaxed">
                            <?= $faq->content_body ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center p-8 bg-surface-container-lowest border border-outline-variant rounded-lg">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/50 mb-2">help_outline</span>
                    <p class="text-on-surface-variant text-sm"><?= lang('Front.lbl_no_faq') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.newlyListedSwiper', {
        slidesPerView: 1,
        spaceBetween: 24,
        pagination: { el: '.swiper-pagination', clickable: true, dynamicBullets: true, },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev', },
        grabCursor: true, 
        breakpoints: {
            768: { slidesPerView: 2, },
            1024: { slidesPerView: 3, },
        }
    });

    const searchInput = document.getElementById('searchInput');
    const suggestDropdown = document.getElementById('suggestDropdown');
    let timeout = null;

    if (!searchInput || !suggestDropdown) return;

    const createSlug = (text) => text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');

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
                            if (item.category === 'City' || item.category === 'Province') icon = 'location_on';
                            if (item.category === 'Zipcode') icon = 'markunread_mailbox';
                            
                            div.innerHTML = `
                                <div class="flex items-center gap-2 text-on-surface">
                                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant">${icon}</span>
                                    <span class="font-body-md text-[14px]">${item.text} ${item.parent_text ? `<span class="text-xs text-on-surface-variant/70">, ${item.parent_text}</span>` : ''}</span>
                                </div>
                                <span class="text-[10px] bg-surface-container px-2 py-0.5 rounded text-on-surface-variant uppercase font-bold">${item.category}</span>
                            `;
                            
                            div.addEventListener('click', () => {
                                let targetUrl = '';
                                if (item.url) {
                                    targetUrl = item.url;
                                } else {
                                    const slug = createSlug(item.text);
                                    if (item.category === 'Province' || item.category === 'State') {
                                        targetUrl = `<?= base_url('properties/sale/province') ?>/${slug}`;
                                    } else if (item.category === 'City' || item.category === 'Location') {
                                        const stateSlug = item.parent_text ? createSlug(item.parent_text) : 'indonesia';
                                        targetUrl = `<?= base_url('properties/sale/city') ?>/${slug}/${stateSlug}`;
                                    } else if (item.category === 'Zipcode') {
                                        targetUrl = `<?= base_url('properties/sale/zipcode') ?>/${item.text}`;
                                    } else {
                                        targetUrl = `<?= base_url('search/sale') ?>?q=${encodeURIComponent(item.text)}`;
                                    }
                                }
                                window.location.href = targetUrl;
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