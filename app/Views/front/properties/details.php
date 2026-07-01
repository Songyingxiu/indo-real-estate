<?= $this->include('front/layout/header') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-8">
    <nav aria-label="Breadcrumb" class="mb-4 flex items-center text-on-surface-variant text-[12px] font-caption">
        <a class="hover:text-primary transition-colors" href="<?= base_url() ?>">Home</a>
        <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
        <a class="hover:text-primary transition-colors" href="<?= base_url('search') ?>">Properties</a>
        <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-on-surface font-medium"><?= esc($property->title) ?></span>
    </nav>

    <?php 
        $mainImg = !empty($images[0]) ? base_url(esc($images[0]->image_path)) : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1920&q=80';
        $img1 = !empty($images[1]) ? base_url(esc($images[1]->image_path)) : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
        $img2 = !empty($images[2]) ? base_url(esc($images[2]->image_path)) : 'https://images.unsplash.com/photo-1600607687920-4e20d33c01f6?auto=format&fit=crop&w=800&q=80';
    ?>

    <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-4 mb-8 rounded overflow-hidden h-[500px]">
        <div class="md:col-span-2 md:row-span-2 relative group overflow-hidden">
            <img alt="Featured" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $mainImg ?>">
            <div class="absolute bottom-4 left-4 bg-white/70 backdrop-blur-md px-4 py-2 rounded flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px] fill">photo_library</span>
                <span class="font-label-md text-[14px] text-primary font-bold">View All Photos</span>
            </div>
        </div>
        <div class="relative overflow-hidden group hidden md:block">
            <img alt="Gallery 1" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $img1 ?>">
        </div>
        <div class="relative overflow-hidden group hidden md:block">
            <img alt="Gallery 2" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $img2 ?>">
        </div>
        <div class="md:col-span-2 relative overflow-hidden group hidden md:block">
            <div class="w-full h-full bg-surface-container-high flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[32px] mb-2">360</span>
                <span class="font-label-md text-[14px] font-bold">Virtual Tour</span>
            </div>
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
                        <h1 class="font-headline-lg text-[28px] md:text-[32px] font-bold text-on-surface mb-2"><?= esc($property->title) ?></h1>
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
                        <p class="font-label-md text-[14px] font-bold text-on-surface"><?= esc($property->usable_area) ?> Sqm</p>
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
        </div>

        <div class="lg:col-span-4">
            <div class="sticky top-28 flex flex-col gap-6">
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-outline-variant">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[40px]">person</span>
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

                    <form class="flex flex-col gap-3">
                        <input class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white" placeholder="Full Name" type="text">
                        <input class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white" placeholder="Phone Number" type="tel">
                        <textarea class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white resize-none" placeholder="I am interested in <?= esc($property->title) ?>..." rows="3"></textarea>
                        
                        <button class="w-full bg-primary-container text-white py-3 rounded font-bold text-[14px] hover:bg-primary transition-colors mt-2 flex items-center justify-center gap-2" type="button">
                            <span class="material-symbols-outlined text-[20px]">mail</span> Contact Agent
                        </button>
                    </form>
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
        
        var map = L.map('propertyMap').setView([lat, lng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        L.marker([lat, lng]).addTo(map)
            .bindPopup('<b><?= esc($property->title) ?></b><br><?= esc($property->area_name) ?>')
            .openPopup();
    });
</script>