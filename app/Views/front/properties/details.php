<?= $this->include('front/layout/header') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- CSRF Token for AJAX requests -->
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div id="photoGallery" class="fixed inset-0 z-[100] hidden bg-black/95 flex-col items-center justify-center p-4">
    <button onclick="closeGallery()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors p-2">
        <span class="material-symbols-outlined text-[36px]">close</span>
    </button>
    <div class="w-full max-w-5xl overflow-y-auto max-h-screen flex flex-col gap-6 custom-scrollbar p-4 md:p-8">
        <?php if(!empty($images)): foreach($images as $img): ?>
            <!-- Wrap with base_url() -->
            <img src="<?= base_url(esc($img->image_path)) ?>" class="w-full h-auto rounded-lg shadow-2xl object-cover">
        <?php endforeach; else: ?>
            <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1920&q=80" class="w-full h-auto rounded-lg shadow-2xl">
        <?php endif; ?>
    </div>
</div>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-8">
    <nav aria-label="Breadcrumb" class="mb-4 flex items-center text-on-surface-variant text-[12px] font-caption">
        <a class="hover:text-primary transition-colors" href="<?= base_url() ?>">Home</a>
        <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
        <a class="hover:text-primary transition-colors" href="<?= base_url('search') ?>">Properties</a>
        <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-on-surface font-medium"><?= esc($property->title) ?></span>
    </nav>

    <?php 
        // Wrap with base_url()
        $mainImg = !empty($images[0]) ? base_url(esc($images[0]->image_path)) : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1920&q=80';
        $img1 = !empty($images[1]) ? base_url(esc($images[1]->image_path)) : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
        $img2 = !empty($images[2]) ? base_url(esc($images[2]->image_path)) : 'https://images.unsplash.com/photo-1600607687920-4e20d33c01f6?auto=format&fit=crop&w=800&q=80';
    ?>

    <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-4 mb-8 rounded overflow-hidden h-[500px]">
        <div class="md:col-span-3 md:row-span-2 relative group overflow-hidden cursor-pointer" onclick="openGallery()">
            <img alt="Featured" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $mainImg ?>">
            <div class="absolute bottom-4 left-4 bg-white/80 backdrop-blur-md px-4 py-2 rounded flex items-center gap-2 hover:bg-white transition-colors">
                <span class="material-symbols-outlined text-primary text-[20px] fill">photo_library</span>
                <span class="font-label-md text-[14px] text-primary font-bold">View All Photos (<?= count($images) ?>)</span>
            </div>
        </div>
        <div class="relative overflow-hidden group hidden md:block cursor-pointer" onclick="openGallery()">
            <img alt="Gallery 1" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $img1 ?>">
        </div>
        <div class="relative overflow-hidden group hidden md:block cursor-pointer" onclick="openGallery()">
            <img alt="Gallery 2" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $img2 ?>">
            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative">
        <div class="lg:col-span-8 flex flex-col gap-8">
            <section>
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-secondary-fixed text-on-secondary-fixed-variant px-2 py-1 rounded text-[12px] font-bold"><?= esc($property->listing_type) ?></span>
                            <span class="text-on-surface-variant text-[12px] flex items-center"><span class="material-symbols-outlined text-[14px] mr-1">schedule</span> Just Listed</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <h1 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-surface mb-2"><?= esc($property->title) ?></h1>
                            <!-- SAVE BUTTON -->
                            <button onclick="toggleSaveProperty(<?= esc($property->id) ?>)" id="savePropertyBtn" class="flex items-center justify-center w-12 h-12 rounded-full border border-outline-variant hover:bg-surface-container transition-colors shadow-sm bg-surface">
                                <span class="material-symbols-outlined <?= $isSaved ? 'text-error' : 'text-on-surface-variant' ?>" id="savePropertyIcon" style="<?= $isSaved ? 'font-variation-settings: \'FILL\' 1;' : '' ?>">
                                    favorite
                                </span>
                            </button>
                        </div>
                        <p class="flex items-center text-on-surface-variant font-body-md text-[16px]">
                            <span class="material-symbols-outlined mr-2">location_on</span>
                            <?= esc($property->address_line_1 ?? $property->area_name) ?>
                        </p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="font-headline-lg text-[28px] md:text-[32px] font-bold text-primary">Rp <?= number_format($property->tax_price, 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-6 py-4 border-t border-b border-outline-variant">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">bed</span>
                        </div>
                        <p class="font-label-md text-[14px] font-bold text-on-surface"><?= esc($property->bed) ?> Bedrooms</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">shower</span>
                        </div>
                        <p class="font-label-md text-[14px] font-bold text-on-surface"><?= esc($property->bath) ?> Bathrooms</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">straighten</span>
                        </div>
                        <p class="font-label-md text-[14px] font-bold text-on-surface"><?= esc($property->total_area) ?> Sqm</p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4">About This Property</h2>
                <div class="prose prose-sm md:prose-base max-w-none text-on-surface-variant font-body-md text-[16px]">
                    <p><?= nl2br(esc($property->description)) ?></p>
                </div>
            </section>

            <section>
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4">Location</h2>
                <div class="border border-outline-variant rounded overflow-hidden h-[400px] relative bg-surface-container-low z-0">
                    <div id="propertyMap" class="w-full h-full"></div>
                </div>
            </section>

            <!-- PHASE 2: PROPERTY FEATURES MATRIX -->
            <?php if(!empty($propertyFeatures)): ?>
            <section class="mt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4">Property Details</h2>
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

            <!-- PHASE 2: MIDDLE AD PLACEMENT -->
            <?php if(!empty($detailAds[0])): ?>
            <div class="w-full h-[90px] md:h-[120px] my-6 rounded overflow-hidden shadow-sm relative group cursor-pointer border border-outline-variant">
                <a href="<?= esc($detailAds[0]->target_url) ?>" target="_blank" class="block w-full h-full">
                    <img src="<?= base_url('uploads/ads/' . $detailAds[0]->image) ?>" class="w-full h-full object-cover">
                    <span class="absolute top-2 right-2 bg-black/50 text-white text-[10px] px-1.5 py-0.5 rounded backdrop-blur-sm">Advertisement</span>
                </a>
            </div>
            <?php endif; ?>

            <!-- NEARBY LOCATIONS (POIs) -->
            <?php if(!empty($nearbyPOIs)): ?>
            <section class="mt-4">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4">Nearby Locations</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach($nearbyPOIs as $poi): ?>
                        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded flex items-center gap-4">
                            <div class="bg-primary/10 p-2 rounded-full text-primary">
                                <span class="material-symbols-outlined">
                                    <?= strtolower($poi->category) == 'school' || strtolower($poi->category) == 'college' ? 'school' : 
                                       (strtolower($poi->category) == 'hospital' ? 'local_hospital' : 
                                       (strtolower($poi->category) == 'mall' ? 'shopping_bag' : 'place')) ?>
                                </span>
                            </div>
                            <div>
                                <p class="font-bold text-[14px] text-on-surface"><?= esc($poi->name) ?></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded font-bold uppercase"><?= esc($poi->category) ?></span>
                                    <span class="text-[12px] text-on-surface-variant font-medium"><?= number_format($poi->distance, 2) ?> km away</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- NEARBY PROPERTIES -->
            <?php if(!empty($nearbyProperties)): ?>
            <section class="mt-8 border-t border-outline-variant pt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4">Nearby Properties</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach(array_slice($nearbyProperties, 0, 4) as $np): ?>
                        <a href="<?= base_url('property/' . $np['id']) ?>" class="flex items-center gap-4 p-3 bg-surface border border-outline-variant rounded hover:shadow-md transition-shadow group">
                            <div class="w-20 h-20 bg-surface-container-high rounded overflow-hidden flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[14px] text-on-surface line-clamp-1 group-hover:text-primary"><?= esc($np['title']) ?></span>
                                <span class="font-bold text-[14px] text-primary-container">Rp <?= number_format($np['tax_price'], 0, ',', '.') ?></span>
                                <span class="text-[12px] text-on-surface-variant mt-1"><?= number_format($np['distance'], 1) ?> km away</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- SIMILAR TYPE DATA -->
            <?php if(!empty($similarType)): ?>
            <section class="mt-8 border-t border-outline-variant pt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4">Similar Property Types</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach(array_slice($similarType, 0, 4) as $st): ?>
                        <a href="<?= base_url('property/' . $st['id']) ?>" class="flex items-center gap-4 p-3 bg-surface border border-outline-variant rounded hover:shadow-md transition-shadow group">
                            <div class="w-20 h-20 bg-surface-container-high rounded overflow-hidden flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[14px] text-on-surface line-clamp-1 group-hover:text-primary"><?= esc($st['title']) ?></span>
                                <span class="font-bold text-[14px] text-primary-container">Rp <?= number_format($st['tax_price'], 0, ',', '.') ?></span>
                                <span class="text-[12px] text-on-surface-variant mt-1"><?= esc($st['bed']) ?> Bed • <?= esc($st['bath']) ?> Bath</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- SAME PRICE RANGE -->
            <?php if(!empty($similarPrice)): ?>
            <section class="mt-8 border-t border-outline-variant pt-8">
                <h2 class="font-brand-text text-[24px] font-bold text-on-surface mb-4">In The Same Price Range</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach(array_slice($similarPrice, 0, 4) as $sp): ?>
                        <a href="<?= base_url('property/' . $sp['id']) ?>" class="flex items-center gap-4 p-3 bg-surface border border-outline-variant rounded hover:shadow-md transition-shadow group">
                            <div class="w-20 h-20 bg-surface-container-high rounded overflow-hidden flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1600607687920-4e20d33c01f6?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[14px] text-on-surface line-clamp-1 group-hover:text-primary"><?= esc($sp['title']) ?></span>
                                <span class="font-bold text-[14px] text-primary-container">Rp <?= number_format($sp['tax_price'], 0, ',', '.') ?></span>
                                <span class="text-[12px] text-on-surface-variant mt-1 line-clamp-1"><?= esc($sp['area_name']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div>

        <div class="lg:col-span-4">
            <div class="sticky top-28 flex flex-col gap-6">
                
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded-xl border border-[#a8c7fa] flex items-start gap-2 shadow-sm">
                        <span class="material-symbols-outlined mt-0.5">check_circle</span>
                        <p class="font-label-md text-[14px] leading-relaxed"><?= session()->getFlashdata('success') ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="bg-[#ffdad6] text-[#410002] p-4 rounded-xl border border-error/30 flex items-start gap-2 shadow-sm">
                        <span class="material-symbols-outlined mt-0.5">warning</span>
                        <p class="font-label-md text-[14px] leading-relaxed"><?= session()->getFlashdata('error') ?></p>
                    </div>
                <?php endif; ?>

                <!-- PHASE 2: MORTGAGE CALCULATOR WIDGET -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm" x-data="mortgageCalculator()">
                    <h3 class="font-headline-md text-[18px] font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calculate</span>
                        Estimated Payment
                    </h3>
                    
                    <div class="mb-6 text-center">
                        <span class="text-[12px] text-on-surface-variant font-medium">Monthly Total</span>
                        <div class="font-headline-lg text-[28px] font-bold text-primary" x-text="'Rp ' + formatRupiah(monthlyPayment)"></div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Home Price (Rp)</label>
                            <input type="text" x-model="homePrice" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded outline-none" readonly>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Down Payment (%)</label>
                                <input type="number" x-model="dpPercent" @input="calculate()" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Interest Rate (%)</label>
                                <input type="number" step="0.1" x-model="interestRate" @input="calculate()" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded outline-none focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Loan Term (Years)</label>
                            <select x-model="loanTerm" @change="calculate()" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded outline-none cursor-pointer focus:border-primary">
                                <option value="10">10 Years</option>
                                <option value="15">15 Years</option>
                                <option value="20">20 Years</option>
                                <option value="30">30 Years</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- AGENT CONTACT FORM -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-outline-variant">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant uppercase font-bold text-xl">
                                <?= substr($property->first_name, 0, 1) . substr($property->last_name ?? '', 0, 1) ?>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-label-md text-[16px] text-on-surface font-bold"><?= esc($property->first_name . ' ' . $property->last_name) ?></h3>
                            <p class="text-[12px] text-on-surface-variant mb-1">Premium Property Agent</p>
                            <span class="inline-flex items-center gap-1 bg-surface-container-low px-2 py-0.5 rounded text-[12px] text-primary border border-outline-variant/50">
                                <span class="material-symbols-outlined text-[12px]">badge</span> Verified
                            </span>
                        </div>
                    </div>

                    <?php if(session()->get('id')): ?>
                        <form action="<?= base_url('contact/submit-lead') ?>" method="POST" class="flex flex-col gap-3">
                            <input type="hidden" name="property_id" value="<?= esc($property->id) ?>">
                            <input type="hidden" name="agent_id" value="<?= esc($property->owner_id) ?>">
                            
                            <select name="source" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none cursor-pointer mb-1">
                                <option value="Contact Form" <?= old('source') == 'Contact Form' ? 'selected' : '' ?>>I want to ask a question</option>
                                <option value="Schedule Visit" <?= old('source') == 'Schedule Visit' ? 'selected' : '' ?>>I want to schedule a visit</option>
                            </select>

                            <input name="name" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none" placeholder="Full Name" type="text" value="<?= esc(old('name') ?? (session()->get('first_name') . ' ' . session()->get('last_name'))) ?>">
                            <input name="phone" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none" placeholder="Phone Number" type="tel" value="<?= esc(old('phone')) ?>">
                            <input name="email" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none" placeholder="Email Address" type="email" value="<?= esc(old('email') ?? session()->get('email')) ?>">
                            <textarea name="message" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white resize-none outline-none" placeholder="I am interested in <?= esc($property->title) ?>..." rows="3"><?= esc(old('message')) ?></textarea>
                            
                            <button type="submit" class="w-full bg-primary-container text-white py-3 rounded font-bold text-[14px] hover:bg-primary transition-colors mt-2 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">send</span> Send Message
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <span class="material-symbols-outlined text-[48px] text-outline-variant mb-2 opacity-50">lock</span>
                            <p class="font-body-md text-[14px] text-on-surface-variant mb-6">You must be logged in to contact the agent and schedule a visit securely.</p>
                            <a href="<?= base_url('login') ?>" class="w-full inline-block bg-primary text-on-primary py-3 rounded font-bold text-[14px] hover:bg-primary-container transition-colors">
                                Sign In to Contact
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->include('front/layout/footer') ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var lat = <?= esc($property->latitude ?? -6.200000) ?>;
        var lng = <?= esc($property->longitude ?? 106.816666) ?>;
        
        // Initialize Map
        var map = L.map('propertyMap').setView([lat, lng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '&copy; OpenStreetMap' 
        }).addTo(map);

        // Define a custom red icon for the main property
        var homeIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Add Main Property Marker
        L.marker([lat, lng], {icon: homeIcon})
            .addTo(map)
            .bindPopup('<b><?= esc(addslashes($property->title)) ?></b><br>Property Location')
            .openPopup();

        // Dynamically plot all POIs from the database
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
    });

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

    // AJAX FUNCTION TO TOGGLE SAVED PROPERTY
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
                window.location.href = '<?= base_url('login') ?>';
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
</script>

<!-- PHASE 2: Mortgage Calculator Alpine Component -->
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
                let p = this.homePriceRaw - (this.homePriceRaw * (this.dpPercent / 100)); // Principal
                let r = (this.interestRate / 100) / 12; // Monthly Interest Rate
                let n = this.loanTerm * 12; // Total Months
                
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