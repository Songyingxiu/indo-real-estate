<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>
<div class="w-full px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-on-background">Advertisement Management</h1>
        <a href="<?= base_url('admin/advertisements/create') ?>" class="bg-primary text-on-primary px-4 py-2 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2 font-medium">
            <span class="material-symbols-outlined text-sm">add</span> Add New Ad
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden w-full">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-sm border-b border-outline-variant">
                        <th class="p-4 font-semibold whitespace-nowrap">Image</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Title (EN)</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Placement</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Status</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Duration</th>
                        <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($advertisements)): ?>
                        <?php foreach ($advertisements as $ad): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="p-4">
                                    <div class="w-20 h-14 bg-surface-container-low rounded border border-outline-variant overflow-hidden flex items-center justify-center">
                                        <img src="<?= base_url($ad->image_path) ?>" alt="<?= esc($ad->title_en ?? $ad->title) ?>" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="p-4 font-medium text-on-surface"><?= esc($ad->title_en ?? $ad->title) ?></td>
                                <td class="p-4 text-on-surface-variant capitalize"><?= str_replace('_', ' ', esc($ad->placement)) ?></td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold <?= $ad->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= esc($ad->status) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-on-surface-variant">
                                    <?= $ad->start_date ? date('M d, Y', strtotime($ad->start_date)) : '<span class="text-outline">N/A</span>' ?> <br> 
                                    <span class="text-xs text-outline">to</span> <br>
                                    <?= $ad->end_date ? date('M d, Y', strtotime($ad->end_date)) : '<span class="text-outline">N/A</span>' ?>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="<?= base_url('admin/advertisements/edit/' . $ad->id) ?>" class="text-primary hover:bg-primary-container hover:text-on-primary-container px-3 py-1 rounded-md transition-colors mr-2 font-medium">Edit</a>
                                    <a href="<?= base_url('admin/advertisements/delete/' . $ad->id) ?>" class="text-error hover:bg-error-container hover:text-on-error-container px-3 py-1 rounded-md transition-colors font-medium" onclick="return confirm('Are you sure you want to delete this ad?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-2 text-outline">ad_off</span>
                                <p>No advertisements found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>