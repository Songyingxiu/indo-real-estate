<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in" x-data="{ showValidationErrorModal: <?= session()->has('errors') ? 'true' : 'false' ?> }">
    <h2 class="font-headline-lg text-[28px] font-bold text-on-surface mb-6">Edit Property Listing</h2>

    <form action="<?= base_url('admin/properties/update/' . $property['id']) ?>" method="POST" enctype="multipart/form-data" novalidate class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant p-6 space-y-8">
        
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4">Property Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ 
                stateId: '<?= esc($property['state_id']) ?>', 
                cities: [], 
                isLoading: false,
                init() {
                    // Pre-load cities if editing an existing state
                    if(this.stateId) { this.fetchCities(this.stateId); }
                },
                fetchCities() {
                    if (!this.stateId) {
                        this.cities = [];
                        return;
                    }
                    this.isLoading = true;
                    fetch('<?= base_url('admin/properties/get-cities/') ?>' + this.stateId)
                        .then(response => response.json())
                        .then(data => {
                            this.cities = data;
                            this.isLoading = false;
                        })
                        .catch(error => { this.cities = []; this.isLoading = false; });
                }
            }">
                
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Property Title *</label>
                    <input type="text" name="title" value="<?= esc($property['title']) ?>" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Type *</label>
                    <select name="property_type_id" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <?php foreach ($propertyTypes as $type): ?>
                            <option value="<?= esc($type->id) ?>" <?= $property['property_type_id'] == $type->id ? 'selected' : '' ?>>
                                <?= esc($type->type_name ?? $type->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Region / State *</label>
                    <select name="state_id" x-model="stateId" @change="fetchCities()" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <?php foreach ($states as $state): ?>
                            <option value="<?= esc($state->id) ?>"><?= esc($state->region_name ?? $state->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">City *</label>
                    <select name="city_id" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" :disabled="cities.length === 0 || isLoading">
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" :selected="city.id == <?= esc($property['city_id']) ?>" x-text="city.city_name || city.name"></option>
                        </template>
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
                
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Address *</label>
                    <input type="text" name="address_line_1" value="<?= esc($property['address_line_1']) ?>" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded"><?= esc($property['description']) ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div><label class="block text-xs mb-1 font-semibold">Beds</label><input type="number" name="bed" value="<?= esc($property['bed']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Baths</label><input type="number" name="bath" value="<?= esc($property['bath']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Land (m2)</label><input type="number" name="total_land_area" value="<?= esc($property['total_land_area']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
                <div><label class="block text-xs mb-1 font-semibold">Building (m2)</label><input type="number" name="usable_area" value="<?= esc($property['usable_area']) ?>" class="w-full px-3 py-2 border border-outline-variant rounded bg-surface"></div>
            </div>
        </div>

        <div class="border-t border-outline-variant pt-6">
            <h3 class="font-headline-md text-lg font-semibold mb-2">Features & Amenities</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php if (!empty($features)): ?>
                    <?php foreach ($features as $feature): ?>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-surface-bright p-2 rounded transition-colors">
                            <input type="checkbox" name="features[]" value="<?= esc($feature->id) ?>" <?= in_array($feature->id, $selectedFeatureIds) ? 'checked' : '' ?> class="w-4 h-4 text-primary bg-surface border-outline-variant rounded focus:ring-primary">
                            <span class="text-sm text-on-surface font-medium"><?= esc($feature->name ?? $feature->feature_name) ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="border-t border-outline-variant pt-6">
            <h3 class="font-headline-md text-lg font-semibold mb-4">Media & Legal Verification</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">Upload New SHM Document</label>
                    <input type="file" name="shm_document" accept=".pdf,.jpg" class="w-full p-2 border rounded bg-surface">
                    <p class="text-xs text-on-surface-variant mt-1">Leave empty to keep current document.</p>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t flex justify-end">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded font-semibold hover:opacity-90 transition-opacity">Update Listing</button>
        </div>
    </form>

    <!-- ALPINE.JS VALIDATION MODAL -->
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
</div>
<?= $this->endSection() ?>