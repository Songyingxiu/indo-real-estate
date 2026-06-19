<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="font-headline-lg text-[28px] font-bold text-on-surface">Property Listings</h2>
            <p class="text-on-surface-variant font-body-md mt-1">Manage and view your real estate portfolio.</p>
        </div>
        <a href="<?= base_url('admin/properties/create') ?>" class="bg-primary-container text-on-primary px-6 py-2.5 rounded font-label-md font-semibold hover:bg-primary transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Add Property
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2 font-body-md">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant font-label-md text-[14px]">
                        <th class="py-4 px-6 font-semibold">Property Title</th>
                        <th class="py-4 px-6 font-semibold">Type</th>
                        <th class="py-4 px-6 font-semibold">Price (IDR)</th>
                        <th class="py-4 px-6 font-semibold">Approval Status</th>
                        <th class="py-4 px-6 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-[14px] text-on-surface">
                    <?php if (!empty($properties) && is_array($properties)): ?>
                        <?php foreach ($properties as $property): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-container-low/50 transition-colors">
                                <td class="py-4 px-6 font-semibold text-primary"><?= esc($property['title']) ?></td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-[12px] font-semibold bg-surface-container border border-outline-variant text-on-surface-variant">
                                        <?= esc($property['listing_type']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">Rp <?= number_format($property['tax_price'], 0, ',', '.') ?></td>
                                <td class="py-4 px-6">
                                    <?php 
                                        $badgeColor = 'bg-surface-container text-on-surface';
                                        // Option 1 applied here: $property['approval_status']
                                        if ($property['approval_status'] == 'Published') $badgeColor = 'bg-[#c4eed0] text-[#0d652d]';
                                        if ($property['approval_status'] == 'Pending Review') $badgeColor = 'bg-[#fef7e0] text-[#b06000]';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-[12px] font-semibold <?= $badgeColor ?>">
                                        <?= esc($property['approval_status']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right flex justify-end gap-2">
                                    <button class="p-2 text-outline hover:text-primary-container transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <button class="p-2 text-outline hover:text-[#ba1a1a] transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-[48px] text-outline-variant mb-2">real_estate_agent</span>
                                    <p>No properties found. Start by adding a new listing!</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>