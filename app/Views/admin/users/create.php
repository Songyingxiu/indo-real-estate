<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto mt-4">
    <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors text-sm font-semibold mb-6">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Users List
    </a>

    <h2 class="text-2xl font-bold text-on-surface mb-2">Create New User</h2>
    <p class="text-on-surface-variant mb-8">Add a new user to the platform.</p>

    <form action="<?= base_url('admin/users/store') ?>" method="POST" class="bg-surface border border-outline-variant rounded-lg p-6 md:p-8">
        
        <h3 class="text-lg font-semibold text-on-surface border-b border-outline-variant pb-2 mb-6">Personal Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Full Name</label>
                <input type="text" name="full_name" required placeholder="e.g. Budi Santoso" class="w-full p-3 border border-outline-variant rounded bg-surface">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="budi.santoso@example.com" class="w-full p-3 border border-outline-variant rounded bg-surface">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Phone Number</label>
                <input type="text" name="phone" placeholder="+62 812-3456-7890" class="w-full p-3 border border-outline-variant rounded bg-surface">
            </div>
        </div>

        <h3 class="text-lg font-semibold text-on-surface border-b border-outline-variant pb-2 mb-6">System Access</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold mb-2">System Role</label>
                <select name="role_id" class="w-full p-3 border border-outline-variant rounded bg-surface">
                    <option value="" disabled selected>Select a role...</option>
                    <option value="4">Admin</option>
                    <option value="3">Agent</option>
                    <option value="2">Owner</option>
                    <option value="1">Buyer</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Account Status</label>
                <select name="status" class="w-full p-3 border border-outline-variant rounded bg-surface">
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                </select>
                <p class="text-xs text-outline mt-1">Suspended users cannot log in or access platform features.</p>
            </div>
        </div>

        <div class="bg-surface-container-low border border-outline-variant rounded p-4 flex items-start gap-3 mb-8">
            <span class="material-symbols-outlined text-primary mt-0.5">info</span>
            <div>
                <h4 class="font-semibold text-sm">Password Generation</h4>
                <p class="text-sm text-on-surface-variant">A temporary password will be automatically generated and sent to the user's email address upon saving.</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-outline-variant">
            <a href="<?= base_url('admin/users') ?>" class="px-6 py-2 text-on-surface-variant font-semibold hover:bg-surface-container-low rounded transition-colors">Cancel</a>
            <button type="submit" class="bg-primary text-on-primary px-8 py-2.5 rounded font-semibold hover:bg-opacity-90 transition-opacity shadow-sm">Save User</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>