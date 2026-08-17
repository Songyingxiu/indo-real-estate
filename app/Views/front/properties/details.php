<?= $this->include('front/layout/header') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div id="photoGallery" class="fixed inset-0 z-[100] hidden bg-black/95 flex-col items-center justify-center p-4">
    <button onclick="closeGallery()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors p-2">
        <span class="material-symbols-outlined text-[36px]">close</span>
    </button>
    <div class="w-full max-w-5xl overflow-y-auto max-h-screen flex flex-col gap-6 custom-scrollbar p-4 md:p-8">
        <?php if(!empty($images)): foreach($images as $img): ?>
            <?php $imgSrc = (strpos(trim($img->image_path), 'http') === 0) ? esc($img->image_path) : base_url(esc($img->image_path)); ?>
            <img src="<?= $imgSrc ?>" onerror="this.onerror=null;this.src='https://placehold.co/1920x1080/e2e8f0/8492a6?text=No+Image+Available';" class="w-full h-auto rounded-lg shadow-2xl object-cover">
        <?php endforeach; else: ?>
            <img src="https://placehold.co/1920x1080/e2e8f0/8492a6?text=No+Image+Available" class="w-full h-auto rounded-lg shadow-2xl">
        <?php endif; ?>
    </div>
</div>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-8">
    <nav aria-label="Breadcrumb" class="mb-4 flex items-center text-on-surface-variant text-[12px] font-caption">
        <a class="hover:text-primary transition-colors" href="<?= base_url() ?>"><?= lang('Front.det_home') ?></a>
        <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
        <a class="hover:text-primary transition-colors" href="<?= base_url('search') ?>"><?= lang('Front.det_properties') ?></a>
        <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-on-surface font-medium"><?= esc($property->title) ?></span>
    </nav>

    <?php 
        $mainImg = !empty($images[0]) ? ((strpos(trim($images[0]->image_path), 'http') === 0) ? esc($images[0]->image_path) : base_url(esc($images[0]->image_path))) : 'https://placehold.co/1920x1080/e2e8f0/8492a6?text=Property+Image';
        $img1 = !empty($images[1]) ? ((strpos(trim($images[1]->image_path), 'http') === 0) ? esc($images[1]->image_path) : base_url(esc($images[1]->image_path))) : 'https://placehold.co/800x600/e2e8f0/8492a6?text=Property+Image';
        $img2 = !empty($images[2]) ? ((strpos(trim($images[2]->image_path), 'http') === 0) ? esc($images[2]->image_path) : base_url(esc($images[2]->image_path))) : 'https://placehold.co/800x600/e2e8f0/8492a6?text=Property+Image';
        
        $badgeClass = '';
        $dispStatus = '';
        if ($property->status === 'Sold') { $badgeClass = 'bg-error text-white'; $dispStatus = 'Sold'; }
        elseif ($property->status !== 'Active') { $badgeClass = 'bg-outline text-white'; $dispStatus = $property->status; }
        elseif ($property->approval_status !== 'Published') { $badgeClass = 'bg-tertiary text-white'; $dispStatus = 'Pending Approval'; }
    ?>

    <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-4 mb-8 rounded overflow-hidden h-[500px]">
        <div class="md:col-span-3 md:row-span-2 relative group overflow-hidden cursor-pointer" onclick="openGallery()">
            <img alt="Featured" src="<?= $mainImg ?>" onerror="this.onerror=null;this.src='https://placehold.co/1920x1080/e2e8f0/8492a6?text=No+Image+Available';" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            
            <?php if($dispStatus): ?>
                <div class="absolute top-4 right-4 z-20 flex flex-col items-end gap-2">
                    <div class="<?= $badgeClass ?> text-[14px] font-bold px-4 py-2 rounded shadow-lg uppercase tracking-widest border border-white/20">
                        <?= esc($dispStatus) ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="absolute bottom-4 left-4 bg-white/80 backdrop-blur-md px-4 py-2 rounded flex items-center gap-2 hover:bg-white transition-colors">
                <span class="material-symbols-outlined text-primary text-[20px] fill">photo_library</span>
                <span class="font-label-md text-[14px] text-primary font-bold"><?= lang('Front.det_view_all_photos') ?> (<?= count($images) ?>)</span>
            </div>
        </div>
        <div class="relative overflow-hidden group hidden md:block cursor-pointer" onclick="openGallery()">
            <img alt="Gallery 1" src="<?= $img1 ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x600/e2e8f0/8492a6?text=No+Image+Available';" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
        <div class="relative overflow-hidden group hidden md:block cursor-pointer" onclick="openGallery()">
            <img alt="Gallery 2" src="<?= $img2 ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x600/e2e8f0/8492a6?text=No+Image+Available';" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative">
        <div class="lg:col-span-8 flex flex-col gap-8">
            <section>
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="bg-secondary-fixed text-on-secondary-fixed-variant px-2 py-1 rounded text-[12px] font-bold"><?= esc($property->listing_type) ?></span>
                            
                            <?php if($dispStatus): ?>
                                <span class="<?= $badgeClass ?> px-2 py-1 rounded text-[12px] font-bold uppercase tracking-wider"><?= esc($dispStatus) ?></span>
                            <?php endif; ?>

                            <span class="text-on-surface-variant text-[12px] flex items-center"><span class="material-symbols-outlined text-[14px] mr-1">schedule</span> <?= esc($timeAgo) ?></span>
                        </div>
                        <div class="flex items-center gap-4">
                            <h1 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-surface mb-2"><?= esc($property->title) ?></h1>
                            <button onclick="toggleSaveProperty(<?= esc($property->id) ?>)" id="savePropertyBtn" class="flex items-center justify-center w-12 h-12 rounded-full border border-outline-variant hover:bg-surface-container transition-colors shadow-sm bg-surface shrink-0">
                                <span class="material-symbols-outlined <?= $isSaved ? 'text-error' : 'text-on-surface-variant' ?>" id="savePropertyIcon" style="<?= $isSaved ? 'font-variation-settings: \'FILL\' 1;' : '' ?>">
                                    favorite
                                </span>
                            </button>
                        </div>
                        <p class="flex items-center text-on-surface-variant font-body-md text-[16px]">
                            <span class="material-symbols-outlined mr-2">location_on</span>
                            <?= esc($property->area_name ?? $property->city_name ?? $property->address_line_1 ?? lang('Front.lbl_location_not_set')) ?><?= !empty($property->zipcode) ? ', ' . esc($property->zipcode) : '' ?>
                        </p>
                    </div>
                    <div class="text-left md:text-right shrink-0 mt-2 md:mt-0">
                        <p class="font-headline-lg text-[20px] md:text-[24px] font-bold text-primary whitespace-nowrap">Rp <?= number_format($property->tax_price, 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-6 py-4 border-t border-b border-outline-variant">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">bed</span>
                        </div>
                        <p class="font-label-md text-[14px] font-bold text-on-surface"><?= esc($property->bed) ?> <?= lang('Front.det_bedrooms') ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">shower</span>
                        </div>
                        <p class="font-label-md text-[14px] font-bold text-on-surface"><?= esc($property->bath) ?> <?= lang('Front.det_bathrooms') ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">straighten</span>
                        </div>
                        <p class="font-label-md text-[14px] font-bold text-on-surface"><?= esc($property->total_area ?? $property->total_land_area ?? 0) ?> <?= lang('Front.det_sqm') ?></p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_about') ?></h2>
                <div class="prose prose-sm md:prose-base max-w-none text-on-surface-variant font-body-md text-[16px]">
                    <p><?= nl2br(esc($property->description)) ?></p>
                </div>
            </section>

            <section>
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_specs') ?></h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 bg-surface-container-lowest border border-outline-variant rounded-lg p-6">
                    <?php if(!empty($property->building_society_name)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_complex') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->building_society_name) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->unit_number)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_unit_num') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->unit_number) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->year_built)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_year_built') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->year_built) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->total_floors)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_total_floors') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->total_floors) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->usable_area)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_usable_area') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->usable_area) ?> m&sup2;</span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->total_land_area)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_land_area') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->total_land_area) ?> m&sup2;</span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->parking)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_parking') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface">
                            <?= esc($property->parking) ?> 
                            <?= (!empty($property->total_parking) && $property->total_parking > 0) ? '(' . esc($property->total_parking) . ' ' . lang('Front.det_spots') . ')' : '' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->basement)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_basement') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->basement) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property->water_facility)): ?>
                    <div>
                        <span class="block text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_water') ?></span>
                        <span class="block text-[14px] font-bold text-on-surface"><?= esc($property->water_facility) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </section>

            <?php if(!empty($propertyFeatures)): ?>
            <section class="mt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_features') ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-surface-container-lowest border border-outline-variant rounded-lg p-6">
                    <?php foreach($propertyFeatures as $category => $features): ?>
                        <div>
                            <h3 class="font-bold text-on-surface flex items-center gap-2 mb-3 border-b border-outline-variant pb-2">
                                <span class="material-symbols-outlined text-[18px] text-primary">check_circle</span>
                                <?= esc($category) ?>
                            </h3>
                            <ul class="space-y-2">
                                <?php foreach($features as $feature): ?>
                                    <li class="text-[14px] text-on-surface-variant flex items-start gap-2">
                                        <span class="material-symbols-outlined text-[16px] mt-0.5 text-outline">done</span>
                                        <?= esc($feature) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <section>
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_location') ?></h2>
                <div class="border border-outline-variant rounded overflow-hidden h-[400px] relative bg-surface-container-low z-0">
                    <div id="propertyMap" class="w-full h-full"></div>
                </div>
            </section>

            <?php if(!empty($detailAds[0])): ?>
            <div class="w-full h-[90px] md:h-[120px] my-6 rounded overflow-hidden shadow-sm relative group cursor-pointer border border-outline-variant">
                <a href="<?= esc($detailAds[0]->target_url ?? '#') ?>" target="_blank" class="block w-full h-full">
                    <?php 
                        $midAdImg = $detailAds[0]->image_path ?? $detailAds[0]->image ?? '';
                        $adSrc = (strpos(trim($midAdImg), 'http') === 0) ? esc($midAdImg) : base_url('uploads/ads/' . esc($midAdImg));
                    ?>
                    <img src="<?= $adSrc ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x200/e2e8f0/8492a6?text=Advertisement';" class="w-full h-full object-cover">
                    <span class="absolute top-2 right-2 bg-black/50 text-white text-[10px] px-1.5 py-0.5 rounded backdrop-blur-sm">Advertisement</span>
                </a>
            </div>
            <?php endif; ?>

            <?php if(!empty($nearbyPOIs)): ?>
            <section class="mt-4">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_nearby_loc') ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach($nearbyPOIs as $poi): ?>
                        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded flex items-center gap-4">
                            <div class="bg-primary/10 p-2 rounded-full text-primary">
                                <span class="material-symbols-outlined">
                                    <?= match(strtolower($poi->category)) {
                                        'school', 'college', 'university' => 'school',
                                        'hospital', 'clinic'              => 'local_hospital',
                                        'mall', 'shopping center'         => 'shopping_bag',
                                        'supermarket', 'grocery'          => 'storefront',
                                        'park', 'garden', 'playground'    => 'park',
                                        'train station', 'transit'        => 'train',
                                        default                           => 'place',
                                    } ?>
                                </span>
                            </div>
                            <div>
                                <p class="font-bold text-[14px] text-on-surface"><?= esc($poi->name) ?></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded font-bold uppercase"><?= esc($poi->category) ?></span>
                                    <span class="text-[12px] text-on-surface-variant font-medium"><?= number_format($poi->distance, 2) ?> <?= lang('Front.det_km_away') ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <?php if(!empty($nearbyProperties)): ?>
            <section class="mt-8 border-t border-outline-variant pt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_nearby_prop') ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach(array_slice($nearbyProperties, 0, 4) as $np): $np = (object)$np; ?>
                        <a href="<?= base_url('property/' . $np->id) ?>" class="flex items-center gap-4 p-3 bg-surface border border-outline-variant rounded hover:shadow-md transition-shadow group">
                            <div class="w-20 h-20 bg-surface-container-high rounded overflow-hidden flex-shrink-0">
                                <?php 
                                    $rawNp = trim($np->image_path ?? $np->image ?? '');
                                    $npImg = !empty($rawNp) ? ((strpos($rawNp, 'http') === 0) ? esc($rawNp) : base_url(esc($rawNp))) : 'https://placehold.co/200x200/e2e8f0/8492a6?text=Property'; 
                                ?>
                                <img src="<?= $npImg ?>" onerror="this.onerror=null;this.src='https://placehold.co/200x200/e2e8f0/8492a6?text=No+Image';" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[14px] text-on-surface line-clamp-1 group-hover:text-primary"><?= esc($np->title) ?></span>
                                <span class="font-bold text-[14px] text-primary-container">Rp <?= number_format($np->tax_price, 0, ',', '.') ?></span>
                                <span class="text-[12px] text-on-surface-variant mt-1"><?= number_format($np->distance ?? 0, 1) ?> <?= lang('Front.det_km_away') ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <?php if(!empty($similarType)): ?>
            <section class="mt-8 border-t border-outline-variant pt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_similar_type') ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach(array_slice($similarType, 0, 4) as $st): $st = (object)$st; ?>
                        <a href="<?= base_url('property/' . $st->id) ?>" class="flex items-center gap-4 p-3 bg-surface border border-outline-variant rounded hover:shadow-md transition-shadow group">
                            <div class="w-20 h-20 bg-surface-container-high rounded overflow-hidden flex-shrink-0">
                                <?php 
                                    $rawSt = trim($st->image_path ?? $st->image ?? '');
                                    $stImg = !empty($rawSt) ? ((strpos($rawSt, 'http') === 0) ? esc($rawSt) : base_url(esc($rawSt))) : 'https://placehold.co/200x200/e2e8f0/8492a6?text=Property'; 
                                ?>
                                <img src="<?= $stImg ?>" onerror="this.onerror=null;this.src='https://placehold.co/200x200/e2e8f0/8492a6?text=No+Image';" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[14px] text-on-surface line-clamp-1 group-hover:text-primary"><?= esc($st->title) ?></span>
                                <span class="font-bold text-[14px] text-primary-container">Rp <?= number_format($st->tax_price, 0, ',', '.') ?></span>
                                <span class="text-[12px] text-on-surface-variant mt-1"><?= esc($st->bed ?? 0) ?> <?= lang('Front.lbl_beds') ?> • <?= esc($st->bath ?? 0) ?> <?= lang('Front.lbl_baths') ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <?php if(!empty($similarPrice)): ?>
            <section class="mt-8 border-t border-outline-variant pt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4"><?= lang('Front.det_same_price') ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach(array_slice($similarPrice, 0, 4) as $sp): $sp = (object)$sp; ?>
                        <a href="<?= base_url('property/' . $sp->id) ?>" class="flex items-center gap-4 p-3 bg-surface border border-outline-variant rounded hover:shadow-md transition-shadow group">
                            <div class="w-20 h-20 bg-surface-container-high rounded overflow-hidden flex-shrink-0">
                                <?php 
                                    $rawSp = trim($sp->image_path ?? $sp->image ?? '');
                                    $spImg = !empty($rawSp) ? ((strpos($rawSp, 'http') === 0) ? esc($rawSp) : base_url(esc($rawSp))) : 'https://placehold.co/200x200/e2e8f0/8492a6?text=Property'; 
                                ?>
                                <img src="<?= $spImg ?>" onerror="this.onerror=null;this.src='https://placehold.co/200x200/e2e8f0/8492a6?text=No+Image';" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[14px] text-on-surface line-clamp-1 group-hover:text-primary"><?= esc($sp->title) ?></span>
                                <span class="font-bold text-[14px] text-primary-container">Rp <?= number_format($sp->tax_price, 0, ',', '.') ?></span>
                                <span class="text-[12px] text-on-surface-variant mt-1 line-clamp-1"><?= esc($sp->area_name ?? $sp->city_name ?? $sp->address_line_1 ?? 'Location') ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div>

        <div class="lg:col-span-4">
            <div class="sticky top-28 flex flex-col gap-6">
                
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm" x-data="mortgageCalculator()">
                    <h3 class="font-headline-md text-[18px] font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calculate</span>
                        <?= lang('Front.det_est_payment') ?>
                    </h3>
                    
                    <div class="mb-6 text-center">
                        <span class="text-[12px] text-on-surface-variant font-medium"><?= lang('Front.det_monthly_total') ?></span>
                        <div class="font-headline-lg text-[28px] font-bold text-primary" x-text="'Rp ' + formatRupiah(monthlyPayment)"></div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[12px] font-bold text-on-surface-variant mb-1"><?= lang('Front.det_home_price') ?></label>
                            <input type="text" x-model="homePrice" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded outline-none" readonly>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[12px] font-bold text-on-surface-variant mb-1"><?= lang('Front.det_dp_percent') ?></label>
                                <input type="number" x-model="dpPercent" @input="calculate()" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-on-surface-variant mb-1"><?= lang('Front.det_interest_rate') ?></label>
                                <input type="number" step="0.1" x-model="interestRate" @input="calculate()" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded outline-none focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-on-surface-variant mb-1"><?= lang('Front.det_loan_term') ?></label>
                            <select x-model="loanTerm" @change="calculate()" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded outline-none cursor-pointer focus:border-primary">
                                <option value="10"><?= lang('Front.det_10_years') ?></option>
                                <option value="15"><?= lang('Front.det_15_years') ?></option>
                                <option value="20"><?= lang('Front.det_20_years') ?></option>
                                <option value="30"><?= lang('Front.det_30_years') ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-outline-variant">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant uppercase font-bold text-xl">
                                <?= substr($property->first_name, 0, 1) . substr($property->last_name ?? '', 0, 1) ?>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-label-md text-[16px] text-on-surface font-bold"><?= esc($property->first_name . ' ' . $property->last_name) ?></h3>
                            <span class="inline-flex items-center gap-1 bg-surface-container-low px-2 py-0.5 mt-1 rounded text-[12px] text-primary border border-outline-variant/50">
                                <span class="material-symbols-outlined text-[12px]">badge</span> <?= lang('Front.det_verified') ?>
                            </span>

                            <?php if(isset($allowDirectEmail) && $allowDirectEmail): ?>
                                <div class="mt-2 space-y-1">
                                    <p class="text-[13px] text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">call</span> <?= esc($property->phone_number) ?>
                                    </p>
                                    <p class="text-[13px] text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">mail</span> <?= esc($property->email) ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p class="text-[12px] text-on-surface-variant mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">lock</span> Contact details hidden
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(session()->get('id')): ?>
                        <form id="inquiryForm" onsubmit="submitInquiry(event, this)" novalidate class="flex flex-col gap-3">
                            <input type="hidden" name="property_id" value="<?= esc($property->id) ?>">
                            <input type="hidden" name="agent_id" value="<?= esc($property->owner_id) ?>">
                            
                            <div id="inquiry-global-error" class="hidden bg-[#c9302c] text-white p-3 font-bold items-center gap-2 rounded shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                            </div>

                            <!-- Preset Auto-Update Source -->
                            <select id="inquirySource" name="source" required onchange="updatePresetMessage()" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none cursor-pointer mb-1">
                                <option value="Contact Form"><?= lang('Front.det_ask_question') ?></option>
                                <option value="Schedule Visit"><?= lang('Front.det_schedule_visit') ?></option>
                            </select>

                            <div>
                                <input id="inquiry-name" name="name" required oninput="clearError('name')" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none" placeholder="<?= lang('Front.det_full_name') ?>" type="text" value="<?= esc(session()->get('first_name') ? session()->get('first_name').' '.session()->get('last_name') : '') ?>">
                                <div id="error-name" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                    <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                                </div>
                            </div>
                            
                            <div>
                                <input id="inquiry-phone" name="phone" required oninput="clearError('phone')" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none" placeholder="<?= lang('Front.det_phone_num') ?>" type="tel" value="">
                                <div id="error-phone" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                    <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                                </div>
                            </div>
                            
                            <div>
                                <input id="inquiry-email" name="email" required oninput="clearError('email')" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none" placeholder="<?= lang('Front.det_email_addr') ?>" type="email" value="<?= esc(session()->get('email')) ?>">
                                <div id="error-email" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                    <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                                </div>
                            </div>
                            
                            <div>
                                <textarea name="message" id="inquiryMessage" required oninput="clearError('message')" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white resize-none outline-none" rows="4"></textarea>
                                <div id="error-message" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                    <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                                </div>
                            </div>
                            
                            <button type="submit" id="submitBtn" class="w-full bg-primary-container text-white py-3 rounded font-bold text-[14px] hover:bg-primary transition-colors mt-2 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">send</span> <?= lang('Front.det_send_msg') ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <span class="material-symbols-outlined text-[48px] text-outline-variant mb-2 opacity-50">lock</span>
                            <p class="font-body-md text-[14px] text-on-surface-variant mb-6"><?= lang('Front.det_lock_notice') ?></p>
                            <button type="button" onclick="openAuthModal()" class="w-full bg-primary text-on-primary py-3 rounded font-bold text-[14px] hover:bg-primary-container transition-colors shadow-sm">
                                Sign in to Contact Agent
                            </button>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</main>

