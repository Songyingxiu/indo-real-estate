<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
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
    <form action="<?= base_url('admin/users/store') ?>" method="POST" class="flex flex-col gap-5">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">First Name <span class="text-error">*</span></label>
                <input name="first_name" type="text" value="<?= old('first_name') ?>" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <?= session('errors.first_name') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.first_name')).'</p>' : '' ?>
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Last Name <span class="text-error">*</span></label>
                <input name="last_name" type="text" value="<?= old('last_name') ?>" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <?= session('errors.last_name') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.last_name')).'</p>' : '' ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Email Address <span class="text-error">*</span></label>
                <input name="email" type="email" value="<?= old('email') ?>" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <?= session('errors.email') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.email')).'</p>' : '' ?>
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Phone Number</label>
                <input name="phone_number" type="tel" value="<?= old('phone_number') ?>" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <?= session('errors.phone_number') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.phone_number')).'</p>' : '' ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Password <span class="text-error">*</span></label>
                <input name="password" type="password" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <p class="text-xs text-on-surface-variant mt-1">Must be at least 8 characters.</p>
                <?= session('errors.password') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.password')).'</p>' : '' ?>
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Assign System Role <span class="text-error">*</span></label>
                <select name="role_id" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none">
                    <option value="" disabled <?= old('role_id') ? '' : 'selected' ?>>Select a Role...</option>
                    <?php if(!empty($roles)): ?>
                        <?php foreach($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>><?= esc($role['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?= session('errors.role_id') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.role_id')).'</p>' : '' ?>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-outline-variant">
            <a href="<?= base_url('admin/users') ?>" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Create User Account</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>