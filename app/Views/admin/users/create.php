<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
$getErr = function($field) { return session('errors.' . $field); };
$errClass = function($err) { return $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'border-outline-variant focus:border-primary bg-surface'; };
$errBox = function($err) { return $err ? '<div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]"><span class="material-symbols-outlined text-[16px] mt-0.5">warning</span>'.esc($err).'</div>' : ''; };
?>

<div class="mt-4 mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="<?= base_url('admin/users') ?>" class="text-on-surface-variant hover:text-primary transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-2xl font-bold text-on-surface">Create New User</h1>
    </div>
    <p class="text-on-surface-variant ml-9">Manually register a new Administrator, Agent, or System User.</p>
</div>

<div class="bg-surface border border-outline-variant rounded-lg p-6 max-w-3xl">
    <form action="<?= base_url('admin/users/store') ?>" method="POST" novalidate class="flex flex-col gap-5">
        
        <?php if (session()->has('errors')): ?>
            <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 mb-2 rounded shadow-sm text-sm">
                <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">First Name <span class="text-[#c9302c]">*</span></label>
                <?php $err = $getErr('first_name'); ?>
                <input name="first_name" type="text" value="<?= old('first_name') ?>" required class="w-full px-4 py-2 border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                <?= $errBox($err) ?>
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Last Name <span class="text-[#c9302c]">*</span></label>
                <?php $err = $getErr('last_name'); ?>
                <input name="last_name" type="text" value="<?= old('last_name') ?>" required class="w-full px-4 py-2 border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                <?= $errBox($err) ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Email Address <span class="text-[#c9302c]">*</span></label>
                <?php $err = $getErr('email'); ?>
                <input name="email" type="email" value="<?= old('email') ?>" required class="w-full px-4 py-2 border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                <?= $errBox($err) ?>
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Phone Number</label>
                <?php $err = $getErr('phone_number'); ?>
                <input name="phone_number" type="tel" value="<?= old('phone_number') ?>" class="w-full px-4 py-2 border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                <?= $errBox($err) ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Password <span class="text-[#c9302c]">*</span></label>
                <?php $err = $getErr('password'); ?>
                <input name="password" type="password" required class="w-full px-4 py-2 border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                <?php if(!$err): ?><p class="text-xs text-on-surface-variant mt-1">Must be at least 8 characters.</p><?php endif; ?>
                <?= $errBox($err) ?>
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Assign System Role <span class="text-[#c9302c]">*</span></label>
                <?php $err = $getErr('role_id'); ?>
                <select name="role_id" required class="w-full px-4 py-2 border rounded focus:ring-1 outline-none appearance-none <?= $errClass($err) ?>">
                    <option value="" disabled <?= old('role_id') ? '' : 'selected' ?>>Select a Role...</option>
                    <?php if(!empty($roles)): foreach($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>><?= esc($role['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <?= $errBox($err) ?>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-outline-variant">
            <a href="<?= base_url('admin/users') ?>" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Create User Account</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>