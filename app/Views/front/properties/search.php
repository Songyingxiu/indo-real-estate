<?= $this->include('front/layout/header') ?>

<div class="flex-1 flex overflow-hidden min-h-[calc(100vh-80px)]">
    
    <aside class="w-80 bg-surface border-r border-outline-variant flex-shrink-0 flex flex-col h-full overflow-y-auto custom-scrollbar">
        <form id="filterForm" action="<?= base_url('search') ?>" method="GET" class="p-6 flex flex-col gap-8 h-full">
            
            <input type="hidden" name="listing_type" id="filter_listing_type" value="<?= esc($listingType ?? 'Sale') ?>">

            <div>
                <h2 class="font-headline-lg-mobile text-[20px] font-bold mb-4 text-primary">Filters</h2>
                
                <div class="flex flex-col gap-2 mb-4">
                    <label class="font-label-md text-[14px] text-on-surface">Keyword / Location</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-outline text-[20px]">search</span>
                        <input name="q" value="<?= esc($keyword ?? '') ?>" class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded bg-surface-container-lowest focus:border-primary-container focus:ring-1 focus:ring-primary-fixed-dim outline-none transition-all font-body-md text-[14px] text-on-surface" placeholder="e.g. Pool, Canggu" type="text"/>
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant"/>

            <div>
                <h3 class="font-label-md text-[14px] text-on-surface mb-4">Property Type</h3>
                <div class="flex flex-col gap-3">
                    <?php 
                        $currentTypes = isset($_GET['type']) ? $_GET['type'] : [];
                        if (!is_array($currentTypes)) $currentTypes = [$currentTypes]; 
                    ?>
                    <?php if(!empty($propertyTypes)): foreach($propertyTypes as $pt): ?>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input name="type[]" value="<?= $pt->id ?>" <?= in_array($pt->id, $currentTypes) ? 'checked' : '' ?> class="rounded border-outline-variant text-primary-container focus:ring-primary-container" type="checkbox"/>
                            <span class="font-body-md text-[14px] text-on-surface-variant group-hover:text-primary transition-colors"><?= esc($pt->name) ?></span>
                        </label>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="mt-auto pt-8">
                <button type="submit" class="w-full bg-primary-container text-on-primary font-label-md text-[14px] font-bold py-3 rounded hover:bg-primary transition-colors">Apply Filters</button>
                <a href="<?= base_url('search') ?>" class="block text-center w-full mt-2 bg-transparent text-primary-container font-label-md text-[14px] py-3 rounded hover:bg-surface-container-low transition-colors">Clear All</a>
            </div>
        </form>
    </aside>

    <main class="flex-1 flex flex-col h-full bg-background relative overflow-hidden">
        
        <header class="bg-surface px-6 py-4 border-b border-outline-variant flex justify-between items-center z-10">
            <div>
                <h1 class="font-headline-lg-mobile text-[24px] font-bold text-on-background">Explore Properties</h1>
                <p class="font-body-md text-[14px] text-on-surface-variant mt-1">Showing <?= esc($total) ?> results</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex bg-surface-container-highest p-1 rounded">
                    <button onclick="document.getElementById('filter_listing_type').value='Sale'; document.getElementById('filterForm').submit();" class="px-4 py-1.5 rounded font-label-md text-[14px] <?= ($listingType ?? 'Sale') == 'Sale' ? 'bg-surface text-on-surface shadow-sm' : 'text-on-surface-variant hover:text-on-surface' ?> transition-all">Sale</button>
                    <button onclick="document.getElementById('filter_listing_type').value='Rent'; document.getElementById('filterForm').submit();" class="px-4 py-1.5 rounded font-label-md text-[14px] <?= ($listingType ?? '') == 'Rent' ? 'bg-surface text-on-surface shadow-sm' : 'text-on-surface-variant hover:text-on-surface' ?> transition-all">Rent</button>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col">
            <div class="p-6 flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if(!empty($properties)): foreach($properties as $property): ?>
                        <article class="bg-surface border border-outline-variant rounded-b-lg rounded-t-xl overflow-hidden hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-300 flex flex-col">
                            <a href="<?= base_url('property/' . $property->id) ?>" class="relative h-48 w-full bg-surface-container-highest block group">
                                <?php $imgSrc = !empty($property->image_path) ? base_url(esc($property->image_path)) : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'; ?>
                                <img alt="<?= esc($property->title) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $imgSrc ?>"/>
                                
                                <div class="absolute top-3 left-3 bg-tertiary-container text-on-tertiary font-label-md text-[12px] px-2 py-1 rounded flex items-center gap-1 shadow-sm">
                                    <span class="material-symbols-outlined text-[14px]">sell</span>
                                    <?= esc($property->listing_type) ?>
                                </div>
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <div class="flex justify-between items-start mb-2 gap-2">
                                    <h3 class="font-headline-lg-mobile text-[18px] leading-tight font-semibold text-on-surface line-clamp-2">
                                        <a href="<?= base_url('property/' . $property->id) ?>" class="hover:text-primary"><?= esc($property->title) ?></a>
                                    </h3>
                                </div>
                                <span class="font-headline-lg-mobile text-[20px] font-bold text-primary-container mb-2">Rp <?= number_format($property->tax_price, 0, ',', '.') ?></span>
                                
                                <p class="font-body-md text-[14px] text-on-surface-variant mb-4 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                    <?= esc($property->area_name ?? $property->address_line_1) ?>
                                </p>
                                
                                <div class="flex items-center gap-4 border-t border-outline-variant pt-4 mt-auto">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-[14px]">
                                            <?= strtoupper(substr($property->first_name ?? 'A', 0, 1)) ?>
                                        </div>
                                        <span class="font-label-md text-[14px] text-on-surface"><?= esc(($property->first_name ?? 'Agent') . ' ' . ($property->last_name ?? '')) ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; else: ?>
                        <div class="col-span-full py-12 text-center text-on-surface-variant flex flex-col items-center">
                            <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">search_off</span>
                            <p class="text-lg">No properties found matching your criteria.</p>
                            <a href="<?= base_url('search') ?>" class="text-primary hover:underline mt-2">Clear filters</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($pager)) : ?>
                <div class="mt-auto px-6 py-4 border-t border-outline-variant bg-surface">
                    <?= $pager->links('default', 'tailwind_pagination') ?>
                </div>
            <?php endif ?>
        </div>
    </main>
</div>

<?= $this->include('front/layout/footer') ?>