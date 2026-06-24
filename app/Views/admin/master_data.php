<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto mt-4 pb-12" x-data="{ 
    showDeleteModal: false, deleteUrl: '', deleteMessage: '',
    showEditTypeModal: false, editTypeId: '', editTypeName: '',
    showEditStateModal: false, editStateId: '', editStateName: '',
    showEditCityModal: false, editCityId: '', editCityName: '', editCityStateId: ''
}">
    <div class="mb-stack-lg mt-4">
        <h1 class="font-headline-lg text-headline-lg text-primary mb-stack-sm">Master Data Configuration</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Manage core system taxonomies like locations and property types.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="flex flex-col gap-8 mt-4">
        
        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">category</span> Property Types
                </h2>
                <form action="<?= base_url('admin/master-data/store-type') ?>" method="POST" class="flex gap-2">
                    <input name="name" required class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add new type (e.g., Warehouse)..." type="text"/>
                    <button type="submit" class="h-10 px-6 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-colors flex items-center gap-1 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">add</span> Add New
                    </button>
                </form>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-6 font-label-md text-label-md text-on-surface-variant font-medium">Type Name</th>
                            <th class="py-3 px-6 font-label-md text-label-md text-on-surface-variant font-medium text-right w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($propertyTypes)): foreach($propertyTypes as $type): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                <td class="py-3 px-6 font-body-md text-body-md text-on-surface">
                                    <?= esc($type->type_name ?? $type->name ?? 'Unknown Type') ?>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" @click="showEditTypeModal = true; editTypeId = <?= $type->id ?>; editTypeName = '<?= esc(addslashes($type->type_name ?? $type->name)) ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-type/' . $type->id) ?>'; deleteMessage = 'Are you sure you want to permanently delete this property type?';" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" class="py-6 text-center text-outline">No property types found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php if ($pager) : ?>
                    <?= $pager->links('types', 'tailwind') ?>
                <?php endif ?>
                
            </div>
        </section>

        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">map</span> Regions / States
                </h2>
                <form action="<?= base_url('admin/master-data/store-state') ?>" method="POST" class="flex gap-2">
                    <input name="name" required class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add region (e.g., Jawa Timur)..." type="text"/>
                    <button type="submit" class="h-10 px-6 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-colors flex items-center gap-1 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">add</span> Add
                    </button>
                </form>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-6 font-label-md text-label-md text-on-surface-variant font-medium">Region Name</th>
                            <th class="py-3 px-6 font-label-md text-label-md text-on-surface-variant font-medium text-right w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($states)): foreach($states as $state): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                <td class="py-3 px-6 font-body-md text-body-md text-on-surface flex items-center gap-3">
                                    <span class="font-semibold"><?= esc($state->name) ?></span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-fixed/30 text-on-primary-container border border-primary-fixed">Active</span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" @click="showEditStateModal = true; editStateId = <?= $state->id ?>; editStateName = '<?= esc(addslashes($state->name)) ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-state/' . $state->id) ?>'; deleteMessage = 'Are you sure you want to delete this region? WARNING: This might affect cities assigned to it.';" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" class="py-6 text-center text-outline">No regions found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php if ($pager) : ?>
                    <?= $pager->links('states', 'tailwind') ?>
                <?php endif ?>
                
            </div>
        </section>

        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">location_on</span> Supported Locations
                </h2>
                <form action="<?= base_url('admin/master-data/store-city') ?>" method="POST" class="flex flex-col sm:flex-row gap-3">
                    <div class="sm:w-1/3">
                        <select name="state_id" required class="w-full h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest cursor-pointer">
                            <option value="" disabled selected>Select Region...</option>
                            <?php if(!empty($states)): foreach($states as $state): ?>
                                <option value="<?= $state->id ?>"><?= esc($state->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="flex gap-2 sm:w-2/3">
                        <input name="name" required class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add new city (e.g., Denpasar)..." type="text"/>
                        <button type="submit" class="h-10 px-6 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-colors flex items-center justify-center gap-1 whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">add</span> Add
                        </button>
                    </div>
                </form>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-6 font-label-md text-label-md text-on-surface-variant font-medium">City, Region</th>
                            <th class="py-3 px-6 font-label-md text-label-md text-on-surface-variant font-medium text-right w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($cities)): foreach($cities as $city): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                <td class="py-3 px-6 font-body-md text-body-md text-on-surface flex items-center gap-2">
                                    <span class="font-semibold"><?= esc($city->name ?? 'Unknown City') ?></span>
                                    <span class="text-on-surface-variant text-sm border-r border-outline-variant pr-3">, <?= esc($city->state_name ?? 'Unknown Region') ?></span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-fixed/30 text-on-primary-container border border-primary-fixed">Active</span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" @click="showEditCityModal = true; editCityId = <?= $city->id ?>; editCityName = '<?= esc(addslashes($city->name)) ?>'; editCityStateId = '<?= $city->state_id ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-city/' . $city->id) ?>'; deleteMessage = 'Are you sure you want to permanently delete this location?';" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" class="py-6 text-center text-outline">No locations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php if ($pager) : ?>
                    <?= $pager->links('cities', 'tailwind') ?>
                <?php endif ?>
                
            </div>
        </section>

    </div>

    <div x-show="showEditTypeModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditTypeModal = false" x-show="showEditTypeModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface flex items-center gap-2"><span class="material-symbols-outlined">edit</span> Edit Property Type</h2>
                <button type="button" @click="showEditTypeModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-type/') ?>' + editTypeId" method="POST">
                <div class="p-6">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Type Name</label>
                    <input type="text" name="name" x-model="editTypeName" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditTypeModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditStateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditStateModal = false" x-show="showEditStateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface flex items-center gap-2"><span class="material-symbols-outlined">edit</span> Edit Region</h2>
                <button type="button" @click="showEditStateModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-state/') ?>' + editStateId" method="POST">
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

    <div x-show="showEditCityModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditCityModal = false" x-show="showEditCityModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface flex items-center gap-2"><span class="material-symbols-outlined">edit</span> Edit Location</h2>
                <button type="button" @click="showEditCityModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-city/') ?>' + editCityId" method="POST">
                <div class="p-6 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Assigned Region</label>
                        <select name="state_id" x-model="editCityStateId" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none cursor-pointer">
                            <?php if(!empty($states)): foreach($states as $state): ?>
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

    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDeleteModal = false" x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px]">warning</span>
                </div>
                <h2 class="text-xl font-bold text-on-surface mb-2">Confirm Deletion</h2>
                <p class="text-sm text-on-surface-variant" x-text="deleteMessage"></p>
            </div>
            <div class="px-6 py-4 flex justify-between gap-3 bg-surface-container-lowest border-t border-outline-variant">
                <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    <button type="submit" class="w-full px-4 py-2 bg-error text-on-error rounded font-semibold hover:opacity-90 transition">Delete</button>
                </form>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>