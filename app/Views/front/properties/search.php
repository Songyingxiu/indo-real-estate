<?= $this->include('front/layout/header') ?>

<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div class="flex-1 flex flex-col md:flex-row overflow-hidden min-h-[calc(100vh-80px)]">
    
    <aside class="w-full md:w-80 bg-surface border-r border-outline-variant flex-shrink-0 flex flex-col h-full overflow-y-auto custom-scrollbar z-20">
        <form id="filterForm" action="<?= base_url('search') ?>" method="GET" class="p-6 flex flex-col gap-6 h-full relative">
            
            <input type="hidden" name="listing_type" id="filter_listing_type" value="<?= esc($listingType ?? 'Sale') ?>">
            
            <!-- Hidden inputs for Radius Search Coordinates -->
            <input type="hidden" name="lat" id="filter_lat" value="<?= esc($lat ?? '') ?>">
            <input type="hidden" name="lng" id="filter_lng" value="<?= esc($lng ?? '') ?>">

            <div class="flex justify-between items-center mb-2">
                <h2 class="font-headline-lg-mobile text-[20px] font-bold text-primary">Filters</h2>
                <?php if(session()->get('id')): ?>
                    <button type="button" onclick="saveCurrentSearch()" class="text-primary hover:text-primary-container text-[12px] font-bold flex items-center gap-1 bg-primary-container/10 px-2 py-1 rounded transition-colors">
                        <span class="material-symbols-outlined text-[14px]">bookmark_add</span> Save Search
                    </button>
                <?php endif; ?>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-[14px] text-on-surface">Keyword / Location</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-outline text-[20px]">search</span>
                    
                    <input name="q" id="sidebarSearchInput" autocomplete="off" value="<?= esc($keyword ?? '') ?>" class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded bg-surface-container-lowest focus:border-primary-container focus:ring-1 focus:ring-primary-fixed-dim outline-none transition-all font-body-md text-[14px] text-on-surface" placeholder="e.g. Pool, Canggu" type="text"/>
                    
                    <div id="sidebarSuggestDropdown" class="absolute left-0 right-0 top-full mt-1 bg-surface border border-outline-variant rounded shadow-lg z-50 hidden max-h-60 overflow-y-auto w-full"></div>
                </div>
            </div>

            <hr class="border-outline-variant"/>

            <!-- NEW: PRICE RANGE FILTER -->
            <div>
                <h3 class="font-label-md text-[14px] text-on-surface mb-3">Price Range (Rp)</h3>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" value="<?= esc($_GET['min_price'] ?? '') ?>" placeholder="Min" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface-container-lowest focus:border-primary-container outline-none font-body-md text-[14px]">
                    <span class="text-on-surface-variant">-</span>
                    <input type="number" name="max_price" value="<?= esc($_GET['max_price'] ?? '') ?>" placeholder="Max" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface-container-lowest focus:border-primary-container outline-none font-body-md text-[14px]">
                </div>
            </div>

            <!-- NEW: BED & BATH FILTER -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-label-md text-[14px] text-on-surface mb-3">Beds</h3>
                    <select name="bed" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface-container-lowest focus:border-primary-container outline-none font-body-md text-[14px]">
                        <option value="">Any</option>
                        <option value="1" <?= ($_GET['bed'] ?? '') == '1' ? 'selected' : '' ?>>1+</option>
                        <option value="2" <?= ($_GET['bed'] ?? '') == '2' ? 'selected' : '' ?>>2+</option>
                        <option value="3" <?= ($_GET['bed'] ?? '') == '3' ? 'selected' : '' ?>>3+</option>
                        <option value="4" <?= ($_GET['bed'] ?? '') == '4' ? 'selected' : '' ?>>4+</option>
                        <option value="5" <?= ($_GET['bed'] ?? '') == '5' ? 'selected' : '' ?>>5+</option>
                    </select>
                </div>
                <div>
                    <h3 class="font-label-md text-[14px] text-on-surface mb-3">Baths</h3>
                    <select name="bath" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface-container-lowest focus:border-primary-container outline-none font-body-md text-[14px]">
                        <option value="">Any</option>
                        <option value="1" <?= ($_GET['bath'] ?? '') == '1' ? 'selected' : '' ?>>1+</option>
                        <option value="2" <?= ($_GET['bath'] ?? '') == '2' ? 'selected' : '' ?>>2+</option>
                        <option value="3" <?= ($_GET['bath'] ?? '') == '3' ? 'selected' : '' ?>>3+</option>
                        <option value="4" <?= ($_GET['bath'] ?? '') == '4' ? 'selected' : '' ?>>4+</option>
                    </select>
                </div>
            </div>

            <hr class="border-outline-variant"/>

            <!-- RADIUS FILTER -->
            <div class="flex flex-col gap-2">
                <div class="flex justify-between items-center">
                    <label class="font-label-md text-[14px] text-on-surface">Radius (Km)</label>
                    <span id="radiusValue" class="text-[12px] font-bold text-primary"><?= esc($radius ?? 'Any') ?> <?= isset($radius) && $radius !== '' ? 'Km' : '' ?></span>
                </div>
                <input type="range" name="radius" id="radiusSlider" min="1" max="50" value="<?= esc($radius ?? 50) ?>" class="w-full accent-primary" oninput="document.getElementById('radiusValue').innerText = this.value + ' Km'">
                <button type="button" onclick="getLocation()" class="text-[12px] text-on-surface-variant hover:text-primary flex items-center gap-1 mt-1 transition-colors w-fit">
                    <span class="material-symbols-outlined text-[14px]">my_location</span> Use my current location
                </button>
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
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php if(!empty($properties)): foreach($properties as $property): ?>
                        <article class="bg-surface border border-outline-variant rounded-b-lg rounded-t-xl overflow-hidden hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-300 flex flex-col">
                            <a href="<?= base_url('property/' . $property['id']) ?>" class="relative h-48 w-full bg-surface-container-highest block group">
                                <?php 
                                    $imgPath = trim($property['image_path'] ?? $property['image'] ?? '');
                                    $imgSrc = 'https://placehold.co/800x600/e2e8f0/8492a6?text=Property+Image';
                                    if (!empty($imgPath)) {
                                        $imgSrc = (strpos($imgPath, 'http') === 0) ? esc($imgPath) : base_url(esc($imgPath));
                                    }
                                ?>
                                <img alt="<?= esc($property['title']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $imgSrc ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x600/e2e8f0/8492a6?text=No+Image+Available';"/>
                                
                                <div class="absolute top-3 left-3 bg-tertiary-container text-on-tertiary font-label-md text-[12px] px-2 py-1 rounded flex items-center gap-1 shadow-sm">
                                    <span class="material-symbols-outlined text-[14px]">sell</span>
                                    <?= esc($property['listing_type']) ?>
                                </div>
                                <?php if(isset($property['distance'])): ?>
                                    <div class="absolute bottom-3 right-3 bg-surface/90 backdrop-blur-sm text-on-surface font-label-md text-[12px] px-2 py-1 rounded shadow-sm">
                                        <?= number_format($property['distance'], 1) ?> km away
                                    </div>
                                <?php endif; ?>
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <div class="flex justify-between items-start mb-2 gap-2">
                                    <h3 class="font-headline-lg-mobile text-[18px] leading-tight font-semibold text-on-surface line-clamp-2">
                                        <a href="<?= base_url('property/' . $property['id']) ?>" class="hover:text-primary"><?= esc($property['title']) ?></a>
                                    </h3>
                                </div>
                                <span class="font-headline-lg-mobile text-[20px] font-bold text-primary-container mb-2">Rp <?= number_format($property['tax_price'], 0, ',', '.') ?></span>
                                
                                <p class="font-body-md text-[14px] text-on-surface-variant mb-4 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                    <?= esc($property['area_name'] ?? $property['address_line_1'] ?? 'Location Not Set') ?>
                                </p>
                                
                                <div class="flex items-center gap-4 border-t border-outline-variant pt-4 mt-auto">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-[14px]">
                                            <?= strtoupper(substr($property['first_name'] ?? 'A', 0, 1)) ?>
                                        </div>
                                        <span class="font-label-md text-[14px] text-on-surface"><?= esc(($property['first_name'] ?? 'Agent') . ' ' . ($property['last_name'] ?? '')) ?></span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sidebarSearchInput');
    const suggestDropdown = document.getElementById('sidebarSuggestDropdown');
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
                                    <span class="font-body-md text-[14px] truncate max-w-[150px]">${item.text}</span>
                                </div>
                            `;
                            
                            div.addEventListener('click', () => {
                                if (item.url) {
                                    window.location.href = item.url;
                                }
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

// Geolocation for Radius Search
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('filter_lat').value = position.coords.latitude;
                document.getElementById('filter_lng').value = position.coords.longitude;
                document.getElementById('sidebarSearchInput').value = 'My Location';
                document.getElementById('filterForm').submit();
            },
            function(error) {
                alert("Unable to retrieve your location. Please check your browser permissions.");
            }
        );
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

// Save Search AJAX logic
function saveCurrentSearch() {
    const urlParams = new URLSearchParams(window.location.search);
    const filters = Object.fromEntries(urlParams.entries());
    const searchName = prompt("Enter a name for this search:", "My Property Search");
    
    if (!searchName) return;

    const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
    const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');

    fetch('<?= base_url('search/save') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            [csrfName]: csrfHash
        },
        body: JSON.stringify({ name: searchName, filters: filters })
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
            alert(data.message);
        } else {
            alert(data.message || 'An error occurred.');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?= $this->include('front/layout/footer') ?>