<?= $this->include('admin/layout/header') ?>
<?= $this->include('admin/layout/sidebar') ?>

<main class="md:ml-64 p-6 bg-background min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-on-background">Advertisement Management</h1>
        <a href="<?= base_url('admin/advertisements/create') ?>" class="bg-primary text-on-primary px-4 py-2 rounded shadow hover:bg-primary-container hover:text-on-primary-container transition-colors">
            + Add New Ad
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant text-sm border-b border-outline-variant">
                    <th class="p-4 font-semibold">Image</th>
                    <th class="p-4 font-semibold">Title</th>
                    <th class="p-4 font-semibold">Placement</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold">Duration</th>
                    <th class="p-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($advertisements)): ?>
                    <?php foreach ($advertisements as $ad): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest">
                            <td class="p-4">
                                <img src="<?= base_url($ad->image_path) ?>" alt="<?= esc($ad->title) ?>" class="w-16 h-12 object-cover rounded border">
                            </td>
                            <td class="p-4 font-medium text-on-surface"><?= esc($ad->title) ?></td>
                            <td class="p-4 text-on-surface-variant capitalize"><?= str_replace('_', ' ', esc($ad->placement)) ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-bold <?= $ad->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= esc($ad->status) ?>
                                </span>
                            </td>
                            <td class="p-4 text-sm text-on-surface-variant">
                                <?= $ad->start_date ? date('M d, Y', strtotime($ad->start_date)) : 'N/A' ?> - 
                                <?= $ad->end_date ? date('M d, Y', strtotime($ad->end_date)) : 'N/A' ?>
                            </td>
                            <td class="p-4">
                                <a href="<?= base_url('admin/advertisements/edit/' . $ad->id) ?>" class="text-primary hover:underline mr-3">Edit</a>
                                <a href="<?= base_url('admin/advertisements/delete/' . $ad->id) ?>" class="text-error hover:underline" onclick="return confirm('Are you sure you want to delete this ad?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">No advertisements found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>