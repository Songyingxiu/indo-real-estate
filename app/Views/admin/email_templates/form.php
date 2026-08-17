<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
$getErr = function($field) { return session('errors.' . $field); };
$errClass = function($err) { return $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'border-outline-variant focus:border-primary bg-surface'; };
$errBox = function($err) { return $err ? '<div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]"><span class="material-symbols-outlined text-[16px] mt-0.5">warning</span>'.esc($err).'</div>' : ''; };
?>

<div class="w-full px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-on-background"><?= isset($template) ? 'Edit Email Template' : 'Create Email Template' ?></h1>
            <p class="text-on-surface-variant mt-1">Configure automated email layouts and dynamic variables.</p>
        </div>

        <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-8">
            <form action="<?= base_url('admin/email-templates/save') ?>" method="POST" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= isset($template) ? $template->id : '' ?>">

                <?php if (session()->has('errors')): ?>
                    <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 mb-6 rounded shadow-sm text-sm">
                        <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Template Name <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('name'); ?>
                        <input type="text" name="name" value="<?= old('name', isset($template) ? $template->name : '') ?>" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none <?= $errClass($err) ?>" placeholder="e.g., Welcome Email" required>
                        <p class="text-xs text-on-surface-variant mt-1">Internal name used to identify this template.</p>
                        <?= $errBox($err) ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-2">Status <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('status'); ?>
                        <select name="status" required class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none appearance-none <?= $errClass($err) ?>">
                            <option value="Active" <?= old('status', isset($template) ? $template->status : '') == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= old('status', isset($template) ? $template->status : '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <?= $errBox($err) ?>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Email Subject <span class="text-[#c9302c]">*</span></label>
                    <?php $err = $getErr('subject'); ?>
                    <input type="text" name="subject" value="<?= old('subject', isset($template) ? $template->subject : '') ?>" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none <?= $errClass($err) ?>" placeholder="e.g., Welcome to HuniKita, {user_name}!" required>
                    <?= $errBox($err) ?>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Allowed Variables <span class="text-outline font-normal">(Optional)</span></label>
                    <?php $err = $getErr('variables'); ?>
                    <input type="text" name="variables" value="<?= old('variables', isset($template) ? $template->variables : '') ?>" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none <?= $errClass($err) ?>" placeholder="e.g., {user_name}, {verification_link}, {property_title}">
                    <p class="text-xs text-on-surface-variant mt-1">Comma-separated placeholders that the system will replace with actual data before sending.</p>
                    <?= $errBox($err) ?>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-on-surface mb-2">Email Body (HTML Supported) <span class="text-[#c9302c]">*</span></label>
                    <?php $err = $getErr('body'); ?>
                    <textarea name="body" rows="10" class="w-full p-3 border rounded-lg focus:ring-1 transition-all outline-none font-mono text-sm resize-y <?= $errClass($err) ?>" placeholder="<p>Hi {user_name},</p><p>Welcome to our platform!</p>" required><?= old('body', isset($template) ? $template->body : '') ?></textarea>
                    <?= $errBox($err) ?>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-outline-variant">
                    <a href="<?= base_url('admin/email-templates') ?>" class="px-6 py-2.5 font-medium border border-outline-variant text-on-surface rounded-lg hover:bg-surface-container-low transition-colors">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 font-medium bg-primary text-on-primary rounded-lg shadow hover:bg-opacity-90 transition-opacity">Save Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>