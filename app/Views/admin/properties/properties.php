<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="font-headline-lg text-[28px] font-bold text-on-surface">Property Listings</h2>
            <p class="text-on-surface-variant">Manage and view your real estate portfolio.</p>
        </div>
        <a href="<?= base_url('admin/properties/create') ?>" class="bg-primary-container text-on-primary px-6 py-2.5 rounded font-label-md font-semibold hover:bg-primary transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Add Property
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined mt-0.5">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-error-container text-on-error-container p-4 rounded mb-6 border flex items-center gap-2">
            <span class="material-symbols-outlined mt-0.5">warning</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant font-label-md text-[14px]">
                    <th class="py-4 px-6 font-semibold">Property Title</th>
                    <th class="py-4 px-6 font-semibold">Type</th>
                    <th class="py-4 px-6 font-semibold">Price (IDR)</th>
                    <th class="py-4 px-6 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-[14px] text-on-surface">
                <?php if (!empty($properties) && is_array($properties)): ?>
                    <?php foreach ($properties as $property): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-container-low/50">
                            <td class="py-4 px-6 font-semibold text-primary"><?= esc($property['title']) ?></td>
                            <td class="py-4 px-6"><span class="px-3 py-1 rounded-full bg-surface-container"><?= esc($property['listing_type']) ?></span></td>
                            <td class="py-4 px-6">Rp <?= number_format($property['tax_price'], 0, ',', '.') ?></td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <?php 
                                    $badge = 'bg-surface-container text-on-surface';
                                    if ($property['approval_status'] == 'Published') $badge = 'bg-[#c4eed0] text-[#0d652d]';
                                    if ($property['approval_status'] == 'Pending Review') $badge = 'bg-[#fef7e0] text-[#b06000]';
                                    if ($property['approval_status'] == 'Draft') $badge = 'bg-surface-container-high text-on-surface-variant border border-outline-variant';
                                ?>
                                <span class="px-3 py-1 rounded-full <?= $badge ?>"><?= esc($property['approval_status']) ?></span>
                                
                                <a href="<?= base_url('admin/properties/edit/' . $property['id']) ?>" class="ml-3 text-primary hover:text-primary-container transition p-1 inline-block" title="Edit Property">
                                    <span class="material-symbols-outlined text-[20px] align-middle">edit</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="py-8 text-center">No properties found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>