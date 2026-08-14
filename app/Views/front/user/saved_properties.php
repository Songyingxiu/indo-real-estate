<?= $this->include('front/layout/header') ?>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-8 flex-grow w-full" x-data="{ activeTab: 'properties' }">
    <div class="flex items-center gap-3 mb-8">
        <span class="material-symbols-outlined text-primary text-[32px] fill">favorite</span>
        <h1 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-surface">My Collections</h1>
    </div>

    <!-- TABS -->
    <div class="flex border-b border-outline-variant mb-6">
        <button @click="activeTab = 'properties'" 
                :class="activeTab === 'properties' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
                class="px-6 py-3 font-label-md font-bold text-[14px] border-b-2 transition-colors">
            Saved Properties
        </button>
        <button @click="activeTab = 'searches'" 
                :class="activeTab === 'searches' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
                class="px-6 py-3 font-label-md font-bold text-[14px] border-b-2 transition-colors">
            Saved Searches
        </button>
    </div>

    <!-- PROPERTIES TAB -->
    <div x-show="activeTab === 'properties'" x-transition.opacity>
        <?php if (empty($properties)): ?>
            <div class="flex flex-col items-center justify-center py-20 bg-surface border border-outline-variant rounded-xl shadow-sm">
                <span class="material-symbols-outlined text-[64px] text-outline-variant mb-4 opacity-50">heart_broken</span>
                <h2 class="font-headline-md text-xl font-bold text-on-surface mb-2"><?= lang('Front.saved_no_title') ?></h2>
                <p class="text-on-surface-variant font-body-md text-center max-w-md mb-6"><?= lang('Front.saved_no_desc') ?></p>
                <a href="<?= base_url('search') ?>" class="bg-primary text-on-primary px-6 py-3 rounded font-bold hover:bg-primary-container transition-colors">
                    <?= lang('Front.saved_explore') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($properties as $prop): ?>
                    <a href="<?= base_url('property/' . $prop->id) ?>" class="property-card bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col h-full group">
                        <div class="relative h-56 overflow-hidden">
                            <?php 
                                $imgPath = trim($prop->image_path ?? '');
                                $imgSrc = 'https://placehold.co/800x600/e2e8f0/8492a6?text=Property+Image';
                                if (!empty($imgPath)) {
                                    $imgSrc = (strpos($imgPath, 'http') === 0) ? esc($imgPath) : base_url(esc($imgPath));
                                }
                            ?>
                            <img alt="<?= esc($prop->title) ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x600/e2e8f0/8492a6?text=No+Image+Available';" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $imgSrc ?>">
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="bg-surface/90 backdrop-blur text-on-surface font-label-md text-[12px] px-2 py-1 rounded font-bold"><?= esc($prop->listing_type) ?></span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-primary font-label-md text-[12px] font-bold"><?= esc($prop->type_name) ?></span>
                                <span class="text-on-surface-variant font-caption text-[11px]"><?= lang('Front.saved_date') ?> <?= date('M d, Y', strtotime($prop->saved_at)) ?></span>
                            </div>
                            <h3 class="font-headline-md text-[18px] font-bold text-on-surface mb-1 line-clamp-1"><?= esc($prop->title) ?></h3>
                            <p class="text-on-surface-variant font-body-sm text-[14px] flex items-center gap-1 mb-4">
                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                <span class="line-clamp-1"><?= esc($prop->area_name) ?></span>
                            </p>
                            <p class="font-headline-md text-[20px] font-bold text-primary mb-4 mt-auto">Rp <?= number_format($prop->tax_price, 0, ',', '.') ?></p>
                            <div class="flex items-center gap-4 border-t border-outline-variant pt-4">
                                <div class="flex items-center gap-1 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[18px]">bed</span>
                                    <span class="font-label-md text-[14px]"><?= esc($prop->bed) ?></span>
                                </div>
                                <div class="flex items-center gap-1 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[18px]">shower</span>
                                    <span class="font-label-md text-[14px]"><?= esc($prop->bath) ?></span>
                                </div>
                                <div class="flex items-center gap-1 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[18px]">straighten</span>
                                    <span class="font-label-md text-[14px]"><?= esc($prop->usable_area) ?> m²</span>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-8 flex justify-center">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- SEARCHES TAB -->
    <div x-show="activeTab === 'searches'" x-transition.opacity style="display: none;">
        <?php if (empty($searches)): ?>
            <div class="flex flex-col items-center justify-center py-20 bg-surface border border-outline-variant rounded-xl shadow-sm">
                <span class="material-symbols-outlined text-[64px] text-outline-variant mb-4 opacity-50">search_off</span>
                <h2 class="font-headline-md text-xl font-bold text-on-surface mb-2">No Saved Searches</h2>
                <p class="text-on-surface-variant font-body-md text-center max-w-md mb-6">Save your filter preferences to get quick access to the homes you want.</p>
                <a href="<?= base_url('search') ?>" class="bg-primary text-on-primary px-6 py-3 rounded font-bold hover:bg-primary-container transition-colors">
                    Start Searching
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($searches as $search): 
                    $filters = json_decode($search->filters, true);
                    $queryStr = http_build_query($filters);
                ?>
                    <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-headline-md text-[18px] font-bold text-on-surface line-clamp-1"><?= esc($search->name) ?></h3>
                            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-full">saved_search</span>
                        </div>
                        <ul class="text-on-surface-variant font-body-sm text-[14px] flex flex-col gap-2 mb-6 flex-grow">
                            <?php if(!empty($filters['q'])): ?>
                                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">location_on</span> <?= esc($filters['q']) ?></li>
                            <?php endif; ?>
                            <?php if(!empty($filters['min_price']) || !empty($filters['max_price'])): ?>
                                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">payments</span> 
                                    <?= !empty($filters['min_price']) ? 'Rp ' . number_format($filters['min_price'], 0, ',', '.') : '0' ?> - 
                                    <?= !empty($filters['max_price']) ? 'Rp ' . number_format($filters['max_price'], 0, ',', '.') : 'Max' ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="flex gap-2 mt-auto border-t border-outline-variant pt-4">
                            <a href="<?= base_url('search?' . $queryStr) ?>" class="flex-1 bg-primary-container text-on-primary text-center py-2 rounded font-bold text-[14px] hover:bg-primary transition-colors">Apply Search</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?= $this->include('front/layout/footer') ?>