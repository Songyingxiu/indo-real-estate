<?= $this->include('admin/layout/header') ?>
<?= $this->include('admin/layout/sidebar') ?>

<main class="md:ml-64 p-6 bg-background min-h-screen">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-on-background"><?= isset($ad) ? 'Edit Advertisement' : 'Create Advertisement' ?></h1>
    </div>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-surface border border-outline-variant rounded-lg p-6 max-w-3xl">
        <form action="<?= base_url('admin/advertisements/save') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= isset($ad) ? $ad->id : '' ?>">

            <div class="mb-4">
                <label class="block text-sm font-medium text-on-surface mb-1">Ad Title</label>
                <input type="text" name="title" value="<?= old('title', isset($ad) ? $ad->title : '') ?>" class="w-full p-2 border border-outline-variant rounded bg-surface focus:ring-primary focus:border-primary" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-on-surface mb-1">Target URL (Link)</label>
                <input type="url" name="target_url" value="<?= old('target_url', isset($ad) ? $ad->target_url : '') ?>" class="w-full p-2 border border-outline-variant rounded bg-surface focus:ring-primary focus:border-primary" placeholder="https://example.com">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Placement</label>
                    <select name="placement" class="w-full p-2 border border-outline-variant rounded bg-surface focus:ring-primary focus:border-primary">
                        <option value="home_banner" <?= old('placement', isset($ad) ? $ad->placement : '') == 'home_banner' ? 'selected' : '' ?>>Home Banner</option>
                        <option value="sidebar" <?= old('placement', isset($ad) ? $ad->placement : '') == 'sidebar' ? 'selected' : '' ?>>Sidebar</option>
                        <option value="property_list" <?= old('placement', isset($ad) ? $ad->placement : '') == 'property_list' ? 'selected' : '' ?>>Property List</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Status</label>
                    <select name="status" class="w-full p-2 border border-outline-variant rounded bg-surface focus:ring-primary focus:border-primary">
                        <option value="Active" <?= old('status', isset($ad) ? $ad->status : '') == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= old('status', isset($ad) ? $ad->status : '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Start Date</label>
                    <input type="date" name="start_date" value="<?= old('start_date', isset($ad) ? $ad->start_date : '') ?>" class="w-full p-2 border border-outline-variant rounded bg-surface">
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">End Date</label>
                    <input type="date" name="end_date" value="<?= old('end_date', isset($ad) ? $ad->end_date : '') ?>" class="w-full p-2 border border-outline-variant rounded bg-surface">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-on-surface mb-1">Upload Image <?= isset($ad) ? '(Leave blank to keep current)' : '*' ?></label>
                <input type="file" name="image" accept="image/*" class="w-full p-2 border border-outline-variant rounded bg-surface" <?= isset($ad) ? '' : 'required' ?>>
                <?php if(isset($ad) && $ad->image_path): ?>
                    <div class="mt-2">
                        <img src="<?= base_url($ad->image_path) ?>" class="h-24 rounded border">
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= base_url('admin/advertisements') ?>" class="px-4 py-2 border border-outline-variant text-on-surface rounded hover:bg-surface-container-low">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded hover:bg-primary-container hover:text-on-primary-container">Save Advertisement</button>
            </div>
        </form>
    </div>
</main>