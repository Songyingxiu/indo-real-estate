<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto mt-4 pb-12" x-data="{ 
    showDeleteModal: false, deleteUrl: '', deleteMessage: '',
    showEditTypeModal: false, editTypeId: '', editTypeNameEN: '', editTypeNameID: '',
    showEditStateModal: false, editStateId: '', editStateName: '',
    showEditCityModal: false, editCityId: '', editCityName: '', editCityStateId: '',
    showEditZipcodeModal: false, editZipcodeId: '', editZipcodeVal: '', editZipcodeCityId: '',
    showCreatePlanModal: false, showEditPlanModal: false,
    editPlanId: '', editPlanCode: '', editPlanNameEN: '', editPlanNameID: '', editPlanDescEN: '', editPlanDescID: '', editPlanPrice: 0, editPlanProp: 1, editPlanAgent: 0, editPlanPoi: 0, editPlanMsg: 0, editPlanEmail: 0,
    editPlanFeatures: [], 
    showCreatePoiModal: false,
    showEditFeatureCatModal: false, editFeatureCatId: '', editFeatureCatNameEN: '', editFeatureCatNameID: '',
    showEditFeatureModal: false, editFeatureId: '', editFeatureNameEN: '', editFeatureNameID: '', editFeatureCategoryId: '',
    translateText(text, targetProperty) {
        if (!text.trim()) return;
        fetch('http://127.0.0.1:5000/translate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                q: text,
                source: 'auto',
                target: 'id',
                format: 'text'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.translatedText) {
                if(this.hasOwnProperty(targetProperty)) {
                    this[targetProperty] = data.translatedText;
                } else {
                    let el = document.getElementById(targetProperty);
                    if(el) el.value = data.translatedText;
                }
            }
        })
        .catch(err => console.error('Translation error:', err));
    }
}">
    
    <div class="mb-stack-lg mt-4 flex justify-between items-end">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-primary mb-stack-sm">Master Data Configuration</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Manage core system taxonomies, locations, features, and packages.</p>
        </div>
    </div>

    <div class="flex flex-col gap-8 mt-4">
        
        <!-- SUBSCRIPTION PACKAGES MODULE -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg flex justify-between items-center">
                <h2 class="font-label-md text-label-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">workspace_premium</span> Subscription Packages
                </h2>
                <button @click="showCreatePlanModal = true" class="px-4 py-2 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">add</span> Create Package
                </button>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Code</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Package Name (EN)</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Price</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Limits (Props/Agents/POIs)</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($plans)): ?>
                            <?php foreach($plans as $plan): ?>
                                <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                    <td class="py-3 px-6 font-label-md font-bold text-primary"><?= esc($plan->code ?? $plan->package_code ?? 'N/A') ?></td>
                                    <td class="py-3 px-6 font-body-md text-on-surface">
                                        <span class="font-semibold block"><?= esc($plan->name_en ?? $plan->name) ?></span>
                                        <span class="text-[11px] text-on-surface-variant truncate max-w-[200px] block">
                                            <?php 
                                                $feats = json_decode($plan->features_en, true);
                                                echo is_array($feats) && !empty($feats) ? esc(implode(', ', $feats)) : esc($plan->description ?? 'No description');
                                            ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 font-body-md text-on-surface">Rp <?= number_format($plan->price, 0, ',', '.') ?></td>
                                    <td class="py-3 px-6 text-on-surface-variant text-sm flex gap-1 flex-wrap">
                                        <span class="bg-surface-container-high px-2 py-1 rounded"><?= esc($plan->max_properties ?? 1) ?> Props</span>
                                        <span class="bg-surface-container-high px-2 py-1 rounded"><?= esc($plan->max_agents ?? 0) ?> Sub-Agents</span>
                                        <span class="bg-primary-container text-on-primary-container px-2 py-1 rounded font-semibold"><?= esc($plan->max_pois ?? 0) ?> POIs</span>
                                    </td>
                                    <td class="py-3 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="
                                                showEditPlanModal = true; 
                                                editPlanId = <?= $plan->id ?>; 
                                                editPlanCode = '<?= esc(addslashes($plan->code ?? $plan->package_code ?? '')) ?>'; 
                                                editPlanNameEN = '<?= esc(addslashes($plan->name_en ?? $plan->name)) ?>'; 
                                                editPlanNameID = '<?= esc(addslashes($plan->name_id ?? $plan->name)) ?>'; 
                                                editPlanDescEN = '<?= esc(addslashes($plan->description ?? '')) ?>'; 
                                                editPlanDescID = '<?= esc(addslashes($plan->description ?? '')) ?>'; 
                                                editPlanPrice = <?= $plan->price ?>; 
                                                editPlanProp = <?= $plan->max_properties ?? 1 ?>; 
                                                editPlanAgent = <?= $plan->max_agents ?? 0 ?>; 
                                                editPlanPoi = <?= $plan->max_pois ?? 0 ?>; 
                                                editPlanMsg = <?= $plan->allow_messages ?? 0 ?>; 
                                                editPlanEmail = <?= $plan->allow_direct_email ?? 0 ?>;
                                                try { editPlanFeatures = JSON.parse('<?= esc(addslashes($plan->features_en ?? '[]')) ?>'); } catch(e) { editPlanFeatures = []; };
                                            " class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </button>
                                            <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-plan/' . $plan->id) ?>'; deleteMessage = 'Are you sure you want to permanently delete this package?';" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="py-6 text-center text-outline">No subscription packages found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant">
                <?php if (isset($pager)) : ?>
                    <?= $pager->links('plans', 'tailwind_pagination') ?>
                <?php endif ?>
            </div>
        </section>

        <!--  LOCATION TAXONOMIES OVERVIEW (3 COLUMNS)  -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Regions / States Module -->
            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">map</span> Regions
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-state') ?>" method="POST" class="flex flex-col gap-3">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <input name="name" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add region..." type="text"/>
                        <button type="submit" class="w-full h-10 bg-primary text-on-primary rounded text-sm font-semibold hover:bg-primary-container transition-colors">Add Region</button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($states)): ?>
                                <?php foreach($states as $state): ?>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-3 px-4 text-sm text-on-surface font-semibold"><?= esc($state->name) ?></td>
                                        <td class="py-3 px-4 text-right">
                                            <button type="button" @click="showEditStateModal = true; editStateId = <?= $state->id ?>; editStateName = '<?= esc(addslashes($state->name)) ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                            <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-state/' . $state->id) ?>'; deleteMessage = 'Delete this region?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="py-6 text-center text-outline text-sm">No regions found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant">
                    <?php if (isset($pager)) : ?>
                        <?= $pager->links('states', 'tailwind_pagination') ?>
                    <?php endif ?>
                </div>
            </section>

            <!-- Cities Module -->
            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">location_city</span> Cities
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-city') ?>" method="POST" class="flex flex-col gap-3">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <select name="state_id" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm bg-surface-container-lowest cursor-pointer">
                            <option value="" disabled selected>Select Region...</option>
                            <?php if(!empty($allStates)): foreach($allStates as $state): ?>
                                <option value="<?= $state->id ?>"><?= esc($state->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <input name="name" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add city..." type="text"/>
                        <button type="submit" class="w-full h-10 bg-primary text-on-primary rounded text-sm font-semibold hover:bg-primary-container transition-colors">Add City</button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($cities)): ?>
                                <?php foreach($cities as $city): ?>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-3 px-4">
                                            <span class="font-semibold block text-sm text-on-surface"><?= esc($city->name ?? '') ?></span>
                                            <span class="text-xs text-on-surface-variant"><?= esc($city->state_name ?? '') ?></span>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <button type="button" @click="showEditCityModal = true; editCityId = <?= $city->id ?>; editCityName = '<?= esc(addslashes($city->name ?? '')) ?>'; editCityStateId = '<?= $city->state_id ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                            <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-city/' . $city->id) ?>'; deleteMessage = 'Delete this city?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="py-6 text-center text-outline text-sm">No cities found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant">
                    <?php if (isset($pager)) : ?>
                        <?= $pager->links('cities', 'tailwind_pagination') ?>
                    <?php endif ?>
                </div>
            </section>

            <!-- Zipcodes Module -->
            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">mark_as_unread</span> Zipcodes
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-zipcode') ?>" method="POST" class="flex flex-col gap-3">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <select name="city_id" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm bg-surface-container-lowest cursor-pointer">
                            <option value="" disabled selected>Select City...</option>
                            <?php if(!empty($allCities)): foreach($allCities as $city): ?>
                                <option value="<?= $city->id ?>"><?= esc($city->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <input name="zipcode" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add code (e.g., 12310)..." type="text"/>
                        <button type="submit" class="w-full h-10 bg-primary text-on-primary rounded text-sm font-semibold hover:bg-primary-container transition-colors">Add Zipcode</button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($zipcodes)): ?>
                                <?php foreach($zipcodes as $zip): ?>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-3 px-4">
                                            <span class="font-semibold block text-sm text-primary"><?= esc($zip->zipcode ?? '') ?></span>
                                            <span class="text-xs text-on-surface-variant"><?= esc($zip->city_name ?? 'Unknown City') ?></span>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <button type="button" @click="showEditZipcodeModal = true; editZipcodeId = <?= $zip->id ?>; editZipcodeVal = '<?= esc(addslashes($zip->zipcode ?? '')) ?>'; editZipcodeCityId = '<?= $zip->city_id ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                            <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-zipcode/' . $zip->id) ?>'; deleteMessage = 'Delete this zip code?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="py-6 text-center text-outline text-sm">No zipcodes found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant">
                    <?php if (isset($pager)) : ?>
                        <?= $pager->links('zipcodes', 'tailwind_pagination') ?>
                    <?php endif ?>
                </div>
            </section>
        </div>

        <!-- Property Specifications Configurations -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">category</span> Property Types
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-type') ?>" method="POST" class="flex flex-col gap-2">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <input name="name_en" id="create_type_en" @blur="translateText($event.target.value, 'create_type_id')" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add new type (EN)..." type="text"/>
                        <input name="name_id" id="create_type_id" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Auto-translated (ID)..." type="text"/>
                        <button type="submit" class="h-10 px-6 bg-primary text-on-primary rounded text-sm font-semibold hover:bg-primary-container transition-colors mt-2">Add</button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($propertyTypes)): ?>
                                <?php foreach($propertyTypes as $type): ?>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-3 px-6 text-sm text-on-surface"><?= esc($type->name_en ?? $type->name) ?></td>
                                        <td class="py-3 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="showEditTypeModal = true; editTypeId = <?= $type->id ?>; editTypeNameEN = '<?= esc(addslashes($type->name_en ?? $type->name)) ?>'; editTypeNameID = '<?= esc(addslashes($type->name_id ?? $type->name)) ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                                <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-type/' . $type->id) ?>'; deleteMessage = 'Delete this property type?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="py-6 text-center text-outline text-sm">No property types found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant">
                    <?php if (isset($pager)) : ?>
                        <?= $pager->links('types', 'tailwind_pagination') ?>
                    <?php endif ?>
                </div>
            </section>

            <!-- FEATURE CATEGORIES MODULE -->
            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">sell</span> Feature Categories
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-feature-category') ?>" method="POST" class="flex flex-col gap-2">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <input name="name_en" id="create_feat_cat_en" @blur="translateText($event.target.value, 'create_feat_cat_id')" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="e.g. Interior, Exterior (EN)..." type="text"/>
                        <input name="name_id" id="create_feat_cat_id" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Auto-translated (ID)..." type="text"/>
                        <button type="submit" class="h-10 px-6 bg-primary text-on-primary rounded text-sm font-semibold hover:bg-primary-container transition-colors mt-2">Add</button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($featureCategories)): ?>
                                <?php foreach($featureCategories as $cat): ?>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-3 px-6 text-sm text-on-surface font-semibold"><?= esc($cat->name_en ?? $cat->name) ?></td>
                                        <td class="py-3 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="showEditFeatureCatModal = true; editFeatureCatId = <?= $cat->id ?>; editFeatureCatNameEN = '<?= esc(addslashes($cat->name_en ?? $cat->name)) ?>'; editFeatureCatNameID = '<?= esc(addslashes($cat->name_id ?? $cat->name)) ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                                <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-feature-category/' . $cat->id) ?>'; deleteMessage = 'Delete this category?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="py-6 text-center text-outline text-sm">No feature categories found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- FEATURES / AMENITIES MODULE -->
            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200 md:col-span-2" id="features">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">format_list_bulleted</span> Features (Amenities)
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-feature') ?>" method="POST" class="flex flex-col gap-3">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <select name="category_id" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm bg-surface-container-lowest cursor-pointer">
                            <option value="" disabled selected>Assign Category...</option>
                            <?php if(!empty($featureCategories)): foreach($featureCategories as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= esc($cat->name_en ?? $cat->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <input name="name_en" id="create_feat_en" @blur="translateText($event.target.value, 'create_feat_id')" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="e.g. Swimming Pool (EN)..." type="text"/>
                            <input name="name_id" id="create_feat_id" required class="w-full h-10 px-3 border border-outline-variant rounded text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Auto-translated (ID)..." type="text"/>
                        </div>
                        <button type="submit" class="w-full h-10 bg-primary text-on-primary rounded text-sm font-semibold hover:bg-primary-container transition-colors">Add Feature</button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($features)): ?>
                                <?php foreach($features as $feature): ?>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-3 px-6">
                                            <span class="font-semibold block text-sm text-on-surface"><?= esc($feature->name_en ?? $feature->name ?? $feature->feature_name) ?></span>
                                            <span class="text-xs text-on-surface-variant block">Category: <?= esc($feature->category_name ?? 'Uncategorized') ?></span>
                                        </td>
                                        <td class="py-3 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="showEditFeatureModal = true; editFeatureId = <?= $feature->id ?>; editFeatureNameEN = '<?= esc(addslashes($feature->name_en ?? $feature->name ?? $feature->feature_name)) ?>'; editFeatureNameID = '<?= esc(addslashes($feature->name_id ?? $feature->name ?? $feature->feature_name)) ?>'; editFeatureCategoryId = '<?= $feature->category_id ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                                <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-feature/' . $feature->id) ?>'; deleteMessage = 'Delete this feature?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="py-6 text-center text-outline text-sm">No features found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant">
                    <?php if (isset($pager)) : ?>
                        <?= $pager->links('features', 'tailwind_pagination') ?>
                    <?php endif ?>
                </div>
            </section>
        </div>

        <!-- POINTS OF INTEREST MODULE -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg flex justify-between items-center">
                <h2 class="font-label-md text-label-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">location_on</span> Points of Interest (POI)
                </h2>
                <button @click="showCreatePoiModal = true" class="px-4 py-2 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">add</span> Add POI
                </button>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Name</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Category</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Coordinates</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pois)): ?>
                            <?php foreach($pois as $poi): ?>
                                <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                    <td class="py-3 px-6 font-body-md font-semibold text-on-surface"><?= esc($poi->name) ?></td>
                                    <td class="py-3 px-6 font-body-md text-on-surface-variant">
                                        <span class="bg-surface-container-high px-2 py-1 rounded text-xs"><?= esc($poi->category) ?></span>
                                    </td>
                                    <td class="py-3 px-6 text-on-surface-variant text-sm">
                                        <span class="block">Lat: <?= esc($poi->latitude) ?></span>
                                        <span class="block">Lng: <?= esc($poi->longitude) ?></span>
                                    </td>
                                    <td class="py-3 px-6 text-right">
                                        <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-poi/' . $poi->id) ?>'; deleteMessage = 'Are you sure you want to delete this Point of Interest?';" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="py-6 text-center text-outline">No points of interest found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant">
                <?php if (isset($pager)) : ?>
                    <?= $pager->links('pois', 'tailwind_pagination') ?>
                <?php endif ?>
            </div>
        </section>

    </div>

    <!-- CREATE POI MODAL -->
    <div x-show="showCreatePoiModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showCreatePoiModal = false" class="bg-surface w-full max-w-lg rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Add Point of Interest</h2>
                <button type="button" @click="showCreatePoiModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="<?= base_url('admin/master-data/store-poi') ?>" method="POST" class="overflow-y-auto custom-scrollbar">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Name</label>
                        <input type="text" name="name" placeholder="e.g. Jakarta International School" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Category</label>
                        <select name="category" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface cursor-pointer">
                            <option value="" disabled selected>Select Category...</option>
                            <option value="School">School</option>
                            <option value="Station">Station</option>
                            <option value="Hospital">Hospital</option>
                            <option value="Mall">Mall</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Latitude</label>
                            <input type="number" step="any" name="latitude" placeholder="-6.2088" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Longitude</label>
                            <input type="number" step="any" name="longitude" placeholder="106.8456" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showCreatePoiModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition-all">Save POI</button>
                </div>
            </form>
        </div>
    </div>

    <!-- CREATE PACKAGE (PLAN) MODAL -->
    <div x-show="showCreatePlanModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showCreatePlanModal = false" class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Create Package Plan</h2>
                <button type="button" @click="showCreatePlanModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="<?= base_url('admin/master-data/store-plan') ?>" method="POST" class="overflow-y-auto custom-scrollbar">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1">Unique Code</label>
                        <input type="text" name="code" placeholder="e.g. FREE" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Package Name (EN)</label>
                        <input type="text" name="name_en" @blur="translateText($event.target.value, 'create_plan_name_id')" placeholder="e.g. Starter Free" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Package Name (ID)</label>
                        <input type="text" name="name_id" id="create_plan_name_id" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Description (EN)</label>
                        <textarea name="description_en" @blur="translateText($event.target.value, 'create_plan_desc_id')" rows="2" required placeholder="Enter plan summary..." class="w-full p-3 border border-outline-variant rounded bg-surface resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Description (ID)</label>
                        <textarea name="description_id" id="create_plan_desc_id" rows="2" required class="w-full p-3 border border-outline-variant rounded bg-surface resize-none"></textarea>
                    </div>
                    
                    <!-- Feature Checkboxes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1">Included Features</label>
                        <div class="grid grid-cols-2 gap-2 border border-outline-variant rounded p-3 bg-surface-container-lowest max-h-40 overflow-y-auto">
                            <?php if(!empty($allFeatures)): foreach($allFeatures as $feat): ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="features[]" value="<?= esc($feat->name_en ?? $feat->name) ?>" class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                    <span class="text-sm text-on-surface"><?= esc($feat->name_en ?? $feat->name) ?></span>
                                </label>
                            <?php endforeach; else: ?>
                                <p class="text-xs text-on-surface-variant">No features found. Please add features in the Features module first.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Price (IDR)</label>
                        <input type="number" name="price" value="0" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Max Properties Allowed</label>
                        <input type="number" name="max_properties" value="1" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Max Sub-Agents Allowed</label>
                        <input type="number" name="max_agents" value="0" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Max Custom POIs Allowed</label>
                        <input type="number" name="max_pois" value="0" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div class="flex flex-col gap-2 pt-4 md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="allow_messages" value="0">
                            <input type="checkbox" name="allow_messages" value="1" class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4"> 
                            <span class="text-sm font-medium">Allow In-App Message System</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="direct_email_inquiry" value="0">
                            <input type="checkbox" name="direct_email_inquiry" value="1" class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4"> 
                            <span class="text-sm font-medium">Forward Lead Alerts Direct to Email</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showCreatePlanModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition-all">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FIX: EDIT PACKAGE (PLAN) MODAL -->
    <div x-show="showEditPlanModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditPlanModal = false" class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Package Configurations</h2>
                <button type="button" @click="showEditPlanModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-plan/') ?>' + editPlanId" method="POST" class="overflow-y-auto custom-scrollbar">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1">Unique Code</label>
                        <input type="text" name="code" x-model="editPlanCode" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Package Name (EN)</label>
                        <input type="text" name="name_en" x-model="editPlanNameEN" @blur="translateText($event.target.value, 'editPlanNameID')" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Package Name (ID)</label>
                        <input type="text" name="name_id" x-model="editPlanNameID" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Description (EN)</label>
                        <textarea name="description_en" x-model="editPlanDescEN" @blur="translateText($event.target.value, 'editPlanDescID')" required rows="2" class="w-full p-3 border border-outline-variant rounded bg-surface resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Description (ID)</label>
                        <textarea name="description_id" x-model="editPlanDescID" required rows="2" class="w-full p-3 border border-outline-variant rounded bg-surface resize-none"></textarea>
                    </div>
                    
                    <!-- Feature Checkboxes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1">Included Features</label>
                        <div class="grid grid-cols-2 gap-2 border border-outline-variant rounded p-3 bg-surface-container-lowest max-h-40 overflow-y-auto">
                            <?php if(!empty($allFeatures)): foreach($allFeatures as $feat): ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="features[]" value="<?= esc($feat->name_en ?? $feat->name) ?>" x-model="editPlanFeatures" class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                    <span class="text-sm text-on-surface"><?= esc($feat->name_en ?? $feat->name) ?></span>
                                </label>
                            <?php endforeach; else: ?>
                                <p class="text-xs text-on-surface-variant">No features found.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Price (IDR)</label>
                        <input type="number" name="price" x-model="editPlanPrice" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Max Properties</label>
                        <input type="number" name="max_properties" x-model="editPlanProp" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Max Sub-Agents</label>
                        <input type="number" name="max_agents" x-model="editPlanAgent" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Max Custom POIs</label>
                        <input type="number" name="max_pois" x-model="editPlanPoi" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface">
                    </div>
                    <div class="flex flex-col gap-2 pt-4 md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="allow_messages" value="0">
                            <input type="checkbox" name="allow_messages" value="1" :checked="editPlanMsg == 1" @change="editPlanMsg = $event.target.checked ? 1 : 0" class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4"> 
                            <span class="text-sm font-medium">Allow In-App Message System</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="direct_email_inquiry" value="0">
                            <input type="checkbox" name="direct_email_inquiry" value="1" :checked="editPlanEmail == 1" @change="editPlanEmail = $event.target.checked ? 1 : 0" class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4"> 
                            <span class="text-sm font-medium">Forward Lead Alerts Direct to Email</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditPlanModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition-all">Update Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- UPDATE MODALS (REGIONS / CITIES / TYPE) -->
    <!-- Type Modal -->
    <div x-show="showEditTypeModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditTypeModal = false" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Property Type</h2>
                <button type="button" @click="showEditTypeModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-type/') ?>' + editTypeId" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Type Name (EN)</label>
                    <input type="text" name="name_en" x-model="editTypeNameEN" @blur="translateText($event.target.value, 'editTypeNameID')" required class="w-full h-10 px-3 mb-4 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    
                    <label class="block text-sm font-semibold text-on-surface mb-2">Type Name (ID)</label>
                    <input type="text" name="name_id" x-model="editTypeNameID" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditTypeModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Region Modal -->
    <div x-show="showEditStateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditStateModal = false" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Region Name</h2>
                <button type="button" @click="showEditStateModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-state/') ?>' + editStateId" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Region Name</label>
                    <input type="text" name="name" x-model="editStateName" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditStateModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- City Modal -->
    <div x-show="showEditCityModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditCityModal = false" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Location</h2>
                <button type="button" @click="showEditCityModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-city/') ?>' + editCityId" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Assigned Region</label>
                        <select name="state_id" x-model="editCityStateId" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none cursor-pointer">
                            <?php if(!empty($allStates)): foreach($allStates as $state): ?>
                                <option value="<?= $state->id ?>"><?= esc($state->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">City Name</label>
                        <input type="text" name="name" x-model="editCityName" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditCityModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Zipcode Modal -->
    <div x-show="showEditZipcodeModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditZipcodeModal = false" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Zipcode</h2>
                <button type="button" @click="showEditZipcodeModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-zipcode/') ?>' + editZipcodeId" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Assigned City</label>
                        <select name="city_id" x-model="editZipcodeCityId" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none cursor-pointer">
                            <?php if(!empty($allCities)): foreach($allCities as $city): ?>
                                <option value="<?= $city->id ?>"><?= esc($city->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Zipcode</label>
                        <input type="text" name="zipcode" x-model="editZipcodeVal" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditZipcodeModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Feature Category Edit Modal -->
    <div x-show="showEditFeatureCatModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditFeatureCatModal = false" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Feature Category</h2>
                <button type="button" @click="showEditFeatureCatModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-feature-category/') ?>' + editFeatureCatId" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Category Name (EN)</label>
                    <input type="text" name="name_en" x-model="editFeatureCatNameEN" @blur="translateText($event.target.value, 'editFeatureCatNameID')" required class="w-full h-10 px-3 mb-4 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    
                    <label class="block text-sm font-semibold text-on-surface mb-2">Category Name (ID)</label>
                    <input type="text" name="name_id" x-model="editFeatureCatNameID" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditFeatureCatModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Feature Edit Modal -->
    <div x-show="showEditFeatureModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditFeatureModal = false" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Feature</h2>
                <button type="button" @click="showEditFeatureModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-feature/') ?>' + editFeatureId" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="p-6 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Assigned Category</label>
                        <select name="category_id" x-model="editFeatureCategoryId" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none cursor-pointer">
                            <?php if(!empty($featureCategories)): foreach($featureCategories as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= esc($cat->name_en ?? $cat->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Feature Name (EN)</label>
                        <input type="text" name="name_en" x-model="editFeatureNameEN" @blur="translateText($event.target.value, 'editFeatureNameID')" required class="w-full h-10 px-3 mb-4 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                        
                        <label class="block text-sm font-semibold text-on-surface mb-2">Feature Name (ID)</label>
                        <input type="text" name="name_id" x-model="editFeatureNameID" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditFeatureModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Universal Delete Modal -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDeleteModal = false" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-[32px]">warning</span></div>
                <h2 class="text-xl font-bold text-on-surface mb-2">Confirm Deletion</h2>
                <p class="text-sm text-on-surface-variant" x-text="deleteMessage"></p>
            </div>
            <div class="px-6 py-4 flex justify-between gap-3 bg-surface-container-lowest border-t border-outline-variant">
                <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2 border border-outline-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <button type="submit" class="w-full px-4 py-2 bg-error text-on-error rounded font-semibold hover:opacity-90 transition">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>