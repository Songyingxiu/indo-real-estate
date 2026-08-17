<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
$getErr = function($field) { return session('errors.' . $field); };
$errClass = function($err) { return $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'border-outline-variant focus:border-primary bg-surface'; };
$errBox = function($err) { return $err ? '<div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]"><span class="material-symbols-outlined text-[16px] mt-0.5">warning</span>'.esc($err).'</div>' : ''; };
?>

<div class="w-full px-6 py-8" x-data="{
    translateText(text, targetId) {
        if (!text.trim()) return;
        fetch('http://127.0.0.1:5000/translate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ q: text, source: 'auto', target: 'id', format: 'text' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.translatedText) {
                document.getElementById(targetId).value = data.translatedText;
            }
        })
        .catch(err => console.error('Translation error:', err));
    }
}">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-on-background"><?= isset($ad) ? 'Edit Advertisement' : 'Create Advertisement' ?></h1>
            <p class="text-on-surface-variant mt-1">Fill in the details below to configure your advertisement block.</p>
        </div>

        <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-8">
            <form action="<?= base_url('admin/advertisements/save') ?>" method="POST" enctype="multipart/form-data" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= isset($ad) ? $ad->id : '' ?>">

                <?php if (session()->has('errors')): ?>
                    <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 mb-6 rounded shadow-sm text-sm">
                        <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Ad Title (EN) <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('title_en'); ?>
                        <input type="text" name="title_en" id="title_en" value="<?= old('title_en', isset($ad) ? ($ad->title_en ?? $ad->title) : '') ?>" @blur="translateText($event.target.value, 'title_id')" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none <?= $errClass($err) ?>" placeholder="e.g., Summer Sale" required>
                        <?= $errBox($err) ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Ad Title (ID) <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('title_id'); ?>
                        <input type="text" name="title_id" id="title_id" value="<?= old('title_id', isset($ad) ? ($ad->title_id ?? $ad->title) : '') ?>" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none <?= $errClass($err) ?>" required>
                        <?= $errBox($err) ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Description (EN) <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('description_en'); ?>
                        <textarea name="description_en" id="description_en" rows="4" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none resize-y <?= $errClass($err) ?>" placeholder="Enter the full advertisement details here..." @blur="translateText($event.target.value, 'description_id')" required><?= old('description_en', isset($ad) ? ($ad->description_en ?? $ad->description) : '') ?></textarea>
                        <?= $errBox($err) ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Description (ID) <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('description_id'); ?>
                        <textarea name="description_id" id="description_id" rows="4" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none resize-y <?= $errClass($err) ?>" required><?= old('description_id', isset($ad) ? ($ad->description_id ?? $ad->description) : '') ?></textarea>
                        <?= $errBox($err) ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Placement Area <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('placement'); ?>
                        <select name="placement" required class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none appearance-none <?= $errClass($err) ?>">
                            <option value="home_banner" <?= old('placement', isset($ad) ? $ad->placement : '') == 'home_banner' ? 'selected' : '' ?>>Home Banner</option>
                            <option value="sidebar" <?= old('placement', isset($ad) ? $ad->placement : '') == 'sidebar' ? 'selected' : '' ?>>Sidebar Panel</option>
                            <option value="property_list" <?= old('placement', isset($ad) ? $ad->placement : '') == 'property_list' ? 'selected' : '' ?>>Property List Inline</option>
                        </select>
                        <?= $errBox($err) ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Status <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('status'); ?>
                        <select name="status" required class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none appearance-none <?= $errClass($err) ?>">
                            <option value="Active" <?= old('status', isset($ad) ? $ad->status : '') == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= old('status', isset($ad) ? $ad->status : '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <?= $errBox($err) ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Start Date <span class="text-outline font-normal">(Optional)</span></label>
                        <?php $err = $getErr('start_date'); ?>
                        <input type="date" name="start_date" value="<?= old('start_date', isset($ad) ? $ad->start_date : '') ?>" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none <?= $errClass($err) ?>">
                        <?= $errBox($err) ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">End Date <span class="text-outline font-normal">(Optional)</span></label>
                        <?php $err = $getErr('end_date'); ?>
                        <input type="date" name="end_date" value="<?= old('end_date', isset($ad) ? $ad->end_date : '') ?>" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none <?= $errClass($err) ?>">
                        <?= $errBox($err) ?>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Advertisement Image <?= isset($ad) ? '<span class="text-outline font-normal">(Upload new to replace)</span>' : '<span class="text-[#c9302c]">*</span>' ?></label>
                    <?php $err = $getErr('image'); ?>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer transition-all group <?= $err ? 'border-[#c9302c] bg-[#fff8f8]' : 'border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low hover:border-primary' ?>">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <div class="p-4 bg-primary-container text-on-primary-container rounded-full mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                                </div>
                                <p class="mb-1 text-sm text-on-surface-variant"><span class="font-bold text-primary">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-outline">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" name="image" accept="image/*" class="hidden" <?= isset($ad) ? '' : 'required' ?> onchange="updateFileName(this)" />
                        </label>
                    </div>
                    <?= $errBox($err) ?>
                    <p id="file-name-display" class="text-sm text-primary font-medium mt-2 hidden text-center"></p>

                    <?php if(isset($ad) && $ad->image_path): ?>
                        <div class="mt-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-16 bg-white rounded border border-outline-variant overflow-hidden">
                                    <img src="<?= base_url($ad->image_path) ?>" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-on-surface">Current Active Image</p>
                                    <p class="text-xs text-on-surface-variant break-all"><?= basename($ad->image_path) ?></p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-outline-variant">
                    <a href="<?= base_url('admin/advertisements') ?>" class="px-6 py-2.5 font-medium border border-outline-variant text-on-surface rounded-lg hover:bg-surface-container-low transition-colors">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 font-medium bg-primary text-on-primary rounded-lg shadow hover:bg-opacity-90 transition-opacity">Save Advertisement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            display.textContent = 'Selected file: ' + input.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }
</script>
<?= $this->endSection() ?>