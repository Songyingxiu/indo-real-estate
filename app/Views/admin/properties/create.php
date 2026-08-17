<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in" x-data="{ 
    translateText(text, targetId, langpair = 'en|id') {
        if (!text.trim()) return;
        fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=${langpair}`)
        .then(res => res.json())
        .then(data => {
            if (data.responseData && data.responseData.translatedText) {
                document.getElementById(targetId).value = data.responseData.translatedText;
            }
        })
        .catch(err => console.error('Translation error:', err));
    },
    validateImageCount(event) {
        if (event.target.files.length > 20) {
            alert('You can only select up to 20 images. Please select fewer files.');
            event.target.value = '';
        }
    },
    attemptAutoFill(stateName, cityName, postcode) {
        if (!stateName) return;
        let stateSelect = document.getElementById('state_id');
        if(!stateSelect) return;
        let matchedState = Array.from(stateSelect.options).find(opt => {
            if (!opt.value) return false;
            let optText = opt.text.toLowerCase();
            let sName = stateName.toLowerCase();
            return optText.includes(sName) || sName.includes(optText);
        });

        if (matchedState && this.stateId !== matchedState.value) {
            this.stateId = matchedState.value;
            this.cityId = '';
            this.zipcodeId = '';
        }

        let attempts = 0;
        let cityInterval = setInterval(() => {
            attempts++;
            if (this.cities.length > 0 && !this.isLoading) {
                clearInterval(cityInterval);
                if (cityName) {
                    let matchedCity = this.cities.find(c => {
                        let cName = (c.city_name || c.name).toLowerCase();
                        let sCity = cityName.toLowerCase();
                        if (cName.includes('selatan') && sCity.includes('south')) return true;
                        if (cName.includes('pusat') && sCity.includes('central')) return true;
                        if (cName.includes('barat') && sCity.includes('west')) return true;
                        if (cName.includes('timur') && sCity.includes('east')) return true;
                        if (cName.includes('utara') && sCity.includes('north')) return true;
                        return cName.includes(sCity) || sCity.includes(cName);
                    });
                    
                    if (matchedCity && this.cityId !== matchedCity.id) {
                        this.cityId = matchedCity.id;
                        this.zipcodeId = '';
                        
                        let zipAttempts = 0;
                        let zipInterval = setInterval(() => {
                            zipAttempts++;
                            if (this.zipcodes.length > 0 && !this.isZipLoading) {
                                clearInterval(zipInterval);
                                if (postcode) {
                                    let matchedZip = this.zipcodes.find(z => z.zipcode === postcode);
                                    if (matchedZip && this.zipcodeId !== matchedZip.id) {
                                        this.zipcodeId = matchedZip.id;
                                    }
                                }
                            } else if (zipAttempts > 15) { clearInterval(zipInterval); }
                        }, 200);
                    }
                }
            } else if (attempts > 15) { clearInterval(cityInterval); }
        }, 200);
    }
}" @autofill-location.window="attemptAutoFill($event.detail.state, $event.detail.city, $event.detail.zip)">
    
    <h2 class="font-headline-lg text-[28px] font-bold text-on-surface mb-6">Create New Listing</h2>
    
    <form action="<?= base_url('admin/properties/store') ?>" method="POST" enctype="multipart/form-data" novalidate class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant p-6 space-y-8">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4 border-b border-outline-variant pb-2">1. Basic Information & Legal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">Property Title (EN) <span class="text-error">*</span></label>
                    <input type="text" name="title_en" id="title_en" value="<?= old('title_en') ?>" @blur="translateText($event.target.value, 'title_id', 'en|id')" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="e.g. Luxury Villa in Canggu">
                    <?= session('errors.title_en') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.title_en')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Title (ID) <span class="text-error">*</span></label>
                    <input type="text" name="title_id" id="title_id" value="<?= old('title_id') ?>" @blur="translateText($event.target.value, 'title_en', 'id|en')" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="e.g. Vila Mewah di Canggu">
                    <?= session('errors.title_id') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.title_id')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Type <span class="text-error">*</span></label>
                    <select name="property_type_id" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <option value="" disabled <?= old('property_type_id') ? '' : 'selected' ?>>Select a type...</option>
                        <?php if (!empty($propertyTypes)): ?>
                            <?php foreach ($propertyTypes as $type): ?>
                                <option value="<?= esc($type->id) ?>" <?= old('property_type_id') == $type->id ? 'selected' : '' ?>><?= esc($type->name_en ?? $type->name) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No property types found</option>
                        <?php endif; ?>
                    </select>
                    <?= session('errors.property_type_id') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.property_type_id')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Listing Type <span class="text-error">*</span></label>
                    <select name="listing_type" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <option value="Sale" <?= old('listing_type') == 'Sale' ? 'selected' : '' ?>>For Sale</option>
                        <option value="Rent" <?= old('listing_type') == 'Rent' ? 'selected' : '' ?>>For Rent</option>
                    </select>
                    <?= session('errors.listing_type') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.listing_type')).'</p>' : '' ?>
                </div>
                
                <div>
                    <label class="block font-semibold mb-2">Asking Price (IDR) <span class="text-error">*</span></label>
                    <input type="number" name="tax_price" value="<?= old('tax_price') ?>" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                    <?= session('errors.tax_price') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.tax_price')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Tax Number (NOP / PBB)</label>
                    <input type="text" name="property_tax_number" value="<?= old('property_tax_number') ?>" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="Optional">
                    <?= session('errors.property_tax_number') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.property_tax_number')).'</p>' : '' ?>
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4 border-b border-outline-variant pb-2">2. Location Details & Zip Code</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ 
                stateId: '<?= old('state_id', '') ?>', 
                cityId: '<?= old('city_id', '') ?>',
                zipcodeId: '<?= old('zipcode_id', '') ?>',
                cities: [], zipcodes: [], isLoading: false, isZipLoading: false,
                init() {
                    if (this.stateId) { this.fetchCities(); }
                    if (this.cityId) { this.fetchZipcodes(); }
                    
                    this.$watch('stateId', (value) => {
                        this.cityId = '';
                        this.zipcodeId = '';
                        this.cities = [];
                        this.zipcodes = [];
                        if (value) this.fetchCities();
                    });

                    this.$watch('cityId', (value) => {
                        this.zipcodeId = '';
                        this.zipcodes = [];
                        if (value) this.fetchZipcodes();
                    });
                },
                fetchCities() {
                    this.isLoading = true;
                    const url = '<?= rtrim(base_url('admin/properties/get-cities'), '/') ?>/' + this.stateId;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => { this.cities = data; this.isLoading = false; })
                        .catch(error => { this.cities = []; this.isLoading = false; });
                },
                fetchZipcodes() {
                    this.isZipLoading = true;
                    const url = '<?= rtrim(base_url('admin/properties/get-zipcodes'), '/') ?>/' + this.cityId;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => { this.zipcodes = data; this.isZipLoading = false; })
                        .catch(error => { this.zipcodes = []; this.isZipLoading = false; });
                }
            }">
                
                <div>
                    <label class="block font-semibold mb-2">Region / State <span class="text-error">*</span></label>
                    <select name="state_id" id="state_id" x-model="stateId" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <option value="" disabled <?= old('state_id') ? '' : 'selected' ?>>Select a region...</option>
                        <?php if (!empty($states)): ?>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= esc($state->id) ?>"><?= esc($state->region_name ?? $state->name) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No regions found</option>
                        <?php endif; ?>
                    </select>
                    <?= session('errors.state_id') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.state_id')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block font-semibold mb-2">City <span class="text-error">*</span></label>
                    <select name="city_id" x-model="cityId" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" :disabled="!stateId || isLoading">
                        <option value="" disabled selected x-text="isLoading ? 'Loading cities...' : (cities.length === 0 ? 'No cities available in this region' : 'Select a city...')"></option>
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" x-text="city.city_name || city.name"></option>
                        </template>
                    </select>
                    <?= session('errors.city_id') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.city_id')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Zip Code <span class="text-error">*</span></label>
                    <select name="zipcode_id" x-model="zipcodeId" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" :disabled="!cityId || isZipLoading">
                        <option value="" x-text="isZipLoading ? 'Loading zip codes...' : (zipcodes.length === 0 ? 'No zip codes available' : 'Select a zip code...')"></option>
                        <template x-for="zip in zipcodes" :key="zip.id">
                            <option :value="zip.id" x-text="zip.zipcode"></option>
                        </template>
                    </select>
                    <?= session('errors.zipcode_id') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.zipcode_id')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Area / District Name</label>
                    <input type="text" name="area_name" id="area_name" value="<?= old('area_name') ?>" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="e.g. Kemang, Canggu">
                    <?= session('errors.area_name') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.area_name')).'</p>' : '' ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Address Line 1 <span class="text-error">*</span></label>
                    <input type="text" name="address_line_1" id="address_line_1" value="<?= old('address_line_1') ?>" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="Street name and number">
                    <?= session('errors.address_line_1') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.address_line_1')).'</p>' : '' ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Address Line 2</label>
                    <input type="text" name="address_line_2" value="<?= old('address_line_2') ?>" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="Apartment, suite, unit, building, floor, etc.">
                    <?= session('errors.address_line_2') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.address_line_2')).'</p>' : '' ?>
                </div>

                <div class="md:col-span-2 mt-2">
                    <label class="block font-semibold mb-2">Pinpoint on Map <span class="text-error">*</span></label>
                    <p class="text-xs text-on-surface-variant mb-2">Search an address, or drag the marker/click anywhere on the map to set the exact property location and auto-fill the address fields.</p>
                    
                    <div class="flex gap-2 mb-3">
                        <input type="text" id="mapSearchInput" placeholder="Search address or area (e.g., Kemang, Jakarta)" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary outline-none" @keydown.enter.prevent="searchMapLocation()">
                        <button type="button" onclick="searchMapLocation()" class="bg-primary text-on-primary px-6 py-2 rounded font-semibold hover:opacity-90 transition-opacity shadow-sm">Search</button>
                    </div>

                    <div id="propertyMap" class="w-full h-[300px] border border-outline-variant rounded z-10"></div>
                    
                    <div class="flex gap-4 mt-2">
                        <input type="text" name="latitude" id="propertyLat" value="<?= old('latitude') ?>" required readonly class="w-full bg-surface-container-lowest border border-outline-variant px-2 py-1 text-xs rounded text-on-surface-variant">
                        <input type="text" name="longitude" id="propertyLng" value="<?= old('longitude') ?>" required readonly class="w-full bg-surface-container-lowest border border-outline-variant px-2 py-1 text-xs rounded text-on-surface-variant">
                    </div>
                    <?= session('errors.latitude') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.latitude')).'</p>' : '' ?>
                    <?= session('errors.longitude') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.longitude')).'</p>' : '' ?>
                </div>

                <!-- POI Button Integration -->
                <div class="md:col-span-2 mt-4 p-4 bg-surface-container-lowest border border-outline-variant rounded flex items-center justify-between" 
                    x-data="{ poiRemaining: <?= ($maxPois ?? 0) - ($poisCreated ?? 0) ?>, maxPois: <?= $maxPois ?? 0 ?>, roleId: <?= session()->get('role_id') ?> }"
                    @poi-added.window="if(roleId != 4) poiRemaining--">
                    
                    <div>
                        <h4 class="font-bold text-sm text-on-surface">Enhance Local Map</h4>
                        <p class="text-xs text-on-surface-variant">Missing a school or hospital? Add it to the map for buyers.</p>
                    </div>

                    <?php if (isset($maxPois) && $maxPois > 0): ?>
                        <template x-if="poiRemaining > 0 || roleId == 4">
                            <button type="button" @click="$dispatch('open-poi-modal')" class="px-4 py-2 bg-secondary text-on-secondary rounded text-sm font-bold hover:opacity-90 transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add_location_alt</span>
                                <span x-text="roleId == 4 ? 'Add Custom POI (Unlimited)' : 'Add Custom POI (' + poiRemaining + ' Left)'"></span>
                            </button>
                        </template>
                        
                        <template x-if="poiRemaining <= 0 && roleId != 4">
                            <button type="button" disabled class="px-4 py-2 bg-surface-variant text-on-surface-variant rounded text-sm font-bold opacity-50 cursor-not-allowed flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">lock</span>
                                <span x-text="'POI Limit Reached (' + maxPois + '/' + maxPois + ')'"></span>
                            </button>
                        </template>
                    <?php else: ?>
                        <a href="<?= base_url('admin/pricing') ?>" target="_blank" class="px-4 py-2 border border-primary text-primary rounded text-sm font-bold hover:bg-primary-container transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">upgrade</span>
                            Upgrade to Add POIs
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SECTION 3: DIMENSIONS & FACILITIES -->
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4 border-b border-outline-variant pb-2">3. Property Dimensions & Facilities</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div><label class="block text-xs mb-1 font-semibold">Beds <span class="text-error">*</span></label><input type="number" name="bed" value="<?= old('bed') ?>" required class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"><?= session('errors.bed') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.bed')).'</p>' : '' ?></div>
                <div><label class="block text-xs mb-1 font-semibold">Baths <span class="text-error">*</span></label><input type="number" name="bath" value="<?= old('bath') ?>" required class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"><?= session('errors.bath') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.bath')).'</p>' : '' ?></div>
                <div><label class="block text-xs mb-1 font-semibold">Total Area (m2) <span class="text-error">*</span></label><input type="number" name="total_area" value="<?= old('total_area') ?>" required class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"><?= session('errors.total_area') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.total_area')).'</p>' : '' ?></div>
                <div><label class="block text-xs mb-1 font-semibold">Usable Area (m2)</label><input type="number" name="usable_area" value="<?= old('usable_area') ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"><?= session('errors.usable_area') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.usable_area')).'</p>' : '' ?></div>
                <div><label class="block text-xs mb-1 font-semibold">Land Area (m2)</label><input type="number" name="total_land_area" value="<?= old('total_land_area') ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"><?= session('errors.total_land_area') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.total_land_area')).'</p>' : '' ?></div>
                <div><label class="block text-xs mb-1 font-semibold">Year Built</label><input type="number" name="year_built" value="<?= old('year_built') ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface" placeholder="YYYY"><?= session('errors.year_built') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.year_built')).'</p>' : '' ?></div>
                <div><label class="block text-xs mb-1 font-semibold">Total Floors</label><input type="number" name="total_floors" value="<?= old('total_floors') ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"><?= session('errors.total_floors') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.total_floors')).'</p>' : '' ?></div>
                <div><label class="block text-xs mb-1 font-semibold">Unit Number</label><input type="text" name="unit_number" value="<?= old('unit_number') ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"><?= session('errors.unit_number') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.unit_number')).'</p>' : '' ?></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-3">
                    <label class="block text-xs mb-1 font-semibold">Building / Society Name</label>
                    <input type="text" name="building_society_name" value="<?= old('building_society_name') ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface" placeholder="Name of complex, cluster, or apartment building">
                    <?= session('errors.building_society_name') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.building_society_name')).'</p>' : '' ?>
                </div>
                
                <div>
                    <label class="block text-xs mb-1 font-semibold">Parking Availability</label>
                    <select name="parking" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                        <option value="Available" <?= old('parking') == 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Not Available" <?= old('parking') == 'Not Available' ? 'selected' : '' ?>>Not Available</option>
                    </select>
                    <?= session('errors.parking') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.parking')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block text-xs mb-1 font-semibold">Total Parking Spots</label>
                    <input type="number" name="total_parking" value="<?= old('total_parking', 0) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                    <?= session('errors.total_parking') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.total_parking')).'</p>' : '' ?>
                </div>

                <div>
                    <label class="block text-xs mb-1 font-semibold">Basement</label>
                    <select name="basement" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                        <option value="No" <?= old('basement') == 'No' ? 'selected' : '' ?>>No</option>
                        <option value="Yes" <?= old('basement') == 'Yes' ? 'selected' : '' ?>>Yes</option>
                    </select>
                    <?= session('errors.basement') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.basement')).'</p>' : '' ?>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs mb-1 font-semibold">Water Facility Type</label>
                    <input type="text" name="water_facility" value="<?= old('water_facility') ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface" placeholder="e.g. PDAM, Ground Water, Borehole">
                    <?= session('errors.water_facility') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.water_facility')).'</p>' : '' ?>
                </div>
            </div>
        </div>

        <!-- SECTION 4: FEATURES -->
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-2 border-b border-outline-variant pb-2">4. Premium Features</h3>
            <?php 
                $oldFeatures = old('features') ?? []; 
            ?>
            <?php if (!empty($categorizedFeatures)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-4">
                    <?php foreach ($categorizedFeatures as $category => $features): ?>
                        <div class="bg-surface-container-lowest p-4 border border-outline-variant rounded-lg">
                            <h4 class="font-bold text-on-surface mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-primary">category</span>
                                <?= esc($category) ?>
                            </h4>
                            <div class="flex flex-col gap-2">
                                <?php foreach ($features as $feature): ?>
                                    <label class="flex items-center gap-3 cursor-pointer hover:bg-surface-bright p-1.5 rounded transition-colors">
                                        <input type="checkbox" name="features[]" value="<?= esc($feature->id ?? $feature->feature_id) ?>" <?= in_array(esc($feature->id ?? $feature->feature_id), $oldFeatures) ? 'checked' : '' ?> class="w-4 h-4 text-primary bg-surface border-outline-variant rounded focus:ring-primary">
                                        <span class="text-sm text-on-surface font-medium"><?= esc($feature->name_en ?? $feature->name) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm text-on-surface-variant col-span-full bg-surface-container-lowest p-4 rounded border border-outline-variant mt-4">No additional features have been set up in Master Data.</p>
            <?php endif; ?>
        </div>

        <!-- SECTION 5: DESCRIPTION & MEDIA -->
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4 border-b border-outline-variant pb-2">5. Description & Media</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block font-semibold mb-2">Description (EN) <span class="text-error">*</span></label>
                    <textarea name="description_en" id="description_en" required rows="5" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="Provide a detailed description of the property..." @blur="translateText($event.target.value, 'description_id', 'en|id')"><?= old('description_en') ?></textarea>
                    <?= session('errors.description_en') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.description_en')).'</p>' : '' ?>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Description (ID) <span class="text-error">*</span></label>
                    <textarea name="description_id" id="description_id" required rows="5" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" placeholder="Berikan deskripsi detail tentang properti..." @blur="translateText($event.target.value, 'description_en', 'id|en')"><?= old('description_id') ?></textarea>
                    <?= session('errors.description_id') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.description_id')).'</p>' : '' ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">Photos (Max 20 images) <span class="text-error">*</span></label>
                    <input type="file" name="property_images[]" required multiple accept="image/*" @change="validateImageCount($event)" class="w-full p-2 border rounded bg-surface">
                    <p class="text-xs text-on-surface-variant mt-1">You can select up to 20 images.</p>
                    <?= session('errors.property_images') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.property_images')).'</p>' : '' ?>
                </div>
                <div>
                    <label class="block font-semibold mb-2">SHM / Legal Document <span class="text-error">*</span></label>
                    <input type="file" name="shm_document" required accept=".pdf,.jpg,.jpeg,.png" class="w-full p-2 border rounded bg-surface">
                    <?= session('errors.shm_document') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.shm_document')).'</p>' : '' ?>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t flex justify-end">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded font-semibold hover:opacity-90 transition-opacity shadow-md">Publish Listing</button>
        </div>
    </form>

    <!-- AGENT AJAX POI MODAL -->
    <div x-data="{ showAgentPoiModal: false }" @open-poi-modal.window="showAgentPoiModal = true" @close-poi-modal.window="showAgentPoiModal = false">
        <div x-show="showAgentPoiModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
            <div @click.outside="showAgentPoiModal = false" class="bg-surface w-full max-w-lg rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
                <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                    <h2 class="text-xl font-bold text-on-surface">Add a Nearby Place</h2>
                    <button type="button" @click="showAgentPoiModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
                </div>
                
                <div class="p-6 flex flex-col gap-4 overflow-y-auto custom-scrollbar">
                    <div id="poiAlertMessage" class="hidden p-3 rounded mb-2 text-sm font-bold"></div>

                    <div><label class="block text-sm font-semibold mb-1">Place Name</label><input type="text" id="agentPoiName" class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Category</label>
                        <select id="agentPoiCategory" name="category" class="w-full h-10 px-3 border border-outline-variant rounded bg-surface cursor-pointer">
                            <option value="School">School / University</option>
                            <option value="Station">Station / Transit</option>
                            <option value="Hospital">Hospital / Clinic</option>
                            <option value="Mall">Mall / Market</option>
                            <option value="Supermarket">Supermarket / Grocery</option>
                            <option value="Park">Park / Garden</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold mb-1">Latitude</label><input type="number" step="any" id="agentPoiLat" class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                        <div><label class="block text-sm font-semibold mb-1">Longitude</label><input type="number" step="any" id="agentPoiLng" class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showAgentPoiModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition-colors">Cancel</button>
                    <button type="button" onclick="submitAgentPoi()" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition-all">Submit POI</button>
                </div>
            </div>
        </div>
    </div>

    <!-- INITIALIZE LEAFLET MAP & AJAX -->
    <script>
        let map;
        let marker;
        let poiIcon;
        function searchMapLocation() {
            const query = document.getElementById('mapSearchInput').value;
            if(!query) return;
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if(data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        
                        map.setView([lat, lon], 16);
                        marker.setLatLng([lat, lon]);
                        
                        document.getElementById('propertyLat').value = lat;
                        document.getElementById('propertyLng').value = lon;
                        
                        reverseGeocode(lat, lon);
                    } else {
                        alert('Location not found. Please try a different search term.');
                    }
                })
                .catch(err => console.error('Geocoding error:', err));
        }

        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if(data && data.address) {
                        const addr = data.address;
                        const state = addr.state || addr.region || '';
                        const city = addr.city || addr.town || addr.county || addr.municipality || '';
                        const zip = addr.postcode || '';
                        const road = addr.road || '';
                        const house = addr.house_number || '';
                        const streetAddr = [road, house].filter(Boolean).join(' ');
                        const addrInput = document.getElementById('address_line_1');
                        if (addrInput && !addrInput.value && streetAddr) {
                            addrInput.value = streetAddr;
                        }

                        // Auto-fill area name if empty
                        const suburb = addr.suburb || addr.neighbourhood || addr.village || '';
                        const areaInput = document.getElementById('area_name');
                        if (areaInput && !areaInput.value && suburb) {
                            areaInput.value = suburb;
                        }
                        window.dispatchEvent(new CustomEvent('autofill-location', {
                            detail: { state, city, zip }
                        }));
                    }
                })
                .catch(err => console.error('Reverse Geocoding error:', err));
        }

        document.addEventListener("DOMContentLoaded", function() {
            const oldLat = <?= old('latitude') ? esc(old('latitude')) : '-6.2088' ?>;
            const oldLng = <?= old('longitude') ? esc(old('longitude')) : '106.8456' ?>;
            
            const defaultLat = oldLat !== null ? oldLat : -6.2250;
            const defaultLng = oldLng !== null ? oldLng : 106.9004;

            map = L.map('propertyMap').setView([defaultLat, defaultLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            setTimeout(() => { map.invalidateSize(); }, 250);

            marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
            marker.bindPopup("<b>Target Property</b><br>Drag to adjust location.").openPopup();

            document.getElementById('propertyLat').value = defaultLat;
            document.getElementById('propertyLng').value = defaultLng;

            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                document.getElementById('propertyLat').value = position.lat;
                document.getElementById('propertyLng').value = position.lng;
                reverseGeocode(position.lat, position.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('propertyLat').value = e.latlng.lat;
                document.getElementById('propertyLng').value = e.latlng.lng;
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            const poiData = <?= json_encode($pois ?? []) ?>;
            
            poiIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            poiData.forEach(poi => {
                if (poi.latitude && poi.longitude) {
                    L.marker([poi.latitude, poi.longitude], { icon: poiIcon })
                     .addTo(map)
                     .bindPopup(`<div class="text-center"><b>${poi.name}</b><br><span class="text-xs px-2 py-0.5 bg-gray-200 rounded">${poi.category}</span></div>`);
                }
            });
        });

        function submitAgentPoi() {
            const data = {
                name: document.getElementById('agentPoiName').value,
                category: document.getElementById('agentPoiCategory').value,
                latitude: document.getElementById('agentPoiLat').value,
                longitude: document.getElementById('agentPoiLng').value
            };
            const alertBox = document.getElementById('poiAlertMessage');

            fetch('<?= base_url('agent/poi/store-ajax') ?>', { 
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                alertBox.classList.remove('hidden', 'bg-[#ffdad6]', 'text-[#410002]', 'bg-[#d3e3fd]', 'text-[#041e49]');
                
                if (data.status === 'success') {
                    alertBox.classList.add('bg-[#d3e3fd]', 'text-[#041e49]');
                    alertBox.innerText = data.message;
                    
                    const newLat = parseFloat(document.getElementById('agentPoiLat').value);
                    const newLng = parseFloat(document.getElementById('agentPoiLng').value);
                    const newName = document.getElementById('agentPoiName').value;
                    const newCat = document.getElementById('agentPoiCategory').value;

                    if (!isNaN(newLat) && !isNaN(newLng) && map && poiIcon) {
                        L.marker([newLat, newLng], { icon: poiIcon })
                         .addTo(map)
                         .bindPopup(`<div class="text-center"><b>${newName}</b><br><span class="text-xs px-2 py-0.5 bg-gray-200 rounded">${newCat}</span></div>`);
                    }

                    document.getElementById('agentPoiName').value = '';
                    document.getElementById('agentPoiLat').value = '';
                    document.getElementById('agentPoiLng').value = '';

                    setTimeout(() => { 
                        window.dispatchEvent(new CustomEvent('close-poi-modal')); 
                        window.dispatchEvent(new CustomEvent('poi-added'));
                        alertBox.classList.add('hidden'); 
                    }, 1200);

                } else {
                    alertBox.classList.add('bg-[#ffdad6]', 'text-[#410002]');
                    alertBox.innerText = data.message;
                }
            })
            .catch(err => {
                console.error(err);
                alertBox.classList.remove('hidden');
                alertBox.classList.add('bg-[#ffdad6]', 'text-[#410002]');
                alertBox.innerText = 'An error occurred while saving the POI. Check your network tab for CSRF or Route errors.';
            });
        }
    </script>
</div>
<?= $this->endSection() ?>