<div x-data="{ show: false }" @show-inquiry-success.window="show = true" x-show="show" style="display:none;" class="fixed inset-0 z-[600] flex items-center justify-center p-4 bg-[#1a1c1e]/80 backdrop-blur-sm">
    <div @click.outside="show = false" class="bg-surface rounded-2xl shadow-2xl border border-outline-variant max-w-md w-full p-8 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-[#d3e3fd] text-primary flex items-center justify-center mb-4 shadow-inner">
            <span class="material-symbols-outlined text-[32px]">mark_email_read</span>
        </div>
        <h2 class="font-headline-lg text-2xl font-bold text-on-surface mb-2">Message Sent!</h2>
        <p class="text-on-surface-variant text-[15px] mb-6 leading-relaxed">
            Your inquiry has been successfully delivered. The agent will reply shortly. Please check your inbox to view replies and continue the conversation.
        </p>
        <div class="flex gap-3 w-full">
            <button @click="show = false" class="flex-1 py-3 px-4 border border-outline-variant text-on-surface-variant rounded-lg font-bold hover:bg-surface-container transition-colors">Close</button>
            <a href="<?= base_url('user/inbox') ?>" class="flex-1 py-3 px-4 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container transition-colors shadow-md">Go to Inbox</a>
        </div>
    </div>
