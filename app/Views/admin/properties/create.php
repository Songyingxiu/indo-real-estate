<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in">
    <h2 class="font-headline-lg text-[28px] font-bold text-on-surface mb-6">Create New Listing</h2>
    <form action="<?= base_url('admin/properties/store') ?>" method="POST" enctype="multipart/form-data" class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant p-6 space-y-8">
        
        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4">Property Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ 
                stateId: '', 
                cities: [], 
                isLoading: false,
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
                        });
                }
            }">
                
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Property Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>

                <div>
                    <label class="block font-semibold mb-2">Property Type *</label>
                    <select name="property_type_id" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <option value="" disabled selected>Select a type...</option>
                        <?php if (!empty($propertyTypes)): ?>
                            <?php foreach ($propertyTypes as $type): ?>
                                <option value="<?= esc($type->id) ?>"><?= esc($type->type_name ?? $type->name) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No property types found</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Region / State *</label>
                    <select name="state_id" id="state_id" x-model="stateId" @change="fetchCities()" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <option value="" disabled selected>Select a region...</option>
                        <?php if (!empty($states)): ?>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= esc($state->id) ?>"><?= esc($state->region_name ?? $state->name) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No regions found</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">
                        City * <span x-show="isLoading" style="display: none;" class="text-xs text-primary font-normal ml-2 animate-pulse">Fetching cities...</span>
                    </label>
                    <select name="city_id" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded" :disabled="cities.length === 0">
                        <option value="" disabled selected>Select a city...</option>
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" x-text="city.city_name || city.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Listing Type *</label>
                    <select name="listing_type" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                        <option value="Sale">For Sale</option>
                        <option value="Rent">For Rent</option>
                    </select>
                </div>
                
                <div>
                    <label class="block font-semibold mb-2">Asking Price (IDR) *</label>
                    <input type="number" name="tax_price" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Address *</label>
                    <input type="text" name="address_line_1" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div><label class="block text-xs mb-1">Beds</label><input type="number" name="bed" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-xs mb-1">Baths</label><input type="number" name="bath" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-xs mb-1">Land (m2)</label><input type="number" name="total_land_area" class="w-full px-3 py-2 border rounded"></div>
                <div><label class="block text-xs mb-1">Building (m2)</label><input type="number" name="usable_area" class="w-full px-3 py-2 border rounded"></div>
            </div>
        </div>

        <div>
            <h3 class="font-headline-md text-lg font-semibold mb-4">Media & Legal Verification</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">Photos (Max 5)</label>
                    <input type="file" name="property_images[]" multiple accept="image/*" class="w-full p-2 border rounded bg-surface">
                </div>
                <div>
                    <label class="block font-semibold mb-2">SHM Document</label>
                    <input type="file" name="shm_document" accept=".pdf,.jpg" class="w-full p-2 border rounded bg-surface">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t flex justify-end">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded font-semibold">Submit for Admin Review</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>