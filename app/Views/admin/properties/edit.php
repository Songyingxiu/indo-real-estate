<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<!-- Leaflet Map Dependencies -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in" x-data="{ 
    showValidationErrorModal: <?= session()->has('errors') ? 'true' : 'false' ?>,
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
    }
}">
    <h2 class="font-headline-lg text-[28px] font-bold text-on-surface mb-6">Edit Property Listing</h2>

    <form action="<?= base_url('admin/properties/update/' . $property['id']) ?>" method="POST" enctype="multipart/form-data" novalidate class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant p-6 space-y-8">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        
        <!-- SECTION 1: BASIC INFO & LEGAL -->
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4 border-b border-outline-variant pb-2">1. Basic Information & Legal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">Property Title (EN) *</label>
                    <input type="text" name="title_en" id="title_en" value="<?= esc($property['title_en'] ?? $property['title']) ?>" @blur="translateText($event.target.value, 'title_id', 'en|id')" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Title (ID) *</label>
                    <input type="text" name="title_id" id="title_id" value="<?= esc($property['title_id'] ?? $property['title']) ?>" @blur="translateText($event.target.value, 'title_en', 'id|en')" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Type *</label>
                    <select name="property_type_id" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <?php foreach ($propertyTypes as $type): ?>
                            <option value="<?= esc($type->id) ?>" <?= $property['property_type_id'] == $type->id ? 'selected' : '' ?>>
                                <?= esc($type->name_en ?? $type->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Listing Type *</label>
                    <select name="listing_type" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <option value="Sale" <?= $property['listing_type'] == 'Sale' ? 'selected' : '' ?>>For Sale</option>
                        <option value="Rent" <?= $property['listing_type'] == 'Rent' ? 'selected' : '' ?>>For Rent</option>
                    </select>
                </div>
                
                <div>
                    <label class="block font-semibold mb-2">Asking Price (IDR) *</label>
                    <input type="number" name="tax_price" value="<?= esc($property['tax_price']) ?>" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Tax Number (NOP / PBB)</label>
                    <input type="text" name="property_tax_number" value="<?= esc($property['property_tax_number']) ?>" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>
            </div>
        </div>

        <!-- SECTION 2: LOCATION DETAILS & MAP -->
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4 border-b border-outline-variant pb-2">2. Location Details & Zip Code</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ 
                stateId: '<?= esc($property['state_id'] ?? '') ?>', 
                cityId: '<?= esc($property['city_id'] ?? '') ?>',
                zipcodeId: '<?= esc($property['zipcode_id'] ?? '') ?>',
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
                        .then(response => {
                            if(!response.ok) throw new Error('Server returned an error.');
                            return response.json();
                        })
                        .then(data => {
                            this.cities = data;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error('AJAX Error:', error);
                            this.cities = [];
                            this.isLoading = false;
                        });
                },
                fetchZipcodes() {
                    this.isZipLoading = true;
                    const url = '<?= rtrim(base_url('admin/properties/get-zipcodes'), '/') ?>/' + this.cityId;
                    fetch(url)
                        .then(response => {
                            if(!response.ok) throw new Error('Server returned an error.');
                            return response.json();
                        })
                        .then(data => {
                            this.zipcodes = data;
                            this.isZipLoading = false;
                        })
                        .catch(error => {
                            console.error('AJAX Error:', error);
                            this.zipcodes = [];
                            this.isZipLoading = false;
                        });
                }
            }">
                
                <div>
                    <label class="block font-semibold mb-2">Region / State *</label>
                    <select name="state_id" x-model="stateId" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <?php foreach ($states as $state): ?>
                            <option value="<?= esc($state->id) ?>"><?= esc($state->region_name ?? $state->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">City *</label>
                    <select name="city_id" x-model="cityId" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" :disabled="!stateId || isLoading">
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" x-text="city.city_name || city.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Zip Code *</label>
                    <select name="zipcode_id" x-model="zipcodeId" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" :disabled="!cityId || isZipLoading">
                        <template x-for="zip in zipcodes" :key="zip.id">
                            <option :value="zip.id" x-text="zip.zipcode"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Area / District Name</label>
                    <input type="text" name="area_name" value="<?= esc($property['area_name']) ?>" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Address Line 1 *</label>
                    <input type="text" name="address_line_1" value="<?= esc($property['address_line_1']) ?>" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Address Line 2</label>
                    <input type="text" name="address_line_2" value="<?= esc($property['address_line_2']) ?>" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div class="md:col-span-2 mt-2">
                    <label class="block font-semibold mb-2">Pinpoint on Map *</label>
                    <p class="text-xs text-on-surface-variant mb-2">Drag the marker or click anywhere on the map to set the exact property location.</p>
                    <div id="propertyMap" class="w-full h-[300px] border border-outline-variant rounded z-10"></div>
                    <div class="flex gap-4 mt-2">
                        <input type="text" name="latitude" id="propertyLat" value="<?= esc($property['latitude']) ?>" required readonly class="w-full bg-surface-container-lowest border border-outline-variant px-2 py-1 text-xs rounded text-on-surface-variant">
                        <input type="text" name="longitude" id="propertyLng" value="<?= esc($property['longitude']) ?>" required readonly class="w-full bg-surface-container-lowest border border-outline-variant px-2 py-1 text-xs rounded text-on-surface-variant">
                    </div>
                </div>

                <!-- POI Button Integration -->
                <div class="md:col-span-2 mt-4 p-4 bg-surface-container-lowest border border-outline-variant rounded flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-on-surface">Enhance Local Map</h4>
                        <p class="text-xs text-on-surface-variant">Missing a school or hospital? Add it to the map for buyers.</p>
                    </div>
                    <?php 
                        $poiRemaining = ($maxPois ?? 0) - ($poisCreated ?? 0); 
                    ?>
                    <?php if (isset($maxPois) && $maxPois > 0): ?>
                        <?php if ($poiRemaining > 0 || session()->get('role_id') == 4): ?>
                            <button type="button" @click="$dispatch('open-poi-modal')" class="px-4 py-2 bg-secondary text-on-secondary rounded text-sm font-bold hover:opacity-90 transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add_location_alt</span>
                                Add Custom POI (<?= session()->get('role_id') == 4 ? 'Unlimited' : $poiRemaining . ' Left' ?>)
                            </button>
                        <?php else: ?>
                            <button type="button" disabled class="px-4 py-2 bg-surface-variant text-on-surface-variant rounded text-sm font-bold opacity-50 cursor-not-allowed flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">lock</span>
                                POI Limit Reached (<?= $maxPois ?>/<?= $maxPois ?>)
                            </button>
                        <?php endif; ?>
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
                <div><label class="block text-xs mb-1 font-semibold">Beds</label><input type="number" name="bed" value="<?= esc($property['bed']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Baths</label><input type="number" name="bath" value="<?= esc($property['bath']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Total Area (m2)</label><input type="number" name="total_area" value="<?= esc($property['total_area']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Usable Area (m2)</label><input type="number" name="usable_area" value="<?= esc($property['usable_area']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Land Area (m2)</label><input type="number" name="total_land_area" value="<?= esc($property['total_land_area']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Year Built</label><input type="number" name="year_built" value="<?= esc($property['year_built']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Total Floors</label><input type="number" name="total_floors" value="<?= esc($property['total_floors']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Unit Number</label><input type="text" name="unit_number" value="<?= esc($property['unit_number']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-3">
                    <label class="block text-xs mb-1 font-semibold">Building / Society Name</label>
                    <input type="text" name="building_society_name" value="<?= esc($property['building_society_name']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                </div>
                
                <div>
                    <label class="block text-xs mb-1 font-semibold">Parking Availability</label>
                    <select name="parking" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                        <option value="Yes" <?= $property['parking'] == 'Yes' ? 'selected' : '' ?>>Available</option>
                        <option value="No" <?= $property['parking'] == 'No' ? 'selected' : '' ?>>Not Available</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs mb-1 font-semibold">Total Parking Spots</label>
                    <input type="number" name="total_parking" value="<?= esc($property['total_parking']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                </div>

                <div>
                    <label class="block text-xs mb-1 font-semibold">Basement</label>
                    <select name="basement" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                        <option value="No" <?= $property['basement'] == 'No' ? 'selected' : '' ?>>No</option>
                        <option value="Yes" <?= $property['basement'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs mb-1 font-semibold">Water Facility Type</label>
                    <input type="text" name="water_facility" value="<?= esc($property['water_facility']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface">
                </div>
            </div>
        </div>

        <!-- SECTION 4: FEATURES -->
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-2 border-b border-outline-variant pb-2">4. Premium Features</h3>
            <?php if (!empty($categorizedFeatures)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-4">
                    <?php foreach ($categorizedFeatures as $category => $catFeatures): ?>
                        <div class="bg-surface-container-lowest p-4 border border-outline-variant rounded-lg">
                            <h4 class="font-bold text-on-surface mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-primary">category</span>
                                <?= esc($category) ?>
                            </h4>
                            <div class="flex flex-col gap-2">
                                <?php foreach ($catFeatures as $feature): ?>
                                    <label class="flex items-center gap-3 cursor-pointer hover:bg-surface-bright p-1.5 rounded transition-colors">
                                        <input type="checkbox" name="features[]" value="<?= esc($feature->id ?? $feature->feature_id) ?>" <?= in_array(($feature->id ?? $feature->feature_id), $selectedFeatureIds ?? []) ? 'checked' : '' ?> class="w-4 h-4 text-primary bg-surface border-outline-variant rounded focus:ring-primary">
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
                    <label class="block font-semibold mb-2">Description (EN)</label>
                    <textarea name="description_en" id="description_en" rows="5" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" @blur="translateText($event.target.value, 'description_id', 'en|id')"><?= esc($property['description_en'] ?? $property['description']) ?></textarea>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Description (ID)</label>
                    <textarea name="description_id" id="description_id" rows="5" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" @blur="translateText($event.target.value, 'description_en', 'id|en')"><?= esc($property['description_id'] ?? $property['description']) ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">Upload New Photos (Optional)</label>
                    <input type="file" name="property_images[]" multiple accept="image/*" class="w-full p-2 border rounded bg-surface">
                </div>
                <div>
                    <label class="block font-semibold mb-2">Upload New SHM Document (Optional)</label>
                    <input type="file" name="shm_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full p-2 border rounded bg-surface">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t flex justify-end">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded font-semibold hover:opacity-90 transition-opacity shadow-md">Update Listing</button>
        </div>
    </form>

    <!-- ALPINE VALIDATION MODAL -->
    <div x-show="showValidationErrorModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showValidationErrorModal = false" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px]">error</span>
                </div>
                <h2 class="text-xl font-bold text-on-surface mb-2">Missing Information</h2>
                <ul class="text-left text-sm text-error bg-error-container/30 p-4 rounded-lg space-y-1">
                    <?php if (session()->has('errors')) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-[16px] mt-0.5">fiber_manual_record</span>
                                <?= esc($error) ?>
                            </li>
                        <?php endforeach ?>
                    <?php endif ?>
                </ul>
            </div>
            <div class="px-6 py-4 flex bg-surface-container-lowest border-t border-outline-variant">
                <button type="button" @click="showValidationErrorModal = false" class="w-full px-4 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Got it, I'll fix it</button>
            </div>
        </div>
    </div>

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
                        <select id="agentPoiCategory" class="w-full h-10 px-3 border border-outline-variant rounded bg-surface cursor-pointer">
                            <option value="School">School / University</option>
                            <option value="Station">Station / Transit</option>
                            <option value="Hospital">Hospital / Clinic</option>
                            <option value="Mall">Mall / Market</option>
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
        let poiIcon;

        document.addEventListener("DOMContentLoaded", function() {
            const savedLat = <?= !empty($property['latitude']) ? esc($property['latitude']) : '-6.2250' ?>;
            const savedLng = <?= !empty($property['longitude']) ? esc($property['longitude']) : '106.9004' ?>;

            map = L.map('propertyMap').setView([savedLat, savedLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            setTimeout(() => { map.invalidateSize(); }, 250);

            const marker = L.marker([savedLat, savedLng], { draggable: true }).addTo(map);
            marker.bindPopup("<b>Target Property</b><br>Drag to adjust location.").openPopup();
            
            document.getElementById('propertyLat').value = savedLat;
            document.getElementById('propertyLng').value = savedLng;

            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                document.getElementById('propertyLat').value = position.lat;
                document.getElementById('propertyLng').value = position.lng;
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('propertyLat').value = e.latlng.lat;
                document.getElementById('propertyLng').value = e.latlng.lng;
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