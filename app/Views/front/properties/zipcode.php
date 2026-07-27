<?= $this->include('front/layout/header') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-10 min-h-[calc(100vh-80px)]">
    <div class="mb-6">
        <h1 class="font-headline-lg text-[32px] font-bold text-on-background">Properties in Zip Code: <?= esc($zipcode['zipcode'] ?? '') ?></h1>
        <p class="text-on-surface-variant mt-2">Explore available real estate locations on the map below.</p>
    </div>

    <!-- Map View -->
    <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden shadow-sm h-[400px] mb-10 z-0 relative">
        <div id="zipcodeMap" class="w-full h-full"></div>
    </div>

    <!-- Property Grid -->
    <h2 class="font-headline-md text-[24px] font-bold text-on-background mb-6">Available Properties</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if(!empty($properties)): foreach($properties as $property): ?>
            <article class="bg-surface border border-outline-variant rounded overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                <a href="<?= base_url('properties/' . $property['id']) ?>" class="h-48 bg-surface-container-high block relative">
                    <img alt="<?= esc($property['title']) ?>" class="w-full h-full object-cover" src="<?= esc($property['image_path'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80') ?>"/>
                    <div class="absolute top-2 left-2 bg-secondary-container text-on-secondary-container text-[10px] font-bold px-2 py-1 rounded uppercase">
                        <?= esc($property['listing_type']) ?>
                    </div>
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <a href="<?= base_url('properties/' . $property['id']) ?>" class="font-bold text-[16px] text-on-surface hover:text-primary line-clamp-2"><?= esc($property['title']) ?></a>
                    <span class="font-bold text-primary-container mt-2">Rp <?= number_format($property['tax_price'], 0, ',', '.') ?></span>
                </div>
            </article>
        <?php endforeach; else: ?>
            <p class="col-span-full text-center text-on-surface-variant py-8">No properties found in this zip code yet.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($pager)) : ?>
        <div class="mt-8">
            <?= $pager->links('default', 'tailwind_pagination') ?>
        </div>
    <?php endif ?>
</main>

<?= $this->include('front/layout/footer') ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('zipcodeMap').setView([-0.789, 113.921], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var markersData = <?= json_encode($markers ?? []) ?>;
        var bounds = [];

        markersData.forEach(function(m) {
            if(m.latitude && m.longitude) {
                var marker = L.marker([m.latitude, m.longitude]).addTo(map);
                marker.bindPopup(`
                    <div class="text-center">
                        <a href="<?= base_url('properties') ?>/${m.id}" class="font-bold text-primary hover:underline">${m.title}</a>
                        <p class="text-sm mt-1">Rp ${parseInt(m.tax_price).toLocaleString('id-ID')}</p>
                    </div>
                `);
                bounds.push([m.latitude, m.longitude]);
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [30, 30], maxZoom: 15 });
        }
    });
</script>