</div>

<?= $this->include('components/login_modal') ?>
<?= $this->include('front/layout/footer') ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var lat = <?= esc($property->latitude ?? -6.200000) ?>;
        var lng = <?= esc($property->longitude ?? 106.816666) ?>;
        
        var map = L.map('propertyMap').setView([lat, lng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '&copy; OpenStreetMap' 
        }).addTo(map);

        var homeIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker([lat, lng], {icon: homeIcon})
            .addTo(map)
            .bindPopup('<b><?= esc(addslashes($property->title)) ?></b><br>Property Location')
            .openPopup();

        <?php if(!empty($nearbyPOIs)): ?>
            <?php foreach($nearbyPOIs as $poi): ?>
                var poiLat = <?= esc($poi->latitude) ?>;
                var poiLng = <?= esc($poi->longitude) ?>;
                
                L.marker([poiLat, poiLng])
                    .addTo(map)
                    .bindPopup(
                        '<b><?= esc(addslashes($poi->name)) ?></b><br>' + 
                        '<?= esc($poi->category) ?> - <?= number_format($poi->distance, 2) ?> km away'
                    );
            <?php endforeach; ?>
        <?php endif; ?>
        
        updatePresetMessage();
    });

    function updatePresetMessage() {
        const source = document.getElementById('inquirySource')?.value;
        const msgEl = document.getElementById('inquiryMessage');
        if (!msgEl) return;
        
        if (source === 'Schedule Visit') {
            msgEl.value = "Hello, I am interested in <?= esc($property->title) ?> and would like to schedule a visit. Please let me know your availability.";
        } else {
            msgEl.value = "Hello, I have a question regarding <?= esc($property->title) ?>. Could you please provide me with more details?";
        }
    }

    function clearError(fieldName) {
        const input = document.getElementById('inquiry-' + fieldName) || document.getElementById('inquiryMessage');
        const errorDiv = document.getElementById('error-' + fieldName);
        if (input) {
            input.classList.remove('border-[#c9302c]', 'focus:border-[#c9302c]', 'focus:ring-[#c9302c]', 'bg-[#fff8f8]');
            input.classList.add('border-outline-variant', 'focus:border-primary', 'bg-white');
        }
        if (errorDiv) {
            errorDiv.classList.add('hidden');
            errorDiv.classList.remove('flex');
        }
        const activeErrors = document.querySelectorAll('[id^="error-"]:not(.hidden)');
        if (activeErrors.length === 0) {
            document.getElementById('inquiry-global-error').classList.add('hidden');
        }
    }

    function openGallery() {
        document.body.style.overflow = 'hidden';
        document.getElementById('photoGallery').classList.remove('hidden');
        document.getElementById('photoGallery').classList.add('flex');
    }
    function closeGallery() {
        document.body.style.overflow = 'auto';
        document.getElementById('photoGallery').classList.add('hidden');
        document.getElementById('photoGallery').classList.remove('flex');
    }

    function toggleSaveProperty(propertyId) {
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
                window.pendingAuthAction = function() { toggleSaveProperty(propertyId); };
                if(typeof openAuthModal === 'function') openAuthModal();
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const icon = document.getElementById('savePropertyIcon');
                if (data.action === 'added') {
                    icon.classList.remove('text-on-surface-variant');
                    icon.classList.add('text-error');
                    icon.style.fontVariationSettings = "'FILL' 1";
                } else {
                    icon.classList.remove('text-error');
                    icon.classList.add('text-on-surface-variant');
                    icon.style.fontVariationSettings = "'FILL' 0";
                }
            } else {
                alert(data.message || 'An error occurred.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function submitInquiry(e, formElement) {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Sending...';
        btn.disabled = true;
        
        const globalBanner = document.getElementById('inquiry-global-error');
        if (globalBanner) globalBanner.classList.add('hidden');

        const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
        const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');

        const formData = new FormData(formElement);
        formData.append(csrfName, csrfHash);

        fetch('<?= base_url('property/submit-inquiry') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => {
            if (response.status === 401) {
                window.pendingAuthAction = function() { submitInquiry({preventDefault:()=>{}}, formElement); };
                if(typeof openAuthModal === 'function') openAuthModal();
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if (data.status === 'validation_error') {
                if (globalBanner) {
                    globalBanner.classList.remove('hidden');
                    globalBanner.classList.add('flex');
                }
                for (const [field, message] of Object.entries(data.errors)) {
                    const input = document.getElementById('inquiry-' + field) || (field === 'message' ? document.getElementById('inquiryMessage') : null);
                    const errorDiv = document.getElementById('error-' + field);
                    
                    if (input) {
                        input.classList.remove('border-outline-variant', 'focus:border-primary', 'bg-white');
                        input.classList.add('border-[#c9302c]', 'focus:border-[#c9302c]', 'focus:ring-[#c9302c]', 'bg-[#fff8f8]');
                    }
                    if (errorDiv) {
                        errorDiv.querySelector('.error-text').textContent = message;
                        errorDiv.classList.remove('hidden');
                        errorDiv.classList.add('flex');
                    }
                }
            } else if (data.status === 'success' || data.message === 'Inquiry submitted successfully.') {
                window.dispatchEvent(new CustomEvent('show-inquiry-success'));
                formElement.reset();
                updatePresetMessage(); 
            } else {
                alert(data.message || 'Error sending inquiry. Please try again.');
            }
        })
        .catch(error => {
            if(error.message !== 'Unauthorized') {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }
</script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mortgageCalculator', () => ({
            homePriceRaw: <?= esc($property->tax_price ?? 0) ?>,
            homePrice: '<?= number_format($property->tax_price ?? 0, 0, ',', '.') ?>',
            dpPercent: 20,
            interestRate: 6.5,
            loanTerm: 20,
            monthlyPayment: 0,
            
            init() {
                this.calculate();
            },
            
            calculate() {
                let p = this.homePriceRaw - (this.homePriceRaw * (this.dpPercent / 100)); 
                let r = (this.interestRate / 100) / 12; 
                let n = this.loanTerm * 12; 
                
                if (r === 0) {
                    this.monthlyPayment = p / n;
                } else {
                    this.monthlyPayment = p * (r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
                }
            },
            
            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(number);
            }
        }))
    });
</script>