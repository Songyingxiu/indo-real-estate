<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="max-w-container-max mx-auto mt-4">
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter mt-4">
        
        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">category</span> Property Types
                </h2>
                
                <form action="<?= base_url('admin/master-data/store-type') ?>" method="POST" class="flex gap-2">
                    <input name="name" required class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add new type (e.g., Warehouse)..." type="text"/>
                    <button type="submit" class="h-10 px-4 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">add</span> Add New
                    </button>
                </form>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium">Type Name</th>
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium text-right w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($propertyTypes)): foreach($propertyTypes as $type): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface">
                                    <?= esc($type->type_name ?? $type->name ?? 'Unknown Type') ?>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                        <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" class="py-4 text-center text-outline">No property types found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">location_on</span> Supported Locations
                </h2>
                
                <form action="<?= base_url('admin/master-data/store-city') ?>" method="POST" class="flex gap-2">
                    <input name="name" required class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add new location (e.g., Bali)..." type="text"/>
                    <button type="submit" class="h-10 px-4 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">add</span> Add New
                    </button>
                </form>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium">City / Region</th>
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium text-right w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($cities)): foreach($cities as $city): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                <td class="py-3 px-4 font-body-md text-body-md text-on-surface flex items-center gap-2">
                                    <?= esc($city->city_name ?? $city->name ?? 'Unknown City') ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary-fixed/30 text-on-primary-container border border-primary-fixed">Active</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                        <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" class="py-4 text-center text-outline">No locations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>

<?= $this->endSection() ?>